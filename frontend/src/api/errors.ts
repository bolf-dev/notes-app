import { isAxiosError } from 'axios'

// Field name → error message, e.g. { title: 'This value should not be blank.' }
export type FieldErrors = Record<string, string>

interface ValidationProblem {
  violations?: { propertyPath: string; title: string }[]
}

// Field errors from a 422 response (Symfony validation, RFC 9457), or null for any other error
export function getValidationErrors(error: unknown): FieldErrors | null {
  if (!isAxiosError<ValidationProblem>(error) || error.response?.status !== 422) {
    return null
  }

  const errors: FieldErrors = {}
  for (const violation of error.response.data.violations ?? []) {
    // Keep the first message per field
    errors[violation.propertyPath] ??= violation.title
  }

  return errors
}
