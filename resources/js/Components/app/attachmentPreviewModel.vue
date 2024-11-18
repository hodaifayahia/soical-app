<script setup>
import { ref, computed, watch } from 'vue';
import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from '@headlessui/vue';
import { XMarkIcon, ChevronLeftIcon, ChevronRightIcon ,PaperClipIcon } from '@heroicons/vue/24/solid';
import { isImage } from "@/helper.js";


const props = defineProps({
  Attachments: {
    type: Array,
    required: true,
  },
  index: {
    type: Number,
  },
  modelValue: Boolean
});
console.log(props.Attachments);


const emit = defineEmits(['update:modelValue', 'hide', 'update:index']);

const show = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
});

const CurrentIndex = computed({
  get: () => props.index,
  set: (value) => emit('update:index', value)
});



function closeModal() {
  show.value = false;
  emit('hide');
  resetModel();
}
function resetModel() {
  form.reset();

  attachementFiles.value = [];
  emit('update:modelValue', false);
  if (props.Attachments) {
    props.Attachments.forEach(file => {
      file.deleted = false;
    });
  }

}

const attachement = computed(() => {
  return props.Attachments[CurrentIndex.value];
})



function goToPreviousAttachment() {
  if (CurrentIndex.value == 0) return;
  CurrentIndex.value--
}
function goToNextAttachment() {
  if (CurrentIndex.value >= props.Attachments.length - 1) return;
  CurrentIndex.value++
}
</script>

<template>
  <teleport to="body">
    <TransitionRoot appear :show="show" as="template">
      <Dialog as="div" @close="closeModal" class="fixed inset-0 z-50 flex items-center justify-center">
        <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100"
          leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
          <div class="fixed inset-0 bg-black/50" />
        </TransitionChild>

        <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
          enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100"
          leave-to="opacity-0 scale-95">
          <DialogPanel
            class="relative w-full max-w-6xl h-full max-h-[90vh] mx-auto transform overflow-hidden rounded bg-white text-left align-middle shadow-xl transition-all">
            
            <!-- Close Button -->
            <button @click="closeModal"
              class="absolute top-2 right-2 z-10 flex items-center justify-center bg-white/50 hover:bg-white/70 p-1 rounded-full text-black duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-opacity-50 text-xs sm:text-sm">
              <XMarkIcon class="h-8 w-8" />
            </button>
            <!-- Modal Content -->
            <div class="relative flex items-center justify-center h-full bg-slate-800">
              <!-- Left Navigation Arrow -->
              <div
                    class="absolute text-white opacity-100 group-hover:opacity-100 transition-opacity duration-300 w-12 left-0 top-1/2 transform -translate-y-1/2 flex items-center"
                    @click="goToPreviousAttachment">
                    <ChevronLeftIcon class="w-16 cursor-pointer" />
                  </div>

              <!-- Attachment Display -->
              <div class="flex items-center justify-center w-full h-full cursor-pointer">
                <img v-if="isImage(attachement)" class="object-contain max-w-full max-h-full" :src="attachement.url">
                <div v-else class="flex justify-center  items-center flex-col text-white cursor-pointer ">
                  <PaperClipIcon class="w-16 h-16 text-white" />
                  <span class="mt-2 text-white">{{ attachement.name }}</span>
                </div>
              </div>

              <!-- Right Navigation Arrow -->
              <div @click="goToNextAttachment"
                class="absolute right-0 text-white opacity-100 group-hover:opacity-100 transition-opacity duration-300 w-12 top-1/2 transform -translate-y-1/2 flex items-center cursor-pointer">
                <ChevronRightIcon class="w-12 h-12" />
              </div>
            </div>

          </DialogPanel>
        </TransitionChild>
      </Dialog>
    </TransitionRoot>
  </teleport>
</template>

<style scoped>
/* Additional Styles */
</style>
