<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import DatePicker from './Partials/DatePicker.vue'
import { Link, useForm, Head, router } from '@inertiajs/vue3'
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
const carouselSlide = ref(0)
const dialog = ref(false)
const carouselDialog = ref(false)

const book = () => {
    if(form.date.from && form.date.to) {
        form.get(route('facility.checkout'))
    }
    else {
        // $q.notify({
        //     message: `Check in and Check out dates are required`,
        //     color: 'negative', 
        //     textColor: 'white',
        //     position: 'top'
        // })
        dialog.value = true
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

// const goToCheckoutPage = () => {
//     if(form.date.from == null || form.date.to == null) {
//         dialog.value = true
//     }
//     else {
//         router.visit(route())
//     }
// }

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
            <q-carousel-slide :name="index" v-for="(image, index) in images" class="q-pa-none" @click="carouselDialog = true">
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
                <q-img :src="`/storage/${fiveImages[0]}`" height="100%" style="position: relative" @click="carouselDialog = true"/>
            </div>
            <div class="col-md-6">
                <div class="row q-col-gutter-sm">
                    <div class="col-md-6" v-for="image in fiveImages.slice(1, 5)" style="height: 26vh;">
                        <q-img :src="`/storage/${image}`" height="100%" style="position: relative" @click="carouselDialog = true"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="q-ma-sm">
        <div :class="['row q-col-gutter-md', $q.screen.lt.md ? '' : 'reverse']">
            <div class="col-md-4 col-lg-4 col-xl-4 col-xs-12 col-sm-12">
                <div class="lt-md q-mb-xs justify-between flex">
                    <div style="position: relative">
                        <div class="">
                            <div class="text-h5 text-weight-bold q-mr-xl">{{ facility.name }}</div>
                            <div style="white-space: pre-line;">{{ facility.description }}</div>
                            <!-- <div class="q-mr-xl" style="white-space: pre-line;">
                                {{ facility.amenities }}
                            </div> -->
                        </div>
                        <div class="text-subtitle1 absolute-top-right q-mr-sm">
                            <q-icon name="star" color="orange" size="sm"/> {{ parseFloat(facility.average_rating).toFixed(2) }}
                        </div>
                    </div>
                </div>
                <q-card class="gt-sm">
                    <q-card-section class="q-pb-xs"> 
                        <span class="text-subtitle1">P{{ facility.price }}</span>
                        / day
                    </q-card-section>
                    <q-card-section class="row q-py-none q-col-gutter-sm q-mb-sm" @click="dialog = true">
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
                        <q-btn 
                            class="full-width" 
                            color="primary" 
                            @click="book" 
                            rounded 
                            no-caps 
                            :label="form.date.from && form.date.to ? 'Book Now' : 'Check Availability'"
                        />
                    </q-card-actions>
                </q-card>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
                <div class="gt-sm" style="position: relative">
                    <div class="q-mr-xl">
                        <div class="text-h5 text-weight-bold">{{ facility.name }}</div>
                        <div style="white-space: pre-line;">{{ facility.description }}</div>
                        <!-- <div class="q-mr-xl" style="white-space: pre-line;">
                            {{ facility.amenities }}
                        </div> -->
                    </div>
                    <div class="text-subtitle1 q-mt-sm absolute-top-right q-mr-sm">
                        <q-icon name="star" color="orange" size="sm"/> {{ parseFloat(facility.average_rating).toFixed(2) }}
                    </div>
                </div>
                
                <q-separator class="q-my-md" />
                <div class="text-h6">What This Place Offers</div>
                <div style="white-space: pre-line;">
                    {{ facility.amenities }}
                </div>
                <q-separator class="q-my-md" />
                <div class="text-h6">About This Place</div>
                <div class="">
                    <ul>
                        <li>Location: {{ facility.location }}</li>
                        <li>Check in time: {{ facility.rental_start }}</li>
                        <li>Check out time: {{ facility.rental_end }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <q-separator class="q-my-md" />
        <div class="col-12">
            <div class="text-h6 q-mb-md">Ratings and Reviews</div>
            <q-card v-if="facility.facility_ratings.length == 0" bordered flat class="flex items-center q-pa-xl justify-center bg-grey-3 text-grey col-12">
                No ratings yet...
            </q-card>
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
                
            </div>
        </div>
    </div>
    <q-dialog 
        v-model="carouselDialog" 
        :maximized="$q.screen.lt.md"
        transition-show="slide-up"
        transition-hide="slide-down"
    >
        <q-card class="full-width full-height" style="position: relative;">
            <div class="absolute-top z-max q-mt-md text-white text-center text-subtitle1">
                {{ `${slide+1}/${images.length}` }}
            </div>
            <q-btn class="absolute-top-right z-max q-mr-sm q-mt-sm text-black" v-close-popup icon="close" round color="white" />
            <!-- <q-btn class="" icon="close" color="primary"/> -->
            <q-carousel
                swipeable
                animated
                arrows
                transition-prev="slide-right"
                transition-next="slide-left"
                v-model="slide"
                infinite
                class="items-center justify-center full-height"
            >
            <q-btn class="absolute-top-left" icon="close" color="primary"/>
                <q-carousel-slide :name="index" class="items-center flex bg-black q-pa-none" v-for="(image, index) in images">
                    <q-img :src="`/storage/${image}`"></q-img>
                </q-carousel-slide>
                <!-- <q-carousel-slide :name="2" img-src="https://cdn.quasar.dev/img/parallax1.jpg" />
                <q-carousel-slide :name="3" img-src="https://cdn.quasar.dev/img/parallax2.jpg" />
                <q-carousel-slide :name="4" img-src="https://cdn.quasar.dev/img/quasar.jpg" /> -->
            </q-carousel>
        </q-card>
    </q-dialog>
    <q-card class="bg-white fixed-bottom lt-md z-top" bordered square v-if="!dialog">
            <!-- <div class="row q-pa-none q-col-gutter-md"> -->
        <q-card-actions class="justify-between">
            <div class="">
                <div>
                    <span class="text-subtitle1 text-weight-bold">₱{{ parseFloat(facility.price).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</span>
                    night
                </div>
                <div 
                    @click="dialog = true" 
                    v-if="form.date.from && form.date.to"
                    style="text-decoration: underline;"
                >
                    {{ qdate.formatDate(form.date.from, 'MMM D') + ' - ' + qdate.formatDate(form.date.to, 'MMM D') }}
                </div>
            </div>
            <div class="justify-end items-center flex">
                    <!-- <q-btn icon="remove" round unelevated size="sm" class="bg-grey-4" @click="decrementGuests" />
                    <span class="q-mx-xs text-subtitle1">{{ form.guests }}</span>
                    <q-btn icon="add" round unelevated size="sm" class="bg-grey-4 q-mr-sm" @click="incrementGuests" /> -->
                <q-btn 
                    color="primary" 
                    @click="book" 
                    rounded 
                    no-caps 
                    :label="form.date.from && form.date.to ? 'Book Now' : 'Check Availability'"
                />
            </div>
        </q-card-actions>
    </q-card>
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
