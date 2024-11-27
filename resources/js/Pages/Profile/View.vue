<script setup>
import { computed, ref } from 'vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TabItem from '@/Pages/Profile/Partials/TabItem.vue';
import Edit from '@/Pages/Profile/Edit.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TabPhotos from '@/Pages/Profile/TabPhotos.vue';
import { usePage } from "@inertiajs/vue3";
import { CheckCircleIcon, XMarkIcon, CameraIcon, PencilIcon } from '@heroicons/vue/24/solid'
import { useForm } from '@inertiajs/vue3'
import DangerButton from '@/Components/DangerButton.vue';
import CreatePost from '@/Components/app/CreatePost.vue';
import PostList from '@/Components/app/PostList.vue';
import UserListItem from '@/Components/app/UserListItem.vue';
import PostAttachments from '@/Components/app/PostAttachments.vue';
import TextInput from '@/Components/TextInput.vue';

const ImageForm = useForm({
  cover: null,
  avatar: null,
})

const AuthUser = usePage().props.auth.user;
const ShowNotificatino = ref(true);
const CoverImgSrc = ref('');
const AvatarImgSrc = ref('');
const searchKeyWordfollowers = ref('');
const searchKeyWordfollowing = ref('');


const props = defineProps({
  errors: {
    type: Object
  },
  mustVerifyEmail: {
    type: Boolean,
  },
  success: {
    type: String,
  },
  user: {
    type: Object,
  },
  isCurrentUserFollower: {
    type: Boolean,
  },
  follwerCount: {
    type: Number,
  },
  posts: {
    type: Object
  },
  followers: {
    type: Array
  },
  following: {
    type: Array
  },
  Photos: {
    type: Array
  },

});


const isMyProfile = computed(() => AuthUser && AuthUser.id === props.user.id);

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

function onChangeAvatar(event) {
  ImageForm.avatar = event.target.files[0];
  if (ImageForm.avatar) {
    const reader = new FileReader();

    reader.onload = (e) => {
      AvatarImgSrc.value = e.target.result;
    };

    reader.readAsDataURL(ImageForm.avatar);
  }
}

function CancelCoverImage() {
  ImageForm.cover = null;
  CoverImgSrc.value = null;
}

function SaveCoverImage() {
  ImageForm.post(route('profile.updateimages'), {
    preserveScroll: true,
    onSuccess: (user) => {
      ShowNotificatino.value = true;
      CancelCoverImage();
      setTimeout(() => {
        ShowNotificatino.value = false;
      }, 3000);
    }
  });
}

function CancelAvatarImage() {
  ImageForm.avatar = null;
  AvatarImgSrc.value = null;
}

function SaveAvatarImage() {
  ImageForm.post(route('profile.updateimages'), {
    preserveScroll: true,
    onSuccess: (user) => {
      ShowNotificatino.value = true;
      CancelAvatarImage();
      setTimeout(() => {
        ShowNotificatino.value = false;
      }, 3000);
    }
  });
}

function followUser() {
  const form = useForm({
    follow: !props.isCurrentUserFollower
  });

  form.post(route('user.follow', props.user.id), {
    preserveScroll: true
  })
}
</script>

<template>
  <AuthenticatedLayout>
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
    <div class="container mx-auto rounded lg:w-[900px] md:w-[600px] sm:w-full h-full overflow-auto ">
      <!-- Cover Image -->
      <div class="relative bg-white w-full">
        <div class="h-[150px] sm:h-[200px]">
          <img class="h-full w-full object-cover bg-cover bg-center"
            :src="CoverImgSrc || user.cover_url || '/img/default_cover.jpg'" alt="Cover Image">
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
        <!-- Avatar and User Info -->
        <div class="py-2 flex flex-col sm:flex-row items-center sm:items-end px-4 -mt-16 sm:-mt-20">
          <div class="relative group">
            <div>
              <img
                class="w-24 h-24 sm:w-28 sm:h-28 group-hover:opacity-70  rounded-full border-4 border-white object-cover"
                :src="AvatarImgSrc || user.avatar_url || '/img/default_avatar.jpg'" alt="Avatar">
            </div>
            <div
              class="absolute top-9 left-9 cursor-pointer   text-white-700 font-semibold py-1 px-1 rounded-full transition-opacity duration-300">
              <button v-if="!AvatarImgSrc"
                class="flex items-center cursor-pointer  justify-center opacity-0 group-hover:opacity-100 px-2 py-2 rounded-full bg-gray-300 shadow-sm transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-xs sm:text-sm">
                <CameraIcon class=" text-black h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor" />
                <input type="file" @change="onChangeAvatar" class="absolute inset-0 opacity-0 cursor-pointer">
              </button>
              <template v-else>
                <div class="absolute -top-11 left-6   rounded-xl flex flex-col ">
                  <button @click="CancelAvatarImage"
                    class="flex items-center justify-center cursor-pointer bg-red-600 bg-opacity-75 hover:bg-opacity-100 py-2 px-2 text-white font-semibold   border border-gray-400 rounded-full shadow-sm transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-xs sm:text-sm">
                    <XMarkIcon class="h-5 w-5 " />
                  </button>
                  <button @click="SaveAvatarImage"
                    class="flex items-center justify-center cursor-pointer bg-emerald-600 bg-opacity-75 hover:bg-opacity-100 py-2 px-2 text-white font-semibold border border-gray-400 rounded-full shadow-sm transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 text-xs sm:text-sm">
                    <CheckCircleIcon class="h-5 w-5 " />
                  </button>
                </div>
              </template>
            </div>
          </div>
          <div class="mt-4 sm:mt-0 sm:ml-4 text-center sm:text-left">
            <h1 class="font-bold text-lg">{{ user.name }}</h1>
            <p class="text-xs text-gray-500">{{ follwerCount }} follwers</p>
          </div>
          <div class="mt-4 sm:mt-0 sm:ml-auto ">
            <template v-if="!isMyProfile">
              <PrimaryButton v-if="!isCurrentUserFollower" @click="followUser" class="text-sm">
                Follow User
              </PrimaryButton>
              <DangerButton v-else @click="followUser" class="bg-red-500 text-sm">
                UnFollow
              </DangerButton>
            </template>

            <!-- <PrimaryButton v-if="isMyProfile" class="text-sm">
              <PencilIcon class="w-4 h-4 mr-2" />
              Edit Profile
            </PrimaryButton> -->
          </div>
        </div>
      </div>

      <!-- Main Content -->
      <div class="">
        <TabGroup>
          <TabList class="flex space-x-1 rounded bg-white p-1 overflow-x-auto">
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
            <TabPanel v-if="isMyProfile" :class="['rounded-xl bg-white p-3 shadow', 'ring-white/60 ring-offset-2']">
              <ul>
                <Edit :must-verify-email="mustVerifyEmail" :status="status" />
              </ul>
            </TabPanel>
            <TabPanel
              :class="['rounded-xl bg-gray p-3 shadow rounded-xl', 'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2']">
              <template v-if="posts">
                <CreatePost />
                <PostList :posts="posts.data" class="flex-1 overflow-auto"></PostList>
              </template>
              <div v-else class="py-8 text-center">
                You Don't Have Permission to view These Posts
              </div>
            </TabPanel>
            <TabPanel
              :class="['bg-white p-3 shadow', 'ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2']">
              <div class="m-3">
                <TextInput v-model="searchKeyWordfollowers" placeholder="search for group"
                  class="mb-2 w-full p-2 border rounded" />
              </div>
              <div v-if="followers.length"class="grid grid-cols-2 bg-slate-50 ">
                <UserListItem v-for="user of followers" :user="user" class="border-2 hover:border-indigo-400" />
              </div>
              <div v-else class="p-6 bg-white border border-gray-300 rounded-lg text-center shadow-sm">
                <p class="text-gray-600 text-lg font-medium">
                  The user does not follow anybody yet.
                </p>
              </div>
            </TabPanel>
            <TabPanel :class="['bg-white p-3 shadow', 'focus:outline-none focus:ring-2']">
              <div class="m-3">
                <TextInput v-model="searchKeyWordfollowing" placeholder="search for group"
                  class="mb-2 w-full p-2 border rounded" />
              </div>
              <div v-if="following.length"class="grid grid-cols-2 bg-slate-50 ">
                <UserListItem v-for="user of following" :user="user" class="border-2 hover:border-indigo-400" />
              </div>
              <div v-else class="p-6 bg-white border border-gray-300 rounded-lg text-center shadow-sm">
                <p class="text-gray-600 text-lg font-medium">
                  The user does not follow anybody yet.
                </p>
              </div>
            

            </TabPanel>
            <TabPanel :class="['bg-white p-3 shadow', 'focus:outline-none focus:ring-2']">
              <TabPhotos :Photos="Photos" />
            </TabPanel>
          </TabPanels>
        </TabGroup>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
