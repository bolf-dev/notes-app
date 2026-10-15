import { ref } from 'vue'

import { fetchNotes } from '@/api/notes'
import type { Note } from '@/types/note'

export function useNotes() {
  const notes = ref<Note[]>([])
  const isLoading = ref(false)
  const hasError = ref(false)

  async function load(): Promise<void> {
    isLoading.value = true
    hasError.value = false

    try {
      notes.value = await fetchNotes()
    } catch (error) {
      hasError.value = true
      console.error('Failed to load notes', error)
    } finally {
      isLoading.value = false
    }
  }

  return { notes, isLoading, hasError, load }
}
