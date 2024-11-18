<script setup>
import PostItem from '@/Components/app/PostItem.vue';
import { ref,onMounted ,watch, onUpdated ,nextTick } from 'vue'
import PostModel from "@/Components/app/PostModel.vue"; // Corrected import (without {})
import attachmentPreviewModel from "@/Components/app/attachmentPreviewModel.vue"; // Corrected import (without {})
import {  usePage } from '@inertiajs/vue3';
import axiosClient from '@/axiosClient.js';


const props = defineProps({
    posts: Array,
});
const page = usePage(); 
const allPosts = ref({
  data: page.props.posts.data,
  next: page.props.posts.links.next
})
const AuthUser = usePage().props.auth.user;


const showEditModel = ref(false); // Changed variable name to camelCase
const editPost = ref({});

const showPreviewModel = ref(false); // Changed variable name to camelCase
const AttachmentModel = ref({});

const loadMoreIntersect = ref(null); 

function openEditModel(post) { // Changed parameter name to singular form
    editPost.value = post;
    showEditModel.value = true;
}
function openAttachmentPreviewModel(post, index) {
    AttachmentModel.value = {
       post, // should contain the post data with the attachments
        index // should be a valid index within the attachments array
    };
    showPreviewModel.value = true;
}

function onModelHiden() {
    editPost.body = {
        id: null,
        body: "",
        user: AuthUser,
    };
}
function loadMore() {
  if (allPosts.value.next) {
    axiosClient.get(allPosts.value.next)
      .then(({ data }) => {
        allPosts.value.data = [...allPosts.value.data, ...data.data];
        allPosts.value.next = data.links.next;
      })
      .catch(error => {
        console.error('Error loading more posts:', error);
      });
  }
}

onMounted(() => {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting && allPosts.value.next) {
        loadMore();
      }
    });
  }, {
    rootMargin: '0px 0px 100px 0px'
  });

  // Use nextTick to ensure the element exists before observing
  nextTick(() => {
    if (loadMoreIntersect.value) {
      observer.observe(loadMoreIntersect.value);
    }
  });
});
// Hooks when the posts gets updated it allposts should alse get updated 
watch(() => page.props.posts, (newPosts) => {
  if(page.props.posts){
    allPosts.value = {
        data: newPosts.data,
        next: newPosts.links.next
    };
  }
} );

</script>

<template>
    <div>
      <PostItem v-for="post in allPosts.data" :key="post.id" :post="post"
        @attachmentClick='openAttachmentPreviewModel'
        @editClick="openEditModel" />
    </div>
    <PostModel :post="editPost" v-model="showEditModel" @hide="onModelHiden" />
    <div ref="loadMoreIntersect"></div>
  
    <attachmentPreviewModel :Attachments="AttachmentModel.post?.attachments || []" v-model:index="AttachmentModel.index" v-model="showPreviewModel" />
  </template>

<style scoped>
/* Add any additional styles here */
</style>
