<script setup>

import { ref, watch } from 'vue'
import AdminLayout from './AdminLayout.vue'
import { router } from '@inertiajs/vue3'

defineOptions({
    layout: AdminLayout
})

const page = ref('Milkteas')
const txt = ref('')
const buttonToggles = [
    { label: 'Milkteas', value: 'milkteas' },
    { label: 'Categories', value: 'Categories' },
    { label: 'Modifier Groups', value: 'modifier-groups' },
    { label: 'Orders', value: 'orders' },
]

watch(page, () => {
    if(page.value === 'milkteas') {
        router.visit(`/admin/${page.value}`, {
            preserveState: true
        })
    }
    else {
        router.visit(`/admin/milkteas/${page.value}`, {
            preserveState: true
        })
    }
})

</script>

<template>
        
  
    <div>
        <q-toolbar class="shadow-2">
        <q-btn-toggle
            v-model="page"
            flat stretch
            no-caps
            toggle-color="primary"
            :options="buttonToggles"
        />
    </q-toolbar>
        <slot/>
    </div>
  
  </template>