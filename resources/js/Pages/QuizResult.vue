<template>
    <div class="quiz-result-container">
        <div class="quiz-result">
            <p v-if="result !== null">
                Вы ответили правильно на <span class="highlight">{{ result.correctAnswers }}</span> из <span class="highlight">{{ result.totalQuestions }}</span> вопросов.
            </p>
            <p v-if="result !== null">
                Процент правильных ответов: <span class="highlight">{{ result.percentage.toFixed(2) }}%</span>.
            </p>
            <p v-if="result !== null">
                Ваша оценка: <span class="highlight">{{ result.grade }}</span>.
            </p>
            <p v-if="result !== null">
                Неправильных ответов: <span class="highlight">{{ result.totalQuestions - result.correctAnswers }}</span>.
            </p>
        </div>
        <a :href="'/'" class="btn-home">Вернуться на главную</a>
    </div>
</template>

<script>
export default {
    props: {
        result: Object,
    },
};
</script>

<style scoped>
.quiz-result-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    margin-top: 50px;
}

.quiz-result {
    background-color: #f9fafb;
    border: 1px solid #e5e7eb;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-align: center;
    max-width: 600px;
    width: 100%;
}

.quiz-result p {
    font-size: 18px;
    color: #333;
    margin: 10px 0;
}

.highlight {
    font-weight: bold;
    color: #1d4ed8;
}

.btn-home {
    margin-top: 20px;
    padding: 10px 20px;
    background-color: #1d4ed8;
    color: #ffffff;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s;
    font-size: 16px;
}

.btn-home:hover {
    background-color: #3b82f6;
}
</style>
