<script setup>
import { ref } from "vue";
import { Head, router } from "@inertiajs/vue3";
import NavLayout from "@/Layouts/NavLayout.vue";

defineProps({ errors: Object });

let test_name = ref("");
let description = ref("");
let duration = ref("");
let attempts = ref(""); // Количество попыток
let question_text = ref("");

let gradePercentages = ref({
    grade_3: 60,
    grade_4: 80,
    grade_5: 90
});

let error = ref({
    test_name: null,
    description: null,
    duration: null,
    attempts: null, // Ошибка для количества попыток
    question_text: null,
    grade_3: null,
    grade_4: null,
    grade_5: null
});

const questions = ref([
    {
        question_text: "",
        type: "single", // default question type
        options: [
            { option_text: "", correct: false }
        ]
    }
])

const addQuiz = () => {
    let err = false;

    error.value.test_name = null;
    error.value.description = null;
    error.value.duration = null;
    error.value.attempts = null; // Сброс ошибки количества попыток
    error.value.question_text = null;
    error.value.grade_3 = null;
    error.value.grade_4 = null;
    error.value.grade_5 = null;

    if (!test_name.value) {
        error.value.test_name = "Please enter Quiz Title";
        err = true;
    }

    if (!description.value) {
        error.value.description = "Please enter Quiz Description";
        err = true;
    }

    if (!duration.value || isNaN(duration.value) || duration.value <= 0) {
        error.value.duration = "Please enter a valid duration in minutes";
        err = true;
    }

    if (!attempts.value || isNaN(attempts.value) || attempts.value <= 0) {
        error.value.attempts = "Please enter a valid number of attempts";
        err = true;
    }

    if (!gradePercentages.value.grade_3 || isNaN(gradePercentages.value.grade_3) || gradePercentages.value.grade_3 < 0 || gradePercentages.value.grade_3 > 100) {
        error.value.grade_3 = "Please enter a valid percentage for grade 3 (0-100)";
        err = true;
    }

    if (!gradePercentages.value.grade_4 || isNaN(gradePercentages.value.grade_4) || gradePercentages.value.grade_4 < 0 || gradePercentages.value.grade_4 > 100) {
        error.value.grade_4 = "Please enter a valid percentage for grade 4 (0-100)";
        err = true;
    }

    if (!gradePercentages.value.grade_5 || isNaN(gradePercentages.value.grade_5) || gradePercentages.value.grade_5 < 0 || gradePercentages.value.grade_5 > 100) {
        error.value.grade_5 = "Please enter a valid percentage for grade 5 (0-100)";
        err = true;
    }

    if (err) {
        return;
    }

    let data = new FormData();

    data.append("test_name", test_name.value);
    data.append("description", description.value);
    data.append("duration", duration.value);
    data.append("attempts", attempts.value); // Добавление количества попыток
    data.append("grade_3", gradePercentages.value.grade_3);
    data.append("grade_4", gradePercentages.value.grade_4);
    data.append("grade_5", gradePercentages.value.grade_5);
    questions.value.forEach((question, qIndex) => {
        data.append(`questions[${qIndex}][question_text]`, question.question_text);
        data.append(`questions[${qIndex}][type]`, question.type);
        question.options.forEach((option, oIndex) => {
            data.append(`questions[${qIndex}][options][${oIndex}][option_text]`, option.option_text);
            data.append(`questions[${qIndex}][options][${oIndex}][correct]`, option.correct ? 'true' : 'false');
        });
    });

    router.post("/addquiz", data);
};

const addQuestion = () => {
    questions.value.push({
        question_text: "",
        type: "single",
        options: [
            { option_text: "", correct: false }
        ]
    });
};

const removeQuestion = (index) => {
    questions.value.splice(index, 1);
};

const addOption = (qIndex) => {
    questions.value[qIndex].options.push({
        option_text: "",
        correct: false
    });
};

const removeOption = (qIndex, oIndex) => {
    questions.value[qIndex].options.splice(oIndex, 1);
};
</script>

<template>

    <Head title="Создать тест" />
    <NavLayout>
        <main class="container mx-auto py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-3 bg-[#1f467a] rounded-lg p-6 flex ">
                    <h2 class="text-white font-extrabold">Создать новый тест</h2>
                </div>

                <div class="md:col-span-3 bg-white rounded-lg p-6 flex border-2 border-gray-300">
                    <form class="w-full" @submit.prevent="addQuiz">
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-[#1f467a] text-base font-bold mb-2 "
                                    for="grid-first-name">
                                    Название теста
                                    <span class="text-red-800">*</span>
                                </label>
                                <input
                                    class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                                    id="grid-first-name" type="text" placeholder="Название теста" v-model="test_name" />
                                <p v-if="error.test_name" class="text-red-500 text-xs italic">
                                    {{ error.test_name }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full px-3">
                                <label class="block uppercase tracking-wide text-[#1f467a] text-base font-bold mb-2"
                                    for="grid-password">
                                    Описание теста
                                    <span class="text-red-600">*</span>
                                </label>
                                <textarea v-model="description" type="text"
                                    class="form-control block w-full px-5 py-1.5 text-xl font-normal text-gray-700 bg-gray-200 placeholder-gray-700 bg-clip-padding border border-solid border-gray-600 rounded transition ease-in-out m-0 border-transparent focus:ring-0">
                                </textarea>
                                <p v-if="error.description" class="text-red-500 text-xs italic">
                                    {{ error.description }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-[#1f467a] text-base font-bold mb-2"
                                    for="grid-duration">
                                    Продолжительность теста (минуты)
                                    <span class="text-red-600">*</span>
                                </label>
                                <input
                                    class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                                    id="grid-duration" type="number" placeholder="10 минут" v-model="duration" />
                                <p v-if="error.duration" class="text-red-500 text-xs italic">
                                    {{ error.duration }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-[#1f467a] text-base font-bold mb-2"
                                    for="grid-attempts">
                                    Количество попыток
                                    <span class="text-red-600">*</span>
                                </label>
                                <input
                                    class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                                    id="grid-attempts" type="number" placeholder="Попытки" v-model="attempts" />
                                <p v-if="error.attempts" class="text-red-500 text-xs italic">
                                    {{ error.attempts }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-[#1f467a] text-base font-bold mb-2"
                                    for="grid-grade-3">
                                    Процент правильных ответов для оценки "3"
                                    <span class="text-red-600">*</span>
                                </label>
                                <input
                                    class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                                    id="grid-grade-3" type="number" placeholder="60"
                                    v-model="gradePercentages.grade_3" />
                                <p v-if="error.grade_3" class="text-red-500 text-xs italic">
                                    {{ error.grade_3 }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-[#1f467a] text-base font-bold mb-2"
                                    for="grid-grade-4">
                                    Процент правильных ответов для оценки "4"
                                    <span class="text-red-600">*</span>
                                </label>
                                <input
                                    class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                                    id="grid-grade-4" type="number" placeholder="80"
                                    v-model="gradePercentages.grade_4" />
                                <p v-if="error.grade_4" class="text-red-500 text-xs italic">
                                    {{ error.grade_4 }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <div class="w-full px-3 mb-6 md:mb-0">
                                <label class="block uppercase tracking-wide text-[#1f467a] text-base font-bold mb-2"
                                    for="grid-grade-5">
                                    Процент правильных ответов для оценки "5"
                                    <span class="text-red-600">*</span>
                                </label>
                                <input
                                    class="appearance-none block w-full bg-gray-200 text-gray-700 border rounded py-3 px-4 mb-3 leading-tight focus:outline-none focus:bg-white"
                                    id="grid-grade-5" type="number" placeholder="90"
                                    v-model="gradePercentages.grade_5" />
                                <p v-if="error.grade_5" class="text-red-500 text-xs italic">
                                    {{ error.grade_5 }}
                                </p>
                            </div>
                        </div>

                        <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700" />
                        <div class="flex flex-wrap -mx-3 mb-6">
                            <button @click="addQuestion()" type="button"
                                class="mb-2 text-white bg-green-600 hover:bg-blue-600 py-2 px-4 rounded">
                                Добавить вопрос
                            </button>
                            <div class="w-full px-3 rounded-sm mt-3">
                                <div v-if="questions.length > 0">
                                    <!-- Question list header -->
                                </div>
                                <div v-for="(question, qIndex) in questions" :key="qIndex"
                                    class="flex items-center justify-between border-b py-2">
                                    <div class="w-1/12 text-white text-center"># {{ qIndex + 1 }}</div>
                                    <div class="w-3/12">
                                        <input type="text" v-model="question.question_text" placeholder="Вопрос"
                                            class="w-full p-2 border" />
                                    </div>
                                    <div class="w-2/12">
                                        <select v-model="question.type" class="w-full p-2 border ml-4">
                                            <option value="single">1 ответ</option>
                                            <option value="multiple">Несколько ответов</option>
                                        </select>
                                    </div>
                                    <div class="w-5/12 flex flex-col">
                                        <div v-for="(option, oIndex) in question.options" :key="oIndex"
                                            class="flex items-center mb-2">
                                            <div class="w-1/12 text-white text-center"># {{ oIndex + 1 }}</div>
                                            <input type="text" v-model="option.option_text" placeholder="Option Text"
                                                class="w-8/12 p-2 border ml-2" />
                                            <input :type="question.type === 'single' ? 'radio' : 'checkbox'"
                                                :name="'question_' + qIndex" v-model="option.correct" :value="true"
                                                class="ml-2" />
                                        </div>
                                    </div>
                                    <div class="w-1/12 text-center">
                                        <button @click="removeQuestion(qIndex)" :disabled="qIndex == 0"
                                            class="bg-red-500 text-white py-2 px-4 mb-2 rounded w-full hover:bg-red-600">
                                            Удалить
                                        </button>
                                        <button @click="addOption(qIndex)" type="button"
                                            class="text-white bg-blue-500 hover:bg-blue-600 py-2 px-4 mb-2 rounded w-full">
                                            Добавить вариант ответа
                                        </button>
                                        <button @click="removeOption(qIndex, oIndex)" type="button"
                                            class="text-white bg-amber-500 hover:bg-amber-600 py-2 px-4 mb-2 rounded w-full">
                                            Удалить вариант ответа
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="float-right">
                            <button type="submit"
                                class="text-white bg-green-600 hover:bg-green-700 font-bold py-2 px-4 rounded cursor-pointer">
                                Создать тест
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </NavLayout>
</template>
