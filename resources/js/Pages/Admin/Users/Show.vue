<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
    products: Array,
    stats: Object
});

const deleteUser = () => {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        router.delete(route('admin.users.destroy', props.user.id), {
            onSuccess: () => router.visit(route('admin.users.index'))
        });
    }
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <Head :title="`User: ${user.name}`" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8 flex items-center justify-between">
                    <div>
                        <Link
                            :href="route('admin.users.index')"
                            class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-2 inline-block"
                        >
                            ← Back to Users
                        </Link>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ user.name }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">
                            User ID: #{{ user.id }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <Link
                            :href="route('admin.users.edit', user.id)"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            Edit User
                        </Link>
                        <button
                            @click="deleteUser"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                        >
                            Delete User
                        </button>
                    </div>
                </div>

                <!-- User Information Card -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">User Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Full Name
                                </label>
                                <p class="text-gray-900 dark:text-white">{{ user.name }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Email Address
                                </label>
                                <p class="text-gray-900 dark:text-white">{{ user.email || 'Not provided' }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Phone Number
                                </label>
                                <p class="text-gray-900 dark:text-white">{{ user.phone_number }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Role
                                </label>
                                <span
                                    :class="{
                                        'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200': user.role === 'buyer',
                                        'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': user.role === 'seller',
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': user.role === 'admin'
                                    }"
                                    class="inline-flex px-3 py-1 text-sm font-medium rounded-full"
                                >
                                    {{ user.role.charAt(0).toUpperCase() + user.role.slice(1) }}
                                </span>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Authentication Method
                                </label>
                                <p class="text-gray-900 dark:text-white">{{ user.auth_method }}</p>
                            </div>

                            <div v-if="user.google_id">
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Google ID
                                </label>
                                <p class="text-gray-900 dark:text-white">{{ user.google_id }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Joined
                                </label>
                                <p class="text-gray-900 dark:text-white">{{ formatDate(user.created_at) }}</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                    Last Updated
                                </label>
                                <p class="text-gray-900 dark:text-white">{{ formatDate(user.updated_at) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics -->
                <div v-if="user.role === 'seller'" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Products</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.total_products }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Available Products</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.available_products }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Sold Products</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.sold_products }}</p>
                    </div>
                </div>

                <!-- Products List (for sellers) -->
                <div v-if="user.role === 'seller' && products.length > 0" class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Products ({{ products.length }})
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div
                                v-for="product in products"
                                :key="product.id"
                                class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden hover:shadow-md transition-shadow"
                            >
                                <img
                                    v-if="product.photos && product.photos.length > 0"
                                    :src="product.photos[0].photo_url"
                                    :alt="product.name"
                                    class="w-full h-48 object-cover"
                                />
                                <div
                                    v-else
                                    class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center"
                                >
                                    <span class="text-gray-400 text-4xl">📦</span>
                                </div>
                                <div class="p-4">
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                                        {{ product.name }}
                                    </h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                                        {{ product.category }}
                                    </p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-lg font-semibold text-primary-600 dark:text-primary-400">
                                            ${{ product.price }}
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
                                    <div class="mt-4">
                                        <Link
                                            :href="route('admin.products.show', product.id)"
                                            class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300"
                                        >
                                            View Product →
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else-if="user.role === 'seller'" class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                    <div class="p-12 text-center">
                        <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                            <span class="text-gray-400 text-4xl">📦</span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                            No Products Yet
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400">
                            This seller hasn't listed any products.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>





