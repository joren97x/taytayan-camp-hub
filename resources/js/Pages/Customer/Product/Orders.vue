<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import Orders from '../Partials/Orders.vue';
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    active_orders: Object,
    past_orders: Object
})

const showPastOrders = ref(false)

</script>

<template>
    <Head title="Orders" />
    <div class="q-pa-md">
        <q-card  flat>
            <q-card-section>
                <div class="row reverse justify-center">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <Orders :active_orders="active_orders" :past_orders="past_orders" />
                    </div>
                </div>
            </q-card-section>
        </q-card>
    </div>
    <!-- <div class="q-pa-md">
        <q-card flat bordered>
            <q-card-section>
                <div class="row">
                    <div class="col-6">
                         <div class="text-h6">Orders</div>
                    </div>
                    <div class="col-6 text-right">
                        <Link :href="route('customer.orders.past_orders')">
                            <q-btn label="See Past Orders" no-caps rounded color="primary"/>
                        </Link>
                    </div>
                </div> -->
                <!-- <div class="row q-ma-xl">
                    <div class="col self-start text-h6">
                        Orders
                    </div>
                    <div class="col text-right">
                        <Link :href="route('customer.orders.past_orders')">
                            See Past Orders
                        </Link>
                    </div>
                </div> -->
                <!-- <div class="row q-my-md">
                    <div 
                        class="col-12" 
                        v-for="order in orders"
                    >
                        <OrderCardItem :order="order"/>
                    </div>
                    
                </div>
            </q-card-section>
        </q-card>  
    </div>   -->
</template>
