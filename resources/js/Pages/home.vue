<script setup>
import { Head } from '@inertiajs/vue3';
import GroupList from '@/Components/app/GroupList.vue'; // Adjust the path as necessary
import FollowingList from '@/Components/app/FollowingList.vue'; // Adjust the path as necessary
import CreatePost from '@/Components/app/CreatePost.vue'; // Adjust the path as necessary
import PostList from '@/Components/app/PostList.vue'; // Adjust the path as necessary


</script>

<template>
    <Head title="Social Media Website" />
    <div class="grid lg:grid-cols-12 gap-3 p-2 h-full">
        <div class="lg:col-span-3 lg:order-1  h-full overflow-auto">
           <GroupList />
        </div>
        <div class="lg:col-span-3 lg:order-3 bg-gray-100 h-full overflow-auto ">
            <FollowingList  />
        </div>
        <div class="lg:col-span-6 lg:order-2 h-full overflow-auto flex flex-col">
          <CreatePost />
          <PostList class="flex-1 overflow-auto"></PostList>
        </div>
    </div>
</template>

<style>
</style>
