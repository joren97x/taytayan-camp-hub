<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import EventCard from '@/Components/Customer/Event/EventCard.vue'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    events: Object
})

</script>

<template>
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
            <!-- <br>
            <span><q-icon name="star"></q-icon>4.6 • (6)</span> -->
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
        <!-- <q-separator/> -->
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
    <!-- <div class="q-my-md">
        <div class="text-h6">Rating and Reviews</div>
        <div><q-icon name="star"></q-icon> 4.7 • 110+ Ratings • 3 Reviews</div>
    </div>
    <div class="row q-col-gutter-md">
        <div class="col-6" v-for="n in 5">
            <q-card bordered>
                <q-card-section>
                    <span class="text-subtitle1">John Doe</span>
                    <br>
                    <q-rating></q-rating> • 02/06/24
                    <br>
                    Ive tried different varieties of boba drink from numerous places and Little Sweet became an instant one of my favorite boba drinks due 
                    to its deliciousness and exactly what I ordered 😊
                </q-card-section>
            </q-card>
        </div>
    </div> -->
    <div style="height: 200px;" class="bg-grey q-mt-md">
        ang footer ayaw kalimti
    </div>
</template>