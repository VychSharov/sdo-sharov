<template>
    <div class="relative">
        <div
            @mouseover="show = true"
            @mouseleave="show = false"
            class="rounded-lg bg-[#1f467a] m-2"
            :class="[
                show && width > 639
                    ? 'absolute z-30 transition ease-in-out delay-150 hover:translate-y-8 hover:scale-125 hover:bg-[#cbced4] duration-300 '
                    : '',
            ]"
        >
            <div>
                <img
                    :class="show
                            ? 'transition ease-in-out delay-150 rounded-lg'
                            : 'rounded-lg'"
                    :src="thumbnail || ''"
                    class="course-image aspect-video cursor-pointer"
                />
                <div class="w-full h-full aspect-video cursor-pointer delay-350 hidden"></div>
            </div>

            <div>
                <div class="flex mt-1.5">
                    <div></div>
                    <div class="px-1.5 mt-1">
                        <div
                            :class="[
                                'text-[17px] font-extrabold w-full cursor-pointer',
                                show ? 'text-black' : 'text-white'
                            ]"
                        >
                            {{ title.substring(0, 100) }}
                        </div>
                        <div
                            :class="[
                                'text-[17px] font-extrabold w-full cursor-pointer',
                                show ? 'text-black' : 'text-white'
                            ]"
                        >
                            {{ creater }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, toRefs, ref, onMounted } from "vue";

const props = defineProps({
    title: String,
    creater: String,
    image: String,
    thumbnail: String,
});

const { title, creater, thumbnail } = toRefs(props);

let show = ref(false);
let width = ref(document.documentElement.clientWidth);

onMounted(() => {
    window.addEventListener('resize', () => {
        width.value = document.documentElement.clientWidth;
    });
});
</script>

<style scoped>
.course-image {
    width: 100%; /* Это заставит изображение занимать всю ширину контейнера */
    height: 75%; /* Фиксированная высота для всех изображений */
    object-fit: cover; /* Это позволит изображению полностью заполнять область */
}
</style>
