<script setup>
import { Link, usePage, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useQuasar } from 'quasar'

const props = defineProps({ 
    booking: Object 
})

const $q = useQuasar()
const rateDialog = ref(false)
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

    <div>
        <q-card class="q-mb-md">
        <q-card-section>
          <div class="row justify-between">
            <!-- Facility and User Info -->
            <div>
              <div><strong>Facility:</strong> {{ booking.facility.name }}</div>
              <div><strong>Description:</strong> {{ booking.facility.description }}</div>
              <div><strong>Guests Allowed:</strong> {{ booking.facility.guests }}</div>
              <div><strong>Booked Guests:</strong> {{ booking.guests }}</div>
              <div><strong>Total Price:</strong> {{ booking.total }} USD</div>
            </div>
            
            <!-- Dates -->
            <div>
              <q-icon name="event" /> <strong>Check-in:</strong> {{ booking.check_in }}<br />
              <q-icon name="event" /> <strong>Check-out:</strong> {{ booking.check_out }}
            </div>
          </div>
        </q-card-section>

        <!-- Facility Images -->
        <q-card-section>
          <q-img
            v-for="(image, i) in JSON.parse(booking.facility.images)"
            :key="i"
            :src="`/storage/${image}`"
            class="q-mr-sm"
            style="max-width: 100px; height: 80px;"
          />
        </q-card-section>

        <!-- Status and Actions -->
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
