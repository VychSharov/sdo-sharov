<script setup>
import { Head } from "@inertiajs/vue3";
import NavLayout from "@/Layouts/NavLayout.vue";

defineProps({ student: Array });
</script>
<template>
    <Head title="Детали студента" />
    <NavLayout>
        <main class="container mx-auto py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-4 bg-[#1f467a] rounded-lg p-6 flex">
                    <!-- <img
                        class="rounded-full"
                        width="35"
                        src="/images/astronaut.png"
                    /> -->
                    <h2 class="text-white ml-2 font-extrabold py-1">
                        Инфомрация студента: {{ student.name }}
                    </h2>
                </div>
                <div class="md:col-span-2 bg-gray-200 rounded-lg p-6 flex">
                    <div class="mr-4">
                        <p class="text-black font-semibold">Имя:</p>
                        <p class="text-black">{{ student.name }}</p>

                        <p class="text-black font-semibold mt-3">Электронная почта:</p>
                        <p class="text-black">{{ student.email }}</p>

                        <p class="text-black font-semibold mt-3">
                            Акккаунт создан:
                        </p>
                        <p class="text-black">{{ student.created_at }}</p>
                    </div>
                </div>
                <div class="md:col-span-2 bg-gray-200 rounded-lg p-6 flex">
                    <div class="mr-4">
                        <p class="text-black font-semibold">ID аккаунта:</p>
                        <p class="text-black">{{ student.id }}</p>
                        <p class="text-black font-semibold mt-3">Роль:</p>
                        <p class="text-black">{{ student.role }}</p>
                        <!-- <p class="text-black font-semibold mt-3">Auth Type:</p>
                        <p class="text-black">{{ student.auth_type }}</p> -->
                    </div>
                </div>
                <!-- <div class="md:col-span-4 bg-gray-800 rounded-lg p-6 flex">
                    <h4 class="text-white ml-2 font-extrabold py-1">
                        Courses Enrolled
                    </h4>
                </div> -->
            </div>
        </main>
    </NavLayout>
</template>
