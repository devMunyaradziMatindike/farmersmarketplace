<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import SellerNavigation from '@/Components/Seller/SellerNavigation.vue';

const showMobileMenu = ref(false);

const toggleMobileMenu = () => {
    showMobileMenu.value = !showMobileMenu.value;
};

const logout = () => {
    if (confirm('Are you sure you want to logout?')) {
        router.post(route('logout'));
    }
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <Head>
            <title slot="title" :title="title">
                <slot name="title" />
            </title>
        </Head>

        <!-- Navigation -->
        <SellerNavigation 
            :current-route="$page.component" 
            @toggle-mobile-menu="toggleMobileMenu"
        >
            <template v-if="showMobileMenu" #mobileMenu>
                <!-- Mobile menu content is handled by SellerNavigation -->
            </template>
        </SellerNavigation>

        <!-- Mobile Menu Overlay -->
        <div v-if="showMobileMenu" class="md:hidden">
            <div class="fixed inset-0 z-40 flex">
                <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="showMobileMenu = false"></div>
                <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white dark:bg-gray-800">
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button
                            @click="showMobileMenu = false"
                            class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        >
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                        <nav class="mt-5 px-2 space-y-1">
                            <Link
                                v-for="item in [
                                    { name: 'Dashboard', href: 'seller.dashboard', icon: '🏠' },
                                    { name: 'Products', href: 'seller.products.index', icon: '📦' },
                                    { name: 'Analytics', href: 'seller.analytics', icon: '📊' },
                                    { name: 'Orders', href: 'seller.orders', icon: '🛒' },
                                    { name: 'Messages', href: 'seller.messages', icon: '💬' },
                                    { name: 'Profile', href: 'seller.profile', icon: '👤' },
                                    { name: 'Settings', href: 'seller.settings', icon: '⚙️' }
                                ]"
                                :key="item.name"
                                :href="route(item.href)"
                                class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-50 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700"
                            >
                                <span class="mr-3">{{ item.icon }}</span>
                                {{ item.name }}
                            </Link>
                        </nav>
                    </div>
                    <div class="flex-shrink-0 flex border-t border-gray-200 dark:border-gray-700 p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                                    <span class="text-primary-600 dark:text-primary-400 text-sm">👤</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ $page.props.auth.user.name }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $page.props.auth.user.role }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-1">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 border-t border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                    <!-- Brand Section -->
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                            <span class="text-white text-lg">🌾</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Musika Wedu</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Seller Portal</p>
                        </div>
                    </div>
                    
                    <!-- Navigation Links -->
                    <div class="flex flex-wrap items-center gap-6">
                        <Link
                            :href="route('products.index')"
                            class="text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-2 group"
                        >
                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Browse Marketplace
                        </Link>
                        <button
                            @click="logout"
                            class="text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition-colors flex items-center gap-2 group"
                        >
                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </div>
                </div>
                
                <!-- Legal Links -->
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex flex-wrap items-center gap-4 text-sm">
                            <Link href="/terms-and-conditions.html" class="text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-2 group">
                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Terms & Conditions
                            </Link>
                            <Link href="/privacy-policy.html" class="text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-2 group">
                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Privacy Policy
                            </Link>
                            <Link href="/end-user-license-agreement.html" class="text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-2 group">
                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                EULA
                            </Link>
                            <Link href="/intellectual-property.html" class="text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-2 group">
                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                Intellectual Property
                            </Link>
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            © {{ new Date().getFullYear() }} Musika Wedu. All rights reserved.
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

