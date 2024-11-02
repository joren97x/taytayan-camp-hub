<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import DatePicker from './Partials/DatePicker.vue'
import { Link, useForm, Head } from '@inertiajs/vue3'
import { date as qdate, useQuasar } from 'quasar'
import { onMounted, ref, computed } from 'vue'

defineOptions({
    layout: CustomerLayout
})

const props = defineProps({
    facility: Object,
    reserved_dates: Object,
})

const $q = useQuasar()
const form = useForm({
    facility_id: props.facility.id,
    date: {
        from: '',
        to: ''
    },
    guests: 1
})

const images = JSON.parse(props.facility.images)
const slide = ref(0)
const dialog = ref(false)

const book = () => {
    if(form.date.from && form.date.to) {
        form.get(route('facility.checkout'))
    }
    else {
        $q.notify({
            message: `Check in and Check out dates are required`,
            color: 'negative', // or any custom color defined in the brand config
            textColor: 'white',
            position: 'top'
        })
        // alert('Check in and Check out dates are required')
    }
}

const setBookingDates = (dates) => {
    console.log(dates)
    form.date.from = dates.check_in
    form.date.to = dates.check_out
    dialog.value = false
}

const fiveImages = computed(() => {
    let repeatedImages = [];
    while (repeatedImages.length < 5) {
        repeatedImages = repeatedImages.concat(images);
    }
    return repeatedImages.slice(0, 5);
})

const incrementGuests = () => {
    if(form.guests < props.facility.guests) {
        form.guests++
    }
}

const decrementGuests = () => {
    if(form.guests > 1 && form.guests <= props.facility.guests) {
        form.guests--
    }
}
</script>

<template>
    <Head :title="facility.name" />
    <div>
        <q-carousel
            swipeable
            animated
            v-model="slide"
            infinite
            navigation
            transition-prev="slide-right"
            transition-next="slide-left"
            height="280px"
            class="q-pa-none q-ma-none lt-md"
        >
            <q-carousel-slide :name="index" v-for="(image, index) in images" class="q-pa-none">
                <q-img
                    class="fit"
                    :src="`/storage/${image}`"
                />
            </q-carousel-slide>
            <template v-slot:navigation-icon="{ active, btnProps, onClick }">
                <q-btn v-if="active" size="5px" :icon="btnProps.icon" color="white" flat round dense @click="onClick" />
                <q-btn v-else size="5px" :icon="btnProps.icon" color="grey" flat round dense @click="onClick" />
            </template>
            <template v-slot:control>
                <q-carousel-control>
                    <q-chip size="md">{{ slide + 1 }} / {{ images.length }}</q-chip>
                </q-carousel-control>
            </template>
        </q-carousel>
        <div class="row q-col-gutter-sm gt-sm">
            <div class="col-md-6" style="height: 52vh;">
                <q-img :src="`/storage/${fiveImages[0]}`" height="100%" style="position: relative"></q-img>
            </div>
            <div class="col-md-6">
                <div class="row q-col-gutter-sm">
                    <div class="col-md-6" v-for="image in fiveImages.slice(1, 5)" style="height: 26vh;">
                        <q-img :src="`/storage/${image}`" height="100%" style="position: relative"></q-img>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="q-ma-md">
        <div :class="['row q-col-gutter-md', $q.screen.lt.md ? '' : 'reverse']">
            <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12 col-sm-12">
                <div class="lt-md q-mb-xs justify-between flex">
                    <div>
                        <div class="text-h6 text-capitalize">{{ facility.name }}</div>
                        <div class="text-subtitle1" >
                            {{ facility.amenities }}
                        </div>
                    </div>
                    <div>
                        Star
                    </div>
                </div>
                <q-card>
                    <q-card-section class="q-pb-xs"> 
                        <span class="text-subtitle1">P{{ facility.price }}</span>
                        kada adlaw
                    </q-card-section>
                    <q-card-section class="row q-py-none q-col-gutter-sm q-mb-sm" @click="dialog = true">
                        <!-- <q-menu v-model="dialog" persistent class="gt-sm" anchor="bottom left" self="center right"> 
                            <q-card style="width: 500px;">
                                <q-card-section class="row justify-between">
                                    <div class="col-10">
                                        <span class="text-h6">Lorem ipsum dolor sit amtet</span>
                                        <br>
                                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Odio incidunt ventore.
                                    </div>
                                    <div>
                                        <q-btn icon="close" round unelevated @click="dialog = false" v-close-popup/>
                                    </div>
                                </q-card-section>
                                <q-card-section class="q-py-none">
                                    <q-date
                                        v-model="date"
                                        range
                                        :options="options"
                                        style="width: 100%;"
                                        :subtitle="`${qdate.formatDate(form.date.from, 'MMM D, YYYY')} - ${qdate.formatDate(form.date.to, 'MMM D, YYYY')}`"
                                    >
                                    </q-date>
                                </q-card-section>
                                <q-card-actions class="row q-col-gutter-md">
                                    <div class="col-6" v-if="form.date.from && form.date.to">
                                        <q-btn class="full-width" color="accent" no-caps rounded @click="clearDates()">Clear Dates</q-btn>
                                    </div>
                                    <div :class="form.date.from && form.date.to ? 'col-6' : 'col-12'">
                                        <q-btn class="full-width" color="primary" no-caps rounded @click="setDates">Save</q-btn>
                                    </div>
                                </q-card-actions>
                            </q-card>
                        </q-menu> -->
                        <div class="col-6">
                            <q-card flat bordered >
                                <q-card-section>
                                    <div >Check-in</div>
                                    <div :class="['text-caption', form.date.to ? '' : 'text-red']">
                                        {{ form.date.from ? '9/23/2024' : 'Required' }}
                                    </div>
                                </q-card-section>
                                
                            </q-card>
                        </div>
                        <div class="col-6">
                            <q-card flat bordered >
                                <q-card-section>
                                    <div >Check-out</div>
                                    <div :class="['text-caption', form.date.to ? '' : 'text-red']">
                                        {{ form.date.to ? '9/23/2024' : 'Required' }}
                                    </div>
                                </q-card-section>
                            </q-card>
                        </div>
                    </q-card-section>
                    <q-card-section class="row q-py-none">
                        
                        <q-card class="col-12" bordered flat>
                            <q-card-section class="row">
                                <div class="col-6 items-center flex">
                                    <div>Guests</div>
                                </div>
                                <div class="col-6 justify-end items-center flex">
                                    <q-btn icon="remove" size="sm" round unelevated class="bg-grey-4" @click="decrementGuests()"></q-btn>
                                    <span class="q-mx-md text-subtitle1">{{ form.guests }}</span>
                                    <q-btn icon="add" size="sm" round unelevated class="bg-grey-4" @click="incrementGuests()"></q-btn>
                                </div>
                            </q-card-section>
                        </q-card>
                    </q-card-section>
                    <q-card-actions>
                            <q-btn class="full-width" color="primary" @click="book" rounded no-caps>Book Now</q-btn>
                    </q-card-actions>
                </q-card>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
                <div class="gt-sm justify-between flex">
                    <div>
                        <div class="text-h6 text-capitalize">{{ facility.name }}</div>
                        <div class="" >
                            {{ facility.amenities }}
                        </div>
                    </div>
                    <div class="text-subtitle1 q-mt-xs q-mr-xs">
                        <q-icon name="star" color="orange" size="sm"/> {{ parseFloat(facility.average_rating).toFixed(2) }}
                    </div>
                </div>
                <q-separator class="q-my-md" />
                <div class="text-h6">About This Place</div>
                <div class="">
                    <ul>
                        <li>Location: Olango Island</li>
                        <li>Check in time: {{ facility.rental_start }}</li>
                        <li>Check out time: {{ facility.rental_end }}</li>
                    </ul>
                </div>
                <q-separator class="q-my-md" />
                <div class="text-h6">What This Place Offers</div>
                <div class="">
                    {{ facility.amenities }}
                </div>
            </div>
        </div>
        <q-separator class="q-my-md" />
        <div class="col-12">
            <div class="text-h6 q-mb-md">Ratings and Reviews</div>
            <div class="row q-col-gutter-md">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" v-for="rating in facility.facility_ratings">
                    <q-card bordered flat>
                        <q-card-section class="row q-py-sm">
                            <div class="col-8">
                                <q-item class="q-pa-none">
                                    <q-item-section avatar>
                                        <q-avatar class="bg-secondary">
                                                <q-img 
                                                    v-if="rating.user.profile_pic"
                                                    :src="`/storage/${rating.user.profile_pic}`"
                                                    class="fit"
                                                    fit="cover"
                                                />
                                                <div v-else>{{ rating.user.first_name[0] }}</div>
                                            </q-avatar>
                                    </q-item-section>
                                    <q-item-section>
                                        <q-item-label>{{ rating.user.first_name + ' ' + rating.user.last_name }}</q-item-label>
                                        <q-item-label caption>{{ qdate.formatDate(rating.created_at, 'MMM D, YYYY') }}</q-item-label>
                                    </q-item-section>
                                </q-item>
                            </div>
                            <div class="col-4 justify-end flex">
                                <q-rating v-model="rating.rating" readonly></q-rating>
                            </div>
                        </q-card-section>
                        <q-card-section class="q-py-none">
                            <div class="q-mb-md q-mx-md">{{ rating.review }}</div>
                        </q-card-section>
                    </q-card>
                </div>
                <q-card v-if="facility.facility_ratings.length == 0" bordered flat class="flex items-center justify-center bg-grey-3 text-grey col-12 q-my-md" style="height: 100px">
                    No ratings yet...
                </q-card>
            </div>
        </div>
        <!-- <div style="height: 700px;">
            bruh
            <h1>HIII</h1>
        </div> -->
    </div>
    <!-- <q-dialog 
        v-model="dialog" 
        :maximized="$q.screen.lt.md"  
        transition-show="slide-up"
        transition-hide="slide-down"
        :position="$q.screen.lt.md ? 'bottom' : 'standard'"
    >
        <q-card style="width: 100%; ">
            <q-card-section class="row justify-between">
                <div class="col-10">
                    <span class="text-h6">Lorem ipsum dolor sit amtet</span>
                    <br>
                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Odio incidunt ventore.
                </div>
                <div>
                    <q-btn icon="close" round unelevated @click="dialog = false" v-close-popup/>
                </div>
            </q-card-section>
            <q-card-section>
                <q-date
                    v-model="date"
                    range
                    :options="options"
                    style="width: 100%;"
                    :subtitle="form.date.to && form.date.from ? `${qdate.formatDate(form.date.from, 'MMM D, YYYY')} - ${qdate.formatDate(form.date.to, 'MMM D, YYYY')}` : 'Please choose dates'"
                >
                </q-date>
            </q-card-section>
            <q-card-actions class="row q-col-gutter-md">
                <div class="col-6" v-if="form.date.from && form.date.to">
                    <q-btn class="full-width" color="accent" no-caps rounded @click="clearDates()">Clear Dates</q-btn>
                </div>
                <div :class="form.date.from && form.date.to ? 'col-6' : 'col-12'">
                    <q-btn class="full-width" color="primary" no-caps rounded @click="setDates">Save</q-btn>
                </div>
            </q-card-actions>
        </q-card>
    </q-dialog> -->
    <DatePicker :dialog="dialog" :reserved_dates="reserved_dates" @setBookingDates="(dates) => setBookingDates(dates)" @close="dialog = false" />
</template> 

<style scoped>

/* @media (max-width: 600px) {
  .gallery {
    grid-template-columns: 1fr;
    grid-template-rows: repeat(5, 1fr);
  }

  .gallery-item.main {
    grid-row: span 1;
  }
} */
</style>
