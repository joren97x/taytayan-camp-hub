<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import FeaturedProductCard from './Product/Partials/FeaturedProductCard.vue'
import EventCard from './Event/Partials/EventCard.vue'
import FacilityCard from './Facility/Partials/FacilityCard.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import { formatDistanceToNow } from 'date-fns'

// defineOptions({ layout: CustomerLayout })
const props = defineProps({ products: Object, events: Object, facilities: Object })

const productContainer = ref(null)
const facilityContainer = ref(null)
const eventContainer = ref(null)

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
    }
    return container
}

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
        <div :style="`position: absolute; width: 100vw; height: ${$q.screen.gt.sm ? '60vh' : '40vh'}; overflow: hidden; top: 0`" class="bg-grey-3">
            <div style="
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(to bottom, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0) 80%);
                z-index: 1;
            "></div>
            <video autoplay muted loop style="position: absolute; top: 0; left: 0; width: 100vw; height: 100%; object-fit: cover;">
                <source src="taytayancamp.mp4" type="video/mp4" />
            </video>
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
                <div 
                    :class="[$q.screen.gt.sm ? 'text-h2' : 'text-h5 q-pt-xl', 'text-weight-bold']"
                    style=" margin: 0; text-shadow: 20px 20px 20px rgba(0, 2, 2, 2.9);" 
                >
                    Explore Our Camp Facilities
                </div>
                <div
                    :class="[$q.screen.gt.sm ? 'text-h6' : 'text-subtitle1', 'text-weight-medium']"
                    style=" text-shadow: 10px 10px 10px rgba(10, 10, 10, 10); max-width: 800px;"
                >
                    Find your perfect spot to relax and enjoy nature
                </div>
            </div>
        </div>
    </template>

    <div class="q-px-xs q-py-md q-mt-xl" :style="`${$q.screen.lt.md ? 'margin-top: 30vh' : 'margin-top: 70vh'};`">
		<div>
			<div class="text-h2 text-weight-bold text-center q-mt-xl">Taytayan Camp</div>
			<div class="text-center q-my-md">
				Explore Taytayan Camp's scenic beauty, where stunning sunsets, 
                palm trees, and cozy spaces await you.
			</div>
			<div class="row q-col-gutter-md">
				<div  class="col-12 col-md-4">
					<q-card flat bordered>
                        <div class="full-width bg-grey" style="height: 250px;">
                            <q-img src="images/first.jpg" class="rounded-borders fit"/>
                        </div>
                        <q-card-section class="q-mb-none q-pb-none">
                            <div>
                                Enjoy refreshing milk teas, shakes, and snacks crafted to delight your taste buds. 
                                Order online or on-site and savor every sip and bite.
                            </div>
                            
                        </q-card-section>
                        <q-card-actions class="justify-end">
                            <Link>
                                <q-btn rounded no-caps label="Learn More" color="primary"/>
                            </Link>
                        </q-card-actions>
					</q-card>
				</div>
				
				<div  class="col-12 col-md-4">
					<q-card flat bordered>
                        <div class="full-width bg-grey" style="height: 250px;">
                            <q-img src="images/second.jpg" class="rounded-borders fit"/>
                        </div>
                        <q-card-section class="q-mb-none q-pb-none">
                            <div>
                                Book a cozy room or tent for a relaxing escape in nature. 
                                Whether a night or weekend, we've got the perfect spot for you.
                            </div>
                        </q-card-section>
                        <q-card-actions class="justify-end">
                            <Link>
                                <q-btn rounded no-caps label="Learn More" color="primary"/>
                            </Link>
                        </q-card-actions>
					</q-card>
				</div>
				
				<div  class="col-12 col-md-4">
					<q-card flat bordered>
                        <div class="full-width bg-grey" style="height: 250px;">
                            <q-img src="images/third.jpg" class="rounded-borders fit"/>
                        </div>
                        <q-card-section class="q-mb-none q-pb-none">
                            <div>
                                Catch exciting events at Taytayan Camp! 
                                From concerts to parties.
                                Get your tickets in advance and make memories that last a lifetime.
                            </div>
                        </q-card-section>
                        <q-card-actions class="justify-end">
                            <Link>
                                <q-btn rounded no-caps label="Learn More" color="primary"/>
                            </Link>
                        </q-card-actions>
					</q-card>
				</div>
			</div>
            <q-separator class="q-my-xl"/>
			<div class="text-h6 row q-mb-md">
                    <div class="col items-center flex">
						Topselling Products
                    </div>
                    <div class=" flex justify-end items-center">
						<Link :href="route('customer.products.index')">
                            <q-btn rounded no-caps flat label="View All" class="bg-grey-3"/>
                        </Link>
                        <q-btn round :size="$q.screen.lt.md ? 'sm' : 'md'" icon="arrow_back" flat @click="scrollLeftFeatured('products')" class="bg-grey-3 q-mx-xs"/>
                        <q-btn round :size="$q.screen.lt.md ? 'sm' : 'md'" icon="arrow_forward" flat @click="scrollRightFeatured('products')" class="bg-grey-3"/>
                    </div>
                </div>
			<div ref="productContainer" :class="`row ${$q.screen.lt.md ? 'q-col-gutter-sm' : 'q-col-gutter-md'} no-wrap hide-scrollbar`" style="overflow-x: auto; scroll-behavior: smooth;">
				<div class="col-5 col-xs-5 col-sm-5 col-md-3 col-lg-3 col-xl-3" v-for="(product, i) in products">
                    <FeaturedProductCard :product="product">
                        <template v-slot:badge>
                            <div 
                                class="text-white q-px-md q-mt-sm absolute-top-left bg-primary" 
                                style="border-top-right-radius: 25px; border-bottom-right-radius: 25px; z-index: 200;"
                                v-if="i < 3"
                            >
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
                            <q-btn rounded no-caps flat label="View All" class="bg-grey-3"/>
                        </Link>
                        <q-btn round :size="$q.screen.lt.md ? 'sm' : 'md'" icon="arrow_back" flat @click="scrollLeftFeatured('facilities')" class="bg-grey-3 q-mx-xs"/>
                        <q-btn round :size="$q.screen.lt.md ? 'sm' : 'md'" icon="arrow_forward" flat @click="scrollRightFeatured('facilities')" class="bg-grey-3"/>
                    </div>
                </div>
				<div ref="facilityContainer" :class="`row ${$q.screen.lt.md ? 'q-col-gutter-sm' : 'q-col-gutter-md'} no-wrap hide-scrollbar`" style="overflow-x: auto; scroll-behavior: smooth;">
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
                            <q-btn rounded no-caps flat label="View All" class="bg-grey-3"/>
                        </Link>
                        <q-btn round :size="$q.screen.lt.md ? 'sm' : 'md'" icon="arrow_back" flat @click="scrollLeftFeatured('events')" class="bg-grey-3 q-mx-xs"/>
                        <q-btn round :size="$q.screen.lt.md ? 'sm' : 'md'" icon="arrow_forward" flat @click="scrollRightFeatured('events')" class="bg-grey-3"/>
				</div>
			</div>
			<div ref="eventContainer" :class="`row ${$q.screen.lt.md ? 'q-col-gutter-sm' : 'q-col-gutter-md'} no-wrap hide-scrollbar`" style="overflow-x: auto; scroll-behavior: smooth;">
				<div class="col-xs-10 col-sm-10 col-md-6 col-lg-6 col-xl-6" v-for="event in events">
					<EventCard :event="event">
                        <template v-slot:badge>
                            <q-chip color="primary" text-color="white" >
                                In {{ formatDistanceToNow(event.date, new Date()) }}
                            </q-chip>
                            <br>
                        </template>
                    </EventCard>
				</div>
                <q-card bordered flat class="q-pa-xl items-center col-12 justify-center flex bg-grey-3" v-if="events.length == 0">
                    No upcoming events... Check again later! 
                </q-card>
			</div>

            <q-separator class="q-my-xl"/>
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

a {
    color: black
}
</style>