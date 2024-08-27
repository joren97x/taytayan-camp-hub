<script setup>

import { ref } from 'vue'

const slide = ref(1)

</script>

<template>
    <q-card class="row" flat>   
        <div class="col-6" style="margin-top: 5%;">
            <slot/>
        </div>
        <div class="col-6" style="margin-top: 5%;">
            <q-img src="images/logo.jpg"></q-img>
            <p class="text-center text-h3 text-primary text-weight-bold">Taytayan Camp Hub</p>
            <q-carousel
                animated
                v-model="slide"
                arrows
                swipeable
                infinite
                class="text-center"
            >
                <q-carousel-slide :name="1" class="bg-red">
                    <q-img src="https://dashboard.paymongo.com/static/media/plugins-login-signup.ecd38b3b.png"></q-img>
                    <div class="absolute-bottom custom-caption">
                        <div class="text-h4">Milk Tea Delights</div>
                        <div class="text-subtitle1">Explore our wide range of refreshing milk tea flavors and customizable options</div>
                    </div>
                </q-carousel-slide>
                <q-carousel-slide :name="2" img-src="https://htmlcolorcodes.com/assets/images/colors/light-gray-color-solid-background-1920x1080.png">
                    <div class="absolute-bottom custom-caption">
                        <div class="text-h4">Nature Retreats</div>
                        <div class="text-subtitle1">Plan your next outdoor adventure with our camp reservation system. Discover scenic campsites and book your stay hassle-free.</div>
                    </div>    
                </q-carousel-slide>
                <q-carousel-slide :name="3" img-src="https://htmlcolorcodes.com/assets/images/colors/light-gray-color-solid-background-1920x1080.png">
                    <div class="absolute-bottom custom-caption">
                        <div class="text-h4">Exciting Events</div>
                        <div class="text-subtitle1">Browse upcoming events and concerts. Secure your tickets and be part of unforgettable experiences.</div>
                    </div>    
                </q-carousel-slide>
            </q-carousel>
            <p class="text-center q-mt-xl">© Copyright 2024 Taytayan Camp Hub</p>
        </div>
    </q-card>
</template>