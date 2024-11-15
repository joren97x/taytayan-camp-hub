<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import Orders from './Partials/Orders.vue'
import Bookings from './Partials/Bookings.vue'
import Tickets from './Partials/Tickets.vue'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    active_orders: Object,
    past_orders: Object,
    active_bookings: Object,
    past_bookings: Object,
    active_ticket_orders: Object,
    past_ticket_orders: Object
})

const tab = ref('orders')
</script>

<template>
    <Head title="Profile" />
    <div :class="$q.screen.lt.md ? 'q-pa-sm' : ''">
        <div :class="`${$q.screen.gt.sm ? 'q-mt-sm' : ''} row q-col-gutter-y-sm`">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="text-h6">Profile</div>
                    <q-card bordered flat class="full-width">
                    <!-- <q-card bordered flat style="height: 40vh; position: relative;" class="full-width bg-grey row justify-center">
                        <q-img class="fit" fit="cover" src="images/huts2.jpg" />
                        <div style="position: absolute; inset: 0; background: rgba(0, 0, 0, 0.5);" class="flex items-center justify-center">
                            <div class="text-center text-white">
                                <div class="text-h2 q-mb-sm text-weight-bold">Explore Our Camp Facilities</div>
                                <div class="text-h6">Find your perfect spot to relax and enjoy nature</div>
                            </div>
                        </div>
                    </q-card> -->
                        <q-item>
                            <q-item-section avatar>
                                <q-avatar size="80px" color="primary" class="text-white">
                                    <q-img :src="`/storage/${$page.props.auth.user.profile_pic}`" fit="cover" class="fit" v-if="$page.props.auth.user.profile_pic" />
                                    <div v-else>
                                        {{ $page.props.auth.user.first_name[0] }}
                                    </div>
                                </q-avatar>
                            </q-item-section>
                            <q-item-section>
                                <q-item-label class="text-h6">{{ $page.props.auth.user.first_name + ' ' + $page.props.auth.user.last_name }}</q-item-label>
                                <q-item-label caption>{{ $page.props.auth.user.email }}</q-item-label>
                                <q-item-label>
                                    <Link :href="route('customer.edit_profile')">
                                        <q-btn label="Edit" no-caps unelevated rounded color="primary" class="q-mr-sm"/>
                                    </Link>
                                    <Link :href="route('conversations.index')">
                                        <q-btn label="Inbox" no-caps unelevated rounded outline color="primary"/>
                                    </Link>
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                </q-card>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <q-card bordered flat>
                    <q-tabs
                        v-model="tab"
                        class="text-black"
                        active-color="primary"
                        indicator-color="primary"
                        align="justify"
                        no-caps
                    >
                        <q-tab name="orders" label="Orders" />
                        <q-tab name="bookings" label="Bookings" />
                        <q-tab name="tickets" label="Tickets" />
                    </q-tabs>

                    <q-separator />

                    <q-tab-panels v-model="tab" animated>
                        <q-tab-panel name="orders" class="q-pa-none q-px-sm q-pb-sm">
                            <Orders :active_orders="active_orders" :past_orders="past_orders" />
                            <!-- <div class="text-h6">Orders</div>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. -->
                        </q-tab-panel>

                        <q-tab-panel name="bookings" class="q-pa-none q-px-sm q-pb-sm">
                            <Bookings :active_bookings="active_bookings" :past_bookings="past_bookings" />
                            <!-- <div class="text-h6">bookings</div>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. -->
                        </q-tab-panel>

                        <q-tab-panel name="tickets" class="q-pa-none q-px-sm q-pb-sm">
                            <Tickets :active_ticket_orders="active_ticket_orders" :past_ticket_orders="past_ticket_orders" />
                            <!-- <div class="text-h6">Tickets</div>
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. -->
                        </q-tab-panel>
                    </q-tab-panels>
                </q-card>
                <!-- <Orders :active_orders="active_orders" :past_orders="past_orders" />
                <q-separator class="q-my-md"/>
                <Bookings :active_bookings="active_bookings" :past_bookings="past_bookings" />
                <q-separator class="q-my-md"/>
                <Tickets :active_ticket_orders="active_ticket_orders" :past_ticket_orders="past_ticket_orders" /> -->
            </div>
        </div>
    </div>

    <!-- <div class="q-pa-md">
        <UpdateProfileInformationForm/>
        <UpdatePasswordForm/>
    </div> -->
</template>
