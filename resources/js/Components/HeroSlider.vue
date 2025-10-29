<template>
    <div class="relative h-[55vh] sm:h-[60vh] md:h-[70vh] lg:h-[75vh] overflow-hidden">
        <!-- Slider Container -->
        <div class="relative w-full h-full">
            <div 
                v-for="(slide, index) in slides" 
                :key="index"
                :class="[
                    'absolute inset-0 transition-all duration-1000 ease-in-out transform',
                    currentSlide === index 
                        ? 'translate-x-0 opacity-100' 
                        : currentSlide > index 
                            ? '-translate-x-full opacity-0' 
                            : 'translate-x-full opacity-0'
                ]"
            >
                <!-- Background Image (responsive) -->
                <div class="absolute inset-0">
                    <picture>
                        <source v-if="slide.imageWebp" :srcset="slide.imageWebp" type="image/webp" />
                        <img 
                            :src="slide.image"
                            :srcset="slide.srcset || ''"
                            sizes="(max-width: 640px) 100vw, 100vw"
                            :alt="slide.subtitle"
                            class="w-full h-full object-cover transition-all duration-1000"
                            :style="{ objectPosition: slide.objectPosition || 'center center' }"
                            :loading="index === 0 ? 'eager' : 'lazy'"
                            decoding="async"
                            @error="handleImageError($event, index)"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                        />
                    </picture>
                    <!-- Fallback content if image fails to load -->
                    <div 
                        class="absolute inset-0 bg-gradient-to-br from-green-600 to-green-800 flex items-center justify-center"
                        style="display: none;"
                    >
                        <div class="text-center text-white">
                            <div class="text-5xl sm:text-6xl mb-3">🌾</div>
                            <h3 class="text-xl sm:text-2xl font-bold mb-2">{{ slide.subtitle }}</h3>
                            <p class="text-base sm:text-lg opacity-90">{{ slide.advertising }}</p>
                        </div>
                    </div>
                </div>

                <!-- Overlay with bottom gradient for legibility -->
                <div class="absolute inset-0">
                    <div class="absolute inset-0 bg-black/35"></div>
                    <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                </div>
                
                <!-- Content -->
                <div class="relative z-10 h-full flex items-center justify-center">
                    <div class="text-center text-white max-w-4xl mx-auto px-2 sm:px-4">
                        <!-- Main Title -->
                        <h1 class="text-2xl xs:text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold mb-4 tracking-tight brand-name-white break-words whitespace-normal">
                            MUSIKA WEDU
                        </h1>
                        
                        <!-- Subtitle -->
                        <p class="text-lg sm:text-2xl mb-4 font-light">
                            {{ slide.subtitle }}
                        </p>
                        
                        <!-- Advertising Statement -->
                        <div class="bg-white/15 sm:bg-white/20 backdrop-blur-sm rounded-2xl p-4 sm:p-6 mb-6 sm:mb-8 max-w-2xl mx-auto">
                            <p class="text-base sm:text-xl md:text-2xl font-semibold mb-2 sm:mb-3">
                                {{ slide.advertising }}
                            </p>
                            <p class="text-sm sm:text-lg opacity-90">
                                {{ slide.description }}
                            </p>
                        </div>
                        
                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center mb-6 sm:mb-8">
                            <Link 
                                :href="getCta1Link(slide)"
                                class="mobile-btn bg-white text-primary-600 hover:bg-primary-50"
                            >
                                🛒 {{ slide.cta1 }}
                            </Link>
                            <Link 
                                :href="getCta2Link(slide)"
                                class="mobile-btn bg-primary-600 text-white hover:bg-primary-700"
                            >
                                📱 {{ slide.cta2 }}
                            </Link>
                        </div>

                        <!-- Buyer Information -->
                        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 sm:p-4 max-w-xl mx-auto">
                            <p class="text-white/90 text-xs sm:text-sm">
                                ✅ No registration required • Browse freely • Contact sellers directly
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Navigation Dots -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-3">
            <button 
                v-for="(slide, index) in slides" 
                :key="index"
                @click="goToSlide(index)"
                :class="[
                    'w-3 h-3 rounded-full transition-all duration-300',
                    currentSlide === index 
                        ? 'bg-white scale-125' 
                        : 'bg-white/50 hover:bg-white/75'
                ]"
            ></button>
        </div>
        
        <!-- Navigation Arrows -->
        <button 
            @click="previousSlide"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/20 hover:bg-white/30 text-white p-3 rounded-full transition-all duration-300"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        
        <button 
            @click="nextSlide"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/20 hover:bg-white/30 text-white p-3 rounded-full transition-all duration-300"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Link } from '@inertiajs/vue3'

const currentSlide = ref(0)
const autoSlideInterval = ref(null)

const slides = ref([
    {
        image: '/farm/maize-field.jpg',
        // srcset: '/farm/maize-field-640.jpg 640w, /farm/maize-field-1280.jpg 1280w, /farm/maize-field.jpg 1920w',
        fallback: '/images/placeholder-hero.svg',
        objectPosition: 'center 35%',
        subtitle: 'Zimbabwe\'s Premier Agricultural Marketplace',
        advertising: '🌾 Fresh Maize Direct from Farm',
        description: 'Connect with local farmers for the freshest maize and grains',
        cta1: 'Buy Fresh Maize',
        cta2: 'Find Local Farmers'
    },
    {
        image: '/farm/tractor.jpg',
        objectPosition: 'center 40%',
        fallback: '/images/placeholder-hero.svg',
        subtitle: 'Professional Farm Equipment',
        advertising: '🚜 Hire a Tractor Today',
        description: 'Rent tractors, combines, and farm machinery from verified owners',
        cta1: 'Rent Equipment',
        cta2: 'List Your Tractor'
    },
    {
        image: '/farm/combine-harvester.jpg',
        objectPosition: 'center 30%',
        fallback: '/images/placeholder-hero.svg',
        subtitle: 'Modern Harvesting Solutions',
        advertising: '🌾 Combine Harvester Services Available',
        description: 'Professional harvesting services for your crops',
        cta1: 'Book Harvesting',
        cta2: 'Offer Services'
    },
    {
        image: '/farm/planter.jpg',
        objectPosition: 'center 35%',
        fallback: '/images/placeholder-hero.svg',
        subtitle: 'Precision Planting Equipment',
        advertising: '🌱 Precision Planters for Hire',
        description: 'Modern planting equipment for efficient crop establishment',
        cta1: 'Rent Planter',
        cta2: 'List Equipment'
    },
    {
        image: '/farm/pivot-irrigation.jpg',
        objectPosition: 'center 40%',
        fallback: '/images/placeholder-hero.svg',
        subtitle: 'Smart Irrigation Systems',
        advertising: '💧 Pivot Irrigation Solutions',
        description: 'Efficient water management for optimal crop growth',
        cta1: 'Get Irrigation',
        cta2: 'Install System'
    },
    {
        image: '/farm/cattle.jpg',
        objectPosition: 'center 35%',
        fallback: '/images/placeholder-hero.svg',
        subtitle: 'Quality Livestock',
        advertising: '🐄 Premium Cattle for Sale',
        description: 'Healthy, well-bred cattle from trusted farmers',
        cta1: 'Buy Cattle',
        cta2: 'Sell Livestock'
    },
    {
        image: '/farm/sheep.jpg',
        objectPosition: 'center 45%',
        fallback: '/images/placeholder-hero.svg',
        subtitle: 'Sheep & Goat Farming',
        advertising: '🐑 Quality Sheep & Goats',
        description: 'Healthy sheep and goats from local breeders',
        cta1: 'Buy Sheep',
        cta2: 'List Animals'
    },
    {
        image: '/farm/goats.jpg',
        objectPosition: 'center 45%',
        fallback: '/images/placeholder-hero.svg',
        subtitle: 'Goat Farming Excellence',
        advertising: '🐐 Premium Goats Available',
        description: 'Quality goats for breeding, meat, or dairy production',
        cta1: 'Buy Goats',
        cta2: 'Sell Goats'
    }
])

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % slides.value.length
}

const previousSlide = () => {
    currentSlide.value = currentSlide.value === 0 ? slides.value.length - 1 : currentSlide.value - 1
}

const goToSlide = (index) => {
    currentSlide.value = index
}

const handleImageError = (event, slideIndex) => {
    console.warn(`Image failed to load for slide ${slideIndex}:`, event.target.src)
    // Hide the failed image and show fallback
    event.target.style.display = 'none'
    const fallback = event.target.nextElementSibling
    if (fallback) {
        fallback.style.display = 'flex'
    }
}

const preloadImages = () => {
    slides.value.forEach((slide, index) => {
        const img = new Image()
        img.onerror = () => {
            console.warn(`Failed to preload image: ${slide.image}`)
        }
        img.src = slide.image
    })
}

const startAutoSlide = () => {
    autoSlideInterval.value = setInterval(nextSlide, 5000) // Change slide every 5 seconds
}

const stopAutoSlide = () => {
    if (autoSlideInterval.value) {
        clearInterval(autoSlideInterval.value)
        autoSlideInterval.value = null
    }
}

onMounted(() => {
    preloadImages()
    startAutoSlide()
})

onUnmounted(() => {
    stopAutoSlide()
})

// CTA Button Navigation Functions
const getCta1Link = (slide) => {
    const cta1Map = {
        'Buy Fresh Maize': route('products.index', { category_id: 1 }), // Grains
        'Rent Equipment': route('products.index', { category_id: 8 }), // Farm Equipment
        'Book Harvesting': route('products.index', { category_id: 11 }), // Agricultural Services
        'Rent Planter': route('products.index', { category_id: 8 }), // Farm Equipment
        'Get Irrigation': route('products.index', { category_id: 8 }), // Farm Equipment
        'Buy Cattle': route('products.index', { category_id: 5 }), // Livestock
        'Buy Sheep': route('products.index', { category_id: 5 }), // Livestock
        'Buy Goats': route('products.index', { category_id: 5 }) // Livestock
    }
    return cta1Map[slide.cta1] || route('products.index')
}

const getCta2Link = (slide) => {
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
