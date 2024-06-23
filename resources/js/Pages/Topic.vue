<template>
    <NavLayout>
        <main class="container mx-auto py-8">
            <button @click="goBack" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-800 duration-300 mb-4">
                Назад
            </button>
            <h1 class="text-3xl font-semibold text-center mb-8">{{ topic.topic_title }}</h1>
            <div class="bg-white rounded-lg p-6">
                <div v-for="object in topic.objects" :key="object.id" class="mb-4">
                    <div v-if="object.type === 'text'" class="text-black mb-4">
                        <p>{{ object.content }}</p>
                    </div>
                    <div v-else-if="object.type === 'file'" class="text-black mb-4">
                        <template v-if="isVideo(object.file_path)">
                            <video controls class="scaled-media">
                                <source :src="object.file_path" type="video/mp4">
                                Ваш браузер не подходит
                            </video>
                        </template>
                        <template v-else-if="isImage(object.file_path)">
                            <img :src="object.file_path" alt="Image" class="scaled-media">
                        </template>
                        <template v-else>
                            <a :href="object.file_path" class="underline text-blue-500" target="_blank">Скачать материал</a>
                        </template>
                    </div>
                    <div v-else-if="object.type === 'test'" class="text-black mb-4">
                        <a :href="`/quiz/${object.test.id}`" class="underline text-blue-500 hover:text-blue-300">
                            {{ object.test.test_name }}
                        </a>
                    </div>
                    <div v-else-if="object.type === 'task'" class="mt-8">
                        <form @submit.prevent="submitResponse" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label for="response_text" class="block text-black mb-2">Ответ:</label>
                                <textarea v-model="responseText" id="response_text" class="w-full p-2 rounded-lg"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="file" class="block text-black mb-2">Загрузить файл:</label>
                                <input type="file" id="file" @change="handleFileUpload" class="text-black" />
                            </div>
                            <button type="submit" class="bg-green-600 text-black px-4 py-2 rounded-lg hover:bg-green-800 duration-300">Отправить</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </NavLayout>
</template>

<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import NavLayout from '@/Layouts/NavLayout.vue';
import axios from 'axios';

const { props } = usePage();
const topic = ref(props.topic);

const responseText = ref('');
const responseFile = ref(null);

const isVideo = (filePath) => /\.(mp4|webm|ogg)$/i.test(filePath);

const isImage = (filePath) => /\.(jpeg|jpg|gif|png|svg|bmp)$/i.test(filePath);

const goBack = () => window.history.back();

const handleFileUpload = (event) => {
    responseFile.value = event.target.files[0];
};

const submitResponse = () => {
    const formData = new FormData();
    formData.append('response_text', responseText.value);
    if (responseFile.value) {
        formData.append('file', responseFile.value);
    }

    axios.post(`/topics/${topic.value.id}/submit-response`, formData)
        .then(response => {
            // Handle successful submission
            console.log('Response submitted successfully');
        })
        .catch(error => {
            // Handle error
            console.error('Error submitting response:', error);
        });
};
</script>

<style scoped>
.scaled-media {
    max-width: 50%;
    height: auto;
    display: block;
    margin: 0 auto;
}
</style>
