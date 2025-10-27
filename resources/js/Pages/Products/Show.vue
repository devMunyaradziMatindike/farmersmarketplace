<template>
    <Head>
        <title>{{ product.name }} - {{ product.category?.name }} | Musika Wedu</title>
        <meta name="description" :content="getProductDescription()">
        <meta name="keywords" :content="getProductKeywords()">
        <link rel="canonical" :href="getProductCanonicalUrl()">
        
        <!-- Open Graph for Product -->
        <meta property="og:title" :content="product.name + ' - Musika Wedu'">
        <meta property="og:description" :content="getProductDescription()">
        <meta property="og:url" :content="getProductCanonicalUrl()">
        <meta property="og:type" content="product">
        <meta property="og:image" :content="getProductImage()">
        
        <!-- Product Structured Data -->
        <script type="application/ld+json" v-html="getProductStructuredData()"></script>
    </Head>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Header -->
        <AppHeader :filters="{}" />

        <!-- Category Bar -->
        <CategoryBar :categories="[]" />

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-6 text-sm text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
                <Link :href="route('home')" class="hover:text-primary-600 dark:hover:text-primary-400">
                    Home
                </Link>
                <span class="mx-2">/</span>
                <Link :href="route('products.index')" class="hover:text-primary-600 dark:hover:text-primary-400">
                    Products
                </Link>
                <span class="mx-2">/</span>
                <Link 
                    :href="route('products.index', { category_id: product.category?.id })" 
                    class="hover:text-primary-600 dark:hover:text-primary-400"
                >
                    {{ product.category?.name }}
                </Link>
                <span class="mx-2">/</span>
                <span class="text-gray-900 dark:text-white font-medium">
                    {{ product.name }}
                </span>
            </nav>

            <!-- Product Details Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Images (2/3 width) -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden p-6">
                        <!-- Main Image -->
                        <div class="relative aspect-square bg-gray-100 dark:bg-gray-700 rounded-lg overflow-hidden mb-4">
                            <img
                                :src="selectedImage || product.photos?.[0]?.photo_url || '/images/placeholder.svg'"
                                :alt="product.name"
                                class="w-full h-full object-contain"
                            />
                        </div>

                        <!-- Thumbnail Gallery -->
                        <div v-if="product.photos && product.photos.length > 1" class="grid grid-cols-5 gap-2">
                            <button
                                v-for="photo in product.photos"
                                :key="photo.id"
                                @click="selectedImage = photo.photo_url"
                                class="aspect-square rounded-lg overflow-hidden border-2 transition"
                                :class="selectedImage === photo.photo_url || (!selectedImage && photo.is_primary)
                                    ? 'border-primary-500 ring-2 ring-primary-200'
                                    : 'border-gray-200 dark:border-gray-700 hover:border-primary-300'"
                            >
                                <img
                                    :src="photo.photo_url"
                                    :alt="`${product.name} - ${photo.id}`"
                                    class="w-full h-full object-cover"
                                />
                            </button>
                        </div>

                        <!-- Product Description -->
                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">
                                Product Description
                            </h3>
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed">
                                {{ product.description }}
                            </p>
                        </div>

                        <!-- Product Details -->
                        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                                Product Details
                            </h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Category:</span>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ product.category?.name }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Status:</span>
                                    <p class="font-medium text-gray-900 dark:text-white capitalize">
                                        {{ product.status.replace('_', ' ') }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Location:</span>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ product.location }}
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Listed:</span>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ formatDate(product.date_listed) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Purchase Info (1/3 width) -->
                <div class="lg:col-span-1">
                    <!-- Price Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 sticky top-24">
                        <!-- Category Badge -->
                        <span class="inline-block px-3 py-1 bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200 text-sm font-medium rounded-full mb-4">
                            {{ product.category?.name }}
                        </span>

                        <!-- Product Title -->
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                            {{ product.name }}
                        </h1>

                        <!-- Price -->
                        <div class="mb-6">
                            <div class="text-4xl font-bold text-primary-600 dark:text-primary-400">
                                ${{ product.price }}
                            </div>
                            <span
                                v-if="product.status === 'available'"
                                class="inline-flex items-center mt-2 px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-sm font-medium rounded-full"
                            >
                                ✓ Available Now
                            </span>
                            <span
                                v-else-if="product.status === 'sold_out'"
                                class="inline-flex items-center mt-2 px-3 py-1 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 text-sm font-medium rounded-full"
                            >
                                Sold Out
                            </span>
                        </div>

                        <!-- Seller Card -->
                        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                Seller Information
                            </h3>
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-12 h-12 bg-primary-600 text-white rounded-full flex items-center justify-center font-bold text-lg">
                                    {{ product.user?.name[0].toUpperCase() }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">
                                        {{ product.user?.name }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                        </svg>
                                        {{ product.location }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Buttons -->
                        <div class="space-y-3">
                            <a
                                :href="`tel:${product.contact_details}`"
                                class="flex items-center justify-center gap-2 w-full px-6 py-3 bg-primary-600 text-white font-semibold rounded-lg hover:bg-primary-700 transition shadow-lg hover:shadow-xl"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Call Seller
                            </a>
                            <a
                                :href="`https://wa.me/${product.contact_details.replace(/[^0-9]/g, '')}?text=Hi, I'm interested in your ${product.name} listed on Farmers Marketplace`"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="flex items-center justify-center gap-2 w-full px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition shadow-lg hover:shadow-xl"
                            >
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                WhatsApp Seller
                            </a>
                            <button
                                @click="shareProduct"
                                type="button"
                                class="flex items-center justify-center gap-2 w-full px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                </svg>
                                Share Product
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back to Products Button -->
            <div class="mt-8">
                <Link
                    :href="route('products.index', { category_id: product.category?.id })"
                    class="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to {{ product.category?.name }}
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppHeader from '@/Components/AppHeader.vue';
import CategoryBar from '@/Components/CategoryBar.vue';

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
});

const selectedImage = ref(props.product.photos?.[0]?.photo_url || null);

// SEO Methods
const getProductDescription = () => {
    const baseDesc = `Buy ${props.product.name} from ${props.product.user?.name} on Musika Wedu, Zimbabwe's premier agricultural marketplace.`;
    return props.product.description ? 
        `${props.product.description.substring(0, 150)}... ${baseDesc}` : 
        baseDesc;
};

const getProductKeywords = () => {
    const keywords = [
        props.product.name,
        props.product.category?.name,
        props.product.user?.name,
        'agricultural products Zimbabwe',
        'farmers Zimbabwe',
        'Musika Wedu'
    ].filter(Boolean);
    return keywords.join(', ');
};

const getProductCanonicalUrl = () => {
    return `https://musikawedu.co.zw/products/${props.product.id}`;
};

const getProductImage = () => {
    return props.product.photos?.[0]?.photo_url || 'https://musikawedu.co.zw/images/placeholder.svg';
};

const getProductStructuredData = () => {
    const structuredData = {
        "@context": "https://schema.org",
        "@type": "Product",
        "name": props.product.name,
        "description": props.product.description,
        "image": props.product.photos?.map(photo => photo.photo_url) || [],
        "category": props.product.category?.name,
        "brand": {
            "@type": "Brand",
            "name": "Musika Wedu"
        },
        "offers": {
            "@type": "Offer",
            "price": props.product.price,
            "priceCurrency": props.product.currency || "USD",
            "availability": props.product.status === 'available' ? "https://schema.org/InStock" : "https://schema.org/OutOfStock",
            "seller": {
                "@type": "Person",
                "name": props.product.user?.name
            },
            "url": getProductCanonicalUrl()
        },
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "4.5",
            "reviewCount": "1"
        }
    };
    
    return JSON.stringify(structuredData);
};

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const shareProduct = async () => {
    const shareData = {
        title: props.product.name,
        text: `Check out this ${props.product.name} on Musika Wedu! Only $${props.product.price}`,
        url: window.location.href
    };

    try {
        if (navigator.share) {
            await navigator.share(shareData);
        } else {
            // Fallback: copy link to clipboard
            await navigator.clipboard.writeText(window.location.href);
            alert('Link copied to clipboard!');
        }
    } catch (err) {
        if (err.name !== 'AbortError') {
            console.error('Error sharing:', err);
        }
    }
};
</script>
