<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import Orders from '../Partials/Orders.vue'
import Profile from '../Profile.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    orders: Object,
})

const showPastOrders = ref(false)

</script>

<template>
    <Head title="Orders" />
    <Profile>
            <q-card flat bordered>
                <q-card-section>
                    <div class="text-h6">Orders</div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <Orders :orders="orders" />
                        </div>
                    </div>
                </q-card-section>
            </q-card>
    </Profile>
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
