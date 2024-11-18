<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PostItem from '@/Components/app/PostItem.vue';
import { ref } from 'vue'
import PostModel from "@/Components/app/PostModel.vue"; // Corrected import (without {})
import attachmentPreviewModel from "@/Components/app/attachmentPreviewModel.vue"; // Corrected import (without {})
import {  usePage } from '@inertiajs/vue3';
defineProps({
    post: Object
});
const AuthUser = usePage().props.auth.user;


const showEditModel = ref(false); // Changed variable name to camelCase
const editPost = ref({});

const showPreviewModel = ref(false); // Changed variable name to camelCase
const AttachmentModel = ref({});


function openEditModel(post) { // Changed parameter name to singular form
    editPost.value = post;
    showEditModel.value = true;
}
function openAttachmentPreviewModel(post, index) {
    AttachmentModel.value = {
        post, // should contain the post data with the attachments
        index // should be a valid index within the attachments array
    };
    showPreviewModel.value = true;
}

function onModelHiden() {
    editPost.body = {
        id: null,
        body: "",
        user: AuthUser,
    };
}
</script>

<template>

    <AuthenticatedLayout>
        <div class="p-8 max-w-[700px] mx-auto overflow-auto h-full">
            <PostItem :post="post" @attachmentClick='openAttachmentPreviewModel' @editClick="openEditModel" />
        <PostModel :post="editPost" v-model="showEditModel" @hide="onModelHiden" />
        <div ref="loadMoreIntersect"></div>

        <attachmentPreviewModel :Attachments="AttachmentModel.post?.attachments || []"
            v-model:index="AttachmentModel.index" v-model="showPreviewModel" />
        </div>
    </AuthenticatedLayout>

</template>

<style></style>