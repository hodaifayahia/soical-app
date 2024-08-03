<script setup>
import PostItem from '@/Components/app/PostItem.vue';
import { ref } from 'vue'
import PostModel from "@/Components/app/PostModel.vue"; // Corrected import (without {})

const props = defineProps({
    posts: Array,
});

const pos1 = {
    user: {
        id: 1,
        avatar: "https://i.pravatar.cc/150?img=25",
        name: "John Smith",
    },
    Group: null,
    attachments: [
        {
            id: 1,
            name: "test.png",
            url: 'https://images.stockcake.com/public/4/0/f/40fe1675-74f1-4231-a989-d64b2e0e9a36_large/tranquil-nature-meditation-stockcake.jpg',
            mime: 'image/png'
        },
        {
            id: 2,
            name: "test.png",
            url: 'https://images.stockcake.com/public/4/0/f/40fe1675-74f1-4231-a989-d64b2e0e9a36_large/tranquil-nature-meditation-stockcake.jpg',
            mime: 'image/png'
        },
        {
            id: 3,
            name: "test",
            url: '#',
            mime: 'application/msword'
        },
    ],
    body: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae non eum reiciendis minus blanditiis voluptates ad delectus saepe dignissimos, velit, rem officia cupiditate mollitia laudantium architecto deleniti placeat rerum nemo!',
    created_at: '2024/7/14 15:12'
};

const showEditModel = ref(false); // Changed variable name to camelCase
const editPost = ref({});

function openEditModel(post) { // Changed parameter name to singular form
    editPost.value = post;
    showEditModel.value = true;
}
</script>

<template>
    <div>
        <PostItem v-for="post in posts" :key="post.id" :post="post" @editClick="openEditModel" />
    </div>
    <PostModel :post="editPost" v-model="showEditModel" />
</template>

<style scoped>
/* Add any additional styles here */
</style>
