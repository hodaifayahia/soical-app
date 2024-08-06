
<script setup>
import {ref, computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue';
import PostUserHeader from './PostUserHeader.vue';
import { XMarkIcon ,PaperClipIcon , BookmarkIcon } from '@heroicons/vue/24/solid';
import { isImage } from "@/helper.js";
const props = defineProps({
  post: {
    type: Object,
    required: true,
  },
  modelValue: Boolean
});
const emit = defineEmits(['update:modelValue']);
// file 
// file and scr
const attachementFiles = ref([]);
const editor = ClassicEditor;
const editorConfig = {
  toolbar: ['heading','|', 'bold', 'italic','|' ,'link','|','bulletedList', 'numberedList', '|', 'outdent', 'indent', 'blockquote'],
};

const form = useForm({
  id: null,
  body: "",
  attachements: [],
});

watch(() => props.post, () => {
  form.reset({
    id: props.post.id,
    body: props.post.id,
  });
}, { immediate: true, deep: true });

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
});

function closeModal() {
  show.value = false;
  form.reset();
  attachementFiles.value =[];
  emit('update:modelValue', false);
}

function submit() {
  form.attachements = attachementFiles.value.map(myfile => myfile.file)
  if (form.id) {
    form.post(route('post.update', props.post.id), {
      preserveScroll: false,
      onSuccess: () => {
        closeModal();
        form.reset();
      },
      onError: (errors) => {
        console.log(errors);  // Log errors for debugging
      }
    });
    
  } else {
   preserveScroll: false,
  form.post(route('post.create'),{
    onSuccess:()=>{
      closeModal();
      form.reset();
    }
  });

  }
}
async function onAttchementChose($event) {
  console.log($event.target.files);
  for(const file of $event.target.files){
    const myFile ={
      file,
      url: await readFile(file),
    }
    attachementFiles.value.push(myFile);
     
  }
  $event.target.files = null;
}
function readFile(file) {
  return new Promise((res , rej ) => {
    if (isImage(file)) {
      const reader = new FileReader();
      reader.onload = () =>{
        res(reader.result)
      }
      reader.onerror = rej;
      reader.readAsDataURL(file)
    }else { 
      res(null);
    }
  })
}
function RemoveImage(myfile) {
  console.log(attachementFiles.value.filter(f=> f != myfile));
  
  attachementFiles.value = attachementFiles.value.filter(f=> f != myfile)
}
</script>

<template>
  <teleport to="body ">
    <TransitionRoot appear :show="show" as="template">
      <Dialog as="div" @close="closeModal" class="relative z-50">
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
                <DialogTitle as="h3"
                  class="flex items-center justify-between py-3 px-4 bg-gray-100 text-lg font-medium text-gray-900">
                  {{ form.id ? ' Update Post' : 'Create Post' }}
                  <button @click="closeModal"
                    class="flex items-center justify-center cursor-pointer bg-opacity-75 hover:bg-opacity-100 text-black font-semibold shadow-sm transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-opacity-50 text-xs sm:text-sm">
                    <XMarkIcon class="h-5 w-5" />
                  </button>
                </DialogTitle>
                <PostUserHeader :post="post" :showTime="false" class="mb-2 m-2" />
                <div class="p-3 mt-3 ">
                  <ckeditor :editor="editor" v-model="form.body" :config="editorConfig"></ckeditor>
                  <div class="grid  gap-2  " :class="[  attachementFiles.length == 1 ?'grid-cols-1' : 'grid-cols-2']">
                    <div v-for="(MyFile) in attachementFiles" class="">

                      <div class="mt-4 relative group aspect-square  flex items-center justify-center bg-gray-200">
                        
                        <!-- download -->
                        <button
                          class="z-20 w-8 h-8 opacity-0 group-hover:opacity-100  transition-all roundend absolute right-2 top-2  bg-gray-700 hover:bg-gray-800 transation-all text-gray-100 flex items-center justify-center">
                          <button @click="RemoveImage(MyFile)"
                            class="flex items-center justify-center cursor-pointer bg-red-600 bg-opacity-75 hover:bg-opacity-100 py-2 px-2 text-white font-semibold   border border-gray-400 rounded-full shadow-sm transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-xs sm:text-sm">
                            <XMarkIcon class="h-5 w-5 " />
                          </button>
                          <!-- end download -->
                        </button>
                        <img v-if="isImage(MyFile.file)" class=" object-fill	 aspect-square" :src="MyFile.url">
                        <template v-else>
                          <PaperClipIcon class=" w-16 h-16 " />
                        </template>

                      </div>
                    </div>
                  </div>
                </div>
                <div class="grid grid-cols-4 gap-1">
                  <div class="m-3 col-span-2">
                    <button type="button"
                      class="flex  items-center justify-center   rounded-md border border-transparent bg-blue-100 w-full p-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                      @click="submit">
                      <BookmarkIcon class="w-5 h-5 mr-1" />
                      Submit
                    </button>
                  </div>
                  <div class="m-3 col-span-2">
                    <button type="button"
                      class="flex items-center justify-center relative rounded-md border border-transparent bg-blue-100 w-full p-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                      @click="submit">
                      <PaperClipIcon class="w-5 h-5 mr-1" />
                      attachement
                      <input type="file" multiple class="absolute top-0 left-0 right-0 bottom-0 opacity-0" @click.stop
                        @change="onAttchementChose">
                    </button>
                  </div>
                </div>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </teleport>
</template>


<style scoped>
/* Add any additional styles here */
</style>
