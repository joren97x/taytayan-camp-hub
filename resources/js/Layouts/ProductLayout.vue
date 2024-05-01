<script setup>

import { ref, watch } from 'vue'
import AdminLayout from './AdminLayout.vue'
import { router } from '@inertiajs/vue3'

defineOptions({
    layout: AdminLayout
})

const page = ref('Product')
const txt = ref('')
const buttonToggles = [
    { label: 'Products', value: 'products' },
    { label: 'Categories', value: 'categories' },
    { label: 'Modifier Groups', value: 'modifier-groups' },
    { label: 'Orders', value: 'orders' },
    { label: 'Reviews', value: 'reviews' },
]

watch(page, () => {
    if(page.value === 'reviews') {
        router.visit(`/admin/product/${page.value}`, {
            preserveState: true
        })
    }
    else {
        router.visit(`/admin/${page.value}`, {
            preserveState: true,
            onFinish: (err) => {
                console.log(err)
            }
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