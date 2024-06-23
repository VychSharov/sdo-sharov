<script setup>
import { ref, onMounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import NavLayout from '@/Layouts/NavLayout.vue';
import axios from 'axios';

defineProps({ errors: Object });

let course_title = ref('');
let course_thumbnail = ref('');
// let course_instructor = ref('');
// let course_price = ref('');
let course_overview = ref('');

let error = ref({
    course_title: null,
    course_thumbnail: null,
    // course_instructor: null,
    // course_price: null,
    course_overview: null,
});

const episodes = ref([
    {
        thumbnail: null,
        video: null,
        title: '',
        description: '',
        topics: [],
    },
]);

const tests = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get('/api/tests');
        tests.value = response.data;
    } catch (error) {
        console.error('Error fetching tests:', error);
    }
});

const addCourse = () => {
    let err = false;

    error.value.course_title = null;
    error.value.course_thumbnail = null;
    // error.value.course_instructor = null;
    // error.value.course_price = null;
    error.value.course_overview = null;

    if (!course_title.value) {
        error.value.course_title = 'Please enter Course Title';
        err = true;
    }
    if (!course_thumbnail.value) {
        error.value.course_thumbnail = 'Please select a thumbnail';
        err = true;
    }
    // if (!course_instructor.value) {
    //     error.value.course_instructor = 'Please enter Course Instructor';
    //     err = true;
    // }

    // if (!course_price.value) {
    //     error.value.course_price = 'Please enter Course Price';
    //     err = true;
    // }

    if (!course_overview.value) {
        error.value.course_overview = 'Please enter Course Overview';
        err = true;
    }

    if (err) {
        return;
    }

    let data = new FormData();

    data.append('title', course_title.value);
    // data.append('instructor', course_instructor.value);
    data.append('thumbnail', course_thumbnail.value);
    // data.append('current_price', course_price.value);
    data.append('course_overview', course_overview.value);
    episodes.value = episodes.value.map((episode, index) => {
        data.append(`episodes[${index}][thumbnail]`, episode.thumbnail);
        data.append(`episodes[${index}][video]`, episode.video);
        data.append(`episodes[${index}][title]`, episode.title);
        data.append(`episodes[${index}][description]`, episode.description);

        episode.topics.forEach((topic, topicIndex) => {
            data.append(`episodes[${index}][topics][${topicIndex}][title]`, topic.title);

            topic.objects.forEach((object, objectIndex) => {
                data.append(`episodes[${index}][topics][${topicIndex}][objects][${objectIndex}][type]`, object.type);
                if (object.type === 'text') {
                    data.append(`episodes[${index}][topics][${topicIndex}][objects][${objectIndex}][content]`, object.content);
                } else if (object.type === 'file') {
                    data.append(`episodes[${index}][topics][${topicIndex}][objects][${objectIndex}][file]`, object.file);
                } else if (object.type === 'test') {
                    data.append(`episodes[${index}][topics][${topicIndex}][objects][${objectIndex}][test_id]`, object.test_id);
                } else if (object.type === 'task') {
                    data.append(`episodes[${index}][topics][${topicIndex}][objects][${objectIndex}][content]`, object.content);
                }
            });
        });

        return episode;
    });

    router.post('/addcourse', data);
};

const handleThumbnailChange = (index, e) => {
    const file = e.target.files[0];
    episodes.value[index].thumbnail = file;
};

const handleVideoChange = (index, e) => {
    const file = e.target.files[0];
    episodes.value[index].video = file;
};

const getImage = (e) => (course_thumbnail.value = e.target.files[0]);

const addEpisode = () => {
    episodes.value.push({
        thumbnail: null,
        video: null,
        title: '',
        description: '',
        topics: [],
    });
};

const removeEpisode = () => {
    if (episodes.value.length > 0) {
        episodes.value.splice(episodes.value.length - 1, 1);
    }
};

const addTopic = (episodeIndex) => {
    episodes.value[episodeIndex].topics.push({ title: '', objects: [] });
};

const removeTopic = (episodeIndex, topicIndex) => {
    episodes.value[episodeIndex].topics.splice(topicIndex, 1);
};

const addObject = (episodeIndex, topicIndex, type) => {
    const newObject = { type, content: '', file: null, test_id: null };
    episodes.value[episodeIndex].topics[topicIndex].objects.push(newObject);
};

const removeObject = (episodeIndex, topicIndex, objectIndex) => {
    episodes.value[episodeIndex].topics[topicIndex].objects.splice(objectIndex, 1);
};

const handleFileChange = (episodeIndex, topicIndex, objectIndex, e) => {
    const file = e.target.files[0];
    episodes.value[episodeIndex].topics[topicIndex].objects[objectIndex].file = file;
};
</script>

<template>

    <Head title="Add Course" />

    <NavLayout>
        <main class="container mx-auto py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-3 bg-[#1f467a] rounded-lg p-6 flex">
                    <h2 class="text-white font-extrabold">Создать ноывй курс</h2>
                </div>
                <div class="md:col-span-3 bg-white rounded-lg p-6 flex ">
                    <form class="w-full" @submit.prevent="addCourse">
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-[#1f467a] text-base font-bold mb-2"
                                    for="course-title">
                                    Название
                                    <span class="text-red-600">*</span>
                                </label>
                                <input
                                    class="appearance-none block w-full bg-gray-200 text-black border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                                    id="course-title" type="text" placeholder="Название курса" v-model="course_title" />
                                <p v-if="error.course_title" class="text-red-500 text-xs italic">
                                    {{ error.course_title }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full px-3">
                                <label class="block uppercase tracking-wide text-[#1f467a] text-base font-bold mb-2"
                                    for="course-overview">
                                    Описание курса
                                    <span class="text-red-600">*</span>
                                </label>
                                <textarea v-model="course_overview" id="course-overview"
                                    class="form-control block w-full px-3 py-3 text-black bg-gray-200 border border-gray-300 rounded leading-tight focus:outline-none focus:bg-white"
                                    rows="5" placeholder="Описание курса"></textarea>
                                <p v-if="error.course_overview" class="text-red-500 text-xs italic">
                                    {{ error.course_overview }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-[#1f467a] text-base font-bold mb-2"
                                    for="course-thumbnail">
                                    Обложка
                                    <span class="text-red-600">*</span>
                                </label>
                                <input
                                    class="appearance-none block w-full bg-gray-200 text-black border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                                    id="course-thumbnail" type="file" @change="getImage" />
                                <p v-if="error.course_thumbnail" class="text-red-500 text-xs italic">
                                    {{ error.course_thumbnail }}
                                </p>
                            </div>
                        </div>

                        <!-- Episode List -->
                        <div v-for="(episode, episodeIndex) in episodes" :key="episodeIndex"
                            class="mb-6 bg-white p-6 rounded-lg border-2 border-gray-300">
                            <div class="flex flex-wrap -mx-3 mb-6 ">
                                <div class="w-full px-3">
                                    <label
                                        class="block uppercase tracking-wide text-[#1f467a] text-base font-bold mb-2 "
                                        for="episode-title">
                                        Название Модуля
                                        <span class="text-red-600">*</span>
                                    </label>
                                    <input v-model="episode.title" id="episode-title" type="text"
                                        placeholder="Название модуля"
                                        class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" />
                                </div>
                                <div class="w-full px-3">
                                    <label class="block uppercase tracking-wide text-[#1f467a] text-base font-bold mb-2"
                                        for="episode-description">
                                        Описание Модуля
                                        <span class="text-red-600">*</span>
                                    </label>
                                    <textarea v-model="episode.description" id="episode-description"
                                        class="form-control block w-full px-3 py-3 text-gray-700 bg-gray-200 border border-gray-300 rounded leading-tight focus:outline-none focus:bg-white"
                                        rows="3" placeholder="Описание модуля"></textarea>
                                </div>
                                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                    <label class="block uppercase tracking-wide text-[#1f467a] text-base font-bold mb-2"
                                        for="episode-thumbnail">
                                        Обложка Модуля
                                    </label>
                                    <input @change="handleThumbnailChange(episodeIndex, $event)" type="file"
                                        id="episode-thumbnail"
                                        class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" />
                                </div>
                                <!-- <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
                                    <label class="block uppercase tracking-wide text-white text-base font-bold mb-2"
                                        for="episode-video">
                                        Видео Эпизода
                                    </label>
                                    <input @change="handleVideoChange(episodeIndex, $event)" type="file"
                                        id="episode-video"
                                        class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white" />
                                </div> -->
                            </div>

                            <!-- Topics -->
                            <div v-for="(topic, topicIndex) in episode.topics" :key="topicIndex" class="mb-4">
                                <div class="flex items-center mb-2">
                                    <label
                                        class="block uppercase tracking-wide text-[#1f467a] text-base font-bold mb-2 mr-2">
                                        Тема {{ topicIndex + 1 }}
                                    </label>
                                    <button type="button" @click="removeTopic(episodeIndex, topicIndex)"
                                        class="bg-red-600 text-white py-1 px-3 rounded ml-auto">
                                        Удалить тему
                                    </button>
                                </div>
                                <input v-model="topic.title" type="text" placeholder="Название темы"
                                    class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-2 px-3 mb-2 leading-tight focus:outline-none focus:bg-white" />

                                <!-- Objects -->
                                <div v-for="(object, objectIndex) in topic.objects" :key="objectIndex" class="mb-2">
                                    <div class="flex items-center mb-1">
                                        <label
                                            class="block uppercase tracking-wide text-[#1f467a] text-base font-bold mb-2 mr-2">
                                            Объект {{ objectIndex + 1 }}
                                        </label>
                                        <button type="button"
                                            @click="removeObject(episodeIndex, topicIndex, objectIndex)"
                                            class="bg-red-600 text-white py-1 px-3 rounded ml-auto">
                                            Удалить объект
                                        </button>
                                    </div>
                                    <div v-if="object.type === 'text'" class="mb-2">
                                        <textarea v-model="object.content" placeholder="Введите текст"
                                            class="form-control block w-full px-3 py-2 text-gray-700 bg-gray-200 border border-gray-300 rounded leading-tight focus:outline-none focus:bg-white"
                                            rows="2"></textarea>
                                    </div>
                                    <div v-if="object.type === 'file'" class="mb-2">
                                        <input @change="handleFileChange(episodeIndex, topicIndex, objectIndex, $event)"
                                            type="file"
                                            class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-2 px-3 leading-tight focus:outline-none focus:bg-white" />
                                    </div>
                                    <div v-if="object.type === 'test'" class="mb-2">
                                        <label class="block uppercase tracking-wide text-white text-xs font-bold mb-2">
                                            Выберите тест
                                        </label>
                                        <select v-model="object.test_id"
                                            class="form-control block w-full px-3 py-2 text-gray-700 bg-gray-200 border border-gray-300 rounded leading-tight focus:outline-none focus:bg-white">
                                            <option value="" disabled>Выберите тест</option>
                                            <option v-for="test in tests" :value="test.id" :key="test.id">{{
                                                test.test_name }}</option>
                                        </select>
                                    </div>
                                    <div v-if="object.type === 'task'" class="mb-2">
                                        <label
                                            class="block uppercase tracking-wide text-white text-xs font-bold mb-2 ">Задание</label>
                                        <textarea v-model="object.content" placeholder="Задание"
                                            class="form-control block w-full px-3 py-2 text-gray-700 bg-gray-200 border border-gray-300 rounded leading-tight focus:outline-none focus:bg-white"
                                            rows="2"></textarea>
                                    </div>
                                </div>

                                <div class="flex mb-2">
                                    <button type="button" @click="addObject(episodeIndex, topicIndex, 'text')"
                                        class="bg-blue-600 text-white py-1 px-3 rounded mr-2">
                                        Добавить текст
                                    </button>
                                    <button type="button" @click="addObject(episodeIndex, topicIndex, 'file')"
                                        class="bg-blue-600 text-white py-1 px-3 rounded mr-2">
                                        Добавить файл
                                    </button>
                                    <button type="button" @click="addObject(episodeIndex, topicIndex, 'test')"
                                        class="bg-blue-600 text-white py-1 px-3 rounded mr-2">
                                        Добавить тест
                                    </button>
                                    <button type="button" @click="addObject(episodeIndex, topicIndex, 'task')"
                                        class="bg-blue-600 text-white py-1 px-3 rounded mr-2">
                                        Добавить задание
                                    </button>
                                </div>
                            </div>
                            <button type="button" @click="addTopic(episodeIndex)"
                                class="bg-green-600 text-white py-1 px-3 rounded">
                                Добавить тему
                            </button>
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <button type="button" @click="addEpisode" class="bg-green-600 text-white py-2 px-4 rounded">
                                Добавить модуль
                            </button>
                            <button type="button" @click="removeEpisode(episodeIndex)"
                                class="bg-red-600 text-white py-2 px-4 rounded">
                                Удалить модуль
                            </button>
                        </div>

                        <div class="mt-8">
                            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">
                                Сохранить курс
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </NavLayout>
</template>
