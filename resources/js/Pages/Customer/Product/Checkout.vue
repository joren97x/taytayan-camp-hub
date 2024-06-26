<script setup>

import FoodCardItem from '@/Components/Customer/Product/FoodCardItem.vue'
import { Link, Head } from '@inertiajs/vue3'
import { useForm, usePage } from '@inertiajs/vue3'
import { Loader } from '@googlemaps/js-api-loader'
import { ref, onMounted } from 'vue'
import { initializeLoader } from '@/Pages/Utils/GoogleMapsLoader'

const props = defineProps({
    order_constants: Object,
    items: Object,
    subtotal: Number,
    google_maps_api_key: Object,
})

const page = usePage()
const map = ref(null)
const directionsService = ref(null)
const directionsRenderer = ref(null)
const infoWindow = ref(null)
const travelMode = ref(null)

const initMap = () => {
    
    directionsService.value = new google.maps.DirectionsService()
    directionsRenderer.value = new google.maps.DirectionsRenderer()
    map.value = new google.maps.Map(document.getElementById("map"), {
        zoom: 7,
        center: JSON.parse(page.props.auth.user.address_coordinates)
    })

    directionsRenderer.value.setMap(map.value)
    travelMode.value = google.maps.TravelMode.WALKING
    calculateAndDisplayRoute()
}

const calculateAndDisplayRoute = () => {
    directionsService.value
        .route({
            origin: JSON.parse(page.props.auth.user.address_coordinates),
            destination: { lat: 10.258812370722216, lng: 124.03867488692084 },
            travelMode: travelMode.value,
        })
        .then((response) => {
            console.log("please get the fkn time")
            console.log(response)

            const duration = response.routes[0].legs[0].duration.text
            const distance = response.routes[0].legs[0].distance.text

            const path = response.routes[0].overview_path;
            const midpointIndex = Math.floor(path.length / 2)

            if (!infoWindow.value) {
                infoWindow.value = new google.maps.InfoWindow();
            }
            infoWindow.value.setContent(`<p>Duration: ${duration}</p> <p>${distance}</p>`);
            infoWindow.value.setPosition(path[midpointIndex]);
            infoWindow.value.open(map.value)

            directionsRenderer.value.setDirections(response)
        })
        .catch((e) => 
            window.alert("Directions request failed due to " + e)
        )
}

const changeTravelMode = (mode) => {
    switch (mode) {
        case 'DRIVING':
            travelMode.value = google.maps.TravelMode.DRIVING;
            break;
        case 'BICYCLING':
            travelMode.value = google.maps.TravelMode.BICYCLING;
            break;
        case 'WALKING':
            travelMode.value = google.maps.TravelMode.WALKING;
            break;
        case 'TWO_WHEELER':
            travelMode.value = google.maps.TravelMode.TWO_WHEELER;
            break;
        case 'TRANSIT':
            travelMode.value = google.maps.TravelMode.TWO_WHEELER;
            break;
    }
    calculateAndDisplayRoute();
};

onMounted(() => {
    const loader = initializeLoader(props.google_maps_api_key)

    loader.load().then(() => {
        initMap()
    })
})

const form = useForm({
    items: props.items,
    payment_method: 'gcash',
    mode: 'pickup'
})

const submit = () => {
    form.post(route('product.pay'), {
        onSuccess: (response) => {
            console.log(response)
        }
    })
}

</script>

<template>
    
    <Head title="Checkout" />
    <div>
        <Link :href="route('customer.cart.index')">
            <q-btn label="Go back" icon="arrow_back" color="blue" flat no-caps unelevated class="q-mx-xl q-my-md" />
        </Link>
        <div class="q-mb-xl q-mx-xl">
            <div class="row q-col-gutter-xl">
                <div class="col-7">
                    <q-card flat bordered>
                        <q-card-section>
                            <q-item>
                                <q-item-section class="text-h6 text-capitalize">{{ form.mode }} Details</q-item-section>
                                <q-item-section side>
                                    <q-btn-toggle
                                        v-model="form.mode"
                                        no-caps
                                        rounded
                                        unelevated
                                        toggle-color="primary"
                                        color="white"
                                        text-color="primary"
                                        :options="[
                                            { label: 'Delivery', value: 'delivery' },
                                            { label: 'Pickup', value: 'pickup' }
                                        ]"
                                    />
                                </q-item-section>
                            </q-item>
                            <q-item v-show="form.mode == 'delivery'">
                                <q-item-section avatar>
                                    <q-icon name="location_on"></q-icon>
                                </q-item-section>
                                <q-item-section>
                                    <!-- 09123456789 -->
                                    {{ $page.props.auth.user.phone_number }}
                                    <q-item-label>{{ $page.props.auth.user.address }}</q-item-label>
                                    <!-- <q-item-label>Purok Sacred heart, Buagsong, Cordova, Cebu</q-item-label> -->
                                </q-item-section>
                            </q-item>
                            <div v-show="form.mode == 'pickup'">
                                <div class="rounded-borders bg-grey q-mt-sm" style="height: auto">
                                    <div id="map" style="height: 450px; width: 100%;"></div>
                                    <!-- <img :src="`https://maps.googleapis.com/maps/api/staticmap?center=Berkeley,CA&zoom=14&size=400x400&key=${google_maps_api_key}`" alt=""> -->
                                </div>
                                <q-item>
                                    <q-item-section avatar>
                                        <q-icon name="home"></q-icon>
                                    </q-item-section>
                                    <q-item-section class="text-h6">
                                        Store Location
                                        <q-item-label caption>Barangay San Vicente, Olango Island</q-item-label>
                                    </q-item-section>
                                    <q-item-section side>
                                        <q-btn-group push>
                                            <q-btn push label="Walking" @click="changeTravelMode('WALKING')" icon="timeline" />
                                            <q-btn push label="Driving" @click="changeTravelMode('DRIVING')" icon="visibility" />
                                            <q-btn push label="Cycling" @click="changeTravelMode('BICYCLING')" icon="update" />
                                            <q-btn push label="Two Wheeler" @click="changeTravelMode('TWO_WHEELER')" icon="update" />
                                            <q-btn push label="Transit" @click="changeTravelMode('TRANSIT')" icon="update" />
                                        </q-btn-group>
                                    </q-item-section>
                                </q-item>
                            </div>
                                <q-separator class="q-my-md" />
                                <q-item>
                                    <q-item-section class="text-h6">Pay With</q-item-section>
                                    <q-item-section side>
                                        <q-chip :class="$q.dark.isActive ? 'bg-grey-9' : ''">Required</q-chip>
                                    </q-item-section>
                                </q-item>
                            <q-list>
                                <q-item 
                                    tag="label" v-ripple 
                                    v-for="(payment_method, index) in order_constants.payment_methods" 
                                    :key="index"
                                >
                                    <q-item-section side top>
                                        <q-radio v-model="form.payment_method" :val="payment_method"/>
                                    </q-item-section>
                                    <q-item-section>
                                        <q-item-label class="text-capitalize">{{ payment_method }}</q-item-label>
                                    </q-item-section>
                                    <q-item-section side>
                                        <q-avatar square>
                                            <img src="https://cdn.quasar.dev/img/boy-avatar.png">
                                        </q-avatar>
                                    </q-item-section>
                                </q-item>
                            </q-list>
                            <q-separator class="q-my-md" />
                            <q-item>
                                <q-item-section class="text-h6">Order Summary</q-item-section>
                                <q-item-section side>
                                    <q-chip :class="$q.dark.isActive ? 'bg-grey-9' : ''">{{ items.length }} items</q-chip>
                                </q-item-section>
                            </q-item>
                            <q-list>
                                <FoodCardItem :item="item" v-for="(item, index) in items" :key="index" />
                            </q-list>
                        </q-card-section>
                    </q-card>
                </div>
                <div class="col-5"  style="position: relative;">
                    <div style="position: sticky; top: 50px">
                        <q-card>
                            <q-card-section>
                                <q-item>
                                    <q-item-section class="text-h6">Order Summary</q-item-section>
                                </q-item>
                                <q-item>
                                    <q-item-section>Subtotal</q-item-section>
                                    <q-item-section side>
                                        {{ subtotal }}
                                    </q-item-section>
                                </q-item>
                                <q-item v-if="form.mode == 'delivery'">
                                    <q-item-section>delivery fee</q-item-section>
                                    <q-item-section side>
                                        P5.00
                                    </q-item-section>
                                </q-item>
                                <q-separator/>
                                <q-item class="text-h6">
                                    <q-item-section>Total</q-item-section>
                                    <q-item-section side>
                                        {{  subtotal  }}
                                    </q-item-section>
                                </q-item>
                            </q-card-section>
                        </q-card>
                        <q-btn class="full-width q-mt-xl" @click="submit" label="Continue to payment" no-caps color="blue" size="lg"></q-btn>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<style scoped>

#map-container {
  position: relative;
}

#map {
  width: 100%;
  height: 500px; /* or any desired height */
}

#map-buttons {
  position: absolute;
  bottom: 10px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 10px;
}

</style>