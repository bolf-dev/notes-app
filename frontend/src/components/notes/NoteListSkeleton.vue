<template>
  <ul class="note-list-skeleton" aria-busy="true" aria-label="Loading notes">
    <li v-for="item in 3" :key="item" class="note-list-skeleton__item">
      <span class="note-list-skeleton__line note-list-skeleton__line--title" />
      <span class="note-list-skeleton__line" />
      <span class="note-list-skeleton__line note-list-skeleton__line--short" />
    </li>
  </ul>
</template>

<style scoped>
.note-list-skeleton {
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
}

.note-list-skeleton__item {
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
  padding: var(--space-3) var(--space-4);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-base);
}

.note-list-skeleton__line {
  height: 12px;
  border-radius: var(--radius-pill);
  background: var(--color-surface-muted);
  animation: pulse 1.2s ease-in-out infinite;
}

.note-list-skeleton__line--title {
  width: 60%;
  height: 16px;
}

.note-list-skeleton__line--short {
  width: 35%;
}

@keyframes pulse {
  50% {
    opacity: 0.5;
  }
}

@media (prefers-reduced-motion: reduce) {
  .note-list-skeleton__line {
    animation: none;
  }
}
</style>
