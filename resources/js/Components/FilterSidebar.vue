<template>
    <aside class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
            Filters
        </h3>

        <!-- Sort By -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Sort By
            </label>
            <select
                v-model="localFilters.sort"
                @change="applyFilters"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500"
            >
                <option value="relevance">Most Relevant</option>
                <option value="price_low">Price: Low to High</option>
                <option value="price_high">Price: High to Low</option>
                <option value="newest">Newest First</option>
                <option value="popular">Most Popular</option>
            </select>
        </div>

        <!-- Price Range -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Price Range
            </label>
            <div class="space-y-2">
                <input
                    v-model.number="localFilters.min_price"
                    type="number"
                    placeholder="Min price"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500"
                    @change="applyFilters"
                />
                <input
                    v-model.number="localFilters.max_price"
                    type="number"
                    placeholder="Max price"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500"
                    @change="applyFilters"
                />
            </div>
        </div>

        <!-- Location Filter -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Location
            </label>
            <input
                v-model="localFilters.location"
                type="text"
                placeholder="Enter location..."
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500"
                @input="debouncedApplyFilters"
            />
        </div>

        <!-- Quantity Range -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Quantity Available
            </label>
            <div class="space-y-2">
                <input
                    v-model.number="localFilters.min_quantity"
                    type="number"
                    step="0.01"
                    min="0"
                    placeholder="Min quantity"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500"
                    @change="applyFilters"
                />
                <input
                    v-model.number="localFilters.max_quantity"
                    type="number"
                    step="0.01"
                    min="0"
                    placeholder="Max quantity"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500"
                    @change="applyFilters"
                />
            </div>
        </div>

        <!-- Unit Filter -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Unit
            </label>
            <select
                v-model="localFilters.unit"
                @change="applyFilters"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500"
            >
                <option value="">All Units</option>
                <option value="kg">Kilograms (kg)</option>
                <option value="tonnes">Tonnes</option>
                <option value="bags">Bags</option>
                <option value="crates">Crates</option>
                <option value="heads">Heads</option>
                <option value="liters">Liters</option>
                <option value="pieces">Pieces</option>
            </select>
        </div>

        <!-- Minimum Order Quantity -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Maximum MOQ (Minimum Order Quantity)
            </label>
            <input
                v-model.number="localFilters.min_order_quantity"
                type="number"
                step="0.01"
                min="0"
                placeholder="e.g., 100"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500"
                @change="applyFilters"
            />
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Show products with MOQ ≤ this value
            </p>
        </div>

        <!-- Packaging Type -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Packaging Type
            </label>
            <select
                v-model="localFilters.packaging_type"
                @change="applyFilters"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500"
            >
                <option value="">All Types</option>
                <option value="bags">Bags</option>
                <option value="crates">Crates</option>
                <option value="bulk">Bulk</option>
                <option value="packaged">Packaged</option>
                <option value="loose">Loose</option>
            </select>
        </div>

        <!-- Agricultural Options -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Special Options
            </label>
            <div class="space-y-2">
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        v-model="localFilters.is_bulk_available"
                        @change="applyFilters"
                        class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                    />
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Bulk Available</span>
                </label>
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        v-model="localFilters.wholesale_only"
                        @change="applyFilters"
                        class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                    />
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Wholesale Only</span>
                </label>
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        v-model="localFilters.is_perishable"
                        @change="applyFilters"
                        class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                    />
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Perishable Items</span>
                </label>
            </div>
        </div>

        <!-- Season Filter -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Season
            </label>
            <select
                v-model="localFilters.season"
                @change="applyFilters"
                class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-primary-500 focus:ring-primary-500"
            >
                <option value="">All Seasons</option>
                <option value="rainy">Rainy Season</option>
                <option value="dry">Dry Season</option>
                <option value="all_year">All Year</option>
            </select>
        </div>

        <!-- Clear Filters -->
        <button
            v-if="hasActiveFilters"
            @click="clearFilters"
            class="w-full py-2 px-4 text-sm font-medium text-primary-600 dark:text-primary-400 hover:bg-primary-50 dark:hover:bg-gray-700 rounded-lg transition"
        >
            Clear All Filters
        </button>
    </aside>
</template>

<script setup>
import { reactive, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';

const props = defineProps({
    filters: {
        type: Object,
        default: () => ({})
    }
});

const localFilters = reactive({
    sort: props.filters.sort || 'relevance',
    min_price: props.filters.min_price || null,
    max_price: props.filters.max_price || null,
    location: props.filters.location || '',
    status: props.filters.status || [],
    // Agricultural filters
    min_quantity: props.filters.min_quantity || null,
    max_quantity: props.filters.max_quantity || null,
    unit: props.filters.unit || '',
    min_order_quantity: props.filters.min_order_quantity || null,
    packaging_type: props.filters.packaging_type || '',
    is_bulk_available: props.filters.is_bulk_available || false,
    wholesale_only: props.filters.wholesale_only || false,
    is_perishable: props.filters.is_perishable || false,
    season: props.filters.season || '',
});

const hasActiveFilters = computed(() => {
    return localFilters.min_price || localFilters.max_price || localFilters.location || 
           localFilters.status.length > 0 || localFilters.min_quantity || localFilters.max_quantity ||
           localFilters.unit || localFilters.min_order_quantity || localFilters.packaging_type ||
           localFilters.is_bulk_available || localFilters.wholesale_only || 
           localFilters.is_perishable || localFilters.season;
});

const applyFilters = () => {
    router.get(route('products.index'), localFilters, {
        preserveState: true,
        preserveScroll: true,
        replace: true
    });
};

const debouncedApplyFilters = debounce(() => {
    applyFilters();
}, 500);

const clearFilters = () => {
    localFilters.sort = 'relevance';
    localFilters.min_price = null;
    localFilters.max_price = null;
    localFilters.location = '';
    localFilters.status = [];
    // Clear agricultural filters
    localFilters.min_quantity = null;
    localFilters.max_quantity = null;
    localFilters.unit = '';
    localFilters.min_order_quantity = null;
    localFilters.packaging_type = '';
    localFilters.is_bulk_available = false;
    localFilters.wholesale_only = false;
    localFilters.is_perishable = false;
    localFilters.season = '';
    applyFilters();
};
</script>



