<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Head } from '@inertiajs/vue3'
import Tickets from '../Partials/Tickets.vue'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    active_ticket_orders: Object,
    past_ticket_orders: Object
})

</script>

<template>
    
    <Head title="Tickets" />
    <div class="q-pa-md">
        <q-card  flat>
            <q-card-section>
                <div class="row reverse justify-center">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <Tickets :active_ticket_orders="active_ticket_orders" :past_ticket_orders="past_ticket_orders" />
                    </div>
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
	