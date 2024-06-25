<script setup>

import { Head, Link } from '@inertiajs/vue3'
import EventLayout from '@/Layouts/EventLayout.vue'

defineOptions({
    layout: EventLayout
})

// defineProps({
//     events: Object
// })

const events = [
    {
        name: 'hello',
        sold: 2,
        gross: 20.00,
        status: 'Upcoming Event'
    }
]

const columns = [
    { name: 'name', label: 'Name', align: 'center', field: 'name', sortable: true },
    { name: 'sold', align: 'center', label: 'sold', field: 'sold', sortable: true },
    { name: 'gross', align: 'center', label: 'gross', field: 'gross', sortable: true },
    { name: 'status', align: 'center', label: 'status', field: 'status', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    
    <Head title="Reviews" />
    <div class="q-pa-md">
        <q-table
            class="my-sticky-header-column-table"
            flat
            :rows="events"
            :columns="columns"
            row-key="name"
        >
            <template v-slot:body-cell-actions="props">
                <q-td :props="props">
                    <q-btn no-caps unelevated>Delete</q-btn>
                    <Link :href="route('admin.events.show', 1)">
                        <q-btn class="q-ml-sm" no-caps color="primary">View Event</q-btn>
                    </Link>
                </q-td>
            </template>
            <template v-slot:top>
                <p class="text-h6 q-pt-md">Events</p>
                <q-space />
                <q-input filled dense label="Search..." debounce="300" color="primary" v-model="filter">
                    <template v-slot:append>
                        <q-icon name="search" />
                    </template>
                </q-input>
                <Link :href="route('admin.events.create')">
                    <q-btn class="q-ml-sm" no-caps color="primary">Create Event</q-btn>
                </Link>
            </template>
        </q-table>
    </div>
</template>