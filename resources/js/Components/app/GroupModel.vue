<script setup>
import { ref, computed, watch } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue';
import { XMarkIcon,  BookmarkIcon } from '@heroicons/vue/24/solid';
import TextArea from '../TextArea.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import axiosClient from '@/axiosClient.js';

const props = defineProps({
 
  modelValue: Boolean
});

let showExtentionText = ref(false);
const FormErrors = ref({});
const editor = ClassicEditor;
const editorConfig = {
  toolbar: ['heading', '|', 'bold', 'italic', '|', 'link', '|', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', 'blockquote'],
};
const emit = defineEmits(['update:modelValue', 'hide', 'create']);

const form = useForm({
  name: "",
  about: "",
  auto_approval: true,
});

function closeModal() {
  show.value = false;
  emit('hide');
  resetModel();
  form.reset();

}

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
});

function submit() {
  axiosClient.post(route('group.create'), form)
    .then(({ data }) => {
      closeModal();
      emit('create', data);
    });
}

 function processErrors(errors) {
  FormErrors.value = errors;
}



function resetModel() {
  form.reset();
  showExtentionText.value =false;
  FormErrors.value = [];
  
  emit('update:modelValue', false);
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
                  New Group 
                  <button @click="closeModal"
                    class="flex items-center justify-center cursor-pointer bg-opacity-75 hover:bg-opacity-100 text-black font-semibold shadow-sm transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-opacity-50 text-xs sm:text-sm">
                    <XMarkIcon class="h-5 w-5" />
                  </button>
                </DialogTitle>
                <div class="p-3">
               <div class="mb-3">
                <label>Group Name</label>
                <TextInput
                    type="text"
                    class="mt-1 block w-full "
                    v-model="form.name"
                    required
                    autofocus
                />            
               </div>

                <div class="mb-3">

                  <Checkbox name="remember" v-model:checked="form.auto_approval" />
                <label class="m-2">Enable auto Aprovel</label>  
                </div> 
                <div class="mb-3">
                  <label>About Group</label>
                  <TextArea v-model="form.about" class="w-full mb-3"/>
                </div>
              </div>
                <div class="grid grid-cols-4 gap-1">
                  <div class="m-1 col-span-2">
                    <button type="button"
                      class="flex  items-center justify-center   rounded-md border border-transparent bg-blue-100 w-full p-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                      @click="submit">
                      <BookmarkIcon class="w-5 h-5 mr-1" />
                      Submit
                    </button>
                  </div>
                  <div class="m-1 col-span-2">

                    <button type="button"
                        class="flex  items-center justify-center   rounded-md border border-transparent bg-blue-100 w-full p-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                        @click="closeModal">
                        <XMarkIcon class="h-5 w-5" />
                        Cancel
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
