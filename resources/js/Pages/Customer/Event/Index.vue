<script setup>

import { Head, Link } from '@inertiajs/vue3'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
// import EventCard from '@/Components/Customer/Event/EventCard.vue'
import EventCard from './Partials/EventCard.vue';

// defineOptions({
//     layout: CustomerLayout
// })

defineProps({
    upcoming_events: Object,
    past_events: Object,
})

</script>

<template>
    <Head  title="Events" />
    <CustomerLayout>
    <template v-slot:cover>
        <div style="position: absolute; width: 100vw; height: 50vh; overflow: hidden; top: 0" class="bg-grey-3">
            <div style="
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(to bottom, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0) 80%);
                z-index: 1;
            "></div>
            <q-img src="images/third.jpg" class="fit" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" />
            <div style="
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 80%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: white;
                z-index: 2;
                text-align: center;
                padding: 20px; /* For smaller screens */
            ">
                <h1 style="
                    margin: 0;
                    font-size: 3.5rem;
                    font-weight: bold;
                    text-shadow: 20px 20px 20px rgba(0, 2, 2, 2.9);
                    " 
                    class="text-h1 text-weight-bold"
                >
                    Explore Our Camp Facilities
                </h1>
                <div style="
                    font-size: 1.25rem;
                    font-weight: bold;
                    text-shadow: 10px 10px 10px rgba(10, 10, 10, 10);
                    max-width: 800px;
                ">
                    Find your perfect spot to relax and enjoy nature
                </div>
            </div>
        </div>
    </template>
    <!-- <div class="gradient-overlay" style="position: relative" v-if="upcoming_events.length > 0">
        <div class="blurred-background" :style="`background-image: url('/storage/${upcoming_events[0].cover_photo}');`"></div>
        <q-img 
            :style="`width: 100%; height: ${$q.screen.gt.sm ? '50vh' : '35vh'}; z-index: 100;`"  
            class="rounded-borders" 
            :src="`/storage/${upcoming_events[0].cover_photo}`"
            fit="contain"
        >
            <div 
                :class="['bg-transparent', $q.screen.lt.md ? ' q-pa-xs q-ma-xs' : ' q-pa-xl q-ma-md']" 
                style="z-index: 10; max-width: 500px; "
            >
                <div style="border-left: 5px solid #FF6F61; background-image: linear-gradient(to right, #FF6F61, transparent);" class="q-pa-sm q-mb-xs">
                    Upcoming Event
                </div>
                <div class=" text-h3 text-weight-bold q-mb-sm gt-sm">
                     {{ upcoming_events[0].title }}
                </div>
                <div :class="['text-subtitle2 gt-sm', $q.screen.lt.md ? 'ellipsis-2-lines' : '']">
                     {{ upcoming_events[0].description }}
                    </div>
                <div>
                    <Link :href="route('customer.events.show', upcoming_events[0].id)" class="gt-sm">
                        <q-btn color="primary" no-caps class="q-mt-sm" rounded icon="confirmation_number" label="Buy Tickets"/>
                    </Link>
                </div>
            </div>
        </q-img>
    </div>
    <div v-else style="width: 100%; height: 50vh;" class="rounded-borders bg-grey items-center flex justify-center" >
        <div class="text-center text-white">
            <div class="text-h3 q-mb-md">No Upcoming Events</div>
            <div>Check back soon for exciting new events. Stay tuned for updates!</div>
        </div>
    </div> -->
    <div class="q-ma-sm" style="margin-top: 45vh">
        <div class="text-h6 q-my-md">Upcoming Events</div>
        <div class="row q-col-gutter-lg" v-if="upcoming_events.length > 0">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-lg-6" v-for="event in upcoming_events">
                <EventCard :event="event" />
            </div>
            <!-- <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-lg-6" v-for="event in events">
                <EventCard :event="event" />
            </div> -->
        </div>
        <q-card bordered flat v-else class="items-center flex-center flex bg-grey-3 text-grey" style="height: 40vh">
            No Events Found
        </q-card>
        <div class="text-h6 q-my-md">Past Events</div>
        <div class="row q-col-gutter-lg" v-if="past_events.length > 0">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-lg-6" v-for="event in past_events">
                <EventCard :event="event" />
            </div>
        </div>
        <q-card bordered flat v-else class="text-grey items-center flex-center flex bg-grey-3" style="height: 40vh">
            No Events Found
        </q-card>
    </div>
</CustomerLayout>
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