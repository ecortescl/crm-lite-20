<script setup lang="ts">
import { inject } from 'vue'

const collapsible = inject<any>('collapsible')
</script>

<template>
  <div @click="collapsible?.toggle">
    <slot />
  </div>
</template>
