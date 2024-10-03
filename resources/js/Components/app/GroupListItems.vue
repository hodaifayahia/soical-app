 <script setup>
 import TextInput from '@/Components/TextInput.vue';
 import GroupItem from '@/Components/app/GroupItem.vue';
 import GroupModel from '@/Components/app/GroupModel.vue';
import { ref } from 'vue';

const ShowNewGroupModel = ref(false);
 const searchKeyWord = ref('');
 const props = defineProps({
    groups:Array
 })
 function onGroupCreate(group) {
    if (Array.isArray(props.groups)) {
        props.groups.unshift(group); // Directly unshift if it's a regular array
    }
}




 </script>
<template >
  <div class="flex gap-2 items-center  justify-center">
    <TextInput v-model="searchKeyWord" placeholder="search for group" class=" w-full p-2 border rounded" />
    <button @click="ShowNewGroupModel = true" class="bg-indigo-500 hover:bg-indigo-600 px-1 py-2 items-center  w-[150px] text-white  rounded text-sm ">New Group</button>

  </div>
  
      <div class="py-8 h-[200px] lg:flex-1 overflow-auto mt-1">
        <div class="text-gray-400 text-center p-1" v-if="false">You're not joined to any groups yet</div>
        <div v-else>
          <GroupItem v-for="group of groups"  :group="group"></GroupItem>
        </div>
      </div>
      <GroupModel v-model="ShowNewGroupModel" @create="onGroupCreate" />

</template>

<style>
</style>