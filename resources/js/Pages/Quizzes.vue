<script setup>
import { Head, Link } from "@inertiajs/vue3";
import NavLayout from "@/Layouts/NavLayout.vue";
import CourseCard from "@/Components/CourseCard.vue";

</script>

<template>
    <Head title="Список тестов" />
    <NavLayout>
      <main class="container mx-auto py-8">
        <div class="grid grid-cols-1 gap-8">
          <div class="bg-[#1f467a] rounded-lg p-6">
            <h2 class="text-white font-extrabold">Список тестов</h2>
          </div>
          <div v-for="test in tests" :key="test.id" class="test-item bg-white border border-gray-300 rounded-lg p-6">
            <h3 class="font-bold text-lg mb-2">{{ test.test_name }}</h3>
            <!-- <p>Количество попыток: {{ remainingAttempts(test.id) }}</p> -->
            <button @click="startQuiz(test.id)" class="text-white bg-blue-500 hover:bg-blue-600 py-2 px-4 rounded mb-4">
              Открыть
            </button>
          </div>
        </div>
      </main>
    </NavLayout>
  </template>

  <script>
  import { Head } from '@inertiajs/vue3';
  import NavLayout from '@/Layouts/NavLayout.vue';

  export default {
    props: {
      tests: Array,
      userAttempts: Array,
    },
    methods: {
    //   remainingAttempts(testId) {
    //     const test = this.tests.find(test => test.id === testId);
    //     const userAttempts = this.userAttempts.filter(attempt => attempt.test_id === testId).length;
    //     return test.attempts - userAttempts;
    //   },
      startQuiz(id) {
        // const remaining = this.remainingAttempts(id);
        // if (remaining > 0) {
          this.$inertia.get(`/quiz/${id}`);
        // } else {
        //   alert('Все попытки использованы.');
        // }
      },
    },
  };
  </script>

  <style scoped>
  .test-item {
    background-color: #f9fafb;
    border: 1px solid #e5e7eb;
    margin-bottom: 20px;
    padding: 20px;
    border-radius: 8px;
  }
  button {
    margin-top: 10px;
    padding: 5px 10px;
    cursor: pointer;
  }
  </style>
