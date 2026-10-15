<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { toast } from 'vue-sonner'

import { getValidationErrors, type FieldErrors } from '@/api/errors'
import { createNote } from '@/api/notes'
import NoteDetailHeader from '@/components/notes/NoteDetailHeader.vue'
import NoteForm from '@/components/notes/NoteForm.vue'
import { useNotes } from '@/composables/useNotes'
import type { NotePayload } from '@/types/note'

const router = useRouter()
const { load: reloadNotes } = useNotes()

const errors = ref<FieldErrors>({})
const generalError = ref<string | null>(null)
const isSubmitting = ref(false)

async function handleSubmit(payload: NotePayload): Promise<void> {
  isSubmitting.value = true
  errors.value = {}
  generalError.value = null

  try {
    const note = await createNote(payload)
    reloadNotes()
    toast.success('Note created')
    // The new note opens in edit mode
    await router.push({ name: 'note-edit', params: { id: note.id } })
  } catch (error) {
    const validationErrors = getValidationErrors(error)
    if (validationErrors) {
      errors.value = validationErrors
    } else {
      generalError.value = 'Note could not be saved. Please try again.'
      console.error('Failed to create note', error)
    }
  } finally {
    isSubmitting.value = false
  }
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
