<template>
    <Head title="View Course" />
    <NavLayout>
        <div class="container mx-auto py-8">
            <div class="bg-white rounded-lg p-6">
                <h1 class="text-4xl font-extrabold text-[#1f467a]">{{ course.title }}</h1>
                <img :src="course.thumbnail" alt="Course Thumbnail" class="mt-4 w-full rounded-lg" />
            </div>

            <div v-for="episode in course.episodes" :key="episode.id" class="mt-8 border-2 border-gray-300 p-6 rounded-lg">
                <div class="bg-white rounded-lg p-6 ">
                    <h2 class="text-3xl font-bold text-[#1f467a] ">{{ episode.episode_title }}</h2>
                    <div v-for="topic in episode.topics" :key="topic.id" class="mt-4 ">
                        <h3 class="text-2xl font-semibold text-[#1f467a]  ">{{ topic.topic_title }}</h3>
                        <div class="text-center">
                            <table class="min-w-full mt-4 ">
                                <thead class="border-b">
                                    <tr class="text-black">
                                        <th class="text-sm font-medium px-6 py-4 text-xl">Студент</th>
                                        <th class="text-sm font-medium px-6 py-4 text-xl">Письменный ответ</th>
                                        <th class="text-sm font-medium px-6 py-4 text-xl">Файл</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="response in topic.responses" :key="response.id" class="border-b">
                                        <td class="px-6 py-4 text-sm text-black text-xl">{{ response.user.name }}</td>
                                        <td class="px-6 py-4 text-sm text-black text-xl">{{ response.response_text }}</td>
                                        <td class="px-6 py-4 text-sm text-black text-xl">
                                            <a :href="getFileUrl(response.file_path)" target="_blank" class="text-blue-400 underline" v-if="response.file_path">Скачать</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </NavLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import NavLayout from '@/Layouts/NavLayout.vue';

const props = defineProps({
    course: Object
});

// Function to get the full URL of the file
const getFileUrl = (filePath) => {
    return new URL(filePath, window.location.origin).href;
};
</script>

<style scoped>
.text-white {
    color: white;
}
.bg-gray-800 {
    background-color: #2d3748;
}
</style>
