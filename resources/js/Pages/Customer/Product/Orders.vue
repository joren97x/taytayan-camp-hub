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
        <q-card flat bordered :square="$q.screen.lt.md">
            <q-card-actions class="text-center justify-center items-center flex">
                <Link :href="route('customer.profile')" class="lt-md">
                    <q-btn icon="arrow_back" flat class="absolute-top-left q-mt-sm q-ml-sm text-black" rounded :label="$q.screen.gt.sm ? 'Go Back' : ''" no-caps/>
                </Link>
                <div class="text-h6">Orders</div>
            </q-card-actions>
            <q-separator/>
            <q-card-section :class="$q.screen.gt.sm ? '' : 'q-pa-none'">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <OrderCardItem v-for="order in orders" :order="order"/>
                        <div v-show="orders.length <= 0">
                            <div class="flex items-center justify-center" style="height: 70vh; width: 100%;">
                                <div class="text-center">
                                    <q-img src="/images/empty_orders.png" style="height: 100px; width: 100px;"></q-img>
                                    <div>
                                        <div class="text-subtitle1 text-grey-7">No Orders Found</div>
                                        <Link :href="route('customer.products.index')">
                                            <q-btn class="q-mt-xl" color="primary" rounded no-caps label="Browse Milkteas"/>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </q-card-section>
        </q-card>
    </Profile>
</template>
