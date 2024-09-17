<script setup>

import CashierLayout from '@/Layouts/CashierLayout.vue'

defineOptions({
    layout: CashierLayout
})

defineProps({
    ticket_order: Object
})

</script>

<template>
    <p>
        {{ ticket_order }}
    </p>
    <p>
        {{ ticket_order.event }}
    </p>
    <p v-for="ticket_order_item in ticket_order.ticket_order_items">
        {{ ticket_order_item.ticket.ticket_holder.name }}
    </p>
</template>
