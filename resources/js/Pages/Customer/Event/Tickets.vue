<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Head } from '@inertiajs/vue3'
import TicketCard from './Partials/TicketCard.vue'
import { ref } from 'vue'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    active_ticket_orders: Object,
    past_ticket_orders: Object
})

const showPastTicketOrders = ref(false)

</script>

<template>
    
    <Head title="Tickets" />
    <div class="q-pa-md">
        <q-card  flat>
            <q-card-section>
                <div class="row reverse justify-center">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <div class="text-h6">Tickets</div>
                        <TicketCard v-for="ticket_order in active_ticket_orders" :ticket_order="ticket_order"/>
                        <div v-show="active_ticket_orders.length <= 0">
                            <div class="flex items-center justify-center" style="height: 100px;">
                                <q-icon name="confirmation_number" size="50px"/>
                            </div>
                            <div class="text-subtitle1 text-center">No Upcoming Tickets</div>
                        </div>
                        <div class="flex justify-center q-mt-md">
                                <q-btn label="See Past Tickets" @click="showPastTicketOrders = !showPastTicketOrders" no-caps flat  color="primary"/>
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
                        </div>
                    </div>
                    <!-- <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <div class="text-h6">Orders</div>
                        <div class="flex items-center justify-center" style="height: 100px;">
                            <q-icon name="confirmation_number" size="50px"/>
                        </div>
                        <div class="text-subtitle1 text-center">No Upcoming Orders</div>
                        <div class="flex justify-center q-mt-md">
                            <q-btn label="See Past Orders" no-caps flat  color="primary"/>
                        </div>
                        <div class="text-h6 q-mt-md">Past Orders</div>
                        <TicketCard v-for="ticket_order in ticket_orders" :ticket_order="ticket_order"/>
                        
                    </div> -->
                </div>
            </q-card-section>
        </q-card>
    </div>
	<!-- <div class="row">
        {{ ticket_orders }}
        <div class="col-6"></div>
        <div class="col-6">
            <q-card v-for="ticket_order in ticket_orders" class="q-my-md">
                {{ ticket_order }}
                <div>Event</div>
                <q-img height="200px" width="200px" :src="`/storage/${ticket_order.qr_code_path}`"></q-img>
                {{ ticket_order.event.title }}
                <p>{{ ticket_order.ticket_order_items.length }}x admission</p>
                <p>Total: {{ ticket_order.amount }}</p>
                <p>Ticket Order Status: {{ ticket_order.status }}</p>
                <q-card-section v-for="ticket_order_item in ticket_order.ticket_order_items">
                    {{ ticket_order_item }}
                </q-card-section>
                <a :href="`/storage/${ticket_order.qr_code_path}`" download>
                    <q-btn>
                        Download Qr Code
                    </q-btn>
                </a>
            </q-card>
        </div>
    </div> -->
</template>
	