<script setup>
import SellerLayout from '@/Layouts/SellerLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    user: Object
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    phone_number: props.user.phone_number,
    contact_details: props.user.contact_details || '',
    business_name: props.user.business_name || '',
    business_description: props.user.business_description || '',
    location: props.user.location || '',
    website: props.user.website || '',
    social_media: props.user.social_media || {}
});

const showPasswordForm = ref(false);
const showDeleteForm = ref(false);
const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: ''
});
const deleteForm = useForm({
    password: ''
});

const updateProfile = () => {
    form.put(route('seller.profile.update'), {
        onSuccess: () => {
            // Show success message
        }
    });
};

const updatePassword = () => {
    passwordForm.put(route('password.update'), {
        onSuccess: () => {
            passwordForm.reset();
            showPasswordForm.value = false;
        }
    });
};

const deleteAccount = () => {
    showDeleteForm.value = true;
};

const confirmDeleteAccount = () => {
    deleteForm.delete(route('profile.destroy'), {
        onSuccess: () => {
            // Account deleted successfully, user will be redirected to home page
            console.log('Account deleted successfully');
        },
        onError: (errors) => {
            console.error('Account deletion failed:', errors);
            alert('Failed to delete account. Please check your password and try again.');
        }
    });
};

const cancelDeleteAccount = () => {
    showDeleteForm.value = false;
    deleteForm.reset();
};
</script>

<template>
    <Head title="Settings" />

    <SellerLayout>
        <div class="py-12">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="space-y-8">
                    <!-- Profile Information -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Profile Information</h3>
                        </div>
                        <form @submit.prevent="updateProfile" class="p-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Full Name *
                                    </label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                    />
                                    <div v-if="form.errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                        {{ form.errors.name }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Email Address *
                                    </label>
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                    />
                                    <div v-if="form.errors.email" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                        {{ form.errors.email }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Phone Number *
                                    </label>
                                    <input
                                        v-model="form.phone_number"
                                        type="tel"
                                        required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                    />
                                    <div v-if="form.errors.phone_number" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                        {{ form.errors.phone_number }}
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Contact Details
                                    </label>
                                    <input
                                        v-model="form.contact_details"
                                        type="text"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="e.g., +263 77 123 4567"
                                    />
                                    <div v-if="form.errors.contact_details" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                        {{ form.errors.contact_details }}
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end">
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

                    <!-- Business Information -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Business Information</h3>
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
                                        placeholder="Your business or farm name"
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
                                        placeholder="e.g., Harare, Zimbabwe"
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
                                        placeholder="https://your-website.com"
                                    />
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        WhatsApp
                                    </label>
                                    <input
                                        v-model="form.social_media.whatsapp"
                                        type="text"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                        placeholder="+263 77 123 4567"
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

                            <div class="flex items-center justify-end">
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                >
                                    <span v-if="form.processing">Updating...</span>
                                    <span v-else>Update Business Info</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Security Settings -->
                    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Security Settings</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <!-- Password Change -->
                                <div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Password</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Update your password</p>
                                        </div>
                                        <button
                                            @click="showPasswordForm = !showPasswordForm"
                                            class="px-4 py-2 text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 border border-primary-300 dark:border-primary-600 rounded-lg hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors"
                                        >
                                            {{ showPasswordForm ? 'Cancel' : 'Change Password' }}
                                        </button>
                                    </div>

                                    <form v-if="showPasswordForm" @submit.prevent="updatePassword" class="mt-4 space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Current Password
                                            </label>
                                            <input
                                                v-model="passwordForm.current_password"
                                                type="password"
                                                required
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                            />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                New Password
                                            </label>
                                            <input
                                                v-model="passwordForm.password"
                                                type="password"
                                                required
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                            />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Confirm New Password
                                            </label>
                                            <input
                                                v-model="passwordForm.password_confirmation"
                                                type="password"
                                                required
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                            />
                                        </div>
                                        <div class="flex items-center space-x-4">
                                            <button
                                                type="submit"
                                                :disabled="passwordForm.processing"
                                                class="px-4 py-2 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                            >
                                                Update Password
                                            </button>
                                            <button
                                                type="button"
                                                @click="showPasswordForm = false"
                                                class="px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                            >
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Account Deletion -->
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="text-sm font-medium text-red-600 dark:text-red-400">Delete Account</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Permanently delete your account and all data</p>
                                        </div>
                                        <button
                                            @click="deleteAccount"
                                            class="px-4 py-2 text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 border border-red-300 dark:border-red-600 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors"
                                        >
                                            Delete Account
                                        </button>
                                    </div>
                                    
                                    <!-- Delete Account Form -->
                                    <div v-if="showDeleteForm" class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                                        <h5 class="text-sm font-medium text-red-800 dark:text-red-200 mb-2">
                                            Confirm Account Deletion
                                        </h5>
                                        <p class="text-sm text-red-700 dark:text-red-300 mb-4">
                                            This action cannot be undone. Please enter your password to confirm account deletion.
                                        </p>
                                        
                                        <div class="space-y-4">
                                            <div>
                                                <label for="delete-password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                    Password
                                                </label>
                                                <input
                                                    id="delete-password"
                                                    v-model="deleteForm.password"
                                                    type="password"
                                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 dark:bg-gray-700 dark:text-white"
                                                    placeholder="Enter your current password"
                                                />
                                                <div v-if="deleteForm.errors.password" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                                    {{ deleteForm.errors.password }}
                                                </div>
                                            </div>
                                            
                                            <div class="flex space-x-3">
                                                <button
                                                    @click="confirmDeleteAccount"
                                                    :disabled="deleteForm.processing"
                                                    class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                                >
                                                    {{ deleteForm.processing ? 'Deleting...' : 'Delete Account' }}
                                                </button>
                                                <button
                                                    @click="cancelDeleteAccount"
                                                    :disabled="deleteForm.processing"
                                                    class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-400 dark:hover:bg-gray-500 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                                >
                                                    Cancel
                                                </button>
                                            </div>
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
