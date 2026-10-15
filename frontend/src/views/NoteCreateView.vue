<script setup lang="ts">
import { useRouter } from 'vue-router'
import { toast } from 'vue-sonner'

import { createNote } from '@/api/notes'
import NoteDetailHeader from '@/components/notes/NoteDetailHeader.vue'
import NoteForm from '@/components/notes/NoteForm.vue'
import { useNoteSubmit } from '@/composables/useNoteSubmit'
import { useNotes } from '@/composables/useNotes'
import type { NotePayload } from '@/types/note'

const router = useRouter()
const { load: reloadNotes } = useNotes()
const { errors, generalError, isSubmitting, submit } = useNoteSubmit()

function handleSubmit(payload: NotePayload): Promise<void> {
  return submit(
    () => createNote(payload),
    async (note) => {
      reloadNotes()
      toast.success('Note created')
      // The new note opens in edit mode
      await router.push({ name: 'note-edit', params: { id: note.id } })
    },
  )
}
</script>

<template>
  <NoteDetailHeader title="New note" />
  <NoteForm
    :errors="errors"
    :general-error="generalError"
    :is-submitting="isSubmitting"
    @submit="handleSubmit"
    @cancel="router.push({ name: 'home' })"
  />
</template>
