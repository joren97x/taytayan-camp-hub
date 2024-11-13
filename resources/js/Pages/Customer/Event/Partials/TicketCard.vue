<script setup>

import { date } from 'quasar'
import { ref } from 'vue'
import { parse, format } from 'date-fns'
import ShowTicket from '../ShowTicket.vue'

const props = defineProps({ ticket_order: Object })
const formattedTime = format(parse(props.ticket_order.event.start_time, 'HH:mm:ss', new Date()), 'h a');
const dialog = ref(false)

</script>

<template>
    <q-card bordered flat class="q-mt-sm">
        <q-item @click="dialog = true" clickable>
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
                <q-item-label caption>
                    ₱{{ ticket_order.amount }}
                </q-item-label>
            </q-item-section>
        </q-item>
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