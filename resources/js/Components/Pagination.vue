<template>
    <div v-if="links.length > 3" class="flex flex-wrap gap-1">
        <template v-for="(link, key) in links" :key="key">
            <div
                v-if="link.url === null"
                class="px-4 py-2 text-sm leading-5 text-gray-400 dark:text-gray-600 border border-gray-300 dark:border-gray-700 cursor-not-allowed rounded"
                v-html="link.label"
            />
            <Link
                v-else
                class="px-4 py-2 text-sm leading-5 border rounded transition hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500"
                :class="{
                    'bg-primary-600 text-white border-primary-600 dark:bg-primary-700 dark:border-primary-700': link.active,
                    'text-gray-700 bg-white border-gray-300 dark:text-gray-300 dark:bg-gray-800 dark:border-gray-700': !link.active,
                }"
                :href="link.url"
                v-html="link.label"
            />
        </template>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script>


