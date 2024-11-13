<script setup>

import TextArea from '../TextArea.vue';
import TextInput from '@/Components/TextInput.vue';
import Checkbox from '@/Components/Checkbox.vue';

defineProps({
    form: Object
});
</script>

<template>
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
   

</template>

<style>

</style>