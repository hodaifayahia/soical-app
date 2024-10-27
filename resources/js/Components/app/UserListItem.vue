<script setup>

import { Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps({
    user: Object,
    forApproved: {
        type: Boolean,
        default: false,
    },
    showRoleDropDown: {
        type: Boolean,
        default: false
    },
    disableRoleDropDown: {
        type: Boolean,
        default: false
    },

})
defineEmits(['approved', 'reject', 'role-change']);

</script>
<template>
    <div
        class="flex items-center m-2 p-1  bg-gray-50  hover:bg-gray-10  rounded-lg transition duration-150 ease-in-out">
        <Link :href="route('profile', user.username)">
        <img :src="user.avatar_url || '/img/default_user.jpg'"
            class="rounded-full cursor-pointer w-[50px] h-[50px] mr-2" alt="">
        </Link>
        <div class="text-center sm:text-left w-full">

            <h3 class="text-xl mr-6 text-black">
                <div class="flex justify-between ">
                    <Link :href="route('profile', user.username)">
                    <div class="w-full hover:underline cursor-pointer">
                        {{ user.name }}
                    </div>
                    </Link>
                    <div class="flex gap-1">
                        <button
                            class=" flex-1 text-xs py-2 px-1 rounded bg-emerald-500 hover:bg-emerald-700 text-white "
                            v-if="forApproved" @click.prevent="$emit('approved', user)"> Approved</button>
                        <button class=" flex-1 text-xs py-2 px-1 rounded bg-red-500 hover:bg-red-700 text-white "
                            v-if="forApproved" @click.prevent="$emit('reject', user)"> Reject</button>

                    </div>
                    <div v-if="showRoleDropDown" class="sm:col-span-3">
                        <div class="mt-2">
                            <select :disabled="disableRoleDropDown"
                                @change="$emit('role-change', user, $event.target.value)"
                                class="rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6">
                                <option value="ADMIN" :selected="user.role == 'ADMIN'">ADMIN</option>
                                <option value="USER" :selected="user.role == 'USER'">USER</option>
                            </select>
                        </div>
                    </div>


                </div>

            </h3>
        </div>
    </div>
</template>

<style scoped></style>
