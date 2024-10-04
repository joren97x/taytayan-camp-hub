<script setup>

import { date } from 'quasar'
import { ref } from 'vue'

defineProps({
    order: Object
})

const dialog = ref(false)

</script>

<template>
    <q-card class="q-my-sm" flat bordered>
        <q-item>
            <q-item-section avatar>
                <!-- {{ order }} -->
                <q-card flat bordered>
                    <div style="height: 100px; width: 200px;" class="row">
                        <div class="col-6" v-for="cart_product in order.cart_products">
                            <q-img :src="`../storage/${cart_product.product.photo}`" fit="contain" height="50px" width="100px"></q-img>
                        </div>
                    </div>
                </q-card>
                <!-- <q-img :src="`/storage/${order.product.image}`" height="100px" width="200px" class="rounded-borders" /> -->
            </q-item-section>
            <q-item-section top>
                <q-item-label class="text-subtitle1">{{ date.formatDate(order.created_at, 'MMMM D, YYYY') }}</q-item-label>
                <q-item-label caption>{{ order.cart_products.length }} items</q-item-label>
                <q-item-label caption> {{ order.mode }} </q-item-label>
                <!-- Tue, Mar 19, 8:31 PM  -->
            </q-item-section>
            <q-item-section side top>
                <q-chip>{{ order.status }}</q-chip>
                {{ order.subtotal }}
            </q-item-section>
        </q-item>
    </q-card>

    <q-dialog 
        v-model="dialog"
        transition-show="slide-up"
        transition-hide="slide-down"
        :maximized="$q.screen.lt.md"
    >
        <q-card bordered flat :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
            <q-card-section>
                <div class="row q-col-gutter-md">
                    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
                        <q-card bordered flat>
                            <q-img :src="`/storage/${order.event.cover_photo}`"></q-img>
                            <q-card-section>
                                <div class="text-h6">{{ order.event.title }}</div>
                                <div>
                                    {{ date.formatDate(order.event.date, 'ddd, MMM D') }}
                                </div>
                                <div>
                                    {{ order.event.location }}
                                </div>
                                <!-- <q-btn label="View QR Code" class="full-width q-my-sm" no-caps color="primary" unelevated/>
                                <div class="text-center">Purchased on {{ date.formatDate(order.created_at, 'ddd, MMM D, h:m A') }}</div> -->
                            </q-card-section>
                        </q-card>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
                        <div class="text-h6">({{ ticket_order.ticket_order_items.length }}x) Admission</div>
                        <q-btn round :flat="$q.screen.gt.sm" v-close-popup icon="close" class="absolute-top-right q-mr-sm q-mt-sm"/>
                        <q-separator class="q-my-md"/>
                        <div class="text-subtitle1">Contact Information</div>
                        <div class="row">
                            <div class="col-6">
                                <div class="text-caption">First Name</div>
                                <div>{{ $page.props.auth.user.first_name }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption">Last Name</div>
                                <div>{{ $page.props.auth.user.last_name }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption">Email Address </div>
                                <div>{{ $page.props.auth.user.email }}</div>
                            </div>
                        </div>
                        <q-separator class="q-my-md"/>
                        <div class="text-subtitle1">Order Details</div>
                        <div class="row">
                            <div class="col-6">
                                <div class="text-caption">Price</div>
                                <div>{{ ticket_order.amount }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption">Payment Method</div>
                                <div>{{ ticket_order.payment_method }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption">Purchased On</div>
                                <div>{{ date.formatDate(ticket_order.created_at, 'ddd, MMM D, h:m A') }}</div>
                            </div>
                        </div>
                        <q-separator class="q-my-md" />
                        <div class="row">
                            <div class="col-12">
                                <div :class="$q.screen.lt.md ? 'text-center' : ''">
                                    Qr COde
                                    <a :href="`/storage/${ticket_order.qr_code_path}`" class="q-ml-sm" download>Download Qr</a>
                                </div>
                                <div :class="$q.screen.lt.md ? ' flex justify-center' : ''">
                                    <q-img :src="`/storage/${ticket_order.qr_code_path}`" height="200px" width="200px"></q-img>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- {{ ticket_order }} -->
            </q-card-section>
        </q-card>
    </q-dialog>

</template>