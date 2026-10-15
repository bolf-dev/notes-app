<script setup lang="ts">
import { PRIORITY_LABELS } from '@/constants/priority'
import type { Priority } from '@/types/note'

defineProps<{
  priority: Priority
}>()
</script>

<template>
  <span class="priority-badge" :class="`priority-badge--${priority}`">
    {{ PRIORITY_LABELS[priority] }}
  </span>
</template>

<style scoped>
.priority-badge {
  display: inline-block;
  padding: 2px var(--space-2);
  border-radius: var(--radius-pill);
  font-size: 11px;
  font-weight: 700;
  line-height: 1.5;
  text-transform: uppercase;
  letter-spacing: 0.04em;
}

.priority-badge--high {
  color: var(--color-priority-high-text);
  background: var(--color-priority-high-bg);
}

.priority-badge--medium {
  color: var(--color-priority-medium-text);
  background: var(--color-priority-medium-bg);
}

.priority-badge--low {
  color: var(--color-priority-low-text);
  background: var(--color-priority-low-bg);
}
</style>
