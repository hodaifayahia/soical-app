<script setup>
import { ref, computed, watch } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue';
import PostUserHeader from './PostUserHeader.vue';
import { XMarkIcon, PaperClipIcon, BookmarkIcon ,ArrowUturnLeftIcon} from '@heroicons/vue/24/solid';
import { isImage } from "@/helper.js";

const props = defineProps({
  post: {
    type: Object,
    required: false,
  },
  modelValue: Boolean
});

const emit = defineEmits(['update:modelValue', 'hide']);
const attachementErrors = ref([]);
let showExtentionText = ref(false);
const FormErrors = ref({});
const attachementFiles = ref([]);
const editor = ClassicEditor;
const editorConfig = {
  toolbar: ['heading', '|', 'bold', 'italic', '|', 'link', '|', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', 'blockquote'],
};
const attachmentExtentions = usePage().props.attachmentExtentions;

const form = useForm({
  body: "",
  attachments: [],
  deleted_file_ids: [],
  _method : 'POST',
});

watch(() => props.post, () => {
  
   form.body = props.post.body || " ";
 
}, { immediate: true, deep: true });

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
});

showExtentionText = computed(()=>{
  attachementFiles.forEach(myfile => {
    let file  = myfile.file;
    let parts = file.name.split('.');
    const  ex = parts.pop().toLowerCase();
      if (!attachmentExtentions.includes(ex)) {
        return true;
      }

  });
  return false;
})
function submit() {
  // Set attachments from the attachment files

  form.attachments = attachementFiles.value.map(myfile => myfile.file);

  // Check if editing an existing post
  if (props.post.id) {
    form._method = 'PUT'; // Set method to PUT for updates
    form.post(route('post.update', props.post.id), {
      preserveScroll: true,
      onSuccess: () => {
        closeModal();
      },
      onError: (errors) => {
        processErrors(errors);
      }
    });
  } else {
    form._method = 'POST'; // Ensure method is POST for new posts
    form.post(route('post.create'), {
      preserveScroll: true,
      onSuccess: () => {
        closeModal();
        form.reset();
      },
      onError: (errors) => {
        processErrors(errors);
      }
    });
  }
}

 function processErrors(errors) {
  FormErrors.value = errors;
  for(const key in errors){
          if(key.includes('.')){
            const [ , index] = key.split('.');
            
            attachementErrors.value[index] = errors[key];
          }
        }
}





async function onAttchementChose($event) {
  for (const file of $event.target.files) {
    const myFile = {
      file,
      url: await readFile(file),
    };
    attachementFiles.value.push(myFile);
  }
  $event.target.value = ''; // Reset the file input value to allow the same file to be selected again
}

function readFile(file) {
  return new Promise((res, rej) => {
    if (isImage(file)) {
      const reader = new FileReader();
      reader.onload = () => {
        res(reader.result);
      };
      reader.onerror = rej;
      reader.readAsDataURL(file);
    } else {
      res(null);
    }
  });
}

const Computedattachments = computed(() => {  
  return [...attachementFiles.value, ...(props.post.attachments || [])];
});


function RemoveImage(myfile) {
  if (myfile.file) {
    attachementFiles.value = attachementFiles.value.filter(f => f !== myfile);
  } else {
    form.deleted_file_ids.push(myfile.id);
    myfile.deleted = true;
  }
}

function undoDelete(myfile) {
  myfile.deleted = false;
  form_deleted_ids = form_deleted_ids.filter(id => myfile.id != id )
}

function closeModal() {
  show.value = false;
  emit('hide');
  resetModel();
  form.reset();

}
function resetModel() {
  form.reset();
  showExtentionText.value =false;
  attachementErrors.value = [];
  attachementFiles.value = [];
  FormErrors.value = [];
  
  emit('update:modelValue', false);
  if (props.post.attachments) {
    props.post.attachments.forEach(file => {
      file.deleted = false;
    });
  }
  
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
                  {{ post.id ? ' Update Post' : 'Create Post' }}
                  <button @click="closeModal"
                    class="flex items-center justify-center cursor-pointer bg-opacity-75 hover:bg-opacity-100 text-black font-semibold shadow-sm transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-opacity-50 text-xs sm:text-sm">
                    <XMarkIcon class="h-5 w-5" />
                  </button>
                </DialogTitle>
                <PostUserHeader :post="post" :showTime="false" class="mb-2 m-2" />
                <div class="p-3 mt-3 ">
                  <ckeditor :editor="editor" v-model="form.body" :config="editorConfig"></ckeditor>
                 <div v-if="attachementFiles.length" class="border-l-4 px-2 border-sky-500 py-3 mt-3 bg-sky-100  border-1 text-gray-800">
                  The attachement need to be one of these extension  <br>
                  <small>{{ attachmentExtentions.join(', ') }}</small>
                </div>

                 <div v-if="FormErrors.NonNullable()" class="border-l-4 px-2 border-red-500 py-3 mt-3 bg-red-100  border-1 text-gray-800">
                  <small>{{ FormErrors.attachments }}</small>
                </div>

                  <div class="grid  gap-2  " :class="[  Computedattachments.length == 1 ?'grid-cols-1' : 'grid-cols-2']">
                    <div v-for="(MyFile ,ind) in Computedattachments" class=""> 
                      <div class="mt-4 relative group aspect-square  flex items-center justify-center bg-gray-200 border-2" :class="attachementErrors[ind] ? 'border-red-500' : ' '">
                        
                        <!-- download -->
                         
                        <button
                          class="z-20 w-8 h-8 opacity-0 group-hover:opacity-100  transition-all roundend absolute right-2 top-2  bg-gray-700 hover:bg-gray-800 transation-all text-gray-100 flex items-center justify-center">
                          <button @click="RemoveImage(MyFile)"
                            class="flex items-center justify-center cursor-pointer bg-red-600 bg-opacity-75 hover:bg-opacity-100 py-2 px-2 text-white font-semibold   border border-gray-400 rounded-full shadow-sm transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-xs sm:text-sm">
                            <XMarkIcon class="h-5 w-5 " />
                          </button>
                          <!-- end download -->
                        </button>
                        <div v-if="MyFile.deleted"  class="absolute right-0 left-0 bottom-0 py-2 text-center  z-10 text-sm bg-black text-white" >
                         <ArrowUturnLeftIcon @click="undoDelete(MyFile)" class="h-5 w-5 text-black  cursor-pointer absolute  right-2 bg-white py-1 px-1 rounded-full "/>  to be deleted
                        </div>
                        <img v-if="isImage(MyFile.file || MyFile)" class=" object-fill	 aspect-square" :src="MyFile.url" :class="MyFile.deleted ?'opacity-50' : ''">
                        <div v-else class="flex justify-center items-center flex-col "
                         :class="MyFile.deleted ?'opacity-50' : ' '">
                          <PaperClipIcon class=" w-16 h-16 " />
                          <small class="text-black">{{MyFile.name}}</small>
                        </div>

                      </div>
                      <small class="text-red-500 flex items-center justify-center  " >{{ attachementErrors[ind] }}</small> 

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
