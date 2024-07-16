<script setup>
defineProps({
    Image: String,
    Title: String,
})
</script>

<template>
    <div class="flex items-center m-4 bg-gray-50 p-2 hover:bg-gray-10 cursor-pointer rounded-lg transition duration-150 ease-in-out">
        <img :src="Image" class="rounded-full w-[64px] h-[64px] mr-2" alt="">
        <div class="text-center sm:text-left">
            <h3 class="text-xl mr-6 text-black">
                {{ Title }}
            </h3>
        </div>
    </div>
</template>

<style scoped>
</style>
