<script setup>

import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
// import OrderItem from '@/Components/Admin/Product/OrderItem.vue'
import PreparingOrderItem from '@/Components/Admin/Product/PreparingOrderItem.vue'
import ReadyOrderItem from '@/Components/Admin/Product/ReadyOrderItem.vue'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    orders: Object,
    order_constants: Object
})

const preparingOrders = computed(() => {
    return props.orders.filter(order => ['pending', 'preparing'].includes(order.status))
})

const readyOrders = computed(() => {
    return props.orders.filter(order => !['pending', 'preparing'].includes(order.status))
})


const order_statuses = computed(() => {
    return props.order_constants.statuses.reduce((obj, status) => {
        obj[status] = status
        return obj
    }, {})
})

</script>

<template>
    
    <Head title="Orders" />
    <div class="row q-mx-md q-pa-md q-mt-lg">
        <div class="col text-h5 text-weight-bold">
            Orders
        </div>
    </div>
    <div class="row q-mx-md">
        <div class="col-6 q-pa-md">
            <p class="text-h6">Preparing</p>
            <q-list>
                <PreparingOrderItem
                    v-for="(order, index) in preparingOrders" 
                    :key="index"
                    :order="order" 
                    :order_statuses="order_statuses"
                />
                <p v-if="preparingOrders.length == 0">
                    No orders yet...
                </p>
            </q-list>
        </div>
        <div class="col-6 q-pa-md">
            <p class="text-h6">Ready</p>
            <q-list>
                <ReadyOrderItem 
                    v-for="(order, index) in readyOrders" 
                    :key="index"
                    :order="order" 
                    :order_statuses="order_statuses"
                />
            </q-list>
        </div>
    </div>

</template>