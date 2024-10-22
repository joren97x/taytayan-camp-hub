<script setup>

import { Head } from '@inertiajs/vue3'
import CashierLayout from '@/Layouts/CashierLayout.vue'
import { ref } from 'vue'
import { date } from 'quasar'
import BookingDialog from './Partials/BookingDialog.vue'
import { useDrawerStore } from '@/Stores/DrawerStore'

defineOptions({
    layout: CashierLayout
})

const drawerStore = useDrawerStore()
const props = defineProps({
    bookings: Object,
    booking_statuses: Array
})

const filter = ref('')

const columns = [
    { name: 'arriving_in', align: 'center', label: 'Arriving In', sortable: true },
    { name: 'facility', label: 'Facility', align: 'center', field: 'facility', sortable: true },
    { name: 'guest', align: 'center', label: 'Guest', field: 'guest', sortable: true },
    { name: 'check_in', align: 'center', label: 'Dates', field: 'check_in', sortable: true },
    // { name: 'check_out', align: 'center', label: 'Check-out', field: 'check_out', sortable: true },
    // { name: 'booked', align: 'center', label: 'Booked', field: 'booked', sortable: true },
    // { name: 'total', align: 'center', label: 'Total', field: 'total', sortable: true },
    { name: 'status', align: 'center', label: 'Status', field: 'status', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    
    <Head title="Bookings" />
    <!-- <CashierLayout :drawer="drawer"> -->
        <div :class="$q.screen.gt.sm ? 'q-pa-md bg-grey-3' : ''" style="height: 100vh;">
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
                        <q-btn icon="menu" flat @click="drawerStore.drawer =true" class="lt-md"/>
                        <p class="text-h6 q-pt-md">Bookings</p>
                        <q-space />
                        <q-input rounded outlined dense label="Search..." v-model="filter" class="q-mx-md" debounce="300" color="primary">
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
                    <template v-slot:body-cell-check_in="props">
                        <q-td :props="props">
                            {{ date.formatDate(props.row.check_in, 'MMM D, YYYY') }} - {{ date.formatDate(props.row.check_out, 'MMM D, YYYY') }}
                        </q-td>
                    </template>
                    <template v-slot:body-cell-guest="props">
                        <q-td :props="props">
                            {{ props.row.user.first_name + ' ' + props.row.user.last_name }}
                        </q-td>
                    </template>
                    <template v-slot:body-cell-actions="props">
                        <q-td :props="props">
                            <q-chip class="q-mr-xs" size="12px" color="green-3"v-if="props.row.status == booking_statuses.checked_in || props.row.status == booking_statuses.checked_out">
                                Checked-in
                            </q-chip>
                            <q-chip size="12px" color="green-3"v-if="props.row.status == booking_statuses.checked_out">
                                Checked-out
                            </q-chip>
                            <BookingDialog :booking="props.row" :booking_statuses="booking_statuses" />
                        </q-td>
                    </template>
                    <template v-slot:item="props">
                        <q-card class="col-12 q-mb-md" bordered flat>
                            <q-card-section>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12">
                                        <q-item>
                                            <q-item-section avatar>
                                                <q-img :src="`/storage/${JSON.parse(props.row.facility.images)[0]}`" width="65  px" height="65    px"></q-img>
                                            </q-item-section>
                                            <q-item-section>
                                                <q-item-label caption>Facility</q-item-label>
                                                <q-item-label>{{ props.row.facility.name }}</q-item-label>
                                            </q-item-section>
                                            <q-item-section side>
                                                <q-item-label caption>Status</q-item-label>
                                                <q-item-label>
                                                    <q-chip>{{ props.row.status }}</q-chip>
                                                </q-item-label>
                                            </q-item-section>
                                        </q-item>
                                    </div>
                                    <div class="col-xs-6 col-sm-6">
                                        <div class="text-caption text-grey">
                                            Guest
                                        </div>
                                        {{ props.row.user.first_name + ' ' + props.row.user.last_name }}
                                    </div>
                                    <div class="col-xs-6 col-sm-6">
                                        <div class="text-caption text-grey">
                                            Dates
                                        </div>
                                        {{ date.formatDate(props.row.check_in, 'MMM D, YYYY') }} - {{ date.formatDate(props.row.check_out, 'MMM D, YYYY') }}
                                    </div>
                                    <div class="col-xs-12 col-sm-12">
                                        <BookingDialog :booking="props.row" :booking_statuses="booking_statuses" />
                                        <!-- <q-btn class="full-width q-mt-sm" unelevated no-caps label="View Booking" rounded color="primary" /> -->
                                    </div>
                                    <!-- <div class="col-xs-6 col-sm-6">
                                        <div class="text-caption text-grey">
                                            Items
                                        </div>
                                        {{ props.row.cart_products.length }} items
                                    </div>
                                    <div class="col-xs-6 col-sm-6">
                                        <div class="text-caption text-grey">
                                            Address
                                        </div>
                                        {{ props.row.user.address }}
                                    </div>
                                    <div class="col-xs-6 col-sm-6">
                                        <div class="text-caption text-grey">
                                            Phone
                                        </div>
                                        {{ props.row.user.phone_number }}
                                    </div>
                                    <div class="col-xs-6 col-sm-6">
                                        <div class="text-caption text-grey">
                                            Status
                                        </div>
                                        {{ props.row.status }}
                                    </div>
                                    <div class="col-xs-6 col-sm-6">
                                        <div class="text-caption text-grey">
                                            Payment Method
                                        </div>
                                        {{ props.row.payment_method }}
                                    </div>
                                    <div class="col-xs-12 col-sm-12">
                                        <div class="text-caption text-grey">
                                            Actions
                                        </div>
                                        <ViewOrderDialog :order="props.row" />
                                    </div> -->
                                </div>
                            </q-card-section>
                        </q-card>
                    </template>
                    <template v-slot:no-data>
                        <div class="full-width row flex-center q-gutter-sm" style="height: 70vh;">
                            <!-- <q-icon size="2em" name="shopping_cart" /> -->
                            <span>
                                There are currently no active or pending bookings...
                            </span>
                        </div>
                    </template>
                </q-table>
            </q-card>
        </div>
    <!-- </CashierLayout> -->
</template>