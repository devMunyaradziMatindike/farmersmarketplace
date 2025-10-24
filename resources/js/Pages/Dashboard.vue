<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
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
            total_views: 0
        })
    },
    recent_products: {
        type: Array,
        default: () => []
    }
});

const isSeller = computed(() => props.user?.role === 'seller');
const isAdmin = computed(() => props.user?.role === 'admin');
const isBuyer = computed(() => props.user?.role === 'buyer');

const showSellerFeatures = computed(() => isSeller.value || isAdmin.value);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Welcome Message -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Welcome back, {{ user.name }}! 👋
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">
                        {{ isSeller ? 'Manage your products and grow your business' : 
                           isBuyer ? 'Discover fresh products from local farmers' : 
                           'Admin dashboard for platform management' }}
                    </p>
                </div>

                <!-- Seller Dashboard -->
                <div v-if="showSellerFeatures" class="space-y-8">
                    <!-- Quick Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
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

                    <!-- Quick Actions -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Quick Actions</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <Link
                                    :href="route('seller.products.create')"
                                    class="flex items-center p-4 bg-primary-50 dark:bg-primary-900/20 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors"
                                >
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center">
                                            <span class="text-white text-lg">➕</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-primary-900 dark:text-primary-100">Add New Product</h4>
                                        <p class="text-xs text-primary-700 dark:text-primary-300">List a new product for sale</p>
                                    </div>
                                </Link>

                                <Link
                                    :href="route('seller.products.index')"
                                    class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors"
                                >
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                                            <span class="text-white text-lg">📋</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-green-900 dark:text-green-100">Manage Products</h4>
                                        <p class="text-xs text-green-700 dark:text-green-300">View and edit your listings</p>
                                    </div>
                                </Link>

                                <Link
                                    :href="route('products.index')"
                                    class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors"
                                >
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                                            <span class="text-white text-lg">🔍</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">Browse Marketplace</h4>
                                        <p class="text-xs text-blue-700 dark:text-blue-300">See what others are selling</p>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Products -->
                    <div v-if="recent_products.length > 0" class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Products</h3>
                                <Link
                                    :href="route('seller.products.index')"
                                    class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300"
                                >
                                    View all →
                                </Link>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div
                                    v-for="product in recent_products"
                                    :key="product.id"
                                    class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow"
                                >
                                    <div class="flex items-start space-x-4">
                                        <div class="flex-shrink-0">
                                            <img
                                                v-if="product.photos && product.photos.length > 0"
                                                :src="product.photos[0].photo_url"
                                                :alt="product.name"
                                                class="w-16 h-16 rounded-lg object-cover"
                                            />
                                            <div
                                                v-else
                                                class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center"
                                            >
                                                <span class="text-gray-400 text-2xl">📦</span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ product.name }}
                                            </h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                ${{ product.price }}
                                            </p>
                                            <div class="mt-2">
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State for New Sellers -->
                    <div v-else class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                        <div class="p-12 text-center">
                            <div class="mx-auto w-24 h-24 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mb-4">
                                <span class="text-primary-600 dark:text-primary-400 text-4xl">🌾</span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                                Start Your Journey
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">
                                You haven't listed any products yet. Create your first listing to start selling!
                            </p>
                            <Link
                                :href="route('seller.products.create')"
                                class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors"
                            >
                                <span class="mr-2">➕</span>
                                Add Your First Product
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Buyer Dashboard -->
                <div v-else-if="isBuyer" class="space-y-8">
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                Welcome to Musika Wedu! 🛒
                            </h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                Discover fresh products from local farmers across Zimbabwe. Browse by category, search by location, and connect directly with sellers.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <Link
                                    :href="route('products.index')"
                                    class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors"
                                >
                                    <span class="mr-2">🔍</span>
                                    Browse Products
                                </Link>
                                <Link
                                    :href="route('register')"
                                    class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors"
                                >
                                    <span class="mr-2">🌾</span>
                                    Become a Seller
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                        <!-- Admin Dashboard - Redirect to Admin Portal -->
                        <div v-else-if="isAdmin" class="space-y-8">
                            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                                <div class="p-6">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                        Admin Dashboard 👑
                                    </h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                                        Access the full admin portal to manage the platform, users, categories, and moderate content.
                                    </p>
                                    <Link
                                        :href="route('admin.dashboard')"
                                        class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-bold rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl hover:scale-105"
                                    >
                                        <span class="mr-2">👑</span>
                                        Go to Admin Portal
                                        <span class="ml-2">→</span>
                                    </Link>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
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
                                                <p class="text-xs text-blue-700 dark:text-blue-300">View and manage user accounts</p>
                                            </div>
                                        </Link>

                                        <Link
                                            :href="route('admin.categories.index')"
                                            class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors"
                                        >
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                                                    <span class="text-white text-lg">🏷️</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <h4 class="text-sm font-medium text-green-900 dark:text-green-100">Manage Categories</h4>
                                                <p class="text-xs text-green-700 dark:text-green-300">Add and edit product categories</p>
                                            </div>
                                        </Link>

                                        <Link
                                            :href="route('admin.products.index')"
                                            class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors"
                                        >
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                                                    <span class="text-white text-lg">📋</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <h4 class="text-sm font-medium text-purple-900 dark:text-purple-100">Moderate Products</h4>
                                                <p class="text-xs text-purple-700 dark:text-purple-300">Review and manage listings</p>
                                            </div>
                                        </Link>
                                    </div>
                                </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
