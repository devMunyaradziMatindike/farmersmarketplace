<template>
  <div class="search-container relative">
    <!-- Search Input with Suggestions -->
    <div class="search-input-wrapper">
      <input 
        v-model="searchQuery" 
        @input="handleSearchInput"
        @focus="showSuggestions = true"
        @blur="hideSuggestions"
        @keydown.enter="performSearch"
        @keydown.escape="showSuggestions = false"
        placeholder="Search products, crops, or farmers..."
        class="w-full px-3 sm:px-4 py-2.5 sm:py-3 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
      />
      
      <!-- Search Suggestions Dropdown -->
      <div v-if="showSuggestions && (suggestions.length || recentSearches.length || popularSearches.length)" 
           class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg z-50 mt-1 max-h-80 overflow-y-auto">
        
        <!-- Recent Searches -->
        <div v-if="recentSearches.length" class="p-3 border-b">
          <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
            Recent Searches
          </h4>
          <div v-for="search in recentSearches" :key="search" 
               @click="selectSuggestion(search)"
               class="flex items-center py-2 px-2 hover:bg-gray-50 cursor-pointer rounded touch-manipulation">
            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-sm">{{ search }}</span>
          </div>
        </div>
        
        <!-- Popular Searches -->
        <div v-if="popularSearches.length" class="p-3 border-b">
          <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
            Popular Searches
          </h4>
          <div v-for="search in popularSearches" :key="search.term" 
               @click="selectSuggestion(search.term)"
               class="flex items-center justify-between py-2 px-2 hover:bg-gray-50 cursor-pointer rounded touch-manipulation">
            <span class="text-sm">{{ search.term }}</span>
            <span class="text-xs text-gray-400">{{ search.count }} searches</span>
          </div>
        </div>
        
        <!-- Live Suggestions -->
        <div v-if="suggestions.length" class="p-3">
          <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">
            Suggestions
          </h4>
          <div v-for="suggestion in suggestions" :key="suggestion" 
               @click="selectSuggestion(suggestion)"
               class="py-2 px-2 hover:bg-gray-50 cursor-pointer rounded touch-manipulation">
            <span class="text-sm">{{ suggestion }}</span>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Advanced Filters -->
    <div class="filters-section mt-4">
      <!-- Mobile: Stack vertically, Desktop: Horizontal -->
      <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
        <!-- Category Filter -->
        <select v-model="selectedCategory" @change="applyFilters" 
                class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
          <option value="">All Categories</option>
          <option v-for="category in categories" :key="category.id" :value="category.id">
            {{ category.name }}
          </option>
        </select>
        
        <!-- Price Range -->
        <div class="flex items-center gap-2">
          <input v-model="minPrice" @input="applyFilters" type="number" 
                 placeholder="Min" class="px-3 py-2 text-sm border border-gray-300 rounded-lg w-20 sm:w-24 focus:ring-2 focus:ring-primary-500">
          <span class="text-gray-500 text-sm">-</span>
          <input v-model="maxPrice" @input="applyFilters" type="number" 
                 placeholder="Max" class="px-3 py-2 text-sm border border-gray-300 rounded-lg w-20 sm:w-24 focus:ring-2 focus:ring-primary-500">
        </div>
        
        <!-- Location Filter -->
        <select v-model="selectedLocation" @change="applyFilters"
                class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
          <option value="">All Locations</option>
          <option v-for="location in locations" :key="location" :value="location">
            {{ location }}
          </option>
        </select>
        
        <!-- Sort Options -->
        <select v-model="sortBy" @change="applyFilters"
                class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500">
          <option value="relevance">Most Relevant</option>
          <option value="price_low">Price: Low to High</option>
          <option value="price_high">Price: High to Low</option>
          <option value="newest">Newest First</option>
          <option value="popular">Most Popular</option>
        </select>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
  categories: {
    type: Array,
    default: () => []
  },
  locations: {
    type: Array,
    default: () => []
  },
  initialQuery: {
    type: String,
    default: ''
  },
  initialFilters: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['search', 'filter-change']);

const searchQuery = ref(props.initialQuery);
const showSuggestions = ref(false);
const suggestions = ref([]);
const recentSearches = ref([]);
const popularSearches = ref([]);

// Filter states
const selectedCategory = ref(props.initialFilters.category || '');
const minPrice = ref(props.initialFilters.min_price || '');
const maxPrice = ref(props.initialFilters.max_price || '');
const selectedLocation = ref(props.initialFilters.location || '');
const sortBy = ref(props.initialFilters.sort || 'relevance');

// Load initial data
onMounted(() => {
  loadRecentSearches();
  loadPopularSearches();
});

// Search input handler with debouncing
let searchTimeout;
const handleSearchInput = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    if (searchQuery.value.length > 2) {
      getLiveSuggestions();
    } else {
      suggestions.value = [];
    }
  }, 300);
};

// Get live suggestions from API
const getLiveSuggestions = async () => {
  try {
    const response = await fetch(`/api/v1/search/suggestions?q=${encodeURIComponent(searchQuery.value)}`);
    const data = await response.json();
    suggestions.value = data.suggestions || [];
  } catch (error) {
    console.error('Error fetching suggestions:', error);
    suggestions.value = [];
  }
};

// Select suggestion
const selectSuggestion = (suggestion) => {
  searchQuery.value = suggestion;
  showSuggestions.value = false;
  saveToRecentSearches(suggestion);
  performSearch();
};

// Perform search
const performSearch = () => {
  const searchParams = {
    q: searchQuery.value,
    category: selectedCategory.value,
    min_price: minPrice.value,
    max_price: maxPrice.value,
    location: selectedLocation.value,
    sort: sortBy.value
  };
  
  emit('search', searchParams);
};

// Apply filters
const applyFilters = () => {
  performSearch();
};

// Save to recent searches
const saveToRecentSearches = (search) => {
  if (!search.trim()) return;
  
  const recent = recentSearches.value.filter(s => s !== search);
  recent.unshift(search);
  recentSearches.value = recent.slice(0, 5); // Keep only 5 recent searches
  
  // Save to localStorage
  localStorage.setItem('recentSearches', JSON.stringify(recentSearches.value));
};

// Load recent searches from localStorage
const loadRecentSearches = () => {
  try {
    const saved = localStorage.getItem('recentSearches');
    if (saved) {
      recentSearches.value = JSON.parse(saved);
    }
  } catch (error) {
    console.error('Error loading recent searches:', error);
  }
};

// Load popular searches from API
const loadPopularSearches = async () => {
  try {
    const response = await fetch('/api/v1/search/popular');
    const data = await response.json();
    popularSearches.value = data.popular || [];
  } catch (error) {
    console.error('Error loading popular searches:', error);
  }
};

// Hide suggestions with delay
const hideSuggestions = () => {
  setTimeout(() => {
    showSuggestions.value = false;
  }, 200);
};

// Watch for external changes
watch(() => props.initialQuery, (newQuery) => {
  searchQuery.value = newQuery;
});

watch(() => props.initialFilters, (newFilters) => {
  selectedCategory.value = newFilters.category || '';
  minPrice.value = newFilters.min_price || '';
  maxPrice.value = newFilters.max_price || '';
  selectedLocation.value = newFilters.location || '';
  sortBy.value = newFilters.sort || 'relevance';
}, { deep: true });
</script>

<style scoped>
.search-container {
  position: relative;
}

.search-input-wrapper {
  position: relative;
}

.filters-section {
  transition: all 0.3s ease;
}

/* Custom scrollbar for suggestions */
.max-h-80::-webkit-scrollbar {
  width: 6px;
}

.max-h-80::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.max-h-80::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.max-h-80::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>
