<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import FeaturedProductCard from './Product/Partials/FeaturedProductCard.vue';
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue';

defineOptions({
    layout: CustomerLayout
})

defineProps({
    products: Object
})

const featuredEvents = ref([
  { id: 1, name: 'Local Music Festival', image: 'music-festival.jpg' },
  { id: 2, name: 'Art & Culture Fair', image: 'art-culture-fair.jpg' },
  { id: 3, name: 'Yoga Retreat', image: 'yoga-retreat.jpg' },
]);

const recommendedRooms = ref([
  { id: 1, name: 'Deluxe Suite', image: 'deluxe-suite.jpg', description: 'A luxurious room with a view.' },
  { id: 2, name: 'Family Room', image: 'family-room.jpg', description: 'Perfect for family stays.' },
  { id: 3, name: 'Single Room', image: 'single-room.jpg', description: 'Ideal for solo travelers.' },
]);

const activeEvent = ref(0);

const goToEvents = () => {
  // Navigate to the events page
};

const goToBookings = () => {
  // Navigate to the bookings page
};

const goToOrders = () => {
  // Navigate to the orders page
};

const goToTickets = () => {
  // Navigate to the tickets page
};

const goToEvent = (eventId) => {
  // Navigate to the specific event details page
};

const goToRoom = (roomId) => {
  // Navigate to the specific room details page
};
</script>

<template>
    <Head title="Home" />
    <div class="hero q-py-xl bg-primary text-white text-center">
      <h1>Welcome to Taytayan Camp Hub</h1>
      <p class="q-mt-none">Discover events, book your stay, and enjoy local experiences.</p>
      <q-btn unelevated color="secondary" class="q-mt-md" label="Explore Events" @click="goToEvents" />
    </div>
    <div class="q-px-lg q-py-md q-mt-xl bg-grey-2">
      <div class="row justify-around">
        <q-card class="col-12 col-md-3 q-pa-md quick-action-card" @click="goToBookings">
          <q-icon name="hotel" size="50px" class="text-primary" />
          <h4 class="q-mt-md">Book a Room</h4>
          <p>Find and book the perfect room for your stay.</p>
        </q-card>
        
        <q-card class="col-12 col-md-3 q-pa-md quick-action-card" @click="goToOrders">
          <q-icon name="restaurant" size="50px" class="text-primary" />
          <h4 class="q-mt-md">Order Food</h4>
          <p>Enjoy delicious meals delivered to your room.</p>
        </q-card>
        
        <q-card class="col-12 col-md-3 q-pa-md quick-action-card" @click="goToTickets">
          <q-icon name="event" size="50px" class="text-primary" />
          <h4 class="q-mt-md">Buy Event Tickets</h4>
          <p>Attend local events and experiences.</p>
        </q-card>
      </div>
    </div>
    <div class="q-px-lg q-py-lg">
      <div class="text-h5 q-mb-md">Popular Events</div>
      <q-carousel v-model="activeEvent" animated swipeable height="300px">
        <q-carousel-slide
          v-for="event in featuredEvents"
          :key="event.id"
          :name="event.id"
          :img-src="event.image"
        >
          <div class="absolute-bottom q-pa-sm bg-opacity">
            <h6 class="text-white">{{ event.name }}</h6>
            <q-btn flat color="white" @click="goToEvent(event.id)" label="Learn More" />
          </div>
        </q-carousel-slide>
      </q-carousel>
    </div>

    <div class="q-px-lg q-py-lg">
      <div class="text-h5 q-mb-md">Recommended Rooms</div>
      <div class="row">
        <q-card
          v-for="room in recommendedRooms"
          :key="room.id"
          class="col-12 col-md-4 q-pa-md"
        >
          <q-img :src="room.image" class="q-mb-md" style="height: 150px;" />
          <h6>{{ room.name }}</h6>
          <p>{{ room.description }}</p>
          <q-btn flat color="primary" @click="goToRoom(room.id)" label="View Room" />
        </q-card>
      </div>
    </div>

    <!-- <div>
        <p class="text-h4 text-weight-bold">Nice to see you, {{ $page.props.auth.user.first_name }}👋</p>
        <div class="row q-col-gutter-md">
            <div class="col-4" v-for="n in 3" :key="n">
                <q-card class="my-card q-py-md q-pl-md" bordered flat>
                    <q-card-section horizontal>
                        <img
                            src="https://cdn.quasar.dev/img/mountains.jpg"
                            width="40%"
                        />
                        <p class="q-ml-md">
                            <span class="text-h6 text-weight-bold">Milktea Menu</span>
                            <br>
                            <span class="text-subtitle2 text-weight-regular">Lorem ipsum dolor, sit amet consectetur</span>
                        </p>
                        <q-card-actions vertical class="justify-around q-px-md">
                            <q-btn icon="chevron_right" flat></q-btn>
                        </q-card-actions>
                    </q-card-section>
                </q-card>
            </div>
        </div>
        
        <div class="q-my-md">
            <p class="text-h5 text-weight-bold">Products</p>
            <div class="row q-col-gutter-md">
                // some product card
            </div>
        </div>
        <div class="q-my-md">
            <p class="text-h5 text-weight-bold">Events</p>
            <div class="row q-col-gutter-md">
                // some event card
            </div>
        </div>
        <div class="q-my-md">
            <p class="text-h5 text-weight-bold">Facilities</p>
            <div class="row q-col-gutter-md">
                // some facility card
            </div>
        </div>
    </div> -->
    <p class="text-center text-red q-mt-xl">designi inig human sa laing pages</p>
    <q-img src="https://avatars.githubusercontent.com/u/112835241?s=400&u=5c5ce654a02c61684c3e00d25d695ce142ede5ef&v=4"></q-img>

</template>

<style>
.hero {
  background: url('hero-background.jpg') no-repeat center center;
  background-size: cover;
}

.quick-action-card {
  transition: transform 0.3s;
  cursor: pointer;
  background: white;
  border-radius: 8px;
}

.quick-action-card:hover {
  transform: translateY(-5px);
}

.bg-opacity {
  background: rgba(0, 0, 0, 0.5);
}

footer {
  background-color: #1a1a1a;
  color: #ffffff;
}
</style>