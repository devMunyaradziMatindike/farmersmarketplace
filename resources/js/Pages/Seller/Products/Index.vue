<script setup>
import SellerLayout from '@/Layouts/SellerLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    products: {
        type: Object,
        required: true
    },
    categories: {
        type: Array,
        default: () => []
    }
});

const searchQuery = ref('');
const selectedCategory = ref('');
const selectedStatus = ref('');
const sortBy = ref('date_listed');

const filteredProducts = computed(() => {
    let filtered = props.products.data;

    if (searchQuery.value) {
        filtered = filtered.filter(product => 
            product.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            product.description.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }

    if (selectedCategory.value) {
        filtered = filtered.filter(product => product.category_id == selectedCategory.value);
    }

    if (selectedStatus.value) {
        filtered = filtered.filter(product => product.status === selectedStatus.value);
    }

    // Sort products
    filtered.sort((a, b) => {
        switch (sortBy.value) {
            case 'name':
                return a.name.localeCompare(b.name);
            case 'price':
                return a.price - b.price;
            case 'date_listed':
                return new Date(b.date_listed) - new Date(a.date_listed);
            default:
                return 0;
        }
    });

    return filtered;
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
</script>

<template>
    <Head title="My Products" />

    <SellerLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Filters and Search -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-6">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Search Products
                                </label>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search by name or description..."
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                />
                            </div>

                            <!-- Category Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Category
                                </label>
                                <select
                                    v-model="selectedCategory"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                >
                                    <option value="">All Categories</option>
                                    <option
                                        v-for="category in categories"
                                        :key="category.id"
                                        :value="category.id"
                                    >
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Status
                                </label>
                                <select
                                    v-model="selectedStatus"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                >
                                    <option value="">All Status</option>
                                    <option value="available">Available</option>
                                    <option value="sold_out">Sold Out</option>
                                    <option value="soon_to_be_available">Soon to be Available</option>
                                    <option value="delisted">Delisted</option>
                                </select>
                            </div>

                            <!-- Sort -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Sort By
                                </label>
                                <select
                                    v-model="sortBy"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                >
                                    <option value="date_listed">Date Listed</option>
                                    <option value="name">Name</option>
                                    <option value="price">Price</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div v-if="filteredProducts.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="product in filteredProducts"
                        :key="product.id"
                        class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden hover:shadow-md transition-shadow"
                    >
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
                                <p>📍 {{ product.location }}</p>
                                <p>📅 Listed: {{ new Date(product.date_listed).toLocaleDateString() }}</p>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center space-x-2">
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
                            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
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
                </div>

                <!-- Empty State -->
                <div v-else class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                    <div class="p-12 text-center">
                        <div class="mx-auto w-24 h-24 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mb-4">
                            <span class="text-primary-600 dark:text-primary-400 text-4xl">📦</span>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                            {{ searchQuery || selectedCategory || selectedStatus ? 'No products found' : 'No products yet' }}
                        </h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">
                            {{ searchQuery || selectedCategory || selectedStatus ? 
                                'Try adjusting your filters or search terms.' : 
                                'Start by adding your first product to the marketplace.' 
                            }}
                        </p>
                        <Link
                            v-if="!searchQuery && !selectedCategory && !selectedStatus"
                            :href="route('seller.products.create')"
                            class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors"
                        >
                            <span class="mr-2">➕</span>
                            Add Your First Product
                        </Link>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="products.links && products.links.length > 3" class="mt-8">
                    <nav class="flex items-center justify-between">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <Link
                                v-if="products.prev_page_url"
                                :href="products.prev_page_url"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700"
                            >
                                Previous
                            </Link>
                            <Link
                                v-if="products.next_page_url"
                                :href="products.next_page_url"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700"
                            >
                                Next
                            </Link>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    Showing
                                    <span class="font-medium">{{ products.from }}</span>
                                    to
                                    <span class="font-medium">{{ products.to }}</span>
                                    of
                                    <span class="font-medium">{{ products.total }}</span>
                                    results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                    <Link
                                        v-for="link in products.links"
                                        :key="link.label"
                                        :href="link.url"
                                        v-html="link.label"
                                        :class="[
                                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                            link.active
                                                ? 'z-10 bg-primary-50 dark:bg-primary-900 border-primary-500 dark:border-primary-400 text-primary-600 dark:text-primary-400'
                                                : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700',
                                            link.url === null ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                                        ]"
                                    />
                                </nav>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </SellerLayout>
</template>
