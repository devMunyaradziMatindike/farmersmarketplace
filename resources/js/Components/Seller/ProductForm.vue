<script setup>
import { ref, computed } from 'vue';
import PhotoUpload from './PhotoUpload.vue';

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    categories: {
        type: Array,
        required: true
    },
    product: {
        type: Object,
        default: null
    },
    isEditing: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['submit', 'remove-existing-photo', 'set-primary-existing-photo']);

const photoFiles = ref([]);
const existingPhotos = ref(props.product?.photos || []);

const handlePhotoUpdate = (photos) => {
    photoFiles.value = photos;
    props.form.photos = photos;
};

const handleRemoveExistingPhoto = (photoId) => {
    existingPhotos.value = existingPhotos.value.filter(photo => photo.id !== photoId);
    emit('remove-existing-photo', photoId);
};

const handleSetPrimaryExistingPhoto = (photoId) => {
    // Update existing photos to set primary
    existingPhotos.value = existingPhotos.value.map(photo => ({
        ...photo,
        is_primary: photo.id === photoId
    }));
    emit('set-primary-existing-photo', photoId);
};

const getCurrentLocation = () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                props.form.latitude = position.coords.latitude;
                props.form.longitude = position.coords.longitude;
            },
            (error) => {
                console.error('Error getting location:', error);
                alert('Unable to get your location. Please enter it manually.');
            }
        );
    } else {
        alert('Geolocation is not supported by this browser.');
    }
};

const formatPrice = (value) => {
    // Remove any non-numeric characters except decimal point
    const numericValue = value.replace(/[^0-9.]/g, '');
    return numericValue;
};

const submitForm = () => {
    emit('submit');
};
</script>

<template>
    <form @submit.prevent="submitForm" class="space-y-8">
        <!-- Basic Information -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Basic Information
                </h3>
            </div>
            <div class="p-6 space-y-6">
                <!-- Product Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Product Name *
                    </label>
                    <input
                        v-model="form.name"
                        type="text"
                        required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                        placeholder="e.g., Fresh Organic Maize"
                    />
                    <div v-if="form.errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">
                        {{ form.errors.name }}
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Category *
                    </label>
                    <select
                        v-model="form.category_id"
                        required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                    >
                        <option value="">Select a category</option>
                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                    <div v-if="form.errors.category_id" class="mt-1 text-sm text-red-600 dark:text-red-400">
                        {{ form.errors.category_id }}
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Description *
                    </label>
                    <textarea
                        v-model="form.description"
                        required
                        rows="4"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                        placeholder="Describe your product in detail..."
                    ></textarea>
                    <div v-if="form.errors.description" class="mt-1 text-sm text-red-600 dark:text-red-400">
                        {{ form.errors.description }}
                    </div>
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Price (USD) *
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 dark:text-gray-400 sm:text-sm">$</span>
                        </div>
                        <input
                            v-model="form.price"
                            type="text"
                            required
                            @input="form.price = formatPrice($event.target.value)"
                            class="w-full pl-7 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                            placeholder="0.00"
                        />
                    </div>
                    <div v-if="form.errors.price" class="mt-1 text-sm text-red-600 dark:text-red-400">
                        {{ form.errors.price }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Location Information -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Location Information
                </h3>
            </div>
            <div class="p-6 space-y-6">
                <!-- Location -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Location *
                    </label>
                    <input
                        v-model="form.location"
                        type="text"
                        required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                        placeholder="e.g., Harare, Zimbabwe"
                    />
                    <div v-if="form.errors.location" class="mt-1 text-sm text-red-600 dark:text-red-400">
                        {{ form.errors.location }}
                    </div>
                </div>

                <!-- GPS Coordinates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Latitude
                        </label>
                        <input
                            v-model="form.latitude"
                            type="number"
                            step="any"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                            placeholder="e.g., -17.8252"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Longitude
                        </label>
                        <input
                            v-model="form.longitude"
                            type="number"
                            step="any"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                            placeholder="e.g., 31.0335"
                        />
                    </div>
                </div>

                <!-- Get Current Location Button -->
                <div>
                    <button
                        type="button"
                        @click="getCurrentLocation"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors"
                    >
                        <span class="mr-2">📍</span>
                        Use Current Location
                    </button>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        GPS coordinates help buyers find your products when searching by location.
                    </p>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Contact Information
                </h3>
            </div>
            <div class="p-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Contact Details *
                    </label>
                    <input
                        v-model="form.contact_details"
                        type="text"
                        required
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                        placeholder="e.g., +263 77 123 4567 or WhatsApp link"
                    />
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        This will be displayed to buyers who want to contact you about this product.
                    </p>
                    <div v-if="form.errors.contact_details" class="mt-1 text-sm text-red-600 dark:text-red-400">
                        {{ form.errors.contact_details }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Photos -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Product Photos
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Upload up to 10 photos. The first photo will be the primary image.
                </p>
            </div>
            <div class="p-6">
                <PhotoUpload
                    v-model="photoFiles"
                    :existing-photos="existingPhotos"
                    :max-photos="10"
                    :max-size="5 * 1024 * 1024"
                    @remove-existing-photo="handleRemoveExistingPhoto"
                    @set-primary-existing-photo="handleSetPrimaryExistingPhoto"
                />
            </div>
        </div>

        <!-- Status -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    Product Status
                </h3>
            </div>
            <div class="p-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status
                    </label>
                    <select
                        v-model="form.status"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                    >
                        <option value="available">Available</option>
                        <option value="soon_to_be_available">Soon to be Available</option>
                        <option value="sold_out">Sold Out</option>
                        <option value="delisted">Delisted</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-4">
            <button
                type="submit"
                :disabled="form.processing"
                class="px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
            >
                <span v-if="form.processing">
                    {{ isEditing ? 'Updating...' : 'Creating...' }}
                </span>
                <span v-else>
                    {{ isEditing ? 'Update Product' : 'Create Product' }}
                </span>
            </button>
        </div>
    </form>
</template>




