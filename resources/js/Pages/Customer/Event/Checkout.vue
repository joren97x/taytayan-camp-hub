<script setup>

import { Link, Head } from '@inertiajs/vue3'
import { useForm, usePage } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import { useQuasar, date } from 'quasar'
import { parse, format } from 'date-fns'

const props = defineProps({
    order_constants: Object,
    event: Object,
    attendees: Number
})

const $q = useQuasar()
const page = usePage()
const attendees = ref(props.attendees)
const admission_fee = ref(props.event.admission_fee)
const formattedTime = format(parse(props.event.start_time, 'HH:mm:ss', new Date()), 'h a');
const form = useForm({
    attendees: props.attendees,
    event_id: props.event.id,
    user_id: page.props.auth.user.id,
    payment_method: 'right_now',
    amount: 0,
    ticket_holders: []
})

for(var i = 0; i < props.attendees; i++) {
    form.ticket_holders.push({
        name: '',
        email: ''
    })
}

onMounted(() => {
   setAmount()
})

const setAmount = () => {
    form.amount = attendees.value * admission_fee.value
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
    attendees.value--
    setAmount()
    console.log('should be removed')
    form.ticket_holders.splice(index, 1)
}

const addAttendee = () => {
    attendees.value++
    form.ticket_holders.push({
        name: '',
        email: ''
    })
    setAmount()
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
                <Link :href="route('customer.events.index')" class="absolute-left">
                    <q-btn :label="$q.screen.lt.md ? '' : 'Go back'" icon="arrow_back" color="black" flat no-caps unelevated />
                </Link>
            </div>
        </q-card>
        <div style="max-width: 1280px; margin: 0 auto;">
            <div class="q-my-md">
                <div class="row q-col-gutter-md">
                    <div class="col-7 col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
                        <q-card flat bordered>
                            <q-card-section>
                                <div class="text-h6 text-center">Checkout</div>
                                <q-separator class="q-my-md"/>
                                <div class="text-h6 q-mb-sm">Event Details</div>
                                <div class="full-width rounded-borders" style="height: 40vh; position: relative; overflow: hidden;">
                                    <div class="blurred-background" :style="`background-image: url('/storage/${event.cover_photo}');`"></div>
                                    <!-- Foreground image (not blurred) -->
                                    <q-img 
                                        :src="`/storage/${event.cover_photo}`"
                                        class="rounded-borders content-wrapper" 
                                        height="100%"
                                        fit="contain"
                                        style="position: relative; z-index: 2;" 
                                    />
                                </div>
                                <q-item>
                                    <q-item-section avatar>
                                        <q-icon name="event"></q-icon>
                                    </q-item-section>
                                    <q-item-section>
                                        {{ event.title }}
                                        <q-item-label caption>{{ date.formatDate(event.date, 'MMMM D, YYYY') }} - {{ formattedTime }}</q-item-label>
                                    </q-item-section>
                                </q-item>
                                <q-item>
                                    <q-item-section avatar>
                                        <q-icon name="location_on"></q-icon>
                                    </q-item-section>
                                    <q-item-section>
                                        {{ event.location }}
                                        <q-item-label caption>Event Location</q-item-label>
                                    </q-item-section>
                                </q-item>
                                <q-separator class="q-my-md"/>
                                <q-list>
                                    <div class="text-h6">
                                        Attendees
                                    </div>
                                    <div class="row q-col-gutter-sm">
                                        <div class="col-6" v-for="(attendee, index) in form.ticket_holders">
                                            <div class="q-ml-md">Ticket {{ index + 1 }}</div>
                                            <!-- <div class="row items-center q-pa-none">
                                                <div class="col-6">
                                                </div>
                                                <div class="col-6 justify-end flex">
                                                    <q-btn @click="removeAttendee(index)" flat round icon="close"></q-btn>
                                                </div>
                                            </div> -->
                                            <q-input 
                                                label="Full Name" 
                                                v-model="form.ticket_holders[index].name" 
                                                outlined 
                                                rounded
                                                :error="!!form.errors[`ticket_holders.${index}.name`]"
                                                error-message="Name is required"
                                            />
                                            <!-- :error-message="form.errors[`ticket_holders.${index}.name`]" -->

                                        </div>
                                    </div>
                                    <!-- <q-btn class="full-width" color="primary" @click="addAttendee()" no-caps rounded>Add Attendee</q-btn> -->
                                </q-list>
                                <q-separator class="q-my-md" />
                                <div class="text-h6 q-mb-md">Pay With</div>
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
                                                <q-item-label caption>E wallet, gcash, debit card etcc..</q-item-label>
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
                                                <q-item-label caption>Lorem ipsum dolor sit amet.</q-item-label>
                                            </q-item-section>
                                        </q-item>
                                    </q-card>
                                </q-list>
                                <!-- <div class="text-h6 q-my-md">Event</div>
                                <q-item>
                                    <q-item-section avatar>
                                        <q-img
                                            :src="`/storage/${event.cover_photo}`"
                                            height="80px"
                                            width="100px"
                                        />
                                    </q-item-section>
                                    <q-item-section>
                                        <q-item-label>{{ event.title }}</q-item-label>
                                        <q-item-label caption>P{{ event.admission_fee }}</q-item-label>
                                    </q-item-section>
                                    <q-item-section side>
                                        <q-item-label>{{ event.price * attendees }}</q-item-label>
                                        <q-item-label>{{ attendees }}</q-item-label>
                                    </q-item-section>
                                </q-item> -->
                            </q-card-section>
                        </q-card>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
                        <q-card bordered flat>
                            <q-card-section>
                                <div class="text-h6">Order Summary</div>
                                <q-separator class="q-my-sm"/>
                                <!-- <div>{{ event.date }} - {{ event.start_time }}</div> -->
                                <div class="row text-weight-medium">
                                    <div class=" col">{{ form.attendees }} Admission or eTicket</div>
                                    <div class=" col text-right">P{{ form.amount }}</div>
                                </div>
                                <!-- <div class="row text-h6">
                                    <div class=" col">Total</div>
                                    <div class=" col text-right">{{ form.amount }}</div>
                                </div> -->
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
    <!-- <div>
        <div class="q-mb-xl ">
            <div class="row q-col-gutter-xl">
                <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 col-xs-12">
                    <q-card flat bordered>
                        <q-card-section>
                            <q-item>
                                <q-item-section class="text-h6 text-capitalize">Event Details</q-item-section>
                            </q-item>
                            <div>
                                <div class="rounded-borders bg-grey q-mt-sm" style="height: auto">
                                    <div id="map" style="height: 250px; width: 100%;">
                                        {{ event }}
                                    </div>
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
                           
                        </q-card-section>
                    </q-card>
                </div>
                <div class="col-xl-5 col-lg-5 col-md-5 col-sm-12 col-xs-12"  style="position: relative;">
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
    </div> -->

</template>


<style scoped>

/* CSS IS HARD FRRR(i used chatGPT XD) */
/* Blurred background */
.blurred-background {
   /* Background image you want to blur */
  background-size: cover;
  background-position: center;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  filter: blur(8px); /* Apply blur effect */
  z-index: 1; /* Keep it behind the foreground content */
}

/* Foreground image (clear, no blur) */
.content-wrapper {
  position: relative;
  z-index: 2; /* Ensure it's on top of the blurred background */
}


</style>