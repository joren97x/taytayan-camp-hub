<script setup>

import { Head } from '@inertiajs/vue3'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
// import EventCard from '@/Components/Customer/Event/EventCard.vue'
import EventCard from './Partials/EventCard.vue';

defineOptions({
    layout: CustomerLayout
})

defineProps({
    events: Object
})

</script>

<template>
    <Head  title="Events" />
    <div class="gradient-overlay">
        <q-img 
            style="width: 100%; height: 50vh;"
            class="rounded-borders" 
            src="https://cdn.evbstatic.com/s3-build/fe/build/images/39ac4703250a1d0fb15911c2c5f10174-generic_1_desktop.webp"
        >
            <div 
                :class="['bg-transparent', $q.screen.lt.md ? ' q-pa-xs q-ma-xs' : ' q-pa-xl q-ma-md']" 
                style="z-index: 10; max-width: 500px; "
            >
                <div style="border-left: 5px solid #FF6F61; background-image: linear-gradient(to right, #FF6F61, transparent);" class="q-pa-sm q-mb-xs">
                    Upcoming Event
                </div>
                <div class=" text-h3 text-weight-bold q-mb-sm">
                    Under The Sky Disco Party SKibidi Gyatt
                </div>
                <div :class="['text-subtitle2', $q.screen.lt.md ? 'ellipsis-2-lines' : '']">
                    Let's GO DISCO sa OLANGO this Wednesday hello annyeong
                    The text is positioned absolutely within the gradient-container to ensure it appears on top of the image and gradient.
                </div>
                <div>
                    <q-btn color="primary" no-caps class="q-mt-sm" rounded icon="confirmation_number" label="Buy Tickets"/>
                </div>
            </div>
        </q-img>
    </div>
    <div class="q-ma-md">
        <div class="text-h6 q-my-md">Upcoming Events</div>
        <div class="row q-col-gutter-lg">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-lg-6" v-for="event in events">
                <EventCard :event="event" />
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-lg-6" v-for="event in events">
                <EventCard :event="event" />
            </div>
        </div>
        <div class="text-h6 q-my-md">Past Events</div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-lg-6" v-for="event in events">
                <EventCard :event="event" />
            </div>
        </div>
    </div>
</template>

<style scoped>
.gradient-overlay {
  position: relative;
}

.q-img {
  position: relative;
  width: 100%;
  height: auto;
}

/* Gradient overlay applied to the whole container */
.gradient-overlay::after {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(to right, rgb(77, 76, 76) 20%, rgba(121, 120, 120, 0) 50%);
  pointer-events: none; /* Prevents any interaction */
  z-index: 5; /* Ensures it's behind the text but on top of the image */
}

/* Styles for the text container */
.text-container {
  position: absolute;
  z-index: 10;
  bottom: 10px;
  left: 10px;
  color: white;
  background-color: rgba(0, 0, 0, 0.6); /* Dark background behind the text */
  padding: 10px;
  border-radius: 4px;
  max-width: 80%;
}
</style>

<!-- <template>
    <Head title="Milktea Menu" />
    <div>
        <q-img
            cover
            src="masarap milktea.jpeg"
            :ratio="16/9"
            height="300px"
            class="rounded-borders"
        />
        <p class="q-mt-md">
            <span class="text-h3">Unsay Title</span>
            <br>
            <span>San Vicente, Olango Island</span>
        </p>
        <div class="row q-col-gutter-md">
            <div class="col-8">
                <p class="text-h6">Closest Upcoming Event</p>
                <EventCard :event="events[0]" />
            </div>
            <div class="col-4">
                <q-card bordered>
                    <div style="height: 200px;" class="bg-grey">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d123065.8494400433!2d123.92886618907012!3d10.25398460253611!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a99184b152420b%3A0x6bebeab9d8bca659!2sRJC%20CAFE!5e0!3m2!1sen!2sph!4v1724885213335!5m2!1sen!2sph" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <q-card-section>
                        <q-item title="San vicente olango" subtitle="boang">
                            <q-item-section avatar>
                                <q-icon name="signal_wifi_off" />
                            </q-item-section>
                            <q-item-section >
                                <q-item-label>RJC CAFE</q-item-label>
                                <q-item-label caption>725Q+CHH, Lapu-Lapu City, Cebu</q-item-label>
                            </q-item-section>
                            <q-item-section side>Side</q-item-section>
                        </q-item>
                    </q-card-section>
                </q-card>
            </div>
        </div>
        <div class="row q-my-lg">
            <div class="col-8">
                <span class="text-h6">Events</span>
                <br>
                <span>Open till 8AM to 5PM or ambot</span>
            </div>
            <div class="col-4">
                <q-input placeholder="Search..." filled>
                    <template v-slot:prepend>
                        <q-icon name="search"></q-icon>
                    </template>
                </q-input>
            </div>
        </div>
        <div class="row" style="border: 1px solid black;">
            ari dire kay mga categories unya na kay di ko kamao mobuhat
        </div>
        <div  class="q-mt-md category">
            <p class="text-h6">Upcoming Events</p>
            <div class="row q-col-gutter-md">
                <div class="col-6" v-for="event in events">
                    <EventCard :event="event" />
                </div>
            </div>
        </div>
        <div  class="q-mt-md category">
            <p class="text-h6">Past Events</p>
            <div class="row q-col-gutter-md">
                <div class="col-6" v-for="event in events">
                    <EventCard :event="event" />
                </div>
            </div>
        </div>
        
    </div>
   
    <div style="height: 200px;" class="bg-grey q-mt-md">
        ang footer ayaw kalimti
    </div>
</template> -->