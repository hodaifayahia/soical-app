<script setup>
import { Head } from '@inertiajs/vue3';
import GroupList from '@/Components/app/GroupList.vue'; // Adjust the path as necessary
import FollowingList from '@/Components/app/FollowingList.vue'; // Adjust the path as necessary
import CreatePost from '@/Components/app/CreatePost.vue'; // Adjust the path as necessary
import PostList from '@/Components/app/PostList.vue'; // Adjust the path as necessary
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'; // Adjust the path as necessary
 defineProps ({
    posts: Object,
    groups:Array,
    following:Array,
})

</script>

<template>
    <Head title="Social Media Website container" />
    <AuthenticatedLayout>
     
    <div class="grid lg:grid-cols-12 gap-3 p-2 h-full">
        <div class="lg:col-span-3 lg:order-1  h-full overflow-auto">
           <GroupList :groups="groups" />
        </div>
        <div class="lg:col-span-3 lg:order-3 bg-gray-100 h-full overflow-auto ">
            <FollowingList  :users ="following" />
        </div>
        <div class="lg:col-span-6 lg:order-2 h-full overflow-auto flex flex-col">
          <CreatePost />
          <PostList  :posts="posts.data" class="flex-1 overflow-auto"></PostList>
        </div>
    </div>
</AuthenticatedLayout>
</template>

<style>
</style>
