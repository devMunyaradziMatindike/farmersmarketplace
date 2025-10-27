<template>
    <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <Link :href="route('home')" class="flex items-center gap-2">
                    <span class="text-3xl">🌾</span>
                    <div class="hidden sm:block">
                        <div class="text-xl brand-name">
                            Musika Wedu
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 -mt-1">
                            Zimbabwe
                        </div>
                    </div>
                </Link>

                <!-- Search Bar (Desktop) -->
                <div class="hidden md:block flex-1 max-w-2xl mx-8">
                    <SearchBar :initialQuery="filters.search" />
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-4">
                    <!-- Dark Mode Toggle -->
                    <button
                        @click="toggleDarkMode"
                        class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                    >
                        <svg v-if="isDark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <!-- User Menu -->
                    <div v-if="$page.props.auth.user" class="relative">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button class="flex items-center gap-2 px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                    <div class="w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center font-semibold text-sm">
                                        {{ $page.props.auth.user.name[0].toUpperCase() }}
                                    </div>
                                    <span class="hidden sm:inline text-sm font-medium text-gray-700 dark:text-gray-200">
                                        {{ $page.props.auth.user.name }}
                                    </span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div v-else class="flex items-center gap-2">
                        <Link
                            :href="route('login')"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:text-primary-600 dark:hover:text-primary-400 transition"
                        >
                            Seller Login
                        </Link>
                        <Link
                            :href="route('register')"
                            class="px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 transition"
                        >
                            Become a Seller
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Mobile Search Bar -->
            <div class="md:hidden pb-3">
                <SearchBar :initialQuery="filters.search" />
            </div>
        </div>
    </header>
</template>

<script setup>
import { ref, onMounted } from 'vue';
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

onMounted(() => {
    isDark.value = document.documentElement.classList.contains('dark');
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

