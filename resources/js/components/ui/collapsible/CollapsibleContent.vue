<script setup lang="ts">
import { inject, computed } from 'vue'

const collapsible = inject<any>('collapsible')
const isOpen = computed(() => collapsible?.isOpen.value)
</script>

<template>
  <div v-if="isOpen" class="overflow-hidden">
    <slot />
  </div>
</template>
