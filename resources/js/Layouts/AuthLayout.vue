<script setup>

import { ref } from 'vue'

const slide = ref(1)

</script>

<template>
    <q-card class="row items-center justify-center" flat style="height: 100vh;">   
        <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 items-center" style="max-width: 500px">
            <div class="justify-center items-center flex q-px-md">
                <q-avatar size="50px">
                    <q-img src="images/logo.jpg"></q-img>
                </q-avatar>
                <span class="text-h6 q-ml-md text-primary text-weight-bold">Taytayan Camp Hub</span>
            </div>
            <slot/>
        </div>
        <div class="col-6 gt-sm">
            <q-carousel
                animated
                v-model="slide"
                arrows
                swipeable
                infinite
                class="text-center q-mx-xl"
                style="height: 60vh;"
                control-color="black"
            >
                <q-carousel-slide :name="1">
                    <q-img fill="contain" width="400px" height="400px" src="/hp_product.png"></q-img>
                    <div class="absolute-bottom">
                        <div class="text-h5">Milk Tea Delights</div>
                        <div class="text-subtitle1">Explore our wide range of refreshing milk tea flavors and customizable options</div>
                    </div>
                </q-carousel-slide>
                <q-carousel-slide :name="2">
                    <q-img fill="contain" width="400px" height="400px" src="/hp_facility.png"></q-img>
                    <div class="absolute-bottom">
                        <div class="text-h5">Nature Retreats</div>
                        <div class="text-subtitle1">Plan your next outdoor adventure with our camp reservation system. 
                            <!-- Discover scenic campsites and book your stay hassle-free. -->
                        </div>
                    </div>    
                </q-carousel-slide>
                <q-carousel-slide :name="3">
                    <q-img fill="contain" width="400px" height="400px" src="/hp_event.png"></q-img>
                    <div class="absolute-bottom">
                        <div class="text-h5">Exciting Events</div>
                        <div class="text-subtitle1">Planning a night out? Reserve your tickets online for exclusive events like discos and more!                        </div>
                    </div>    
                </q-carousel-slide>
            </q-carousel>
            <p class="text-center q-mt-xl">© Copyright 2024 Taytayan Camp Hub</p>
        </div>
    </q-card>
</template>