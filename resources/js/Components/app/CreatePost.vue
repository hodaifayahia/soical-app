<script>
import { ref } from 'vue';

export default {
  setup() {
    const postCreating = ref(false);
    const togglePostCreating = () => {
      postCreating.value = !postCreating.value;
    };

    return {
      postCreating,
      togglePostCreating
    };
  }
};
</script>

<template>
  <div class=" p-2 bg-white  rounded shadow-sm mb-4">
    <div @click="togglePostCreating" class="py-4 px-2 text-gray-700 border border-gray-400 rounded mb-3 cursor-pointer hover:bg-gray-200">
      Create a New Post
    </div>
    <div v-if="postCreating" class="flex justify-between items-center">
      <button class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        Attach File
      </button>
      <button class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
        Submit
      </button>
    </div>
  </div>
</template>

<style scoped>
/* Add your styles here */
</style>
