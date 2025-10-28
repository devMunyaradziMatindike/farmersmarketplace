<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    products: Object,
    stats: Object,
    categories: Array,
    filters: Object
});

const searchQuery = ref(props.filters.search || '');
const selectedStatus = ref(props.filters.status || '');
const selectedCategory = ref(props.filters.category_id || '');
const sortBy = ref(props.filters.sort || 'created_at');
const sortOrder = ref(props.filters.order || 'desc');

const applyFilters = () => {
    router.get(route('admin.products.index'), {
        search: searchQuery.value,
        status: selectedStatus.value,
        category_id: selectedCategory.value,
        sort: sortBy.value,
        order: sortOrder.value
    }, {
        preserveState: true
    });
};

const clearFilters = () => {
    searchQuery.value = '';
    selectedStatus.value = '';
    selectedCategory.value = '';
    sortBy.value = 'created_at';
    sortOrder.value = 'desc';
    applyFilters();
};

const deleteProduct = (productId) => {
    if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
        router.delete(route('admin.products.destroy', productId));
    }
};

const approveProduct = (productId) => {
    if (confirm('Approve this product listing?')) {
        router.patch(route('admin.products.approve', productId));
    }
};

const rejectProduct = (productId) => {
    if (confirm('Reject/delist this product?')) {
        router.patch(route('admin.products.reject', productId));
    }
};
</script>

<template>
    <Head title="Product Management" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Product Management 📦
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">
                        Moderate and manage all product listings
                    </p>
                </div>

                <!-- Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Products</p>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.total }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Available</p>
                        <p class="text-2xl font-semibold text-green-600 dark:text-green-400">{{ stats.available }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Sold Out</p>
                        <p class="text-2xl font-semibold text-red-600 dark:text-red-400">{{ stats.sold_out }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Soon Available</p>
                        <p class="text-2xl font-semibold text-yellow-600 dark:text-yellow-400">{{ stats.soon_available }}</p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Delisted</p>
                        <p class="text-2xl font-semibold text-gray-600 dark:text-gray-400">{{ stats.delisted }}</p>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-8">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Search
                                </label>
                                <input
                                    v-model="searchQuery"
                                    @keyup.enter="applyFilters"
                                    type="text"
                                    placeholder="Product name..."
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Status
                                </label>
                                <select
                                    v-model="selectedStatus"
                                    @change="applyFilters"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                >
                                    <option value="">All Status</option>
                                    <option value="available">Available</option>
                                    <option value="sold_out">Sold Out</option>
                                    <option value="soon_to_be_available">Soon Available</option>
                                    <option value="delisted">Delisted</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Category
                                </label>
                                <select
                                    v-model="selectedCategory"
                                    @change="applyFilters"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                >
                                    <option value="">All Categories</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Sort By
                                </label>
                                <select
                                    v-model="sortBy"
                                    @change="applyFilters"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                >
                                    <option value="created_at">Date Listed</option>
                                    <option value="name">Name</option>
                                    <option value="price">Price</option>
                                </select>
                            </div>

                            <div class="flex items-end">
                                <button
                                    @click="clearFilters"
                                    class="w-full px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                >
                                    Clear Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Products ({{ products.total }})
                        </h3>
                    </div>
                    <div class="p-6">
                        <div v-if="products.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div
                                v-for="product in products.data"
                                :key="product.id"
                                class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden hover:shadow-md transition-shadow"
                            >
                                <img
                                    :src="product.primary_photo"
                                    :alt="product.name"
                                    class="w-full h-48 object-cover"
                                />
                                <div class="p-4">
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                                        {{ product.name }}
                                    </h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                                        {{ product.description }}
                                    </p>
                                    <div class="flex items-center justify-between mb-2">
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
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                                        <p>Seller: {{ product.seller.name }}</p>
                                        <p>Category: {{ product.category.name }}</p>
                                        <p>Location: {{ product.location }}</p>
                                    </div>
                                    <div class="flex items-center space-x-2 mt-4">
                                        <Link
                                            :href="route('admin.products.show', product.id)"
                                            class="flex-1 text-center px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700"
                                        >
                                            View
                                        </Link>
                                        <button
                                            v-if="product.status !== 'available'"
                                            @click="approveProduct(product.id)"
                                            class="flex-1 px-3 py-1 text-sm bg-green-600 text-white rounded hover:bg-green-700"
                                        >
                                            Approve
                                        </button>
                                        <button
                                            v-if="product.status === 'available'"
                                            @click="rejectProduct(product.id)"
                                            class="flex-1 px-3 py-1 text-sm bg-yellow-600 text-white rounded hover:bg-yellow-700"
                                        >
                                            Delist
                                        </button>
                                        <button
                                            @click="deleteProduct(product.id)"
                                            class="px-3 py-1 text-sm bg-red-600 text-white rounded hover:bg-red-700"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-12">
                            <p class="text-gray-500 dark:text-gray-400">No products found.</p>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Showing {{ products.from }} to {{ products.to }} of {{ products.total }} products
                            </div>
                            <div class="flex space-x-2">
                                <template v-for="link in products.links" :key="link.label">
                                    <span
                                        v-if="!link.url"
                                        :class="[
                                            'px-3 py-1 rounded-md text-sm cursor-not-allowed opacity-50',
                                            link.active
                                                ? 'bg-primary-600 text-white'
                                                : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'
                                        ]"
                                        v-html="link.label"
                                    ></span>
                                    <Link
                                        v-else
                                        :href="link.url"
                                        :class="[
                                            'px-3 py-1 rounded-md text-sm',
                                            link.active
                                                ? 'bg-primary-600 text-white'
                                                : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                                        ]"
                                        v-html="link.label"
                                    />
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>





