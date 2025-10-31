<template>
    <section class="py-16 bg-gradient-to-br from-primary-50 to-green-50 dark:from-gray-800 dark:to-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left: Text Content -->
                <div>
                    <span class="inline-block px-4 py-2 bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200 rounded-full text-sm font-semibold mb-4">
                        Why Choose Us
                    </span>
                    <h2 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-6">
                        Direct from Zimbabwe's Farms to Your Doorstep
                    </h2>
                    <p class="text-lg text-gray-700 dark:text-gray-300 mb-8">
                        Connect with verified farmers across Zimbabwe. Fresh produce, quality livestock, and agricultural services at your fingertips.
                    </p>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">🌾</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Fresh Products Daily</h3>
                                <p class="text-gray-600 dark:text-gray-400">Direct from farmers, ensuring quality and freshness</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">📍</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Nationwide Coverage</h3>
                                <p class="text-gray-600 dark:text-gray-400">Find farmers and products across all provinces</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">💬</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Easy Communication</h3>
                                <p class="text-gray-600 dark:text-gray-400">Contact sellers instantly via phone or WhatsApp</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex items-center gap-4">
                        <Link
                            :href="route('register')"
                            class="inline-flex items-center px-8 py-4 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-700 transition-all shadow-lg hover:shadow-xl hover:scale-105"
                        >
                            <span class="mr-2">🌾</span>
                            Join as a Seller
                        </Link>
                        <Link
                            :href="route('events.index')"
                            class="inline-flex items-center px-8 py-4 bg-white dark:bg-gray-800 text-primary-600 dark:text-primary-400 font-bold rounded-xl border-2 border-primary-600 dark:border-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all shadow-lg hover:shadow-xl hover:scale-105"
                        >
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Events
                        </Link>
                    </div>
                </div>

                <!-- Right: Image Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-4">
                        <img 
                            src="/farm/freshfarmproduce.png" 
                            alt="Fresh Produce" 
                            class="rounded-2xl shadow-xl w-full h-48 object-cover hover:scale-105 transition-transform duration-300"
                            @error="handleImageError"
                            onerror="this.src='/images/placeholder.svg'"
                        />
                        <img 
                            src="/farm/free-range-chickens.jpg" 
                            alt="Chickens" 
                            class="rounded-2xl shadow-xl w-full h-64 object-cover hover:scale-105 transition-transform duration-300"
                            @error="handleImageError"
                            onerror="this.src='/images/placeholder.svg'"
                        />
                    </div>
                    <div class="space-y-4 pt-8">
                        <img 
                            src="/farm/potatoes.png" 
                            alt="Potatoes" 
                            class="rounded-2xl shadow-xl w-full h-64 object-cover hover:scale-105 transition-transform duration-300"
                            @error="handleImageError"
                            onerror="this.src='/images/placeholder.svg'"
                        />
                        <img 
                            src="/farm/goats.jpg" 
                            alt="Goats" 
                            class="rounded-2xl shadow-xl w-full h-48 object-cover hover:scale-105 transition-transform duration-300"
                            @error="handleImageError"
                            onerror="this.src='/images/placeholder.svg'"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

const handleImageError = (event) => {
    console.warn('Image failed to load:', event.target.src);
    event.target.src = '/images/placeholder.svg';
};
</script>


