<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3"; // Добавили useForm для работы с формами
import NavLayout from "@/Layouts/NavLayout.vue";
import Star from "vue-material-design-icons/Star.vue";
import StarHalfFull from "vue-material-design-icons/StarHalfFull.vue";

defineProps({
    course: Object,
    episodes: Array,
});

const form = useForm({
    course_id: null,
});

const enrollCourse = (courseId) => {
    form.course_id = courseId;
    form.post(route('courses.enroll', courseId), {
        onSuccess: () => {
            // Логика после успешной записи курса, если необходимо
        },
    });
};

const trimLongText = (string, length) => {
    if (string.length <= length) {
        return string;
    } else {
        const trimmedString = string.slice(0, length).trim();
        const trimmedStringWithEllipsis = trimmedString + "...";

        return trimmedStringWithEllipsis;
    }
};
</script>

<template>
    <Head :title="course.title" />
    <NavLayout>
        <main class="container mx-auto py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-2 bg-gray-800 rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4 text-white">
                        Описание курса
                    </h2>
                    <p class="text-white mb-4 flex items-center justify-center text-justify">
                        {{ course.overview }}
                    </p>
                </div>
                <div class="bg-gray-800 rounded-lg p-6">
                    <img :src="course.thumbnail" alt="Course Image" class="mb-4 rounded-lg" />
                    <h2 class="text-xl font-semibold mb-4 text-white">
                        {{ course.title }}
                    </h2>
                    <div class="px-1.5 text-white mt-1">
                        <div class="text-[14px] text-gray-300 font-extrabold flex gap-1 items-center cursor-pointer">
                            {{ course.instructor }}
                        </div>
                    </div>
                    <button @click="enrollCourse(course.id)"
                        class="mt-3 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-800 duration-300">
                        Записаться
                    </button>
                </div>
                <div class="md:col-span-3 bg-gray-800 rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4 text-white">
                        Список модулей
                    </h2>
                    <ul class="space-y-4">
                        <div v-for="episode in episodes" :key="episode">
                            <!-- <Link :href="route('course.watchepisode', { id: episode.id })" class="flex items-center justify-center cursor-pointer"> -->
                                <li class="w-full flex items-center space-x-4 hover:bg-gray-900 rounded-lg">
                                    <div class="flex-shrink-0">
                                        <img class="h-40 w-60 rounded-md" :src="episode.episode_thumbnail" alt="Video Thumbnail" />
                                    </div>
                                    <div class="flex-grow">
                                        <h3 class="text-lg font-semibold text-white">
                                            {{ episode.episode_title }}
                                        </h3>
                                        <p class="text-gray-400">
                                            {{ trimLongText(episode.episode_description, 150) }}
                                        </p>
                                    </div>
                                </li>
                            <!-- </Link> -->
                        </div>
                    </ul>
                </div>
            </div>
        </main>
    </NavLayout>
</template>
