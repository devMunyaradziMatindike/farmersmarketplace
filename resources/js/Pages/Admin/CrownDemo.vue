<template>
  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">
        Crown Icon Variants 👑
      </h1>
      
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Gold Variant -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
          <div class="flex flex-col items-center">
            <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center mb-4">
              <CrownIcon :size="32" variant="gold" class="text-white" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Gold Crown</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 text-center">Luxurious gold crown with red jewels</p>
          </div>
        </div>

        <!-- Silver Variant -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
          <div class="flex flex-col items-center">
            <div class="w-16 h-16 bg-gradient-to-br from-gray-400 to-gray-600 rounded-xl flex items-center justify-center mb-4">
              <CrownIcon :size="32" variant="silver" class="text-white" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Silver Crown</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 text-center">Elegant silver crown with blue jewels</p>
          </div>
        </div>

        <!-- Primary Variant -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
          <div class="flex flex-col items-center">
            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-4">
              <CrownIcon :size="32" variant="primary" class="text-white" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Primary Crown</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 text-center">Royal purple crown with pink jewels</p>
          </div>
        </div>

        <!-- Default Variant -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
          <div class="flex flex-col items-center">
            <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center mb-4">
              <CrownIcon :size="32" variant="default" class="text-white" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Default Crown</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 text-center">Classic orange crown with red jewels</p>
          </div>
        </div>
      </div>

      <!-- Size Examples -->
      <div class="mt-12">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Size Examples</h2>
        <div class="flex items-center gap-6 flex-wrap">
          <div class="flex flex-col items-center">
            <CrownIcon :size="16" variant="gold" class="text-yellow-600" />
            <span class="text-xs text-gray-600 dark:text-gray-400 mt-2">16px</span>
          </div>
          <div class="flex flex-col items-center">
            <CrownIcon :size="24" variant="gold" class="text-yellow-600" />
            <span class="text-xs text-gray-600 dark:text-gray-400 mt-2">24px</span>
          </div>
          <div class="flex flex-col items-center">
            <CrownIcon :size="32" variant="gold" class="text-yellow-600" />
            <span class="text-xs text-gray-600 dark:text-gray-400 mt-2">32px</span>
          </div>
          <div class="flex flex-col items-center">
            <CrownIcon :size="48" variant="gold" class="text-yellow-600" />
            <span class="text-xs text-gray-600 dark:text-gray-400 mt-2">48px</span>
          </div>
          <div class="flex flex-col items-center">
            <CrownIcon :size="64" variant="gold" class="text-yellow-600" />
            <span class="text-xs text-gray-600 dark:text-gray-400 mt-2">64px</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import CrownIcon from '@/Components/Icons/CrownIcon.vue';
</script>

