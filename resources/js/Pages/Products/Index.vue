<template>
    <Head>
        <title>{{ getPageTitle() }} - 🌾 Musika Wedu | todye tichiguta</title>
        <meta name="description" :content="getPageDescription()">
        <meta name="keywords" :content="getPageKeywords()">
        <link rel="canonical" :href="getCanonicalUrl()">
        
        <!-- Open Graph for Products Page -->
        <meta property="og:title" :content="getPageTitle() + ' - Musika Wedu'">
        <meta property="og:description" :content="getPageDescription()">
        <meta property="og:url" :content="getCanonicalUrl()">
        <meta property="og:type" content="website">
        
        <!-- Structured Data will be injected via document head -->
    </Head>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Header -->
        <AppHeader :filters="filters" />

        <!-- Category Bar -->
        <CategoryBar :categories="categories" :selectedCategory="filters.category_id" />

        <!-- Hero Section (Only on homepage) -->
        <HeroSection v-if="!filters.search && !filters.category_id" :stats="stats" />

        <!-- Featured Section with Images -->
        <FeaturedSection v-if="!filters.search && !filters.category_id" />

        <!-- Product Showcase with Category Images -->
        <ProductShowcase v-if="!filters.search && !filters.category_id" :categories="categories" />

        <!-- Category Showcase (Only on homepage without filters) -->
        <CategoryShowcase v-if="!filters.search && !filters.category_id" :categories="categoriesWithCount" />

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-6 sm:py-8">
            <!-- Enhanced Search Section -->
            <div v-if="filters.search || showSearchBar" class="mb-6 sm:mb-8">
                <EnhancedSearch 
                    :categories="categories"
                    :locations="locations"
                    :initial-query="filters.search"
                    :initial-filters="filters"
                    @search="handleSearch"
                    @filter-change="handleFilterChange"
                />
            </div>

            <!-- Breadcrumb -->
            <nav class="flex mb-4 sm:mb-6 text-sm text-gray-500 dark:text-gray-400" aria-label="Breadcrumb">
                <Link :href="route('home')" class="hover:text-primary-600 dark:hover:text-primary-400">
                    Home
                </Link>
                <span class="mx-2">/</span>
                <span v-if="filters.category_id" class="text-gray-900 dark:text-white">
                    {{ getCurrentCategoryName() }}
                </span>
                <span v-else-if="filters.search" class="text-gray-900 dark:text-white">
                    Search: "{{ filters.search }}"
                </span>
                <span v-else class="text-gray-900 dark:text-white">
                    All Products
                </span>
            </nav>

            <!-- Active Filters & Results Count -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6 gap-3 sm:gap-0">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">
                        {{ getPageTitle() }}
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ products.total }} products found
                    </p>
                </div>

                <!-- View Toggle (Grid/List) -->
                <div class="flex items-center gap-2">
                    <button
                        @click="viewMode = 'grid'"
                        :class="[
                            'p-2 rounded-lg transition touch-manipulation',
                            viewMode === 'grid'
                                ? 'bg-primary-600 text-white'
                                : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                        ]"
                    >
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </button>
                    <button
                        @click="viewMode = 'list'"
                        :class="[
                            'p-2 rounded-lg transition touch-manipulation',
                            viewMode === 'list'
                                ? 'bg-primary-600 text-white'
                                : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
                        ]"
                    >
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 sm:gap-6" id="products-grid">
                <!-- Filters Sidebar (Desktop) -->
                <div class="hidden lg:block">
                    <FilterSidebar :filters="filters" />
                </div>

                <!-- Products Grid -->
                <div class="lg:col-span-3">
                    <!-- Products -->
                    <div
                        v-if="products.data.length > 0"
                        :class="[
                            'grid gap-4 sm:gap-6 mb-6 sm:mb-8',
                            viewMode === 'grid'
                                ? 'grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4'
                                : 'grid-cols-1'
                        ]"
                    >
                        <EnhancedProductCard
                            v-for="product in productsWithConversion"
                            :key="product.id"
                            :product="product"
                        />
                    </div>

                    <!-- Enhanced Empty State -->
                    <EnhancedEmptyState 
                        :search-query="filters.search"
                        :is-loading="isLoading"
                        :active-filters="filters"
                        :show-market-insights="showMarketInsights"
                        :market-insights="marketInsights"
                        @clear-filters="clearFilters"
                        @browse-categories="browseCategories"
                        @try-suggestion="trySuggestion"
                    />

                    <!-- Pagination -->
                    <div v-if="products.data.length > 0" class="flex justify-center">
                        <Pagination :links="products.links" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Partners Carousel (Infinite Scroll) -->
        <PartnersCarousel />

        <!-- Safe Trading & Security Section -->
        <SafeTradingSection />

        <!-- Footer -->
        <footer class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12 sm:mt-16">
            <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-12 sm:py-16">
                <!-- Main Footer Content -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 mb-8 sm:mb-12">
                    <!-- Company Info -->
                    <div class="lg:col-span-1">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl">🌾</span>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Musika Wedu</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Agricultural Marketplace</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 leading-relaxed">
                            Zimbabwe's Premier Agricultural Marketplace - Connecting farmers with buyers nationwide.
                        </p>
                        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 6a2 2 0 114 0 2 2 0 01-4 0zm6 0a2 2 0 114 0 2 2 0 01-4 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Nesso Systems (Pvt) Ltd</span>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            Quick Links
                        </h4>
                        <ul class="space-y-3">
                            <li>
                                <Link :href="route('home')" class="text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-2 group">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                    </svg>
                                    Home
                                </Link>
                            </li>
                            <li>
                                <Link :href="route('products.index')" class="text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-2 group">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Products
                                </Link>
                            </li>
                            <li>
                                <Link :href="route('register')" class="text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-2 group">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Become a Seller
                                </Link>
                            </li>
                            <li>
                                <Link href="#about" class="text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-2 group">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    About Us
                                </Link>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Categories -->
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Categories
                        </h4>
                        <ul class="space-y-3">
                            <li v-for="cat in categories.slice(0, 5)" :key="cat.id">
                                <Link :href="route('products.index', { category_id: cat.id })" class="text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-2 group">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    {{ cat.name }}
                                </Link>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Legal & Contact -->
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            Legal & Contact
                        </h4>
                        <ul class="space-y-3">
                            <li>
                                <a href="/terms-and-conditions.html" target="_blank" class="text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-2 group">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Terms & Conditions
                                </a>
                            </li>
                            <li>
                                <a href="/privacy-policy.html" target="_blank" class="text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-2 group">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    Privacy Policy
                                </a>
                            </li>
                            <li>
                                <a href="/cookie-policy.html" target="_blank" class="text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-2 group">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                    </svg>
                                    Cookie Policy
                                </a>
                            </li>
                            <li>
                                <a href="/end-user-license-agreement.html" target="_blank" class="text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-2 group">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                    End User License Agreement
                                </a>
                            </li>
                            <li>
                                <a href="/intellectual-property.html" target="_blank" class="text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-2 group">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                    Intellectual Property
                                </a>
                            </li>
                        </ul>
                        
                        <!-- Contact Info -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <h5 class="font-medium text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Contact Us
                            </h5>
                            <div class="space-y-3">
                                <a href="mailto:info@nessosystems.co.zw" class="text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-2 group">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    info@nessosystems.co.zw
                                </a>
                                <a href="tel:+263782339300" class="text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-2 group">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    +263 78 233 9300
                                </a>
                                <a href="https://wa.me/263782339300" target="_blank" class="text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400 transition-colors flex items-center gap-2 group">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom Bar -->
                <div class="pt-8 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
                        <div class="text-sm text-gray-500 dark:text-gray-400 text-center lg:text-left">
                            © 2025 <span class="font-semibold text-gray-700 dark:text-gray-300">Musika Wedu</span> by Nesso Systems (Pvt) Ltd. All rights reserved.
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-400 dark:text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>Farm 42, Coburn Estate, Chegutu, Zimbabwe</span>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppHeader from '@/Components/AppHeader.vue';
import CategoryBar from '@/Components/CategoryBar.vue';
import HeroSection from '@/Components/HeroSection.vue';
import FeaturedSection from '@/Components/FeaturedSection.vue';
import CategoryShowcase from '@/Components/CategoryShowcase.vue';
import EnhancedProductCard from '@/Components/EnhancedProductCard.vue';
import FilterSidebar from '@/Components/FilterSidebar.vue';
import Pagination from '@/Components/Pagination.vue';
import PartnersCarousel from '@/Components/PartnersCarousel.vue';
import SafeTradingSection from '@/Components/SafeTradingSection.vue';
import EnhancedSearch from '@/Components/EnhancedSearch.vue';
import EnhancedEmptyState from '@/Components/EnhancedEmptyState.vue';

const props = defineProps({
    products: {
        type: Object,
        required: true
    },
    categories: {
        type: Array,
        required: true
    },
    categoriesWithCount: {
        type: Array,
        default: () => []
    },
    filters: {
        type: Object,
        default: () => ({})
    },
    stats: {
        type: Object,
        default: () => ({
            products: 0,
            sellers: 0,
            categories: 11
        })
    },
    exchangeRate: {
        type: Number,
        default: 13.5000
    },
    locations: {
        type: Array,
        default: () => []
    }
});

const viewMode = ref('grid');
const isLoading = ref(false);
const showSearchBar = ref(false);
const showMarketInsights = ref(true);

// Market insights data
const marketInsights = ref({
    popularCrop: 'Maize',
    priceTrend: 'Rising'
});

// Currency conversion helper
const convertPrice = (amount, from, to) => {
    if (from === to || !from) return amount;
    const usdAmount = from === 'USD' ? amount : amount / props.exchangeRate;
    return to === 'USD' ? usdAmount : usdAmount * props.exchangeRate;
};

// Add converted price to each product
const productsWithConversion = computed(() => {
    return props.products.data.map(product => ({
        ...product,
        currency_rate: props.exchangeRate,
        converted_price: product.currency 
            ? convertPrice(product.price, product.currency, product.currency === 'USD' ? 'ZWG' : 'USD').toFixed(2)
            : null
    }));
});

const getCurrentCategoryName = () => {
    const category = props.categories.find(c => c.id == props.filters.category_id);
    return category ? category.name : 'Products';
};

const getPageTitle = () => {
    if (props.filters.search) {
        return `Search Results for "${props.filters.search}"`;
    }
    if (props.filters.category_id) {
        return getCurrentCategoryName();
    }
    return 'All Products';
};

// SEO Methods
const getPageDescription = () => {
    if (props.filters.search) {
        return `Find ${props.products?.total || 0} agricultural products matching "${props.filters.search}" on Musika Wedu, Zimbabwe's premier agricultural marketplace.`;
    }
    if (props.filters.category_id) {
        const category = props.categories?.find(c => c.id === props.filters.category_id);
        return `Browse ${category?.name || 'agricultural'} products from verified farmers across Zimbabwe. Buy and sell on Musika Wedu marketplace.`;
    }
    return 'Browse thousands of agricultural products from verified farmers across Zimbabwe. Crops, livestock, equipment, and more on Musika Wedu marketplace.';
};

const getPageKeywords = () => {
    const baseKeywords = 'agricultural products Zimbabwe, farmers Zimbabwe, crops Zimbabwe, livestock Zimbabwe, agricultural equipment Zimbabwe, Musika Wedu';
    
    if (props.filters.search) {
        return `${props.filters.search}, ${baseKeywords}`;
    }
    if (props.filters.category_id) {
        const category = props.categories?.find(c => c.id === props.filters.category_id);
        return `${category?.name || 'agricultural'} Zimbabwe, ${baseKeywords}`;
    }
    return baseKeywords;
};

const getCanonicalUrl = () => {
    const baseUrl = 'https://musikawedu.co.zw';
    if (props.filters.search) {
        return `${baseUrl}/products?search=${encodeURIComponent(props.filters.search)}`;
    }
    if (props.filters.category_id) {
        return `${baseUrl}/products?category_id=${props.filters.category_id}`;
    }
    return `${baseUrl}/products`;
};

const injectStructuredData = () => {
    const structuredData = {
        "@context": "https://schema.org",
        "@type": "ItemList",
        "name": getPageTitle(),
        "description": getPageDescription(),
        "url": getCanonicalUrl(),
        "numberOfItems": props.products?.total || 0,
        "itemListElement": props.products?.data?.slice(0, 10).map((product, index) => ({
            "@type": "ListItem",
            "position": index + 1,
            "item": {
                "@type": "Product",
                "name": product.name,
                "description": product.description,
                "image": product.photos?.[0]?.photo_url || '/images/placeholder.svg',
                "offers": {
                    "@type": "Offer",
                    "price": product.price,
                    "priceCurrency": product.currency || "USD",
                    "availability": product.status === 'available' ? "https://schema.org/InStock" : "https://schema.org/OutOfStock"
                },
                "seller": {
                    "@type": "Person",
                    "name": product.user?.name
                }
            }
        })) || []
    };
    
    // Remove existing structured data script
    const existingScript = document.querySelector('script[data-page-structured-data]');
    if (existingScript) {
        existingScript.remove();
    }
    
    // Create new structured data script
    const script = document.createElement('script');
    script.type = 'application/ld+json';
    script.setAttribute('data-page-structured-data', 'true');
    script.textContent = JSON.stringify(structuredData);
    document.head.appendChild(script);
};

// Enhanced search handlers
const handleSearch = (searchParams) => {
    isLoading.value = true;
    
    // Build URL with search parameters
    const url = new URL(window.location.href);
    Object.keys(searchParams).forEach(key => {
        if (searchParams[key]) {
            url.searchParams.set(key, searchParams[key]);
        } else {
            url.searchParams.delete(key);
        }
    });
    
    // Navigate to new URL
    window.location.href = url.toString();
};

const handleFilterChange = (filters) => {
    handleSearch(filters);
};

const clearFilters = () => {
    window.location.href = route('products.index');
};

const browseCategories = () => {
    window.location.href = route('products.index');
};

const trySuggestion = (suggestion) => {
    handleSearch({ q: suggestion });
};

// Inject structured data when component mounts
onMounted(() => {
    injectStructuredData();
});
</script>
