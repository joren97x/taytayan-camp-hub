<script setup>

import DatePicker from './Partials/DatePicker.vue'
import { Link, useForm } from '@inertiajs/vue3'
import { date } from 'quasar'
import { ref } from 'vue'
import { useQuasar } from 'quasar'

const dialog = ref(false)
const $q = useQuasar()
const props = defineProps({
    facility: Object,
    date: Array,
    guests: Number,
    reservation_constants: Object,
    reserved_dates: Object
})

const form = useForm({
    payment_method: 'right_now',
    total: props.facility.price,
    guests: props.guests,
    check_in: props.date.from,
    check_out: props.date.to,
    facility_id: props.facility.id
})

const submit = () => {
    form.post(route('facility.pay'), {
        onSuccess: () => {
            console.log('ok')
        },
        onError: () => {
            $q.notify({
                message: `Something went wrong...`,
                color: 'negative', // or any custom color defined in the brand config
                textColor: 'white',
                position: 'top'
            })
        }
    })
}

const setBookingDates = (dates) => {
    form.check_in = dates.check_in
    form.check_out = dates.check_out
    dialog.value = false
    form.total = props.facility.price * (date.getDateDiff(form.check_out, form.check_in, 'days') + 1)
    form.clearErrors('check_in', 'check_out')
}

const incrementGuests = () => {
    if(form.guests < props.facility.guests) {
        form.guests++
    }
}

const decrementGuests = () => {
    if(form.guests > 1 && form.guests <= props.facility.guests) {
        form.guests--
    }
}

</script>

<template>
    <Head title="Checkout" />
    <div class="bg-grey-2">
        <q-card class="row justify-between bg-white q-pa-sm" flat bordered>
            <div class="col-12 text-center text-h6" style="max-width: 1280px; margin: 0 auto; position: relative;">
               <q-avatar size="lg">
                    <q-img src="../logo.png" fill="cover" />
                </q-avatar>
                    Taytayan CAMP
                <Link :href="route('customer.facilities.index')" class="absolute-left">
                    <q-btn :label="$q.screen.lt.md ? '' : 'Go back'" icon="arrow_back" color="black" flat no-caps unelevated />
                </Link>
            </div>
        </q-card>
        <div style="max-width: 1280px; margin: 0 auto;">
            <div class="q-mt-sm">
                <div class="row q-col-gutter-md">
                    <div class="col-7 col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
                        <q-card flat bordered>
                            <q-card-section>
                                <div class="text-h6 text-center">Checkout</div>
                                <q-separator class="q-my-md"/>
                                <div class="text-h6">Your trip</div>
                                <q-item class="rounded-borders" :style="`${form.errors.check_in || form.errors.check_out ? 'border: 1px solid var(--q-negative)' : ''}`">
                                    <q-item-section>
                                        <q-item-label>Dates</q-item-label>
                                        <q-item-label caption>{{ date.formatDate(form.check_in, 'MMMM D, YYYY') }} - {{ date.formatDate(form.check_out, 'MMMM D, YYYY') }}</q-item-label>
                                    </q-item-section>
                                    <q-item-section side>
                                        <q-chip @click="dialog = true" clickable>Edit</q-chip>
                                    </q-item-section>
                                </q-item>
                                <div class="text-caption text-negative" v-if="form.errors.check_in || form.errors.check_out">Check-in and Check-out dates are required</div>
                                <q-item>
                                    <q-item-section>
                                        <q-item-label>Guests</q-item-label>
                                        <q-item-label caption>{{ form.guests }}</q-item-label>
                                    </q-item-section>
                                    <q-item-section side>
                                        Max {{ facility.guests }}
                                        <div class="col-6 justify-end items-center flex">
                                            <q-btn icon="remove" size="xs" round unelevated class="bg-grey-4" @click="decrementGuests()"></q-btn>
                                            <span class="q-mx-md">{{ form.guests }}</span>
                                            <q-btn icon="add" size="xs" round unelevated class="bg-grey-4" @click="incrementGuests()"></q-btn>
                                        </div>
                                        <!-- <q-chip>Edit</q-chip> -->
                                    </q-item-section>
                                </q-item>
                                <q-separator class="q-my-md"/>
                                <div class="text-h6 q-mb-md">Choose how to pay</div>
                                <q-list>
                                    <q-card 
                                        bordered flat 
                                        class="q-pa-sm q-mb-sm" 
                                        @click="form.payment_method = 'right_now'" 
                                        :style="form.payment_method == 'right_now' ? 'border: 1px solid black' : ''"
                                    >
                                        <q-item>
                                            <q-item-section avatar>
                                                <q-avatar square>
                                                    <img src="https://cdn.quasar.dev/img/boy-avatar.png">
                                                </q-avatar>
                                                <!-- <q-radio v-model="form.payment_method" val="right_now"/> -->
                                            </q-item-section>
                                            <q-item-section>
                                                <q-item-label>Right Now</q-item-label>
                                                <q-item-label caption>Pay immediately using e-wallet, GCash, debit card, or other digital methods.</q-item-label>
                                            </q-item-section>
                                        </q-item>
                                    </q-card>
                                    <q-card 
                                        bordered flat 
                                        class="q-pa-sm q-mt-sm" 
                                        @click="form.payment_method = 'walk_in'" 
                                        :style="form.payment_method == 'walk_in' ? 'border: 1px solid black' : ''"
                                    >
                                        <q-item>
                                            <q-item-section avatar>
                                                <q-avatar square>
                                                    <img src="https://cdn.quasar.dev/img/boy-avatar.png">
                                                </q-avatar>
                                                <!-- <q-radio v-model="form.payment_method" val="right_now"/> -->
                                            </q-item-section>
                                            <q-item-section>
                                                <q-item-label>Walk In</q-item-label>
                                                <q-item-label caption>Pay with cash directly to the delivery person upon arrival.</q-item-label>
                                            </q-item-section>
                                        </q-item>
                                    </q-card>
                                </q-list>
                                <!-- <q-separator class="q-my-md"/> -->
                                <!-- <div class="text-h6">Cancellation Policy</div>
                                <div class="">
                                    You may cancel your booking anytime up to 2 days before the check-in date to receive a full refund. 
                                </div> -->
                                <q-separator class="q-my-md"/>
                                <q-banner rounded class="text-black" style="border: 1px solid var(--q-warning)">
                                    <div class="text-subtitle1">
                                        <q-icon name="warning" class="q-mr-sm" color="warning"/> Cancellation Policy
                                    </div>
                                    You may cancel your booking anytime up to 2 days before the check-in date to receive a full refund. 
                                </q-banner>
                                <q-separator class="q-my-md"/>
                                <div class="text-h6">Facility Details</div>
                                <q-item>
                                    <q-item-section avatar>
                                        <q-img 
                                            height="100px"
                                            width="100px"
                                            class="rounded-borders"
                                            src="https://a0.muscache.com/im/pictures/miso/Hosting-22774851/original/7789a5cc-f7cb-4238-ad5d-f1d71e36365c.jpeg?aki_policy=large"
                                        ></q-img>
                                    </q-item-section>
                                    <q-item-section>
                                        <q-item-label>{{ facility.name }}</q-item-label>
                                        <q-item-label caption>{{ facility.description }}</q-item-label>
                                        <q-item-label>P{{ facility.price }}</q-item-label>
                                    </q-item-section>
                                    <q-item-section side top>
                                        <div>
                                            <q-icon name="star" color="orange" size="sm"/> {{ parseFloat(facility.average_rating).toFixed(2) }}
                                        </div>
                                    </q-item-section>
                                </q-item>
                            </q-card-section>
                        </q-card>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
                        <q-card bordered flat>
                            <q-card-section>
                                <div class="text-h6">Price Details</div>
                                <q-separator class="q-my-md"/>
                                <div class="row">
                                    <div class="col-8">
                                        P{{ facility.price }} x {{ date.getDateDiff(form.check_out, form.check_in, 'days') + 1 }} nights
                                    </div>
                                    <div class="col-4 text-right">
                                        P{{ form.total }}
                                    </div>
                                </div>
                            </q-card-section>
                            <q-card-actions>
                                <q-btn class="full-width" no-caps rounded color="primary" @click="submit">Checkout</q-btn>
                            </q-card-actions>
                        </q-card>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <DatePicker 
        :dialog="dialog" 
        :reserved_dates="reserved_dates"
        :booked_dates="{
            check_in: form.check_in,
            check_out: form.check_out
        }"
        @setBookingDates="(dates) => setBookingDates(dates)" 
        @close="dialog = false" 
    />

    <!-- <div class="row q-col-gutter-md q-ma-lg">
        <div class="col-8">
            <q-list>
                <q-item 
                    tag="label" v-ripple 
                    v-for="(payment_method, index) in reservation_constants.payment_methods" 
                    :key="index"
                >
                    <q-item-section side top>
                        <q-radio v-model="form.payment_method" :val="payment_method"/>
                    </q-item-section>
                    <q-item-section>
                        <q-item-label class="text-capitalize">{{ payment_method }}</q-item-label>
                    </q-item-section>
                    <q-item-section side>
                        <q-avatar square>
                            <img src="https://cdn.quasar.dev/img/boy-avatar.png">
                        </q-avatar>
                    </q-item-section>
                </q-item>
            </q-list>
            <p>
                Check in: {{ date.from }}
            </p>
            <p>
                Check out: {{ date.to }}
            </p>
        </div>
        <div class="col-4">
            <div>
                {{ props }}
            </div>
            <q-btn class="full-width" color="primary" @click="submit">
                Check Out
            </q-btn>
        </div>
        {{ form }}
    </div> -->
</template>