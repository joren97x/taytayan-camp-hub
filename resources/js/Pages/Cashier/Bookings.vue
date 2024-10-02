<script setup>

import { Head } from '@inertiajs/vue3'
import CashierLayout from '@/Layouts/CashierLayout.vue'
import { ref } from 'vue'
import { date } from 'quasar'
import BookingDialog from './Partials/BookingDialog.vue'

defineOptions({
    layout: CashierLayout
})

const props = defineProps({
    bookings: Object,
    booking_statuses: Array
})

const filter = ref('')

const columns = [
    { name: 'arriving_in', align: 'center', label: 'Arriving In', sortable: true },
    { name: 'status', align: 'center', label: 'Status', field: 'status', sortable: true },
    { name: 'facility', label: 'Facility', align: 'center', field: 'facility', sortable: true },
    { name: 'guests', align: 'center', label: 'Guests', field: 'guests', sortable: true },
    { name: 'check_in', align: 'center', label: 'Check-in', field: 'check_in', sortable: true },
    { name: 'check_out', align: 'center', label: 'Check-out', field: 'check_out', sortable: true },
    { name: 'booked', align: 'center', label: 'Booked', field: 'booked', sortable: true },
    { name: 'total', align: 'center', label: 'Total', field: 'total', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    
    <Head title="Bookings" />
    <div class="q-pa-md bg-grey-3" style="height: 100vh;">
        <q-card flat bordered>
            <q-table
                class="my-sticky-header-column-table"
                flat
                :grid="$q.screen.lt.md"
                title="Treats"
                :rows="bookings"
                :columns="columns"
                row-key="name"
                :filter="filter"
            >
                <template v-slot:top>
                    <p class="text-h6 q-pt-md">Bookings</p>
                    <q-space />
                    <q-input filled dense label="Search..." v-model="filter" class="q-mx-md" debounce="300" color="primary">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                    <!-- <Link :href="route('admin.facilities.create')">
                        <q-btn no-caps color="primary">Create Facility</q-btn>
                    </Link> -->
                </template>
                <template v-slot:body-cell-arriving_in="props">
                    <q-td :props="props">
                        {{ date.getDateDiff(props.row.check_in, new Date(), 'days') }} Days
                    </q-td>
                </template>
                <template v-slot:body-cell-facility="props">
                    <q-td :props="props">
                        {{ props.row.facility.name }}
                    </q-td>
                </template>
                <template v-slot:body-cell-booked="props">
                    <q-td :props="props">
                        {{ date.formatDate(props.row.created_at, 'MMM D, YYYY') }}
                    </q-td>
                </template>
                <template v-slot:body-cell-check_in="props">
                    <q-td :props="props">
                        {{ date.formatDate(props.row.check_in, 'MMM D, YYYY') }}
                    </q-td>
                </template>
                <template v-slot:body-cell-check_out="props">
                    <q-td :props="props">
                        {{ date.formatDate(props.row.check_out, 'MMM D, YYYY') }}
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <BookingDialog :order="props.row" :booking_statuses="booking_statuses" />
                    </q-td>
                </template>
            </q-table>
        </q-card>
    </div>
</template>