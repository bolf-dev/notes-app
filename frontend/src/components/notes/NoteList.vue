<script setup lang="ts">
import type { Note } from '@/types/note'
import NoteListItem from './NoteListItem.vue'

defineProps<{
  notes: Note[]
}>()
</script>

<template>
  <ul class="note-list">
    <li v-for="note in notes" :key="note.id">
      <NoteListItem :note="note" />
    </li>
  </ul>
</template>

<style scoped>
.note-list {
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
}
</style>
