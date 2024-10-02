<script setup>

import { Link, Head } from '@inertiajs/vue3'
import { useForm, usePage } from '@inertiajs/vue3'
import { ref, onMounted, watch } from 'vue'
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

const mode = ref('delivery')
const form = useForm({
    items: props.items,
    payment_method: 'right_now',
    mode: 'delivery',
    cart_id: props.cart_id
})

const submit = () => {
    form.post(route('product.pay'), {
        onSuccess: (response) => {
            console.log(response)
        }
    })
}

watch(mode, () => {
    form.mode = mode.value
    form.payment_method = 'right_now'
})

</script>

<template>
    
    <Head title="Checkout" />
    <div class="bg-grey-2">
        <q-card class="row justify-between bg-white q-pa-sm" flat bordered>
            <div class="col-12 text-center text-h6" style="max-width: 1280px; margin: 0 auto; position: relative;">
               <q-avatar size="lg">
                    <q-img src="../logo.png" fill="cover" />
                </q-avatar>
                    Taytayan CAMP
                <Link :href="route('customer.cart.index')" class="absolute-left">
                    <q-btn :label="$q.screen.lt.md ? '' : 'Go back'" icon="arrow_back" color="black" flat no-caps unelevated />
                </Link>
            </div>
        </q-card>
        <div style="max-width: 1280px; margin: 0 auto;">
            <div class="q-my-md">
                <div class="row q-col-gutter-md">
                    <div class="col-7 col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
                        <q-card flat bordered>
                            <q-card-section>
                                <div class="text-h6 text-center">Checkout</div>

                                <q-separator class="q-my-md"/>

                                <q-item>
                                    <q-item-section class="text-h6 text-capitalize">{{ form.mode }} Details</q-item-section>
                                    <q-item-section side>
                                        <q-btn-toggle
                                            v-model="mode"
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
                                <!-- <q-separator/> -->
                                <q-item v-show="form.mode == 'delivery'">
                                    <q-item-section avatar>
                                        <q-icon name="person"></q-icon>
                                    </q-item-section>
                                    <q-item-section>
                                        {{ $page.props.auth.user.first_name + ' ' + $page.props.auth.user.last_name }}
                                        <q-item-label caption>{{ $page.props.auth.user.email }}</q-item-label>
                                    </q-item-section>
                                </q-item>
                                <q-item v-show="form.mode == 'delivery'">
                                    <q-item-section avatar>
                                        <q-icon name="location_on"></q-icon>
                                    </q-item-section>
                                    <q-item-section>
                                        {{ $page.props.auth.user.phone_number }}
                                        <q-item-label caption>{{ $page.props.auth.user.address }}</q-item-label>
                                    </q-item-section>
                                </q-item>
                                <div v-show="form.mode == 'pickup'">
                                    <div class="rounded-borders bg-grey q-mt-sm" style="height: auto">
                                        <div id="map" style="height: 350px; width: 100%;"></div>
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
                                <q-separator class="q-my-md"/>
                                <q-item>
                                    <q-item-section class="text-h6">Pay With</q-item-section>
                                    <q-item-section side>
                                        <q-chip :class="$q.dark.isActive ? 'bg-grey-9' : ''">Required</q-chip>
                                    </q-item-section>
                                </q-item>
                                <q-list>
                                    <q-card 
                                        bordered flat 
                                        class="q-pa-sm q-mb-sm" 
                                        @click="form.payment_method = 'right_now'" 
                                        :style="form.payment_method == 'right_now' ? 'border: 1px solid black' : ''"
                                    >
                                        <q-item>
                                            <q-item-section avatar>
                                                <q-avatar square>
                                                    <img src="https://cdn.quasar.dev/img/boy-avatar.png">
                                                </q-avatar>
                                                <!-- <q-radio v-model="form.payment_method" val="right_now"/> -->
                                            </q-item-section>
                                            <q-item-section>
                                                <q-item-label>Right Now</q-item-label>
                                                <q-item-label caption>E wallet, gcash, debit card etcc..</q-item-label>
                                            </q-item-section>
                                        </q-item>
                                    </q-card>
                                    <q-card 
                                        bordered flat 
                                        class="q-pa-sm" 
                                        @click="form.payment_method = 'cash_on_delivery'" 
                                        v-if="form.mode == 'delivery'"
                                        :style="form.payment_method == 'cash_on_delivery' ? 'border: 1px solid black' : ''"
                                    >
                                        <q-item>
                                            <q-item-section avatar>
                                                <q-avatar square>
                                                    <img src="https://cdn.quasar.dev/img/boy-avatar.png">
                                                </q-avatar>
                                                <!-- <q-radio v-model="form.payment_method" val="right_now"/> -->
                                            </q-item-section>
                                            <q-item-section>
                                                <q-item-label>Cash On Delivery</q-item-label>
                                                <q-item-label caption>Lorem ipsum dolor sit amet.</q-item-label>
                                            </q-item-section>
                                        </q-item>
                                    </q-card>
                                    <q-card 
                                        bordered flat 
                                        class="q-pa-sm q-mt-sm" 
                                        @click="form.payment_method = 'walk_in'" 
                                        v-else
                                        :style="form.payment_method == 'walk_in' ? 'border: 1px solid black' : ''"
                                    >
                                        <q-item>
                                            <q-item-section avatar>
                                                <q-avatar square>
                                                    <img src="https://cdn.quasar.dev/img/boy-avatar.png">
                                                </q-avatar>
                                                <!-- <q-radio v-model="form.payment_method" val="right_now"/> -->
                                            </q-item-section>
                                            <q-item-section>
                                                <q-item-label>Walk In</q-item-label>
                                                <q-item-label caption>Lorem ipsum dolor sit amet.</q-item-label>
                                            </q-item-section>
                                        </q-item>
                                    </q-card>
                                </q-list>
                                <q-item class="q-mt-md">
                                    <q-item-section class="text-h6">Order Summary</q-item-section>
                                    <q-item-section side>
                                        <q-chip :class="$q.dark.isActive ? 'bg-grey-9' : ''">{{ items.length }} items</q-chip>
                                    </q-item-section>
                                </q-item>
                                <q-list>
                                    <q-item v-for="(item, index) in items" :key="index">
                                        <q-item-section avatar>
                                            <q-img 
                                                :src="`/storage/${item.product.photo}`"
                                                height="70px"
                                                width="70px"
                                                fit="contain"
                                                class="q-mx-md"
                                            />
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label>
                                                {{ item.product.name }} ({{ item.product.price }}) - {{ item.quantity }} pcs
                                            </q-item-label>
                                            <template 
                                                v-for="(modifier, index) in item.grouped_modifiers" 
                                                :key="index"
                                            >
                                                <q-item-label caption>{{ modifier.modifier_group.name }}</q-item-label>
                                                <q-item-label 
                                                    caption 
                                                    v-for="(modifier_item, index) in modifier.modifier_items" 
                                                    :key="index"
                                                >
                                                    {{ `${modifier_item.quantity} - ${modifier_item.modifier_item.name} (P${modifier_item.total})` }}
                                                </q-item-label>
                                                
                                            </template>
                                            <q-item-label caption v-if="item.special_instruction">
                                                Note: {{ item.special_instruction }}
                                            </q-item-label>
                                        </q-item-section>
                                        <q-item-section side>
                                            P{{ item.total }}
                                        </q-item-section>
                                    </q-item>
                                </q-list>
                            </q-card-section>
                        </q-card>
                    </div>
                    <div class="col-5 col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5"  style="position: relative;">
                        <div style="position: sticky; top: 50px">
                            <q-card flat bordered>
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
                                <q-card-actions>
                                    <q-btn class="full-width" @click="submit" label="Checkout" no-caps color="primary" rounded/>
                                </q-card-actions>
                            </q-card>
                        </div>
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