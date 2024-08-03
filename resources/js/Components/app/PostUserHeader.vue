<script setup>

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
        <a href="javascript:void(0)">
            <img :src="post.user.avatar_url" alt="User image"
                class="w-10 h-10 rounded-full mr-3 border-2 transition-all hover:border-cyan-400">
        </a>
        <div>
            <a href="javascript:void(0)">
                <h4 class="font-semibold hover:underline">{{ post.user.name }}
                    <template v-if="post.Group">
                        >
                        <a href="javascript:void(0)" class="hover:underline">
                            {{ post.Group.name }}
                        </a>
                    </template>
                </h4>
            </a>
            <small v-if="showTime" class="text-gray-400">{{ post.updated_at }}</small>
        </div>
        <div>

        </div>
    </div>
</template>

<style>
</style>