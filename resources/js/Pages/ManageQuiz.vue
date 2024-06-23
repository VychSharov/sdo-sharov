<script setup>
import { ref } from "vue";
import { Head, router } from "@inertiajs/vue3";
import NavLayout from "@/Layouts/NavLayout.vue";

const props = defineProps({ tests: Array });

const editingTest = ref(null);
const editingQuestion = ref(null);
const editingAnswer = ref(null);

const toggleTest = (testId) => {
    const test = props.tests.find(t => t.id === testId);
    if (test.isExpanded) {
        test.isExpanded = false;
    } else {
        props.tests.forEach(t => t.isExpanded = false); // свернуть все тесты
        test.isExpanded = true;
    }
};

const editTest = (test) => {
    editingTest.value = { ...test };
};

const saveTest = async (test) => {
    await axios.post(`/api/update-test/${test.id}`, {
        test_name: editingTest.value.test_name,
        description: editingTest.value.description,
        duration: editingTest.value.duration,
    });
    const index = props.tests.findIndex(t => t.id === test.id);
    props.tests[index] = { ...editingTest.value };
    editingTest.value = null;
};

const editQuestion = (question) => {
    editingQuestion.value = { ...question };
};

const saveQuestion = async (question, testId) => {
    await axios.post(`/api/update-question/${question.id}`, {
        question_text: editingQuestion.value.question_text,
    });
    const test = props.tests.find(t => t.id === testId);
    const questionIndex = test.questions.findIndex(q => q.id === question.id);
    test.questions[questionIndex].question_text = editingQuestion.value.question_text;
    editingQuestion.value = null;
};

const editAnswer = (answer) => {
    editingAnswer.value = { ...answer };
};

const saveAnswer = async (answer, questionId, testId) => {
    await axios.post(`/api/update-answer/${answer.id}`, {
        option_text: editingAnswer.value.option_text,
    });
    const test = props.tests.find(t => t.id === testId);
    const question = test.questions.find(q => q.id === questionId);
    const answerIndex = question.options.findIndex(a => a.id === answer.id);
    question.options[answerIndex].option_text = editingAnswer.value.option_text;
    editingAnswer.value = null;
};
</script>

<template>

    <Head title="Manage Quiz" />
    <NavLayout>
        <main class="container mx-auto py-8">
            <div class="grid grid-cols-1 gap-8">
                <div class="bg-gray-800 rounded-lg p-6">
                    <h2 class="text-white font-extrabold">Управление тестами</h2>
                </div>
                <div v-for="test in tests" :key="test.id"
                    class="test-item bg-white border border-gray-300 rounded-lg p-6">
                    <h3 class="font-bold text-lg mb-2">{{ test.test_name }}</h3>
                    <p class="mb-4">{{ test.description }}</p>
                    <p class="mb-4">Время: {{ test.duration }} минуты</p>
                    <button @click="toggleTest(test.id)"
                        class="text-white bg-blue-500 hover:bg-blue-600 py-2 px-4 rounded mb-4">
                        {{ test.isExpanded ? 'Свернуть' : 'Развернуть' }}
                    </button>
                    <button @click="editTest(test)"
                        class="text-white bg-yellow-500 hover:bg-yellow-600 py-1 px-2 rounded ml-2">Изменить</button>
                    <div v-if="editingTest && editingTest.id === test.id" class="mt-4">
                        <div>
                            <label for="test-name" class="block text-sm font-medium text-gray-700">Название
                                теста</label>
                            <input id="test-name" v-model="editingTest.test_name" class="border p-2 mb-4 w-full" />
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Описание</label>
                            <textarea id="description" v-model="editingTest.description"
                                class="border p-2 mb-4 w-full"></textarea>
                        </div>
                        <div>
                            <label for="duration" class="block text-sm font-medium text-gray-700">Длительность (в
                                минутах)</label>
                            <input id="duration" v-model="editingTest.duration" type="number"
                                class="border p-2 mb-4 w-full" />
                        </div>
                        <button @click="saveTest(test)"
                            class="text-white bg-green-500 hover:bg-green-600 py-2 px-4 rounded">Сохранить</button>
                    </div>
                    <div v-if="test.isExpanded" class="mt-4">
                        <div v-for="question in test.questions" :key="question.id" class="question-item mt-4">
                            <div v-if="editingQuestion && editingQuestion.id === question.id">
                                <input v-model="editingQuestion.question_text" class="border p-2 mb-2 w-full" />
                                <button @click="saveQuestion(question, test.id)"
                                    class="text-white bg-green-500 hover:bg-green-600 py-2 px-4 rounded">Сохранить</button>
                            </div>
                            <div v-else class="flex items-center justify-between">
                                <h4 class="font-semibold">{{ question.question_text }}</h4>
                                <button @click="editQuestion(question)"
                                    class="text-white bg-yellow-500 hover:bg-yellow-600 py-1 px-2 rounded">
                                    Изменить вопрос
                                </button>
                            </div>
                            <ul class="pl-4 list-disc">
                                <li v-for="option in question.options" :key="option.id">
                                    <div v-if="editingAnswer && editingAnswer.id === option.id">
                                        <input v-model="editingAnswer.option_text" class="border p-2 mb-2 w-full" />
                                        <button @click="saveAnswer(option, question.id, test.id)"
                                            class="text-white bg-green-500 hover:bg-green-600 py-2 px-4 rounded">Сохранить</button>
                                    </div>
                                    <div v-else class="flex items-center justify-between">
    <span>{{ option.option_text }}</span>
    <button @click="editAnswer(option)"
        class="text-white bg-yellow-500 hover:bg-yellow-600 py-1 px-2 rounded ml-2">
        Изменить ответ
    </button>
</div>


                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </NavLayout>
</template>

<style scoped>
.test-item {
    background-color: #f9fafb;
    /* Белый цвет фона для тестов */
    border: 1px solid #e5e7eb;
    /* Серая граница */
    margin-bottom: 20px;
    /* Отступ снизу */
    padding: 20px;
    /* Внутренний отступ */
    border-radius: 8px;
    /* Скругление углов */
}

.question-item {
    margin-left: 20px;
}

button {
    margin-top: 10px;
    padding: 5px 10px;
    cursor: pointer;
}
</style>
