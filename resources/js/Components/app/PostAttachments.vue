<script setup >
import {  ArrowDownTrayIcon, PaperClipIcon  } from '@heroicons/vue/20/solid';
import { isImage } from "@/helper.js";

defineProps({ 
	attachments : Array
})
defineEmits(['clickAttachement'])
</script>

<template>
	<div v-for="(attachment, index) in attachments.slice(0, 4)" :key="attachment.id"
	                class="relative group aspect-square bg-blue-100 flex items-center justify-center bg-gray-200">
	                <div @click="$emit('clickAttachement' , index) " class='cursor-pointer'>
	                    <!-- More Attachments Overlay -->
	                    <div v-if="index === 3 && attachments.length > 3"
	                        class="absolute inset-0 z-10 bg-black/60 text-white flex items-center justify-center text-2xl">
	                        +{{ attachments.length - 3 }} more
	                    </div>
	
	                    <!-- Download Button -->
	                    <a :href="route('post.download', attachment)" v-if="index < 3"
	                        class="z-20 w-8 h-8 opacity-0 group-hover:opacity-100 transition-all rounded-full absolute right-2 top-2 bg-gray-700 hover:bg-gray-800 text-gray-100 flex items-center justify-center">
	                        <ArrowDownTrayIcon class="h-5 w-5" />
	                    </a>
	
	                    <!-- Attachment Preview -->
	                    <img v-if="isImage(attachment)" class="object-cover aspect-square w-full h-full"
	                        :src="attachment.url" :alt="attachment.name">
	
	                    <!-- Fallback for Non-Image Attachments -->
	                    <template v-else>
	                        <div class="flex items-center justify-center flex-col text-center text-sm px-4 py-2">
	                            <PaperClipIcon class=" w-16 h-16 " />
	                            <small class=" text-black text-base">{{ attachment.name }}</small>
	                        </div>
	                    </template>
	
	                </div>
	            </div>
</template>
