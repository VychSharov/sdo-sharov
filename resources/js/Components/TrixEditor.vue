<template>
    <div>
        <label class="block uppercase tracking-wide text-white text-xs font-bold mb-2">
            Описание курса
            <span class="text-red-600">*</span>
        </label>
        <trix-editor ref="trixEditor" input="courseOverview"></trix-editor>
        <input id="courseOverview" type="hidden" v-model="content">
    </div>
</template>

<script>
import "trix/dist/trix.css";
import { onMounted, ref, watch } from 'vue';

export default {
    name: 'TrixEditor',
    props: {
        modelValue: {
            type: String,
            default: ''
        }
    },
    setup(props, { emit }) {
        const content = ref(props.modelValue);

        const updateContent = (event) => {
            content.value = event.target.value;
            emit('update:modelValue', content.value);
        };

        onMounted(() => {
            const editor = document.querySelector("trix-editor");
            editor.addEventListener('trix-change', updateContent);
        });

        watch(() => props.modelValue, (newValue) => {
            content.value = newValue;
        });

        return {
            content
        };
    }
};
</script>

<style scoped>
/* Add any custom styles here */
</style>
