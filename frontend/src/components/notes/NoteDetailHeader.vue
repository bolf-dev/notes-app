<script setup lang="ts">
import { RouterLink } from 'vue-router'

defineProps<{
  title: string
}>()
</script>

<template>
  <header class="note-detail-header">
    <!-- Back to the list – only needed on mobile, where the list is hidden -->
    <RouterLink :to="{ name: 'home' }" class="note-detail-header__back">← Back</RouterLink>
    <h2 class="note-detail-header__title">{{ title }}</h2>
  </header>
</template>

<style scoped>
.note-detail-header {
  display: flex;
  align-items: center;
  gap: var(--space-3);
  padding: var(--space-4) var(--space-5);
  border-bottom: 1px solid var(--color-border);
}

.note-detail-header__back {
  display: inline-flex;
  align-items: center;
  min-height: var(--size-tap-target);
  color: var(--color-primary);
  font-weight: 600;
  text-decoration: none;
}

.note-detail-header__title {
  font-size: var(--font-size-lg);
  font-weight: 700;
}

@media (min-width: 768px) {
  .note-detail-header__back {
    display: none;
  }
}
</style>
