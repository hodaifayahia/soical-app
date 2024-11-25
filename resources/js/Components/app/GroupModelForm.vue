<script setup>

import TextArea from '../TextArea.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

defineProps({
    form: Object,
    disable:Boolean,
});
const editor = ClassicEditor;
const editorConfig = {
  toolbar: ['heading', '|', 'bold', 'italic', '|', 'link', '|', 'bulletedList', 'numberedList', '|', 'outdent', 'indent', 'blockquote'],
};
</script>

<template>
      <div class="mb-3">
                <label>Group Name</label>
                <TextInput
                    type="text"
                    class="mt-1 block w-full  "
                    v-model="form.name"
                    :disabled="disable"
                    required
                    autofocus
                    
                />            
               </div>

                <div class="mb-3">

                 <Checkbox :disabled="disable"
                 name="remember" v-model:checked="form.auto_approval" />
                 <label class="m-2">Enable auto Aprovel</label>  
                </div> 
                <div class="mb-3">
                  <label>About Group</label>
                  <ckeditor :editor="editor" v-model="form.about" :config="editorConfig"></ckeditor>
                </div>
   

</template>

<style>

</style>