<script setup>

import { ref } from 'vue'
import OrderCardItem from '../Product/Partials/OrderCardItem.vue'

defineProps({
    orders: Object,
})
</script>

<template>
    <!-- <div class="text-h6">Active Orders</div> -->
    <OrderCardItem v-for="order in orders" :order="order"/>
    <div v-show="orders.length <= 0">
        <div class="flex items-center justify-center" style="height: 100px;">
            <q-icon name="confirmation_number" size="50px"/>
        </div>
        <div class="text-subtitle1 text-center">No Active Orders</div>
    </div>
</template>