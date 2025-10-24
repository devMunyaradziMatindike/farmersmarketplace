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
                <option value="latest">Latest First</option>
                <option value="cheapest">Lowest Price</option>
                <option value="nearest">Nearest to Me</option>
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

        <!-- Status Filter -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Availability
            </label>
            <div class="space-y-2">
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        value="available"
                        v-model="localFilters.status"
                        @change="applyFilters"
                        class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                    />
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Available Now</span>
                </label>
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        value="soon_to_be_available"
                        v-model="localFilters.status"
                        @change="applyFilters"
                        class="rounded border-gray-300 text-primary-600 focus:ring-primary-500"
                    />
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Coming Soon</span>
                </label>
            </div>
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
    sort: props.filters.sort || 'latest',
    min_price: props.filters.min_price || null,
    max_price: props.filters.max_price || null,
    location: props.filters.location || '',
    status: props.filters.status || []
});

const hasActiveFilters = computed(() => {
    return localFilters.min_price || localFilters.max_price || localFilters.location || localFilters.status.length > 0;
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
    localFilters.sort = 'latest';
    localFilters.min_price = null;
    localFilters.max_price = null;
    localFilters.location = '';
    localFilters.status = [];
    applyFilters();
};
</script>



