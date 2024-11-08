<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link, Head, useForm } from '@inertiajs/vue3'
import { date } from 'quasar'
import { ref } from 'vue';
import { useQuasar } from 'quasar';

defineOptions({ layout: CustomerLayout })
const props = defineProps({
    booking: Object
})

const $q = useQuasar()
const slide = ref(0)
const images = JSON.parse(props.booking.facility.images)
const completeBookingForm = useForm({})
const rateDialog = ref(false)

const ratingForm = useForm({
    rating: 0,
    review: ''
})

const completeBooking = () => {
    completeBookingForm.patch(route('customer.bookings.complete', props.booking.id), {
        onSuccess: () => {
            // completeBookingDialog.value = false
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

</script>

<template>
<div class="row q-col-gutter-md justify-center flex ">
    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5 gt-sm">
        <q-card bordered>
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
            <q-card-section>
                <div class="text-h6">{{ booking.facility.name }}</div>
                <div>{{ booking.facility.amenities }}</div>
                <div>{{ booking.facility.description }}</div>
                <div>{{ booking.facility.price }}</div>
                <!-- <q-btn class="full-width q-mt-md" rounded label="View" color="primary" no-caps unelevated/> -->
            </q-card-section>
        </q-card>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
        <div class="text-h6 text-center">Booking Details</div>
        <q-item @click="dialog = true" clickable class="lt-md">
            <q-item-section avatar>
                <q-img :src="`/storage/${JSON.parse(booking.facility.images)[0]}`" height="100px" :width="$q.screen.lt.md ? '100px' : '200px'" class="rounded-borders" />
            </q-item-section>
            <q-item-section top :class="$q.screen.lt.md ? ' q-py-md' : ''">
                <q-item-label class="text-subtitle1">{{ booking.facility.name }}</q-item-label>
                <q-item-label caption>P{{ booking.facility.description }}</q-item-label>
                <q-item-label caption>P{{ booking.facility.price }}</q-item-label>
            </q-item-section>
            <q-item-section side top>
                <div  class="q-pt-sm q-pr-sm">
                    <q-icon name="star"/> 5
                </div>
            </q-item-section>
        </q-item>
        <!-- <q-separator class="q-my-md"/> -->
        <div class="row q-col-gutter-md q-pa-md">
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
                <div class="text-caption">Purchased On</div>
                <div>{{ booking.created_at }}</div>
            </div>
            <div class="col-6">
                <div class="text-caption">Payment Method</div>
                <div>{{ booking.payment_method }}</div>
            </div>
            <div class="col-6">
                <div class="text-caption">Amount</div>
                <div class="text-weight-bold text-subtitle1">P{{ booking.total }}</div>
            </div>

            <div class="col-12">
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
            </div>

        </div>
        
        <!-- <q-btn label="Cancel Booking" color="negative" class="full-width" no-caps v-if="order.status == 'pending'"/> -->
    </div>
</div>
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
</template>
