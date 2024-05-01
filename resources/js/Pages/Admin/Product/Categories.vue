<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import MilkteaLayout from '@/Layouts/ProductLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

defineOptions({
    layout: AdminLayout
})

const columns = [
    { name: 'name', label: 'Name', align: 'center', field: 'name', sortable: true },
    { name: 'items', align: 'center', label: 'Items', field: 'items', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

const rows = [
    {
        name: 'Milktea',
        items: 'item 1, item 2, item 3'
    },
    {
        name: 'Drinks',
        items: 'item 1, item 2, item 3'
    },
]

</script>

<template>
    
    <Head title="Categories" />
    <MilkteaLayout>
        <div class="q-pa-md">
            <q-table
                class="my-sticky-header-column-table"
                flat
                title="Treats"
                :rows="rows"
                :columns="columns"
                row-key="name"
            >
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <q-btn no-caps unelevated>Edit</q-btn>
                        <q-btn no-caps unelevated>Delete</q-btn>
                    </q-td>
                </template>
                <template v-slot:top>
                    <p class="text-h6 q-pt-md">Categories</p>
                    <q-space />
                        <q-input filled dense label="Search..." debounce="300" color="primary" v-model="filter">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                    <Link :href="route('admin.category.create')">
                        <q-btn class="q-ml-sm" color="primary" no-caps label="New Category" />
                    </Link>
                </template>
                
            </q-table>
        </div>
    </MilkteaLayout>
</template>
