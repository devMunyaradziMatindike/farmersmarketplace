<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <Head>
            <title>Market Commodity Pricing - Musika Wedu</title>
            <meta name="description" content="Real-time agricultural commodity prices across Zimbabwe with comparisons by province, town, and city." />
            <link rel="canonical" href="https://musikawedu.co.zw/market-pricing" />
        </Head>

        <AppHeader :filters="{}" />

        <div class="max-w-7xl mx-auto px-3 sm:px-4 lg:px-8 py-6 sm:py-8">
            <!-- Hero / Header -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-2xl p-6 sm:p-8 mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold flex items-center gap-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Market Commodity Pricing
                        </h1>
                        <p class="text-white/90 mt-1">Real-time prices and comparisons across Zimbabwean markets</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 text-sm">
                        <span class="bg-white/15 rounded-full px-3 py-1">🔄 Updated: {{ lastUpdated }}</span>
                        <span class="bg-white/15 rounded-full px-3 py-1">📍 {{ meta.markets }} Markets</span>
                        <span class="bg-white/15 rounded-full px-3 py-1">🥬 {{ meta.commodities }} Commodities</span>
                    </div>
                </div>
            </div>

      <!-- Category Tabs -->
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-4 sm:p-6 mb-6 sm:mb-8">
        <div class="flex flex-wrap gap-2 mb-4">
          <button
            v-for="(label, key) in categories"
            :key="key"
            @click="setActiveCategory(key)"
            :class="[
              'px-4 py-2 rounded-lg font-medium transition-all touch-manipulation',
              activeCategory === key
                ? 'bg-green-600 text-white shadow-md'
                : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600'
            ]"
          >
            {{ label }}
          </button>
        </div>

        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Commodity</label>
            <select v-model="filters.commodity" @change="fetchPrices" class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900">
              <option value="">All</option>
              <option v-for="c in commodities" :key="c" :value="c">{{ c }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Province</label>
            <select v-model="filters.province" @change="fetchPrices" class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900">
              <option value="">All</option>
              <option v-for="p in provinces" :key="p" :value="p">{{ p }}</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Min Price (USD)</label>
            <input v-model.number="filters.min_price" @change="fetchPrices" type="number" min="0" step="0.01" class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Price (USD)</label>
            <input v-model.number="filters.max_price" @change="fetchPrices" type="number" min="0" step="0.01" class="w-full p-2.5 rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900" />
          </div>
        </div>
      </div>

            <!-- Highlights -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 sm:mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">🏆 Top Prices</h2>
                    <div class="space-y-3">
                        <div v-for="(row, idx) in topPrices" :key="idx" class="flex items-center justify-between p-3 rounded-xl bg-green-50 dark:bg-green-900/20">
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ row.commodity }}</div>
                                <div class="text-xs text-gray-500">{{ row.market }}, {{ row.province }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-xl font-bold text-green-700 dark:text-green-400">${{ row.price.toFixed(2) }}</div>
                                <div class="text-xs text-gray-500">{{ row.unit }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">💰 Best Deals</h2>
                    <div class="space-y-3">
                        <div v-for="(row, idx) in bestDeals" :key="idx" class="flex items-center justify-between p-3 rounded-xl bg-blue-50 dark:bg-blue-900/20">
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ row.commodity }}</div>
                                <div class="text-xs text-gray-500">{{ row.market }}, {{ row.province }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-xl font-bold text-blue-700 dark:text-blue-400">${{ row.price.toFixed(2) }}</div>
                                <div class="text-xs text-gray-500">{{ row.unit }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                        </svg>
                        {{ categories[activeCategory] }} Price Comparison
                    </h2>
                    <div class="text-sm text-gray-500">{{ filtered.length }} results</div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-600 dark:text-gray-300">Commodity</th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-600 dark:text-gray-300">Market</th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-600 dark:text-gray-300">Province</th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-600 dark:text-gray-300">Price (USD)</th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-600 dark:text-gray-300">Unit</th>
                                <th class="text-left px-6 py-3 text-xs font-medium text-gray-600 dark:text-gray-300">Trend</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="(row, idx) in filtered" :key="idx" class="hover:bg-gray-50 dark:hover:bg-gray-900/40">
                                <td class="px-6 py-3 font-medium text-gray-900 dark:text-white">{{ row.commodity }}</td>
                                <td class="px-6 py-3 text-gray-700 dark:text-gray-300">{{ row.market }}</td>
                                <td class="px-6 py-3 text-gray-700 dark:text-gray-300">{{ row.province }}</td>
                                <td class="px-6 py-3 text-gray-900 dark:text-white font-semibold">${{ row.price.toFixed(2) }}</td>
                                <td class="px-6 py-3 text-gray-700 dark:text-gray-300">{{ row.unit }}</td>
                                <td class="px-6 py-3">
                                    <span :class="trendClass(row.trend)" class="px-2 py-1 rounded-full text-xs font-medium">
                                        {{ trendIcon(row.trend) }} {{ row.change }}%
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="meta.note" class="px-6 py-4 text-sm text-amber-700 bg-amber-50 dark:bg-amber-900/20 dark:text-amber-300">
                    {{ meta.note }}
                </div>
            </div>
        </div>
    </div>
    
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppHeader from '@/Components/AppHeader.vue';

const prices = ref([]);
const loading = ref(false);
const lastUpdated = ref('');
const meta = ref({ markets: 0, commodities: 0 });

const activeCategory = ref('fruits_vegetables');
const categories = ref({
  'fruits_vegetables': 'Fruits & Vegetables',
  'livestock': 'Livestock',
  'chemicals': 'Agricultural Chemicals',
  'equipment': 'Agricultural Equipment',
  'classifieds': 'General Agriculture'
});

const filters = ref({ commodity: '', province: '', min_price: '', max_price: '' });

const commodities = computed(() => [...new Set(prices.value.map(p => p.commodity))]);
const provinces = computed(() => [...new Set(prices.value.map(p => p.province))]);

const filtered = computed(() => {
    return prices.value.filter(p => {
        const c = !filters.value.commodity || p.commodity === filters.value.commodity;
        const pr = !filters.value.province || p.province === filters.value.province;
        const min = !filters.value.min_price || p.price >= Number(filters.value.min_price);
        const max = !filters.value.max_price || p.price <= Number(filters.value.max_price);
        return c && pr && min && max;
    });
});

const topPrices = computed(() => [...filtered.value].sort((a, b) => b.price - a.price).slice(0, 5));
const bestDeals = computed(() => [...filtered.value].sort((a, b) => a.price - b.price).slice(0, 5));

const trendClass = (t) => t === 'up' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : (t === 'down' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300');
const trendIcon = (t) => t === 'up' ? '↗️' : (t === 'down' ? '↘️' : '→');

const setActiveCategory = (category) => {
  activeCategory.value = category;
  fetchPrices();
};

const fetchPrices = async () => {
  try {
    loading.value = true;
    const params = new URLSearchParams();
    params.set('category', activeCategory.value);
    Object.entries(filters.value).forEach(([k, v]) => { 
      if (v !== '' && v !== null && v !== undefined) params.set(k, v); 
    });
    
    const res = await fetch(`/api/v1/market-prices?${params.toString()}`);
    const json = await res.json();
    
    if (json?.success) {
      prices.value = json.data || [];
      lastUpdated.value = new Date(json.meta?.last_updated || Date.now()).toLocaleString();
      const marketsCount = new Set(prices.value.map(p => p.market)).size;
      const commoditiesCount = new Set(prices.value.map(p => p.commodity)).size;
      meta.value = {
        ...(json.meta || {}),
        markets: marketsCount,
        commodities: commoditiesCount,
      };
    }
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
    fetchPrices();
});
</script>


