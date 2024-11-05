<script setup>

import { ref } from 'vue'
import OrderCardItem from '../Product/Partials/OrderCardItem.vue'

defineProps({
    active_orders: Object,
    past_orders: Object
})

const showPastOrders = ref(false)
</script>

<template>
    <!-- <div class="text-h6">Active Orders</div> -->
    <OrderCardItem v-for="order in active_orders" :order="order"/>
    <div v-show="active_orders.length <= 0">
        <div class="flex items-center justify-center" style="height: 100px;">
            <q-icon name="confirmation_number" size="50px"/>
        </div>
        <div class="text-subtitle1 text-center">No Active Orders</div>
    </div>
    <div class="flex justify-center q-mt-md">
        <q-btn label="See Past Orders" @click="showPastOrders = !showPastOrders" no-caps flat v-show="!showPastOrders" color="primary"/>
    </div>
    <div v-show="showPastOrders">
        <div class="text-h6 q-mt-md">Past Orders</div>
        <OrderCardItem v-for="order in past_orders" :order="order"/>
        <div v-show="past_orders.length <= 0">
            <div class="flex items-center justify-center" style="height: 100px;">
                <q-icon name="confirmation_number" size="50px"/>
            </div>
            <div class="text-subtitle1 text-center">No Past Orders</div>
        </div>
        <div class="flex justify-center q-mt-md">
            <q-btn label="Hide Past Orders" @click="showPastOrders = !showPastOrders" no-caps flat  color="primary"/>
        </div>
    </div>
</template>