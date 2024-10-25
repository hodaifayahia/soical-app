<script setup>
import { computed, ref } from 'vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TabItem from '@/Pages/Profile/Partials/TabItem.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { usePage } from "@inertiajs/vue3";
import { CheckCircleIcon, XMarkIcon, CameraIcon } from '@heroicons/vue/24/solid'
import { useForm } from '@inertiajs/vue3'
import InviteGroupModel from '@/Pages/group/InviteGroupModel.vue';
import UserListItem from '@/Components/app/UserListItem.vue';
import TextInput from '@/Components/TextInput.vue';

const ImageForm = useForm({
  cover: null,
  thumbnail: null,
})

const AuthUser = usePage().props.auth.user;
const ShowNotificatino = ref(true);
const ShowInviteGroupModel = ref(false);
const CoverImgSrc = ref('');
const thumbnailImgSrc = ref('');

const props = defineProps({
  errors: {
    type: Object
  },
  success: {
    type: String,
  },
  group: {
    type: Object,
  },
  users: {
    type: Array,
  },
  Requests: {
    type: Array,
  },
});
const isCurrentUserAdmin = computed(() => props.group.role == 'Admain');
const isJoinToGroup = computed(() => props.group.role  && props.group.status =="APPROVED");


function onChangeCover(event) {
  ImageForm.cover = event.target.files[0];
  if (ImageForm.cover) {
    const reader = new FileReader();

    reader.onload = (e) => {
      CoverImgSrc.value = e.target.result;
    };

    reader.readAsDataURL(ImageForm.cover);
  }
}

function onChangethumbnail(event) {
  ImageForm.thumbnail = event.target.files[0];
  if (ImageForm.thumbnail) {
    const reader = new FileReader();

    reader.onload = (e) => {
      thumbnailImgSrc.value = e.target.result;
    };

    reader.readAsDataURL(ImageForm.thumbnail);
  }
}

function CancelCoverImage() {
  ImageForm.cover = null;
  CoverImgSrc.value = null;
}

function SaveCoverImage() {
  ImageForm.post(route('group.updateimages',props.group.slug), {
    onSuccess: (group) => {
      ShowNotificatino.value= true;
      CancelCoverImage();
      setTimeout(() => {
        ShowNotificatino.value = false;
      }, 3000);
    }
  });
}

function CancelThumbnailImage() {
  ImageForm.thumbnail = null;
  thumbnailImgSrc.value = null;
}

function SaveThumbnailImage() {
  ImageForm.post(route('group.updateimages',props.group.slug), {
    onSuccess: (group) => {
      ShowNotificatino.value= true;
      CancelThumbnailImage();
      setTimeout(() => {
        ShowNotificatino.value = false;
      }, 3000);
    }
  });
}

function jointogroup() {
  const form = useForm([]);

  // Pass the slug as the second argument to the route function
  form.post(route('group.join', { group: props.group.slug }));
}


function approveRequest(user) {
  console.log(user);
  
  const form = useForm({
    'user_id' : user.id,
    'action' : 'accept'
  });
  form.post(route('group.joinRequest' , props.group.slug));
}
function RejectRequest(user) {  
  const form = useForm({
    'user_id' : user.id ,
    'action' : 'reject'
  });
  form.post(route('group.joinRequest' , props.group.slug));
}

</script>

<template>
  <AuthenticatedLayout>
    <div>

   
    <transition enter-active-class="transition ease-out duration-300 transform"
      enter-from-class="opacity-0 translate-y-2" enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
      <div v-show="ShowNotificatino && success"
        class="container mx-auto lg:w-[900px] md:w-[600px] sm:w-full my-4 rounded-md shadow-md bg-emerald-100 border-l-4 border-emerald-500">
        <div class="flex items-center justify-between p-4">
          <div class="flex items-center">
            <CheckCircleIcon class="w-6 h-6 text-emerald-500 mr-3" fill="none" stroke="currentColor"
              viewBox="0 0 24 24" />
            <p class="font-medium text-sm text-emerald-700">
              {{ success }}
            </p>
          </div>
        </div>
      </div>
    </transition>
    <transition enter-active-class="transition ease-out duration-300 transform"
      enter-from-class="opacity-0 translate-y-2" enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
      <div v-show="errors.cover"
        class="container mx-auto lg:w-[900px] md:w-[600px] sm:w-full my-4 rounded-md shadow-md bg-red-100 border-l-4 border-red-500">
        <div class="flex items-center justify-between p-4">
          <div class="flex items-center">
            <CheckCircleIcon class="w-6 h-6 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" />
            <p class="font-medium text-sm text-red-700">
              {{ errors.cover }}
            </p>
          </div>
        </div>
      </div>
    </transition>
    <transition enter-active-class="transition ease-out duration-300 transform"
      enter-from-class="opacity-0 translate-y-2" enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
      <div v-show="errors.avatar"
        class="container mx-auto lg:w-[900px] md:w-[600px] sm:w-full my-4 rounded-md shadow-md bg-red-100 border-l-4 border-red-500">
        <div class="flex items-center justify-between p-4">
          <div class="flex items-center">
            <CheckCircleIcon class="w-6 h-6 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" />
            <p class="font-medium text-sm text-red-700">
              {{ errors.avatar }}
            </p>
          </div>
        </div>
      </div>
    </transition>
  </div>
    <div class="container mx-auto rounded lg:w-[900px] md:w-[600px] sm:w-full h-full overflow-auto ">
      <!-- Cover Image -->
      <div class="relative bg-white w-full">
        <div class="h-[150px] sm:h-[200px]">
          <img class="h-full w-full object-cover bg-cover bg-center"
            :src="CoverImgSrc || group.cover_url || '/img/default_cover.jpg'" alt="Cover Image">
        </div>
        <div class="absolute top-2 right-2">
          <button v-if="!CoverImgSrc"
            class=" bg-white bg-opacity-75 hover:bg-opacity-100 text-gray-700 font-semibold py-1 px-2 sm:py-2 sm:px-4 border border-gray-400 rounded-lg shadow-sm transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-xs sm:text-sm">
            <span class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mr-1 sm:mr-2" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
              <span class="hidden sm:inline">Update Cover Image</span>
              <span class="sm:hidden">Update</span>
            </span>
            <input type="file" @change="onChangeCover" class="absolute inset-0 opacity-0 cursor-pointer">
          </button>
          <template v-else class="flex whitespace-nowrap">
            <button @click="CancelCoverImage"
              class=" cursor-pointer bg-white bg-opacity-75 hover:bg-opacity-100 text-gray-700 font-semibold py-1 px-2 sm:py-2 sm:px-4 border border-gray-400 rounded-lg shadow-sm transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-xs sm:text-sm">
              <span class="flex items-center">
                <XMarkIcon class="h-5 w-5 mr-2" />
                <span>Cancel</span>
              </span>
            </button>
            <button @click="SaveCoverImage"
              class="ml-2  cursor-pointer bg-gray-600 bg-opacity-75 hover:bg-opacity-100 text-white font-semibold py-1 px-2 sm:py-2 sm:px-4 border border-gray-400 rounded-lg shadow-sm transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-xs sm:text-sm">
              <span class="flex items-center">
                <CheckCircleIcon class="h-5 w-5 mr-2" />
                <span>Save</span>
              </span>
            </button>
          </template>
        </div>
        <!-- Thumbnail and User Info -->
        <div class="py-2 flex flex-col sm:flex-row items-center sm:items-end px-4  -mt-16 sm:-mt-20">
          <div class="relative group">
            <div>
              <img class="w-24 h-24 sm:w-32 sm:h-28 group-hover:opacity-70    rounded-full border-4 border-white object-cover"
                :src="thumbnailImgSrc || group.thumbnail_url || '/img/default_user.jpg'" alt="Thumbnail">
            </div>
            <div
              class="absolute top-9 left-9 cursor-pointer   text-white-700 font-semibold py-1 px-1 rounded-full transition-opacity duration-300">
              <button v-if="!thumbnailImgSrc"
                class="flex items-center cursor-pointer  justify-center opacity-0 group-hover:opacity-100 px-2 py-2 rounded-full bg-gray-300 shadow-sm transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-xs sm:text-sm">
                <CameraIcon class=" text-black h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor" />
                <input type="file" @change="onChangethumbnail" class="absolute inset-0 opacity-0 cursor-pointer">
              </button>
              <template v-else>
                <div class="absolute -top-11 left-6   rounded-xl flex flex-col ">
                  <button @click="CancelThumbnailImage"
                    class="flex items-center justify-center cursor-pointer bg-red-600 bg-opacity-75 hover:bg-opacity-100 py-2 px-2 text-white font-semibold   border border-gray-400 rounded-full shadow-sm transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-xs sm:text-sm">
                    <XMarkIcon class="h-5 w-5 " />
                  </button>
                  <button @click="SaveThumbnailImage"
                    class="flex items-center justify-center cursor-pointer bg-emerald-600 bg-opacity-75 hover:bg-opacity-100 py-2 px-2 text-white font-semibold border border-gray-400 rounded-full shadow-sm transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-xs sm:text-sm">
                    <CheckCircleIcon class="h-5 w-5 " />
                  </button>
                </div>
              </template>
            </div>
          </div>
          <div class="w-full mt-4 sm:mt-0 sm:ml-4 text-center ">
            <div class="flex justify-between">
              <h1 class="font-bold text-lg">{{ group.name }}</h1>
            

              <PrimaryButton  

                            v-if="!AuthUser" class="sm:text-right"
                            :href="route('login')"
                            >
                            Login to join to the group
              </PrimaryButton>

              <PrimaryButton @click="ShowInviteGroupModel = true" 
                            v-if="AuthUser && isCurrentUserAdmin" class="sm:text-right">
                            Invite Users
              </PrimaryButton>
              <PrimaryButton @click="jointogroup" v-if="AuthUser && !group.role && props.group.auto_approval" class="sm:text-right">join to Group</PrimaryButton>
              
              <PrimaryButton @click="jointogroup" v-if="AuthUser && !group.role && !props.group.auto_approval" class="sm:text-right">Request To Join</PrimaryButton>
            </div>

          </div>
         
        </div>
      </div>

      <!-- Main Content -->
      <div class="">
        <TabGroup>
          <TabList class="flex space-x-1 rounded bg-white p-1 overflow-x-auto">
            <Tab v-slot="{ selected }" as="template">
              <TabItem text="Posts" :selected="selected" />
            </Tab>
            <Tab v-if="isJoinToGroup" v-slot="{ selected }" as="template">
              <TabItem text="User" :selected="selected" />

            </Tab>
            <Tab v-if="isCurrentUserAdmin" v-slot="{ selected }" as="template">
              <TabItem text="Pendding Users" :selected="selected" />
            </Tab>
            <Tab v-slot="{ selected }" as="template">
              <TabItem text="Photos" :selected="selected" />
            </Tab>
          </TabList>
          <TabPanels class="mt-2">
           
            <TabPanel
              :class="['rounded-xl bg-white p-3 shadow', 'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2']">
              <ul>Posts</ul>
            </TabPanel>

            <TabPanel
            v-if="isJoinToGroup"
              :class="['bg-white p-1 shadow', '   ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2']">
              <div class="m-3">
                <TextInput v-model="searchKeyWord" placeholder="search for group" class="mb-2 w-full p-2 border rounded" />
              </div>
              <div class="grid grid-cols-2 bg-slate-50 ">

                <UserListItem  v-for="user of users" 
                              :user="user" 
                              :key="user.id" 
                              class="border-2 hover:border-indigo-400"
                              />


              </div>
            </TabPanel>
            <TabPanel v-if="isCurrentUserAdmin" :class="['bg-white p-3 shadow', 'focus:outline-none focus:ring-2']">
              <div v-if="Requests.length">
                <UserListItem  v-for="request of Requests"
                                    :user="request" 
                                    :key="request.id"
                                    :forApproved="true"
                                    @approved="approveRequest(request)"
                                    @reject="RejectRequest(request)"
                                    />
              </div>
              <div class="py-8 text-center" v-else>
                  There are no Pending Requests
              </div>
              </TabPanel>
            <TabPanel :class="['bg-white p-3 shadow', 'focus:outline-none focus:ring-2']">
              <ul>Photos</ul>
            </TabPanel>
          </TabPanels>
        </TabGroup>
      </div>
      <InviteGroupModel v-model="ShowInviteGroupModel" />
    </div>
  </AuthenticatedLayout>
</template>
