<script setup lang="ts">
import { RouterLink, type RouteLocationRaw } from 'vue-router'

// With `to` the button navigates (renders a link), otherwise it is a regular <button>
withDefaults(
  defineProps<{
    to?: RouteLocationRaw
    type?: 'button' | 'submit'
    variant?: 'primary' | 'secondary' | 'danger'
  }>(),
  {
    to: undefined,
    type: 'button',
    variant: 'primary',
  },
)
</script>

<template>
  <RouterLink v-if="to" :to="to" class="base-button" :class="`base-button--${variant}`">
    <slot />
  </RouterLink>
  <button v-else :type="type" class="base-button" :class="`base-button--${variant}`">
    <slot />
  </button>
</template>

<style scoped>
.base-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-height: var(--size-tap-target);
  padding: 0 var(--space-5);
  border: 1px solid transparent;
  border-radius: var(--radius-pill);
  font-weight: 600;
  text-decoration: none;
  transition:
    background-color 0.15s,
    border-color 0.15s;
}

.base-button--primary {
  background: var(--color-primary);
  color: var(--color-on-primary);
}

.base-button--primary:hover {
  background: var(--color-primary-dark);
}

.base-button--secondary {
  border-color: var(--color-border);
  background: var(--color-surface);
  color: var(--color-text);
}

.base-button--secondary:hover {
  border-color: var(--color-border-hover);
}

.base-button--danger {
  background: var(--color-error-text);
  color: var(--color-on-primary);
}

.base-button--danger:hover {
  background: var(--color-priority-high-text);
}

.base-button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
