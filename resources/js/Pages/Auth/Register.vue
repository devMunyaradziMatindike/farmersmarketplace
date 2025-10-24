<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <!-- Musika Wedu Branding -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold brand-name text-primary-600 dark:text-primary-400 mb-2">
                MUSIKA WEDU
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Create Your Seller Account
            </p>
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Confirm Password"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>


            <!-- Registration Info -->
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <div class="flex items-start">
                    <span class="text-2xl mr-3">ℹ️</span>
                    <div>
                        <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">
                            Seller Registration Process
                        </h3>
                        <div class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                            <p><strong>Email Verification Required:</strong> You'll receive a verification link to confirm your email address</p>
                            <p><strong>After Verification:</strong> You can start listing and selling your products</p>
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-2">
                                We verify seller emails to ensure a safe marketplace for all users.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                >
                    Already registered?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register as Seller
                </PrimaryButton>
            </div>
        </form>

        <!-- Alternative Registration Options -->
        <div class="mt-8">
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white dark:bg-gray-900 text-gray-500 dark:text-gray-400">
                        Or register with
                    </span>
                </div>
            </div>

            <div class="mt-6 space-y-3">
                <Link
                    :href="route('register-phone')"
                    class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                >
                    <span class="text-gray-700 dark:text-gray-300 font-medium">
                        📱 Phone Number (with OTP)
                    </span>
                </Link>
            </div>
        </div>
    </GuestLayout>
</template>
