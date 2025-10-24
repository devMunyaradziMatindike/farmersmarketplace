<template>
  <svg 
    :class="className" 
    :width="size" 
    :height="size" 
    viewBox="0 0 24 24" 
    fill="none" 
    xmlns="http://www.w3.org/2000/svg"
  >
    <!-- Crown Base -->
    <path 
      d="M3 18h18v2H3v-2z" 
      :fill="fillColor"
    />
    
    <!-- Crown Main Body -->
    <path 
      d="M5 8l2-4h10l2 4v8H5V8z" 
      :fill="fillColor"
    />
    
    <!-- Crown Jewels -->
    <circle 
      cx="7" 
      cy="6" 
      r="1" 
      :fill="jewelColor"
    />
    <circle 
      cx="12" 
      cy="4" 
      r="1.5" 
      :fill="jewelColor"
    />
    <circle 
      cx="17" 
      cy="6" 
      r="1" 
      :fill="jewelColor"
    />
    
    <!-- Crown Details -->
    <path 
      d="M6 8h2v6H6V8z" 
      :fill="accentColor"
    />
    <path 
      d="M11 6h2v8h-2V6z" 
      :fill="accentColor"
    />
    <path 
      d="M16 8h2v6h-2V8z" 
      :fill="accentColor"
    />
    
    <!-- Crown Highlights -->
    <path 
      d="M5 8l2-4h10l2 4v2H5V8z" 
      :fill="highlightColor"
      opacity="0.3"
    />
    
    <!-- Crown Border -->
    <path 
      d="M5 8l2-4h10l2 4v8H5V8z" 
      :stroke="borderColor"
      stroke-width="0.5"
      fill="none"
    />
  </svg>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  size: {
    type: [String, Number],
    default: 24
  },
  className: {
    type: String,
    default: ''
  },
  variant: {
    type: String,
    default: 'default', // default, gold, silver, primary
    validator: (value) => ['default', 'gold', 'silver', 'primary'].includes(value)
  }
})

const fillColor = computed(() => {
  switch (props.variant) {
    case 'gold':
      return '#FFD700'
    case 'silver':
      return '#C0C0C0'
    case 'primary':
      return '#8B5CF6'
    default:
      return '#F59E0B'
  }
})

const jewelColor = computed(() => {
  switch (props.variant) {
    case 'gold':
      return '#FF6B6B'
    case 'silver':
      return '#4A90E2'
    case 'primary':
      return '#EC4899'
    default:
      return '#EF4444'
  }
})

const accentColor = computed(() => {
  switch (props.variant) {
    case 'gold':
      return '#FFA500'
    case 'silver':
      return '#E8E8E8'
    case 'primary':
      return '#A855F7'
    default:
      return '#F97316'
  }
})

const highlightColor = computed(() => {
  switch (props.variant) {
    case 'gold':
      return '#FFD700'
    case 'silver':
      return '#FFFFFF'
    case 'primary':
      return '#C084FC'
    default:
      return '#FBBF24'
  }
})

const borderColor = computed(() => {
  switch (props.variant) {
    case 'gold':
      return '#B8860B'
    case 'silver':
      return '#A0A0A0'
    case 'primary':
      return '#7C3AED'
    default:
      return '#EA580C'
  }
})
</script>
