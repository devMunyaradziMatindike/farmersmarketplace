<script setup>
import SellerLayout from '@/Layouts/SellerLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    orders: {
        type: Object,
        default: () => ({
            data: [],
            links: []
        })
    },
    stats: {
        type: Object,
        default: () => ({
            total_orders: 0,
            pending_orders: 0,
            completed_orders: 0,
            total_revenue: 0
        })
    }
});

const searchQuery = ref('');
const selectedStatus = ref('');
const sortBy = ref('created_at');

const filteredOrders = computed(() => {
    let filtered = props.orders.data;

    if (searchQuery.value) {
        filtered = filtered.filter(order => 
            order.product_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            order.buyer_name.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }

    if (selectedStatus.value) {
        filtered = filtered.filter(order => order.status === selectedStatus.value);
    }

    // Sort orders
    filtered.sort((a, b) => {
        switch (sortBy.value) {
            case 'product_name':
                return a.product_name.localeCompare(b.product_name);
            case 'total_amount':
                return a.total_amount - b.total_amount;
            case 'created_at':
                return new Date(b.created_at) - new Date(a.created_at);
            default:
                return 0;
        }
    });

    return filtered;
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-ZW', {
        style: 'currency',
        currency: 'USD'
    }).format(price);
};

const getStatusColor = (status) => {
    const colors = {
        pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        confirmed: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        shipped: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
        delivered: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        cancelled: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        refunded: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
    };
    return colors[status] || colors.pending;
};

const updateOrderStatus = (orderId, newStatus) => {
    const form = useForm({
        status: newStatus
    });
    
    form.patch(route('seller.orders.update', orderId), {
        onSuccess: () => {
            // Order status updated successfully
        }
    });
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <Head title="Orders" />

    <SellerLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Order Stats -->
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
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.total_orders }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                                        <span class="text-yellow-600 dark:text-yellow-400 text-lg">⏳</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.pending_orders }}</p>
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
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.completed_orders }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                        <span class="text-blue-600 dark:text-blue-400 text-lg">💰</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ formatPrice(stats.total_revenue) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters and Search -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-8">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Search Orders
                                </label>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search by product or buyer..."
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Filter by Status
                                </label>
                                <select
                                    v-model="selectedStatus"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                >
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="shipped">Shipped</option>
                                    <option value="delivered">Delivered</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Sort By
                                </label>
                                <select
                                    v-model="sortBy"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                >
                                    <option value="created_at">Date</option>
                                    <option value="product_name">Product Name</option>
                                    <option value="total_amount">Amount</option>
                                </select>
                            </div>

                            <div class="flex items-end">
                                <button
                                    @click="searchQuery = ''; selectedStatus = ''; sortBy = 'created_at'"
                                    class="w-full px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                >
                                    Clear Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders List -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Orders ({{ filteredOrders.length }})
                        </h3>
                    </div>
                    <div class="p-6">
                        <div v-if="filteredOrders.length > 0" class="space-y-4">
                            <div
                                v-for="order in filteredOrders"
                                :key="order.id"
                                class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 hover:shadow-md transition-shadow"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-4">
                                            <img
                                                v-if="order.product_photo"
                                                :src="order.product_photo"
                                                :alt="order.product_name"
                                                class="w-16 h-16 rounded-lg object-cover"
                                            />
                                            <div
                                                v-else
                                                class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center"
                                            >
                                                <span class="text-gray-400 text-2xl">📦</span>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-lg font-medium text-gray-900 dark:text-white">
                                                    {{ order.product_name }}
                                                </h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Order #{{ order.id }} • {{ formatDate(order.created_at) }}
                                                </p>
                                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                                    Buyer: {{ order.buyer_name }} • {{ order.buyer_phone }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                            {{ formatPrice(order.total_amount) }}
                                        </div>
                                        <div class="mt-2">
                                            <span
                                                :class="getStatusColor(order.status)"
                                                class="inline-flex px-3 py-1 text-sm font-medium rounded-full"
                                            >
                                                {{ order.status.replace('_', ' ') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Order Details -->
                                <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Quantity:</span>
                                        <span class="ml-2 text-gray-900 dark:text-white">{{ order.quantity }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Unit Price:</span>
                                        <span class="ml-2 text-gray-900 dark:text-white">{{ formatPrice(order.unit_price) }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400">Delivery:</span>
                                        <span class="ml-2 text-gray-900 dark:text-white">{{ order.delivery_method }}</span>
                                    </div>
                                </div>

                                <!-- Order Actions -->
                                <div class="mt-4 flex items-center justify-between">
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ order.notes || 'No additional notes' }}
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <select
                                            v-model="order.status"
                                            @change="updateOrderStatus(order.id, order.status)"
                                            class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1 dark:bg-gray-700 dark:text-white"
                                        >
                                            <option value="pending">Pending</option>
                                            <option value="confirmed">Confirmed</option>
                                            <option value="shipped">Shipped</option>
                                            <option value="delivered">Delivered</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                        <Link
                                            :href="route('seller.orders.show', order.id)"
                                            class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300"
                                        >
                                            View Details
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-8">
                            <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                <span class="text-gray-400 text-4xl">📦</span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                                No Orders Found
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">
                                {{ searchQuery || selectedStatus ? 'Try adjusting your filters' : 'You haven\'t received any orders yet' }}
                            </p>
                            <Link
                                v-if="!searchQuery && !selectedStatus"
                                :href="route('seller.products.create')"
                                class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors"
                            >
                                <span class="mr-2">➕</span>
                                Add Your First Product
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SellerLayout>
</template>
