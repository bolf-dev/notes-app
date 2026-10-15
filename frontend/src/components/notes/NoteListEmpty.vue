<script setup lang="ts">
import BaseButton from '@/components/base/BaseButton.vue'

defineEmits<{
  create: []
}>()
</script>

<template>
  <div class="note-list-empty">
    <p>No notes yet.</p>
    <BaseButton @click="$emit('create')">+ Create first note</BaseButton>
  </div>
</template>

<style scoped>
.note-list-empty {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: var(--space-3);
  padding: var(--space-6) var(--space-4);
  color: var(--color-text-secondary);
  text-align: center;
}
</style>
