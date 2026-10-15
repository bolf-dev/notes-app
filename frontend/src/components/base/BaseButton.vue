<script setup lang="ts">
withDefaults(defineProps<{ type?: 'button' | 'submit' }>(), { type: 'button' })
</script>

<template>
  <button :type="type" class="base-button">
    <slot />
  </button>
</template>

<style scoped>
.base-button {
  min-height: var(--size-tap-target);
  padding: 0 var(--space-5);
  border: none;
  border-radius: var(--radius-pill);
  background: var(--color-primary);
  color: var(--color-on-primary);
  font-weight: 600;
  transition: background-color 0.15s;
}

.base-button:hover {
  background: var(--color-primary-dark);
}
</style>
