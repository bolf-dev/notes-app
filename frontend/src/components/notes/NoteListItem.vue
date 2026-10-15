<script setup lang="ts">
import { RouterLink } from 'vue-router'

import type { Note } from '@/types/note'
import { formatDate } from '@/utils/formatDate'
import NotePriorityBadge from './NotePriorityBadge.vue'

defineProps<{
  note: Note
}>()
</script>

<template>
  <!-- The link to the open note gets the "selected" class and aria-current="page" from the router -->
  <RouterLink
    :to="{ name: 'note-edit', params: { id: note.id } }"
    class="note-list-item"
    :class="`note-list-item--${note.priority}`"
    exact-active-class="note-list-item--selected"
  >
    <span class="note-list-item__title">{{ note.title }}</span>
    <span class="note-list-item__excerpt">{{ note.content }}</span>
    <span class="note-list-item__meta">
      <NotePriorityBadge :priority="note.priority" />
      {{ formatDate(note.updatedAt) }}
    </span>
  </RouterLink>
</template>

<style scoped>
.note-list-item {
  position: relative;
  display: flex;
  flex-direction: column;
  gap: var(--space-1);
  width: 100%;
  padding: var(--space-3) var(--space-4) var(--space-3) var(--space-5);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-base);
  background: var(--color-surface);
  color: inherit;
  text-decoration: none;
  overflow: hidden;
  transition: border-color 0.15s;
}

.note-list-item:hover {
  border-color: var(--color-border-hover);
}

/* Priority stripe on the left edge */
.note-list-item::before {
  content: '';
  position: absolute;
  inset: 0 auto 0 0;
  width: 5px;
  background: var(--priority-color);
}

.note-list-item--high {
  --priority-color: var(--color-priority-high);
}

.note-list-item--medium {
  --priority-color: var(--color-priority-medium);
}

.note-list-item--low {
  --priority-color: var(--color-priority-low);
}

.note-list-item--selected {
  border-color: var(--color-primary);
  background: var(--color-primary-lighter);
}

.note-list-item__title {
  font-weight: 700;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.note-list-item__excerpt {
  display: -webkit-box;
  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  line-clamp: 2;
  overflow: hidden;
  color: var(--color-text-secondary);
  font-size: var(--font-size-sm);
}

.note-list-item__meta {
  display: flex;
  align-items: center;
  gap: var(--space-2);
  color: var(--color-text-secondary);
  font-size: var(--font-size-sm);
}
</style>
