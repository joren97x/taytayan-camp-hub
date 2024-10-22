<script setup>

import { Head } from '@inertiajs/vue3'
import { computed, onMounted, ref } from 'vue'
import CashierLayout from '@/Layouts/CashierLayout.vue'
// import OrderItem from '@/Components/Admin/Product/OrderItem.vue'
import PreparingOrderItem from '@/Pages/Cashier/Partials/PreparingOrderItem.vue'
import ReadyOrderItem from '@/Pages/Cashier/Partials/ReadyOrderItem.vue'
import { useQuasar } from 'quasar'
import axios from 'axios'
import { useDrawerStore } from '@/Stores/DrawerStore'

defineOptions({
    layout: CashierLayout
})

const drawerStore = useDrawerStore()
const $q = useQuasar()
const props = defineProps({
    orders: Object,
    order_constants: Object
})

const orders = ref(props.orders)
const tab = ref('preparing')

// props.orders.forEach(order => {
//     orders.value.push(order)
// });

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

onMounted(() => {
    Echo.private('orders')
    .listen('Product\\OrderPending', (data) => {
        $q.notify('new order arrived')
        console.log(data)
        axios.get(route('cashier.orders.show', data.order.id))
        .then((orderData) => {
            // $q.notify('fetched and ykwis bruh')
            // console.log(orderData)
            orders.value.push(orderData.data)
        })
        .catch((err) => {
            console.error(err)
        })
    })
    // .listen('Product\\OrderStatusUpdated', (data) => {
    //     console.log('data')
    //     axios.get(route('cashier.orders.get_orders'))
    //     .then((res) => {
    //         orders.value = res.data
    //     })
    //     .catch((err) => {
    //         console.error(err)
    //     })
    // })
})

const fetchOrders = () => {
    axios.get(route('cashier.orders.get_orders'))
        .then((res) => {
            orders.value = res.data
        })
        .catch((err) => {
            console.error(err)
        })
}

// axios.get(route('cashier.orders.show', 12))
//     .then((orderData) => {
//         $q.notify('fetched and ykwis bruh')
//         console.log(orderData.data)
//         orders.value.push(orderData.data)
//     })
//     .catch((err) => {
//         console.error(err)
//     })
</script>

<template>
    
    <Head title="Orders" />
   
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
        <q-card bordered flat>
            <div class="text-h6 text-weight-bold q-ml-md q-pt-md">
                <q-btn icon="menu" flat @click="drawerStore.drawer =true" class="lt-md"/>
                Orders
            </div>
            <q-separator class="q-my-md" />
            <div class="row" v-if="$q.screen.gt.sm">
                <div class="col-6 q-pb-md q-px-md">
                    <p class="text-h6">Preparing</p>
                    <q-list>
                        <PreparingOrderItem
                            v-for="(order, index) in preparingOrders" 
                            :key="index"
                            :order="order" 
                            :order_statuses="order_statuses"
                            @order_updated="fetchOrders"
                        />
                        <p v-if="preparingOrders.length == 0">
                            No orders yet...
                        </p>
                    </q-list>
                </div>
                <div class="col-6 q-pb-md q-px-md">
                    <p class="text-h6">Ready</p>
                    <q-list>
                        <ReadyOrderItem 
                            v-for="(order, index) in readyOrders" 
                            :key="index"
                            :order="order" 
                            :order_statuses="order_statuses"
                            @order_updated="fetchOrders"
                        />
                    </q-list>
                </div>
            </div>
                
                <q-card flat class="q-gutter-y-md" v-else>
                    <q-tabs
                        v-model="tab"
                        class="text-grey"
                        active-color="primary"
                        indicator-color="primary"
                        align="start"
                        narrow-indicator
                    >
                        <q-tab name="preparing" no-caps class="q-px-lg" label="Preparing">
                            <q-badge color="red" floating>4</q-badge>
                        </q-tab>
                        <q-tab name="ready" no-caps label="Ready" class="q-px-lg">
                            <q-badge color="red" floating>2</q-badge>
                        </q-tab>
                    </q-tabs>
                    <q-separator />
                </q-card>
                <q-card class="full-width" flat v-if="$q.screen.lt.md">
                    <q-tab-panels v-model="tab" animated>
                        <q-tab-panel name="preparing">
                            <q-list>
                                <PreparingOrderItem
                                    v-for="(order, index) in preparingOrders" 
                                    :key="index"
                                    :order="order" 
                                    :order_statuses="order_statuses"
                                />
                                <p v-if="preparingOrders.length == 0" class="text-center">
                                    No orders yet...
                                </p>
                            </q-list>
                        </q-tab-panel>
                        <q-tab-panel name="ready">
                            <q-list>
                                <ReadyOrderItem 
                                    v-for="(order, index) in readyOrders" 
                                    :key="index"
                                    :order="order" 
                                    :order_statuses="order_statuses"
                                />
                                <p v-if="readyOrders.length == 0" class="text-center">
                                    No orders yet...
                                </p>
                            </q-list>
                        </q-tab-panel>
                    </q-tab-panels>
                </q-card>
        </q-card>
    </div>

    <!-- </div> -->

</template>

<style scoped>
.custom-dialog {
  width: 70vw;
  max-width: 70vw;
}
</style>