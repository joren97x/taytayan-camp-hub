<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { date } from 'quasar'
import { format, parse, isBefore, isToday, parseISO } from 'date-fns'
import { ref, computed } from 'vue'
import { useQuasar } from 'quasar'
import Profile from '../Profile.vue'

defineOptions({ layout: CustomerLayout })
const props = defineProps({ ticket_order: Object })

const $q = useQuasar()
const formattedTime = format(parse(props.ticket_order.event.start_time, 'HH:mm:ss', new Date()), 'h a');
const cancelForm = useForm({})
const cancelDialog = ref(false)
const cancel = () => {
    cancelForm.put(route('customer.tickets.cancel', props.ticket_order.id), {
        onSuccess: () => {
            cancelDialog.value = false
            $q.notify('Ticket Order Cancelled')
        }
    })
}

const now = new Date();

// Check if the cancel button should be shown
const showCancelButton = computed(() => {
    const isStatusValid = !['completed', 'cancelled'].includes(props.ticket_order.status);

    // Parse event's date and start time
    const eventDateTime = parseISO(`${props.ticket_order.event.date}T${props.ticket_order.event.start_time}`);
    const hasEventStarted = isToday(eventDateTime) && isBefore(eventDateTime, now);

    // Show cancel button if the status is valid and event hasn't started yet
    return isStatusValid && !hasEventStarted;
});

const STATUS_PENDING = 'pending';
const STATUS_COMPLETED = 'completed';
const STATUS_CONFIRMED = 'confirmed';
const STATUS_CANCELLED = 'cancelled';

const getChipColor = (status) => {
    const colorMap = {
        pending: 'orange',
        completed: 'green',
        confirmed: 'green',
        cancelled: 'red',
    };
    return colorMap[status] || 'grey'; // Default to grey if no match
}

</script>

<template>

    <Head title="Ticket" />
    <Profile>
        <!-- <div :class="$q.screen.lt.md ? '' : 'q-pa-md'"> -->
        <q-card bordered flat :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''" :square="$q.screen.lt.md">
            <q-card-actions class="text-center justify-center items-center flex">
                <Link :href="route('customer.tickets.index')" class="lt-md">
                    <q-btn icon="arrow_back" flat class="absolute-top-left q-mt-sm q-ml-sm text-black" rounded :label="$q.screen.gt.sm ? 'Go Back' : ''" no-caps/>
                </Link>
                <div class="text-h6">Ticket Details</div>
            </q-card-actions>
            <q-separator/>
            <q-card-section :class="$q.screen.lt.md ? 'q-px-sm q-py-none' : ''">
                <!-- Header -->
                <div class="row items-center">
                    <q-item class="col-12 col-md-8 q-px-none">
                        <q-item-section avatar>
                            <q-img :height="$q.screen.gt.md ? '100px' : '80px'"
                                :width="$q.screen.gt.md ? '100px' : '80px'" class="rounded-borders"
                                :src="`/storage/${ticket_order.event.cover_photo}`"></q-img>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label class="text-h6 text-weight-bold">{{ ticket_order.event.title }}</q-item-label>
                            <q-item-label class="text-caption text-grey-7">{{ ticket_order.event.location
                                }}</q-item-label>
                            <q-item-label class="text-caption text-grey-7">
                                {{ date.formatDate(ticket_order.event.date, 'dddd, MMMM D') }} - {{ formattedTime }}
                            </q-item-label>
                        </q-item-section>
                    </q-item>
                </div>

                <!-- Separator -->
                <q-separator class="q-my-md" />

                <!-- Status and Payment Details -->
                <div class="row q-col-gutter-md">
                    <div class="col-6">
                        <div class="text-caption text-grey-9 ">Status</div>
                        <q-chip 
                            :color="getChipColor(ticket_order.status)" 
                            class="text-white text-capitalize"
                            :label="ticket_order.status"
                        />
                    </div>
                    <div class="col-6">
                        <div class="text-caption text-grey-9">Payment Method</div>
                        <div>{{ ticket_order.payment_method }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-caption text-grey-9">Purchased On</div>
                        <div>{{ date.formatDate(ticket_order.created_at, 'ddd, MMM D') }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-caption text-grey-9">Total</div>
                        <div class="text-weight-bold">
                            ₱{{ parseFloat(ticket_order.amount).toLocaleString('en-PH', {
                                minimumFractionDigits: 2,
                            maximumFractionDigits: 2 }) }}
                        </div>
                    </div>
                </div>

                <!-- Separator -->
                <q-separator class="q-my-md" />

                <!-- Attendees Section -->
                <div>
                    <div class="text-weight-bold text-subtitle1 q-mb-md">Attendees</div>
                    <div class="row q-col-gutter-sm">
                        <div v-for="ticket in ticket_order.tickets" :key="ticket.id" class="col-12 col-sm-6 col-md-4">
                            <q-card bordered flat >
                                <q-item clickable class="rounded-borders">
                                    <q-item-section avatar>
                                        <q-avatar color="primary" text-color="white">{{ ticket.name[0] }}</q-avatar>
                                    </q-item-section>
                                    <q-item-section>
                                        <q-item-label>{{ ticket.name }}</q-item-label>
                                    </q-item-section>
                                    <q-item-section side>
                                        <q-chip>{{ ticket.status }}</q-chip>
                                    </q-item-section>
                                </q-item>
                            </q-card>
                        </div>
                    </div>
                </div>

                <!-- Separator -->
                <q-separator class="q-my-md" />

                <!-- QR Code Section -->
                <div class="q-mt-md text-center">
                    <div class="text-caption text-grey-9">QR Code</div>
                    <div class="q-my-sm">
                        <q-img :src="`/storage/${ticket_order.qr_code_path}`"
                            :height="$q.screen.lt.md ? '150px' : '200px'"
                            :width="$q.screen.lt.md ? '150px' : '200px'"></q-img>
                    </div>
                    <a :href="`/storage/${ticket_order.qr_code_path}`" download
                        class="q-btn q-btn-item q-mt-sm text-primary">
                        Download QR
                    </a>
                </div>
            </q-card-section>
            <q-card-actions class="justify-end q-mt-md">
                <q-btn label="Cancel" @click="cancelDialog = true" v-if="showCancelButton" outline color="negative" class="q-mr-sm" no-caps rounded/>
                <Link :href="route('conversations.chat_cashier')">
                    <q-btn label="Contact Organizer" unelevated no-caps color="primary" rounded/>
                </Link>
            </q-card-actions>
        </q-card>

        <q-dialog v-model="cancelDialog" transition-show="slide-up" transition-hide="slide-down">
            <q-card>
                <q-card-section class="row items-center q-pb-none">
                    <q-icon name="warning" color="negative" size="32px" />
                    <div class="text-h6 q-ml-md">Cancel Order</div>
                    <q-btn round icon="close" v-close-popup flat class="absolute-top-right q-mt-sm q-mr-sm" />
                </q-card-section>
                <q-card-section>
                    <q-item class="bg-negative text-white q-my-md q-pa-md rounded-borders">
                        <q-item-section>
                            <q-item-label class="text-weight-bold text-subtitle1">Are you sure you want to cancel this
                                order? This action cannot be undone.</q-item-label>
                        </q-item-section>
                    </q-item>
                </q-card-section>
                <q-card-actions class="justify-end">
                    <q-btn no-caps v-close-popup label="No" flat />
                    <q-btn no-caps :loading="cancelForm.processing" :disable="cancelForm.processing" @click="cancel()"
                        rounded unelevated label="Yes" color="negative" />
                </q-card-actions>
            </q-card>
        </q-dialog>
        <!-- </div> -->
    </Profile>
</template>

<style scoped>
a {
    color: black
}
</style>