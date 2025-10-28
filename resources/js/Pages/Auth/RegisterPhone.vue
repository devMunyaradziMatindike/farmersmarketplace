<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    name: '',
    username: '',
    phone_number: '',
    password: '',
    password_confirmation: '',
    role: 'buyer',
});

const submit = () => {
    form.post(route('register-phone'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register with Phone" />

        <!-- Musika Wedu Branding -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold brand-name text-primary-600 dark:text-primary-400 mb-2">
                MUSIKA WEDU
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Register with Phone Number
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <!-- Full Name -->
            <div>
                <InputLabel for="name" value="Full Name *" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Enter your full name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <!-- Username -->
            <div>
                <InputLabel for="username" value="Username *" />
                <TextInput
                    id="username"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.username"
                    required
                    autocomplete="username"
                    placeholder="Choose a unique username"
                />
                <InputError class="mt-2" :message="form.errors.username" />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    This will appear on your profile
                </p>
            </div>

            <!-- Phone Number -->
            <div>
                <InputLabel for="phone_number" value="Phone Number *" />
                <TextInput
                    id="phone_number"
                    type="tel"
                    class="mt-1 block w-full"
                    v-model="form.phone_number"
                    required
                    autocomplete="tel"
                    placeholder="+263 77 123 4567"
                />
                <InputError class="mt-2" :message="form.errors.phone_number" />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Include country code (e.g., +263 for Zimbabwe)
                </p>
            </div>

            <!-- Password -->
            <div>
                <InputLabel for="password" value="Password *" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    placeholder="Create a strong password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <!-- Confirm Password -->
            <div>
                <InputLabel for="password_confirmation" value="Confirm Password *" />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Confirm your password"
                />
                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>

            <!-- Role Selection -->
            <div>
                <InputLabel value="Account Type *" />
                <div class="mt-2 space-y-2">
                    <label class="flex items-center cursor-pointer">
                        <input
                            v-model="form.role"
                            type="radio"
                            value="buyer"
                            class="mr-3 text-primary-600 focus:ring-primary-500"
                        />
                        <div>
                            <span class="text-gray-700 dark:text-gray-300 font-medium">🛒 Buyer</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Browse and purchase products</p>
                        </div>
                    </label>
                    <label class="flex items-center cursor-pointer">
                        <input
                            v-model="form.role"
                            type="radio"
                            value="seller"
                            class="mr-3 text-primary-600 focus:ring-primary-500"
                        />
                        <div>
                            <span class="text-gray-700 dark:text-gray-300 font-medium">🌾 Seller</span>
                            <p class="text-xs text-gray-500 dark:text-gray-400">List and sell your products</p>
                        </div>
                    </label>
                </div>
                <InputError class="mt-2" :message="form.errors.role" />
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between">
                <Link
                    :href="route('login')"
                    class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100"
                >
                    Already have an account?
                </Link>

                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing">Registering...</span>
                    <span v-else>Register with Phone</span>
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
                    :href="route('register')"
                    class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                >
                    <span class="text-gray-700 dark:text-gray-300 font-medium">
                        📧 Email Registration
                    </span>
                </Link>
            </div>
        </div>
    </GuestLayout>
</template>




