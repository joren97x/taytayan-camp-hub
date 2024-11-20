<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import OrderCardItem from './Partials/OrderCardItem.vue'
import Profile from '../Profile.vue'
import { Head, Link } from '@inertiajs/vue3'
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
            <q-card-actions class="text-center justify-center items-center flex">
                <Link :href="route('customer.profile')" class="lt-md">
                    <q-btn icon="arrow_back" flat class="absolute-top-left q-mt-sm q-ml-sm text-black" rounded :label="$q.screen.gt.sm ? 'Go Back' : ''" no-caps/>
                </Link>
                <div class="text-h6">Orders</div>
            </q-card-actions>
            <q-separator/>
            <q-card-section>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <OrderCardItem v-for="order in orders" :order="order"/>
                        <div v-show="orders.length <= 0">
                            <!-- <div class="flex items-center justify-center" style="height: 100px;">
                                <q-icon name="confirmation_number" size="50px"/>
                            </div>
                            <div class="text-subtitle1 text-center">No Active Orders</div> -->
                            <q-icon name="info" size="50px" color="primary" />
                            <div class="text-h6 q-mt-md">Looks like it’s empty here.</div>
                            <div class="text-subtitle2 q-mt-sm">
                            Start exploring events, booking a stay, or ordering delicious food!
                            </div>
                        </div>
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
