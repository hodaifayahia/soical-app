<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import {  HandThumbUpIcon, ChatBubbleLeftRightIcon, ArrowDownTrayIcon, PaperClipIcon } from '@heroicons/vue/20/solid';
import PostUserHeader from '@/Components/app/PostUserHeader.vue';
import { router, usePage } from '@inertiajs/vue3';
import { isImage } from "@/helper.js";
import axiosClient from '@/axiosClient.js';
import indigoButton from '@/Components/app/indigoButton.vue';
import { ref } from 'vue';
import TextArea from '@/Components/TextArea.vue';
import ReadlessReadMore from '@/Components/app/ReadlessReadMore.vue';
import EditDeleteDropDown from '@/Components/app/EditDeleteDropDown.vue';



// Define the props
const props = defineProps({
    post: Object
});
const newCommentText = ref('');
const authUser = usePage().props.auth.user;
const emit = defineEmits(['editClick', 'attachmentClick']);
const edittingComment = ref(null); 
function openEditModel() {
    emit('editClick', props.post)
}
function deletePost() {
    if (window.confirm('are Sure You want to Delate this post')) {
        router.delete(route('post.destroy', props.post), {
            preserveScroll: true,
        })
    }
}
function openAttachment(index) {
    emit('attachmentClick', props.post, index);
}
function sendReaction() {
    axiosClient.post(route('post.reaction', props.post), {
        reaction: 'Like'
    })
        .then(({ data }) => {
            props.post.num_of_reaction = data.num_of_reaction;
            props.post.current_user_has_reaction = data.current_user_has_reaction;
        });
}
function createComment() {
    axiosClient.post(route('post.comment.create', props.post), {
        comment: newCommentText.value
    })
        .then(({ data }) => {
            newCommentText.value = '',
            props.post.comment.unshift(data),
            props.post.num_of_comment++

        });
}
function StartEditComment(comment) {

    edittingComment.value = {
        id:comment.id,
        comment:comment.comment.replace('<br\s*\/?>/gi','\n')
    };
}
function deleteComment(comment) {
    if (!window.confirm('are sure u want to delete this comment ')) {
        return false;
    }
     axiosClient.delete(route('post.comment.delate', comment.id))
        .then(({ data }) => {
            props.post.comments = props.post.comments.filters(c => c.id != comment.id),
            props.post.num_of_comment--;

        });
}
function updateComment() {
     axiosClient.put(route('post.comment.update', edittingComment.value.id),edittingComment.value)
        .then(({ data }) => {
            edittingComment.value = null;
            props.post.comments = props.post.comments.map((c)=>{
                if(c.id = data.id){
                    return data;
                }
                return c;
            })

        });
}


</script>

<template>

    <div class="p-6 bg-white rounded  mb-4">
        <div class="flex items-center mb-4 justify-between">
            <PostUserHeader :post="post" :showTime="true" />
            <div>
                <EditDeleteDropDown :user="post.user" @edit="openEditModel" @delete="deletePost" />
            </div>

        </div>
        <div>
            <ReadlessReadMore :Content="post.body" ContentClass="">
            </ReadlessReadMore>
        </div>
        <div class="grid gap-2" :class="`grid-cols-${Math.min(props.post.attachments.length, 2)}`">
            <div v-for="(attachment, index) in props.post.attachments.slice(0, 4)" :key="attachment.id"
                class="relative group aspect-square bg-blue-100 flex items-center justify-center bg-gray-200">
                <div @click="openAttachment(index)" class='cursor-pointer'>
                    <!-- More Attachments Overlay -->
                    <div v-if="index === 3 && props.post.attachments.length > 3"
                        class="absolute inset-0 z-10 bg-black/60 text-white flex items-center justify-center text-2xl">
                        +{{ props.post.attachments.length - 3 }} more
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
        </div>
        <Disclosure v-slot="{ open }">
            <div class="flex justify-around mt-2 gap-2 ">



                <button @click="sendReaction"
                    class="gap-1  rounded bg-gray-100 py-3 px-10   flex items-center space-x-1 text-gray-500 ">
                    <div class="flex justify-center items-center pr-1">
                        <HandThumbUpIcon class='w-5 h-5 '
                            :class="[post.current_user_has_reaction ? 'text-blue-600' : ' ']" />
                        <span class="pl-1">{{ post.num_of_reaction }}</span>
                    </div>
                    <span>Like
                    </span>
                </button>

                <DisclosureButton
                    class="gap-1  rounded bg-gray-100 hover:bg-gray-200 py-3 px-10   flex items-center space-x-1 text-gray-500 hover:text-blue-200">
                    <ChatBubbleLeftRightIcon class='w-5 h-5' />
                    <span>Comment</span>
                    <span class="pl-1">{{ post.num_of_comment }}</span>
                </DisclosureButton>
            </div>
            <DisclosurePanel class="px-4 pb-2 mt-2 pt-4 text-sm text-gray-500">
                <div>
                    <div>
                        <div class="flex mt-4">
                            <a href="javascript:void(0)">
                                <img :src="authUser.avatar_url" alt="User image"
                                    class="w-10 h-10 rounded-full mr-3 border-2 transition-all hover:border-cyan-400">
                            </a>
                            <div class="flex flex-1">
                                <TextArea v-model="newCommentText" name="" rows="1"
                                    class="w-full  resize-none max-h-[160px] rounded-r-none "
                                    placeholder="Enter your COmment Here" d="" />
                                <indigoButton @click="createComment" class="w-[100px]  rounded-l-none"> submit
                                </indigoButton>
                            </div>


                            <div>

                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <div v-for="comment in post.Comments" :key="comment.id"
                            class="bg-white rounded-lg p-4 shadow-md mb-4">
                            <div class="flex items-start">
                                <!-- User Avatar -->
                                <a href="javascript:void(0)">
                                    <img :src="authUser.avatar_url" alt="User image"
                                        class="w-10 h-10 rounded-full mr-4 border-2 transition-all hover:border-cyan-400">
                                </a>
                                <!-- Comment Content -->
                                <div class="flex-1 ">
                                    <div class="flex items-center justify-between">
                                        <!-- User Name and Time -->
                                        <div>
                                            <h4 class="font-semibold text-gray-800">{{ comment.user.name }}</h4>
                                            <span class="text-sm text-gray-500">{{ comment.created_at }}</span>
                                        </div>

                                    </div>
                                    <EditDeleteDropDown class="flex  justify-end bottom-8 " :user="comment.user" @edit="StartEditComment(comment)"
                                        @delete="deleteComment(comment)" />


                                    <div v-if="edittingComment && edittingComment.id == comment.id" class="m-2">

                                        <TextArea v-model="edittingComment.comment" name="" rows="1"
                                            class="w-full  resize-none max-h-[160px] rounded-r-none "
                                            placeholder="Enter your COmment Here" d="" />
                                        <div class="flex gap-2 justify-end">
                                            
                                            <button class="text-indigo-500 " @click=" edittingComment = null" >Cancel</button>
                                            <indigoButton @click="updateComment(comment)" class="w-[100px]  ">
                                                Update
                                            </indigoButton>
                                        </div>
                                    </div>

                                    <ReadlessReadMore v-else :Content="comment.comment" ContentClass="text-gray-700 mt-2">
                                    </ReadlessReadMore>
                                </div>


                            </div>

                        </div>
                    </div>

                    <div>

                    </div>
                </div>


            </DisclosurePanel>
        </Disclosure>
    </div>


</template>

<style scoped>
/* Add any additional styles here */
</style>
