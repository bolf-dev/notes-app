<script setup lang="ts">
import BaseButton from '@/components/base/BaseButton.vue'
import { PRIORITY_LABELS } from '@/constants/priority'
import type { Priority } from '@/types/note'

defineProps<{
  priority: Priority
}>()

defineEmits<{
  clear: []
}>()
</script>

<template>
  <div class="note-list-empty-filter">
    <p>No notes with {{ PRIORITY_LABELS[priority] }} priority.</p>
    <BaseButton @click="$emit('clear')">Show all</BaseButton>
  </div>
</template>

<style scoped>
.note-list-empty-filter {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: var(--space-3);
  padding: var(--space-6) var(--space-4);
  color: var(--color-text-secondary);
  text-align: center;
}
</style>
