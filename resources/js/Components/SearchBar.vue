<template>
    <div class="relative flex-1 max-w-2xl mx-8">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input
                v-model="searchQuery"
                @input="handleSearch"
                type="text"
                placeholder="Search products, categories, or sellers..."
                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
            />
            <button
                v-if="searchQuery"
                @click="clearSearch"
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
            >
                <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';

const props = defineProps({
    initialQuery: {
        type: String,
        default: ''
    }
});

const searchQuery = ref(props.initialQuery);

const performSearch = () => {
    router.get(route('products.index'), 
        { search: searchQuery.value },
        { 
            preserveState: true,
            preserveScroll: false,
            replace: true
        }
    );
};

const handleSearch = debounce(() => {
    performSearch();
}, 500);

const clearSearch = () => {
    searchQuery.value = '';
    performSearch();
};
</script>



