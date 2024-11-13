<script setup>
import { Link } from '@inertiajs/vue3';
import { ChevronRightIcon } from '@heroicons/vue/20/solid';


defineProps({
    post:{
        type: Object
    },
    showTime:{
        type: Boolean,
        default: true,
    }
})
</script>
<template>

    <div class="flex mt-4">
        <Link :href="route('profile',post.user.username)">
            <img :src="post.user.avatar_url" alt="User image"
                class="w-10 h-10 rounded-full mr-3 border-2 transition-all hover:border-cyan-400">
        </Link>
        <div>
            <div>
                <Link :href="route('profile',post.user.username)" class="font-semibold hover:underline">{{ post.user.name }} </Link>
                  
                <template v-if="post.group">
                        <ChevronRightIcon class="w-5 inline"  ></ChevronRightIcon>
                        <a :href="route('group.profile',post.group.slug)" class="hover:underline">
                            {{ post.group.name }}
                        </a>
                    </template>
                
            </div>
            <small v-if="showTime" class="text-gray-400">{{ post.updated_at }}</small>
        </div>
        <div>

        </div>
    </div>
</template>

<style>
</style>