<script setup>

import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    event: Object
})

const columns = [
    { name: 'customer', label: 'customer', align: 'center', field: 'customer', sortable: true },
    { name: 'qr_code_path', align: 'center', label: 'qr_code_path', field: 'qr_code_path', sortable: true },
    { name: 'payment_method', align: 'center', label: 'payment_method', field: 'payment_method', sortable: true },
    { name: 'status', align: 'center', label: 'status', field: 'status', sortable: true },
    { name: 'tickets_sold', align: 'center', label: 'tickets_sold', field: 'tickets_sold', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    
    <Head title="Reviews" />
    <div class="q-pa-md text-h6">{{ event.title }}</div>
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
                <q-card-section>P{{ event.tickets_sold * event.admission_fee }}</q-card-section>
            </q-card>
        </div>
    </div>
    <q-table
        class="my-sticky-header-column-table"
        flat
        :rows="event.ticket_orders"
        :columns="columns"
        row-key="name"
    >
        <template v-slot:body-cell-customer="props">
            <q-td :props="props">
                {{ props.row.user.first_name + ' ' + props.row.user.last_name }}
            </q-td>
        </template>
        <template v-slot:body-cell-qr_code_path="props">
            <q-td :props="props">
                <q-img :src="`/storage/${props.row.qr_code_path}`"/>
            </q-td>
        </template>
        <template v-slot:body-cell-tickets_sold="props">
            <q-td :props="props">
                {{ props.row.user.first_name + ' ' + props.row.user.last_name }}
                <div v-for="ticket_holder in props.row.ticket_order_items">
                    {{ ticket_holder }}
                </div>
            </q-td>
        </template>
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
    </q-table>
</template>