<script setup>

import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useDrawerStore } from '@/Stores/DrawerStore'
import { ref, computed } from 'vue'
import { date } from 'quasar'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    bookings: Object,
    statuses: Array
})
props.statuses.unshift('all')

const drawerStore = useDrawerStore()
const showSearch = ref(false)
const searchTerm = ref('')
const filter = ref('all')

const columns = [
    { name: 'status', align: 'center', label: 'Status', field: 'status', sortable: true },
    { name: 'user', label: 'user', align: 'center', field: 'user', sortable: true },
    { name: 'facility', label: 'Facility', align: 'center', field: 'facility', sortable: true },
    { name: 'guests', align: 'center', label: 'Guests', field: 'guests', sortable: true },
    { name: 'check_in', align: 'center', label: 'Check-in', field: 'check_in', sortable: true },
    { name: 'check_out', align: 'center', label: 'Check-out', field: 'check_out', sortable: true },
    { name: 'booked', align: 'center', label: 'Booked', field: 'booked', sortable: true },
    { name: 'total', align: 'center', label: 'Total', field: 'total', sortable: true },
    // { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]


const filteredBookings = computed(() => {
  let filtered = props.bookings;

  // Apply status filter
  if (filter.value !== 'all') {
    filtered = filtered.filter(booking => booking.status === filter.value);
  }

  // Apply search term filter
  if (searchTerm.value) {
    filtered = filtered.filter(booking => {
      const fullName = `${booking.user.first_name} ${booking.user.last_name}`.toLowerCase();
      const email = booking.user.email.toLowerCase();
      const facilityName = booking.facility.name.toLowerCase();
      const search = searchTerm.value.toLowerCase();

      return (
        fullName.includes(search) ||
        email.includes(search) ||
        facilityName.includes(search)
      );
    });
  }

  return filtered;
});


</script>

<template>
    
    <Head title="Bookings" />
    <div :class="`${$q.screen.gt.sm ? 'q-pa-md' : '' } bg-grey-3`" style="height: 100vh;">
        <q-card flat bordered>
            <q-table
                class="my-sticky-header-column-table"
                flat
                title="Treats"
                :rows="filteredBookings"
                :columns="columns"
                row-key="name"
                :grid="$q.screen.lt.md"
            >
            <template v-slot:top>
                    <q-btn icon="menu" flat dense @click="drawerStore.drawer =true" class="lt-md q-mr-sm"/>
                    <div class="text-h6">Bookings</div>
                    <q-space />
                    <q-btn icon="search" class="q-mr-xs" round dense flat @click="showSearch = !showSearch"/>
                    
                    <q-select 
                        :options="statuses" 
                        v-model="filter"
                        outlined 
                        rounded
                        dense
                    >
                    </q-select>
                    <div class="full-width q-mt-sm" v-if="showSearch">
                        <q-input
                            v-model="searchTerm"
                            rounded
                            outlined
                            dense
                            label="Search using name, email or facility name..."
                            debounce="300"
                            class="full-width"
                            color="primary"
                        >
                            <template v-slot:append>
                                <q-icon name="search" />
                            </template>
                        </q-input>
                    </div>
                </template>
                <!-- <template v-slot:top>
                    <div class="text-h6">Bookings</div>
                    <q-space />
                    <q-input filled dense label="Search..." v-model="filter" class="q-mx-md" debounce="300" color="primary">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                     <Link :href="route('admin.facilities.create')">
                        <q-btn no-caps color="primary">Create Facility</q-btn>
                    </Link>
                </template> -->
                <template v-slot:body-cell-facility="props">
                    <q-td :props="props">
                        {{ props.row.facility.name }}
                    </q-td>
                </template>
                <template v-slot:body-cell-user="props">
                    <q-td :props="props">
                        {{ props.row.user.first_name }}
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
                <template v-slot:item="props">
                        <q-card class="col-12 q-mb-md" bordered flat>
                            <q-card-section class="q-pb-sm">
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
            </q-table>
        </q-card>
        <!-- <q-dialog v-model="deleteProductDialog">
            <q-card>
                <q-card-section>
                    <p>Delete product</p>
                </q-card-section>
                <q-card-actions>
                    <q-space/>
                    <q-btn no-caps v-close-popup>Cancel</q-btn>
                    <q-btn 
                        no-caps
                        :loading="deleteProductForm.processing"
                        :disable="deleteProductForm.processing"
                        @click="deleteProduct"
                    >
                        Delete
                    </q-btn>
                </q-card-actions>
            </q-card>
        </q-dialog> -->
    </div>
</template>