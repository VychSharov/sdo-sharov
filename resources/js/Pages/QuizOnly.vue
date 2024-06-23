<template>
    <Head title="Тест" />

      <main class="container mx-auto py-8">
        <div class="bg-[#1f467a] rounded-lg p-6 mb-8">
          <h2 class="text-white font-extrabold">{{ test.test_name }}</h2>
        </div>
        <div class="test-details bg-white border border-gray-300 rounded-lg p-6">
          <h3 class="font-bold text-lg mb-2">{{ test.description }}</h3>
          <p>Количество попыток: {{ remainingAttempts(test.id) }}</p>
          <button @click="startQuiz(test.id)" class="text-white bg-blue-500 hover:bg-blue-600 py-2 px-4 rounded mb-4">
            Пройти
          </button>
        </div>
      </main>

  </template>

<script>
  export default {
    props: {
      test: Object,
      userAttempts: Array,
    },
    methods: {
      remainingAttempts(testId) {
        const test = this.test; // предполагается, что у вас есть только один тест
        const userAttempts = this.userAttempts.filter(attempt => attempt.test_id === testId).length;
        return test.attempts - userAttempts;
      },
      startQuiz(id) {
        const remaining = this.remainingAttempts(id);
        if (remaining > 0) {
          this.$inertia.get(`/quizzes/start/${id}`);
        } else {
          alert('Все попытки использованы.');
        }
      },
    },
  };
</script>


  <style scoped>
  .test-details {
    background-color: #f9fafb;
    border: 1px solid #e5e7eb;
    padding: 20px;
    border-radius: 8px;
  }
  button {
    margin-top: 10px;
    padding: 5px 10px;
    cursor: pointer;
  }
  </style>
