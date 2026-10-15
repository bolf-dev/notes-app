<script setup lang="ts">
import { PRIORITIES, PRIORITY_LABELS } from '@/constants/priority'
import type { Priority } from '@/types/note'

// null = all priorities (no filter)
const selected = defineModel<Priority | null>({ required: true })

const options: { value: Priority | null; label: string }[] = [
  { value: null, label: 'All' },
  ...PRIORITIES.map((priority) => ({ value: priority, label: PRIORITY_LABELS[priority] })),
]
</script>

<template>
  <div class="priority-filter" role="group" aria-label="Filter by priority">
    <button
      v-for="option in options"
      :key="option.value ?? 'all'"
      type="button"
      class="priority-filter__tab"
      :class="{ 'priority-filter__tab--active': selected === option.value }"
      :aria-pressed="selected === option.value"
      @click="selected = option.value"
    >
      {{ option.label }}
    </button>
  </div>
</template>

<style scoped>
.priority-filter {
  display: flex;
  gap: var(--space-1);
  padding: var(--space-1);
  background: var(--color-surface-muted);
  border-radius: var(--radius-pill);
}

.priority-filter__tab {
  flex: 1;
  min-height: 36px;
  border: none;
  border-radius: var(--radius-pill);
  background: transparent;
  color: var(--color-text-secondary);
  font-size: var(--font-size-sm);
  font-weight: 600;
}

.priority-filter__tab--active {
  background: var(--color-surface);
  color: var(--color-text);
  box-shadow: 0 1px 3px rgb(0 0 0 / 12%);
}
</style>
