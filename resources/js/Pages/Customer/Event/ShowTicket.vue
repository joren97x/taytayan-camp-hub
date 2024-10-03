<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Head } from '@inertiajs/vue3'
import { date } from 'quasar'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    ticket_order: Object
})

</script>

<template>
    
    <Head title="Tickets" />
    <div class="q-pa-md">
        <q-card bordered flat>
            <q-card-section>
                <div class="row q-col-gutter-md">
                    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
                        <q-card bordered flat>
                            <q-img :src="`/storage/${ticket_order.event.cover_photo}`"></q-img>
                            <q-card-section>
                                <div class="text-h6">{{ ticket_order.event.title }}</div>
                                <div>
                                    {{ date.formatDate(ticket_order.event.date, 'ddd, MMM D') }}
                                </div>
                                <div>
                                    {{ ticket_order.event.location }}
                                </div>
                                <!-- <q-btn label="View QR Code" class="full-width q-my-sm" no-caps color="primary" unelevated/>
                                <div class="text-center">Purchased on {{ date.formatDate(ticket_order.created_at, 'ddd, MMM D, h:m A') }}</div> -->
                            </q-card-section>
                        </q-card>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
                        <div class="text-h6">({{ ticket_order.ticket_order_items.length }}x) Admission</div>
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
    </div>
	
</template>
	