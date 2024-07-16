<script setup>
defineProps({
    Image: String,
    Title: String,
    Description: String,
})
</script>

<template>
    <div class="flex flex-col sm:flex-row m-4 bg-gray-50 p-2 hover:bg-gray-10 cursor-pointer rounded-lg transition duration-150 ease-in-out">
        <img :src="Image" class="rounded-full w-[64px] h-[64px] mx-auto md:mr-1 sm:mx-0 sm:mr-3 mb-2 sm:mb-0" alt="">
        <div class="text-center sm:text-left">
            <h3 class="text-xl text-black">
                {{ Title }}
            </h3>
            <div class="text-sm w-full sm:w-[240px]">{{ Description }}</div>
        </div>
    </div>
</template>

<style scoped>
</style>
