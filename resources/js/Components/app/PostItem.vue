<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';
import {  PencilIcon ,TrashIcon , EllipsisVerticalIcon ,HandThumbUpIcon , ChatBubbleLeftRightIcon , ArrowDownTrayIcon} from '@heroicons/vue/20/solid';
import PostUserHeader from '@/Components/app/PostUserHeader.vue';
import {router} from '@inertiajs/vue3';
import {  isImage } from "@/helper.js";


// Define the props
const props = defineProps({
    post: Object
});
const emit = defineEmits(['editClick']);


function openEditModel(){
    emit('editClick',props.post)
}
function deletePost() {
    if (window.confirm('are Sure You want to Delate this post')) {
        router.delete(route('post.destroy',props.post),{
            preserveScroll: true,
        })
    }
}

</script>

<template>

    <div class="p-6 bg-white rounded  mb-4">
        <div class="flex items-center mb-4 justify-between">
            <PostUserHeader :post="post" :showTime="true" />
            <div>
                <Menu as="div" class="relative inline-block text-left">
                    <div>
                        <MenuButton
                            class="inline-flex w-full justify-center rounded-mdtext-sm font-medium text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75">

                            <EllipsisVerticalIcon class=" h-6 w-6 text-black hover:text-black" aria-hidden="true" />
                        </MenuButton>
                    </div>

                    <transition enter-active-class="transition duration-100 ease-out"
                        enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
                        leave-active-class="transition duration-75 ease-in"
                        leave-from-class="transform scale-100 opacity-100"
                        leave-to-class="transform scale-95 opacity-0">
                        <MenuItems
                            class="absolute right-0 mt-2 w-36 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none">
                            <div class="px-1 py-1">
                                <MenuItem v-slot="{ active }">
                                <button @click="openEditModel" :class="[
                                        
                                        active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                    ]">
                                    <PencilIcon :active="active" class="mr-2 h-5 w-5 text-indigo-400"
                                        aria-hidden="true" />
                                    Edit
                                </button>
                                </MenuItem>

                            </div>

                            <div class="px-1 py-1">
                                <MenuItem v-slot="{ active }">
                                <button @click="deletePost" :class="[
                                        active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                    ]">
                                    <TrashIcon :active="active" class="mr-2 h-5 w-5 text-indigo-400"
                                        aria-hidden="true" />
                                    Delete
                                </button>
                                </MenuItem>
                            </div>
                        </MenuItems>
                    </transition>
                </Menu>
            </div>

        </div>
        <div>
            <Disclosure>
                <template v-slot="{ open }">
                    <div>
                        <div class="ck-content-output prose break-words" v-if="!open"
                            v-html="post.body.substring(0, 100)"></div>
                        <template v-if="post.body.length > 200">
                            <DisclosurePanel class="">
                                <div class="ck-content-output prose break-words" v-html="post.body"></div>
                            </DisclosurePanel>
                            <DisclosureButton class="flex items-center mt-2 text-blue-500 hover:text-blue-600">
                                {{ open ? 'Read less' : 'Read more' }}
                            </DisclosureButton>
                        </template>
                    </div>
                </template>
            </Disclosure>
        </div>
        <div class="grid  gap-2  " :class="[post.attachments.length == 1 ?'grid-cols-1' : 'grid-cols-2']">
            <div v-for="(attachment , index) in post.attachments.slice(0,4)" :key="attachment.id" >

                <div class="relative group aspect-square bg-blue-100 flex items-center justify-center bg-gray-200">
                    <div v-if="index == 3" class="absolute left-0 top-0 right-0 bottom-0 z-10 bg-black/60 text-white flex
                            items-center justify-center text-2xl">
                        +{{ post.attachments.length - 4 }} more
                    </div>

                    <!-- download -->

                    <button
                        class="z-20 w-8 h-8 opacity-0 group-hover:opacity-100  transition-all roundend absolute right-2 top-2  bg-gray-700 hover:bg-gray-800 transation-all text-gray-100 flex items-center justify-center">
                        <ArrowDownTrayIcon class="h-5 w-5 rounded" />
                        <!-- end download -->
                    </button>
                    <img v-if="isImage(attachment)" class="object-fit aspect-square" :src="attachment.url"
                        :alt="attachment.name">
                    <template v-else>
                       <

                        {{ attachment.name }}
                    </template>

                </div>
            </div>
        </div>
        <div class="flex justify-around  mt-4 ">
            <button
                class="gap-1  rounded bg-gray-100 hover:bg-gray-200 py-3 px-10   flex items-center space-x-1 text-gray-500 hover:text-blue-200">

                <HandThumbUpIcon class='w-5 h-5' />
                <span>Like</span>
            </button>
            <button
                class="gap-1  rounded bg-gray-100 hover:bg-gray-200 py-3 px-10   flex items-center space-x-1 text-gray-500 hover:text-blue-200">
                <ChatBubbleLeftRightIcon class='w-5 h-5' />


                <span>Comment</span>
            </button>
        </div>
    </div>


</template>

<style scoped>
/* Add any additional styles here */
</style>
