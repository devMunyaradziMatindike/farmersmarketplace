<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    phone_number: props.user.phone_number || '',
    location: props.user.location || '',
    notification_preferences: props.user.notification_preferences || {
        email_notifications: true,
        sms_notifications: false,
        product_updates: true,
        order_updates: true,
        marketing_emails: false,
    },
});

const submit = () => {
    form.put(route('buyer.settings.update'));
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Buyer Settings" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                    Settings ⚙️
                                </h2>
                                <p class="text-gray-600 dark:text-gray-400">
                                    Manage your account settings and preferences.
                                </p>
                            </div>
                            <div class="hidden md:block">
                                <span class="text-6xl">⚙️</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Form -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">
                            Account Settings
                        </h3>

                        <form @submit.prevent="submit" class="space-y-8">
                            <!-- Basic Information -->
                            <div>
                                <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">
                                    Basic Information
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Full Name
                                        </label>
                                        <input
                                            id="name"
                                            type="text"
                                            v-model="form.name"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                            required
                                        />
                                        <div v-if="form.errors.name" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            {{ form.errors.name }}
                                        </div>
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Email Address
                                        </label>
                                        <input
                                            id="email"
                                            type="email"
                                            v-model="form.email"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                            required
                                        />
                                        <div v-if="form.errors.email" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            {{ form.errors.email }}
                                        </div>
                                    </div>

                                    <div>
                                        <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Phone Number
                                        </label>
                                        <input
                                            id="phone_number"
                                            type="tel"
                                            v-model="form.phone_number"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                            placeholder="+263 77 123 4567"
                                        />
                                        <div v-if="form.errors.phone_number" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            {{ form.errors.phone_number }}
                                        </div>
                                    </div>

                                    <div>
                                        <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Location
                                        </label>
                                        <input
                                            id="location"
                                            type="text"
                                            v-model="form.location"
                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                            placeholder="Harare, Zimbabwe"
                                        />
                                        <div v-if="form.errors.location" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                            {{ form.errors.location }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notification Preferences -->
                            <div>
                                <h4 class="text-md font-medium text-gray-900 dark:text-white mb-4">
                                    Notification Preferences
                                </h4>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Email Notifications
                                            </label>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Receive notifications via email
                                            </p>
                                        </div>
                                        <input
                                            type="checkbox"
                                            v-model="form.notification_preferences.email_notifications"
                                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                        />
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                SMS Notifications
                                            </label>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Receive notifications via SMS
                                            </p>
                                        </div>
                                        <input
                                            type="checkbox"
                                            v-model="form.notification_preferences.sms_notifications"
                                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                        />
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Product Updates
                                            </label>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Get notified about new products in your area
                                            </p>
                                        </div>
                                        <input
                                            type="checkbox"
                                            v-model="form.notification_preferences.product_updates"
                                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                        />
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Order Updates
                                            </label>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Get notified about your order status
                                            </p>
                                        </div>
                                        <input
                                            type="checkbox"
                                            v-model="form.notification_preferences.order_updates"
                                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                        />
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div>
                                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                Marketing Emails
                                            </label>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                Receive promotional emails and offers
                                            </p>
                                        </div>
                                        <input
                                            type="checkbox"
                                            v-model="form.notification_preferences.marketing_emails"
                                            class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <Link
                                    :href="route('buyer.dashboard')"
                                    class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors"
                                >
                                    Cancel
                                </Link>
                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50 transition-colors"
                                >
                                    {{ form.processing ? 'Saving...' : 'Save Settings' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
