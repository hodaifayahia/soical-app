<script setup>
import GroupItem from '@/Components/app/GroupItem.vue';
import UserListItem from '@/Components/app/UserListItem.vue';
import PostList from '@/Components/app/PostList.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

    const props = defineProps({
        posts:{
            type:Array
        },
        users:{
            type:Array
        },
        groups:{
            type:Array
        },
        keywords:{
            type:String
        },

    })


</script>


<template>
    <AuthenticatedLayout>
    <div class="p-3">
    <div v-if="!keywords.startsWith('#')" class="grid lg:grid-cols-2 sm:grid-cols-1 gap-2">
        <div>
            <h2 class="text-lg font-bold roundend  ">Users</h2>
            <div v-if="users.length"  class="grid-cols-1 bg-slate-50 p-2">
                <UserListItem   v-for="user in users"  :user="user" />

            </div>
            <div v-else  class="grid-cols-1 bg-slate-50 p-2 ">
                    <div  class="py-8 text-center ">
                        There no groups 
                    </div>
            </div>
            
        </div>
        <div>
            <h2 class="text-lg font-bold">Gorups</h2>
            <div v-if="groups.length" class="grid-cols-1 bg-slate-50 p-2">
                <GroupItem v-for="group in groups" :key="group.id" :group="group" />

            </div>
            <div  v-else  class="grid-cols-1 bg-slate-50 p-2">
                <div  class="py-8 text-center">
            There no groups 
        </div>
            </div>
        </div>
    </div>
    <div class="bg-slate-50 p-2 mt-4">
        <h2 class="text-lg font-bold">Posts</h2>
        <PostList :posts="posts.data" class="flex-1 overflow-auto"></PostList>
        <div  class="py-8 text-center">
            There no posts 
        </div>
    </div>
   </div>  
</AuthenticatedLayout>

</template>

<style>
</style>