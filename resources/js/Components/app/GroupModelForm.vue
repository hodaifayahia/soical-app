<script setup>

import TextArea from '../TextArea.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';

defineProps({
    form: Object,
    disable:Boolean,
});
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
                  <TextArea  :class="[{ 'disabled': disable }]"
                  :disabled="disable" v-model="form.about" class="w-full mb-3"/>
                </div>
   

</template>

<style>

</style>