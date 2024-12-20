<script setup>

import { Head } from '@inertiajs/vue3'
import CashierLayout from '@/Layouts/CashierLayout.vue'
import { ref, computed } from 'vue'
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

const filter = ref('all')
const searchTerm = ref('')

const columns = [
    { name: 'facility', label: 'Facility', align: 'center', field: 'facility', sortable: true },
    { name: 'arriving_in', align: 'center', label: 'Arriving In', sortable: true, field: 'check_in' },
    // { name: 'guest', align: 'center', label: 'Guest', field: 'guest', sortable: true },
    { name: 'check_in', align: 'center', label: 'Dates', field: 'check_in', sortable: true },
    // { name: 'check_out', align: 'center', label: 'Check-out', field: 'check_out', sortable: true },
    // { name: 'booked', align: 'center', label: 'Booked', field: 'booked', sortable: true },
    // { name: 'total', align: 'center', label: 'Total', field: 'total', sortable: true },
    { name: 'status', align: 'center', label: 'Status', field: 'status', sortable: true },
    { name: 'actions', align: 'center', label: '', field: 'actions', sortable: true },
]

// const filteredBookings = computed(() => {
//   if (filter.value == 'all') {
//         console.log('hii')
//         console.log(props.bookings)
//         return props.bookings// Return all bookings if filter is 'all'
//     } else {
//         return props.bookings.filter(booking => booking.status === filter.value);
//     }
// });
// console.log(filteredBookings.value)

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

const showSearch = ref(false)
const initialPagination = {
    sortBy: 'desc',
    descending: false,
    page: 1,
    rowsPerPage: 5
    // rowsNumber: xx if getting data from a server
}

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
                    title="Bookings"
                    :rows="filteredBookings"
                    :columns="columns"
                    row-key="name"
                    :pagination="initialPagination"
                >
                    <template v-slot:top>
                        <q-btn icon="menu" flat dense @click="drawerStore.drawer =true" class="lt-md q-mr-sm"/>
                        <div class="text-h6">Bookings</div>
                        <q-space />
                        <!-- <q-input rounded outlined dense label="Search..." v-model="filter" class="q-mx-md" debounce="300" color="primary">
                            <template v-slot:append>
                                <q-icon name="search" />
                            </template>
                        </q-input> -->
                        <q-btn icon="search" class="q-mr-xs" round dense flat @click="showSearch = !showSearch"/>
                            
                        <q-select 
                            :options="['all', 'pending', 'checked_in', 'checked_out', 'complete']" 
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
                    <template v-slot:body-cell-arriving_in="props">
                        <q-td :props="props">
                            {{ date.getDateDiff(props.row.check_in, new Date(), 'days') }} Days
                        </q-td>
                    </template>
                    <template v-slot:body-cell-facility="props">
                        <q-td :props="props">
                            <q-item>
                                <q-item-section avatar>
                                    <q-img :src="`/storage/${JSON.parse(props.row.facility.images)[0]}`" width="66px" height="65px"></q-img>
                                </q-item-section>
                                <q-item-section class="justify-start flex items-start">
                                    <q-item-label class="text-weight-bold">{{ props.row.user.first_name + ' ' + props.row.user.last_name }}</q-item-label>
                                    <q-item-label class="text-caption">{{ props.row.facility.name }}</q-item-label>
                                </q-item-section>
                            </q-item>
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
                            <q-btn icon="more_horiz" class="text-black" flat color="white" round>
                                <q-menu>
                                    <q-list style="min-width: 100px">
                                        <BookingDialog :booking="props.row" :booking_statuses="booking_statuses" />
                                    </q-list>
                                </q-menu>
                            </q-btn>
                        </q-td>
                    </template>
                    <template v-slot:item="props">
                        <q-card class="col-12 q-mb-sm" bordered flat :square="$q.screen.lt.md">
                            <q-card-section :class="`q-pb-sm ${$q.screen.lt.md ? 'q-pa-sm' : ''}`">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12">
                                        <div class="text-caption text-grey">
                                            Booking
                                        </div>
                                        <q-item class="q-pa-none">
                                            <q-item-section avatar>
                                                <q-img :src="`/storage/${JSON.parse(props.row.facility.images)[0]}`" width="65  px" height="65    px"></q-img>
                                            </q-item-section>
                                            <q-item-section>
                                                <q-item-label>{{ props.row.user.first_name + ' ' + props.row.user.last_name }}</q-item-label>
                                                <q-item-label caption>{{ props.row.facility.name }}</q-item-label>
                                            </q-item-section>
                                        </q-item>
                                        <q-btn icon="more_horiz" class="text-black absolute-top-right q-mr-sm" flat color="white" round>
                                            <q-menu>
                                                <q-list style="min-width: 100px">
                                                    <BookingDialog :booking="props.row" :booking_statuses="booking_statuses" />
                                                </q-list>
                                            </q-menu>
                                        </q-btn>
                                    </div>
                                    <div class="col-xs-6 col-sm-6">
                                        <div class="text-caption text-grey">
                                            Status
                                        </div>
                                        <q-chip>
                                            {{ props.row.status }}
                                        </q-chip>
                                    </div>
                                    <div class="col-xs-6 col-sm-6">
                                        <div class="text-caption text-grey">
                                            Dates
                                        </div>
                                        {{ date.formatDate(props.row.check_in, 'MMM D, YYYY') }} - {{ date.formatDate(props.row.check_out, 'MMM D, YYYY') }}
                                    </div>
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