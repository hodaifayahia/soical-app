<script setup>
import indigoButton from '@/Components/app/indigoButton.vue';
import TextArea from '@/Components/TextArea.vue';
import { HandThumbUpIcon, ArrowPathIcon } from '@heroicons/vue/20/solid';
import axiosClient from '@/axiosClient.js';
import { DisclosureButton, Disclosure, DisclosurePanel } from '@headlessui/vue';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import ReadlessReadMore from '@/Components/app/ReadlessReadMore.vue';
import EditDeleteDropDown from '@/Components/app/EditDeleteDropDown.vue';
import { Link } from '@inertiajs/vue3';



const newCommentText = ref("");
const authUser = usePage().props.auth.user;
const edittingComment = ref(null);
 const props =  defineProps({
    post: Object,
    data: Object,
    ParentComment:{
        type: [Object],
        default: null

    }
})
const emit = defineEmits(['DeleteComment','CreateComment']);

function createComment() {
  axiosClient.post(route('post.comment.create', props.post), {
    comment: newCommentText.value,
    parent_id: props.ParentComment ? props.ParentComment.id : null
  })
  .then(({ data }) => {
    newCommentText.value = '';
    props.data.comments.unshift(data);
    if (props.ParentComment) {
      props.ParentComment.num_of_comment++;
    } 
      props.post.num_of_comment++;
      emit('CreateComment',data)
    
  })
  .catch((error) => {
    console.error('Error creating comment:', error);
    // Handle the error appropriately
  });
}



function StartEditComment(comment) {

    edittingComment.value = {
        id: comment.id,
        comment: comment.comment.replace('<br\s*\/?>/gi', '\n')
    };
}
function deleteComment(comment) {
    if (!window.confirm('are sure u want to delete this comment ')) {
        return false;
    }
    axiosClient.delete(route('comment.delate', comment.id))
        .then(({ data }) => {
            const index = props.data.comments.findIndex(c => c.id == comment.id)
           props.data.comments.splice(index , 1);
           if (props.ParentComment) {
            props.ParentComment.num_of_comment--;
           }
           props.post.num_of_comment--;

           
           
           emit('DeleteComment',comment)
        });
}
function updateComment() {
    axiosClient.put(route('comment.update', edittingComment.value.id), edittingComment.value)
        .then(({ data }) => {
            edittingComment.value = null;
            props.data.comments = props.data.comments.map((c) => {
                if (c.id === data.id) { // This line assigns data.id to c.id
                    return data;
                } 
                return c;
            });


        });
}

function sendcommentReaction(comment) {
    axiosClient.post(route('comment.CommentReactions', comment.id), {
        reaction: 'Like'
    })
        .then(({ data }) => {
            comment.num_of_reaction = data.num_of_reaction;
            comment.current_user_has_reaction = data.current_user_has_reaction;
        });
}


function oncreateComment(comment) {
    if (props.ParentComment) {
        props.ParentComment.num_of_comment++;
    }
    emit('CreateComment',comment)


}
function ondeleteComment(comment) {
    if (props.ParentComment) {
        props.ParentComment.num_of_comment--;
    }
    emit('DeleteComment',comment)


}
</script>

<template>
    <div>
        <div>
            <div class="flex mt-4">
                <Link :href="route('profile', authUser.username)">
                            <img :src="authUser.avatar_url" alt="User image"
                                class="w-10 h-10 rounded-full mr-4 border-2 transition-all hover:border-cyan-400">
                </Link>
                <div class="flex flex-1">
                    <TextArea v-model="newCommentText" name="" rows="1"
                        class="w-full  resize-none max-h-[160px] rounded-r-none " placeholder="Enter your COmment Here"
                        d="" />
                    <indigoButton @click="createComment" class="w-[100px]  rounded-l-none"> submit
                    </indigoButton>
                </div>


                <div>

                </div>
            </div>
        </div>
        <div class="mt-4">
                <div v-for="comment in props.data.comments" :key="comment.id" class="bg-white rounded-lg p-4 shadow-md mb-4">

                    <div class="flex items-start">
                        <!-- User Avatar -->
                        <Link :href="route('profile', authUser.username)">
                            <img :src="authUser.avatar_url" alt="User image"
                                class="w-10 h-10 rounded-full mr-4 border-2 transition-all hover:border-cyan-400">
                        </Link>
                        <!-- Comment Content -->
                        <div class="flex-1 ">
                            <div class="flex items-center justify-between">
                                <!-- User Name and Time -->
                                <div>
                                    <h4 class="font-semibold text-gray-800">
                                        <pre>{{ comment.user.name }}</pre>
                                    </h4>
                                    <span class="text-sm text-gray-500">{{ comment.created_at }}</span>
                                </div>

                            </div>
                            <EditDeleteDropDown class="flex  justify-end bottom-8 " :posts="post" :comment="comment" :user="comment.user"
                                @edit="StartEditComment(comment)" @delete="deleteComment(comment)" />


                            <div class="-mt-4 ">
                            </div>
                            <!-- Edit Comment Section -->
                            <div v-if="edittingComment && edittingComment.id == comment.id">
                                <TextArea v-model="edittingComment.comment" rows="1"
                                    class="w-full resize-none max-h-[160px] rounded"
                                    placeholder="Enter your Comment Here" />
                                <div class="flex gap-2 justify-end mt-2">
                                    <button class="text-indigo-500 hover:text-indigo-700 font-semibold"
                                        @click="edittingComment = null">
                                        Cancel
                                    </button>
                                    <indigoButton @click="updateComment(comment)" class="w-[100px]">
                                        Update
                                    </indigoButton>
                                </div>
                            </div>

                            <!-- Read More/Read Less Content -->
                            <div v-else>
                                <ReadlessReadMore :Content="comment.comment" ContentClass="text-gray-700" />
                            </div>

                            <!-- Like and Reply Section -->
                            <Disclosure>
                                <div class="flex items-center gap-4 mt-2">
                                    <!-- Like Button -->
                                    <button @click="sendcommentReaction(comment)"
                                        class="flex items-center text-gray-500 hover:text-indigo-500">
                                        <HandThumbUpIcon class="w-5 h-5"
                                            :class="{ 'text-blue-600': comment.current_user_has_reaction }" />
                                        <span class="pl-1">{{ comment.num_of_reaction }}</span>
                                    </button>

                                    <!-- Reply Button -->
                                    <DisclosureButton class="flex items-center text-gray-500 hover:text-indigo-500">
                                        <ArrowPathIcon class="w-4 h-4 mr-1"></ArrowPathIcon>
                                        <span class="pl-1">{{ comment.num_of_comment }}</span>
                                        Reply
                                    </DisclosureButton>
                                </div>

                                <!-- Subcomments Section -->
                                <DisclosurePanel>
                                    <div class="mt-2 ml-6">
                                        <CommentList :post="post"
                                         :data="comment"
                                          :ParentComment="comment"
                                          @DeleteComment="ondeleteComment"
                                          @CreateComment="oncreateComment"
                                          />

                                    </div>
                                </DisclosurePanel>
                            </Disclosure>
                        </div>


                    </div>


                </div>

        </div>

        <div>

        </div>
    </div>


</template>

<style></style>