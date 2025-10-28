<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

const props = defineProps({
    phone_number: String,
});

const form = useForm({
    otp: '',
});

const submit = () => {
    form.post(route('verify-otp'));
};

const resendOTP = () => {
    form.post(route('resend-otp'), {
        preserveScroll: true,
        onSuccess: () => {
            // Reset form after successful resend
            form.reset();
        }
    });
};

// Auto-focus on OTP input
onMounted(() => {
    const otpInput = document.getElementById('otp');
    if (otpInput) {
        otpInput.focus();
    }
});
</script>

<template>
    <GuestLayout>
        <Head title="Verify OTP" />

        <!-- Musika Wedu Branding -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold brand-name text-primary-600 dark:text-primary-400 mb-2">
                MUSIKA WEDU
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Verify Your Phone Number
            </p>
        </div>

        <!-- Success Message -->
        <div v-if="$page.props.flash?.success" class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-800 dark:text-green-200">
                        {{ $page.props.flash?.success }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Phone Number Display -->
        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <strong>OTP sent to:</strong> {{ phone_number }}
                    </p>
                    <p class="text-xs text-blue-600 dark:text-blue-300 mt-1">
                        Check your SMS messages for the 6-digit verification code
                    </p>
                </div>
            </div>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <!-- OTP Input -->
            <div>
                <InputLabel for="otp" value="Enter Verification Code *" />
                <TextInput
                    id="otp"
                    type="text"
                    class="mt-1 block w-full text-center text-2xl tracking-widest"
                    v-model="form.otp"
                    required
                    autofocus
                    maxlength="6"
                    placeholder="000000"
                    @input="form.otp = form.otp.replace(/[^0-9]/g, '')"
                />
                <InputError class="mt-2" :message="form.errors.otp" />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Enter the 6-digit code sent to your phone
                </p>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between">
                <button
                    type="button"
                    @click="resendOTP"
                    :disabled="form.processing"
                    class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium disabled:opacity-50"
                >
                    Resend OTP
                </button>

                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing">Verifying...</span>
                    <span v-else>Verify & Complete Registration</span>
                </PrimaryButton>
            </div>
        </form>

        <!-- Help Text -->
        <div class="mt-8 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
            <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-2">
                Didn't receive the code?
            </h3>
            <ul class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                <li>• Check your SMS messages</li>
                <li>• Make sure your phone number is correct</li>
                <li>• Wait a few minutes and try resending</li>
                <li>• Check your spam folder</li>
            </ul>
        </div>

        <!-- Back to Registration -->
        <div class="mt-6 text-center">
            <Link
                :href="route('register-phone')"
                class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100"
            >
                ← Back to Registration
            </Link>
        </div>
    </GuestLayout>
</template>
