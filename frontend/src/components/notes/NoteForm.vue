<script setup lang="ts">
import { nextTick, reactive, useId, useTemplateRef, watch } from 'vue'

import type { FieldErrors } from '@/api/errors'
import BaseButton from '@/components/base/BaseButton.vue'
import type { NotePayload } from '@/types/note'
import NotePrioritySelect from './NotePrioritySelect.vue'

const props = withDefaults(
  defineProps<{
    initialValues?: NotePayload
    errors?: FieldErrors
    generalError?: string | null
    isSubmitting?: boolean
  }>(),
  {
    initialValues: () => ({ title: '', content: '', priority: 'medium' }),
    errors: () => ({}),
    generalError: null,
    isSubmitting: false,
  },
)

const emit = defineEmits<{
  submit: [payload: NotePayload]
  cancel: []
}>()

// Local copy – the form edits its own values, the parent only gets them on submit
const form = reactive<NotePayload>({ ...props.initialValues })

const id = useId()
const titleInput = useTemplateRef<HTMLInputElement>('titleInput')
const contentInput = useTemplateRef<HTMLTextAreaElement>('contentInput')

// After a failed submit move the cursor to the first invalid field (also scrolls it into view)
watch(
  () => props.errors,
  async (errors) => {
    await nextTick()
    if (errors.title) {
      titleInput.value?.focus()
    } else if (errors.content) {
      contentInput.value?.focus()
    }
  },
)
</script>

<template>
  <form class="note-form" novalidate @submit.prevent="emit('submit', { ...form })">
    <div class="note-form__field">
      <label :for="`${id}-title`" class="note-form__label">Title</label>
      <input
        :id="`${id}-title`"
        ref="titleInput"
        v-model="form.title"
        type="text"
        maxlength="255"
        class="note-form__input"
        :aria-invalid="!!errors.title"
        :aria-describedby="errors.title ? `${id}-title-error` : undefined"
      />
      <p v-if="errors.title" :id="`${id}-title-error`" class="note-form__error">
        {{ errors.title }}
      </p>
    </div>

    <div class="note-form__field">
      <label :for="`${id}-content`" class="note-form__label">Content</label>
      <textarea
        :id="`${id}-content`"
        ref="contentInput"
        v-model="form.content"
        rows="10"
        maxlength="10000"
        class="note-form__input note-form__input--textarea"
        :aria-invalid="!!errors.content"
        :aria-describedby="errors.content ? `${id}-content-error` : undefined"
      />
      <p v-if="errors.content" :id="`${id}-content-error`" class="note-form__error">
        {{ errors.content }}
      </p>
    </div>

    <fieldset class="note-form__field">
      <legend class="note-form__label">Priority</legend>
      <NotePrioritySelect v-model="form.priority" />
      <p v-if="errors.priority" class="note-form__error">{{ errors.priority }}</p>
    </fieldset>

    <p v-if="generalError" class="note-form__general-error" role="alert">{{ generalError }}</p>

    <div class="note-form__actions">
      <BaseButton variant="secondary" class="note-form__cancel" @click="emit('cancel')">
        Cancel
      </BaseButton>
      <BaseButton type="submit" class="note-form__save" :disabled="isSubmitting">
        {{ isSubmitting ? 'Saving…' : 'Save' }}
      </BaseButton>
    </div>
  </form>
</template>

<style scoped>
.note-form {
  display: flex;
  flex-direction: column;
  gap: var(--space-5);
  /* Full panel width, capped only on very large screens for readable line length */
  max-width: 1000px;
  padding: var(--space-5);
}

.note-form__field {
  display: flex;
  flex-direction: column;
  gap: var(--space-2);
  padding: 0;
  border: none;
}

.note-form__label {
  padding: 0;
  font-weight: 600;
}

.note-form__input {
  width: 100%;
  padding: var(--space-3) var(--space-4);
  border: 1px solid var(--color-border);
  border-radius: var(--radius-base);
  background: var(--color-surface);
  transition: border-color 0.15s;
}

.note-form__input:hover {
  border-color: var(--color-border-hover);
}

.note-form__input:focus {
  outline: none;
  border-color: var(--color-primary);
  box-shadow: 0 0 0 3px var(--color-primary-lighter);
}

.note-form__input[aria-invalid='true'] {
  border-color: var(--color-error);
}

.note-form__input--textarea {
  resize: vertical;
}

.note-form__error {
  color: var(--color-error-text);
  font-size: var(--font-size-sm);
}

.note-form__general-error {
  padding: var(--space-3) var(--space-4);
  border-radius: var(--radius-base);
  background: var(--color-error-light);
  color: var(--color-error-text);
}

.note-form__actions {
  display: flex;
  justify-content: flex-end;
  gap: var(--space-3);
}

/* Mobile: Save sticks to the bottom of the screen, Cancel is replaced by "← Back" */
@media (max-width: 767px) {
  .note-form__actions {
    position: sticky;
    bottom: 0;
    margin: 0 calc(-1 * var(--space-5)) calc(-1 * var(--space-5));
    padding: var(--space-3) var(--space-5);
    border-top: 1px solid var(--color-border);
    background: var(--color-surface);
  }

  .note-form__cancel {
    display: none;
  }

  .note-form__save {
    flex: 1;
  }
}
</style>
