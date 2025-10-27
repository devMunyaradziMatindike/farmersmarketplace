<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const showSellerRegistration = ref(false);
</script>

<template>
    <GuestLayout>
        <Head title="Join Musika Wedu" />

        <!-- Musika Wedu Branding -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold brand-name text-primary-600 dark:text-primary-400 mb-2">
                MUSIKA WEDU
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Zimbabwe's Premier Agricultural Marketplace
            </p>
        </div>

        <!-- User Type Selection -->
        <div class="space-y-6">
            <div class="text-center">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    How would you like to use Musika Wedu?
                </h2>
                <p class="text-gray-600 dark:text-gray-400 mb-8">
                    Choose your role to get started
                </p>
            </div>

            <!-- Buyer Option (No Registration Required) -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900 dark:to-green-800 p-6 rounded-lg border border-green-200 dark:border-green-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                I'm a Buyer
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Browse products, contact sellers directly
                            </p>
                            <div class="mt-2 text-xs text-green-700 dark:text-green-300">
                                ✅ No registration required
                            </div>
                        </div>
                    </div>
                    <Link
                        :href="route('home')"
                        class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:shadow-md"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Start Shopping
                    </Link>
                </div>
            </div>

            <!-- Seller Option (Registration Required) -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900 dark:to-blue-800 p-6 rounded-lg border border-blue-200 dark:border-blue-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                I'm a Seller/Farmer
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                List products, manage inventory, grow your business
                            </p>
                            <div class="mt-2 text-xs text-blue-700 dark:text-blue-300">
                                📝 Registration required
                            </div>
                        </div>
                    </div>
                    <button
                        @click="showSellerRegistration = true"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:shadow-md"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Register as Seller
                    </button>
                </div>
            </div>
        </div>

        <!-- Seller Registration Methods (Modal/Dropdown) -->
        <div v-if="showSellerRegistration" class="mt-8 p-6 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-center mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                    Choose Your Registration Method
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Select how you'd like to create your seller account
                </p>
            </div>

            <div class="space-y-4">
                <!-- Google Registration -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <!-- Real Google Logo -->
                                <svg class="w-8 h-8" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Sign in with Google
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Recommended:</strong> Fast and secure registration
                                </p>
                                <div class="mt-1 text-xs text-green-600 dark:text-green-400">
                                    ✅ Instant verification ✅ No SMS required
                                </div>
                            </div>
                        </div>
                        <a
                            :href="route('google.redirect')"
                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-sm text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:bg-gray-50 active:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:shadow-md"
                        >
                            <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24">
                                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            Use Google
                        </a>
                    </div>
                </div>

                <!-- Phone Registration -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <span class="text-3xl">📱</span>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    Register with Phone Number
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    <strong>Alternative:</strong> SMS verification required
                                </p>
                                <div class="mt-1 text-xs text-blue-600 dark:text-blue-400">
                                    📱 SMS verification 📞 Phone number required
                                </div>
                            </div>
                        </div>
                        <Link
                            :href="route('register-phone')"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm hover:shadow-md"
                        >
                            <span class="text-lg mr-2">📱</span>
                            Use Phone
                        </Link>
                    </div>
                </div>
            </div>
        </div>

        <!-- Information Boxes -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Buyer Information -->
            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-200 dark:border-green-700">
                <h4 class="font-semibold text-green-800 dark:text-green-200 mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    As a Buyer, you can:
                </h4>
                <ul class="text-sm text-green-700 dark:text-green-300 space-y-1">
                    <li>• Browse all available products</li>
                    <li>• Search and filter by category, price, location</li>
                    <li>• Contact sellers directly via phone/WhatsApp</li>
                    <li>• View detailed product information and photos</li>
                    <li>• No registration required - start shopping immediately!</li>
                </ul>
            </div>

            <!-- Seller Information -->
            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-700">
                <h4 class="font-semibold text-blue-800 dark:text-blue-200 mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    As a Seller, you can:
                </h4>
                <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                    <li>• List unlimited products with photos</li>
                    <li>• Manage inventory and pricing</li>
                    <li>• Track product views and inquiries</li>
                    <li>• Update product availability status</li>
                    <li>• Grow your business nationwide</li>
                </ul>
            </div>
        </div>

        <div class="flex items-center justify-center mt-6">
            <Link
                :href="route('login')"
                class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                Already have a seller account? Sign in
            </Link>
        </div>
    </GuestLayout>
</template>
