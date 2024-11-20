<script setup>

import { date } from 'quasar'
import { ref } from 'vue'
import { parse, format } from 'date-fns'
import ShowTicket from '../ShowTicket.vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({ ticket_order: Object })
const formattedTime = format(parse(props.ticket_order.event.start_time, 'HH:mm:ss', new Date()), 'h a');
const dialog = ref(false)

</script>

<template>
    <q-card bordered flat class="q-mt-sm">
        <q-item >
            <q-item-section>
                <q-item-label caption>Date</q-item-label>
                <q-item-label>{{ date.formatDate(ticket_order.event.date, 'MMM D, YYYY') }}</q-item-label>
            </q-item-section>
            <q-item-section>
                <q-item-label caption>Attendees</q-item-label>
                <q-item-label>{{ ticket_order.tickets.length}}</q-item-label>
            </q-item-section>
            <q-item-section>
                <q-item-label caption>Status</q-item-label>
                <q-item-label>{{ ticket_order.status }}</q-item-label>
            </q-item-section>
            <q-item-section side>
                <div class="button-group gt-sm">
                    <Link :href="route('customer.tickets.show', ticket_order.id)">
                        <q-btn label="View Ticket" no-caps color="primary" outline rounded />
                    </Link>
                </div>
                <q-btn icon="more_horiz" round flat class="lt-md"></q-btn>
            </q-item-section>
        </q-item>
        <q-separator class="q-my-xs"/>
        <q-card>
            <q-item @click="dialog = true" >
                <q-item-section avatar class="items-center">
                    <div class="text-weight-bold text-secondary">{{ date.formatDate(ticket_order.event.date, 'MMM') }}</div>
                    <div>{{ date.formatDate(ticket_order.event.date, 'D') }}</div>
                </q-item-section>
                <q-item-section avatar>
                    <q-img :src="`/storage/${ticket_order.event.cover_photo}`" height="100px" width="100px" fit="cover" class="rounded-borders" />
                </q-item-section>
                <q-item-section top>
                    <q-item-label class="text-subtitle1">{{ ticket_order.event.title }}</q-item-label>
                    <q-item-label caption>{{ date.formatDate(ticket_order.event.date, 'MMM D, YYYY') + ' at ' + formattedTime }}</q-item-label>
                    <q-item-label caption class="ellipsis-2-lines">
                        {{ ticket_order.event.description }}
                    </q-item-label>
                </q-item-section>
            </q-item>
        </q-card>
        <q-card-actions class="justify-end">
            <div class="text-weight-bold q-mr-md">Total</div>
                <div class="text-weight-bold">₱{{ parseFloat(ticket_order.amount).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</div>
        </q-card-actions>
    </q-card>

    <q-dialog 
        v-model="dialog"
        transition-show="slide-up"
        transition-hide="slide-down"
        :maximized="$q.screen.lt.md"
    >
        <ShowTicket :ticket_order="ticket_order">
            <template v-slot:button>
                <q-btn round :flat="$q.screen.gt.sm" v-close-popup icon="close" class="absolute-top-right q-mr-sm q-mt-sm gt-sm"/>
            </template>
        </ShowTicket>
    </q-dialog>
</template>


<style scoped>

a {
    text-decoration: none;
}
</style>