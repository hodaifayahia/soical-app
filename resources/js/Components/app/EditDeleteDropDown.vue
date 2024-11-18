<script setup>
import { Menu, MenuButton, MenuItems, MenuItem } from '@headlessui/vue';
import { PencilIcon, TrashIcon, EllipsisVerticalIcon, EyeIcon, ClipboardIcon } from '@heroicons/vue/20/solid';
import { usePage  ,Link} from '@inertiajs/vue3';
import {  computed ,ref } from "vue";


defineEmits(['edit','delete']);
const props = defineProps({
    posts:{
        type:Object,
        default:null, 
    },
    comment:{
        type:Object,
        default:null, 
    },
})

const Authuser = usePage().props.auth.user.id;
const user = computed(() => {
    return props.comment?.user || props.posts?.user;
});

const editIsAllowed = computed(()=>{
    return user.value.id == Authuser;
});
const deletedIsAllowed = computed(() => {
    if (user.value.id == Authuser) return true; // User is the owner of the post/comment.
    if (props.posts.user.id == Authuser) return true; // Authenticated user owns the post.

    return !props.comment && props.posts.group?.role == 'ADMIN'; // Admin in a group can delete a post.
});


const textToCopy = ref(route('post.view',props.posts.id))
const copySuccess = ref(false)
const buttonText = ref('Copy to Clipboard')

const copyToClipboard = async () => {
  try {
    await navigator.clipboard.writeText(textToCopy.value)
    copySuccess.value = true
    buttonText.value = 'Copied!'
    
    // Reset button state after 2 seconds
    setTimeout(() => {
      copySuccess.value = false
      buttonText.value = 'Copy to Clipboard'
    }, 2000)
  } catch (err) {
    console.error('Failed to copy text: ', err)
    buttonText.value = 'Failed to copy'
  }
}



</script>

<template>
        <Menu as="div" class="relative inline-block text-left">
                    <div>
                        <MenuButton
                            class="inline-flex w-full justify-center rounded-mdtext-sm font-medium text-white focus:outline-none focus-visible:ring-2 focus-visible:ring-white/75">

                            <EllipsisVerticalIcon class=" h-6 w-6 text-black hover:text-black" aria-hidden="true" />
                        </MenuButton>
                    </div>
                    

                    <transition enter-active-class="transition duration-100 ease-out"
                        enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
                        leave-active-class="transition duration-75 ease-in"
                        leave-from-class="transform scale-100 opacity-100"
                        leave-to-class="transform scale-95 opacity-0">
                        <MenuItems
                            class="absolute z-20 right-0 mt-2 w-36 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none">
                            <div class="px-1 py-1">
                                <MenuItem v-if="editIsAllowed" v-slot="{ active }">
                                <button  @click="$emit('edit')" :class="[

                                    active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]">
                                    <PencilIcon :active="active" class="mr-2 h-5 w-5 text-indigo-400"
                                        aria-hidden="true" />
                                    Edit
                                </button>
                                </MenuItem>

                            </div>
                            <div class="px-1 py-1">
                                <MenuItem v-if="deletedIsAllowed" v-slot="{ active }">
                                <Link   :class="[
                                    active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]" :href="route('post.view',posts.id)">
                                    <EyeIcon :active="active" class="mr-2 h-5 w-5 text-indigo-400"
                                        aria-hidden="true" />
                                    Open Post
                                </Link>
                                </MenuItem>
                            </div>
                            <div class="px-1 py-1">
                                <MenuItem v-if="deletedIsAllowed" v-slot="{ active }">
                                <button   :class="[
                                    active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]" @click="copyToClipboard">
                                    <ClipboardIcon :active="active" class="mr-2 h-5 w-5 text-indigo-400"
                                        aria-hidden="true" />
                                    
                                 Copy URL</button>
                                </MenuItem>
                            </div>

                            <div class="px-1 py-1">
                                <MenuItem v-if="deletedIsAllowed" v-slot="{ active }">
                                <button @click="$emit('delete')" :class="[
                                    active ? 'bg-indigo-500 text-white' : 'text-gray-900',
                                    'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                                ]">
                                    <TrashIcon :active="active" class="mr-2 h-5 w-5 text-indigo-400"
                                        aria-hidden="true" />
                                    Delete
                                </button>
                                </MenuItem>
                            </div>
                          
                        </MenuItems>
                    </transition>
                </Menu>
</template>