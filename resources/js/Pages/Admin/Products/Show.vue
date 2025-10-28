<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

const props = defineProps({
    product: Object
});

const deleteProduct = () => {
    if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        router.delete(route('admin.products.destroy', props.product.id), {
            onSuccess: () => router.visit(route('admin.products.index'))
        });
    }
};

const approveProduct = () => {
    if (confirm('Approve this product listing?')) {
        router.patch(route('admin.products.approve', props.product.id));
    }
};

const rejectProduct = () => {
    if (confirm('Reject/delist this product?')) {
        router.patch(route('admin.products.reject', props.product.id));
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
    <Head :title="`Product: ${product.name}`" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8 flex items-center justify-between">
                    <div>
                        <Link
                            :href="route('admin.products.index')"
                            class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 mb-2 inline-block"
                        >
                            ← Back to Products
                        </Link>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ product.name }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">
                            Product ID: #{{ product.id }}
                        </p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button
                            v-if="product.status !== 'available'"
                            @click="approveProduct"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                        >
                            Approve Product
                        </button>
                        <button
                            v-if="product.status === 'available'"
                            @click="rejectProduct"
                            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors"
                        >
                            Delist Product
                        </button>
                        <button
                            @click="deleteProduct"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                        >
                            Delete Product
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Product Images -->
                    <div>
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Product Images</h3>
                                <div class="space-y-4">
                                    <div
                                        v-for="photo in product.photos"
                                        :key="photo.id"
                                        class="relative"
                                    >
                                        <img
                                            :src="photo.photo_url"
                                            :alt="product.name"
                                            class="w-full h-64 object-cover rounded-lg"
                                        />
                                        <div
                                            v-if="photo.is_primary"
                                            class="absolute top-2 right-2 bg-primary-600 text-white px-3 py-1 rounded-full text-sm"
                                        >
                                            Primary
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="space-y-6">
                        <!-- Basic Information -->
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Product Information</h3>
                            </div>
                            <div class="p-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        Name
                                    </label>
                                    <p class="text-gray-900 dark:text-white">{{ product.name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        Description
                                    </label>
                                    <p class="text-gray-900 dark:text-white">{{ product.description }}</p>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                            Price
                                        </label>
                                        <p class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                                            ${{ product.price }}
                                        </p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                            Status
                                        </label>
                                        <span
                                            :class="{
                                                'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': product.status === 'available',
                                                'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': product.status === 'sold_out',
                                                'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': product.status === 'soon_to_be_available',
                                                'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200': product.status === 'delisted'
                                            }"
                                            class="inline-flex px-3 py-1 text-sm font-medium rounded-full"
                                        >
                                            {{ product.status.replace('_', ' ') }}
                                        </span>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        Category
                                    </label>
                                    <p class="text-gray-900 dark:text-white">{{ product.category.name }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        Location
                                    </label>
                                    <p class="text-gray-900 dark:text-white">{{ product.location }}</p>
                                    <p v-if="product.latitude && product.longitude" class="text-sm text-gray-500 dark:text-gray-400">
                                        GPS: {{ product.latitude }}, {{ product.longitude }}
                                    </p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        Contact Details
                                    </label>
                                    <p class="text-gray-900 dark:text-white">{{ product.contact_details }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Seller Information -->
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Seller Information</h3>
                            </div>
                            <div class="p-6 space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        Name
                                    </label>
                                    <Link
                                        :href="route('admin.users.show', product.seller.id)"
                                        class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300"
                                    >
                                        {{ product.seller.name }}
                                    </Link>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        Email
                                    </label>
                                    <p class="text-gray-900 dark:text-white">{{ product.seller.email }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        Phone
                                    </label>
                                    <p class="text-gray-900 dark:text-white">{{ product.seller.phone_number }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Timestamps -->
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Timestamps</h3>
                            </div>
                            <div class="p-6 space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        Listed On
                                    </label>
                                    <p class="text-gray-900 dark:text-white">{{ formatDate(product.created_at) }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        Last Updated
                                    </label>
                                    <p class="text-gray-900 dark:text-white">{{ formatDate(product.updated_at) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>





