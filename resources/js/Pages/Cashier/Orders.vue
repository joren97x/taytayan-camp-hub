<script setup>

import { Head } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import CashierLayout from '@/Layouts/CashierLayout.vue'
// import OrderItem from '@/Components/Admin/Product/OrderItem.vue'
import PreparingOrderItem from '@/Components/Admin/Product/PreparingOrderItem.vue'
import ReadyOrderItem from '@/Components/Admin/Product/ReadyOrderItem.vue'
import ImageGallery from './ImageGallery.vue'
import { useQuasar } from 'quasar'
import axios from 'axios'

defineOptions({
    layout: CashierLayout
})

const $q = useQuasar()
const props = defineProps({
    orders: Object,
    order_constants: Object
})

const orders = ref([])
const tab = ref('preparing')

props.orders.forEach(order => {
    orders.value.push(order)
});

const preparingOrders = computed(() => {
    return orders.value.filter(order => ['pending', 'preparing'].includes(order.status))
})

const readyOrders = computed(() => {
    return orders.value.filter(order => !['pending', 'preparing'].includes(order.status))
})

const order_statuses = computed(() => {
    return props.order_constants.statuses.reduce((obj, status) => {
        obj[status] = status
        return obj
    }, {})
})

Echo.private('orders')
    .listen('Product\\OrderPending', (data) => {
        $q.notify('new order arrived')
        console.log(data)
        axios.get(route('cashier.orders.show', data.order.id))
        .then((orderData) => {
            $q.notify('fetched and ykwis bruh')
            console.log(orderData)
            orders.value.push(orderData.data)
        })
        .catch((err) => {
            console.error(err)
        })
    })

    axios.get(route('cashier.orders.show', 12))
        .then((orderData) => {
            $q.notify('fetched and ykwis bruh')
            console.log(orderData.data)
            orders.value.push(orderData.data)
        })
        .catch((err) => {
            console.error(err)
        })

</script>

<template>
    
    <Head title="Orders" />
    <div class="row q-mx-md q-pa-md q-mt-lg">
        <div class="col text-h5 text-weight-bold">
            Orders
        </div>
    </div>
    <hr>
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
        <q-card>
            <q-tabs
                v-model="tab"
                dense
                class="text-grey"
                active-color="primary"
                indicator-color="primary"
                align="justify"
                narrow-indicator
            >
                <q-tab name="preparing" label="preparing" />
                <q-tab name="ready" label="ready" />
            </q-tabs>
            <q-separator />
        </q-card>
        <q-card>
            <q-tab-panels v-model="tab" animated>
                <q-tab-panel name="preparing">
                    <div class="text-h6">Mails</div>
                    <ImageGallery :images="[
                        'https://www.inkatrinaskitchen.com/wp-content/uploads/2020/05/Strawberry-Bubble-Tea-24-wm-600.jpg',
                        'https://www.dadcooksdinner.com/wp-content/uploads/2022/04/Instant-Pot-Boba-Tea-DSCF9377.jpg',
                        'https://teacultureoftheworld.com/cdn/shop/articles/taiwan-milk-tea-with-boba-bubble-pearl-on-plastic-2024-02-05-02-27-11-utc_2191x.jpg?v=1714023533'
                    ]" />
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                </q-tab-panel>
                <q-tab-panel name="ready">
                    <div class="text-h6">Alarms</div>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                </q-tab-panel>
            </q-tab-panels>
        </q-card>
    </div>

</template>