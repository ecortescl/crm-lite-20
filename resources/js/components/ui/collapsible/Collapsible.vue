<script setup lang="ts">
import { ref, provide } from 'vue'

const props = withDefaults(
  defineProps<{
    defaultOpen?: boolean
    disabled?: boolean
  }>(),
  {
    defaultOpen: false,
    disabled: false,
  }
)

const isOpen = ref(props.defaultOpen)

const toggle = () => {
  if (!props.disabled) {
    isOpen.value = !isOpen.value
  }
}

provide('collapsible', {
  isOpen,
  toggle,
})
</script>

<template>
  <div :data-state="isOpen ? 'open' : 'closed'" :data-disabled="disabled ? '' : undefined">
    <slot />
  </div>
</template>
