<script setup>

import { ref, watch } from 'vue'
import AdminSidebar from './AdminSidebar.vue'
import { router } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'

const page = ref('Product')
const navigations = [
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
        
    <AdminSidebar>
        <q-toolbar class="shadow-2">
            <q-btn-group flat>
                <q-btn icon="menu"></q-btn>
                <q-btn 
                    no-caps
                    v-for="(navigation, index) in navigations" 
                    :key="index"
                    @click="page = navigation.value"
                    :label="navigation.label" 
                />
            </q-btn-group>
            <q-space></q-space>
            <q-btn-group flat>
                <q-btn icon="dark_mode" @click="$q.dark.toggle()"></q-btn>
                <Link :href="route('logout')" method="post">
                    <q-btn icon="logout" flat></q-btn>
                </Link>
            </q-btn-group>
        </q-toolbar>
        <slot/>
    </AdminSidebar>
  
  </template>