<template>
    <Link :href="route('products.show', product.id)" class="block group">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-xl transition-all duration-300 overflow-hidden">
            <!-- Product Image -->
            <div class="relative aspect-square overflow-hidden bg-gray-100 dark:bg-gray-700">
                <img
                    :src="product.photos?.[0]?.photo_url || '/images/placeholder.svg'"
                    :alt="product.name"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                />
                
                <!-- Status Badge (Top Right) -->
                <div class="absolute top-2 right-2">
                    <span
                        v-if="product.status === 'available'"
                        class="px-2 py-1 bg-green-500 text-white text-xs font-semibold rounded-full shadow-lg"
                    >
                        Available
                    </span>
                    <span
                        v-else-if="product.status === 'sold_out'"
                        class="px-2 py-1 bg-red-500 text-white text-xs font-semibold rounded-full shadow-lg"
                    >
                        Sold Out
                    </span>
                </div>

                <!-- Category Badge (Top Left) -->
                <div class="absolute top-2 left-2">
                    <span class="px-2 py-1 bg-white/90 dark:bg-gray-800/90 text-primary-600 dark:text-primary-400 text-xs font-medium rounded-full backdrop-blur-sm">
                        {{ product.category?.name }}
                    </span>
                </div>
            </div>

            <!-- Product Info -->
            <div class="p-4">
                <!-- Seller Name (Small) -->
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                        {{ product.user?.name }}
                    </span>
                    <span class="text-xs text-gray-400 dark:text-gray-500">
                        {{ formatDate(product.date_listed) }}
                    </span>
                </div>

                <!-- Product Name -->
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2 min-h-[2.5rem]">
                    {{ product.name }}
                </h3>
                
                <!-- Price & Location -->
                <div class="flex items-end justify-between mb-3">
                    <div class="flex-1">
                        <!-- Primary Price (Seller's Currency) -->
                        <div class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                            {{ product.currency === 'USD' ? '$' : 'ZWG$' }}{{ parseFloat(product.price).toFixed(2) }}
                            <span class="text-sm text-gray-500 dark:text-gray-400 ml-1">{{ product.currency || 'USD' }}</span>
                        </div>
                        <!-- Converted Price (Secondary) -->
                        <div v-if="product.currency && product.currency_rate" class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            ≈ {{ product.currency === 'USD' ? 'ZWG$' : '$' }}{{ product.converted_price }}
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center mt-1">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            {{ truncateLocation(product.location) }}
                        </div>
                    </div>
                </div>

                <!-- Contact Button -->
                <button 
                    class="w-full py-2 px-4 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center justify-center gap-2"
                    @click.prevent="contactSeller"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    Contact Seller
                </button>
            </div>
        </div>
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
});

const formatDate = (date) => {
    if (!date) return '';
    const d = new Date(date);
    const now = new Date();
    const diffTime = Math.abs(now - d);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays === 0) return 'Today';
    if (diffDays === 1) return 'Yesterday';
    if (diffDays < 7) return `${diffDays} days ago`;
    if (diffDays < 30) return `${Math.floor(diffDays / 7)} weeks ago`;
    return d.toLocaleDateString();
};

const truncateLocation = (location) => {
    if (!location) return '';
    return location.length > 20 ? location.substring(0, 20) + '...' : location;
};

const contactSeller = () => {
    // This will be prevented by @click.prevent on the Link
    window.location.href = route('products.show', props.product.id);
};
</script>


