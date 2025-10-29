<template>
    <div class="relative">
        <!-- Main Hero Banner (First Slide) -->
        <div class="relative h-[55vh] sm:h-[60vh] md:h-[70vh] lg:h-[75vh] overflow-hidden">
            <div class="absolute inset-0">
                <picture>
                    <source v-if="mainHero.imageWebp" :srcset="mainHero.imageWebp" type="image/webp" />
                    <img 
                        :src="mainHero.image"
                        :alt="mainHero.subtitle"
                        class="w-full h-full object-cover transition-all duration-1000"
                        :style="{ objectPosition: mainHero.objectPosition || 'center center' }"
                        loading="eager"
                        decoding="async"
                    />
                </picture>
                <!-- Fallback gradient -->
                <div 
                    v-if="!mainHero.image"
                    class="absolute inset-0 bg-gradient-to-br from-green-600 to-green-800 flex items-center justify-center"
                >
                    <div class="text-center text-white">
                        <div class="text-5xl sm:text-6xl mb-3">🌾</div>
                        <h3 class="text-xl sm:text-2xl font-bold mb-2">{{ mainHero.subtitle }}</h3>
                        <p class="text-base sm:text-lg opacity-90">{{ mainHero.advertising }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Overlay with bottom gradient -->
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-black/35"></div>
                <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
            </div>
            
            <!-- Hero Content -->
            <div class="relative z-10 h-full flex items-center justify-center">
                <div class="text-center text-white max-w-4xl mx-auto px-2 sm:px-4">
                    <!-- Main Title -->
                    <h1 class="text-2xl xs:text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold mb-4 tracking-tight brand-name-white break-words whitespace-normal">
                        MUSIKA WEDU
                    </h1>
                    
                    <!-- Subtitle -->
                    <p class="text-lg sm:text-2xl mb-4 font-light">
                        {{ mainHero.subtitle }}
                    </p>
                    
                    <!-- Advertising Statement -->
                    <div class="bg-white/15 sm:bg-white/20 backdrop-blur-sm rounded-2xl p-4 sm:p-6 mb-6 sm:mb-8 max-w-2xl mx-auto">
                        <p class="text-base sm:text-xl md:text-2xl font-semibold mb-2 sm:mb-3">
                            {{ mainHero.advertising }}
                        </p>
                        <p class="text-sm sm:text-lg opacity-90">
                            {{ mainHero.description }}
                        </p>
                    </div>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center mb-6 sm:mb-8">
                        <Link 
                            :href="getCta1Link(mainHero)"
                            class="mobile-btn bg-white text-primary-600 hover:bg-primary-50"
                        >
                            🛒 {{ mainHero.cta1 }}
                        </Link>
                        <Link 
                            :href="getCta2Link(mainHero)"
                            class="mobile-btn bg-primary-600 text-white hover:bg-primary-700"
                        >
                            📱 {{ mainHero.cta2 }}
                        </Link>
                    </div>
                    
                    <!-- Buyer Info -->
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 sm:p-4 max-w-xl mx-auto">
                        <p class="text-white/90 text-xs sm:text-sm">
                            ✅ No registration required • Browse freely • Contact sellers directly
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Slanted Cards Grid Section -->
        <div class="bg-white dark:bg-gray-900 py-12 sm:py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Section Title -->
                <div class="text-center mb-10 sm:mb-12">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-3">
                        Explore Our Marketplace
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                        Discover quality agricultural products and services across Zimbabwe
                    </p>
                </div>

                <!-- Cards Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
                    <div 
                        v-for="(slide, index) in featuredSlides" 
                        :key="index"
                        class="group relative sm:transform sm:transition-transform sm:duration-300 sm:hover:scale-105 sm:hover:z-50"
                        :class="getCardRotationClass(index)"
                        :style="getCardZIndex(index)"
                    >
                        <!-- Card -->
                        <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden h-72 sm:h-80 transform transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:z-20">
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
                                class="relative h-full flex flex-col justify-end p-4 sm:p-6 text-white"
                                :class="getInverseRotationClass(index)"
                            >
                                <div class="text-2xl sm:text-3xl mb-2">{{ slide.advertising.split(' ')[0] }}</div>
                                <h3 class="text-base sm:text-lg font-bold mb-2 line-clamp-2">
                                    {{ slide.subtitle }}
                                </h3>
                                <p class="text-xs sm:text-sm opacity-90 mb-4 line-clamp-2">
                                    {{ slide.description }}
                                </p>
                                <Link 
                                    :href="getCta1Link(slide)"
                                    class="inline-block bg-white text-primary-600 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-50 transition-colors transform group-hover:scale-105"
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
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    slides: {
        type: Array,
        required: true
    }
})

// First slide as main hero banner
const mainHero = computed(() => props.slides[0] || {})

// Remaining slides for cards grid
const featuredSlides = computed(() => props.slides.slice(1))

// Generate rotation patterns (-6 to +6 degrees) mapped to Tailwind classes
const rotationClasses = [
    'sm:-rotate-[5deg]',   // -5 degrees
    'sm:rotate-[4deg]',   // 4 degrees
    'sm:-rotate-[6deg]',  // -6 degrees
    'sm:rotate-[3deg]',   // 3 degrees
    'sm:-rotate-[4deg]',  // -4 degrees
    'sm:rotate-[5deg]',   // 5 degrees
    'sm:-rotate-[3deg]',  // -3 degrees
    'sm:rotate-[6deg]'    // 6 degrees
]

const inverseRotationClasses = [
    'sm:rotate-[5deg]',   // inverse of -5
    'sm:-rotate-[4deg]',  // inverse of 4
    'sm:rotate-[6deg]',   // inverse of -6
    'sm:-rotate-[3deg]',  // inverse of 3
    'sm:rotate-[4deg]',   // inverse of -4
    'sm:-rotate-[5deg]',  // inverse of 5
    'sm:rotate-[3deg]',   // inverse of -3
    'sm:-rotate-[6deg]'   // inverse of 6
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
        'Buy Goats': route('products.index', { category_id: 5 })
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

