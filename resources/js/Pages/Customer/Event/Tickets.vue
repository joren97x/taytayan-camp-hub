<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { date } from 'quasar'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    ticket_orders: Object
})

</script>

<template>
    
    <Head title="Tickets" />
    <div class="q-pa-md">
        <q-card  flat>
            <q-card-section>
                <div class="row reverse justify-center">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <div class="text-h6">Orders</div>
                        <div class="flex items-center justify-center" style="height: 100px;">
                            <q-icon name="confirmation_number" size="50px"/>
                        </div>
                        <div class="text-subtitle1 text-center">No Upcoming Orders</div>
                        <div class="flex justify-center q-mt-md">
                            <q-btn label="See Past Orders" no-caps flat  color="primary"/>
                        </div>
                        <div class="text-h6 q-mt-md">Past Orders</div>
                        <Link :href="route('customer.tickets.show', ticket_order.id)" v-for="ticket_order in ticket_orders">
                            <q-item >
                                <q-item-section avatar class="items-center">
                                    <div class="text-weight-bold text-secondary">{{ date.formatDate(ticket_order.event.date, 'MMM') }}</div>
                                    <div>{{ date.formatDate(ticket_order.event.date, 'D') }}</div>
                                </q-item-section>
                                <q-item-section avatar>
                                    <q-img :src="`/storage/${ticket_order.event.cover_photo}`" height="100px" width="200px" class="rounded-borders" />
                                </q-item-section>
                                <q-item-section top>
                                    <q-item-label class="text-subtitle1">{{ ticket_order.event.title }}</q-item-label>
                                    <q-item-label caption>{{ date.formatDate(ticket_order.event.date, 'MMM D, YYYY') + ' at ' +  ticket_order.event.start_time}}</q-item-label>
                                    <q-item-label caption>Purchased on {{ date.formatDate(ticket_order.created_at, 'ddd, MMM D, h:m A') }}(₱839.92) </q-item-label>
                                    <!-- Tue, Mar 19, 8:31 PM  -->
                                </q-item-section>
                            </q-item>
                        </Link>
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
	