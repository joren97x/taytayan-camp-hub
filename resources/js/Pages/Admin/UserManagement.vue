<script setup>

import AdminSidebar from '@/Layouts/AdminSidebar.vue'
import { Head } from '@inertiajs/vue3'

defineOptions({
    layout: AdminSidebar
})

defineProps({
    users: Object
})

const columns = [
  { name: 'id', label: 'id', align: 'center', field: 'id', sortable: true },
  { name: 'photo', label: 'Photo', align: 'center', field: 'photo', sortable: true },
  { name: 'fullname', align: 'center', label: 'fullname', field: 'fullname', sortable: true },
  { name: 'email', align: 'center', label: 'email', field: 'email', sortable: true },
  { name: 'contact', align: 'center', label: 'contact', field: 'contact', sortable: true },
  { name: 'role', align: 'center', label: 'role', field: 'role', sortable: true },
  { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    {{ users }}
    <Head title="User Management" />
    <q-table
        class="my-sticky-header-column-table"
        flat
        title="Users"
        :rows="users"
        :columns="columns"
        row-key="name"
    >
        <template v-slot:body-cell-fullname="props">
            <q-td :props="props">
                {{ props.row.first_name + ' ' + props.row.last_name }}
            </q-td>
        </template>
        <template v-slot:body-cell-contact="props">
            <q-td :props="props">
                {{ props.row.phone_number }}
            </q-td>
        </template>
        <template v-slot:body-cell-actions="props">
            <q-td :props="props">
                <q-btn no-caps unelevated>Edit</q-btn>
                <q-btn no-caps unelevated>Delete</q-btn>
            </q-td>
        </template>
        <template v-slot:top>
            <p class="text-h6 q-pt-md">Users</p>
            <q-space />
                <q-input filled dense label="Search..." debounce="300" color="primary" v-model="filter">
                <template v-slot:append>
                    <q-icon name="search" />
                </template>
            </q-input>
        </template>
        
    </q-table>
</template>