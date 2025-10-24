<template>
    <div class="relative h-[70vh] overflow-hidden">
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
                <!-- Background Image -->
                <div class="absolute inset-0">
                    <img 
                        :src="slide.image" 
                        :alt="slide.subtitle"
                        class="w-full h-full object-cover transition-all duration-1000"
                        @error="handleImageError($event, index)"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    />
                    <!-- Fallback content if image fails to load -->
                    <div 
                        class="absolute inset-0 bg-gradient-to-br from-green-600 to-green-800 flex items-center justify-center"
                        style="display: none;"
                    >
                        <div class="text-center text-white">
                            <div class="text-6xl mb-4">🌾</div>
                            <h3 class="text-2xl font-bold mb-2">{{ slide.subtitle }}</h3>
                            <p class="text-lg opacity-90">{{ slide.advertising }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Overlay -->
                <div class="absolute inset-0 bg-black/40"></div>
                
                <!-- Content -->
                <div class="relative z-10 h-full flex items-center justify-center">
                    <div class="text-center text-white max-w-4xl mx-auto px-4">
                        <!-- Main Title -->
                        <h1 class="text-6xl md:text-8xl font-bold mb-6 brand-name-white">
                            MUSIKA WEDU
                        </h1>
                        
                        <!-- Subtitle -->
                        <p class="text-2xl md:text-3xl mb-4 font-light">
                            {{ slide.subtitle }}
                        </p>
                        
                        <!-- Advertising Statement -->
                        <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6 mb-8 max-w-2xl mx-auto">
                            <p class="text-xl md:text-2xl font-semibold mb-4">
                                {{ slide.advertising }}
                            </p>
                            <p class="text-lg opacity-90">
                                {{ slide.description }}
                            </p>
                        </div>
                        
                        <!-- CTA Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <Link 
                                :href="getCta1Link(slide)"
                                class="bg-white text-primary-600 px-8 py-4 rounded-full font-semibold text-lg hover:bg-primary-50 transition-colors duration-300 flex items-center justify-center gap-2"
                            >
                                <span>🚜</span>
                                {{ slide.cta1 }}
                            </Link>
                            <Link 
                                :href="getCta2Link(slide)"
                                class="bg-primary-600 text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-primary-700 transition-colors duration-300 flex items-center justify-center gap-2"
                            >
                                <span>📱</span>
                                {{ slide.cta2 }}
                            </Link>
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
        fallback: '/images/placeholder-hero.svg',
        subtitle: 'Zimbabwe\'s Premier Agricultural Marketplace',
        advertising: '🌾 Fresh Maize Direct from Farm',
        description: 'Connect with local farmers for the freshest maize and grains',
        cta1: 'Buy Fresh Maize',
        cta2: 'Find Local Farmers'
    },
    {
        image: '/farm/tractor.jpg',
        fallback: '/images/placeholder-hero.svg',
        subtitle: 'Professional Farm Equipment',
        advertising: '🚜 Hire a Tractor Today',
        description: 'Rent tractors, combines, and farm machinery from verified owners',
        cta1: 'Rent Equipment',
        cta2: 'List Your Tractor'
    },
    {
        image: '/farm/combine-harvester.jpg',
        fallback: '/images/placeholder-hero.svg',
        subtitle: 'Modern Harvesting Solutions',
        advertising: '🌾 Combine Harvester Services Available',
        description: 'Professional harvesting services for your crops',
        cta1: 'Book Harvesting',
        cta2: 'Offer Services'
    },
    {
        image: '/farm/planter.jpg',
        fallback: '/images/placeholder-hero.svg',
        subtitle: 'Precision Planting Equipment',
        advertising: '🌱 Precision Planters for Hire',
        description: 'Modern planting equipment for efficient crop establishment',
        cta1: 'Rent Planter',
        cta2: 'List Equipment'
    },
    {
        image: '/farm/pivot-irrigation.jpg',
        fallback: '/images/placeholder-hero.svg',
        subtitle: 'Smart Irrigation Systems',
        advertising: '💧 Pivot Irrigation Solutions',
        description: 'Efficient water management for optimal crop growth',
        cta1: 'Get Irrigation',
        cta2: 'Install System'
    },
    {
        image: '/farm/cattle.jpg',
        fallback: '/images/placeholder-hero.svg',
        subtitle: 'Quality Livestock',
        advertising: '🐄 Premium Cattle for Sale',
        description: 'Healthy, well-bred cattle from trusted farmers',
        cta1: 'Buy Cattle',
        cta2: 'Sell Livestock'
    },
    {
        image: '/farm/sheep.jpg',
        fallback: '/images/placeholder-hero.svg',
        subtitle: 'Sheep & Goat Farming',
        advertising: '🐑 Quality Sheep & Goats',
        description: 'Healthy sheep and goats from local breeders',
        cta1: 'Buy Sheep',
        cta2: 'List Animals'
    },
    {
        image: '/farm/goats.jpg',
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
