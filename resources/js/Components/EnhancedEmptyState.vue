<template>
  <div class="empty-state-container text-center py-16">
    <!-- Different empty states based on context -->
    <div v-if="searchQuery && !isLoading">
      <!-- No Results Found -->
      <div class="empty-icon mb-6">
        <svg class="w-24 h-24 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
      </div>
      
      <h3 class="text-xl font-semibold text-gray-900 mb-2">
        No products found for "{{ searchQuery }}"
      </h3>
      
      <p class="text-gray-600 mb-6 max-w-md mx-auto">
        Try adjusting your search terms or filters to find what you're looking for.
      </p>
      
      <!-- Search Suggestions -->
      <div class="search-suggestions mb-6">
        <h4 class="text-sm font-medium text-gray-700 mb-3">Try searching for:</h4>
        <div class="flex flex-wrap justify-center gap-2">
          <button v-for="suggestion in searchSuggestions" :key="suggestion"
                  @click="trySuggestion(suggestion)"
                  class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm hover:bg-gray-200 transition">
            {{ suggestion }}
          </button>
        </div>
      </div>
      
      <!-- Alternative Actions -->
      <div class="alternative-actions">
        <button @click="clearFilters" 
                class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition mr-3">
          Clear Filters
        </button>
        <button @click="browseCategories" 
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
          Browse Categories
        </button>
      </div>
    </div>
    
    <div v-else-if="!searchQuery && !isLoading && !hasFilters">
      <!-- No Products at All -->
      <div class="empty-icon mb-6">
        <svg class="w-24 h-24 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
        </svg>
      </div>
      
      <h3 class="text-xl font-semibold text-gray-900 mb-2">
        No products available yet
      </h3>
      
      <p class="text-gray-600 mb-6 max-w-md mx-auto">
        Be the first to list your agricultural products and connect with buyers.
      </p>
      
      <Link :href="route('register')" 
            class="inline-flex items-center px-6 py-3 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Start Selling
      </Link>
    </div>
    
    <div v-else-if="hasFilters && !isLoading">
      <!-- No Results with Filters -->
      <div class="empty-icon mb-6">
        <svg class="w-24 h-24 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
        </svg>
      </div>
      
      <h3 class="text-xl font-semibold text-gray-900 mb-2">
        No products match your filters
      </h3>
      
      <p class="text-gray-600 mb-6 max-w-md mx-auto">
        Try adjusting your filters or search terms to find more products.
      </p>
      
      <!-- Filter Summary -->
      <div class="filter-summary mb-6">
        <h4 class="text-sm font-medium text-gray-700 mb-3">Current filters:</h4>
        <div class="flex flex-wrap justify-center gap-2">
          <span v-if="activeFilters.category" 
                class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
            Category: {{ activeFilters.category }}
          </span>
          <span v-if="activeFilters.min_price || activeFilters.max_price" 
                class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
            Price: {{ activeFilters.min_price || '0' }} - {{ activeFilters.max_price || '∞' }}
          </span>
          <span v-if="activeFilters.location" 
                class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-sm">
            Location: {{ activeFilters.location }}
          </span>
        </div>
      </div>
      
      <!-- Alternative Actions -->
      <div class="alternative-actions">
        <button @click="clearFilters" 
                class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition mr-3">
          Clear All Filters
        </button>
        <button @click="browseCategories" 
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
          Browse All Products
        </button>
      </div>
    </div>
    
    <div v-else-if="isLoading">
      <!-- Loading State -->
      <div class="loading-spinner mb-6">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600 mx-auto"></div>
      </div>
      <p class="text-gray-600">Searching for products...</p>
    </div>
    
    <!-- Market Insights Section -->
    <div v-if="showMarketInsights" class="mt-12 bg-gradient-to-br from-blue-50 to-indigo-100 rounded-lg p-6">
      <h4 class="text-lg font-semibold text-gray-900 mb-4">💡 Market Insights</h4>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div class="text-left">
          <p class="text-gray-700 mb-2">
            <strong>Popular this week:</strong> {{ marketInsights.popularCrop }}
          </p>
          <p class="text-gray-600">
            High demand for {{ marketInsights.popularCrop }} in your area
          </p>
        </div>
        <div class="text-left">
          <p class="text-gray-700 mb-2">
            <strong>Price trend:</strong> {{ marketInsights.priceTrend }}
          </p>
          <p class="text-gray-600">
            {{ marketInsights.priceTrend === 'Rising' ? 'Consider listing' : 'Good time to buy' }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  searchQuery: {
    type: String,
    default: ''
  },
  isLoading: {
    type: Boolean,
    default: false
  },
  activeFilters: {
    type: Object,
    default: () => ({})
  },
  showMarketInsights: {
    type: Boolean,
    default: false
  },
  marketInsights: {
    type: Object,
    default: () => ({
      popularCrop: 'Maize',
      priceTrend: 'Rising'
    })
  }
});

const emit = defineEmits(['clearFilters', 'browseCategories', 'trySuggestion']);

const searchSuggestions = ref([
  'Maize',
  'Wheat',
  'Tomatoes',
  'Cattle',
  'Chickens',
  'Fertilizer',
  'Seeds',
  'Equipment'
]);

const hasFilters = computed(() => {
  return Object.values(props.activeFilters).some(value => 
    value !== '' && value !== null && value !== undefined
  );
});

const trySuggestion = (suggestion) => {
  emit('trySuggestion', suggestion);
};

const clearFilters = () => {
  emit('clearFilters');
};

const browseCategories = () => {
  emit('browseCategories');
};
</script>

<style scoped>
.empty-state-container {
  min-height: 400px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}

.empty-icon {
  opacity: 0.6;
}

.search-suggestions button {
  transition: all 0.2s ease;
}

.search-suggestions button:hover {
  transform: translateY(-1px);
}

.alternative-actions button {
  transition: all 0.2s ease;
}

.alternative-actions button:hover {
  transform: translateY(-1px);
}

.filter-summary span {
  transition: all 0.2s ease;
}

.loading-spinner {
  animation: pulse 2s infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}
</style>
