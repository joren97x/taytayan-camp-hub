<script setup>

import { Head, Link } from '@inertiajs/vue3'
import EventLayout from '@/Layouts/EventLayout.vue'

defineOptions({
    layout: EventLayout
})

const props = defineProps({
    event: Object
})

const columns = [
    { name: 'title', label: 'title', align: 'center', field: 'title', sortable: true },
    { name: 'tickets_sold', align: 'center', label: 'tickets_sold', field: 'tickets_sold', sortable: true },
    // { name: 'gross', align: 'center', label: 'gross', field: 'gross', sortable: true },
    { name: 'status', align: 'center', label: 'status', field: 'status', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    
    <Head title="Reviews" />
    <div class="q-pa-md">{{ event.title }}</div>
    <div class="row q-pa-md q-col-gutter-md">
        <div class="col-4">
            <q-card>
                <q-card-section>Tickets Sold</q-card-section>
                <q-card-section>{{ event.tickets_sold }} / {{ event.capacity }}</q-card-section>
            </q-card>
        </div>
        <div class="col-4">
            <q-card>
                <q-card-section>Net Sales</q-card-section>
                <q-card-section>P10.00</q-card-section>
            </q-card>
        </div>
    </div>
    {{event}}
    <div>Recent Orders</div>
    <!-- <q-table
        class="my-sticky-header-column-table"
        flat
        :rows="event"
        :columns="columns"
        row-key="name"
    >
        <template v-slot:body-cell-actions="props">
            <q-td :props="props">
                <q-btn no-caps unelevated>Delete</q-btn>
            </q-td>
        </template>
        <template v-slot:top>
            <p class="text-h6 q-pt-md">Recent Orders</p>
            <q-space />
            <q-input filled dense label="Search..." debounce="300" color="primary" v-model="filter">
                <template v-slot:append>
                    <q-icon name="search" />
                </template>
            </q-input>
        </template>
    </q-table> -->
</template>