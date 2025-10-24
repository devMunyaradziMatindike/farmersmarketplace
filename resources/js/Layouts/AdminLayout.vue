<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminNavigation from '@/Components/Admin/AdminNavigation.vue';
import CrownIcon from '@/Components/Icons/CrownIcon.vue';

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
    <div class="min-h-screen bg-gray-100 dark:bg-gray-950">
        <Head>
            <title slot="title" :title="title">
                <slot name="title" />
            </title>
        </Head>

        <!-- Navigation -->
        <AdminNavigation 
            :current-route="$page.component" 
            @toggle-mobile-menu="toggleMobileMenu"
        >
            <template v-if="showMobileMenu" #mobileMenu>
                <!-- Mobile menu content is handled by AdminNavigation -->
            </template>
        </AdminNavigation>

        <!-- Mobile Menu Overlay -->
        <div v-if="showMobileMenu" class="md:hidden">
            <div class="fixed inset-0 z-40 flex">
                <div class="fixed inset-0 bg-gray-600 bg-opacity-75" @click="showMobileMenu = false"></div>
                <div class="relative flex-1 flex flex-col max-w-xs w-full bg-gray-900">
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
                                    { name: 'Dashboard', href: 'admin.dashboard', icon: '📊' },
                                    { name: 'Users', href: 'admin.users.index', icon: '👥' },
                                    { name: 'Products', href: 'admin.products.index', icon: '📦' },
                                    { name: 'Categories', href: 'admin.categories.index', icon: '🏷️' },
                                    { name: 'Settings', href: 'admin.settings.index', icon: '⚙️' }
                                ]"
                                :key="item.name"
                                :href="route(item.href)"
                                class="group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-300 hover:text-white hover:bg-gray-800"
                            >
                                <span class="mr-3">{{ item.icon }}</span>
                                {{ item.name }}
                            </Link>
                        </nav>
                    </div>
                    <div class="flex-shrink-0 flex border-t border-gray-800 p-4">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-full flex items-center justify-center">
                                    <CrownIcon 
                                        :size="16" 
                                        variant="gold" 
                                        class="text-white"
                                    />
                                </div>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-300">
                                    {{ $page.props.auth.user.name }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    Administrator
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
        <footer class="bg-gradient-to-br from-gray-900 to-gray-800 border-t border-gray-700">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-6">
                    <!-- Brand Section -->
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center">
                            <CrownIcon 
                                :size="20" 
                                variant="gold" 
                                class="text-white"
                            />
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Musika Wedu</h3>
                            <p class="text-xs text-gray-400">Admin Portal</p>
                        </div>
                    </div>
                    
                    <!-- Navigation Links -->
                    <div class="flex flex-wrap items-center gap-6">
                        <Link
                            :href="route('home')"
                            class="text-sm text-gray-400 hover:text-white transition-colors flex items-center gap-2 group"
                        >
                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            View Site
                        </Link>
                        <Link
                            method="post"
                            :href="route('logout')"
                            as="button"
                            class="text-sm text-red-400 hover:text-red-300 transition-colors flex items-center gap-2 group"
                        >
                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </Link>
                    </div>
                </div>
                
                <!-- Legal Links -->
                <div class="mt-6 pt-6 border-t border-gray-700">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="flex flex-wrap items-center gap-4 text-sm">
                            <span class="text-gray-500">Admin Dashboard</span>
                            <span class="text-gray-600">•</span>
                            <span class="text-gray-500">Platform Management</span>
                            <span class="text-gray-600">•</span>
                            <Link href="/intellectual-property.html" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2 group">
                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                IP Policy
                            </Link>
                        </div>
                        <div class="text-sm text-gray-400">
                            © {{ new Date().getFullYear() }} Musika Wedu. All rights reserved.
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

