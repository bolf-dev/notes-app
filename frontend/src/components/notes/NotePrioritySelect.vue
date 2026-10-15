<script setup lang="ts">
import { useId } from 'vue'

import { PRIORITIES, PRIORITY_LABELS } from '@/constants/priority'
import type { Priority } from '@/types/note'

const selected = defineModel<Priority>({ required: true })

// Form order from the lowest to the highest priority
const options = [...PRIORITIES].reverse()

// Shared name groups the radio buttons, so only one can be checked
const name = useId()
</script>

<template>
  <div class="priority-select">
    <label
      v-for="option in options"
      :key="option"
      class="priority-select__option"
      :class="{ 'priority-select__option--checked': selected === option }"
    >
      <input
        v-model="selected"
        type="radio"
        :name="name"
        :value="option"
        class="priority-select__input"
      />
      <span class="priority-select__dot" :class="`priority-select__dot--${option}`" />
      {{ PRIORITY_LABELS[option] }}
    </label>
  </div>
</template>

<style scoped>
.priority-select {
  display: inline-flex;
  align-self: flex-start;
  gap: var(--space-1);
  padding: var(--space-1);
  background: var(--color-surface-muted);
  border-radius: var(--radius-pill);
}

.priority-select__option {
  display: flex;
  align-items: center;
  gap: var(--space-2);
  min-height: 36px;
  padding: 0 var(--space-4);
  border-radius: var(--radius-pill);
  color: var(--color-text-secondary);
  font-size: var(--font-size-sm);
  font-weight: 600;
  cursor: pointer;
}

.priority-select__dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
}

.priority-select__dot--high {
  background: var(--color-priority-high);
}

.priority-select__dot--medium {
  background: var(--color-priority-medium);
}

.priority-select__dot--low {
  background: var(--color-priority-low);
}

.priority-select__option--checked {
  background: var(--color-surface);
  color: var(--color-text);
  box-shadow: 0 1px 3px rgb(0 0 0 / 12%);
}

/* Keyboard focus is shown on the whole option, the native radio is hidden */
.priority-select__option:has(:focus-visible) {
  outline: 2px solid var(--color-primary);
  outline-offset: 2px;
}

/* Visually hidden, but still focusable and announced by screen readers */
.priority-select__input {
  position: absolute;
  width: 1px;
  height: 1px;
  overflow: hidden;
  clip-path: inset(50%);
  white-space: nowrap;
}
</style>
