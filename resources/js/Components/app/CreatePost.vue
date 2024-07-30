<script setup>
import TextArea from '@/Components/TextArea.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const postCreating = ref(false);
const NewPostForm = useForm({
  body :''
})
function submit() {
  NewPostForm.post(route('post.create'),{
    onSuccess:()=>{
      NewPostForm.reset();
    }
  });
}
</script>

<template>
  <div class="p-2 bg-white rounded shadow-sm mb-4">
    <TextArea
      @click="postCreating = true" 
      class="py-4 px-2 text-gray-700 border border-gray-400 rounded mb-3 cursor-pointer hover:bg-gray-50 w-full"
      v-model="NewPostForm.body"
      rows="1"
      placeholder="What's on your mind?"
    />
    
    <div v-if="postCreating">
      <div class="flex justify-between items-center">
        <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
          Attach File
        </button>
        <button @click = 'submit' class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
          Submit
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Add your styles here */
</style>