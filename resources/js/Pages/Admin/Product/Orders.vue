<script setup>

import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'
import ProductLayout from '@/Layouts/ProductLayout.vue'
import OrderItem from '@/Components/Admin/Product/OrderItem.vue'

defineOptions({
    layout: ProductLayout
})

const props = defineProps({
    orders: Object
})

const preparingOrders = computed(() => {
    return props.orders.filter(order => ['pending', 'preparing'].includes(order.status))
})

const readyOrders = computed(() => {
    return props.orders.filter(order => !['pending', 'preparing'].includes(order.status))
})


</script>

<template>
    
    <Head title="Orders" />
    <div class="row q-mx-md q-pa-md q-mt-lg">
        <div class="col text-h5">
            Orders
        </div>
    </div>
    <div class="row q-col-gutter-md q-mx-md">
        <div class="col-6">
            <p class="text-weight-bold text-h6">Preparing</p>
            <q-list>
                <OrderItem 
                    v-for="(order, index) in preparingOrders" 
                    :key="index"
                    :order="order" 
                />
                <q-item class="bg-blue-2 q-my-sm" clickable v-ripple>
                    <q-item-section>
                        <q-item-label>John Doe</q-item-label>
                        <q-item-label caption lines="2">3 items</q-item-label>
                    </q-item-section>

                    <q-item-section side top>
                        New
                        <q-item-label caption>5 min ago</q-item-label>
                    </q-item-section>
                </q-item>
                <q-item class="bg-grey-4"  clickable v-ripple>
                    <q-item-section>
                        <q-item-label>John Doe</q-item-label>
                        <q-item-label caption lines="2">3 items</q-item-label>
                    </q-item-section>

                    <q-item-section side top>
                        New
                        <q-item-label caption>5 min ago</q-item-label>
                    </q-item-section>
                </q-item>
            </q-list>
            {{ preparingOrders }}
        </div>
        <div class="col-6">
            <p class="text-weight-bold text-h6">Ready</p>
            <q-item class="bg-grey-4">
                <q-item-section>
                    <q-item-label>John Doe</q-item-label>
                    <q-item-label caption lines="2">3 items</q-item-label>
                </q-item-section>

                <q-item-section side top>
                    New
                    <q-item-label caption>5 min ago</q-item-label>
                </q-item-section>
            </q-item>
            {{ readyOrders }}
        </div>
        {{orders}}
    </div>

</template>