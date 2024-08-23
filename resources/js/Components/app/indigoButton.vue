<script setup>
defineProps({
    type:{
        type:String,
        default: 'button'
    }
})
</script>
<template>
    <button :type="type"
        class="flex  items-center justify-center   rounded-md border border-transparent bg-blue-100  p-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
        @click="submit">
        <slot></slot>
    </button>
</template>
<style></style>