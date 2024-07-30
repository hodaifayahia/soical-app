<script setup>
import PostItem from '@/Components/app/PostItem.vue';

defineProps({
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

const pos2 = {
    user: {
        id: 1,
        avatar: "https://i.pravatar.cc/150?img=12",
        name: "Houdiafa Yahia",
    },
    Group: {
        id: 1,
        name: 'Laravel Developers'
    },
    body: 'Lorem ipsum dolor sit amet consectetur adipisicing elit Molestiae non eum reiciendis minus blanditiis voluptates ad delectus saepe dignissimos velit rem officia cupiditate mollitia laudantium architecto deleniti placeat rerum nemo!',
    created_at: '2024/7/14 15:12'
};
</script>

<template>
    <div class="max-w-2xl mx-auto p-4">
        <PostItem v-for="post in posts" :key="post.id" :post="post"></PostItem>
        <PostItem :post="pos2"></PostItem>
    </div>
</template>

<style scoped>
/* Add any additional styles here */
</style>
