import type { Priority } from '@/types/note'

// Ordered from most to least important (order of filter tabs)
export const PRIORITIES: readonly Priority[] = ['high', 'medium', 'low']

export const PRIORITY_LABELS: Record<Priority, string> = {
  high: 'High',
  medium: 'Medium',
  low: 'Low',
}
