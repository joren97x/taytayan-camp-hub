<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Head } from '@inertiajs/vue3'
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

const tab = ref('mails')
</script>

<template>
    <Head title="Profile" />

    <div :class="$q.screen.lt.md ? 'q-pa-sm' : ''">
        <div class="row justify-center">
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                <q-item>
                    <q-item-section avatar>
                        <q-avatar size="80px">
                            <q-img :src="`/storage/${$page.props.auth.user.profile_pic}`" fit="cover" class="fit" />
                        </q-avatar>
                    </q-item-section>
                    <q-item-section>
                        <q-item-label class="text-h6">{{ $page.props.auth.user.first_name + ' ' + $page.props.auth.user.last_name }}</q-item-label>
                        <q-item-label caption>{{ $page.props.auth.user.email }}</q-item-label>
                    </q-item-section>
                </q-item>
                <div class="row q-col-gutter-md">
                    <div class="col-6">
                        <q-btn label="Edit Profile" no-caps unelevated rounded color="primary" class="full-width" />
                    </div>
                    <div class="col-6">
                        <q-btn label="Edit Profile" no-caps unelevated rounded outline color="primary" class="full-width" />
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <q-card>
                    <q-tabs
                    v-model="tab"
                    dense
                    class="text-grey"
                    active-color="primary"
                    indicator-color="primary"
                    align="justify"
                    narrow-indicator
                    >
                    <q-tab name="mails" label="Mails" />
                    <q-tab name="alarms" label="Alarms" />
                    <q-tab name="movies" label="Movies" />
                    </q-tabs>

                    <q-separator />

                    <q-tab-panels v-model="tab" animated>
                    <q-tab-panel name="mails">
                        <div class="text-h6">Mails</div>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </q-tab-panel>

                    <q-tab-panel name="alarms">
                        <div class="text-h6">Alarms</div>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                    </q-tab-panel>

                    <q-tab-panel name="movies">
                        <div class="text-h6">Movies</div>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
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
