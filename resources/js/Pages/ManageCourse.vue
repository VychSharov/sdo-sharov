<script setup>
import { Head, Link } from "@inertiajs/vue3";
import NavLayout from "@/Layouts/NavLayout.vue";

defineProps({ courses: Array });
</script>

<template>
    <Head title="Manage Courses" />
    <NavLayout>
        <main class="container mx-auto py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-3 bg-[#1f467a] rounded-lg p-6 flex">
                    <h2 class="text-white font-extrabold">Управление курса</h2>
                </div>

                <div class="md:col-span-3 bg-white rounded-lg p-6">
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <table class="min-w-full">
                            <thead class="border-b border-blue-500">
                                <tr class="text-black flex justify-around">
                                    <th
                                        scope="col"
                                        class="text-sm font-medium px-6 py-4"
                                    >
                                        Курс
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm font-medium px-6 py-4"
                                    >
                                        Название курса
                                    </th>
                                    <!-- <th
                                        scope="col"
                                        class="text-sm font-medium px-6 py-4"
                                    >
                                        Course Instructor
                                    </th> -->
                                    <th
                                        scope="col"
                                        class="text-sm font-medium px-6 py-4"
                                    >
                                        Действия
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <div v-for="course in courses" :key="course">
                                    <Link
                                        :href="
                                            route('course.view', {
                                                id: course.id,
                                            })
                                        "
                                    >
                                        <tr
                                            class="border-b border-blue-500 flex items-center justify-center hover:bg-gray-200 rounded-sm"
                                        >
                                            <td
                                                class="px-6 py-4 w-4/12 text-sm font-medium text-black"
                                            >
                                                <img
                                                    class="transition ease-in-out delay-150 rounded-lg"
                                                    :src="
                                                        course.thumbnail || ''
                                                    "
                                                />
                                            </td>
                                            <td
                                                class="px-6 py-4 w-7/12 text-sm font-medium text-black text-center"
                                            >
                                                {{ course.title }}
                                            </td>
                                            <!-- <td
                                                class="px-6 py-4 w-7/12 text-sm font-medium text-black"
                                            >
                                                {{ course.instructor }}
                                            </td> -->
                                            <td
                                                class="px-6 py-4 w-5/12 text-sm font-medium text-black relative text-center"
                                            >
                                                <div class="w-full">
                                                    <Link
                                                        :href="
                                                            route(
                                                                'course.destroy',
                                                                {
                                                                    id: course.id,
                                                                }
                                                            )
                                                        "
                                                        as="button"
                                                        method="delete"
                                                        class="text-white py-3 text-sm bg-red-600 hover:bg-red-800 duration-300 font-bold px-3 rounded cursor-pointer"
                                                    >
                                                        Удалить
                                                    </Link>
                                                </div>
                                            </td>
                                        </tr>
                                    </Link>
                                </div>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </NavLayout>
</template>
