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

</script>

<template>
    
    <Head title="Tickets" />
    <Profile>
    <!-- <div :class="$q.screen.lt.md ? '' : 'q-pa-md'"> -->
        <q-card bordered flat :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
            <q-card-actions class="justify-between lt-md">
                <div class="text-h6">
                    Ticket Details
                </div>
                <q-btn round icon="close" v-close-popup unelevated />
            </q-card-actions>
            <q-card-section :class="$q.screen.lt.md ? 'q-pa-none' : ''">
                <slot name="button" />
                <div class="row q-col-gutter-md">
                    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
                        <q-card bordered flat>
                            <q-img :src="`/storage/${ticket_order.event.cover_photo}`"></q-img>
                            <q-card-section>
                                <div class="text-h6">{{ ticket_order.event.title }}</div>
                                <div>
                                    {{ date.formatDate(ticket_order.event.date, 'ddd, MMM D') }} at {{formattedTime}}
                                </div>
                                <div class="q-mb-sm">
                                    {{ ticket_order.event.location }}
                                </div>
                                <Link :href="route('conversations.chat_cashier')" >
                                    <q-btn class="full-width " label="Contact Organizer" no-caps color="primary" rounded />
                                </Link>
                                <q-btn 
                                    class="full-width q-mt-sm" 
                                    label="Cancel"
                                    @click="cancelDialog = true" 
                                    no-caps 
                                    color="negative" 
                                    outline 
                                    rounded
                                    v-if="showCancelButton" 
                                />
                            </q-card-section>
                        </q-card>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
                        <div class="q-px-md">
                            <div class="text-h6">({{ ticket_order.tickets.length }}x) Admission</div>
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
                                <div class="text-caption text-grey-9">Status</div>
                                <q-chip>{{ ticket_order.status }}</q-chip>
                            </div>
                            <div class="col-6">
                                <div class="text-caption text-grey-9">Payment Method</div>
                                <div>{{ ticket_order.payment_method }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption text-grey-9">Purchased On</div>
                                <div>{{ date.formatDate(ticket_order.created_at, 'ddd, MMM D, h:m A') }}</div>
                            </div>
                            <div class="col-12 q-mt-md">
                                <div class="text-weight-bold">Attendees</div>
                                <div class="row">
                                    <div v-for="ticket in ticket_order.tickets" class="col-6">
                                        <q-item clickable class="rounded-borders">
                                            <q-item-section avatar>
                                                <q-avatar color="primary" text-color="white">
                                                    {{ ticket.name[0] }}
                                                </q-avatar>
                                            </q-item-section>
                                            <q-item-section>
                                                {{ ticket.name }}
                                            </q-item-section>
                                        </q-item>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <q-separator class="q-my-md" />
                        <div class="row">
                            <div class="col-12">
                                <div :class="$q.screen.lt.md ? 'text-center' : ''">
                                    Qr Code
                                    <a :href="`/storage/${ticket_order.qr_code_path}`" class="q-ml-sm" download>Download Qr</a>
                                </div>
                                <div :class="$q.screen.lt.md ? ' flex justify-center' : ''">
                                    <q-img :src="`/storage/${ticket_order.qr_code_path}`" height="200px" width="200px"></q-img>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <!-- {{ ticket_order }} -->
            </q-card-section>
        </q-card>
        <q-dialog 
            v-model="cancelDialog"
            transition-show="slide-up"
            transition-hide="slide-down"
        >
            <q-card>
                <q-card-section class="row items-center q-pb-none">
                    <q-icon name="warning" color="negative" size="32px" />
                    <div class="text-h6 q-ml-md">Cancel Order</div>
                    <q-btn round icon="close" v-close-popup flat class="absolute-top-right q-mt-sm q-mr-sm"/> 
                </q-card-section>
                <q-card-section>
                    <q-item class="bg-negative text-white q-my-md q-pa-md rounded-borders">
                        <q-item-section>
                            <q-item-label class="text-weight-bold text-subtitle1">Are you sure you want to cancel this order? This action cannot be undone.</q-item-label>
                        </q-item-section>
                    </q-item>
                </q-card-section>
                <q-card-actions class="justify-end">
                    <q-btn no-caps v-close-popup label="No" flat/>
                    <q-btn 
                        no-caps
                        :loading="cancelForm.processing"
                        :disable="cancelForm.processing"
                        @click="cancel()"
                        rounded 
                        unelevated
                        label="Yes"
                        color="negative"
                    />
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