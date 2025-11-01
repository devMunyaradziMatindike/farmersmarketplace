<template>
    <div>
        <Head title="Saved Searches" />
        <AppHeader />
        
        <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                        Saved Searches
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Manage your saved searches and get notified when new products match your criteria.
                    </p>
                </div>

                <!-- Save Current Search Button -->
                <div v-if="$page.props.auth?.user" class="mb-6">
                    <Link
                        :href="route('products.index')"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Save Current Search
                    </Link>
                </div>

                <!-- Saved Searches List -->
                <div v-if="savedSearches.length > 0" class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="search in savedSearches"
                        :key="search.id"
                        class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 hover:shadow-lg transition"
                    >
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                    {{ search.name }}
                                </h3>
                                <p v-if="search.has_price_alert" class="text-xs text-primary-600 dark:text-primary-400">
                                    🔔 Price Alert Active
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <button
                                    @click="executeSearch(search)"
                                    class="p-2 text-primary-600 dark:text-primary-400 hover:bg-primary-50 dark:hover:bg-gray-700 rounded"
                                    title="Run Search"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </button>
                                <button
                                    @click="deleteSearch(search)"
                                    class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded"
                                    title="Delete"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Search Criteria Summary -->
                        <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1 mb-4">
                            <div v-if="search.search_criteria.q || search.search_criteria.search">
                                <span class="font-medium">Search:</span> {{ search.search_criteria.q || search.search_criteria.search }}
                            </div>
                            <div v-if="search.search_criteria.category_id">
                                <span class="font-medium">Category:</span> {{ getCategoryName(search.search_criteria.category_id) }}
                            </div>
                            <div v-if="search.search_criteria.min_price || search.search_criteria.max_price">
                                <span class="font-medium">Price:</span>
                                <span v-if="search.search_criteria.min_price">${{ search.search_criteria.min_price }}</span>
                                <span v-if="search.search_criteria.min_price && search.search_criteria.max_price"> - </span>
                                <span v-if="search.search_criteria.max_price">${{ search.search_criteria.max_price }}</span>
                            </div>
                            <div v-if="search.search_criteria.location">
                                <span class="font-medium">Location:</span> {{ search.search_criteria.location }}
                            </div>
                        </div>

                        <!-- Price Alert Info -->
                        <div v-if="search.has_price_alert" class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg mb-4">
                            <div class="text-xs text-blue-800 dark:text-blue-200">
                                Alert when price is
                                <span class="font-semibold">{{ search.alert_condition }}</span>
                                <span class="font-semibold">${{ search.alert_price_threshold }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                            <Link
                                :href="route('saved-searches.execute', search.id)"
                                class="text-sm text-primary-600 dark:text-primary-400 hover:underline font-medium"
                            >
                                View Results
                            </Link>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ formatDate(search.created_at) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No saved searches</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Save searches to quickly find products that match your criteria.
                    </p>
                    <div class="mt-6">
                        <Link
                            :href="route('products.index')"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            Browse Products
                        </Link>
                    </div>
                </div>

                <!-- Flash Messages -->
                <div v-if="$page.props.flash?.success" class="mt-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <p class="text-sm text-green-800 dark:text-green-200">{{ $page.props.flash.success }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AppHeader from '@/Components/AppHeader.vue';

const props = defineProps({
    savedSearches: {
        type: Array,
        default: () => []
    }
});

const page = usePage();

const executeSearch = (search) => {
    router.visit(route('saved-searches.execute', search.id));
};

const deleteSearch = (search) => {
    if (confirm('Are you sure you want to delete this saved search?')) {
        router.delete(route('saved-searches.destroy', search.id), {
            preserveScroll: true,
        });
    }
};

const getCategoryName = (categoryId) => {
    const categories = page.props.categories || [];
    const category = categories.find(c => c.id === categoryId);
    return category ? category.name : 'Unknown';
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
};
</script>

