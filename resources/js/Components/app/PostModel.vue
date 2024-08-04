<template>
  <teleport to="body">
    <TransitionRoot appear :show="show" as="template">
      <Dialog as="div" @close="closeModal" class="relative z-10">
        <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
          leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
          <div class="fixed inset-0 bg-black/25" />
        </TransitionChild>

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4 text-center">
            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
              enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
              leave-to="opacity-0 scale-95">
              <DialogPanel
                class="w-full max-w-md transform overflow-hidden rounded bg-white text-left align-middle shadow-xl transition-all">
                <DialogTitle as="h3" class="flex items-center justify-between py-3 px-4 bg-gray-100 text-lg font-medium text-gray-900">
                  Update Post
                  <button @click="closeModal"
                    class="flex items-center justify-center cursor-pointer bg-opacity-75 hover:bg-opacity-100 text-black font-semibold shadow-sm transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-opacity-50 text-xs sm:text-sm">
                    <XMarkIcon class="h-5 w-5" />
                  </button>
                </DialogTitle>
                <PostUserHeader :post="post" :showTime="false" class="mb-2 m-2" />
                <div class="p-3 mt-3">
                  <ckeditor :editor="editor" v-model="form.body" :config="editorConfig"></ckeditor>
                </div>

                <div class="m-4">
                  <button type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 w-full p-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="submit">
                    Submit
                  </button>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </teleport>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue';
import PostUserHeader from './PostUserHeader.vue';
import { XMarkIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
  post: {
    type: Object,
    required: true,
  },
  modelValue: Boolean
});
const emit = defineEmits(['update:modelValue']);
const editor = ClassicEditor;
const editorConfig = {
  toolbar: ['heading','|', 'bold', 'italic','|' ,'link','|','bulletedList', 'numberedList', '|', 'outdent', 'indent', 'blockquote'],
};

const form = useForm({
  id: props.post.id,
  body: props.post.body,
});

watch(() => props.post, (newPost) => {
  form.reset({
    id: newPost.id,
    body: newPost.body,
  });
}, { immediate: true, deep: true });

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
});

function closeModal() {
  show.value = false;
  emit('update:modelValue', false);
}

function submit() {
  form.post(route('post.update', props.post.id), {
    preserveScroll: false,
    onSuccess: () => {
      closeModal();
    },
    onError: (errors) => {
      console.log(errors);  // Log errors for debugging
    }
  });
}
</script>

<style scoped>
/* Add any additional styles here */
</style>
