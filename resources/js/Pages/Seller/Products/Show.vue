<script setup>
import SellerLayout from '@/Layouts/SellerLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
});

const deleteProduct = () => {
    if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        router.delete(route('seller.products.destroy', props.product.id));
    }
};

const updateProductStatus = (newStatus) => {
    router.patch(route('seller.products.status', props.product.id), {
        status: newStatus
    });
};

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

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};
</script>

<template>
    <Head :title="product.name" />

    <SellerLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {{ product.name }}
                </h2>
                <div class="flex items-center space-x-4">
                    <Link
                        :href="route('seller.products.edit', product.id)"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors"
                    >
                        Edit Product
                    </Link>
                    <Link
                        :href="route('seller.products.index')"
                        class="inline-flex items-center px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                    >
                        ← Back to Products
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Product Images -->
                    <div class="lg:col-span-2">
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                    Product Images
                                </h3>
                                
                                <!-- Primary Image -->
                                <div v-if="product.photos && product.photos.length > 0" class="mb-6">
                                    <img
                                        :src="product.photos[0].photo_url"
                                        :alt="product.name"
                                        class="w-full h-96 object-cover rounded-lg"
                                    />
                                </div>
                                
                                <!-- No Images -->
                                <div v-else class="w-full h-96 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                    <div class="text-center">
                                        <span class="text-gray-400 text-6xl">📦</span>
                                        <p class="text-gray-500 dark:text-gray-400 mt-2">No images uploaded</p>
                                    </div>
                                </div>

                                <!-- Thumbnail Gallery -->
                                <div v-if="product.photos && product.photos.length > 1" class="grid grid-cols-4 gap-4">
                                    <div
                                        v-for="(photo, index) in product.photos.slice(1)"
                                        :key="photo.id"
                                        class="aspect-w-1 aspect-h-1"
                                    >
                                        <img
                                            :src="photo.photo_url"
                                            :alt="`${product.name} - Image ${index + 2}`"
                                            class="w-full h-24 object-cover rounded-lg cursor-pointer hover:opacity-75 transition-opacity"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <div class="space-y-6">
                        <!-- Basic Info -->
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                        Product Details
                                    </h3>
                                    <span
                                        :class="getStatusColor(product.status)"
                                        class="inline-flex px-3 py-1 text-sm font-medium rounded-full"
                                    >
                                        {{ product.status.replace('_', ' ') }}
                                    </span>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Price
                                        </label>
                                        <p class="text-3xl font-bold text-primary-600 dark:text-primary-400">
                                            {{ formatPrice(product.price) }}
                                        </p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Category
                                        </label>
                                        <p class="text-gray-900 dark:text-white">
                                            {{ product.category.name }}
                                        </p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Description
                                        </label>
                                        <p class="text-gray-900 dark:text-white whitespace-pre-wrap">
                                            {{ product.description }}
                                        </p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Location
                                        </label>
                                        <p class="text-gray-900 dark:text-white">
                                            📍 {{ product.location }}
                                        </p>
                                        <p v-if="product.latitude && product.longitude" class="text-sm text-gray-500 dark:text-gray-400">
                                            GPS: {{ product.latitude }}, {{ product.longitude }}
                                        </p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Contact Details
                                        </label>
                                        <p class="text-gray-900 dark:text-white">
                                            {{ product.contact_details }}
                                        </p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Date Listed
                                        </label>
                                        <p class="text-gray-900 dark:text-white">
                                            {{ formatDate(product.date_listed) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Management -->
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                    Status Management
                                </h3>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Update Status
                                        </label>
                                        <select
                                            :value="product.status"
                                            @change="updateProductStatus($event.target.value)"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                        >
                                            <option value="available">Available</option>
                                            <option value="sold_out">Sold Out</option>
                                            <option value="soon_to_be_available">Soon to be Available</option>
                                            <option value="delisted">Delisted</option>
                                        </select>
                                    </div>

                                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                                            Status changes are reflected immediately on the marketplace.
                                        </p>
                                        
                                        <div class="flex space-x-3">
                                            <Link
                                                :href="route('seller.products.edit', product.id)"
                                                class="flex-1 text-center px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors"
                                            >
                                                Edit Product
                                            </Link>
                                            <button
                                                @click="deleteProduct"
                                                class="flex-1 text-center px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors"
                                            >
                                                Delete Product
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                                    Quick Actions
                                </h3>
                                
                                <div class="space-y-3">
                                    <Link
                                        :href="route('products.show', product.id)"
                                        class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
                                    >
                                        <span class="mr-2">👁️</span>
                                        View on Marketplace
                                    </Link>
                                    
                                    <Link
                                        :href="route('seller.products.edit', product.id)"
                                        class="w-full flex items-center justify-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors"
                                    >
                                        <span class="mr-2">✏️</span>
                                        Edit Product
                                    </Link>
                                    
                                    <Link
                                        :href="route('seller.products.index')"
                                        class="w-full flex items-center justify-center px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                    >
                                        <span class="mr-2">📋</span>
                                        All My Products
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SellerLayout>
</template>
