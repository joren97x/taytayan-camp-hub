<script setup>

import ViewOrderDialog from './ViewOrderDialog.vue'
import { date } from 'quasar';
defineProps({
    order: Object,
    google_maps_api_key: String
})

const formatMoney = (money) => {
    return `₱${parseFloat(money).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
}

</script>
<template>
    <q-card class="q-ma-xs" bordered flat>
        <q-card-actions class="justify-between">
            <div :class="$q.screen.gt.sm ? 'text-h6' : 'text-subtitle1'">Order #{{ order.id }}</div>
            <div>Expected at {{ date.formatDate(order.waiting_time, 'h: m A') }}</div>
        </q-card-actions>
        <q-card-section :class="$q.screen.lt.md ? 'q-pa-none' : ''">
            <q-list>
                <q-item >
                    <q-item-section avatar>
                        <q-avatar class="text-white" color="grey">
                            <q-img v-if="order.user.profile_pic" :src="`/storage/${order.user.profile_pic}`" class="fit" fit="cover"/>
                            <div v-else>
                                {{ order.user.first_name[0] }}
                            </div>
                        </q-avatar> 
                    </q-item-section>
                    <q-item-section>
                        <q-item-label>{{ order.user.first_name + ' ' + order.user.last_name }}</q-item-label>
                        <q-item-label caption>{{ order.user.phone_number }}</q-item-label>
                    </q-item-section>
                </q-item>
                <q-item>
                    <q-item-section avatar>
                        <q-avatar class="text-white" color="grey">
                            <q-icon name="location_on"></q-icon>
                        </q-avatar> 
                    </q-item-section>
                    <q-item-section>
                        <q-item-label>{{ order.user.address }}</q-item-label>
                        <q-item-label caption>{{ order.user.street }}</q-item-label>
                    </q-item-section>
                </q-item>
                <q-item>
                    <q-item-section avatar>
                        <q-avatar class="text-white" color="grey">
                            <q-icon name="fastfood"></q-icon>
                        </q-avatar> 
                    </q-item-section>
                    <q-item-section>
                        <q-item-label>{{ order.cart_products.length }} Items</q-item-label>
                        <q-item-label class="text-weight-bold">{{ formatMoney(order.total) }}</q-item-label>
                    </q-item-section>
                </q-item>
            </q-list>
        </q-card-section>
        <q-card-actions class="justify-end">
            <ViewOrderDialog :order="order" :google_maps_api_key="google_maps_api_key" />
            <!-- <q-btn no-caps rounded color="primary" outline label="View Order"/> -->
            <!-- <q-btn no-caps rounded color="primary" label="Accept Order"/> -->
        </q-card-actions>
    </q-card>
</template>