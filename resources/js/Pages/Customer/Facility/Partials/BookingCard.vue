<script setup>
import { Link, usePage, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import { date } from 'quasar'
import { useQuasar } from 'quasar'

const props = defineProps({ 
    booking: Object 
})

const $q = useQuasar()
const rateDialog = ref(false)
const dialog = ref(false)
const completeBookingDialog = ref(false)
const completeBookingForm = useForm({})
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

</script>

<template>
    <q-card bordered flat>
        <q-item @click="dialog = true" clickable>
            <q-item-section avatar>
                <q-img :src="`/storage/${JSON.parse(booking.facility.images)[0]}`" height="100px" width="200px" class="rounded-borders" />
            </q-item-section>
            <q-item-section top>
                <q-item-label class="text-subtitle1">{{ booking.facility.name }}</q-item-label>
                <q-item-label caption>{{ date.formatDate(booking.check_in, 'MMM D, YYYY') + ' - ' + date.formatDate(booking.check_out, 'MMM D, YYYY')}}</q-item-label>
                <q-item-label caption>P{{ booking.total }}</q-item-label>
            </q-item-section>
            <q-item-section side top>
                {{ booking.status }}
            </q-item-section>
        </q-item>
    </q-card>
    <!-- <div>
        <q-card class="q-mb-md">
        <q-card-section>
          <div class="row justify-between">
            <div>
              <div><strong>Facility:</strong> {{ booking.facility.name }}</div>
              <div><strong>Description:</strong> {{ booking.facility.description }}</div>
              <div><strong>Guests Allowed:</strong> {{ booking.facility.guests }}</div>
              <div><strong>Booked Guests:</strong> {{ booking.guests }}</div>
              <div><strong>Total Price:</strong> {{ booking.total }} USD</div>
            </div>
            
            <div>
              <q-icon name="event" /> <strong>Check-in:</strong> {{ booking.check_in }}<br />
              <q-icon name="event" /> <strong>Check-out:</strong> {{ booking.check_out }}
            </div>
          </div>
        </q-card-section>

        <q-card-section>
          <q-img
            v-for="(image, i) in JSON.parse(booking.facility.images)"
            :key="i"
            :src="`/storage/${image}`"
            class="q-mr-sm"
            style="max-width: 100px; height: 80px;"
          />
        </q-card-section>

        <q-card-actions align="right">
          <q-chip color="orange" outline>{{ booking.status }}</q-chip>
          <q-btn color="primary" label="View Order" @click="viewOrder(booking)" />
          <q-btn v-if="booking.status == 'checked_out'" 
                 color="green" 
                 label="Complete Order wf" 
                 @click="completeBooking()"
                 :loading="completeBookingForm.processing"
                 :disable="completeBookingForm.processing"
                 />
        </q-card-actions>
        
      </q-card>
    </div> -->

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
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                        <q-img :src="`/storage/${JSON.parse(booking.facility.images)[0]}`" class="rounded-borders" width="100%" height="300px"/>
                        <div class="text-h6">{{ booking.facility.name }}</div>
                        Olango Island
                        <!-- <q-btn class="full-width q-mt-md" label="View" color="primary" no-caps unelevated/> -->
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <q-btn round icon="close" class="absolute-top-right q-mt-sm q-mr-sm gt-sm" v-close-popup unelevated />
                        <div class="text-h6">Booking Details</div>
                        <q-separator class="q-my-md"/>
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
                                <div class="text-caption">Amount</div>
                                <div class="text-weight-bold text-subtitle1">P{{ booking.total }}</div>
                            </div>
                        </div>
                        <q-btn 
                            v-if="booking.status == 'checked_out'"
                            color="green" 
                            label="Complete Booking"
                            class=" q-mt-md full-width"
                            no-caps 
                            @click="completeBooking()"
                            :loading="completeBookingForm.processing"
                            :disable="completeBookingForm.processing"
                        />
                        <!-- <q-btn label="Cancel Booking" color="negative" class="full-width" no-caps v-if="order.status == 'pending'"/> -->
                    </div>
                </div>
            </q-card-section>
        </q-card>
            
    </q-dialog>

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
