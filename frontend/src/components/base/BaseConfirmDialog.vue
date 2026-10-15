<script setup lang="ts">
import { useId, useTemplateRef, watch } from 'vue'

import BaseButton from './BaseButton.vue'

const open = defineModel<boolean>('open', { required: true })

withDefaults(
  defineProps<{
    title: string
    confirmLabel?: string
    cancelLabel?: string
    danger?: boolean
    isConfirming?: boolean
  }>(),
  {
    confirmLabel: 'Confirm',
    cancelLabel: 'Cancel',
    danger: false,
    isConfirming: false,
  },
)

const emit = defineEmits<{
  confirm: []
}>()

const titleId = useId()
const dialog = useTemplateRef<HTMLDialogElement>('dialog')

// The native <dialog> handles the backdrop, Esc, focus trapping and returning focus when closed
watch(open, (isOpen) => {
  if (isOpen) {
    dialog.value?.showModal()
  } else {
    dialog.value?.close()
  }
})
</script>

<template>
  <!-- @close also fires on Esc, so the v-model stays in sync -->
  <dialog ref="dialog" class="confirm-dialog" :aria-labelledby="titleId" @close="open = false">
    <h2 :id="titleId" class="confirm-dialog__title">{{ title }}</h2>
    <div class="confirm-dialog__message">
      <slot />
    </div>
    <div class="confirm-dialog__actions">
      <BaseButton variant="secondary" @click="open = false">{{ cancelLabel }}</BaseButton>
      <BaseButton
        :variant="danger ? 'danger' : 'primary'"
        :disabled="isConfirming"
        @click="emit('confirm')"
      >
        {{ confirmLabel }}
      </BaseButton>
    </div>
  </dialog>
</template>

<style scoped>
.confirm-dialog {
  /* base.css resets margins – a modal dialog is centered by margin: auto */
  margin: auto;
  width: min(440px, calc(100% - 2 * var(--space-4)));
  padding: var(--space-5);
  border: none;
  border-radius: var(--radius-xl);
  background: var(--color-surface);
  color: var(--color-text);
}

.confirm-dialog::backdrop {
  background: rgb(0 0 0 / 60%);
}

.confirm-dialog__title {
  font-size: var(--font-size-lg);
  font-weight: 700;
}

.confirm-dialog__message {
  margin-top: var(--space-2);
  color: var(--color-text-secondary);
}

.confirm-dialog__actions {
  display: flex;
  justify-content: flex-end;
  gap: var(--space-3);
  margin-top: var(--space-5);
}

/* Mobile: bottom sheet with full-width buttons, the confirm action on top */
@media (max-width: 767px) {
  .confirm-dialog {
    margin: auto 0 0;
    width: 100%;
    max-width: 100%;
    border-radius: var(--radius-xl) var(--radius-xl) 0 0;
  }

  .confirm-dialog__actions {
    flex-direction: column-reverse;
  }
}
</style>
