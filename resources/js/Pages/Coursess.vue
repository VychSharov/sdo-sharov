<template>
    <div>
        <NavLayout>
            <main class="container mx-auto py-8">
                <h1 class="text-3xl font-semibold text-center mb-8">{{ course.title }}</h1>
                <div class="bg-white rounded-lg p-6 border-2 border-gray-300">
                    <div v-for="episode in episodes" :key="episode.id" class="mb-4">
                        <div class="flex items-center">
                            <img v-if="episode.episode_thumbnail" :src="episode.episode_thumbnail" alt="Episode Thumbnail" class="w-16 h-16 object-cover rounded mr-4">
                            <h2 @click="toggleEpisode(episode.id)" class="text-2xl font-semibold text-black cursor-pointer hover:underline">
                                {{ episode.episode_title }}
                            </h2>
                        </div>
                        <div v-if="episode.isOpen" class="pl-8 mt-2">
                            <div v-for="topic in episode.topics" :key="topic.id" @click="openTopic(topic.id)" class="text-black cursor-pointer hover:underline">
                                <h3 class="text-xl">{{ topic.topic_title }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <button @click="unenrollCourse(course.id)" class="mt-3 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-800 duration-300">
                    Отписаться
                </button>
            </main>
        </NavLayout>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { usePage, useForm, router } from '@inertiajs/vue3';
import NavLayout from '@/Layouts/NavLayout.vue';

const { props } = usePage();
const course = ref(props.course);
const episodes = ref(props.episodes);

const form = useForm({
    course_id: null,
});

const toggleEpisode = (episodeId) => {
    const episode = episodes.value.find(ep => ep.id === episodeId);
    episode.isOpen = !episode.isOpen;
};

const openTopic = (topicId) => {
    router.get(`/topics/${topicId}`);
};

const unenrollCourse = (courseId) => {
    form.delete(route('courses.unenroll', courseId), {
        onSuccess: () => {
            // Логика после успешной отписки от курса, например редирект
            router.get('/'); // Пример редиректа на главную страницу или другую
        },
    });
};
</script>
