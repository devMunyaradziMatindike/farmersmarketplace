<template>
    <Head>
        <title>{{ event.title }} - Events | 🌾 Musika Wedu</title>
        <meta name="description" :content="event.description.substring(0, 160)">
    </Head>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <AppHeader :filters="{}" />

        <!-- Event Hero Image -->
        <div class="relative h-64 sm:h-96 overflow-hidden">
            <img
                :src="event.image || '/images/placeholder-hero.svg'"
                :alt="event.title"
                class="w-full h-full object-cover"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 text-white">
                <div class="max-w-7xl mx-auto">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-semibold">
                            {{ formatEventType(event.type) }}
                        </span>
                        <span v-if="event.is_featured" class="px-3 py-1 bg-yellow-400 text-yellow-900 rounded-full text-sm font-bold">
                            ⭐ Featured
                        </span>
                    </div>
                    <h1 class="text-3xl sm:text-5xl font-bold mb-4">{{ event.title }}</h1>
                    <div class="flex flex-wrap items-center gap-4 text-sm sm:text-base">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>{{ formatDate(event.start_date) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>{{ event.location }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Flash Messages -->
            <div v-if="$page.props.flash?.success" class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-green-800 dark:text-green-200 font-semibold">{{ $page.props.flash.success }}</p>
                </div>
            </div>
            <div v-if="$page.props.flash?.error" class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-red-800 dark:text-red-200 font-semibold">{{ $page.props.flash.error }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Description -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 sm:p-8 mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">About This Event</h2>
                        <div class="prose dark:prose-invert max-w-none">
                            <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ event.description }}</p>
                        </div>
                    </div>

                    <!-- Event Details -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 sm:p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Event Details</h2>
                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Date & Time</h3>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        {{ formatFullDate(event.start_date) }}
                                        <span v-if="event.end_date">
                                            <br>Ends: {{ formatFullDate(event.end_date) }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Location</h3>
                                    <p class="text-gray-600 dark:text-gray-400">{{ event.location }}</p>
                                </div>
                            </div>

                            <div v-if="event.organizer_name" class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white">Organizer</h3>
                                    <p class="text-gray-600 dark:text-gray-400">{{ event.organizer_name }}</p>
                                    <p v-if="event.organizer_contact" class="text-gray-600 dark:text-gray-400">{{ event.organizer_contact }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Registration Instructions -->
                    <div v-if="event.registration_instructions" class="bg-blue-50 dark:bg-blue-900/20 rounded-xl shadow-sm p-6 sm:p-8 mt-6">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Registration Instructions</h2>
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ event.registration_instructions }}</p>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Registration Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 sm:p-8 sticky top-24">
                        <div v-if="isRegistered" class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-semibold text-green-800 dark:text-green-200">You're Registered!</span>
                            </div>
                            <p class="text-sm text-green-700 dark:text-green-300">You have successfully registered for this event.</p>
                        </div>

                        <div v-else>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Register for Event</h3>
                            
                            <!-- Registration Fee -->
                            <div v-if="event.registration_fee > 0" class="mb-6 p-4 bg-primary-50 dark:bg-primary-900/20 rounded-lg">
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Registration Fee</div>
                                <div class="text-2xl font-bold text-primary-600 dark:text-primary-400">
                                    {{ event.currency === 'USD' ? 'USD' : 'ZWG' }} {{ parseFloat(event.registration_fee).toFixed(2) }}
                                </div>
                            </div>

                            <!-- Capacity Info -->
                            <div v-if="event.capacity" class="mb-6 text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex justify-between mb-2">
                                    <span>Capacity</span>
                                    <span class="font-semibold">{{ event.registrations_count || 0 }} / {{ event.capacity }}</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div
                                        class="bg-primary-600 h-2 rounded-full transition-all"
                                        :style="{ width: `${Math.min((event.registrations_count || 0) / event.capacity * 100, 100)}%` }"
                                    ></div>
                                </div>
                            </div>

                            <!-- Status -->
                            <div v-if="!event.is_registration_open" class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                <p class="text-sm text-red-800 dark:text-red-200 font-semibold">Registration is closed</p>
                            </div>
                            <div v-else-if="event.capacity && (event.registrations_count || 0) >= event.capacity" class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                                <p class="text-sm text-red-800 dark:text-red-200 font-semibold">Event is full</p>
                            </div>

                            <!-- Registration Form -->
                            <form v-if="event.is_registration_open && (!event.capacity || (event.registrations_count || 0) < event.capacity)" @submit.prevent="submitRegistration">
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Registration Type *
                                        </label>
                                        <select
                                            v-model="form.registration_type"
                                            required
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                        >
                                            <option value="individual">Individual</option>
                                            <option value="cooperative">Cooperative</option>
                                            <option value="company">Company</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Full Name *
                                        </label>
                                        <input
                                            v-model="form.name"
                                            type="text"
                                            required
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Email *
                                        </label>
                                        <input
                                            v-model="form.email"
                                            type="email"
                                            required
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Phone *
                                        </label>
                                        <input
                                            v-model="form.phone"
                                            type="tel"
                                            required
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                        />
                                    </div>

                                    <div v-if="form.registration_type !== 'individual'">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Organization Name *
                                        </label>
                                        <input
                                            v-model="form.organization_name"
                                            type="text"
                                            :required="form.registration_type !== 'individual'"
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Additional Information (Optional)
                                        </label>
                                        <textarea
                                            v-model="form.additional_info"
                                            rows="3"
                                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                        ></textarea>
                                    </div>

                                    <!-- Note for guests -->
                                    <div v-if="!page.props.auth?.user" class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 mb-4">
                                        <p class="text-xs text-blue-800 dark:text-blue-200">
                                            💡 You can register as a guest. <Link :href="route('login')" class="underline font-semibold">Login</Link> to pre-fill your information.
                                        </p>
                                    </div>

                                    <button
                                        type="submit"
                                        :disabled="form.processing || !event.is_registration_open"
                                        :class="[
                                            'w-full py-3 px-4 rounded-lg font-semibold transition',
                                            form.processing || !event.is_registration_open
                                                ? 'bg-gray-300 dark:bg-gray-700 text-gray-500 cursor-not-allowed'
                                                : 'bg-primary-600 hover:bg-primary-700 text-white'
                                        ]"
                                    >
                                        <span v-if="form.processing">Registering...</span>
                                        <span v-else>Register Now</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AppHeader from '@/Components/AppHeader.vue';

const props = defineProps({
    event: Object,
    isRegistered: Boolean,
});

const page = usePage();

const form = useForm({
    registration_type: 'individual',
    name: '',
    email: '',
    phone: '',
    organization_name: '',
    additional_info: '',
});

// Pre-fill form with user data if logged in
onMounted(() => {
    if (page.props.auth?.user) {
        form.name = page.props.auth.user.name || '';
        form.email = page.props.auth.user.email || '';
    }
});

const submitRegistration = () => {
    form.post(route('events.register', props.event.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
};

const formatEventType = (type) => {
    const types = {
        workshop: 'Workshop',
        exhibition: 'Exhibition',
        training: 'Training',
        seminar: 'Seminar',
        field_day: 'Field Day',
        conference: 'Conference',
        networking: 'Networking',
        other: 'Other',
    };
    return types[type] || type;
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
    });
};

const formatFullDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

