<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    user: Object,
    featured_products: Array,
    categories: Array,
    stats: Object,
});

const isBuyer = computed(() => props.user?.role === 'buyer');
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Buyer Dashboard" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Welcome Section -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                    Welcome back, {{ user.name }}! 🛒
                                </h2>
                                <p class="text-gray-600 dark:text-gray-400">
                                    Discover fresh products from local farmers across Zimbabwe.
                                </p>
                            </div>
                            <div class="hidden md:block">
                                <span class="text-6xl">🛒</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="text-2xl">❤️</span>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Favorites</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.favorites_count }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="text-2xl">📦</span>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Orders</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.orders_count }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="text-2xl">💬</span>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Messages</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.messages_count }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <span class="text-2xl">👁️</span>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Recent Views</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.recent_views }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categories Section -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                Browse by Category
                            </h3>
                            <Link
                                :href="route('products.index')"
                                class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-medium"
                            >
                                View All
                            </Link>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                            <div
                                v-for="category in categories"
                                :key="category.id"
                                class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 text-center hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors cursor-pointer"
                            >
                                <div class="text-2xl mb-2">🌾</div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {{ category.name }}
                                </h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ category.products_count }} products
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Featured Products Section -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                Featured Products
                            </h3>
                            <Link
                                :href="route('products.index')"
                                class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 font-medium"
                            >
                                View All
                            </Link>
                        </div>
                        
                        <div v-if="featured_products.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div
                                v-for="product in featured_products"
                                :key="product.id"
                                class="bg-white dark:bg-gray-700 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 overflow-hidden hover:shadow-md transition-shadow"
                            >
                                <div class="aspect-w-16 aspect-h-9">
                                    <img
                                        v-if="product.photos && product.photos.length > 0"
                                        :src="product.photos[0].photo_url"
                                        :alt="product.name"
                                        class="w-full h-48 object-cover"
                                    />
                                    <div
                                        v-else
                                        class="w-full h-48 bg-gray-200 dark:bg-gray-600 flex items-center justify-center"
                                    >
                                        <span class="text-gray-400 text-4xl">📦</span>
                                    </div>
                                </div>
                                
                                <div class="p-4">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                        {{ product.name }}
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 line-clamp-2">
                                        {{ product.description }}
                                    </p>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-lg font-bold text-primary-600 dark:text-primary-400">
                                            ${{ product.price }}
                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ product.location }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            by {{ product.seller }}
                                        </span>
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
                                
                                <div class="px-4 pb-4">
                                    <Link
                                        :href="route('products.show', product.id)"
                                        class="w-full bg-primary-600 text-white text-center py-2 px-4 rounded-lg hover:bg-primary-700 transition-colors block"
                                    >
                                        View Details
                                    </Link>
                                </div>
                            </div>
                        </div>
                        
                        <div v-else class="text-center py-12">
                            <div class="text-6xl mb-4">🌾</div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                                No Featured Products
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">
                                Check back later for new products from local farmers.
                            </p>
                            <Link
                                :href="route('products.index')"
                                class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors"
                            >
                                Browse All Products
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">
                            Quick Actions
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <Link
                                :href="route('products.index')"
                                class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                            >
                                <span class="text-2xl mr-4">🔍</span>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Browse Products</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Discover fresh products</p>
                                </div>
                            </Link>
                            
                            <Link
                                :href="route('buyer.profile')"
                                class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                            >
                                <span class="text-2xl mr-4">👤</span>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">My Profile</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Manage your account</p>
                                </div>
                            </Link>
                            
                            <Link
                                :href="route('register')"
                                class="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors"
                            >
                                <span class="text-2xl mr-4">🌾</span>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">Become a Seller</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Start selling your products</p>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
