<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';

// Define the props
const props = defineProps({
    post: Object
});

function isImage(attachment) {
    const mime = attachment.mime.split('/');
    return mime[0].toLowerCase() === 'image';
}
</script>

<template>
    <div class="p-6 bg-white rounded  mb-4">
        <div class="flex items-center mb-4">
            <a href="javascript:void(0)">
                <img :src="post.user.avatar" alt="User image"
                    class="w-10 h-10 rounded-full mr-3 border-2 transition-all hover:border-cyan-400">
            </a>
            <div>
                <a href="javascript:void(0)">
                    <h4 class="font-semibold hover:underline">{{ post.user.name }}
                        <template v-if="post.Group">
                            >
                            <a href="javascript:void(0)" class="hover:underline">
                                {{ post.Group.name }}
                            </a>
                        </template>
                    </h4>
                </a>
                <small class="text-gray-400">{{ post.created_at }}</small>
            </div>
        </div>
        <div>
            <Disclosure>
                <template v-slot="{ open }">
                    <div>
                        <div v-if="!open" v-html="post.body.substring(0, 100)" class="prose"></div>
                        <DisclosurePanel class="">
                            <div v-html="post.body" class="prose"></div>
                        </DisclosurePanel>
                        <DisclosureButton class="flex items-center mt-2 text-blue-500 hover:text-blue-600">
                            {{ open ? 'Read less' : 'Read more' }}
                        </DisclosureButton>
                    </div>
                </template>
            </Disclosure>
        </div>
        <div class="grid grid-cols-3 gap-2  ">
            <div v-for="attachment in post.attachments" :key="attachment.id" class="">

                <div class="relative group aspect-square bg-blue-100 flex items-center justify-center bg-gray-200">
                    <!-- download -->
                    <button
                    class="w-8 h-8 opacity-0 group-hover:opacity-100  transition-all roundend absolute right-2 top-2  bg-gray-700 hover:bg-gray-800 transation-all text-gray-100 flex items-center justify-center">
                    <svg class="w-5 h-5 rounded" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                <!-- end download -->
            </button>
                    <img v-if="isImage(attachment)" class="object-fit aspect-square" :src="attachment.url"
                        :alt="attachment.name">
                    <template v-else>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" class="w-16 h-16" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>

                        {{ attachment.name }}
                    </template>

                </div>
            </div>
        </div>
        <div class="flex justify-around  mt-4 bg-gray-50">
            <button
                class="gap-1  rounded bg-gray-100 hover:bg-gray-200 py-2 px-10   flex items-center space-x-1 text-gray-500 hover:text-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path
                        d="M7.493 18.5c-.425 0-.82-.236-.975-.632A7.48 7.48 0 0 1 6 15.125c0-1.75.599-3.358 1.602-4.634.151-.192.373-.309.6-.397.473-.183.89-.514 1.212-.924a9.042 9.042 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75A.75.75 0 0 1 15 2a2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H14.23c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23h-.777ZM2.331 10.727a11.969 11.969 0 0 0-.831 4.398 12 12 0 0 0 .52 3.507C2.28 19.482 3.105 20 3.994 20H4.9c.445 0 .72-.498.523-.898a8.963 8.963 0 0 1-.924-3.977c0-1.708.476-3.305 1.302-4.666.245-.403-.028-.959-.5-.959H4.25c-.832 0-1.612.453-1.918 1.227Z" />
                </svg>

                <span>Like</span>
            </button>
            <button
                class="gap-1  rounded bg-gray-100 hover:bg-gray-200 py-2 px-10   flex items-center space-x-1 text-gray-500 hover:text-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path fill-rule="evenodd"
                        d="M4.848 2.771A49.144 49.144 0 0 1 12 2.25c2.43 0 4.817.178 7.152.52 1.978.292 3.348 2.024 3.348 3.97v6.02c0 1.946-1.37 3.678-3.348 3.97a48.901 48.901 0 0 1-3.476.383.39.39 0 0 0-.297.17l-2.755 4.133a.75.75 0 0 1-1.248 0l-2.755-4.133a.39.39 0 0 0-.297-.17 48.9 48.9 0 0 1-3.476-.384c-1.978-.29-3.348-2.024-3.348-3.97V6.741c0-1.946 1.37-3.68 3.348-3.97ZM6.75 8.25a.75.75 0 0 1 .75-.75h9a.75.75 0 0 1 0 1.5h-9a.75.75 0 0 1-.75-.75Zm.75 2.25a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H7.5Z"
                        clip-rule="evenodd" />
                </svg>

                <span>Comment</span>
            </button>
            <button
                class=" gap-1 rounded bg-gray-100 hover:bg-gray-200 py-2 px-10    flex items-center space-x-1 text-gray-500 hover:text-blue-500">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                    <path fill-rule="evenodd"
                        d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm0 8.625a1.125 1.125 0 1 0 0 2.25 1.125 1.125 0 0 0 0-2.25ZM15.375 12a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0ZM7.5 10.875a1.125 1.125 0 1 0 0 2.25 1.125 1.125 0 0 0 0-2.25Z"
                        clip-rule="evenodd" />
                </svg>



                <span>Share</span>
            </button>
        </div>
    </div>
</template>

<style scoped>
/* Add any additional styles here */
</style>
