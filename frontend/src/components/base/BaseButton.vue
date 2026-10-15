<script setup lang="ts">
import { RouterLink, type RouteLocationRaw } from 'vue-router'

// With `to` the button navigates (renders a link), otherwise it is a regular <button>
withDefaults(defineProps<{ to?: RouteLocationRaw; type?: 'button' | 'submit' }>(), {
  to: undefined,
  type: 'button',
})
</script>

<template>
  <RouterLink v-if="to" :to="to" class="base-button">
    <slot />
  </RouterLink>
  <button v-else :type="type" class="base-button">
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
  border: none;
  border-radius: var(--radius-pill);
  background: var(--color-primary);
  color: var(--color-on-primary);
  font-weight: 600;
  text-decoration: none;
  transition: background-color 0.15s;
}

.base-button:hover {
  background: var(--color-primary-dark);
}
</style>
