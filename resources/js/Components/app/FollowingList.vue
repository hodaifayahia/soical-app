<script setup>

import { Disclosure, DisclosureButton, DisclosurePanel } from '@headlessui/vue';
import FollowingListItems from '@/Components/app/FollowingListItems.vue' ;

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
                  <h2 class="text-xl font-bold m-3">My Following </h2>
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
             
                 <FollowingListItems/>
            </DisclosurePanel>
          </div>
        </template>
      </Disclosure>
    </div>
    <!-- Large screens: Always show content -->
    <div class="hidden lg:flex flex-col flex-1 h-full overflow-auto ">
       <h2 class="text-xl font-bold m-8">My Following </h2>
        <FollowingListItems/>
    </div>
  </div>
</template>


<style scoped>
/* Add your styles here */
</style>
