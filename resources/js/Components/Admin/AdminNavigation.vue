<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import CrownIcon from '@/Components/Icons/CrownIcon.vue';

const props = defineProps({
    currentRoute: {
        type: String,
        default: ''
    }
});

const navigation = [
    {
        name: 'Dashboard',
        href: 'admin.dashboard',
        icon: '📊',
        description: 'Overview and statistics'
    },
    {
        name: 'Users',
        href: 'admin.users.index',
        icon: '👥',
        description: 'User management'
    },
    {
        name: 'Products',
        href: 'admin.products.index',
        icon: '📦',
        description: 'Product moderation'
    },
    {
        name: 'Categories',
        href: 'admin.categories.index',
        icon: '🏷️',
        description: 'Category management'
    },
    {
        name: 'Settings',
        href: 'admin.settings.index',
        icon: '⚙️',
        description: 'Platform settings'
    }
];

const isActive = (routeName) => {
    return props.currentRoute === routeName || 
           props.currentRoute.startsWith(routeName.replace('.index', ''));
};
</script>

<template>
    <nav class="bg-gray-900 shadow-sm border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo/Brand -->
                <div class="flex items-center">
                    <Link
                        :href="route('admin.dashboard')"
                        class="flex items-center space-x-2 group"
                    >
                        <div class="flex items-center justify-center w-8 h-8 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg group-hover:from-yellow-400 group-hover:to-yellow-500 transition-all duration-200">
                            <CrownIcon 
                                :size="20" 
                                variant="gold" 
                                class="text-white"
                            />
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold text-primary-400 brand-name">
                                MUSIKA WEDU
                            </span>
                            <span class="text-xs text-gray-400 -mt-1">Admin Portal</span>
                        </div>
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
                                ? 'bg-primary-900 text-primary-200'
                                : 'text-gray-300 hover:text-white hover:bg-gray-800'
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
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-500"
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
        <div v-if="$slots.mobileMenu" class="md:hidden border-t border-gray-800">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="route(item.href)"
                    :class="[
                        'flex items-center px-3 py-2 rounded-lg text-base font-medium transition-colors',
                        isActive(item.href)
                            ? 'bg-primary-900 text-primary-200'
                            : 'text-gray-300 hover:text-white hover:bg-gray-800'
                    ]"
                >
                    <span class="mr-3">{{ item.icon }}</span>
                    <div>
                        <div>{{ item.name }}</div>
                        <div class="text-xs text-gray-400">{{ item.description }}</div>
                    </div>
                </Link>
            </div>
        </div>
    </nav>
</template>



