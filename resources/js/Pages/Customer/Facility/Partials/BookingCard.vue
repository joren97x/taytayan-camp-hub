<script setup>

import { date } from 'quasar'
import ShowBooking from '../ShowBooking.vue'
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3';

const props = defineProps({  booking: Object })
const dialog = ref(false)

</script>

<template>
    <!-- <q-card bordered flat>
        <q-item @click="dialog = true" clickable >
            <q-item-section avatar>
                <q-img :src="`/storage/${JSON.parse(booking.facility.images)[0]}`" height="100px" :width="$q.screen.lt.md ? '100px' : '200px'" class="rounded-borders" />
            </q-item-section>
            <q-item-section top :class="$q.screen.lt.md ? ' q-py-md' : ''">
                <q-item-label class="text-subtitle1">{{ booking.facility.name }}</q-item-label>
                <q-item-label caption>{{ date.formatDate(booking.check_in, 'MMM D, YYYY') + ' - ' + date.formatDate(booking.check_out, 'MMM D, YYYY')}}</q-item-label>
                <q-item-label caption>P{{ booking.total }}</q-item-label>
            </q-item-section>
            <q-item-section side top>
                <div  class="q-pt-sm q-pr-sm">
                    <q-chip size="12px">
                        {{ booking.status }}
                    </q-chip>
                </div>
            </q-item-section>
        </q-item>
    </q-card> -->
    <q-card bordered flat class="q-my-sm">
        <q-item @click="viewOrderDialog = true">
            <q-item-section>
                <q-item-label caption>Check-in</q-item-label>
                <q-item-label>{{ date.formatDate(booking.check_in, 'ddd, MMM D, YYYY') }}</q-item-label>
            </q-item-section>
            <q-item-section>
                <q-item-label caption>Check-out</q-item-label>
                <q-item-label>{{ date.formatDate(booking.check_out, 'ddd, MMM D, YYYY') }}</q-item-label>
            </q-item-section>
            <q-item-section>
                <q-item-label caption>Status</q-item-label>
                <q-item-label>{{ booking.status }}</q-item-label>
            </q-item-section>
            <q-item-section side>
                <div class="button-group gt-sm">
                    <Link :href="route('customer.bookings.show', booking.id)">
                        <q-btn label="View Booking" no-caps color="primary" outline rounded />
                    </Link>
                </div>
                <q-btn icon="more_horiz" round flat class="lt-md"></q-btn>
            </q-item-section>
        </q-item>
        <q-separator class="q-my-xs"/>
        <q-card>
            <q-item>
                <q-item-section avatar>
                    <q-img 
                        :height="$q.screen.gt.md ? '100px' : '80px'"
                        :width="$q.screen.gt.md ? '100px' : '80px'"
                        class="rounded-borders"
                        :src="`/storage/${JSON.parse(booking.facility.images)[0]}`"
                    ></q-img>
                </q-item-section>
                <q-item-section>
                    <q-item-label class="text-subtitle1 text-weight-bold">{{ booking.facility.name }}</q-item-label>
                    <q-item-label caption class="ellipsis-2-lines">{{ booking.facility.description }}</q-item-label>
                    <!-- <div class="text-weight-bold">₱{{ parseFloat(booking.facility.price).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</div> -->
                </q-item-section>
                <q-item-section side top>
                    <q-icon name="star" color="orange" size="sm"/> {{ parseFloat(booking.facility.average_rating).toFixed(2) }}
                </q-item-section>
            </q-item>
            <q-card-actions class="justify-end">
                <div class="text-weight-bold q-mr-md">Total</div>
                <div class="text-weight-bold">₱{{ parseFloat(booking.total).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</div>
            </q-card-actions>
        </q-card>
    </q-card>
    <q-dialog
        v-model="dialog"
        transition-show="slide-up"
        transition-hide="slide-down"
        :maximized="$q.screen.lt.md"
    >
        <ShowBooking :booking="booking" />
    </q-dialog>
</template>
