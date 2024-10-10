<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'; 

defineProps({
    title: String,
    body: String
})
</script>
    <template>
<AuthenticatedLayout>
        <div class="flex items-center justify-center min-h-screen bg-red-400 p-6">
            <div class="bg-white border border-red-200 rounded-lg p-8 max-w-lg text-center shadow-md">
            <h1 class="text-red-700 text-3xl font-semibold mb-4">{{ title }}</h1>
            <p class="text-gray-700 text-lg">{{ body }}</p>
            </div>
        </div>
    </AuthenticatedLayout>
    </template>

<style scoped>
</style>
