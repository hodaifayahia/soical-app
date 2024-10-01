<script setup>

import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import GroupListItems from '@/Components/app/GroupListItems.vue' ;
import GroupModel from '@/Components/app/GroupModel.vue';
import { ref } from 'vue'

const ShowNewGroupModel = ref(false);

</script>
<template>
  <div class="text-center p-2 bg-white rounded  lg:h-full overflow-hidden flex flex-col">
    <!-- Small screens: Use Disclosure -->
    <div class="block lg:hidden">
      <Disclosure>
        <template v-slot="{ open }">
          <div>
            <DisclosureButton class="w-full">
              <div class="flex justify-between items-center w-full">
                <div>
                  <h2 class="text-xl font-bold m-3">My Groups</h2>
                </div> 
                <div class="m-3 ">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                      stroke="currentColor" class="size-5" :class="open ? 'size-5 rotate-180 transform transition-all ' : ' '">
                      <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                      </svg>
                </div>
            </div>

            </DisclosureButton>
            <DisclosurePanel>
             
                 <GroupListItems/>
            </DisclosurePanel>
          </div>
        </template>
      </Disclosure>
    </div>
    <!-- Large screens: Always show content -->
    <div class="hidden lg:flex flex-col flex-1 h-full ">
        <div class="flex justify-between items-center">
          <h2 class="text-xl font-bold m-8">My Group </h2>
          <button @click="ShowNewGroupModel = true" class="bg-indigo-500 hover:bg-indigo-600 px-1 py-2 text-white  rounded text-sm ml-2">New Group</button>
        </div>
        <GroupListItems />
    </div>
    <GroupModel  v-model="ShowNewGroupModel"  />
  </div>
</template>


<style scoped>
/* Add your styles here */
</style>
