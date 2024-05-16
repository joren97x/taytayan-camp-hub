<script setup>

import AdminSidebar from './AdminSidebar.vue'
import { Link } from '@inertiajs/vue3'

</script>

<template>
        
  
    <AdminSidebar>
        <q-toolbar class="shadow-2">
            <q-btn-group flat>
                <q-btn icon="menu"></q-btn>
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