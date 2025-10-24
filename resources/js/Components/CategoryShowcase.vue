<template>
    <section class="py-12 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Browse by Category
                </h2>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <Link
                    v-for="category in categories"
                    :key="category.id"
                    :href="route('products.index', { category_id: category.id })"
                    class="bg-white dark:bg-gray-800 rounded-xl p-6 text-center hover:shadow-lg hover:scale-105 transition-all duration-300 cursor-pointer border-2 border-transparent hover:border-primary-500"
                >
                    <div class="text-5xl mb-3">
                        {{ getCategoryIcon(category.name) }}
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-1">
                        {{ category.name }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ category.products_count || 0 }} items
                    </p>
                </Link>
            </div>
        </div>
    </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    categories: {
        type: Array,
        required: true
    }
});

const getCategoryIcon = (name) => {
    const icons = {
        'Grains': '🌾',
        'Vegetables': '🥕',
        'Fruits': '🍎',
        'Legumes': '🫘',
        'Livestock': '🐄',
        'Dairy Products': '🥛',
        'Poultry Products': '🥚',
        'Farm Equipment': '🚜',
        'Seeds & Seedlings': '🌱',
        'Organic Products': '🌿',
        'Agricultural Services': '👨‍🌾'
    };
    return icons[name] || '📦';
};
</script>



