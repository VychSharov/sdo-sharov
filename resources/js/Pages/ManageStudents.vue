<script setup>
import { Head, Link } from "@inertiajs/vue3";
import NavLayout from "@/Layouts/NavLayout.vue";

defineProps({ students: Array });
</script>
<template>
    <Head title="Студенты" />
    <NavLayout>
        <main class="container mx-auto py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-3 bg-[#1f467a] rounded-lg p-6 flex">
                    <!-- <img
                        class="rounded-full"
                        width="35"
                        src="/images/astronaut.png"
                    /> -->
                    <h2 class="text-white ml-2 font-extrabold py-1">
                        Управление студентами
                    </h2>
                </div>

                <div class="md:col-span-3 bg-white rounded-lg p-6 border-2 border-gray-300 ">
                    <div class="flex flex-wrap mx-3 mb-6">
                        <table class="min-w-full">
                            <thead class="border-b border-slate-600">
                                <tr class="text-black flex justify-around">
                                    <th
                                        scope="col"
                                        class="text-sm font-medium px-2 py-4"
                                    >
                                        ID студента
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm font-medium px-2 py-4"
                                    >
                                        Имя студента
                                    </th>
                                    <th
                                        scope="col"
                                        class="text-sm font-medium px-2 py-4"
                                    >
                                        Электроная почта
                                    </th>
                                    <!-- <th
                                        scope="col"
                                        class="text-sm font-medium px-2 py-4"
                                    >
                                        Auth Type
                                    </th> -->
                                    <th
                                        scope="col"
                                        class="text-sm font-medium px-2 py-4"
                                    >
                                        Просмотреть
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <div v-for="student in students" :key="student">
                                    <tr
                                        class="border-b border-slate-600 hover:bg-gray-300 rounded-sm text-black text-sm font-medium"
                                    >
                                        <td class="py-4 ml-48 w-96 text-center ">
                                            {{ student.id }}
                                        </td>
                                        <td class="py-4 ml-48 w-96 text-center ">
                                            {{ student.name }}
                                        </td>
                                        <td class="py-4 ml-48 w-96 text-center ">
                                            {{ student.email }}
                                        </td>
                                        <!-- <td class="py-4 w-3/12">
                                            {{ student.auth_type }}
                                        </td> -->
                                        <td class="py-4 w-96 ">
                                            <Link
                                                :href="
                                                    route('student.show', {
                                                        id: student.id,
                                                    })
                                                "
                                            >
                                                <button
                                                    :data-modal-target="
                                                        'defaultModal' +
                                                        student.id
                                                    "
                                                    :data-modal-toggle="
                                                        'defaultModal' +
                                                        student.id
                                                    "
                                                    class="ml-40 block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm mr-4 px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 duration-300 dark:focus:ring-blue-800"
                                                    type="button"
                                                >
                                                    Посмотреть
                                                </button>
                                            </Link>
                                        </td>
                                    </tr>
                                </div>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </NavLayout>
</template>
