<script setup>
import {Link} from '@inertiajs/vue3'
defineProps({
   group:Object
})
</script>

<template>
    <Link :href="route('group.profile',{group:group.slug})" class="flex items-center m-3 p-3 bg-gray-100   hover:bg-gray-300   rounded-lg transition duration-150 ease-in-out h-[80px]">
        <img :src="group.thumbnail_url" class="rounded-full w-[64px] h-[64px] mx-auto md:mr-1 sm:mx-0 sm:mr-3 mb-2 sm:mb-0" alt="">
        <div class="text-center flex-1">
            <div class="flex justify-between ">
                <div>
                    <h3 class=" mt-3 text-xl text-black text ml-2 ">
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
