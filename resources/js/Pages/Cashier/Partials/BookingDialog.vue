<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import { useQuasar, date } from 'quasar'
import { defineEmits, ref } from 'vue'
import Bookings from '../Bookings.vue';

const emit = defineEmits(['close']) 
const props = defineProps({
    dialog: Boolean,
    booking: Object,
    booking_statuses: Array
})

const $q = useQuasar()
const bookingDetailsDialog = ref(false)
const checkInDialog = ref(false)
const checkOutDialog = ref(false)
const cancelDialog = ref(false)
const form = useForm({})
const cancelForm = useForm({})
const checkIn = () => {
    form.patch(route('cashier.bookings.check_in', props.booking.id), {
        onSuccess: () => {
            checkInDialog.value = false
            $q.notify('Customer Checked In')
        }   
    })
}

const checkOut = () => {
    form.patch(route('cashier.bookings.check_out', props.booking.id), {
        onSuccess: () => {
            checkOutDialog.value = false
            $q.notify('Customer Checked Out')
        }   
    })
}

const cancel = () => {
    form.patch(route('cashier.bookings.cancel', props.booking.id), {
        onSuccess: () => {
            cancelDialog.value = false
            $q.notify('Booking is Cancelled')
        }
    })
}

const statuses = props.booking_statuses.reduce((obj, status) => {
    obj[status] = status
        return obj
    }, {})

</script>

<template>
        <!-- <div class="gt-sm">
            <q-chip class="q-mr-xs" size="12px" color="green-3"v-if="booking.status == statuses.checked_in || booking.status == statuses.checked_out">
                Checked-in
            </q-chip>
            <q-chip size="12px" color="green-3"v-if="booking.status == statuses.checked_out">
                Checked-out
            </q-chip>
            <q-chip v-if="booking.status == statuses.complete">Complete</q-chip>
        </div> -->
        <div >
            <q-item clickable @click="checkInDialog = true" v-if="booking.status == statuses.pending || booking.status == statuses.confirmed">
                <q-item-section>Check-in</q-item-section>
            </q-item>
            <q-item clickable @click="checkOutDialog = true" v-if="booking.status == statuses.checked_in">
                <q-item-section>Check-out</q-item-section>
            </q-item>
            <q-item clickable @click="bookingDetailsDialog = true">
                <q-item-section>View</q-item-section>
            </q-item>
            <q-item clickable @click="cancelDialog = true" class="text-negative" v-if="booking.status == statuses.pending || booking.status == statuses.confirmed">
                <q-item-section>Cancel</q-item-section>
            </q-item>
        </div>
        <!-- <div class="gt-sm" v-if="false">
            <q-btn label="Cancel" rounded color="negative" no-caps outline class="q-mr-sm" />
            <q-btn 
                @click="checkInDialog = true" 
                no-caps
                label="Check-in"
                rounded 
                unelevated
                color="primary"
                v-if="booking.status == statuses.pending || booking.status == statuses.confirmed"
            />
            <q-btn 
                @click="checkOutDialog = true" 
                label="Check-out"
                no-caps
                rounded 
                unelevated
                color="primary"
                v-if="booking.status == booking_statuses.checked_in"
            />  
            <q-btn rounded color="primary" no-caps unelevated outline class="q-ml-sm" @click="bookingDetailsDialog = true" label="View"/>
        </div> -->
    <q-dialog 
        v-model="checkInDialog"
        :maximized="$q.screen.lt.md"  
        transition-show="slide-up"
        transition-hide="slide-down"
        :position="$q.screen.lt.md ? 'bottom' : 'standard'"     
    >
        <q-card>
            <q-card-section :class="$q.screen.lt.md ? 'q-pa-sm' : ''">
                <div class="text-subtitle1 text-weight-medium">Confirm Check-in</div>
                Are you sure you want to check in this booking for {{ booking.facility.name }} ?
                <q-btn class="absolute-top-right q-mt-xs q-mr-sm" flat round icon="close" v-close-popup/>
                <div class="row">
                    <q-item class="col-xs-12 col-sm-12">
                        <q-item-section avatar>
                            <q-img :src="`/storage/${JSON.parse(booking.facility.images)[0]}`" width="65  px" height="65    px"></q-img>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>{{ booking.user.first_name + ' ' + booking.user.last_name }}</q-item-label>
                            <q-item-label class="text-caption">{{ booking.facility.name }}</q-item-label>
                        </q-item-section>
                        <q-item-section side>
                            <q-item-label>
                                <q-chip>{{ booking.status }}</q-chip>
                            </q-item-label>
                        </q-item-section>
                    </q-item>
                    <div class="col-6">
                                <div class="text-caption">Check-in</div>
                                <div>{{ date.formatDate(booking.check_in, 'MMMM D, YYYY') }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption">Check-out</div>
                                <div>{{ date.formatDate(booking.check_out, 'MMMM D, YYYY') }}</div>
                            </div>
                </div>
            </q-card-section>
            <q-card-actions class="justify-end">
                <q-btn v-close-popup no-caps unelevated rounded>No</q-btn>
                <q-btn 
                    @click="checkIn"
                    :loading="form.processing" 
                    :disable="form.processing"
                    label="Check-in"
                    no-caps
                    rounded
                    color="primary"
                    unelevated
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
    <q-dialog 
        v-model="checkOutDialog"
        :maximized="$q.screen.lt.md"  
        transition-show="slide-up"
        transition-hide="slide-down"
        :position="$q.screen.lt.md ? 'bottom' : 'standard'"    
    >
        <q-card class="rounded-borders">
            <q-card-section :class="$q.screen.lt.md ? 'q-pa-sm' : ''">
                <div class="text-subtitle1 text-weight-medium">Confirm Check-out</div>
                Are you sure you want to check out this booking for {{ booking.facility.name }} ?
                <q-btn class="absolute-top-right q-mt-xs q-mr-sm" flat round icon="close" v-close-popup/>
                <div class="row">
                    <q-item class="col-xs-12 col-sm-12">
                        <q-item-section avatar>
                            <q-img :src="`/storage/${JSON.parse(booking.facility.images)[0]}`" width="65  px" height="65    px"></q-img>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>{{ booking.user.first_name + ' ' + booking.user.last_name }}</q-item-label>
                            <q-item-label class="text-caption">{{ booking.facility.name }}</q-item-label>
                        </q-item-section>
                        <q-item-section side>
                            <q-item-label>
                                <q-chip>{{ booking.status }}</q-chip>
                            </q-item-label>
                        </q-item-section>
                    </q-item>
                    <div class="col-6">
                        <div class="text-caption">Check-in</div>
                        <div>{{ date.formatDate(booking.check_in, 'MMMM D, YYYY') }}</div>
                    </div>
                    <div class="col-6">
                        <div class="text-caption">Check-out</div>
                        <div>{{ date.formatDate(booking.check_out, 'MMMM D, YYYY') }}</div>
                    </div>
                </div>
            </q-card-section>
            <q-card-actions class="justify-end">
                <q-btn v-close-popup no-caps rounded unelevated>Cancel</q-btn>
                <q-btn 
                    @click="checkOut"
                    :loading="form.processing" 
                    :disable="form.processing"
                    label="Check-out"
                    no-caps
                    rounded
                    color="primary"
                    unelevated
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
    <q-dialog
        v-model="bookingDetailsDialog"
        transition-show="slide-up"
        transition-hide="slide-down"
        :maximized="$q.screen.lt.md"
    >
        <q-card bordered flat :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
            <q-card-actions class="lt-md">
                <div class="text-h6">Booking Details</div>
                <q-btn round icon="close" class="absolute-top-right q-mt-xs q-mr-xs" v-close-popup unelevated />
            </q-card-actions>
            <q-card-section :class="$q.screen.lt.md ? 'q-pa-sm' : ''">
                <div class="row q-col-gutter-md">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <q-img :src="`/storage/${JSON.parse(booking.facility.images)[0]}`" class="rounded-borders" width="100%" height="300px"/>
                        <div class="text-h6">{{ booking.facility.name }}</div>
                        <div>P{{ booking.facility.description }}</div>
                        <!-- <q-btn class="full-width q-mt-md" label="View" color="primary" no-caps unelevated/> -->
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <q-btn round icon="close" class="absolute-top-right q-mt-sm q-mr-sm gt-sm" v-close-popup unelevated />
                        <div class="text-h6 gt-sm">Booking Details</div>
                        <q-separator class="q-my-md gt-sm"/>
                        <div class="row q-col-gutter-md">
                            <div class="col-6">
                                <div class="text-caption">Check-in</div>
                                <div>{{ date.formatDate(booking.check_in, 'MMMM D, YYYY') }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption">Check-out</div>
                                <div>{{ date.formatDate(booking.check_out, 'MMMM D, YYYY') }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption">Guests</div>
                                <div>{{ booking.guests }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption">Status</div>
                                <div class="text-weight-bold">{{ booking.status }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption">Booked at</div>
                                <div class="">{{ date.formatDate(booking.created_at, 'MMM D, YYYY') }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption">Payment Method</div>
                                <div>{{ booking.payment_method }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption">Amount</div>
                                <div class="text-weight-bold text-subtitle1">₱{{ parseFloat(booking.total).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</div>
                            </div>
                            <div class="col-12">
                                <div class="text-h6">Guest</div>
                                <q-item>
                                    <q-item-section avatar>
                                        <q-avatar size="50px" color="primary" class="text-white">
                                            <q-img class="fit" fit="cover" :src="`/storage/${booking.user.profile_pic}`" v-if="booking.user.profile_pic"/>
                                            <div v-else>
                                                {{ booking.user.first_name[0] }}
                                            </div>
                                        </q-avatar>
                                    </q-item-section>
                                    <q-item-section>
                                        <q-item-label>{{ booking.user.first_name + ' ' + booking.user.last_name }}</q-item-label>
                                        <q-item-label caption v-if="booking.user.contact_number">{{ booking.user.contact_number }}</q-item-label>
                                        <q-item-label caption>{{ booking.user.email }}</q-item-label>
                                    </q-item-section>
                                    <q-item-section side>
                                        <Link :href="route('cashier.conversations.show', booking.user.id)">
                                            <q-btn round icon="chat" color="primary"/>
                                        </Link>
                                    </q-item-section>
                                </q-item>
                            </div>
                        </div>
                        <!-- <q-btn label="Cancel Booking" color="negative" class="full-width" no-caps v-if="order.status == 'pending'"/> -->
                    </div>
                </div>
            </q-card-section>
        </q-card>
    </q-dialog>
    <q-dialog 
        v-model="cancelDialog" 
        persistent 
        :maximized="$q.screen.lt.md"
        transition-show="slide-up"
        transition-hide="slide-down"
        :position="$q.screen.lt.md ? 'bottom' : 'standard'"
    >
    <q-card>
            <q-card-section :class="$q.screen.lt.md ? 'q-pa-sm' : ''">
                <div class="text-subtitle1 text-weight-medium">Cancel Booking</div>
                Are you sure you want to cancel this booking for {{ booking.facility.name }}? This cannot be undone
                <q-btn class="absolute-top-right q-mt-xs q-mr-sm" flat round icon="close" v-close-popup/>
                <div class="row">
                    <q-item class="col-xs-12 col-sm-12 bg-negative text-white rounded-borders">
                        <q-item-section avatar>
                            <q-img :src="`/storage/${JSON.parse(booking.facility.images)[0]}`" width="65  px" height="65    px"></q-img>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>{{ booking.user.first_name + ' ' + booking.user.last_name }}</q-item-label>
                            <q-item-label class="text-caption text-white">{{ booking.facility.name }}</q-item-label>
                        </q-item-section>
                        <q-item-section side>
                            <q-item-label>
                                <q-chip>{{ booking.status }}</q-chip>
                            </q-item-label>
                        </q-item-section>
                    </q-item>
                    <div class="col-xs-6 col-sm-6">
                        <div class="text-caption text-grey">
                            Dates
                        </div>
                        {{ date.formatDate(booking.check_in, 'MMM D, YYYY') }} - {{ date.formatDate(booking.check_out, 'MMM D, YYYY') }}
                    </div>
                </div>
            </q-card-section>
            <q-card-actions class="justify-end">
                <q-btn v-close-popup no-caps unelevated rounded>No</q-btn>
                <q-btn 
                    @click="cancel"
                    :loading="cancelForm.processing" 
                    :disable="cancelForm.processing"
                    label="Cancel Booking"
                    no-caps
                    rounded
                    color="negative"
                    unelevated
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>