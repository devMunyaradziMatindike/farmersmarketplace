<template>
    <Head>
        <title>Agricultural Events - 🌾 Musika Wedu | todye tichiguta</title>
        <meta name="description" content="Discover agricultural workshops, exhibitions, training sessions, and networking events in Zimbabwe. Register for events to grow your farming business.">
    </Head>

    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <AppHeader :filters="{}" />

        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-green-600 via-green-700 to-green-800 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
                <div class="text-center">
                    <h1 class="text-4xl sm:text-5xl font-bold mb-4">
                        Agricultural Events
                    </h1>
                    <p class="text-xl sm:text-2xl text-green-100 max-w-2xl mx-auto">
                        Join workshops, exhibitions, training sessions, and networking events to grow your farming business
                    </p>
                </div>
            </div>
        </div>

        <!-- Filters & Sort -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row gap-4 mb-6">
                <!-- Event Type Filter -->
                <select
                    v-model="filters.type"
                    @change="applyFilters"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white"
                >
                    <option value="">All Event Types</option>
                    <option value="workshop">Workshop</option>
                    <option value="exhibition">Exhibition</option>
                    <option value="training">Training</option>
                    <option value="seminar">Seminar</option>
                    <option value="field_day">Field Day</option>
                    <option value="conference">Conference</option>
                    <option value="networking">Networking</option>
                </select>

                <!-- Upcoming Filter -->
                <button
                    @click="toggleUpcoming"
                    :class="[
                        'px-4 py-2 rounded-lg font-medium transition',
                        filters.upcoming
                            ? 'bg-primary-600 text-white'
                            : 'bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200'
                    ]"
                >
                    Upcoming Events
                </button>

                <!-- Sort -->
                <select
                    v-model="filters.sort"
                    @change="applyFilters"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white ml-auto"
                >
                    <option value="start_date">Date (Earliest)</option>
                    <option value="featured">Featured First</option>
                    <option value="newest">Newest First</option>
                </select>
            </div>

            <!-- Events Grid -->
            <div v-if="events.data && events.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <Link
                    v-for="event in events.data"
                    :key="event.id"
                    :href="route('events.show', event.id)"
                    class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden card-hover"
                >
                    <!-- Event Image -->
                    <div class="relative h-48 overflow-hidden">
                        <img
                            :src="event.image || '/images/placeholder.svg'"
                            :alt="event.title"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                        />
                        <!-- Featured Badge -->
                        <div v-if="event.is_featured" class="absolute top-3 right-3">
                            <span class="px-3 py-1 bg-yellow-400 text-yellow-900 text-xs font-bold rounded-full">
                                ⭐ Featured
                            </span>
                        </div>
                        <!-- Event Type Badge -->
                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm text-gray-900 dark:text-white text-xs font-semibold rounded-full">
                                {{ formatEventType(event.type) }}
                            </span>
                        </div>
                        <!-- Status Badge -->
                        <div v-if="!event.is_registration_open" class="absolute bottom-3 right-3">
                            <span class="px-3 py-1 bg-red-500 text-white text-xs font-semibold rounded-full">
                                Registration Closed
                            </span>
                        </div>
                    </div>

                    <!-- Event Content -->
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">
                            {{ event.title }}
                        </h3>
                        
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                            {{ event.description }}
                        </p>

                        <!-- Event Details -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ formatDate(event.start_date) }}</span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>{{ event.location }}</span>
                            </div>
                            <div v-if="event.registration_fee > 0" class="flex items-center gap-2 text-sm">
                                <span class="font-semibold text-primary-600 dark:text-primary-400">
                                    {{ event.currency === 'USD' ? 'USD' : 'ZWG' }} {{ parseFloat(event.registration_fee).toFixed(2) }}
                                </span>
                            </div>
                        </div>

                        <!-- Registration Count -->
                        <div v-if="event.capacity" class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                            {{ event.registrations_count || 0 }} / {{ event.capacity }} registered
                        </div>

                        <!-- CTA Button -->
                        <button
                            :disabled="!event.is_registration_open"
                            :class="[
                                'w-full py-2.5 px-4 rounded-lg font-medium transition',
                                event.is_registration_open
                                    ? 'bg-primary-600 hover:bg-primary-700 text-white'
                                    : 'bg-gray-300 dark:bg-gray-700 text-gray-500 cursor-not-allowed'
                            ]"
                        >
                            {{ event.is_registration_open ? 'View Details' : 'Registration Closed' }}
                        </button>
                    </div>
                </Link>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No events found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your filters.</p>
            </div>

            <!-- Pagination -->
            <div v-if="events.links && events.links.length > 3" class="mt-8 flex justify-center">
                <div class="flex gap-2">
                    <Link
                        v-for="(link, index) in events.links"
                        :key="index"
                        :href="link.url || '#'"
                        v-html="link.label"
                        :class="[
                            'px-4 py-2 rounded-lg transition',
                            link.active
                                ? 'bg-primary-600 text-white'
                                : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700',
                            !link.url && 'pointer-events-none opacity-50'
                        ]"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AppHeader from '@/Components/AppHeader.vue';

const props = defineProps({
    events: Object,
    filters: Object,
});

const filters = reactive({
    type: props.filters?.type || '',
    upcoming: props.filters?.upcoming || null,
    sort: props.filters?.sort || 'start_date',
});

const toggleUpcoming = () => {
    filters.upcoming = filters.upcoming ? null : '1';
    applyFilters();
};

const applyFilters = () => {
    router.get(route('events.index'), filters, {
        preserveState: true,
        preserveScroll: true,
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
        weekday: 'short',
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

