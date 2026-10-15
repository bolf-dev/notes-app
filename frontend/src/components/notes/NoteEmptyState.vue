<script setup lang="ts">
import BaseButton from '@/components/base/BaseButton.vue'

defineEmits<{
  create: []
}>()
</script>

<template>
  <div class="note-empty-state">
    <p>Select a note from the list or create a new one.</p>
    <BaseButton @click="$emit('create')">+ New note</BaseButton>
  </div>
</template>

<style scoped>
.note-empty-state {
  height: 100%;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: var(--space-4);
  padding: var(--space-6);
  color: var(--color-text-secondary);
  text-align: center;
}
</style>
