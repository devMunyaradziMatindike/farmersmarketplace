<template>
    <Link :href="route('products.show', product.id)" class="block group">
        <!-- Mobile Layout: Horizontal (Amazon-style) -->
        <div class="md:hidden bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="flex gap-3 p-3">
                <!-- Product Image (Left) -->
                <div class="relative w-32 sm:w-36 flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden aspect-square">
                    <img
                        :src="product.photos?.[0]?.photo_url || '/images/placeholder.svg'"
                        :alt="product.name"
                        class="w-full h-full object-cover"
                    />
                    
                    <!-- Status Badge (Top Left) -->
                    <div class="absolute top-1 left-1">
                        <span
                            v-if="product.status === 'available'"
                            class="px-1.5 py-0.5 bg-green-500 text-white text-[10px] font-semibold rounded-full"
                        >
                            Available
                        </span>
                        <span
                            v-else-if="product.status === 'sold_out'"
                            class="px-1.5 py-0.5 bg-red-500 text-white text-[10px] font-semibold rounded-full"
                        >
                            Sold Out
                        </span>
                    </div>

                    <!-- Image Gallery Indicator -->
                    <div v-if="product.photos && product.photos.length > 1" class="absolute bottom-1 left-1 bg-black/70 text-white text-[10px] px-1.5 py-0.5 rounded flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                            <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ product.photos.length }}
                    </div>
                </div>

                <!-- Product Details (Right) -->
                <div class="flex-1 flex flex-col min-w-0">
                    <!-- Product Name -->
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-1 line-clamp-2 leading-tight">
                        {{ product.name }}
                    </h3>
                    
                    <!-- Category/Seller Info -->
                    <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ product.category?.name }}
                        </span>
                        <span v-if="product.user?.name" class="text-xs text-gray-400 dark:text-gray-500">•</span>
                        <span v-if="product.user?.name" class="text-xs text-gray-500 dark:text-gray-400 truncate">
                            {{ product.user.name }}
                        </span>
                    </div>

                    <!-- Price Section -->
                    <div class="mb-2">
                        <div :class="[
                            'text-lg text-gray-900 dark:text-white',
                            product.currency === 'USD' ? 'font-bold' : 'font-semibold'
                        ]">
                            <span v-if="product.currency === 'USD'">USD </span>
                            <span v-else>ZWG </span>{{ parseFloat(product.price).toFixed(2) }}
                        </div>
                        <!-- Converted Price -->
                        <div v-if="product.currency && product.currency_rate" class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                            ≈ <span v-if="product.currency === 'USD'">ZWG </span><span v-else>USD </span>{{ product.converted_price }}
                        </div>
                    </div>

                    <!-- Location -->
                    <div v-if="product.location" class="flex items-center gap-1 mb-3 text-xs text-gray-500 dark:text-gray-400">
                        <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        <span class="truncate">{{ product.location }}</span>
                    </div>

                    <!-- See Options Button -->
                    <button 
                        class="w-full py-2.5 px-4 bg-gray-900 dark:bg-gray-700 hover:bg-gray-800 dark:hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors flex items-center justify-center gap-2 touch-manipulation mt-auto"
                        @click.prevent="contactSeller"
                    >
                        See options
                    </button>
                </div>
            </div>
        </div>

        <!-- Desktop Layout: Vertical Card (Original) -->
        <div class="hidden md:block bg-white dark:bg-gray-800 rounded-lg shadow card-hover overflow-hidden">
            <!-- Product Image -->
            <div class="relative aspect-[4/3] sm:aspect-square overflow-hidden bg-gray-100 dark:bg-gray-700">
                <img
                    :src="product.photos?.[0]?.photo_url || '/images/placeholder.svg'"
                    :alt="product.name"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                />
                
                <!-- Status Badge -->
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

                <!-- Category Badge -->
                <div class="absolute top-2 left-2">
                    <span class="px-2 py-1 bg-white/90 dark:bg-gray-800/90 text-primary-600 dark:text-primary-400 text-xs font-medium rounded-full backdrop-blur-sm">
                        {{ product.category?.name }}
                    </span>
                </div>
            </div>

            <!-- Product Info -->
            <div class="p-3 sm:p-4">
                <!-- Seller Name -->
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
                <h3 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2 min-h-[2.5rem]">
                    {{ product.name }}
                </h3>
                
                <!-- Price & Location -->
                <div class="flex items-end justify-between mb-3">
                    <div class="flex-1">
                        <!-- Primary Price -->
                        <div :class="[
                            'text-lg sm:text-2xl text-primary-600 dark:text-primary-400',
                            product.currency === 'USD' ? 'font-bold' : 'font-semibold'
                        ]">
                            <span v-if="product.currency === 'USD'">USD </span>
                            <span v-else>ZWG </span>{{ parseFloat(product.price).toFixed(2) }}
                        </div>
                        <!-- Converted Price -->
                        <div v-if="product.currency && product.currency_rate" class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                            ≈ <span v-if="product.currency === 'USD'">ZWG </span><span v-else>USD </span>{{ product.converted_price }}
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
                    class="w-full py-2.5 sm:py-2 px-4 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors flex items-center justify-center gap-2 touch-manipulation"
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
    window.location.href = route('products.show', props.product.id);
};
</script>
