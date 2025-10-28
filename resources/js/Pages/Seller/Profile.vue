<script setup>
import SellerLayout from '@/Layouts/SellerLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    user: Object,
    stats: {
        type: Object,
        default: () => ({
            total_products: 0,
            total_views: 0,
            total_sales: 0,
            rating: 0,
            reviews_count: 0
        })
    },
    recent_products: {
        type: Array,
        default: () => []
    }
});

const showEditForm = ref(false);
const showPhotoUpload = ref(false);

const form = useForm({
    business_name: props.user.business_name || '',
    business_description: props.user.business_description || '',
    location: props.user.location || '',
    website: props.user.website || '',
    social_media: props.user.social_media || {},
    contact_details: props.user.contact_details || ''
});

const photoForm = useForm({
    profile_photo: null
}, {
    forceFormData: true
});

const updateProfile = () => {
    form.patch(route('profile.update'), {
        onSuccess: () => {
            showEditForm.value = false;
        }
    });
};

const uploadPhoto = () => {
    photoForm.post(route('profile.photo'), {
        onSuccess: () => {
            showPhotoUpload.value = false;
            photoForm.reset();
        }
    });
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const getRatingStars = (rating) => {
    const stars = [];
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 !== 0;
    
    for (let i = 0; i < fullStars; i++) {
        stars.push('★');
    }
    if (hasHalfStar) {
        stars.push('☆');
    }
    while (stars.length < 5) {
        stars.push('☆');
    }
    return stars;
};
</script>

<template>
    <Head title="Profile" />

    <SellerLayout>
        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <!-- Profile Header -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-8">
                    <div class="p-6">
                        <div class="flex items-start space-x-6">
                            <div class="flex-shrink-0">
                                <img
                                    v-if="user.profile_photo"
                                    :src="user.profile_photo"
                                    :alt="user.name"
                                    class="w-24 h-24 rounded-full object-cover"
                                />
                                <div
                                    v-else
                                    class="w-24 h-24 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center"
                                >
                                    <span class="text-gray-400 text-3xl">👤</span>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                            {{ user.business_name || user.name }}
                                        </h1>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            {{ user.location || 'Location not specified' }}
                                        </p>
                                        <div class="flex items-center mt-2">
                                            <div class="flex items-center">
                                                <span
                                                    v-for="star in getRatingStars(stats.rating)"
                                                    :key="star"
                                                    class="text-yellow-400 text-lg"
                                                >
                                                    {{ star }}
                                                </span>
                                            </div>
                                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                                {{ stats.rating.toFixed(1) }} ({{ stats.reviews_count }} reviews)
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button
                                            @click="showEditForm = !showEditForm"
                                            class="px-4 py-2 text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 border border-primary-300 dark:border-primary-600 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors"
                                        >
                                            {{ showEditForm ? 'Cancel' : 'Edit Profile' }}
                                        </button>
                                        <button
                                            @click="showPhotoUpload = !showPhotoUpload"
                                            class="px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                        >
                                            Change Photo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Business Description -->
                        <div v-if="user.business_description" class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">About</h3>
                            <p class="text-gray-600 dark:text-gray-300">{{ user.business_description }}</p>
                        </div>

                        <!-- Contact Information -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div v-if="user.contact_details">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Contact</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ user.contact_details }}</p>
                            </div>
                            <div v-if="user.website">
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">Website</h4>
                                <a
                                    :href="user.website"
                                    target="_blank"
                                    class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300"
                                >
                                    {{ user.website }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Profile Form -->
                <div v-if="showEditForm" class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Profile</h3>
                    </div>
                    <form @submit.prevent="updateProfile" class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Business Name
                                </label>
                                <input
                                    v-model="form.business_name"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Location
                                </label>
                                <input
                                    v-model="form.location"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Website
                                </label>
                                <input
                                    v-model="form.website"
                                    type="url"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Contact Details
                                </label>
                                <input
                                    v-model="form.contact_details"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Business Description
                            </label>
                            <textarea
                                v-model="form.business_description"
                                rows="4"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                placeholder="Tell buyers about your business, farming practices, or specialties..."
                            ></textarea>
                        </div>

                        <div class="flex items-center justify-end space-x-4">
                            <button
                                type="button"
                                @click="showEditForm = false"
                                class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                <span v-if="form.processing">Updating...</span>
                                <span v-else>Update Profile</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Photo Upload Form -->
                <div v-if="showPhotoUpload" class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Change Profile Photo</h3>
                    </div>
                    <form @submit.prevent="uploadPhoto" class="p-6" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Select Photo
                            </label>
                            <input
                                @change="photoForm.profile_photo = $event.target.files[0]"
                                type="file"
                                accept="image/*"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                            />
                        </div>
                        <div class="flex items-center justify-end space-x-4">
                            <button
                                type="button"
                                @click="showPhotoUpload = false"
                                class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                :disabled="photoForm.processing"
                                class="px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                <span v-if="photoForm.processing">Uploading...</span>
                                <span v-else>Upload Photo</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
                                        <span class="text-primary-600 dark:text-primary-400 text-lg">📦</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Products</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.total_products }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                        <span class="text-blue-600 dark:text-blue-400 text-lg">👁️</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Views</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.total_views }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                        <span class="text-green-600 dark:text-green-400 text-lg">💰</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Sales</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.total_sales }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center">
                                        <span class="text-yellow-600 dark:text-yellow-400 text-lg">⭐</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rating</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.rating.toFixed(1) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Products -->
                <div v-if="recent_products.length > 0" class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Products</h3>
                            <Link
                                :href="route('seller.products.index')"
                                class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300"
                            >
                                View all →
                            </Link>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div
                                v-for="product in recent_products.slice(0, 6)"
                                :key="product.id"
                                class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow"
                            >
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <img
                                            v-if="product.photos && product.photos.length > 0"
                                            :src="product.photos[0].photo_url"
                                            :alt="product.name"
                                            class="w-16 h-16 rounded-lg object-cover"
                                        />
                                        <div
                                            v-else
                                            class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center"
                                        >
                                            <span class="text-gray-400 text-2xl">📦</span>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                            {{ product.name }}
                                        </h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            ${{ product.price }}
                                        </p>
                                        <div class="mt-2">
                                            <span
                                                :class="{
                                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': product.status === 'available',
                                                    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': product.status === 'sold_out',
                                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200': product.status === 'soon_to_be_available',
                                                    'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200': product.status === 'delisted'
                                                }"
                                                class="inline-flex px-2 py-1 text-xs font-medium rounded-full"
                                            >
                                                {{ product.status.replace('_', ' ') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </SellerLayout>
</template>
