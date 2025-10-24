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
    </div>
</template>

<script setup>
import HeroSlider from './HeroSlider.vue'

defineProps({
    stats: {
        type: Object,
        required: true
    }
})
</script>