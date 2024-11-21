<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import Profile from '../Profile.vue'
import TicketCard from './Partials/TicketCard.vue';

defineOptions({ layout: CustomerLayout })
defineProps({ ticket_orders: Object })

</script>

<template>
    
    <Head title="Tickets" />
    <Profile>
    <q-card bordered flat :square="$q.screen.lt.md">
        <q-card-actions class="text-center justify-center items-center flex">
            <Link :href="route('customer.profile')" class="lt-md">
                <q-btn icon="arrow_back" flat class="absolute-top-left q-mt-sm q-ml-sm text-black" rounded :label="$q.screen.gt.sm ? 'Go Back' : ''" no-caps/>
            </Link>
                <div class="text-h6">Tickets</div>
        </q-card-actions>
        <q-separator/>
        <q-card-section :class="$q.screen.lt.md ? 'q-pa-none' : ''">
            <div class="row reverse justify-center">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <TicketCard v-for="ticket_order in ticket_orders" :ticket_order="ticket_order"/>
                    <div v-show="ticket_orders.length == 0">
                        <div class="flex items-center justify-center" style="height: 100px;">
                            <q-icon name="confirmation_number" size="50px"/>
                        </div>
                        <div class="text-subtitle1 text-center">No Upcoming Tickets</div>
                    </div>
                </div>
            </div>
        </q-card-section>
    </q-card>
</Profile>
	
</template>
	