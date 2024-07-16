<script setup>
import { ref } from 'vue';
import TextInput from '@/Components/TextInput.vue';
import FollowingItem from '@/Components/app/FollowingItem.vue';
    const searchKeyWord = ref('');
</script>
<template >
  <TextInput v-model="searchKeyWord" placeholder="search for group" class="mb-2 w-full p-2 border rounded" />
     <div class="py-8 h-[200px] lg:flex-1 overflow-auto mt-1">
       <div class="text-gray-400 text-center p-1" v-if="false">You're not joined to any groups yet</div>
       <div v-else>
        <FollowingItem
            Image="https://picsum.photos/100"
            Title="Laravel"
            Description="Lorem ipsum dolor sit amet consectetur"
          ></FollowingItem>
        <FollowingItem
            Image="https://picsum.photos/100"
            Title="Laravel"
            Description="Lorem ipsum dolor sit amet consectetur"
          ></FollowingItem>
        <FollowingItem
            Image="https://picsum.photos/100"
            Title="Laravel"
            Description="Lorem ipsum dolor sit amet consectetur"
          ></FollowingItem>
        <FollowingItem
            Image="https://picsum.photos/100"
            Title="Laravel"
            Description="Lorem ipsum dolor sit amet consectetur"
          ></FollowingItem>
        <FollowingItem
            Image="https://picsum.photos/100"
            Title="Laravel"
            Description="Lorem ipsum dolor sit amet consectetur"
          ></FollowingItem>
         
       </div>
     </div>
</template>

<style>
</style>