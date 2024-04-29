<script setup>

import { ref } from 'vue'
import AdminLayout from './AdminLayout.vue'

defineOptions({
    layout: AdminLayout
})

const page = ref('group modifiers')
const buttonToggles = [
    { label: 'Orders', value: 'Orders' },
    { label: 'Items', value: 'Items' },
    { label: 'Categories', value: 'Categories' },
    { label: 'Modifier Groups', value: 'Modifier Groups' },
]

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