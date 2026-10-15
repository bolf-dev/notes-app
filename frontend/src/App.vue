<script setup lang="ts">
import { ref } from 'vue'

import BaseButton from '@/components/base/BaseButton.vue'
import NoteEmptyState from '@/components/notes/NoteEmptyState.vue'
import NoteList from '@/components/notes/NoteList.vue'
import NotePriorityFilter from '@/components/notes/NotePriorityFilter.vue'
import type { Note, Priority } from '@/types/note'

// Static placeholder data – replaced by API data in step 2
const notes: Note[] = [
  {
    id: 4,
    title: 'Call patient about elevated potassium',
    content:
      'Lab result from this morning: K+ 6.1 mmol/l. Call the patient today, arrange a repeat test tomorrow at 7:30 and review the ACE inhibitor dose.',
    priority: 'high',
    createdAt: '2026-10-14T15:42:00+02:00',
    updatedAt: '2026-10-14T15:42:00+02:00',
  },
  {
    id: 3,
    title: 'Reply to online consultations',
    content:
      'Three unanswered patient questions in the online consultation inbox – rash after antibiotics, sick leave extension, vaccination schedule. Reply by end of day.',
    priority: 'medium',
    createdAt: '2026-10-13T08:05:00+02:00',
    updatedAt: '2026-10-13T09:10:00+02:00',
  },
  {
    id: 2,
    title: 'Renew chronic prescriptions before holidays',
    content: 'Check e-prescriptions for diabetic patients that expire in December.',
    priority: 'medium',
    createdAt: '2026-10-12T11:20:00+02:00',
    updatedAt: '2026-10-12T11:20:00+02:00',
  },
  {
    id: 1,
    title: 'Update office hours in the online booking profile',
    content: 'Add the new Friday afternoon slots for online booking.',
    priority: 'low',
    createdAt: '2026-10-10T18:03:00+02:00',
    updatedAt: '2026-10-10T18:03:00+02:00',
  },
]

const priorityFilter = ref<Priority | null>(null)
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

      <NotePriorityFilter v-model="priorityFilter" class="list-filter" />

      <NoteList
        :notes="notes"
        :selected-id="selectedNoteId"
        class="list-notes"
        @select="selectedNoteId = $event"
      />
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

.list-notes {
  padding: 0 var(--space-4) var(--space-4);
}
</style>
