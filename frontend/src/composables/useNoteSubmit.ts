import { ref } from 'vue'

import { getValidationErrors, type FieldErrors } from '@/api/errors'

// Shared submit handling of the create and edit form: saving flag, 422 field errors, other errors
export function useNoteSubmit() {
  const errors = ref<FieldErrors>({})
  const generalError = ref<string | null>(null)
  const isSubmitting = ref(false)

  async function submit<T>(
    request: () => Promise<T>,
    onSuccess: (result: T) => void | Promise<void>,
  ): Promise<void> {
    isSubmitting.value = true
    errors.value = {}
    generalError.value = null

    try {
      const result = await request()
      await onSuccess(result)
    } catch (error) {
      const validationErrors = getValidationErrors(error)
      if (validationErrors) {
        errors.value = validationErrors
      } else {
        generalError.value = 'Note could not be saved. Please try again.'
        console.error('Failed to save note', error)
      }
    } finally {
      isSubmitting.value = false
    }
  }

  return { errors, generalError, isSubmitting, submit }
}
