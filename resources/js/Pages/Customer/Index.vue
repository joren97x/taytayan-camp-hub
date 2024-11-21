<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import FeaturedProductCard from './Product/Partials/FeaturedProductCard.vue'
import EventCard from './Event/Partials/EventCard.vue'
import FacilityCard from './Facility/Partials/FacilityCard.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import { formatDistanceToNow } from 'date-fns'

// defineOptions({ layout: CustomerLayout })
const props = defineProps({ products: Object, events: Object, facilities: Object, ratings: Object })

const productContainer = ref(null)
const facilityContainer = ref(null)
const eventContainer = ref(null)
const feedbackContainer = ref(null)

const scrollLeftFeatured = (category) => {
    const container = setContainer(category)
    container.scrollBy({
        left: -container.clientWidth, // Scroll left by the width of the container
        behavior: 'smooth'
    })
}

const scrollRightFeatured = (category) => {
    const container = setContainer(category)
    container.scrollBy({
        left: container.clientWidth, // Scroll right by the width of the container
        behavior: 'smooth'
    })
}

const setContainer = (category) => {
    let container = null
    switch(category) {
        case 'products':
            container = productContainer.value
            break
        case 'events':
            container = eventContainer.value
            break
        case 'facilities':
            container = facilityContainer.value
            break
        case 'feedback':
            container = feedbackContainer.value
            break
    }
    return container
}

const testimonials = [
  {
    title: "Phasellus feugiat lacus vitae neque ornare!",
    description:
      "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus feugiat lacus vitae neque ornare, vitae libero.",
    username: "@buckybns",
    rating: 4,
  },
  {
    title: "Amazing experience!",
    description:
      "I had an amazing time, and everything went smoothly! Highly recommended.",
    username: "@janedoe",
    rating: 5,
  },
  {
    title: "Would love to come back!",
    description:
      "The service was top-notch, and the experience was unforgettable. I can't wait to return!",
    username: "@johndoe",
    rating: 5,
  },
];

</script>

<template>

    <Head title="Home" />
    <!-- <video
        autoplay
        muted
        loop
        class="q-mt-xl"
        id="myVideo"
        :style="`
            position: absolute;
            top: 0;
            left: 0;
            width: ${$q.screen.lt.md ? '100vw' : '99vw'};
            height: ${$q.screen.lt.md ? '40vh' : '80vh'};
            object-fit: cover;
            z-index: -1;
        `"
    >
        <source src="taytayancamp.mp4" type="video/mp4" />
    </video>
    <div class="text-white items-end q-mt-xl flex text-center justify-center" 
        :style="`
            background: rgba(0, 0.0, 0.0, 0.5);
            width: ${$q.screen.lt.md ? '100vw' : '99vw'};
            top: 0;
            left: 0;
            height: ${$q.screen.lt.md ? '40vh' : '80vh'};
            position: absolute;
         `"
    >
        <div :class="`text-h6 text-weight-bold q-mb-${$q.screen.lt.md ? 'md' : 'xl'} bounce-animation`" style="width: 60%">
            Explore Taytayan Camp in Olango Island
			<br>
			<q-btn icon="south" flat round/>
        </div>
    </div> -->
    <CustomerLayout>
        <template v-slot:cover>
            <div
                :style="`position: absolute; width: 100vw; height: ${$q.screen.gt.sm ? '60vh' : '40vh'}; overflow: hidden; top: 0`">
                <div style="
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0) 80%);
        z-index: 1;
      ">
                    <!-- <div class="absolute-bottom text-center text-subtitle1 bounce-animation q-mb-sm">
                        <div>Explore Taytayan Camp</div>
                        <q-btn icon="south" flat round />
                    </div> -->

                </div>
                <q-img src="taytayan.jpg" class="fit"
                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" />
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
      ">
                    <div style="
          margin: 0;
          font-weight: bold;
          text-shadow: 20px 20px 20px rgba(0, 2, 2, 2.9);
        " :class="[$q.screen.gt.sm ? 'text-h2' : 'text-h5 q-pt-xl', 'text-weight-bold']">
                        Welcome to Taytayan Camp
                    </div>
                    <div :class="[$q.screen.gt.sm ? 'text-h6' : 'text-subtitle1', 'text-weight-medium']" style="
          font-weight: bold;
          text-shadow: 10px 10px 10px rgba(10, 10, 10, 10);
          max-width: 800px;
        ">
                        Sip, Explore, and Stay - Discover More at Taytayan Camp Adventure Island
                    </div>
                </div>
            </div>
        </template>

        <div class="q-px-xs q-py-md q-mt-xl" :style="`${$q.screen.lt.md ? 'margin-top: 30vh' : 'margin-top: 50vh'};`">
            <div>
                <div class="text-h5 text-center text-weight-bold q-mt-xl">
                    Your Next Adventure Awaits
                </div>
                <div class="text-center text-subtitle1 q-mb-lg">
                    Book your stay, grab your favorite treats, or secure your event tickets with ease.
                </div>

                <div class="row q-col-gutter-md">
                    <!-- Card 1 -->
                    <div class="col-12 col-md-4">
                        <q-card flat bordered class="hover-card">
                            <q-img src="images/first.jpg" class="rounded-borders fit" style="height: 100%; max-height: 250px" />
                            <q-card-section class="q-pb-none">
                                <div class="text-subtitle2 q-mb-md">
                                    Order milk teas, shakes, and snacks crafted to satisfy your cravings—online or on-site.
                                </div>
                            </q-card-section>
                            <q-card-actions align="center">
                                <Link :href="route('customer.products.index')">
                                    <q-btn rounded no-caps label="Order Delicious Treats" color="primary" class="hover-btn" />
                                </Link>
                            </q-card-actions>
                        </q-card>
                    </div>

                    <!-- Card 2 -->
                    <div class="col-12 col-md-4">
                        <q-card flat bordered class="hover-card">
                            <q-img src="images/second.jpg" class="rounded-borders fit" style="height: 250px; max-height: 250px" />
                            <q-card-section class="q-pb-none">
                                <div class="text-subtitle2 q-mb-md">
                                    Relax in a cozy room or tent surrounded by nature. Perfect for a day or weekend escape.
                                </div>
                            </q-card-section>
                            <q-card-actions align="center">
                                <Link :href="route('customer.facilities.index')">
                                    <q-btn rounded no-caps label="Book Your Adventure" color="primary" class="hover-btn" />
                                </Link>
                            </q-card-actions>
                        </q-card>
                    </div>

                    <!-- Card 3 -->
                    <div class="col-12 col-md-4">
                        <q-card flat bordered class="hover-card">
                            <q-img src="images/third.jpg" class="rounded-borders fit"style=" height: 250px; max-height: 250px" />
                            <q-card-section class="q-pb-none">
                                <div class="text-subtitle2 q-mb-md">
                                    Don't miss events at Taytayan Camp! Secure your tickets and make lasting memories.
                                </div>
                            </q-card-section>
                            <q-card-actions align="center">
                                <Link :href="route('customer.events.index')">
                                    <q-btn rounded no-caps label="Grab Your Tickets" color="primary" class="hover-btn" />
                                </Link>
                            </q-card-actions>
                        </q-card>
                    </div>
                </div>
            <!-- Map and Address Section -->
            <div class=" row q-mt-md">
                <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12 items-center flex q-pa-md">
                    <div class=" text-center">
                        <div class="text-h5 text-center text-weight-bold">
                            Where Are We?
                        </div>
                        <p>
                            Our camp is located in Barangay Taytayan, just a short distance from the main town. Surrounded by nature, it offers the perfect place to relax, unwind, and explore.
                        </p>
                        <p>
                            This spot is one of the best locations for outdoor activities, from camping to events, making it a favorite destination for adventure seekers in Leyte!
                        </p>

                        <div class="text-weight-bold">Taytayan CAMP, San Vicente, Olango Island</div>

                        <!-- <p>
                            For inquiries please contact us at: <br>
                            Phone: (Your Contact Number) <br>
                            Email: (Your Email Address)
                        </p> -->
                    </div>
                </div>
                 <div class="col-md-6 col-lg-6 col-xl-6 col-sm-12 col-xs-12">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3926.037964833761!2d124.0386881!3d10.258521799999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a9910217dd8947%3A0x9769230c1338b6c!2sTaytayan%20Camp!5e0!3m2!1sen!2sph!4v1732162203820!5m2!1sen!2sph" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>

                <q-separator class="q-my-xl" />
                <div class="text-h6 row q-mb-md">
                    <div class="col items-center flex">
                        Topselling Products
                    </div>
                    <div class=" flex justify-end items-center">
                        <Link :href="route('customer.products.index')">
                        <q-btn rounded no-caps flat label="View All" class="bg-grey-3" />
                        </Link>
                        <q-btn round :size="$q.screen.lt.md ? 'sm' : 'md'" icon="arrow_back" flat
                            @click="scrollLeftFeatured('products')" class="bg-grey-3 q-mx-xs" />
                        <q-btn round :size="$q.screen.lt.md ? 'sm' : 'md'" icon="arrow_forward" flat
                            @click="scrollRightFeatured('products')" class="bg-grey-3" />
                    </div>
                </div>
                <div ref="productContainer"
                    :class="`row ${$q.screen.lt.md ? 'q-col-gutter-sm' : 'q-col-gutter-md'} no-wrap hide-scrollbar`"
                    style="overflow-x: auto; scroll-behavior: smooth;">
                    <div class="col-5 col-xs-5 col-sm-5 col-md-3 col-lg-3 col-xl-3" v-for="(product, i) in products">
                        <FeaturedProductCard :product="product">
                            <template v-slot:badge>
                                <div class="text-white q-px-md q-mt-sm absolute-top-left bg-primary"
                                    style="border-top-right-radius: 25px; border-bottom-right-radius: 25px; z-index: 200;"
                                    v-if="i < 3">
                                    #{{ i + 1 }} Most Selling
                                </div>
                            </template>
                        </FeaturedProductCard>
                    </div>
                </div>

                <div class="text-h6 row q-mb-md q-mt-xl">
                    <div class="col items-center flex">
                        Facilities
                    </div>
                    <div class="flex justify-end items-center">
                        <Link :href="route('customer.facilities.index')">
                        <q-btn rounded no-caps flat label="View All" class="bg-grey-3" />
                        </Link>
                        <q-btn round :size="$q.screen.lt.md ? 'sm' : 'md'" icon="arrow_back" flat
                            @click="scrollLeftFeatured('facilities')" class="bg-grey-3 q-mx-xs" />
                        <q-btn round :size="$q.screen.lt.md ? 'sm' : 'md'" icon="arrow_forward" flat
                            @click="scrollRightFeatured('facilities')" class="bg-grey-3" />
                    </div>
                </div>
                <div ref="facilityContainer"
                    :class="`row ${$q.screen.lt.md ? 'q-col-gutter-sm' : 'q-col-gutter-md'} no-wrap hide-scrollbar`"
                    style="overflow-x: auto; scroll-behavior: smooth;">
                    <div class="col-xs-10 col-sm-10 col-md-4 col-lg-4 col-xl-4" v-for="facility in facilities">
                        <FacilityCard :facility="facility" />
                    </div>
                </div>

                <div class="text-h6 row q-mb-md q-mt-xl">
                    <div class="col items-center flex">
                        Upcoming Events
                    </div>
                    <div class="flex justify-end items-center">
                        <Link :href="route('customer.events.index')">
                        <q-btn rounded no-caps flat label="View All" class="bg-grey-3" />
                        </Link>
                        <q-btn round :size="$q.screen.lt.md ? 'sm' : 'md'" icon="arrow_back" flat
                            @click="scrollLeftFeatured('events')" class="bg-grey-3 q-mx-xs" />
                        <q-btn round :size="$q.screen.lt.md ? 'sm' : 'md'" icon="arrow_forward" flat
                            @click="scrollRightFeatured('events')" class="bg-grey-3" />
                    </div>
                </div>
                <div ref="eventContainer"
                    :class="`row ${$q.screen.lt.md ? 'q-col-gutter-sm' : 'q-col-gutter-md'} no-wrap hide-scrollbar`"
                    style="overflow-x: auto; scroll-behavior: smooth;">
                    <div class="col-xs-10 col-sm-10 col-md-6 col-lg-6 col-xl-6" v-for="event in events">
                        <EventCard :event="event" :show_remaining_time="true">
                            <template v-slot:badge>
                                <q-chip color="primary" text-color="white">
                                    In {{ formatDistanceToNow(event.date, new Date()) }}
                                </q-chip>
                                <br>
                            </template>
                        </EventCard>
                    </div>
                    <q-card bordered flat class="q-pa-xl items-center col-12 justify-center flex bg-grey-3"
                        v-if="events.length == 0">
                        No upcoming events... Check again later!
                    </q-card>
                </div>

                <div v-if="ratings.length > 0">
                    <div class="text-h6 row q-mb-md q-mt-xl">
                        <div class="col items-center flex">
                            Our Customer Says
                        </div>
                        <div class="flex justify-end items-center">
                            <Link :href="route('customer.events.index')">
                            <q-btn rounded no-caps flat label="View All" class="bg-grey-3" />
                            </Link>
                            <q-btn round :size="$q.screen.lt.md ? 'sm' : 'md'" icon="arrow_back" flat
                                @click="scrollLeftFeatured('feedback')" class="bg-grey-3 q-mx-xs" />
                            <q-btn round :size="$q.screen.lt.md ? 'sm' : 'md'" icon="arrow_forward" flat
                                @click="scrollRightFeatured('feedback')" class="bg-grey-3" />
                        </div>
                    </div>
                    <div ref="feedbackContainer"
                        :class="`row ${$q.screen.lt.md ? 'q-col-gutter-sm' : 'q-col-gutter-md'} no-wrap hide-scrollbar`"
                        style="overflow-x: auto; scroll-behavior: smooth;">
                        <div class="col-xs-10 col-sm-10 col-md-5 col-lg-5 col-xl-5" v-for="testimonial in ratings">
                            <q-card>
                                <q-card-section>
                                    <svg fill="#098a00" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 0 123.961 123.961" xml:space="preserve" stroke="#098a00"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M49.8,29.032c3.1-1.3,4.4-5,3-8l-4.9-10.3c-1.4-2.899-4.8-4.2-7.8-2.899c-8.5,3.6-15.8,8.3-21.6,14 C11.4,28.532,6.6,36.232,4,44.732c-2.6,8.601-4,20.3-4,35.2v30.7c0,3.3,2.7,6,6,6h39.3c3.3,0,6-2.7,6-6v-39.3c0-3.301-2.7-6-6-6 H26.5c0.2-10.101,2.6-18.2,7-24.301C37.1,36.133,42.5,32.133,49.8,29.032z"></path> <path d="M120.4,29.032c3.1-1.3,4.399-5,3-8l-4.9-10.199c-1.4-2.9-4.8-4.2-7.8-2.9c-8.4,3.6-15.601,8.3-21.5,13.9 c-7.101,6.8-12,14.5-14.601,23c-2.6,8.399-3.899,20.1-3.899,35.1v30.7c0,3.3,2.7,6,6,6H116c3.3,0,6-2.7,6-6v-39.3 c0-3.301-2.7-6-6-6H97.1c0.2-10.101,2.601-18.2,7-24.301C107.7,36.133,113.1,32.133,120.4,29.032z"></path> </g> </g></svg>
                                    <div class="text-weight-bold">{{ testimonial.title }}</div>
                                    <q-item>
                                        <q-item-section>
                                            <q-avatar size="md" text-color="white" color="primary" class="gt-sm">
                                                <q-img class="fit" fit="cover" :src="`/storage/${testimonial.user.profile_pic}`"
                                                    v-if="testimonial.user.profile_pic" />
                                                <div v-else>
                                                    {{ testimonial.user.first_name[0] }}
                                                </div>
                                            </q-avatar>
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label>{{ testimonial.user.first_name + ' ' + testimonial.user.last_name }}</q-item-label>
                                            <q-item-label>{{ testimonial.review }}</q-item-label>
                                        </q-item-section>
                                    </q-item>
                                    <q-rating v-model="testimonial.rating"></q-rating>
                                </q-card-section>
                            </q-card>
                        </div>
                        <q-card bordered flat class="q-pa-xl items-center col-12 justify-center flex bg-grey-3"
                            v-if="events.length == 0">
                            No upcoming events... Check again later!
                        </q-card>
                    </div>
                </div>

                <q-separator class="q-my-xl" />
                <!-- <div class="text-h6 text-center">Facebook posts</div>
			<div class="row q-col-gutter-md">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" v-for="post in posts">
                </div>
            </div> -->
            </div>
        </div>
    </CustomerLayout>
    <!-- <p class="text-center text-red q-mt-xl">designi inig human sa laing pages</p>
    <q-img src="https://avatars.githubusercontent.com/u/112835241?s=400&u=5c5ce654a02c61684c3e00d25d695ce142ede5ef&v=4"></q-img> -->
</template>

<style>


footer {
  background-color: #1a1a1a;
  color: #ffffff;
}
@keyframes bounce {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(5px);
  }
}

.bounce-animation {
  animation: bounce 1s infinite;
}

/* 

.hover-card:hover {
  transform: translateY(-5px);
  box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
} */

a {
    color: black
}
</style>