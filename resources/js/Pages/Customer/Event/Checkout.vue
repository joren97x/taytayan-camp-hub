<script setup>

import { Link, Head } from '@inertiajs/vue3'
import { useForm, usePage } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import { useQuasar } from 'quasar'

const props = defineProps({
    order_constants: Object,
    event: Object,
    attendees: Number
})

const $q = useQuasar()
const page = usePage()
console.log(props.event.admission_fee)
console.log(props.attendees)
const form = useForm({
    attendees: props.attendees,
    event_id: props.event.id,
    user_id: page.props.auth.user.id,
    payment_method: 'gcash',
    amount: props.event.admission_fee * props.attendees,
    ticket_holders: []
})

    for(var i = 0; i < props.attendees; i++) {
        form.ticket_holders.push({
            name: '',
            email: ''
        })
    }

const submit = () => {
    form.post(route('event.pay'), {
        onSuccess: (response) => {
            console.log(response)
        },
        onError: (err) => {
            console.log(err)
            $q.notify(err.error)
        }
    })
}

const removeAttendee = (index) => {
    console.log('should be removed')
    form.ticket_holders.splice(index, 1)
}

const addAttendee = () => {
    form.ticket_holders.push({
        name: '',
        email: ''
    })
}

</script>

<template>
    
    <Head title="Checkout" />
    <div>
        <Link :href="route('customer.events.index')">
            <q-btn label="Go back" icon="arrow_back" color="blue" flat no-caps unelevated class="q-mx-xl q-my-md" />
        </Link>
        <div class="q-mb-xl q-mx-xl">
            {{ attendees }}
            <div class="row q-col-gutter-xl">
                <div class="col-7">
                    <q-card flat bordered>
                        <q-card-section>
                            <q-item>
                                <q-item-section class="text-h6 text-capitalize">Event Details</q-item-section>
                            </q-item>
                            <q-item v-show="form.mode == 'delivery'">
                                <q-item-section avatar>
                                    <q-icon name="location_on"></q-icon>
                                </q-item-section>
                                <q-item-section>
                                    {{ $page.props.auth.user.phone_number }}
                                    <q-item-label>{{ $page.props.auth.user.address }}</q-item-label>
                                </q-item-section>
                            </q-item>
                            <div>
                                <div class="rounded-borders bg-grey q-mt-sm" style="height: auto">
                                    <div id="map" style="height: 250px; width: 100%;">
                                        {{ event }}
                                    </div>
                                    <!-- <img :src="`https://maps.googleapis.com/maps/api/staticmap?center=Berkeley,CA&zoom=14&size=400x400&key=${google_maps_api_key}`" alt=""> -->
                                </div>
                                <q-item>
                                    <q-item-section avatar>
                                        <q-icon name="home"></q-icon>
                                    </q-item-section>
                                    <q-item-section class="text-h6">
                                        Event Location
                                        <q-item-label caption>{{ event.location }}</q-item-label>
                                    </q-item-section>
                                </q-item>
                            </div>
                            <q-separator class="q-my-md" />
                            <q-list>
                                <div class="text-h6">
                                    Attendees
                                </div>
                                <!-- :error="form.errors.email ? true : false"
                                :error-message="form.errors.email" -->
                                <!-- {{ form.errors.ticket_holders.0.name }} -->
                                <p class="text-green">need to have some error handling here!!!</p>
                                <q-list-item v-for="(attendee, index) in form.ticket_holders">
                                    Attendee {{ index }}
                                    <q-btn color="red" @click="removeAttendee(index)">Remove</q-btn>
                                    <div class="row q-col-gutter-md">
                                        <div class="col-6">
                                            <q-input 
                                                label="Name" 
                                                v-model="form.ticket_holders[index].name" 
                                                filled
                                                :error="!!form.errors[`ticket_holders.${index}.name`]"
                                                :error-message="form.errors[`ticket_holders.${index}.name`]"
                                            />
                                        </div>
                                        <div class="col-6">
                                            <q-input 
                                                label="Email Address" 
                                                v-model="form.ticket_holders[index].email" 
                                                filled
                                                :error="!!form.errors[`ticket_holders.${index}.email`]"
                                                :error-message="form.errors[`ticket_holders.${index}.email`]"
                                            />
                                        </div>
                                    </div>
                                </q-list-item>
                                <q-btn class="full-width" color="primary" @click="addAttendee()">Add attendee</q-btn>
                            </q-list>
                            <q-separator class="q-my-md" />
                            <q-item>
                                <q-item-section class="text-h6">Pay With</q-item-section>
                                <q-item-section side>
                                    <q-chip :class="$q.dark.isActive ? 'bg-grey-9' : ''">Required</q-chip>
                                </q-item-section>
                            </q-item>
                            <q-list>
                                <q-item 
                                    tag="label" v-ripple 
                                    v-for="(payment_method, index) in order_constants.payment_methods" 
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
                            <q-separator class="q-my-md" />
                            <!-- <q-item>
                                <q-item-section class="text-h6">Order Summary</q-item-section>
                                <q-item-section side>
                                    <q-chip :class="$q.dark.isActive ? 'bg-grey-9' : ''">{{ items.length }} items</q-chip>
                                </q-item-section>
                            </q-item>
                            <q-list>
                                <FoodCardItem :item="item" v-for="(item, index) in items" :key="index" />
                            </q-list> -->
                        </q-card-section>
                    </q-card>
                </div>
                <div class="col-5"  style="position: relative;">
                    {{ form }}
                    <div style="position: sticky; top: 50px">
                        <q-card>
                            <q-card-section>
                                <q-item>
                                    <q-item-section class="text-h6">Order Summary</q-item-section>
                                </q-item>
                                <q-item>
                                    <q-item-section>Subtotal</q-item-section>
                                    <q-item-section side>
                                        {{ form.amount }}
                                    </q-item-section>
                                </q-item>
                                <q-item v-if="form.mode == 'delivery'">
                                    <q-item-section>delivery fee</q-item-section>
                                    <q-item-section side>
                                        P5.00
                                    </q-item-section>
                                </q-item>
                                <q-separator/>
                                <q-item class="text-h6">
                                    <q-item-section>Total</q-item-section>
                                    <q-item-section side>
                                        {{  form.amount  }}
                                    </q-item-section>
                                </q-item>
                            </q-card-section>
                        </q-card>
                        <q-btn class="full-width q-mt-xl" @click="submit" label="Continue to payment" no-caps color="blue" size="lg"></q-btn>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<style scoped>

#map-container {
  position: relative;
}

#map {
  width: 100%;
  height: 500px; /* or any desired height */
}

#map-buttons {
  position: absolute;
  bottom: 10px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 10px;
}

</style>