// Mirrors the API contract (NoteResponse DTO on the backend)
export type Priority = 'low' | 'medium' | 'high'

export interface Note {
  id: number
  title: string
  content: string
  priority: Priority
  createdAt: string // ISO 8601
  updatedAt: string // ISO 8601
}
