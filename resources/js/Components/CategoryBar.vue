<template>
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-1 overflow-x-auto py-3 scrollbar-hide">
                <!-- All Categories -->
                <button
                    @click="selectCategory(null)"
                    :class="[
                        'px-4 py-2 rounded-lg whitespace-nowrap text-sm font-medium transition-all',
                        !selectedCategory
                            ? 'bg-primary-600 text-white'
                            : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                    ]"
                >
                    All Categories
                </button>

                <!-- Category Buttons -->
                <button
                    v-for="category in categories"
                    :key="category.id"
                    @click="selectCategory(category.id)"
                    :class="[
                        'px-4 py-2 rounded-lg whitespace-nowrap text-sm font-medium transition-all flex items-center gap-2',
                        selectedCategory === category.id
                            ? 'bg-primary-600 text-white'
                            : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                    ]"
                >
                    <span>{{ getCategoryIcon(category.name) }}</span>
                    <span>{{ category.name }}</span>
                </button>
            </div>
        </div>
    </nav>
</template>

<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({
    categories: {
        type: Array,
        required: true
    },
    selectedCategory: {
        type: [Number, String],
        default: null
    }
});

const selectCategory = (categoryId) => {
    router.get(route('products.index'), 
        { category_id: categoryId },
        { 
            preserveState: true,
            preserveScroll: false,
            replace: true
        }
    );
};

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

<style scoped>
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
</style>

