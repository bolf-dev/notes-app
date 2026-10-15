<script setup lang="ts">
import NoteDetailHeader from '@/components/notes/NoteDetailHeader.vue'

defineProps<{
  id: number
}>()
</script>

<template>
  <NoteDetailHeader title="Edit note" />
  <!-- Loading the note and the form come in step 6 -->
</template>
