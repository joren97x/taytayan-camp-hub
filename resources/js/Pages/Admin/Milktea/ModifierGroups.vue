<script setup>

import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import MilkteaLayout from '@/Layouts/MilkteaLayout.vue'

defineOptions({
    layout: AdminLayout
})

const columns = [
  { name: 'name', label: 'Name', align: 'center', field: 'name', sortable: true },
  { name: 'contains', align: 'center', label: 'Contains', field: 'contains', sortable: true },
  { name: 'itemsUsing', align: 'center', label: 'Items using', field: 'itemsUsing', sortable: true },
  { name: 'fat', align: 'center', label: 'Actions', field: 'fat', sortable: true },
]

const rows = [
  {
    name: 'Choose your drink',
    contains: 'coke, sprite, water, or smn',
    itemsUsing: 'item 1, item 2, item 3',
    fat: 'still figuring out how'
  },
  {
    name: 'Sugar Level',
    contains: '20%, 50%, 80%, none',
    itemsUsing: 'matcha milktea, some milktea, uhhh milktea',
    fat: 'still figuring out how'
  },
  
]

</script>

<template>
    
    <Head title="Group Modifiers" />
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
                <template v-slot:top>
                    <p class="text-h6 q-pt-md">Modifier Groups</p>
                    <q-space />
                        <q-input filled dense label="Search..." debounce="300" color="primary" v-model="filter">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                    <Link :href="route('admin.new_modifier_group')">
                        <q-btn class="q-ml-sm" color="primary" no-caps label="New Group" />
                    </Link>
                </template>
                
            </q-table>
        </div>
    </MilkteaLayout>
</template>
