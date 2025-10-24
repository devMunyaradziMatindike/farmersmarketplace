<script setup>
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
});

const deleteProduct = (productId) => {
    if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        router.delete(route('seller.products.destroy', productId));
    }
};

const updateProductStatus = (productId, newStatus) => {
    router.patch(route('seller.products.status', productId), {
        status: newStatus
    });
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

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-ZW', {
        style: 'currency',
        currency: 'USD'
    }).format(price);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};
</script>

<template>
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden hover:shadow-md transition-shadow">
        <!-- Product Image -->
        <div class="aspect-w-16 aspect-h-9 bg-gray-200 dark:bg-gray-700">
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
        </div>

        <!-- Product Info -->
        <div class="p-6">
            <div class="flex items-start justify-between mb-2">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                    {{ product.name }}
                </h3>
                <span
                    :class="getStatusColor(product.status)"
                    class="inline-flex px-2 py-1 text-xs font-medium rounded-full"
                >
                    {{ product.status.replace('_', ' ') }}
                </span>
            </div>

            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                {{ product.description }}
            </p>

            <div class="flex items-center justify-between mb-4">
                <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                    {{ formatPrice(product.price) }}
                </span>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    {{ product.category.name }}
                </span>
            </div>

            <div class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                <p class="flex items-center">
                    <span class="mr-1">📍</span>
                    {{ product.location }}
                </p>
                <p class="flex items-center">
                    <span class="mr-1">📅</span>
                    Listed: {{ formatDate(product.date_listed) }}
                </p>
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-2 mb-4">
                <Link
                    :href="route('seller.products.show', product.id)"
                    class="flex-1 text-center px-3 py-2 text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 border border-primary-300 dark:border-primary-600 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors"
                >
                    View
                </Link>
                <Link
                    :href="route('seller.products.edit', product.id)"
                    class="flex-1 text-center px-3 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                >
                    Edit
                </Link>
                <button
                    @click="deleteProduct(product.id)"
                    class="px-3 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 border border-red-300 dark:border-red-600 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                >
                    Delete
                </button>
            </div>

            <!-- Status Update -->
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Update Status:
                </label>
                <select
                    :value="product.status"
                    @change="updateProductStatus(product.id, $event.target.value)"
                    class="w-full px-2 py-1 text-xs border border-gray-300 dark:border-gray-600 rounded focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                >
                    <option value="available">Available</option>
                    <option value="sold_out">Sold Out</option>
                    <option value="soon_to_be_available">Soon to be Available</option>
                    <option value="delisted">Delisted</option>
                </select>
            </div>
        </div>
    </div>
</template>




