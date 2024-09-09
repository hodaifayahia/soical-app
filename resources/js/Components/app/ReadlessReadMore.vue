<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue';

defineProps({
    Content:String,
    ContentClass:String
})
</script>
<template>
    <Disclosure>
        <template v-slot="{ open }">
            <div>
                <div :class="ContentClass" class="ck-content-output prose break-words" v-if="!open"v-html="Content.substring(0, 200)"></div>
                <template v-if="Content && Content.length > 200">
                    <DisclosurePanel :class="ContentClass">
                        <div class="ck-content-output prose break-words" v-html="Content"></div>
                    </DisclosurePanel>
                    <DisclosureButton class="mt-2 text-blue-500 hover:text-blue-600"
                        style="position: relative; left: 88%;">
                        {{ open ? 'Read less' : 'Read more' }}
                    </DisclosureButton>
                </template>
            </div>
        </template>
    </Disclosure>
</template>
<style scoped>
</style>