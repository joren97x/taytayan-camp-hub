<script setup>

import { ref } from 'vue'

const slide = ref(1)

</script>

<template>
    <q-card class="row items-center justify-center" flat style="height: 100vh;">   
        <div class="col-6">
            <slot/>
        </div>
        <div class="col-6">
            <div class="justify-center flex q-mt-xl">
                <q-avatar size="80px">
                    <q-img src="images/logo.jpg"></q-img>
                </q-avatar>
                <span class="text-h3 q-mt-md q-ml-md text-primary text-weight-bold">Taytayan Camp Hub</span>
            </div>
            <q-carousel
                animated
                v-model="slide"
                arrows
                swipeable
                infinite
                class="text-center"
                style="height: 70vh;"
                control-color="black"
            >
                <q-carousel-slide :name="1">
                    <q-img fill="contain" width="400px" class="q-mt-xl" height="400px" src="https://dashboard.paymongo.com/static/media/plugins-login-signup.ecd38b3b.png"></q-img>
                    <div class="absolute-bottom">
                        <div class="text-h4">Milk Tea Delights</div>
                        <div class="text-subtitle1">Explore our wide range of refreshing milk tea flavors and customizable options</div>
                    </div>
                </q-carousel-slide>
                <q-carousel-slide :name="2">
                    <q-img fill="contain" width="400px" class="q-mt-xl" height="400px" src="https://dashboard.paymongo.com/static/media/pages-login-signup.740a27da.png"></q-img>
                    <div class="absolute-bottom">
                        <div class="text-h4">Nature Retreats</div>
                        <div class="text-subtitle1">Plan your next outdoor adventure with our camp reservation system. 
                            <!-- Discover scenic campsites and book your stay hassle-free. -->
                        </div>
                    </div>    
                </q-carousel-slide>
                <q-carousel-slide :name="3">
                    <q-img fill="contain" width="400px" class="q-mt-xl" height="400px" src="https://dashboard.paymongo.com/static/media/api-login-signup.d8fe73e3.png"></q-img>
                    <div class="absolute-bottom">
                        <div class="text-h4">Exciting Events</div>
                        <div class="text-subtitle1">Browse upcoming events and concerts. Secure your tickets and be part of unforgettable experiences.</div>
                    </div>    
                </q-carousel-slide>
            </q-carousel>
            <p class="text-center q-mt-xl">© Copyright 2024 Taytayan Camp Hub</p>
        </div>
    </q-card>
</template>