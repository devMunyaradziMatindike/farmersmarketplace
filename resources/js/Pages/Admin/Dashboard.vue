<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import CrownIcon from '@/Components/Icons/CrownIcon.vue';

const props = defineProps({
    stats: Object,
    recentUsers: Array,
    recentProducts: Array,
    categoryStats: Array,
    userGrowth: Array,
    productGrowth: Array
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};
</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Welcome Message -->
                <div class="mb-8">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                            <CrownIcon 
                                :size="28" 
                                variant="gold" 
                                class="text-white"
                            />
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                                Admin Dashboard
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">
                                Platform overview and management tools
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Statistics Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Users -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                        <span class="text-blue-600 dark:text-blue-400 text-lg">👥</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.total_users }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ stats.total_buyers }} buyers • {{ stats.total_sellers }} sellers
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Products -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                        <span class="text-green-600 dark:text-green-400 text-lg">📦</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Products</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.total_products }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ stats.available_products }} available
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Categories -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                        <span class="text-purple-600 dark:text-purple-400 text-lg">🏷️</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Categories</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.total_categories }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Active categories
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admins -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                                        <span class="text-yellow-600 dark:text-yellow-400 text-lg">👑</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Administrators</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.total_admins }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Platform admins
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Quick Actions</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <Link
                                :href="route('admin.users.index')"
                                class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors"
                            >
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                        <span class="text-white text-lg">👥</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">Manage Users</h4>
                                    <p class="text-xs text-blue-700 dark:text-blue-300">View all users</p>
                                </div>
                            </Link>

                            <Link
                                :href="route('admin.products.index')"
                                class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors"
                            >
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                                        <span class="text-white text-lg">📦</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-green-900 dark:text-green-100">Manage Products</h4>
                                    <p class="text-xs text-green-700 dark:text-green-300">Moderate listings</p>
                                </div>
                            </Link>

                            <Link
                                :href="route('admin.categories.index')"
                                class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors"
                            >
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                                        <span class="text-white text-lg">🏷️</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-purple-900 dark:text-purple-100">Manage Categories</h4>
                                    <p class="text-xs text-purple-700 dark:text-purple-300">Add categories</p>
                                </div>
                            </Link>

                            <Link
                                :href="route('admin.settings.index')"
                                class="flex items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                            >
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gray-600 rounded-lg flex items-center justify-center">
                                        <span class="text-white text-lg">⚙️</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">Settings</h4>
                                    <p class="text-xs text-gray-700 dark:text-gray-300">Platform config</p>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                    <!-- Recent Users -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Users</h3>
                                <Link
                                    :href="route('admin.users.index')"
                                    class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300"
                                >
                                    View all →
                                </Link>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div
                                    v-for="user in recentUsers"
                                    :key="user.id"
                                    class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"
                                >
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ user.name }}</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ user.email }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            :class="{
                                                'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200': user.role === 'buyer',
                                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': user.role === 'seller',
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': user.role === 'admin'
                                            }"
                                            class="inline-flex px-2 py-1 text-xs font-medium rounded-full"
                                        >
                                            {{ user.role }}
                                        </span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ formatDate(user.created_at) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Products -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Products</h3>
                                <Link
                                    :href="route('admin.products.index')"
                                    class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300"
                                >
                                    View all →
                                </Link>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div
                                    v-for="product in recentProducts"
                                    :key="product.id"
                                    class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg"
                                >
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ product.name }}</h4>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">by {{ product.seller }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span
                                            :class="{
                                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': product.status === 'available',
                                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': product.status === 'sold_out',
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': product.status === 'soon_to_be_available',
                                                'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200': product.status === 'delisted'
                                            }"
                                            class="inline-flex px-2 py-1 text-xs font-medium rounded-full"
                                        >
                                            {{ product.status.replace('_', ' ') }}
                                        </span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">${{ product.price }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category Statistics -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Category Statistics</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <div
                                v-for="category in categoryStats.slice(0, 8)"
                                :key="category.id"
                                class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg"
                            >
                                <div class="text-2xl mb-2">
                                    {{ category.name === 'Grains' ? '🌾' : 
                                       category.name === 'Vegetables' ? '🥬' : 
                                       category.name === 'Fruits' ? '🍎' : 
                                       category.name === 'Livestock' ? '🐄' : 
                                       category.name === 'Poultry Products' ? '🐔' : 
                                       category.name === 'Farm Equipment' ? '🚜' : '📦' }}
                                </div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ category.name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ category.products_count }} products</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>



