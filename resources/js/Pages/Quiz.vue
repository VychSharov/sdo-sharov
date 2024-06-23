<template>
    <Head title="Прохождение теста" />
    <NavLayout>
        <main class="container mx-auto py-8">
            <div class="bg-gray-800 rounded-lg p-6">
                <h2 class="text-white font-extrabold">{{ test.test_name }}</h2>
                <p class="text-white mt-2">Оставшееся время: {{ formatTime(timeLeft) }}</p>
            </div>
            <div class="test-questions mt-6">
                <div v-for="(question, index) in test.questions" :key="question.id" class="question-item mb-6">
                    <h3 class="font-semibold mb-2">{{ question.question_text }}</h3>
                    <div v-for="option in question.options" :key="option.id" class="option-item mb-2">
                        <input
                            v-if="question.type === 'single'"
                            type="radio"
                            :name="`question_${index}`"
                            :value="option.id"
                            v-model="answers[index]"
                            class="mr-2"
                        />
                        <input
                            v-else-if="question.type === 'multiple'"
                            type="checkbox"
                            :name="`question_${index}`"
                            :value="option.id"
                            :checked="answers[index] && answers[index].includes(option.id)"
                            @change="toggleCheckbox(index, option.id)"
                            class="mr-2"
                        />
                        {{ option.option_text }}
                    </div>
                </div>
            </div>
            <button @click="submitQuiz" class="text-white bg-blue-500 hover:bg-blue-600 py-2 px-4 rounded mt-4">
                Завершить
            </button>
        </main>
    </NavLayout>
</template>
<script>
export default {
    props: {
        test: Object,
    },
    data() {
        return {
            answers: {},
            result: null,
            timeLeft: this.test.duration * 60, // Продолжительность теста в секундах
            timer: null,
        };
    },
    mounted() {
        this.startTimer();
    },
    beforeDestroy() {
        clearInterval(this.timer);
    },
    methods: {
        toggleCheckbox(index, optionId) {
            if (!this.answers[index]) {
                this.answers[index] = [];
            }
            const optionIndex = this.answers[index].indexOf(optionId);
            if (optionIndex > -1) {
                this.answers[index].splice(optionIndex, 1);
            } else {
                this.answers[index].push(optionId);
            }
        },
        async submitQuiz() {
            try {
                clearInterval(this.timer);
                const response = await this.$inertia.post(`/quizzes/${this.test.id}/submit`, { answers: this.answers });
                this.result = response.props.result;
            } catch (error) {
                console.error('Error submitting quiz:', error);
            }
        },
        startTimer() {
            this.timer = setInterval(() => {
                if (this.timeLeft > 0) {
                    this.timeLeft--;
                } else {
                    clearInterval(this.timer);
                    this.submitQuiz();
                }
            }, 1000);
        },
        formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;
            return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
        },
    },
};
</script>
<style scoped>
.question-item {
    background-color: #f9fafb; /* Белый цвет фона для вопросов */
    border: 1px solid #e5e7eb; /* Серая граница */
    padding: 20px; /* Внутренний отступ */
    border-radius: 8px; /* Скругление углов */
}
.option-item {
margin-left: 20px; /* Отступ слева для опций */
}
button {
margin-top: 10px;
padding: 5px 10px;
cursor: pointer;
}
.test-questions {
margin-top: 20px; /* Отступ сверху для блока вопросов */
}
</style>
