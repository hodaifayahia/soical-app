<script setup>
import {Link} from '@inertiajs/vue3'
defineProps({
   group:Object
})
</script>

<template>
    <Link :href="route('group.profile',group.slug)" class="flex w-full flex-col sm:flex-row mb-2 bg-gray-100  p-3 hover:bg-gray-200 cursor-pointer rounded-lg transition duration-150 ease-in-out">
        <img :src="group.thumbnail_url" class="rounded-full w-[64px] h-[64px] mx-auto md:mr-1 sm:mx-0 sm:mr-3 mb-2 sm:mb-0" alt="">
        <div class="text-center flex-1">
            <div class="flex justify-between ">
                <div>
                    <h3 class=" mt-2 text-xl text-black text">
                        {{ group.name }}
                    </h3>
                    
                </div>
                <div class="text-xs">
                    <span>
                        {{ group.status === 'APPROVED'? (group.role === 'ADMIN' ? group.role : "") : "not approved"}}
                    </span>
                    
                </div>
            </div>
        </div>
    </Link>
</template>

<style scoped>
</style>
