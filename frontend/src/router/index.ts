import { createRouter, createWebHistory } from 'vue-router'

import NoteEmptyState from '@/components/notes/NoteEmptyState.vue'
import NoteCreateView from '@/views/NoteCreateView.vue'
import NoteEditView from '@/views/NoteEditView.vue'

// Routes control the detail panel; the notes list is always rendered by App.vue
export const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', name: 'home', component: NoteEmptyState },
    { path: '/notes/new', name: 'note-create', component: NoteCreateView },
    {
      // Only numeric ids match, anything else falls through to the redirect below
      path: '/notes/:id(\\d+)',
      name: 'note-edit',
      component: NoteEditView,
      props: (route) => ({ id: Number(route.params.id) }),
    },
    { path: '/:pathMatch(.*)*', redirect: '/' },
  ],
})
