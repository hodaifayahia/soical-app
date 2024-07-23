<script setup>
import { computed, ref } from 'vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TabItem from '@/Pages/Profile/Partials/TabItem.vue';
import Edit from '@/Pages/Profile/Edit.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { usePage } from "@inertiajs/vue3";

const AuthUser = usePage().props.auth.user;


const props =  defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    user: {
      type: Object,
    },
});


const isMyProfile = computed(() => AuthUser && AuthUser.id === props.user.id);
 
</script>

<template>
  <AuthenticatedLayout>
    <div class="container mx-auto w-[900px] h-full overflow-auto">
      <!-- Cover Image -->
      <div class="relative h-[200px] bg-white w-full">
        <img class="h-full w-full object-cover"
          src="https://images.inc.com/uploaded_files/image/1920x1080/getty_509107562_2000133320009280346_351827.jpg"
          alt="Cover Image">

        <!-- Avatar -->
        <div class="flex ">
          <img class="ml-[48px] -mt-[64px] h-[120px] w-[120px] rounded-full border-4 border-white object-cover"
            src="https://png.pngtree.com/png-vector/20191101/ourmid/pngtree-cartoon-color-simple-male-avatar-png-image_1934459.jpg"
            alt="Avatar">
          <div class="flex flex-1 justify-between items-center    ml-4 ">
            <h1 class="font-bold text-lg">{{ user.name }}</h1>
            
            <PrimaryButton v-if="isMyProfile" class="mx-5">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
              </svg>
              Edit Profile
            </PrimaryButton>
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="py-16">
        <TabGroup>
          <TabList class="flex space-x-1 rounded bg-white p-1">
            <Tab v-if="isMyProfile" v-slot="{ selected }" as="template">
              <TabItem text="About" :selected="selected" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem text="Posts" :selected="selected" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem text="Followers" :selected="selected" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem text="Following" :selected="selected" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem text="Photos" :selected="selected" />
            </Tab>
          </TabList>
          <TabPanels class="mt-2">
            <TabPanel v-if="isMyProfile"  :class="['rounded-xl bg-white p-3 shadow', 'ring-white/60 ring-offset-2']">
              <ul>
                <Edit :must-verify-email="mustVerifyEmail" :status="status" />
              </ul>
            </TabPanel>
            <TabPanel
              :class="['rounded-xl bg-white p-3 shadow', 'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2']">
              <ul>Posts</ul>
            </TabPanel>
            <TabPanel
              :class="['bg-white p-3 shadow', 'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2']">
              <ul>Followers</ul>
            </TabPanel>
            <TabPanel :class="['bg-white p-3 shadow', 'focus:outline-none focus:ring-2']">
              <ul>Following</ul>
            </TabPanel>
            <TabPanel :class="['bg-white p-3 shadow', 'focus:outline-none focus:ring-2']">
              <ul>Photos</ul>
            </TabPanel>
          </TabPanels>
        </TabGroup>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
