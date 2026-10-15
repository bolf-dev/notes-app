<script setup lang="ts">
import { computed, nextTick, useTemplateRef, watch } from 'vue'
import { RouterView, useRoute } from 'vue-router'
import { Toaster } from 'vue-sonner'

import BaseButton from '@/components/base/BaseButton.vue'
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

// On mobile only one panel is visible: the list on "/", the detail when a note is open or being created
const route = useRoute()
const isDetailOpen = computed(() => route.name === 'note-create' || route.name === 'note-edit')

// A hidden panel loses its scroll position – remember it, so "Back" returns to the same place in the list
const listPanel = useTemplateRef<HTMLElement>('listPanel')
let listScrollTop = 0

watch(isDetailOpen, async (isOpen) => {
  if (!listPanel.value) {
    return
  }
  if (isOpen) {
    listScrollTop = listPanel.value.scrollTop
  } else {
    await nextTick()
    listPanel.value.scrollTop = listScrollTop
  }
})
</script>

<template>
  <div class="layout" :class="{ 'layout--detail-open': isDetailOpen }">
    <aside ref="listPanel" class="panel panel--list">
      <header class="list-header">
        <h1 class="list-header__title">Notes</h1>
        <BaseButton :to="{ name: 'note-create' }">+ New note</BaseButton>
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
        <NoteListEmpty v-else-if="notes.length === 0" />
        <NoteList v-else :notes="notes" />
      </div>
    </aside>

    <main class="panel panel--detail">
      <!-- key: switching to another note creates a fresh view (new form state) instead of reusing the old one -->
      <RouterView :key="route.path" />
    </main>
  </div>

  <!-- Short confirmations after actions, triggered anywhere via toast.success(...) -->
  <Toaster position="bottom-right" rich-colors :duration="3000" />
</template>

<style scoped>
/* Layout: one panel on mobile, list + detail side by side from 768px */
.layout {
  height: 100%;
  display: grid;
  grid-template-columns: 1fr;
  /* Row fills the viewport height, so the panels scroll inside instead of the whole page */
  grid-template-rows: minmax(0, 1fr);
}

.panel {
  background: var(--color-surface);
  overflow-y: auto;
}

.panel--detail,
.layout--detail-open .panel--list {
  display: none;
}

.layout--detail-open .panel--detail {
  display: block;
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

  /* Desktop: both panels are always visible */
  .panel--list,
  .panel--detail,
  .layout--detail-open .panel--list {
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
