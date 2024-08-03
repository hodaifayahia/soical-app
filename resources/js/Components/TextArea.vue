<script setup>
import { onMounted, ref, defineProps, defineExpose, defineModel } from 'vue';
const model = defineModel({
  type: String,
  required: true,
});

const input = ref(null);

const props =  defineProps({
  placeholder: String,
  autoResize: {
    type: Boolean,
    default: true
  },
  rows: {
    type: Number,
    default: 1
  }
});

defineExpose({ focus: () => input.value.focus() });

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    input.value.focus();
  }
  adjustHeight(); // Initial adjustment
});

function onInputChange($event) {
  model.value = $event.target.value;
  adjustHeight();
}

function adjustHeight() {
  if (props.autoResize) {
    input.value.style.height = 'auto';
    input.value.style.height = input.value.scrollHeight + 'px';
  }

}
</script>

<template>
  <textarea
    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm "
    :value="model"
    ref="input"
    :placeholder="placeholder"
    :rows="rows"
    @input="onInputChange"
  ></textarea>
</template>