<script setup lang="ts">
import { ref } from 'vue'

import BaseButton from '@/components/base/BaseButton.vue'
import NoteEmptyState from '@/components/notes/NoteEmptyState.vue'
import NoteList from '@/components/notes/NoteList.vue'
import NoteListEmpty from '@/components/notes/NoteListEmpty.vue'
import NoteListEmptyFilter from '@/components/notes/NoteListEmptyFilter.vue'
import NoteListError from '@/components/notes/NoteListError.vue'
import NoteListSkeleton from '@/components/notes/NoteListSkeleton.vue'
import NotePriorityFilter from '@/components/notes/NotePriorityFilter.vue'
import { useNotes } from '@/composables/useNotes'

const { notes, priority, isLoading, hasLoaded, hasError, load } = useNotes()

// Start loading right away (not in onMounted), so the first render already shows the loading state
load()

const selectedNoteId = ref<number | null>(null)

function startCreate(): void {
  // Switches the detail panel to the create form (step 5)
  selectedNoteId.value = null
}
</script>

<template>
  <div class="layout">
    <aside class="panel panel--list">
      <header class="list-header">
        <h1 class="list-header__title">Notes</h1>
        <BaseButton @click="startCreate">+ New note</BaseButton>
      </header>

      <NotePriorityFilter v-model="priority" class="list-filter" />

      <div class="list-content">
        <!-- Skeleton only on the first load; when the filter changes, the current list stays until new data arrive -->
        <NoteListSkeleton v-if="isLoading && !hasLoaded" />
        <NoteListError v-else-if="hasError" @retry="load" />
        <NoteListEmptyFilter
          v-else-if="notes.length === 0 && priority !== null"
          :priority="priority"
          @clear="priority = null"
        />
        <NoteListEmpty v-else-if="notes.length === 0" @create="startCreate" />
        <NoteList
          v-else
          :notes="notes"
          :selected-id="selectedNoteId"
          @select="selectedNoteId = $event"
        />
      </div>
    </aside>

    <main class="panel panel--detail">
      <NoteEmptyState @create="startCreate" />
    </main>
  </div>
</template>

<style scoped>
/* Layout: one panel on mobile, list + detail side by side from 768px */
.layout {
  height: 100%;
  display: grid;
  grid-template-columns: 1fr;
}

.panel {
  background: var(--color-surface);
  overflow-y: auto;
}

.panel--detail {
  display: none;
}

@media (min-width: 768px) {
  .layout {
    grid-template-columns: 380px 1fr;
    gap: var(--space-4);
    padding: var(--space-4);
  }

  .panel {
    border-radius: var(--radius-base);
  }

  .panel--detail {
    display: block;
  }
}

.list-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: var(--space-3);
  padding: var(--space-4);
}

.list-header__title {
  font-size: var(--font-size-xl);
  font-weight: 700;
}

.list-filter {
  margin: 0 var(--space-4) var(--space-3);
}

.list-content {
  padding: 0 var(--space-4) var(--space-4);
}
</style>
