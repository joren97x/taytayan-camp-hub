<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link, Head, useForm } from '@inertiajs/vue3'
import { date } from 'quasar'
import { ref, computed } from 'vue';
import { useQuasar } from 'quasar'
import { addDays, isBefore } from 'date-fns';

defineOptions({ layout: CustomerLayout })
const props = defineProps({
    booking: Object
})

const $q = useQuasar()
const rateDialog = ref(false)
const completeBookingDialog = ref(false)
const completeBookingForm = useForm({})
const images = JSON.parse(props.booking.facility.images)
const slide = ref(0)

const ratingForm = useForm({
    rating: 0,
    review: ''
})

const completeBooking = () => {
    completeBookingForm.patch(route('customer.bookings.complete', props.booking.id), {
        onSuccess: () => {
            completeBookingDialog.value = false
            rateDialog.value = true
        }
    })
}

const submitRatingForm = () => {
    ratingForm.post(route('customer.facility_rating.store', props.booking.facility.id), {
        onSuccess: () => {
            rateDialog.value = false
            $q.notify('Thank your for you feedback!!')
        }
    })
}

const cancelForm = useForm({})
const cancelDialog = ref(false)
const cancel = () => {
    cancelForm.patch(route('customer.bookings.cancel', props.booking.id), {
        onSuccess: () => {
            cancelDialog.value = false
            $q.notify('Booking Cancelled')
        }
    })
}

const showCancelButton = computed(() => {
    // Check if the booking status allows cancellation
    const cancellableStatuses = ['pending', 'confirmed']
    if (!cancellableStatuses.includes(props.booking.status)) {
        return false
    }

    // Check if the current date is within 2 days of the check-in date
    const twoDaysBeforeCheckIn = addDays(new Date(props.booking.check_in), -2)
    const now = new Date()
    return isBefore(now, twoDaysBeforeCheckIn)
})

</script>

<template>
<q-card bordered flat :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
            <q-card-actions class="justify-between lt-md">
                <div class="text-h6">
                    Booking Details
                </div>
                <q-btn round icon="close" v-close-popup unelevated />
            </q-card-actions>
            <q-card-section :class="$q.screen.lt.md ? 'q-py-none' : ''">
                <div class="row q-col-gutter-md">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <q-carousel
                            v-model="slide"
                            transition-prev="slide-right"
                            transition-next="slide-left"
                            animated
                            swipeable
                            navigation
                            arrows
                            height="280px"
                        >
                            <q-carousel-slide id="carousel-container" :name="index" class="q-pa-none" v-for="(image, index) in images">
                                <q-img 
                                    class="fit"
                                    :src="`/storage/${image}`"
                                />
                            </q-carousel-slide>
                            
                            <template v-slot:navigation-icon="{ active, btnProps, onClick }">
                                <q-btn v-if="active" size="5px" :icon="btnProps.icon" color="primary" flat round dense @click="onClick" />
                                <q-btn v-else size="5px" :icon="btnProps.icon" color="grey" flat round dense @click="onClick" />
                            </template>
                        </q-carousel>
                        <!-- <q-img :src="`/storage/${JSON.parse(booking.facility.images)[0]}`" class="rounded-borders" width="100%" height="300px"/> -->
                        <div class="text-h6">{{ booking.facility.name }}</div>
                        <div>{{ booking.facility.description }}</div>
                        <q-btn 
                            v-if="booking.status == 'checked_out'"
                            color="green" 
                            label="Complete Booking"
                            class=" q-mt-md full-width"
                            no-caps 
                            @click="completeBooking()"
                            :loading="completeBookingForm.processing"
                            :disable="completeBookingForm.processing"
                            rounded
                        />
                        <Link :href="route('conversations.chat_cashier')">
                            <q-btn class="full-width " label="Contact Host" no-caps color="primary" rounded />
                        </Link>
                        <q-btn 
                            label="Cancel Booking" 
                            @click="cancelDialog = true" 
                            color="negative" 
                            class="full-width q-mt-sm" 
                            no-caps 
                            rounded 
                            outline 
                            v-if="showCancelButton"
                        />
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <q-btn round icon="close" class="absolute-top-right q-mt-sm q-mr-sm gt-sm" v-close-popup unelevated />
                        <div class="text-h6">Booking Details</div>
                        <!-- <q-separator class="q-my-md"/> -->
                        <div class="row q-col-gutter-md ">
                            <div class="col-6">
                                <div class="text-caption text-grey-9">Check-in</div>
                                <div>{{ date.formatDate(booking.check_in, 'MMMM D, YYYY') }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption text-grey-9">Check-out</div>
                                <div>{{ date.formatDate(booking.check_out, 'MMMM D, YYYY') }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption text-grey-9">Guests</div>
                                <div>{{ booking.guests }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption text-grey-9">Status</div>
                                <q-chip>{{ booking.status }}</q-chip>
                            </div>
                            <div class="col-6">
                                <div class="text-caption text-grey-9">Payment Method</div>
                                <div>{{ booking.payment_method }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption text-grey-9">Amount</div>
                                <div class="text-weight-bold text-subtitle1">P{{ booking.total }}</div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </q-card-section>
        </q-card>
        <q-dialog v-model="completeBookingDialog">
        <q-card>
            <q-card-section>
                Complete this Booking?
            </q-card-section>
            <q-card-actions class="justify-end">
                <q-btn no-caps label="No" rounded/>
                <q-btn 
                    no-caps 
                    label="Complete" 
                    @click="completeBooking" 
                    :loading="completeBookingForm.processing"
                    :disable="completeBookingForm.processing"
                    rounded
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
    <q-dialog v-model="rateDialog">
        <q-card>
            <q-form @submit="submitRatingForm()">
                <q-card-section>
                    <q-btn 
                        icon="close" 
                        class="absolute-top-right q-mr-sm q-mt-sm" 
                        round 
                        unelevated 
                        v-close-popup
                    />
                    <div class="text-h6">Rate the facility</div>
                    <div class="text-subtitle1">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Voluptatum, nesciunt?</div>
                    <q-rating 
                        size="xl" 
                        v-model="ratingForm.rating" 
                    />
                    <div class="text-red" v-if="ratingForm.errors.rating ? true : false">
                        {{ ratingForm.errors.rating }}
                    </div>
                    <q-input 
                        type="textarea" 
                        v-model="ratingForm.review" 
                        filled 
                        label="Write your review here..."
                    />
                </q-card-section>
            </q-form>
            <q-card-actions>
                <q-btn 
                    class="full-width" 
                    no-caps 
                    @click="submitRatingForm" 
                    :disable="ratingForm.processing" 
                    :loading="ratingForm.processing" 
                    label="Submit" 
                    rounded
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
    <q-dialog 
        v-model="cancelDialog"
        transition-show="slide-up"
        transition-hide="slide-down"
    >
        <q-card>
            <q-card-section class="row items-center q-pb-none">
                <q-icon name="warning" color="negative" size="32px" />
                <div class="text-h6 q-ml-md">Cancel Booking</div>
                <q-btn round icon="close" v-close-popup flat class="absolute-top-right q-mt-sm q-mr-sm"/>
            </q-card-section>
            <q-card-section>
                <q-item class="bg-negative text-white q-my-md q-pa-md rounded-borders">
                    <q-item-section>
                        <q-item-label class="text-weight-bold text-subtitle1">Are you sure you want to cancel this booking? This action cannot be undone.</q-item-label>
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
</template>
