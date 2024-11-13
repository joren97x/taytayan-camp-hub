
<script setup>

import { Link, Head } from '@inertiajs/vue3'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { computed, ref } from 'vue'
import { useQuasar, date } from 'quasar'
import { parse, format } from 'date-fns'

defineOptions({ layout: CustomerLayout })
const props = defineProps({
    event: Object
})

const $q = useQuasar()
const attendees = ref(props.event.min_ticket)
const formattedTime = format(parse(props.event.start_time, 'HH:mm:ss', new Date()), 'h a');

const incrementTicket = () => {
    if(attendees.value < props.event.max_ticket) {
        attendees.value++
    }
}

//bawal minus 0 or minus sa min tickets
const decrementTicket = () => {
    if(attendees.value <= props.event.max_ticket && attendees.value > props.event.min_ticket) {
        attendees.value--
    }
}

const total = computed(() => {
    return attendees.value * props.event.admission_fee
})

</script>

<template>
    <Head :title="event.title" />
    <div>
        <!-- <div class="full-width bg-grey q-my-md rounded-borders" style="height: 50vh;"> -->
        <div class="full-width q-my-md rounded-borders" style="height: 50vh; position: relative; overflow: hidden;">
            <div class="blurred-background" :style="`background-image: url('/storage/${event.cover_photo}');`"></div>
            <q-img 
                :src="`/storage/${event.cover_photo}`"
                class="rounded-borders content-wrapper" 
                height="100%"
                fit="contain"
                style="position: relative; z-index: 2;" 
            />
        </div>

        <div class="row q-mx-xs q-col-gutter-md">
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
                <div class="text-subtitle1">
                    {{ date.formatDate(event.date, 'dddd, MMMM D') }} at {{ formattedTime }}
                </div>
                <div class="text-h5 text-weight-bold">
                    {{ event.title }}
                </div>
                <div style="white-space: pre-line;">
                    {{ event.description }}
                </div>
                <q-separator class="q-my-md"/>
                <div>
                    <div class="text-h6">Date & Time</div>
                    <q-item>
                        <q-item-section avatar>
                            <q-icon name="event" />
                        </q-item-section>
                        <q-item-section>
                            {{ date.formatDate(event.date, 'dddd, MMMM D') }} - {{ formattedTime }}
                        </q-item-section>
                    </q-item>
                </div>
                <q-separator class="q-my-md"/>
                <div style="margin-bottom: 80px">
                    <div class="text-h6">Location</div>
                    <q-item>
                        <q-item-section avatar>
                            <q-icon name="location_on" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>{{ event.location }}</q-item-label>
                            <q-item-label caption>Olango Island</q-item-label>
                        </q-item-section>
                    </q-item>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 gt-sm ">
                <q-card bordered>
                    <q-card-section>
                        <q-card class="row rounded-borders q-pa-md" bordered flat>
                            <div class="col-6">
                                <div>Admission</div>
                                <div class="text-h6 text-weight-bold">P{{ total }}</div>
                            </div>
                            <div class="col-6 justify-end items-center flex">
                                <q-btn icon="remove" size="sm" round unelevated class="bg-grey-4" @click="decrementTicket"/>
                                <span class="q-mx-md text-subtitle1">{{ attendees }}</span>
                                <q-btn icon="add" size="sm" round unelevated class="bg-grey-4" @click="incrementTicket"/>
                            </div>
                        </q-card>
                    </q-card-section>
                    <q-card-actions>
                        <Link 
                            :href="route('event.checkout')" 
                            class="full-width" 
                            :data="{ event_id: event.id, attendees }" 
                            v-if="date.getDateDiff(event.date, new Date()) >= 0 && event.status == 'on_sale'"
                        >
                            <q-btn class="full-width" rounded color="primary" no-caps label="Go To Checkout"/>
                        </Link>
                        <q-btn class="full-width" v-else disable rounded color="primary" no-caps label="Event Ended"/>
                        <!-- <div class="q-mt-md flex justify-center full-width">You wont be charged yet...</div> -->
                    </q-card-actions>
                </q-card>
            </div>
        </div>
        <q-card class="bg-white fixed-bottom lt-md" bordered>
            <!-- <div class="row q-pa-none q-col-gutter-md"> -->
            <q-card-section class="row">
                <div class="col-3">
                    <div>Admission</div>
                    <div class="text-h6">P{{ total }}</div>
                </div>
                <div class="col-9 justify-end items-center flex">
                    <q-btn icon="remove" round unelevated size="sm" class="bg-grey-4" @click="decrementTicket"></q-btn>
                    <span class="q-mx-md text-subtitle1">{{ attendees }}</span>
                    <q-btn icon="add" round unelevated size="sm" class="bg-grey-4 q-mr-sm" @click="incrementTicket"></q-btn>
                    <Link 
                        :href="route('event.checkout')" 
                        :data="{ event_id: event.id, attendees }"
                        v-if="date.getDateDiff(event.date, new Date()) >= 0 && event.status == 'on_sale'"
                    >
                        <q-btn
                            label="Go To Checkout"
                            color="primary"
                            no-caps
                            rounded
                        />  
                    </Link>
                    <q-btn v-else disable rounded color="primary" no-caps label="Event Ended"/>
                    
                </div>
            </q-card-section>
        </q-card>
    </div>
  
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