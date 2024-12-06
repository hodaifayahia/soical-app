<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import { HandThumbUpIcon, ChatBubbleLeftRightIcon , ArrowPathIcon } from '@heroicons/vue/20/solid';
import PostUserHeader from '@/Components/app/PostUserHeader.vue';
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import axiosClient from '@/axiosClient.js';

import ReadlessReadMore from '@/Components/app/ReadlessReadMore.vue';
import EditDeleteDropDown from '@/Components/app/EditDeleteDropDown.vue';
import PostAttachments from '@/Components/app/PostAttachments.vue'
import CommentList from '@/Components/app/CommentList.vue'



// Define the props
const props = defineProps({
    post: Object,
    
});
const postbody =  computed(()=>{
   return  props.post.body.replace(/(#\w+)(?![^<]*<\/a>)/g, function (match) {
        return `<a class="hashtag" href="/search/${encodeURIComponent(match)}">${match}</a>`;
    });
})

const emit = defineEmits(['editClick', 'attachmentClick']);
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


</script>

<template>

    <div class="p-6 bg-white  rounded  mb-4">
        <div class="flex items-center mb-4 justify-between">
            <PostUserHeader :post="post" :showTime="true" />
            <div>
                <EditDeleteDropDown :posts="post"  @edit="openEditModel" @delete="deletePost" />
            </div>

        </div>
        <div>
            <ReadlessReadMore :Content="postbody" ContentClass="">
            </ReadlessReadMore>
        </div>
        <div class="grid gap-2" :class="`grid-cols-${Math.min(props.post.attachments.length, 2)}`">
            <PostAttachments :attachments="post.attachments" @clickAttachement="openAttachment"  />
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
            <DisclosurePanel class="comment-list overflow-auto max-h-[400px]  px-4 pb-2 mt-2 pt-4 text-sm text-gray-500">
              
                <CommentList :post="props.post" :data="{comments : post.Comments}"   />
                </DisclosurePanel>
        </Disclosure>
    </div>


</template>

<style scoped>
/* Add any additional styles here */
</style>
