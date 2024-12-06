<script setup>
import { stringify } from 'postcss';
import { onMounted, ref } from 'vue';

const model = defineModel({
    type:String,
    required: true,
});

defineProps({
    placehoulder : "placeholder"
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm"
        v-model="model"
        ref="input"
        :placehoulder="placehoulder"
    />
</template>
