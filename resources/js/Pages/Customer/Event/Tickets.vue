<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    ticket_orders: Object
})

</script>

<template>
	<div class="row">
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
    </div>
</template>
	