<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link, Head } from '@inertiajs/vue3'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    bookings: Object,
})

</script>

<template>
    <Head title="Bookings"/>
    <div class="q-pa-md">
        <q-card flat bordered>
            <q-card-section>
                <div class="row">
                    <div class="col-6">
                         <div class="text-h6">Bookings</div>
                    </div>
                    <div class="col-6 text-right">
                        <!-- <Link :href="route('customer.bookings.past_bookings')"> -->
                            <q-btn label="See Past Bookings" no-caps rounded color="primary"/>
                        <!-- </Link> -->
                    </div>
                </div>
                {{ bookings }}
                <q-card v-for="(booking, index) in bookings" :key="index" class="q-mb-md">
        <q-card-section>
          <div class="row justify-between">
            <!-- Facility and User Info -->
            <div>
              <div><strong>Facility:</strong> {{ booking.facility.name }}</div>
              <div><strong>Description:</strong> {{ booking.facility.description }}</div>
              <div><strong>Guests Allowed:</strong> {{ booking.facility.guests }}</div>
              <div><strong>Booked Guests:</strong> {{ booking.guests }}</div>
              <div><strong>Total Price:</strong> {{ booking.total }} USD</div>
            </div>
            
            <!-- Dates -->
            <div>
              <q-icon name="event" /> <strong>Check-in:</strong> {{ booking.check_in }}<br />
              <q-icon name="event" /> <strong>Check-out:</strong> {{ booking.check_out }}
            </div>
          </div>
        </q-card-section>

        <!-- Facility Images -->
        <q-card-section>
          <q-img
            v-for="(image, i) in JSON.parse(booking.facility.images)"
            :key="i"
            :src="`/storage/${image}`"
            class="q-mr-sm"
            style="max-width: 100px; height: 80px;"
          />
        </q-card-section>

        <!-- Status and Actions -->
        <q-card-actions align="right">
          <q-chip color="orange" outline>{{ booking.status }}</q-chip>
          <q-btn color="primary" label="View Order" @click="viewOrder(booking)" />
          <q-btn v-if="booking.status == 'checked_out'" 
                 color="green" 
                 label="Complete Order" 
                 @click="completeOrder()" />
                 complete order tomm
        </q-card-actions>
        
      </q-card>
            </q-card-section>
        </q-card>
    </div>
</template>