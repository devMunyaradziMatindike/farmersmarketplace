<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    settings: Object,
    currencySettings: Object,
    flash: {
        type: Object,
        default: () => ({})
    }
});

const form = useForm({
    app_name: props.settings.app_name,
    app_url: props.settings.app_url,
    mail_from_address: props.settings.mail_from_address,
    mail_from_name: props.settings.mail_from_name
});

const currencyForm = useForm({
    base_currency: props.currencySettings.base_currency,
    zwg_to_usd_rate: props.currencySettings.zwg_to_usd_rate
});

const submit = () => {
    form.put(route('admin.settings.update'));
};

const updateCurrency = () => {
    currencyForm.put(route('admin.settings.currency'));
};

const formatDate = (date) => {
    if (!date) return 'Never';
    return new Date(date).toLocaleString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const usdToZwg = computed(() => {
    return (100 * currencyForm.zwg_to_usd_rate).toFixed(2);
});

const zwgToUsd = computed(() => {
    return (100 / currencyForm.zwg_to_usd_rate).toFixed(2);
});
</script>

<template>
    <Head title="Settings" />

    <AdminLayout>
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Platform Settings ⚙️
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">
                        Configure platform settings and preferences
                    </p>
                </div>

                <!-- Success Message -->
                <div v-if="flash && flash.success" class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">
                                {{ flash.success }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Application Settings -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Application Settings</h3>
                    </div>
                    <form @submit.prevent="submit" class="p-6 space-y-6">
                        <!-- App Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Application Name *
                            </label>
                            <input
                                v-model="form.app_name"
                                type="text"
                                required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                            />
                            <div v-if="form.errors.app_name" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.app_name }}
                            </div>
                        </div>

                        <!-- App URL -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Application URL *
                            </label>
                            <input
                                v-model="form.app_url"
                                type="url"
                                required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                            />
                            <div v-if="form.errors.app_url" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.app_url }}
                            </div>
                        </div>

                        <!-- Mail From Address -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Mail From Address *
                            </label>
                            <input
                                v-model="form.mail_from_address"
                                type="email"
                                required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                            />
                            <div v-if="form.errors.mail_from_address" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.mail_from_address }}
                            </div>
                        </div>

                        <!-- Mail From Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Mail From Name *
                            </label>
                            <input
                                v-model="form.mail_from_name"
                                type="text"
                                required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                            />
                            <div v-if="form.errors.mail_from_name" class="mt-1 text-sm text-red-600 dark:text-red-400">
                                {{ form.errors.mail_from_name }}
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                <span v-if="form.processing">Saving...</span>
                                <span v-else">Save Settings</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Currency Settings -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Currency Settings 💱</h3>
                    </div>
                    <form @submit.prevent="updateCurrency" class="p-6 space-y-6">
                        <!-- Base Currency -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Base Currency *
                            </label>
                            <select
                                v-model="currencyForm.base_currency"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                            >
                                <option value="USD">USD - United States Dollar</option>
                                <option value="ZWG">ZWG - Zimbabwe Gold</option>
                            </select>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                All prices will be convertible to this base currency
                            </p>
                        </div>

                        <!-- Exchange Rate -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                ZWG to USD Exchange Rate *
                            </label>
                            <div class="flex items-center space-x-2">
                                <span class="text-gray-600 dark:text-gray-400">ZWG$1 =</span>
                                <input
                                    v-model="currencyForm.zwg_to_usd_rate"
                                    type="number"
                                    step="0.0001"
                                    min="0"
                                    required
                                    class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                    placeholder="13.5000"
                                />
                                <span class="text-gray-600 dark:text-gray-400">USD</span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Current rate: ZWG${{ currencySettings.zwg_to_usd_rate }} = $1.00 USD
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Last updated: {{ formatDate(currencySettings.rate_updated_at) }}
                            </p>
                        </div>

                        <!-- Quick Rate Calculator -->
                        <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Quick Converter</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">$100 USD =</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        ZWG${{ usdToZwg }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">ZWG$100 =</p>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                        ${{ zwgToUsd }} USD
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Save Button -->
                        <div class="flex items-center justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
                            <button
                                type="submit"
                                :disabled="currencyForm.processing"
                                class="px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                            >
                                <span v-if="currencyForm.processing">Updating...</span>
                                <span v-else>Update Currency Settings</span>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Service Status -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">External Services Status</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <!-- Twilio Status -->
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Twilio SMS Service</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Used for OTP verification</p>
                                </div>
                                <span
                                    :class="settings.twilio_enabled ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'"
                                    class="inline-flex px-3 py-1 text-sm font-medium rounded-full"
                                >
                                    {{ settings.twilio_enabled ? 'Enabled' : 'Disabled' }}
                                </span>
                            </div>

                            <!-- Google OAuth Status -->
                            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">Google OAuth</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Google Sign-In integration</p>
                                </div>
                                <span
                                    :class="settings.google_oauth_enabled ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'"
                                    class="inline-flex px-3 py-1 text-sm font-medium rounded-full"
                                >
                                    {{ settings.google_oauth_enabled ? 'Enabled' : 'Disabled' }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <p class="text-sm text-blue-800 dark:text-blue-200">
                                <strong>Note:</strong> To modify service configurations, update your <code>.env</code> file and restart the application.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

