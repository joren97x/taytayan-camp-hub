<script setup>

import { ref } from 'vue'
import TicketCard from '../Event/Partials/TicketCard.vue'

defineProps({
    active_ticket_orders: Object,
    past_ticket_orders: Object
})

const showPastTicketOrders = ref(false)

</script>

<template>
    <div class="text-h6">Tickets</div>
        <TicketCard v-for="ticket_order in active_ticket_orders" :ticket_order="ticket_order"/>
        <div v-show="active_ticket_orders.length == 0">
            <div class="flex items-center justify-center" style="height: 100px;">
                <q-icon name="confirmation_number" size="50px"/>
            </div>
            <div class="text-subtitle1 text-center">No Upcoming Tickets</div>
        </div>
        <div class="flex justify-center q-mt-md">
            <q-btn label="See Past Tickets" @click="showPastTicketOrders = !showPastTicketOrders" v-show="!showPastTicketOrders" no-caps flat  color="primary"/>
        </div>
        <div v-show="showPastTicketOrders">
            <div class="text-h6 q-mt-md">Past Tickets</div>
            <TicketCard v-for="ticket_order in past_ticket_orders" :ticket_order="ticket_order"/>
            <div v-show="past_ticket_orders.length <= 0">
                <div class="flex items-center justify-center" style="height: 100px;">
                    <q-icon name="confirmation_number" size="50px"/>
                </div>
                <div class="text-subtitle1 text-center">No Past Tickets</div>
            </div>
            <div class="flex justify-center q-mt-md">
                <q-btn label="Hide Past Tickets" @click="showPastTicketOrders = !showPastTicketOrders" no-caps flat  color="primary"/>
            </div>
        </div>
</template>