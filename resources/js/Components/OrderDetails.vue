<script setup>

import { formatDistance } from 'date-fns'
import { Link } from '@inertiajs/vue3'
import OrderedItems from './OrderedItems.vue'

defineProps({ order: Object })

</script>

<template>
<q-card :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
    <q-card-actions class="justify-center">
        <div class="text-h6 text-center">Order Details</div>
        <q-btn icon="close" class="absolute-top-right q-mr-sm q-mt-xs" round v-close-popup flat/>
    </q-card-actions>
    <!-- {{ order }} -->
    <q-card-section class="row q-col-gutter-md q-pa-md">
        <!-- Order Items Section -->
        <div class="col-7 col-md-7 col-lg-7 col-xl-7 col-xs-12 col-sm-12">
            <q-item class="q-py-none q-my-sm">
                <q-item-section avatar>
                    <q-avatar size="xl" class="text-white" color="primary">
                        <q-img :src="`/storage/${order.user.profile_pic}`" v-if="order.user.profile_pic"/>
                        <div v-else>
                            {{ order.user.first_name[0] }}
                        </div>
                    </q-avatar>
                </q-item-section>
                <q-item-section>
                    <q-item-label class="text-weight-bold">{{ order.user.first_name + ' ' + order.user.last_name }}</q-item-label>
                    <q-item-label caption><q-icon name="phone" /> {{ order.user.phone_number }}</q-item-label>
                </q-item-section>
                <q-item-section side>
                    <Link :href="route('conversations.chat_user', order.user.id)">
                        <q-btn round icon="message" unelevated color="primary" />
                    </Link>
                </q-item-section>
            </q-item>
            <OrderedItems :subtotal="order.subtotal" :cart_products="order.cart_products" />  
        </div>

        <!-- Customer Details Section -->
        <div class="col-5 col-md-5 col-lg-5 col-xl-5 col-xs-12 col-sm-12">
            <div class="text-subtitle1 text-weight-medium q-m-md text-center">Order Information</div>
            <div class="row text-center q-col-gutter-md">
                <div class="col-6">
                    <div class="text-caption text-grey">Ordered At</div>
                    <div>{{ formatDistance(new Date(), order.created_at) }} ago</div>
                </div>
                <div class="col-6">
                    <div class="text-caption text-grey">Payment Method</div>
                    <div>{{ order.payment_method }}</div>
                </div>
                <div class="col-6">
                    <div class="text-caption text-grey">Fulfillment Type</div>
                    <div>{{ order.mode }}</div>
                </div>
                <div class="col-6">
                    <div class="text-caption text-grey">Status</div>
                    <div>{{ order.status }}</div>
                </div>
            </div>
            <slot />
        </div>
    </q-card-section>
</q-card>
</template>