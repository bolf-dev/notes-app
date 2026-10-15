import type { Note } from '@/types/note'
import { apiClient } from './client'

interface NoteListResponse {
  data: Note[]
}

export async function fetchNotes(): Promise<Note[]> {
  const response = await apiClient.get<NoteListResponse>('/notes')

  return response.data.data
}
