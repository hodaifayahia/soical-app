<script setup>
import PostItem from '@/Components/app/PostItem.vue';
const pos1 = {
    user: {
        id: 1,
        avatar: "https://i.pravatar.cc/150?img=25",
        name: "John Smith",
    },
    Group: null,
    attachments: [  // Fixed spelling here
        {
            id: 1,
            name: "test.png",
            url: 'https://images.stockcake.com/public/4/0/f/40fe1675-74f1-4231-a989-d64b2e0e9a36_large/tranquil-nature-meditation-stockcake.jpg',
            mime: 'image/png'  // Fixed mime type here
        },
        {
            id: 2,
            name: "test.png",
            url: 'https://images.stockcake.com/public/4/0/f/40fe1675-74f1-4231-a989-d64b2e0e9a36_large/tranquil-nature-meditation-stockcake.jpg',
            mime: 'image/png'  // Fixed mime type here
        },
        {
            id: 2,
            name: "test",
            url: '#',
            mime: 'application/msword'  // Fixed mime type here
        },
    ],
    body: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae non eum reiciendis minus blanditiis voluptates ad delectus saepe dignissimos, velit, rem officia cupiditate mollitia laudantium architecto deleniti placeat rerum nemo!',
    created_at: '2024/7/14 15:12'
}
const pos2 = {
    user :{
        id:1,
        avatar : "https://i.pravatar.cc/150?img=12",
        name : "houdiafa yahia",
    },
    Group :{
        id:1,
        name:'laravel devlopers'
    },
    body : 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Molestiae non eum reiciendis minus blanditiis voluptates ad delectus saepe dignissimos, velit, rem officia cupiditate mollitia laudantium architecto deleniti placeat rerum nemo!',
    created_at :'2024/7/14 15:12'
}

</script>

<template>
    <div class="overflow-auto">
        <PostItem :post="pos1" ></PostItem>
        <PostItem :post="pos2" ></PostItem>
    </div>

</template>

<style scoped>

</style>