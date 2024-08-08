<script setup>
import PostItem from '@/Components/app/PostItem.vue';
import { ref, } from 'vue'
import PostModel from "@/Components/app/PostModel.vue"; // Corrected import (without {})
import {  usePage } from '@inertiajs/vue3';

const props = defineProps({
    posts: Array,
});

const AuthUser = usePage().props.auth.user;

const showEditModel = ref(false); // Changed variable name to camelCase
const editPost = ref({});

function openEditModel(post) { // Changed parameter name to singular form
    editPost.value = post;
    showEditModel.value = true;
}
function onModelHiden() {
    editPost.body = {
        id: null,
        body: "",
        user: AuthUser,
    };
}
</script>

<template>
    <div>
        <PostItem v-for="post in posts" :key="post.id" :post="post" @editClick="openEditModel" />
    </div>
    <PostModel :post="editPost" v-model="showEditModel"  @hide= "onModelHiden" />
</template>

<style scoped>
/* Add any additional styles here */
</style>
