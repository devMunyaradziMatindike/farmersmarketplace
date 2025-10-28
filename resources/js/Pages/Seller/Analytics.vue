<script setup>
import SellerLayout from '@/Layouts/SellerLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    user: Object,
    stats: {
        type: Object,
        default: () => ({
            total_products: 0,
            available_products: 0,
            sold_products: 0,
            total_views: 0,
            monthly_views: 0,
            weekly_views: 0,
            top_categories: [],
            recent_activity: []
        })
    },
    products: {
        type: Array,
        default: () => []
    }
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-ZW', {
        style: 'currency',
        currency: 'USD'
    }).format(price);
};

const getStatusColor = (status) => {
    const colors = {
        available: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        sold_out: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        soon_to_be_available: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        delisted: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
    };
    return colors[status] || colors.available;
};
</script>

<template>
    <Head title="Analytics" />

    <SellerLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Overview Stats -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
                                        <span class="text-primary-600 dark:text-primary-400 text-lg">📦</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Products</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.total_products }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                        <span class="text-green-600 dark:text-green-400 text-lg">✅</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Available</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.available_products }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                                        <span class="text-yellow-600 dark:text-yellow-400 text-lg">💰</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sold</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.sold_products }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                        <span class="text-blue-600 dark:text-blue-400 text-lg">👁️</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Views</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.total_views }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Views Over Time -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Views Over Time</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">This Week</span>
                                    <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ stats.weekly_views }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">This Month</span>
                                    <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ stats.monthly_views }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">All Time</span>
                                    <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ stats.total_views }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Categories -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Top Categories</h3>
                        </div>
                        <div class="p-6">
                            <div v-if="stats.top_categories && stats.top_categories.length > 0" class="space-y-3">
                                <div
                                    v-for="category in stats.top_categories"
                                    :key="category.id"
                                    class="flex items-center justify-between"
                                >
                                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ category.name }}</span>
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ category.count }} products</span>
                                </div>
                            </div>
                            <div v-else class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">No category data available</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Performance -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Product Performance</h3>
                    </div>
                    <div class="p-6">
                        <div v-if="products.length > 0" class="space-y-4">
                            <div
                                v-for="product in products.slice(0, 10)"
                                :key="product.id"
                                class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg"
                            >
                                <div class="flex items-center space-x-4">
                                    <img
                                        v-if="product.photos && product.photos.length > 0"
                                        :src="product.photos[0].photo_url"
                                        :alt="product.name"
                                        class="w-12 h-12 rounded-lg object-cover"
                                    />
                                    <div
                                        v-else
                                        class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center"
                                    >
                                        <span class="text-gray-400 text-xl">📦</span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ product.name }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ product.category.name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ formatPrice(product.price) }}</span>
                                    <span
                                        :class="getStatusColor(product.status)"
                                        class="inline-flex px-2 py-1 text-xs font-medium rounded-full"
                                    >
                                        {{ product.status.replace('_', ' ') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-8">
                            <p class="text-gray-500 dark:text-gray-400">No products found</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div v-if="stats.recent_activity && stats.recent_activity.length > 0" class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Activity</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div
                                v-for="activity in stats.recent_activity"
                                :key="activity.id"
                                class="flex items-start space-x-3"
                            >
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                                        <span class="text-primary-600 dark:text-primary-400 text-sm">📝</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900 dark:text-white">{{ activity.description }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ activity.created_at }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <Link
                        :href="route('seller.products.create')"
                        class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors"
                    >
                        <span class="mr-2">➕</span>
                        Add New Product
                    </Link>
                    <Link
                        :href="route('seller.products.index')"
                        class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors"
                    >
                        <span class="mr-2">📋</span>
                        Manage Products
                    </Link>
                </div>
            </div>
        </div>
    </SellerLayout>
</template>
