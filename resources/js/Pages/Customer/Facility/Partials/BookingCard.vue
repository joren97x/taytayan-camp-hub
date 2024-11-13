<script setup>

import { date } from 'quasar'
import ShowBooking from '../ShowBooking.vue'
import { ref } from 'vue'

const props = defineProps({  booking: Object })
const dialog = ref(false)

</script>

<template>
    <q-card bordered flat>
        <q-item @click="dialog = true" clickable :class="$q.screen.lt.md ? 'q-pa-none' : ''">
            <q-item-section avatar>
                <q-img :src="`/storage/${JSON.parse(booking.facility.images)[0]}`" height="100px" :width="$q.screen.lt.md ? '100px' : '200px'" class="rounded-borders" />
            </q-item-section>
            <q-item-section top :class="$q.screen.lt.md ? ' q-py-md' : ''">
                <q-item-label class="text-subtitle1">{{ booking.facility.name }}</q-item-label>
                <q-item-label caption>{{ date.formatDate(booking.check_in, 'MMM D, YYYY') + ' - ' + date.formatDate(booking.check_out, 'MMM D, YYYY')}}</q-item-label>
                <q-item-label caption>P{{ booking.total }}</q-item-label>
            </q-item-section>
            <q-item-section side top>
                <div  class="q-pt-sm q-pr-sm">
                    <q-chip size="12px">
                        {{ booking.status }}
                    </q-chip>
                </div>
            </q-item-section>
        </q-item>
    </q-card>
    <q-dialog
        v-model="dialog"
        transition-show="slide-up"
        transition-hide="slide-down"
        :maximized="$q.screen.lt.md"
    >
        <ShowBooking :booking="booking" />
    </q-dialog>
</template>
