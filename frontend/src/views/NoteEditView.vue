<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { toast } from 'vue-sonner'

import { isNotFoundError } from '@/api/errors'
import { fetchNote, updateNote } from '@/api/notes'
import BaseButton from '@/components/base/BaseButton.vue'
import NoteDetailHeader from '@/components/notes/NoteDetailHeader.vue'
import NoteForm from '@/components/notes/NoteForm.vue'
import { useNoteSubmit } from '@/composables/useNoteSubmit'
import { useNotes } from '@/composables/useNotes'
import type { Note, NotePayload } from '@/types/note'
import { formatDateTime } from '@/utils/formatDate'

const props = defineProps<{
  id: number
}>()

const router = useRouter()
const { load: reloadNotes } = useNotes()
const { errors, generalError, isSubmitting, submit } = useNoteSubmit()

const note = ref<Note | null>(null)
const status = ref<'loading' | 'loaded' | 'not-found' | 'error'>('loading')

async function loadNote(): Promise<void> {
  status.value = 'loading'

  try {
    note.value = await fetchNote(props.id)
    status.value = 'loaded'
  } catch (error) {
    if (isNotFoundError(error)) {
      status.value = 'not-found'
    } else {
      status.value = 'error'
      console.error('Failed to load note', error)
    }
  }
}

loadNote()

function handleSubmit(payload: NotePayload): Promise<void> {
  return submit(
    () => updateNote(props.id, payload),
    (updatedNote) => {
      note.value = updatedNote
      reloadNotes()
      toast.success('Note saved')
    },
  )
}
</script>

<template>
  <NoteDetailHeader title="Edit note" />

  <p v-if="status === 'loading'" class="note-edit-view__message">Loading note…</p>

  <div v-else-if="status === 'not-found'" class="note-edit-view__message">
    <p>Note not found.</p>
    <BaseButton :to="{ name: 'home' }" variant="secondary">Back to notes</BaseButton>
  </div>

  <div v-else-if="status === 'error'" class="note-edit-view__message" role="alert">
    <p>Note could not be loaded.</p>
    <BaseButton @click="loadNote">Try again</BaseButton>
  </div>

  <template v-else-if="note">
    <NoteForm
      :initial-values="{ title: note.title, content: note.content, priority: note.priority }"
      :errors="errors"
      :general-error="generalError"
      :is-submitting="isSubmitting"
      @submit="handleSubmit"
      @cancel="router.push({ name: 'home' })"
    >
      <template #meta>
        <p class="note-edit-view__dates">
          Created {{ formatDateTime(note.createdAt) }} · Updated
          {{ formatDateTime(note.updatedAt) }}
        </p>
      </template>
    </NoteForm>
  </template>
</template>

<style scoped>
.note-edit-view__message {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: var(--space-3);
  padding: var(--space-6) var(--space-5);
  color: var(--color-text-secondary);
  text-align: center;
}

.note-edit-view__dates {
  color: var(--color-text-secondary);
  font-size: var(--font-size-sm);
}
</style>
