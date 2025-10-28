<script setup>
import SellerLayout from '@/Layouts/SellerLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    conversations: {
        type: Object,
        default: () => ({
            data: [],
            links: []
        })
    },
    stats: {
        type: Object,
        default: () => ({
            total_conversations: 0,
            unread_messages: 0,
            active_conversations: 0
        })
    }
});

const searchQuery = ref('');
const selectedStatus = ref('');
const selectedConversation = ref(null);

const filteredConversations = computed(() => {
    let filtered = props.conversations.data;

    if (searchQuery.value) {
        filtered = filtered.filter(conversation => 
            conversation.buyer_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            conversation.product_name.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }

    if (selectedStatus.value) {
        filtered = filtered.filter(conversation => conversation.status === selectedStatus.value);
    }

    return filtered;
});

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const formatTime = (date) => {
    return new Date(date).toLocaleTimeString('en-US', {
        hour: '2-digit',
        minute: '2-digit'
    });
};

const isToday = (date) => {
    const today = new Date();
    const messageDate = new Date(date);
    return today.toDateString() === messageDate.toDateString();
};

const isYesterday = (date) => {
    const yesterday = new Date();
    yesterday.setDate(yesterday.getDate() - 1);
    const messageDate = new Date(date);
    return yesterday.toDateString() === messageDate.toDateString();
};

const getRelativeTime = (date) => {
    if (isToday(date)) {
        return formatTime(date);
    } else if (isYesterday(date)) {
        return 'Yesterday';
    } else {
        return formatDate(date);
    }
};

const sendMessage = (conversationId) => {
    const form = useForm({
        message: '',
        conversation_id: conversationId
    });
    
    form.post(route('seller.messages.send'), {
        onSuccess: () => {
            form.reset();
        }
    });
};

const markAsRead = (conversationId) => {
    const form = useForm({});
    form.patch(route('seller.messages.read', conversationId));
};
</script>

<template>
    <Head title="Messages" />

    <SellerLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Message Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
                                        <span class="text-primary-600 dark:text-primary-400 text-lg">💬</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Conversations</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.total_conversations }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                                        <span class="text-red-600 dark:text-red-400 text-lg">🔴</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Unread Messages</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.unread_messages }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                        <span class="text-green-600 dark:text-green-400 text-lg">✅</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Conversations</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-white">{{ stats.active_conversations }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg mb-8">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Search Conversations
                                </label>
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search by buyer or product..."
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                />
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Filter by Status
                                </label>
                                <select
                                    v-model="selectedStatus"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                                >
                                    <option value="">All Conversations</option>
                                    <option value="active">Active</option>
                                    <option value="archived">Archived</option>
                                </select>
                            </div>

                            <div class="flex items-end">
                                <button
                                    @click="searchQuery = ''; selectedStatus = ''"
                                    class="w-full px-4 py-2 text-gray-600 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                                >
                                    Clear Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Conversations List -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                            Conversations ({{ filteredConversations.length }})
                        </h3>
                    </div>
                    <div class="p-6">
                        <div v-if="filteredConversations.length > 0" class="space-y-4">
                            <div
                                v-for="conversation in filteredConversations"
                                :key="conversation.id"
                                class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 hover:shadow-md transition-shadow cursor-pointer"
                                @click="selectedConversation = conversation"
                            >
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-4">
                                            <img
                                                v-if="conversation.buyer_photo"
                                                :src="conversation.buyer_photo"
                                                :alt="conversation.buyer_name"
                                                class="w-12 h-12 rounded-full object-cover"
                                            />
                                            <div
                                                v-else
                                                class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center"
                                            >
                                                <span class="text-gray-400 text-lg">👤</span>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-2">
                                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white">
                                                        {{ conversation.buyer_name }}
                                                    </h4>
                                                    <span
                                                        v-if="conversation.unread_count > 0"
                                                        class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 rounded-full"
                                                    >
                                                        {{ conversation.unread_count }}
                                                    </span>
                                                </div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    About: {{ conversation.product_name }}
                                                </p>
                                                <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                                                    {{ conversation.last_message }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ getRelativeTime(conversation.last_message_time) }}
                                        </div>
                                        <div class="mt-2">
                                            <span
                                                :class="{
                                                    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': conversation.status === 'active',
                                                    'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200': conversation.status === 'archived'
                                                }"
                                                class="inline-flex px-2 py-1 text-xs font-medium rounded-full"
                                            >
                                                {{ conversation.status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <div class="mt-4 flex items-center justify-between">
                                    <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span>{{ conversation.message_count }} messages</span>
                                        <span>•</span>
                                        <span>Started {{ formatDate(conversation.created_at) }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <Link
                                            :href="route('seller.messages.show', conversation.id)"
                                            class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300"
                                        >
                                            View Conversation
                                        </Link>
                                        <button
                                            v-if="conversation.unread_count > 0"
                                            @click="markAsRead(conversation.id)"
                                            class="text-sm text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300"
                                        >
                                            Mark as Read
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-8">
                            <div class="mx-auto w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                <span class="text-gray-400 text-4xl">💬</span>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                                No Conversations Found
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">
                                {{ searchQuery || selectedStatus ? 'Try adjusting your filters' : 'You haven\'t received any messages yet' }}
                            </p>
                            <Link
                                v-if="!searchQuery && !selectedStatus"
                                :href="route('seller.products.create')"
                                class="inline-flex items-center px-6 py-3 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors"
                            >
                                <span class="mr-2">➕</span>
                                Add Your First Product
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SellerLayout>
</template>
