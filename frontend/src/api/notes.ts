import type { Note, NotePayload, Priority } from '@/types/note'
import { apiClient } from './client'

interface NoteListResponse {
  data: Note[]
}

export async function fetchNotes(priority: Priority | null, signal?: AbortSignal): Promise<Note[]> {
  const response = await apiClient.get<NoteListResponse>('/notes', {
    // undefined is left out of the URL, so "all" sends plain /notes
    params: { priority: priority ?? undefined },
    signal,
  })

  return response.data.data
}

export async function createNote(payload: NotePayload): Promise<Note> {
  const response = await apiClient.post<Note>('/notes', payload)

  return response.data
}
