<template>
    <li
        :class="[!openSideNav ? 'p-2' : 'flex items-center p-2']"
        class="text-[#1f467a] text-sm font-semibold text-center hover:bg-gray-900 rounded-lg cursor-pointer"
    >
        <div :class="[!openSideNav ? 'w-full flex justify-center' : '']">
            <component :is="icon" />
        </div>
        <div :class="[!openSideNav ? '' : 'mt-1 ml-4']">
            <span v-if="!openSideNav">{{ iconString.substring(0, 4) }}</span>
            <span v-else>{{ iconString }}</span>
        </div>
    </li>
</template>

<script setup>
import { defineProps, toRefs } from "vue";

import Home from "vue-material-design-icons/Home.vue";
import ChairSchool from "vue-material-design-icons/ChairSchool.vue";
import Library from "vue-material-design-icons/Library.vue";
import AccountSchool from "vue-material-design-icons/AccountSchool.vue";
import BookPlus from "vue-material-design-icons/BookPlus.vue";
import BookMultiple from "vue-material-design-icons/BookMultiple.vue";
import TooltipEdit from "vue-material-design-icons/TooltipEdit.vue";
import Star from "vue-material-design-icons/TextBoxCheck.vue";
import Timer from "vue-material-design-icons/Timer.vue";

const props = defineProps({
    openSideNav: Boolean,
    iconString: String,
});

const { openSideNav, iconString } = toRefs(props);

let icon = null;

if (iconString.value === "Главная") icon = Home;
if (iconString.value === "Мои курсы") icon = ChairSchool;
if (iconString.value === "My Library") icon = Library;
if (iconString.value === "Управление студентами") icon = AccountSchool;
if (iconString.value === "Создать курс") icon = BookPlus;
if (iconString.value === "Управление курсами") icon = BookMultiple;
if (iconString.value === "Управление тестами") icon = Star;
if (iconString.value === "Показать тесты") icon = Timer;
if (iconString.value === "Создать тест") icon = TooltipEdit;
</script>
