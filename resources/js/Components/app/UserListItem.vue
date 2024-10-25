<script setup>

import {Link} from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
   user:Object,
   forApproved:{
    type:Boolean,
    default:false,
   }
})
defineEmits(['approved' , 'reject']);

</script>
<template>
    <Link :href="route('profile',user.username)" class="flex items-center m-2 p-1  bg-gray-100  hover:bg-gray-10 cursor-pointer rounded-lg transition duration-150 ease-in-out">
        <img :src="user.avatar_url|| '/img/default_user.jpg'" class="rounded-full w-[50px] h-[50px] mr-2" alt="">

        <div class="text-center sm:text-left w-full">

            <h3 class="text-xl mr-6 text-black">
                    <div class="flex justify-between ">
                        <div class="w-full">
                            {{ user.name }}
                        </div>
                        <div class="flex gap-1">
                            <button class=" flex-1 text-xs py-2 px-1 rounded bg-emerald-500 hover:bg-emerald-700 text-white " v-if="forApproved"  @click.prevent ="$emit('approved',user)" > Approved</button>
                            <button class=" flex-1 text-xs py-2 px-1 rounded bg-red-500 hover:bg-red-700 text-white " v-if="forApproved"   @click.prevent ="$emit('reject',user)" > Reject</button>

                        </div>
                    </div>
                    
            </h3>
        </div>
    </Link>
</template>

<style scoped>
</style>
