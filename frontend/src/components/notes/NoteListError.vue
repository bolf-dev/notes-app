<script setup lang="ts">
import BaseButton from '@/components/base/BaseButton.vue'

defineEmits<{
  retry: []
}>()
</script>

<template>
  <div class="note-list-error" role="alert">
    <p>Notes could not be loaded.</p>
    <BaseButton @click="$emit('retry')">Try again</BaseButton>
  </div>
</template>

<style scoped>
.note-list-error {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: var(--space-3);
  padding: var(--space-5) var(--space-4);
  border-radius: var(--radius-base);
  background: var(--color-error-light);
  color: var(--color-error-text);
  text-align: center;
}
</style>
