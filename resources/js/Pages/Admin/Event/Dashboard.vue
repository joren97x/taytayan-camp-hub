<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import { date } from 'quasar'
import { ref } from 'vue'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    event: Object,
    ticket_orders: Object
})

const filter = ref('')
const columns = [
    { name: 'name', align: 'center', label: 'Name', field: 'name', sortable: true },
    { name: 'quantity', align: 'center', label: 'Quantity', field: 'quantity', sortable: true },
    { name: 'amount', align: 'center', label: 'Price', field: 'amount', sortable: true },
    { name: 'status', align: 'center', label: 'Status', field: 'status', sortable: true },
    { name: 'created_at', align: 'center', label: 'Date', field: 'created_at', sortable: true },
    { name: 'actions', align: 'center', label: '', field: 'actions', sortable: true },
]
</script>

<template>
    <q-card bordered flat :class="$q.screen.gt.sm ? 'q-ma-md' : ''">
        <q-card-section>
            <div class="text-h6">Event Dashboard</div>
            <q-card bordered flat >
                <q-item>
                    <q-item-section avatar class="items-center">
                        <div class="text-weight-bold text-secondary">{{ date.formatDate(event.date, 'MMM') }}</div>
                        <div>{{ date.formatDate(event.date, 'D') }}</div>
                    </q-item-section>
                    <q-item-section avatar>
                        <q-img :src="`/storage/${event.cover_photo}`" height="70px" width="70px" class="rounded-borders" />
                    </q-item-section>
                    <q-item-section class="items-start">
                        <q-item-label class="text-subtitle1">{{ event.title }}</q-item-label>
                        <q-item-label caption>{{ date.formatDate(event.date, 'MMM D, YYYY') + ' at ' +  event.start_time}}</q-item-label>
                    </q-item-section>
                </q-item>
            </q-card>
            <div class="row q-my-xs q-col-gutter-md">
                <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
                    <q-card bordered flat class="q-pa-xl">
                        <q-card-section>
                            errmm what the sigma
                        </q-card-section>
                    </q-card>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <q-card bordered flat class="q-pa-xl">
                        <q-card-section>
                            tyshii
                        </q-card-section>
                    </q-card>
                </div>
            </div>
            <div class="text-h6 q-my-md">Recent Orders</div>
            <q-table
                class="my-sticky-header-column-table"
                flat
                :grid="$q.screen.lt.md"
                :rows="ticket_orders"
                :columns="columns"
                row-key="name"
                :filter="filter"
            >
                <template v-slot:body-cell-name="props">
                    <q-td :props="props">
                        {{ props.row.user.first_name + ' ' + props.row.user.last_name }}
                    </q-td>
                </template>
                <template v-slot:body-cell-quantity="props">
                    <q-td :props="props">
                        {{ props.row.ticket_order_items.length }}
                    </q-td>
                </template>
                <template v-slot:body-cell-created_at="props">
                    <q-td :props="props">
                        {{ date.formatDate(props.row.created_at, 'MMM D, YYYY') }}
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <q-btn no-caps unelevated color="primary">button</q-btn>
                    </q-td>
                </template>
            </q-table>
        </q-card-section>
    </q-card>
</template>
