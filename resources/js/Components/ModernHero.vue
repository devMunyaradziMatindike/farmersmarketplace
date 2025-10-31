<template>
    <div class="relative">
        <!-- Slanted Cards Grid Section -->
        <div class="bg-white dark:bg-gray-900 py-8 sm:py-12 lg:py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Title -->
                <div class="text-center mb-8 sm:mb-10 lg:mb-12">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-3">
                        Explore Our Marketplace
                    </h2>
                    <p class="text-base sm:text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto px-2">
                        Discover quality agricultural products and services across Zimbabwe
                    </p>
                </div>

                <!-- Mobile Layout: Amazon-style 2x2 Grid -->
                <div class="md:hidden">
                    <div class="grid grid-cols-2 gap-3">
                        <Link 
                            v-for="(slide, index) in slides" 
                            :key="index"
                            :href="getCta1Link(slide)"
                            class="group block"
                        >
                            <div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-300">
                                <!-- Large Image -->
                                <div class="relative aspect-square overflow-hidden bg-gray-100 dark:bg-gray-700">
                                    <img 
                                        :src="slide.image"
                                        :alt="slide.subtitle"
                                        class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                                        :style="{ objectPosition: slide.objectPosition || 'center center' }"
                                        loading="lazy"
                                        decoding="async"
                                    />
                                </div>
                                <!-- Category Label, Description and CTA (mobile) -->
                                <div class="p-3">
                                    <h3 class="text-sm font-bold text-gray-900 dark:text-white mb-1.5 line-clamp-2 leading-tight">
                                        {{ slide.subtitle }}
                                    </h3>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-2 line-clamp-2 leading-snug">
                                        {{ slide.description }}
                                    </p>
                                    <div class="flex items-center gap-1 text-xs text-primary-600 dark:text-primary-400 font-medium">
                                        <span>{{ slide.cta1 }}</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Desktop Layout: Slanted Cards (Original) -->
                <div class="hidden md:grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
                    <div 
                        v-for="(slide, index) in slides" 
                        :key="index"
                        class="group relative transform transition-transform duration-300 hover:scale-105 hover:z-50"
                        :class="getCardRotationClass(index)"
                        :style="getCardZIndex(index)"
                    >
                        <!-- Card -->
                        <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden h-72 lg:h-80 transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:z-20">
                            <!-- Card Image -->
                            <div class="absolute inset-0">
                                <img 
                                    :src="slide.image"
                                    :alt="slide.subtitle"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                    :style="{ objectPosition: slide.objectPosition || 'center center' }"
                                    loading="lazy"
                                    decoding="async"
                                />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                            </div>
                            
                            <!-- Card Content (inverse rotation for readability) -->
                            <div 
                                class="relative h-full flex flex-col justify-end p-4 lg:p-6 text-white"
                                :class="getInverseRotationClass(index)"
                            >
                                <div class="text-xl lg:text-2xl mb-2">{{ slide.advertising.split(' ')[0] }}</div>
                                <h3 class="text-base lg:text-lg font-bold mb-2 line-clamp-2">
                                    {{ slide.subtitle }}
                                </h3>
                                <p class="text-xs lg:text-sm opacity-90 mb-4 line-clamp-2">
                                    {{ slide.description }}
                                </p>
                                <Link 
                                    :href="getCta1Link(slide)"
                                    class="inline-block bg-white text-primary-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-50 transition-colors transform group-hover:scale-105 min-h-[44px] flex items-center justify-center"
                                >
                                    {{ slide.cta1 }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    slides: {
        type: Array,
        required: true
    }
})

// Generate rotation patterns (-6 to +6 degrees) mapped to Tailwind classes (desktop only)
const rotationClasses = [
    'rotate-[5deg]',   // -5 degrees
    '-rotate-[4deg]',   // 4 degrees
    'rotate-[6deg]',  // -6 degrees
    '-rotate-[3deg]',   // 3 degrees
    'rotate-[4deg]',  // -4 degrees
    '-rotate-[5deg]',   // 5 degrees
    'rotate-[3deg]',  // -3 degrees
    '-rotate-[6deg]'    // 6 degrees
]

const inverseRotationClasses = [
    '-rotate-[5deg]',   // inverse of -5
    'rotate-[4deg]',  // inverse of 4
    '-rotate-[6deg]',   // inverse of -6
    'rotate-[3deg]',  // inverse of 3
    '-rotate-[4deg]',   // inverse of -4
    'rotate-[5deg]',  // inverse of 5
    '-rotate-[3deg]',   // inverse of -3
    'rotate-[6deg]'   // inverse of 6
]

// Get card rotation class (only on desktop)
function getCardRotationClass(index) {
    return rotationClasses[index % rotationClasses.length]
}

// Get card z-index for layering
function getCardZIndex(index) {
    return {
        zIndex: 100 - index * 2
    }
}

// Get inverse rotation class for text readability
function getInverseRotationClass(index) {
    return inverseRotationClasses[index % inverseRotationClasses.length]
}

// CTA Button Navigation Functions
function getCta1Link(slide) {
    const cta1Map = {
        'Buy Fresh Maize': route('products.index', { category_id: 1 }),
        'Rent Equipment': route('products.index', { category_id: 8 }),
        'Book Harvesting': route('products.index', { category_id: 11 }),
        'Rent Planter': route('products.index', { category_id: 8 }),
        'Get Irrigation': route('products.index', { category_id: 8 }),
        'Buy Cattle': route('products.index', { category_id: 5 }),
        'Buy Sheep': route('products.index', { category_id: 5 }),
        'Buy Goats': route('products.index', { category_id: 5 }),
        'Find Consultants': route('products.index', { category_id: 13 })
    }
    return cta1Map[slide.cta1] || route('products.index')
}

function getCta2Link(slide) {
    const cta2Map = {
        'Find Local Farmers': route('products.index'),
        'List Your Tractor': route('register'),
        'Offer Services': route('register'),
        'List Equipment': route('register'),
        'Install System': route('register'),
        'Sell Livestock': route('register'),
        'List Animals': route('register'),
        'Sell Goats': route('register')
    }
    return cta2Map[slide.cta2] || route('register')
}
</script>

<style scoped>
/* Smooth hover transitions for cards */
.group:hover {
    transition: transform 0.3s ease, z-index 0.3s ease;
}
</style>

