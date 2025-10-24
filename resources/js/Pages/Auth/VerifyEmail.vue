<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
    isSeller: {
        type: Boolean,
        default: false,
    },
    userRole: {
        type: String,
        default: 'buyer',
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);

const isSellerRegistration = computed(
    () => props.status === 'seller-registration',
);

const welcomeMessage = computed(() => {
    if (props.isSeller) {
        return "Welcome to Musika Wedu! 🌾 As a seller, you'll need to verify your email address before you can start listing your products.";
    }
    return "Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?";
});
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <!-- Musika Wedu Branding -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold brand-name text-primary-600 dark:text-primary-400 mb-2">
                MUSIKA WEDU
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                {{ isSeller ? 'Seller Registration' : 'Email Verification' }}
            </p>
        </div>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ welcomeMessage }} If you didn't receive the email, we will gladly send you another.
        </div>

        <div v-if="isSeller" class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <div class="flex items-center">
                <span class="text-2xl mr-3">🌾</span>
                <div>
                    <h3 class="text-sm font-medium text-green-800 dark:text-green-200">
                        Seller Benefits
                    </h3>
                    <p class="text-sm text-green-700 dark:text-green-300 mt-1">
                        Once verified, you'll be able to list products, manage your inventory, and connect with buyers across Zimbabwe.
                    </p>
                </div>
            </div>
        </div>

        <div
            class="mb-4 text-sm font-medium text-green-600 dark:text-green-400"
            v-if="verificationLinkSent"
        >
            A new verification link has been sent to the email address you
            provided during registration.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-4 flex items-center justify-between">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Resend Verification Email
                </PrimaryButton>

                <div class="flex items-center space-x-4">
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                        >Log Out</Link
                    >
                    <Link
                        :href="route('login')"
                        class="rounded-md text-sm text-primary-600 underline hover:text-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:text-primary-400 dark:hover:text-primary-300 dark:focus:ring-offset-gray-800"
                        >Already verified? Login</Link
                    >
                </div>
            </div>
        </form>
    </GuestLayout>
</template>
