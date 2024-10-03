<script setup>
defineProps({
   group:Object
})
</script>

<template>
    <div class="flex w-full flex-col sm:flex-row mb-2 bg-gray-100  p-3 hover:bg-gray-200 cursor-pointer rounded-lg transition duration-150 ease-in-out">
        <img :src="group.thumbnail_url" class="rounded-full w-[64px] h-[64px] mx-auto md:mr-1 sm:mx-0 sm:mr-3 mb-2 sm:mb-0" alt="">
        <div class="text-center sm:text-left">
            <div class="flex justify-between ">
                <h3 class=" mt-2 text-xl text-black text">
                    {{ group.name }}
                </h3>
                <span>
                    {{ group.status === 'APPROVED'? (group.role === 'admin' ? group.role : "") : "not approved"}}
                </span>
            </div>
            <div class=" mr-1 text-sm w-full sm:w-[240px]">{{ group.description }}</div>
        </div>
    </div>
</template>

<style scoped>
</style>
