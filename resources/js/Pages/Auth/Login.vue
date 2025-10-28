<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const isEmail = ref(true);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const toggleInputType = () => {
    isEmail.value = !isEmail.value;
    form.email = '';
};

const googleSignIn = () => {
    window.location.href = route('google.redirect');
};

const isEmailVerified = computed(() => props.status === 'email-verified');
const isEmailAlreadyVerified = computed(() => props.status === 'email-already-verified');
</script>

<template>
    <GuestLayout>
        <Head>
            <title>Seller Login Portal - 🌾 Musika Wedu | todye tichiguta</title>
            <meta name="description" content="Login to your Musika Wedu seller account. Access your dashboard, manage products, and grow your agricultural business on Zimbabwe's premier marketplace.">
            <meta name="keywords" content="seller login Zimbabwe, agricultural marketplace login, Musika Wedu login, farmer login Zimbabwe">
            <link rel="canonical" href="https://musikawedu.co.zw/login">
        </Head>

        <!-- Musika Wedu Branding -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold brand-name text-primary-600 dark:text-primary-400 mb-2">
                MUSIKA WEDU
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Seller Login Portal
            </p>
        </div>

        <!-- Email Verification Status Messages -->
        <div v-if="isEmailVerified" class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
            <div class="flex items-center">
                <span class="text-2xl mr-3">✅</span>
                <div>
                    <h3 class="text-sm font-medium text-green-800 dark:text-green-200">
                        Email Verified Successfully!
                    </h3>
                    <p class="text-sm text-green-700 dark:text-green-300 mt-1">
                        Your email has been verified. You can now log in with your credentials.
                    </p>
                </div>
            </div>
        </div>

        <div v-if="isEmailAlreadyVerified" class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
            <div class="flex items-center">
                <span class="text-2xl mr-3">ℹ️</span>
                <div>
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        Email Already Verified
                    </h3>
                    <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                        Your email is already verified. Please log in with your credentials.
                    </p>
                </div>
            </div>
        </div>

        <!-- Google Sign-In Button -->
        <button
            @click="googleSignIn"
            type="button"
            class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors mb-6"
        >
            <svg class="w-6 h-6 mr-3" viewBox="0 0 48 48">
                <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                <path fill="none" d="M0 0h48v48H0z"/>
            </svg>
            <span class="text-gray-700 dark:text-gray-300 font-medium">
                Sign in with Google
            </span>
        </button>

        <!-- Divider -->
        <div class="relative my-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-white dark:bg-gray-900 text-gray-500 dark:text-gray-400">
                    Or continue with
                </span>
            </div>
        </div>

        <!-- Login Form -->
        <form @submit.prevent="submit">
            <!-- Email or Phone Toggle -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <InputLabel :for="isEmail ? 'email' : 'phone'" :value="isEmail ? 'Email Address' : 'Phone Number'" />
                    <button
                        type="button"
                        @click="toggleInputType"
                        class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium"
                    >
                        {{ isEmail ? '📱 Use Phone' : '📧 Use Email' }}
                    </button>
                </div>

                <TextInput
                    :id="isEmail ? 'email' : 'phone'"
                    :type="isEmail ? 'email' : 'tel'"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    :autocomplete="isEmail ? 'username' : 'tel'"
                    :placeholder="isEmail ? 'your@email.com' : '+263 77 123 4567'"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- Remember Me -->
            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                </label>
            </div>

            <!-- Actions -->
            <div class="mt-4 flex items-center justify-between">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                >
                    Forgot password?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form>

        <!-- Register Link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Don't have a seller account?
                <Link
                    :href="route('register')"
                    class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium"
                >
                    Register as seller
                </Link>
            </p>
        </div>
    </GuestLayout>
</template>
