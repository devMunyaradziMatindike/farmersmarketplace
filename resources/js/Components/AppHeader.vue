<template>
    <header 
        :class="[
            'border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 transition-all duration-300',
            isScrolled 
                ? 'bg-white/95 dark:bg-gray-900/95 backdrop-blur-md shadow-md' 
                : 'bg-white dark:bg-gray-800'
        ]"
    >
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8">
            <div class="flex items-center justify-between h-14 sm:h-16">
                <!-- Logo - Always visible with responsive sizing -->
                <Link :href="route('home')" class="flex items-center gap-2 flex-shrink-0">
                    <span class="text-2xl sm:text-3xl">🌾</span>
                    <div class="block">
                        <div class="text-lg sm:text-xl brand-name">
                            Musika Wedu
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 -mt-1 hidden xs:block">
                            todye tichiguta
                        </div>
                    </div>
                </Link>

                <!-- Search Bar (Desktop) -->
                <div class="hidden lg:block flex-1 max-w-2xl mx-4">
                    <SearchBar :initialQuery="filters.search" />
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2 sm:gap-4">
                    <Link
                        :href="route('market.pricing')"
                        class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-3 py-1.5 sm:py-2 text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition touch-manipulation min-h-[44px] min-w-[44px] justify-center"
                    >
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <span class="hidden xs:inline">Market Pricing</span>
                        <span class="xs:hidden">Pricing</span>
                    </Link>
                    <!-- Dark Mode Toggle -->
                    <button
                        @click="toggleDarkMode"
                        class="p-1.5 sm:p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition touch-manipulation"
                    >
                        <svg v-if="isDark" class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg v-else class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <!-- User Menu -->
                    <div v-if="$page.props.auth.user" class="relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button class="flex items-center gap-1 sm:gap-2 px-2 sm:px-3 py-1.5 sm:py-2 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition touch-manipulation">
                                    <div class="w-6 h-6 sm:w-8 sm:h-8 bg-primary-600 text-white rounded-full flex items-center justify-center font-semibold text-xs sm:text-sm">
                                        {{ $page.props.auth.user.name[0].toUpperCase() }}
                                    </div>
                                    <span class="hidden sm:inline text-sm font-medium text-gray-700 dark:text-gray-200">
                                        {{ $page.props.auth.user.name }}
                                    </span>
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('dashboard')">
                                    Dashboard
                                </DropdownLink>
                                <DropdownLink :href="route('profile.edit')">
                                    Profile
                                </DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">
                                    Log Out
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>

                    <!-- Guest Actions -->
                    <div v-else class="flex items-center gap-1 sm:gap-2">
                        <Link
                            :href="route('login')"
                            class="px-2 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-primary-600 dark:hover:text-primary-400 transition touch-manipulation"
                        >
                            <span class="hidden sm:inline">Seller Login</span>
                            <span class="sm:hidden">Login</span>
                        </Link>
                        
                        <!-- Events Button -->
                        <Link
                            :href="route('events.index')"
                            class="px-2 sm:px-4 py-1.5 sm:py-2 text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition touch-manipulation flex items-center gap-1.5"
                        >
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="hidden sm:inline">Events</span>
                            <span class="sm:hidden">Events</span>
                        </Link>
                        
                        <Link
                            :href="route('register')"
                            class="px-2 sm:px-4 py-1.5 sm:py-2 bg-primary-600 text-white text-xs sm:text-sm font-medium rounded-lg hover:bg-primary-700 transition touch-manipulation"
                        >
                            <span class="hidden sm:inline">Become a Seller</span>
                            <span class="sm:hidden">Sell</span>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Mobile Search Bar -->
            <div class="lg:hidden pb-3">
                <SearchBar :initialQuery="filters.search" />
            </div>
        </div>
    </header>

    <!-- Mobile Bottom Navigation -->
    <nav class="sm:hidden fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 z-50 safe-area-pb">
        <div class="flex items-center justify-around py-1">
            <!-- Home -->
            <Link 
                :href="route('home')" 
                class="flex flex-col items-center gap-1 p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors min-h-[60px] justify-center"
                :class="{ 'text-primary-600 dark:text-primary-400': route().current('home') }"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="text-xs font-medium">Home</span>
            </Link>
            
            <!-- Market Pricing -->
            <Link 
                :href="route('market.pricing')" 
                class="flex flex-col items-center gap-1 p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors min-h-[60px] justify-center"
                :class="{ 'text-primary-600 dark:text-primary-400': route().current('market.pricing') }"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span class="text-xs font-medium">Pricing</span>
            </Link>
            
            <!-- Products -->
            <Link 
                :href="route('products.index')" 
                class="flex flex-col items-center gap-1 p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors min-h-[60px] justify-center"
                :class="{ 'text-primary-600 dark:text-primary-400': route().current('products.*') }"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span class="text-xs font-medium">Products</span>
            </Link>
            
            <!-- Events -->
            <Link 
                :href="route('events.index')" 
                class="flex flex-col items-center gap-1 p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors min-h-[60px] justify-center"
                :class="{ 'text-primary-600 dark:text-primary-400': route().current('events.*') }"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-xs font-medium">Events</span>
            </Link>
            
            <!-- Sell/Register -->
            <Link 
                :href="route('register')" 
                class="flex flex-col items-center gap-1 p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors min-h-[60px] justify-center"
                :class="{ 'text-primary-600 dark:text-primary-400': route().current('register') }"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span class="text-xs font-medium">Sell</span>
            </Link>
            
            <!-- Profile/Login -->
            <Link 
                v-if="$page.props.auth.user"
                :href="route('dashboard')" 
                class="flex flex-col items-center gap-1 p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors min-h-[60px] justify-center"
                :class="{ 'text-primary-600 dark:text-primary-400': route().current('dashboard') }"
            >
                <div class="w-5 h-5 bg-primary-600 text-white rounded-full flex items-center justify-center font-semibold text-xs">
                    {{ $page.props.auth.user.name[0].toUpperCase() }}
                </div>
                <span class="text-xs font-medium">Profile</span>
            </Link>
            <Link 
                v-else
                :href="route('login')" 
                class="flex flex-col items-center gap-1 p-2 text-gray-600 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors min-h-[60px] justify-center"
                :class="{ 'text-primary-600 dark:text-primary-400': route().current('login') }"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="text-xs font-medium">Login</span>
            </Link>
        </div>
    </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import Dropdown from './Dropdown.vue';
import DropdownLink from './DropdownLink.vue';
import SearchBar from './SearchBar.vue';

defineProps({
    filters: {
        type: Object,
        default: () => ({})
    }
});

const isDark = ref(false);
const isScrolled = ref(false);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 50;
};

onMounted(() => {
    isDark.value = document.documentElement.classList.contains('dark');
    window.addEventListener('scroll', handleScroll, { passive: true });
    // Initial check
    handleScroll();
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const toggleDarkMode = () => {
    isDark.value = !isDark.value;
    if (isDark.value) {
        document.documentElement.classList.add('dark');
        localStorage.setItem('darkMode', 'true');
    } else {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('darkMode', 'false');
    }
};
</script>

