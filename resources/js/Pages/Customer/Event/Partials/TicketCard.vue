<script setup>

import { date } from 'quasar'
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

defineProps({
    ticket_order: Object
})

console.log(date.formatDate(new Date, 'MMMM D, YYYY h:mm A'))

const dialog = ref(false)

const formatTime = (timeString) => {
    const today = new Date()
    const dateObj = new Date(`${today.t}T${timeString}Z`)
    console.log(dateObj)
    return date.formatDate(dateObj, 'h:mm A'); // 12-hour format with AM/PM
}

</script>

<template>
    <q-card bordered flat class="q-mt-sm">
        <q-item @click="dialog = true" clickable>
            <q-item-section avatar class="items-center">
                <div class="text-weight-bold text-secondary">{{ date.formatDate(ticket_order.event.date, 'MMM') }}</div>
                <div>{{ date.formatDate(ticket_order.event.date, 'D') }}</div>
            </q-item-section>
            <q-item-section avatar>
                <q-img :src="`/storage/${ticket_order.event.cover_photo}`" height="100px" width="200px" class="rounded-borders" />
            </q-item-section>
            <q-item-section top>
                <q-item-label class="text-subtitle1">{{ ticket_order.event.title }}</q-item-label>
                <q-item-label caption>{{ date.formatDate(ticket_order.event.date, 'MMM D, YYYY') + ' at ' +  formatTime(ticket_order.event.start_time) }}</q-item-label>
                <q-item-label caption>Purchased on {{ date.formatDate(ticket_order.created_at, 'ddd, MMM D, h:m A') }}(₱839.92) </q-item-label>
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
            <q-card-actions class="justify-end lt-md">
                <q-btn round icon="close" v-close-popup unelevated />
            </q-card-actions>
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
                                <div class="q-mb-sm">
                                    {{ ticket_order.event.location }}
                                </div>
                                <Link :href="route('conversations.chat_cashier')" >
                                    <q-btn class="full-width " label="Contact Host" no-caps color="primary" rounded />
                                </Link>
                                <!-- <q-btn label="View QR Code" class="full-width q-my-sm" no-caps color="primary" unelevated/>
                                <div class="text-center">Purchased on {{ date.formatDate(ticket_order.created_at, 'ddd, MMM D, h:m A') }}</div> -->
                            </q-card-section>
                        </q-card>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
                        <div class="text-h6">({{ ticket_order.ticket_order_items.length }}x) Admission</div>
                        <q-btn round :flat="$q.screen.gt.sm" v-close-popup icon="close" class="absolute-top-right q-mr-sm q-mt-sm gt-sm"/>
                        <q-separator class="q-my-md"/>
                        <div class="text-subtitle1 text-weight-bold">Contact Information</div>
                        <div class="row">
                            <div class="col-6">
                                <div class="text-caption text-grey-9">First Name</div>
                                <div>{{ $page.props.auth.user.first_name }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption text-grey-9">Last Name</div>
                                <div>{{ $page.props.auth.user.last_name }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption text-grey-9">Email Address </div>
                                <div>{{ $page.props.auth.user.email }}</div>
                            </div>
                        </div>
                        <q-separator class="q-my-md"/>
                        <div class="text-subtitle1 text-weight-bold">Order Details</div>
                        <div class="row">
                            <div class="col-6">
                                <div class="text-caption text-grey-9">Price</div>
                                <div>{{ ticket_order.amount }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption text-grey-9">Payment Method</div>
                                <div>{{ ticket_order.payment_method }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption text-grey-9">Purchased On</div>
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