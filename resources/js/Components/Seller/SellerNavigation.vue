<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    currentRoute: {
        type: String,
        default: ''
    }
});

const navigation = [
    {
        name: 'Dashboard',
        href: 'seller.dashboard',
        icon: '🏠',
        description: 'Overview and quick stats'
    },
    {
        name: 'Products',
        href: 'seller.products.index',
        icon: '📦',
        description: 'Manage your listings'
    },
    {
        name: 'Analytics',
        href: 'seller.analytics',
        icon: '📊',
        description: 'Performance insights'
    },
    {
        name: 'Orders',
        href: 'seller.orders',
        icon: '🛒',
        description: 'Customer orders'
    },
    {
        name: 'Messages',
        href: 'seller.messages',
        icon: '💬',
        description: 'Customer conversations'
    },
    {
        name: 'Profile',
        href: 'seller.profile',
        icon: '👤',
        description: 'Your seller profile'
    },
    {
        name: 'Settings',
        href: 'seller.settings',
        icon: '⚙️',
        description: 'Account settings'
    }
];

const isActive = (routeName) => {
    return props.currentRoute === routeName || 
           (routeName === 'seller.dashboard' && props.currentRoute === 'dashboard');
};
</script>

<template>
    <nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo/Brand -->
                <div class="flex items-center">
                    <Link
                        :href="route('seller.dashboard')"
                        class="flex items-center space-x-2"
                    >
                        <span class="text-2xl">🌾</span>
                        <span class="text-xl font-bold text-primary-600 dark:text-primary-400 brand-name">
                            MUSIKA WEDU
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Seller</span>
                    </Link>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-1">
                    <Link
                        v-for="item in navigation"
                        :key="item.name"
                        :href="route(item.href)"
                        :class="[
                            'flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-colors',
                            isActive(item.href)
                                ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-200'
                                : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700'
                        ]"
                    >
                        <span class="mr-2">{{ item.icon }}</span>
                        {{ item.name }}
                    </Link>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button
                        @click="$emit('toggleMobileMenu')"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500"
                    >
                        <span class="sr-only">Open main menu</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div v-if="$slots.mobileMenu" class="md:hidden border-t border-gray-200 dark:border-gray-700">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="route(item.href)"
                    :class="[
                        'flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors',
                        isActive(item.href)
                            ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-200'
                            : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700'
                    ]"
                >
                    <span class="mr-3">{{ item.icon }}</span>
                    <div>
                        <div>{{ item.name }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ item.description }}</div>
                    </div>
                </Link>
            </div>
        </div>
    </nav>
</template>




