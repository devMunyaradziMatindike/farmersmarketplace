<template>
    <div class="bg-white dark:bg-gray-800 border-y border-gray-200 dark:border-gray-700 overflow-hidden py-8">
        <div class="max-w-7xl mx-auto px-4 mb-6">
            <h3 class="text-lg font-semibold text-center text-gray-900 dark:text-white">
                Our Trusted Partners
            </h3>
        </div>
        
        <!-- Infinite Scroll Container -->
        <div class="relative overflow-hidden">
            <!-- Gradient Overlays -->
            <div class="absolute left-0 top-0 bottom-0 w-32 bg-gradient-to-r from-white dark:from-gray-800 to-transparent z-10"></div>
            <div class="absolute right-0 top-0 bottom-0 w-32 bg-gradient-to-l from-white dark:from-gray-800 to-transparent z-10"></div>
            
            <!-- Scrolling Images -->
            <div class="flex animate-scroll-partners">
                <!-- First Set -->
                <div class="flex gap-16 px-8">
                    <img v-for="i in 5" :key="i" 
                         :src="`/farm/partners${i}.png`" 
                         :alt="`Partner ${i}`" 
                         class="h-20 w-auto object-contain grayscale hover:grayscale-0 transition-all duration-300 cursor-pointer"
                    />
                </div>
                <!-- Duplicate Set for Seamless Loop -->
                <div class="flex gap-16 px-8">
                    <img v-for="i in 5" :key="`dup-${i}`" 
                         :src="`/farm/partners${i}.png`" 
                         :alt="`Partner ${i}`" 
                         class="h-20 w-auto object-contain grayscale hover:grayscale-0 transition-all duration-300 cursor-pointer"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes scroll-partners {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

.animate-scroll-partners {
    animation: scroll-partners 25s linear infinite;
}

.animate-scroll-partners:hover {
    animation-play-state: paused;
}
</style>



