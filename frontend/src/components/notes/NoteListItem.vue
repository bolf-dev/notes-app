<script setup lang="ts">
import { RouterLink } from 'vue-router'

import type { Note } from '@/types/note'
import { formatDate } from '@/utils/formatDate'
import NotePriorityBadge from './NotePriorityBadge.vue'

defineProps<{
  note: Note
}>()

defineEmits<{
  delete: []
}>()
</script>

<template>
  <div class="note-list-item">
    <!-- The link to the open note gets the "selected" class and aria-current="page" from the router -->
    <RouterLink
      :to="{ name: 'note-edit', params: { id: note.id } }"
      class="note-list-item__link"
      :class="`note-list-item__link--${note.priority}`"
      exact-active-class="note-list-item__link--selected"
    >
      <span class="note-list-item__title">{{ note.title }}</span>
      <span class="note-list-item__excerpt">{{ note.content }}</span>
      <span class="note-list-item__meta">
        <NotePriorityBadge :priority="note.priority" />
        {{ formatDate(note.updatedAt) }}
      </span>
    </RouterLink>

    <!-- A button cannot be inside a link (invalid HTML), so it sits next to it, positioned in the corner -->
    <button
      type="button"
      class="note-list-item__delete"
      :aria-label="`Delete note “${note.title}”`"
      @click="$emit('delete')"
    >
      <svg
        viewBox="0 0 24 24"
        width="18"
        height="18"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        aria-hidden="true"
      >
        <path d="M3 6h18" />
        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
        <path d="M10 11v6" />
        <path d="M14 11v6" />
      </svg>
    </button>
  </div>
</template>

<style scoped>
.note-list-item {
  position: relative;
}

.note-list-item__link {
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

.note-list-item__link:hover {
  border-color: var(--color-border-hover);
}

/* Priority stripe on the left edge */
.note-list-item__link::before {
  content: '';
  position: absolute;
  inset: 0 auto 0 0;
  width: 5px;
  background: var(--priority-color);
}

.note-list-item__link--high {
  --priority-color: var(--color-priority-high);
}

.note-list-item__link--medium {
  --priority-color: var(--color-priority-medium);
}

.note-list-item__link--low {
  --priority-color: var(--color-priority-low);
}

.note-list-item__link--selected {
  border-color: var(--color-primary);
  background: var(--color-primary-lighter);
}

.note-list-item__title {
  /* Leave room for the delete button in the corner */
  padding-right: var(--space-6);
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

.note-list-item__delete {
  position: absolute;
  top: var(--space-1);
  right: var(--space-1);
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border: none;
  border-radius: 50%;
  background: transparent;
  color: var(--color-text-secondary);
  transition:
    color 0.15s,
    background-color 0.15s;
}

.note-list-item__delete:hover,
.note-list-item__delete:focus-visible {
  background: var(--color-error-light);
  color: var(--color-error-text);
}
</style>
