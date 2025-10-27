<template>
    <div class="relative">
        <!-- Use the new Hero Slider -->
        <HeroSlider />
        
        <!-- Statistics Section (below slider) -->
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

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
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
import HeroSlider from './HeroSlider.vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    stats: {
        type: Object,
        required: true
    }
})
</script>