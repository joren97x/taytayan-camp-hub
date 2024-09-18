<script setup>

import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref } from 'vue'

defineOptions({
    layout: AdminLayout
})

defineProps({
    ratings: Object
})

const filter = ref('')

const columns = [
    { name: 'user', label: 'User', align: 'center', field: 'user', sortable: true },
    { name: 'rating', align: 'center', label: 'Rating', field: 'rating', sortable: true },
    { name: 'review', align: 'center', label: 'review', field: 'review', sortable: true },
    { name: 'created_at', align: 'center', label: 'created_at', field: 'created_at', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    
    <Head title="Reviews" />
    <q-table
        class="my-sticky-header-column-table"
        flat
        title="Treats"
        :rows="ratings"
        :columns="columns"
        row-key="name"
        :filter="filter"
    >
        <template v-slot:body-cell-user="props">
            <q-td :props="props">
                {{ props.row.user.first_name + ' ' + props.row.user.last_name }}
            </q-td>
        </template>
        <template v-slot:body-cell-rating="props">
            <q-td :props="props">
                {{ props.row.rating }} star
            </q-td>
        </template>
        <template v-slot:body-cell-actions="props">
            <q-td :props="props">
                <q-btn no-caps color="primary">Button</q-btn>
                <q-btn no-caps color="red" icon="delete" class="q-ml-sm"></q-btn>
            </q-td>
        </template>
        <template v-slot:top>
            <p class="text-h6 q-pt-md">Rating and Reviews</p>
            <q-space />
            <q-input filled dense label="Search..." v-model="filter" class="q-mx-md" debounce="300" color="primary">
                <template v-slot:append>
                    <q-icon name="search" />
                </template>
            </q-input>
        </template>
    </q-table>
</template>