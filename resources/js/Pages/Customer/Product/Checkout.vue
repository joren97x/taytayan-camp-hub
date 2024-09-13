<script setup>

import FoodCardItem from '@/Components/Customer/Product/FoodCardItem.vue'
import { Link, Head } from '@inertiajs/vue3'
import { useForm, usePage } from '@inertiajs/vue3'
import { Loader } from '@googlemaps/js-api-loader'
import { ref, onMounted } from 'vue'
import { initializeLoader } from '@/Pages/Utils/GoogleMapsLoader'

const props = defineProps({
    cart_id: Number,
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

console.log(page.props)
const form = useForm({
    items: props.items,
    payment_method: 'gcash',
    mode: 'pickup',
    cart_id: props.cart_id
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
        <div class="row justify-between lt-md">
            <div class="col-12 text-center center text-h6">
                Checkout
                <Link :href="route('customer.cart.index')" class="absolute-left">
                    <q-btn label="Go back" icon="arrow_back" color="blue" flat no-caps unelevated />
                </Link>
            </div>
        </div>
        <div class="q-mb-xl">
            
            <div class="row q-col-gutter-xl">
                <div class="col-7 col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
                    <q-card flat >
                        <q-card-section>
                            <p class="text-h5 q-ml-md gt-sm">Checkout</p>
                            <q-item>
                                <q-item-section class="text-h6 text-capitalize">{{ form.mode }} Details</q-item-section>
                                <q-item-section side>
                                    <q-btn-toggle
                                        v-model="form.mode"
                                        no-caps
                                        round
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
                                
                                    <div class="row">
                                        <div class="col-12">
                                            <q-btn-group spread flat unelevated class="no-border">
                                                <q-btn push no-caps label="Walking" @click="changeTravelMode('WALKING')" icon="timeline" />
                                                <q-btn push no-caps label="Driving" @click="changeTravelMode('DRIVING')" icon="visibility" />
                                                <q-btn push no-caps label="Cycling" @click="changeTravelMode('BICYCLING')" icon="update" />
                                                <q-btn push no-caps label="Two Wheeler" @click="changeTravelMode('TWO_WHEELER')" icon="update" />
                                                <q-btn push no-caps label="Transit" @click="changeTravelMode('TRANSIT')" icon="update" />
                                            </q-btn-group>
                                        </div>
                                    </div>
                                <q-item class="q-my-md">
                                    <q-item-section avatar>
                                        <q-icon name="home"></q-icon>
                                    </q-item-section>
                                    <q-item-section class="text-h6">
                                        Barangay San Vicente, Olango Island
                                        <q-item-label caption>Store Location</q-item-label>
                                    </q-item-section>
                                </q-item>
                            </div>
                                <q-item>
                                    <q-item-section class="text-h6">Pay With</q-item-section>
                                    <q-item-section side>
                                        <q-chip :class="$q.dark.isActive ? 'bg-grey-9' : ''">Required</q-chip>
                                    </q-item-section>
                                </q-item>
                            <q-list>
                                <span class="text-red">fix this later </span>
                                <q-item 
                                    tag="label" v-ripple 
                                    v-for="(payment_method, index) in order_constants.payment_methods" 
                                    :key="index"
                                    v-if="!(
                                        (payment_method === 'cash_on_delivery' && form.mode === 'pickup') || 
                                        (payment_method === 'walk_in' && form.mode === 'delivery')
                                    )"
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
                <div class="col-5 col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5"  style="position: relative;">
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
                            <q-card-actions :class="['row bg-white', $q.screen.lt.md ? 'fixed-bottom' : '']">
                                <div class="col-8 items-center">
                                    <q-item>
                                        <q-item-section>
                                            <div class="text-subtitle1">
                                                Total
                                            </div>
                                            <!-- <div>
                                                {{ product.price }}
                                            </div> -->
                                        </q-item-section>
                                        <q-item-section side>
                                            P{{ subtotal }}
                                        </q-item-section>
                                    </q-item>
                                </div>
                                <div class="col-4">
                                    <!-- <q-btn
                                        label="Add To Cart"
                                        color="primary"
                                        class="full-width"
                                        type="submit"
                                        no-caps
                                        :loading="form.processing"
                                        :disable="form.processing"
                                    />   -->
                                    <q-btn class="full-width" @click="submit" label="Contit" no-caps color="blue"></q-btn>
                                </div>
                            </q-card-actions>
                        </q-card>
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
/* 
#map-buttons {
  position: absolute;
  bottom: 10px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 10px;
} */

</style>