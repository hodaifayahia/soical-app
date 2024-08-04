<script setup>
import TextArea from '@/Components/TextArea.vue';
import PostModel from '@/Components/app/PostModel.vue'; // Make sure this path is correct
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const AuthUser = usePage().props.auth.user;
const ShowModel = ref(false);
const NewPost = ref({
  id: null,
  body: '',
  user: AuthUser,
});

function ShowCreatePostModel() {
  ShowModel.value = true;
}
</script>

<template>
  <div class="p-2 bg-white rounded shadow-sm mb-4">
    <div
      @click="ShowCreatePostModel"
      class="py-4 px-2 text-gray-700 border border-gray-400 rounded mb-3 cursor-pointer hover:bg-gray-50 w-full"
      rows="1"
    >What's on your mind?</div>
    
    <PostModel :post="NewPost" v-model="ShowModel" />
  </div>
</template>