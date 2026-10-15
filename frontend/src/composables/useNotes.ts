import { ref, watch } from 'vue'

import { fetchNotes } from '@/api/notes'
import type { Note, Priority } from '@/types/note'

export function useNotes() {
  const notes = ref<Note[]>([])
  const priority = ref<Priority | null>(null) // null = all priorities
  const isLoading = ref(false)
  const hasLoaded = ref(false) // true after the first successful load
  const hasError = ref(false)

  let currentRequest: AbortController | null = null

  async function load(): Promise<void> {
    // Cancel the previous request, so an older response cannot overwrite a newer one
    currentRequest?.abort()
    const request = new AbortController()
    currentRequest = request

    isLoading.value = true
    hasError.value = false

    try {
      notes.value = await fetchNotes(priority.value, request.signal)
      hasLoaded.value = true
    } catch (error) {
      if (request.signal.aborted) {
        return
      }
      hasError.value = true
      console.error('Failed to load notes', error)
    } finally {
      // Only the latest request ends the loading state
      if (request === currentRequest) {
        isLoading.value = false
      }
    }
  }

  watch(priority, load)

  return { notes, priority, isLoading, hasLoaded, hasError, load }
}
