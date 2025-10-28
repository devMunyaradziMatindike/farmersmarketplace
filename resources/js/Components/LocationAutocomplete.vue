<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: 'e.g., Harare Central, Bulawayo, Gweru...'
    },
});

const emit = defineEmits(['update:modelValue', 'location-selected']);

const inputValue = ref(props.modelValue || '');
const suggestions = ref([]);
const showSuggestions = ref(false);
let searchTimeout = null;

// Watch for external changes to modelValue
watch(() => props.modelValue, (newValue) => {
    inputValue.value = newValue || '';
});

const searchLocations = async (query) => {
    if (!query || query.length < 2) {
        return [];
    }

    try {
        // OpenStreetMap Nominatim API (public, no key required)
        const url = `https://nominatim.openstreetmap.org/search?format=json&addressdetails=1&countrycodes=zw&limit=5&q=${encodeURIComponent(query)}`;
        const response = await fetch(url, {
            headers: {
                'Accept': 'application/json'
            }
        });
        const data = await response.json();
        return (data || []).map(item => ({
            display_name: item.display_name,
            lat: parseFloat(item.lat),
            lng: parseFloat(item.lon),
            type: item.type || 'location'
        }));
    } catch (error) {
        console.error('Location search error:', error);
        return [];
    }
};

const onInput = () => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(async () => {
        suggestions.value = await searchLocations(inputValue.value);
        showSuggestions.value = suggestions.value.length > 0;
        // Always emit the current input value
        emit('update:modelValue', inputValue.value);
    }, 250);
};

const selectSuggestion = (s) => {
    inputValue.value = s.display_name;
    emit('update:modelValue', s.display_name);
    emit('location-selected', { location: s.display_name, latitude: s.lat, longitude: s.lng });
    showSuggestions.value = false;
};

const onBlur = () => {
    // Delay hiding to allow click selection
    setTimeout(() => (showSuggestions.value = false), 150);
};

watch(() => props.modelValue, (v) => { inputValue.value = v || ''; });
</script>

<template>
    <div class="relative">
        <input
            :value="inputValue"
            @input="(e) => { inputValue = e.target.value; onInput(); }"
            @focus="() => { if (suggestions.length) showSuggestions = true; }"
            @blur="onBlur"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
            :placeholder="placeholder"
        />

        <div
            v-if="showSuggestions && suggestions.length"
            class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg max-h-60 overflow-auto"
        >
            <button
                v-for="(s, idx) in suggestions"
                :key="idx"
                type="button"
                @click="selectSuggestion(s)"
                class="w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 border-b last:border-b-0 border-gray-100 dark:border-gray-700"
            >
                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ s.display_name }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 capitalize">{{ s.type }}</div>
            </button>
        </div>
    </div>
</template>



