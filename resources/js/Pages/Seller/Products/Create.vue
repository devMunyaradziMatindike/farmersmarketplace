<script setup>
import SellerLayout from '@/Layouts/SellerLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, computed, onMounted, watch } from 'vue';
import LocationAutocomplete from '@/Components/LocationAutocomplete.vue';

const props = defineProps({
    categories: {
        type: Array,
        required: true
    }
});

const form = useForm({
    category_id: '',
    name: '',
    description: '',
    price: '',
    currency: 'USD',
    location: '',
    latitude: '',
    longitude: '',
    contact_details: '',
    status: 'available',
    photos: []
});

const photoFiles = ref([]);
const photoPreviews = ref([]);
const locationInput = ref(null);

const handlePhotoUpload = (event) => {
    const files = Array.from(event.target.files);
    const maxFiles = 10;
    const maxSizePerFile = 10 * 1024 * 1024; // 10MB per file
    const maxTotalSize = 50 * 1024 * 1024; // 50MB total
    
    // Check total file count
    if (photoFiles.value.length + files.length > maxFiles) {
        alert(`Maximum of ${maxFiles} photos allowed`);
        return;
    }

    // Check individual file sizes and total size
    let totalSize = photoFiles.value.reduce((sum, file) => sum + file.size, 0);
    const validFiles = [];
    
    files.forEach(file => {
        if (file.size > maxSizePerFile) {
            alert(`File "${file.name}" is too large. Maximum size is 10MB per file.`);
            return;
        }
        
        totalSize += file.size;
        if (totalSize > maxTotalSize) {
            alert(`Total upload size would be too large. Please reduce the number or size of images.`);
            return;
        }
        
        validFiles.push(file);
    });

    // Process valid files
    validFiles.forEach(file => {
        photoFiles.value.push(file);
        
        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            photoPreviews.value.push({
                file: file,
                url: e.target.result
            });
        };
        reader.readAsDataURL(file);
    });
};

const removePhoto = (index) => {
    photoFiles.value.splice(index, 1);
    photoPreviews.value.splice(index, 1);
};

const setPrimaryPhoto = (index) => {
    // Move the selected photo to the front
    const photo = photoFiles.value.splice(index, 1)[0];
    const preview = photoPreviews.value.splice(index, 1)[0];
    
    photoFiles.value.unshift(photo);
    photoPreviews.value.unshift(preview);
};

const getCurrentLocation = () => {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                form.latitude = position.coords.latitude;
                form.longitude = position.coords.longitude;
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

const submitForm = () => {
    // Debug: Log form data before submission
    console.log('=== FORM SUBMISSION DEBUG ===');
    console.log('Form object:', form);
    console.log('Form data being submitted:', {
        category_id: form.category_id,
        name: form.name,
        description: form.description,
        price: form.price,
        currency: form.currency,
        location: form.location,
        latitude: form.latitude,
        longitude: form.longitude,
        contact_details: form.contact_details,
        status: form.status,
        photos: photoFiles.value.length
    });
    
    // Check for required fields before submission
    const requiredFields = {
        category_id: form.category_id,
        name: form.name,
        description: form.description,
        price: form.price,
        currency: form.currency,
        location: form.location,
        contact_details: form.contact_details,
        status: form.status
    };
    
    console.log('Required fields check:', requiredFields);
    
    const missingFields = Object.entries(requiredFields)
        .filter(([key, value]) => {
            const isEmpty = !value || (typeof value === 'string' && value.trim() === '');
            if (isEmpty) {
                console.log(`Field '${key}' is empty or invalid:`, value);
            }
            return isEmpty;
        })
        .map(([key]) => key);
    
    if (missingFields.length > 0) {
        console.error('Missing required fields:', missingFields);
        alert(`Please fill in all required fields: ${missingFields.join(', ')}`);
        return;
    }
    
    console.log('All required fields are filled, proceeding with submission...');
    
    // Add photos to form data
    form.photos = photoFiles.value;
    
    // Try a different approach - use router.post directly
    router.post(route('seller.products.store'), {
        category_id: form.category_id,
        name: form.name,
        description: form.description,
        price: form.price,
        currency: form.currency,
        location: form.location,
        latitude: form.latitude,
        longitude: form.longitude,
        contact_details: form.contact_details,
        status: form.status,
        photos: photoFiles.value
    }, {
        forceFormData: true,
        onSuccess: () => {
            console.log('Product created successfully!');
            // Reset form
            form.reset();
            photoFiles.value = [];
            photoPreviews.value = [];
        },
        onError: (errors) => {
            console.log('Validation errors:', errors);
            console.log('Form errors object:', form.errors);
        }
    });
};

const formatPrice = (value) => {
    // Remove any non-numeric characters except decimal point
    const numericValue = value.replace(/[^0-9.]/g, '');
    return numericValue;
};

// Initialize Google Places Autocomplete
const handleLocationSelected = (locationData) => {
    form.location = locationData.location;
    form.latitude = locationData.latitude;
    form.longitude = locationData.longitude;
    console.log('Location selected:', locationData);
};

// Debug function to check form state
const debugForm = () => {
    console.log('=== FORM DEBUG ===');
    console.log('Form object:', form);
    console.log('Form data:', form.data());
    console.log('Form errors:', form.errors);
    console.log('Form processing:', form.processing);
    console.log('Form has errors:', form.hasErrors);
};

// Watch form data changes for debugging
watch(() => form.data(), (newData) => {
    console.log('Form data changed:', newData);
}, { deep: true });

onMounted(() => {
    // Using OpenStreetMap autocomplete component, no Google initialization needed
    console.log('Create component mounted, initial form data:', form.data());
});
</script>

<template>
    <Head title="Add Product" />

    <SellerLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    Add New Product
                </h2>
                <Link
                    :href="route('seller.products.index')"
                    class="inline-flex items-center px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                >
                    ← Back to Products
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
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
                                <!-- Currency Selection -->
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Currency *
                                    </label>
                                    <div class="flex items-center space-x-4">
                                        <label class="flex items-center cursor-pointer">
                                            <input
                                                v-model="form.currency"
                                                type="radio"
                                                value="USD"
                                                name="currency"
                                                class="mr-2 text-primary-600 focus:ring-primary-500"
                                            />
                                            <span class="text-gray-700 dark:text-gray-300">💵 USD ($)</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input
                                                v-model="form.currency"
                                                type="radio"
                                                value="ZWG"
                                                name="currency"
                                                class="mr-2 text-primary-600 focus:ring-primary-500"
                                            />
                                            <span class="text-gray-700 dark:text-gray-300">🇿🇼 ZWG (ZWG$)</span>
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Choose the currency for your product price
                                    </p>
                                </div>

                                <!-- Price Input -->
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">{{ form.currency === 'USD' ? '$' : 'ZWG$' }}</span>
                                    </div>
                                    <input
                                        v-model="form.price"
                                        type="text"
                                        required
                                        @input="form.price = formatPrice($event.target.value)"
                                        class="w-full pl-12 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="0.00"
                                    />
                                </div>
                                <div v-if="form.errors.price" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.price }}
                                </div>
                                <div v-if="form.errors.currency" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.currency }}
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
                            <!-- Location with Autocomplete -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Location * 
                                    <span class="text-xs text-gray-500 dark:text-gray-400 font-normal">(Start typing to see suggestions)</span>
                                </label>
                                <LocationAutocomplete
                                    :model-value="form.location"
                                    @update:model-value="(value) => form.location = value"
                                    @location-selected="handleLocationSelected"
                                />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    📍 Type your location and select from the dropdown suggestions
                                </p>
                                <div v-if="form.errors.location" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                    {{ form.errors.location }}
                                </div>
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
                            <!-- Photo Upload Area -->
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:border-primary-400 dark:hover:border-primary-500 transition-colors">
                                <input
                                    type="file"
                                    multiple
                                    accept="image/*"
                                    @change="handlePhotoUpload"
                                    class="hidden"
                                    id="photo-upload"
                                />
                                <label
                                    for="photo-upload"
                                    class="cursor-pointer"
                                >
                                    <div class="mx-auto w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mb-4">
                                        <span class="text-primary-600 dark:text-primary-400 text-2xl">📷</span>
                                    </div>
                                    <p class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                                        Upload Photos
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Click to select photos or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                        PNG, JPG, GIF up to 5MB each
                                    </p>
                                </label>
                            </div>

                            <!-- Photo Previews -->
                            <div v-if="photoPreviews.length > 0" class="mt-6">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">
                                    Uploaded Photos ({{ photoPreviews.length }}/10)
                                </h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div
                                        v-for="(preview, index) in photoPreviews"
                                        :key="index"
                                        class="relative group"
                                    >
                                        <img
                                            :src="preview.url"
                                            :alt="`Photo ${index + 1}`"
                                            class="w-full h-32 object-cover rounded-lg"
                                        />
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 rounded-lg flex items-center justify-center">
                                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex space-x-2">
                                                <button
                                                    v-if="index !== 0"
                                                    @click="setPrimaryPhoto(index)"
                                                    type="button"
                                                    class="px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700"
                                                >
                                                    Set Primary
                                                </button>
                                                <button
                                                    @click="removePhoto(index)"
                                                    type="button"
                                                    class="px-2 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700"
                                                >
                                                    Remove
                                                </button>
                                            </div>
                                        </div>
                                        <div v-if="index === 0" class="absolute top-2 left-2">
                                            <span class="px-2 py-1 bg-primary-600 text-white text-xs rounded">
                                                Primary
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                        <Link
                            :href="route('seller.products.index')"
                            class="px-6 py-3 text-gray-600 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            <span v-if="form.processing">Creating...</span>
                            <span v-else>Create Product</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </SellerLayout>
</template>
