<template>
    <div class="relative">
        <!-- Use the Modern Hero with Slanted Cards -->
        <ModernHero :slides="heroSlides" />
        
        <!-- Statistics Section (below hero) -->
        <div class="bg-white dark:bg-gray-800 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="text-4xl font-bold text-primary-600 dark:text-primary-400 mb-2">
                            {{ stats.products }}+
                        </div>
                        <div class="text-gray-600 dark:text-gray-400 font-medium">
                            Products Available
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-primary-600 dark:text-primary-400 mb-2">
                            {{ stats.sellers }}+
                        </div>
                        <div class="text-gray-600 dark:text-gray-400 font-medium">
                            Verified Farmers
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-bold text-primary-600 dark:text-primary-400 mb-2">
                            {{ stats.categories }}+
                        </div>
                        <div class="text-gray-600 dark:text-gray-400 font-medium">
                            Categories
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seller Call-to-Action Section -->
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                        Are you a farmer or agricultural supplier?
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                        Join thousands of sellers already growing their business on Musika Wedu. 
                        List your products, reach more customers, and grow your agricultural business.
                    </p>
                </div>

                <!-- Mobile-only Benefits: Horizontal Snap Cards -->
                <div class="md:hidden -mx-4 px-4 py-6 bg-gradient-to-b from-transparent to-blue-950/10 dark:to-blue-900/20">
                    <div ref="benefitsCarousel" @scroll="updateBenefitIndex" class="flex gap-4 overflow-x-auto snap-x snap-mandatory no-scrollbar">
                        <!-- Card 1 -->
                        <div class="min-w-[85%] snap-center bg-white/90 dark:bg-gray-800/80 backdrop-blur rounded-2xl shadow-md p-5 border border-white/40 dark:border-gray-700">
                            <div class="w-12 h-12 rounded-xl bg-green-100 dark:bg-green-900 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-green-700 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">List Unlimited Products</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Upload photos, set prices, and manage your inventory with ease.</p>
                        </div>

                        <!-- Card 2 -->
                        <div class="min-w-[85%] snap-center bg-white/90 dark:bg-gray-800/80 backdrop-blur rounded-2xl shadow-md p-5 border border-white/40 dark:border-gray-700">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-blue-700 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Reach More Customers</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Connect with buyers across Zimbabwe and expand your reach.</p>
                        </div>

                        <!-- Card 3 -->
                        <div class="min-w-[85%] snap-center bg-white/90 dark:bg-gray-800/80 backdrop-blur rounded-2xl shadow-md p-5 border border-white/40 dark:border-gray-700">
                            <div class="w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-900 flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-purple-700 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-1">Track Performance</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Monitor views, inquiries, and sales to optimize listings.</p>
                        </div>
                    </div>

                    <!-- Dots under the carousel -->
                    <div class="mt-4 flex items-center justify-center gap-2">
                        <button
                            v-for="i in 3"
                            :key="`dot-`+i"
                            type="button"
                            @click="scrollBenefitsTo(i-1)"
                            class="h-2 rounded-full transition-all"
                            :class="[ currentBenefitIndex === (i-1) ? 'bg-primary-600 w-5' : 'bg-gray-300 dark:bg-gray-600 w-2' ]"
                            aria-label="Go to slide"
                        />
                    </div>

                    <!-- CTA under the carousel -->
                    <div class="mt-5 text-center">
                        <Link
                            :href="route('register')"
                            class="inline-flex items-center px-4 py-2 rounded-full bg-primary-600 text-white text-sm font-semibold shadow hover:bg-primary-700 transition-colors"
                        >
                            Start Selling Today
                        </Link>
                    </div>
                </div>

                <div class="hidden md:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    <!-- Seller Benefits -->
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">List Unlimited Products</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Upload photos, set prices, and manage your inventory with ease</p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Reach More Customers</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Connect with buyers across Zimbabwe and expand your market reach</p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Track Performance</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">Monitor views, inquiries, and sales to optimize your listings</p>
                    </div>
                </div>

                <!-- Seller Registration CTA -->
                <div class="text-center">
                    <Link
                        :href="route('register')"
                        class="inline-flex items-center px-8 py-4 bg-blue-600 text-white font-semibold text-lg rounded-lg hover:bg-blue-700 transition-colors duration-300 shadow-lg hover:shadow-xl"
                    >
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Start Selling Today
                    </Link>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-4">
                        Free registration • No monthly fees • Start listing immediately
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import ModernHero from './ModernHero.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    stats: {
        type: Object,
        required: true
    }
})

// Hero slides data
const heroSlides = ref([
    {
        image: '/farm/maize-field.jpg',
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
        image: '/farm/maize-field.jpg',
        objectPosition: 'center 35%',
        fallback: '/images/placeholder-hero.svg',
        subtitle: 'Fresh Maize & Grains',
        advertising: '🌾 Fresh Maize Direct from Farm',
        description: 'Connect with local farmers for the freshest maize and grains',
        cta1: 'Buy Fresh Maize',
        cta2: 'Find Local Farmers'
    }
])

// Mobile benefits carousel state
const benefitsCarousel = ref(null)
const currentBenefitIndex = ref(0)

const updateBenefitIndex = () => {
    const el = benefitsCarousel.value
    if (!el) {
        return
    }
    const containerWidth = el.clientWidth
    // Card is ~85% of container width and gap is 16px (gap-4)
    const cardWidth = containerWidth * 0.85
    const step = cardWidth + 16
    const idx = Math.round(el.scrollLeft / step)
    currentBenefitIndex.value = Math.max(0, Math.min(2, idx))
}

const scrollBenefitsTo = (index) => {
    const el = benefitsCarousel.value
    if (!el) {
        return
    }
    const containerWidth = el.clientWidth
    const cardWidth = containerWidth * 0.85
    const step = cardWidth + 16
    el.scrollTo({ left: index * step, behavior: 'smooth' })
    currentBenefitIndex.value = index
}

onMounted(() => {
    updateBenefitIndex()
})
</script>