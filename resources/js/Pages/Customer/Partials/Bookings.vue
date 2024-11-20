<script setup>

import { ref } from 'vue'
import BookingCard from '../Facility/Partials/BookingCard.vue'

defineProps({
    bookings: Object,
})
</script>

<template>
    <!-- <div class="text-h6">Bookings</div> -->
    <BookingCard v-for="booking in bookings" :booking="booking"/>
    <div v-show="bookings.length <= 0">
        <div class="flex items-center justify-center" style="height: 100px;">
            <q-icon name="confirmation_number" size="50px"/>
        </div>
        <div class="text-subtitle1 text-center">No Upcoming/Active Bookings</div>
    </div>
    
</template>