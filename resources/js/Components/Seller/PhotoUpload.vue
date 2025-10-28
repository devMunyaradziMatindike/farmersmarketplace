<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => []
    },
    maxPhotos: {
        type: Number,
        default: 10
    },
    maxSize: {
        type: Number,
        default: 5 * 1024 * 1024 // 5MB
    },
    existingPhotos: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['update:modelValue']);

const photoFiles = ref([]);
const photoPreviews = ref([]);
const isDragOver = ref(false);

const handlePhotoUpload = (event) => {
    const files = Array.from(event.target.files);
    addPhotos(files);
};

const handleDragOver = (event) => {
    event.preventDefault();
    isDragOver.value = true;
};

const handleDragLeave = (event) => {
    event.preventDefault();
    isDragOver.value = false;
};

const handleDrop = (event) => {
    event.preventDefault();
    isDragOver.value = false;
    
    const files = Array.from(event.dataTransfer.files);
    addPhotos(files);
};

const addPhotos = (files) => {
    const totalPhotos = photoFiles.value.length + existingPhotos.value.length;
    
    if (totalPhotos + files.length > props.maxPhotos) {
        alert(`Maximum of ${props.maxPhotos} photos allowed`);
        return;
    }

    files.forEach(file => {
        if (!file.type.startsWith('image/')) {
            alert(`File ${file.name} is not an image.`);
            return;
        }

        if (file.size > props.maxSize) {
            alert(`File ${file.name} is too large. Maximum size is ${Math.round(props.maxSize / 1024 / 1024)}MB.`);
            return;
        }

        photoFiles.value.push(file);
        
        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            photoPreviews.value.push({
                file: file,
                url: e.target.result
            });
        };
        reader.readAsDataURL(file);
    });

    emit('update:modelValue', photoFiles.value);
};

const removeNewPhoto = (index) => {
    photoFiles.value.splice(index, 1);
    photoPreviews.value.splice(index, 1);
    emit('update:modelValue', photoFiles.value);
};

const setPrimaryPhoto = (index) => {
    // Move the selected photo to the front
    const photo = photoFiles.value.splice(index, 1)[0];
    const preview = photoPreviews.value.splice(index, 1)[0];
    
    photoFiles.value.unshift(photo);
    photoPreviews.value.unshift(preview);
    
    emit('update:modelValue', photoFiles.value);
};

const removeExistingPhoto = (photoId) => {
    if (confirm('Are you sure you want to delete this photo?')) {
        // This will be handled by the parent component
        emit('remove-existing-photo', photoId);
    }
};

const setPrimaryExistingPhoto = (photoId) => {
    // This will be handled by the parent component
    emit('set-primary-existing-photo', photoId);
};

// Watch for changes in modelValue
watch(() => props.modelValue, (newValue) => {
    if (newValue !== photoFiles.value) {
        photoFiles.value = newValue || [];
    }
}, { deep: true });
</script>

<template>
    <div class="space-y-6">
        <!-- Existing Photos -->
        <div v-if="existingPhotos.length > 0">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">
                Current Photos ({{ existingPhotos.length }})
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div
                    v-for="(photo, index) in existingPhotos"
                    :key="photo.id"
                    class="relative group"
                >
                    <img
                        :src="photo.photo_url"
                        :alt="`Photo ${index + 1}`"
                        class="w-full h-32 object-cover rounded-lg"
                    />
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 rounded-lg flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex space-x-2">
                            <button
                                v-if="!photo.is_primary"
                                @click="setPrimaryExistingPhoto(photo.id)"
                                type="button"
                                class="px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700"
                            >
                                Set Primary
                            </button>
                            <button
                                @click="removeExistingPhoto(photo.id)"
                                type="button"
                                class="px-2 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700"
                            >
                                Remove
                            </button>
                        </div>
                    </div>
                    <div v-if="photo.is_primary" class="absolute top-2 left-2">
                        <span class="px-2 py-1 bg-primary-600 text-white text-xs rounded">
                            Primary
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Photo Upload Area -->
        <div
            :class="[
                'border-2 border-dashed rounded-lg p-6 text-center transition-colors',
                isDragOver
                    ? 'border-primary-400 dark:border-primary-500 bg-primary-50 dark:bg-primary-900/20'
                    : 'border-gray-300 dark:border-gray-600 hover:border-primary-400 dark:hover:border-primary-500'
            ]"
            @dragover="handleDragOver"
            @dragleave="handleDragLeave"
            @drop="handleDrop"
        >
            <input
                type="file"
                multiple
                accept="image/*"
                @change="handlePhotoUpload"
                class="hidden"
                :id="`photo-upload-${Math.random()}`"
            />
            <label
                :for="`photo-upload-${Math.random()}`"
                class="cursor-pointer"
            >
                <div class="mx-auto w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mb-4">
                    <span class="text-primary-600 dark:text-primary-400 text-2xl">📷</span>
                </div>
                <p class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    {{ existingPhotos.length > 0 ? 'Add More Photos' : 'Upload Photos' }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Click to select photos or drag and drop
                </p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                    PNG, JPG, GIF up to {{ Math.round(maxSize / 1024 / 1024) }}MB each
                </p>
                <p class="text-xs text-gray-400 dark:text-gray-500">
                    Maximum {{ maxPhotos }} photos total
                </p>
            </label>
        </div>

        <!-- New Photo Previews -->
        <div v-if="photoPreviews.length > 0">
            <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-4">
                New Photos ({{ photoPreviews.length }})
            </h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div
                    v-for="(preview, index) in photoPreviews"
                    :key="index"
                    class="relative group"
                >
                    <img
                        :src="preview.url"
                        :alt="`New Photo ${index + 1}`"
                        class="w-full h-32 object-cover rounded-lg"
                    />
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-200 rounded-lg flex items-center justify-center">
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex space-x-2">
                            <button
                                v-if="index !== 0"
                                @click="setPrimaryPhoto(index)"
                                type="button"
                                class="px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700"
                            >
                                Set Primary
                            </button>
                            <button
                                @click="removeNewPhoto(index)"
                                type="button"
                                class="px-2 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700"
                            >
                                Remove
                            </button>
                        </div>
                    </div>
                    <div v-if="index === 0" class="absolute top-2 left-2">
                        <span class="px-2 py-1 bg-primary-600 text-white text-xs rounded">
                            Primary
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upload Progress -->
        <div v-if="photoFiles.length > 0" class="text-sm text-gray-500 dark:text-gray-400">
            <p>
                {{ photoFiles.length }} photo(s) ready to upload
                <span v-if="existingPhotos.length > 0">
                    ({{ existingPhotos.length }} existing + {{ photoFiles.length }} new)
                </span>
            </p>
        </div>
    </div>
</template>




