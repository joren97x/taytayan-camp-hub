<script setup>

import { Link, Head } from '@inertiajs/vue3'
import { useForm, usePage } from '@inertiajs/vue3'
import { ref, onMounted, watch } from 'vue'
import { initializeLoader } from '@/Pages/Utils/GoogleMapsLoader'
import { useQuasar } from 'quasar'

const props = defineProps({
    cart_id: Number,
    order_constants: Object,
    cart_products: Object,
    subtotal: Number,
    google_maps_api_key: Object,
})

const $q = useQuasar()
const page = usePage()
const map = ref(null)
const directionsService = ref(null)
const directionsRenderer = ref(null)
const infoWindow = ref(null)
const travelMode = ref(null)
const locationError = ref(false)
const editAddressDialog = ref(false)
const initMap = () => {
    
    directionsService.value = new google.maps.DirectionsService()
    directionsRenderer.value = new google.maps.DirectionsRenderer()
    map.value = new google.maps.Map(document.getElementById("map"), {
        zoom: 7,
        center: JSON.parse(page.props.auth.user.address_coordinates)
    })

    directionsRenderer.value.setMap(map.value)
    travelMode.value = google.maps.TravelMode.DRIVING
    calculateAndDisplayRoute()
}


const mode = ref('delivery')
const form = useForm({
    cart_products: props.cart_products,
    payment_method: 'right_now',
    mode: 'delivery',
    cart_id: props.cart_id,
    delivery_fee: 0,
    total: props.subtotal
})

const MIN_DELIVERY_FEE = 30;
const THRESHOLD_DISTANCE = 3; // distance up to which the minimum fee applies

console.log(JSON.parse(page.props.auth.user.address_coordinates))
const calculateAndDisplayRoute = () => {
    directionsService.value
        .route({
            origin: JSON.parse(page.props.auth.user.address_coordinates),
            destination: { lat: 10.258812370722216, lng: 124.03867488692084 },
            travelMode: travelMode.value,
        })
        .then((response) => {
            const duration = response.routes[0].legs[0].duration.text;
            const distanceText = response.routes[0].legs[0].distance.text;
            const distanceValueInMeters = response.routes[0].legs[0].distance.value; // This is the raw distance value in meters
            let distanceValue;

            // Check if the distance is in kilometers or meters
            if (distanceText.includes('km')) {
                // If distance is in kilometers, parse it
                distanceValue = parseFloat(distanceText.split(' ')[0]);
            } else if (distanceText.includes('M')) {
                // If distance is in meters, convert to kilometers
                distanceValue = parseFloat(distanceText.split(' ')[0]) / 1000;
            }

            console.log(`Distance: ${distanceValue} km`);

            let deliveryFee = MIN_DELIVERY_FEE;

            // Calculate additional fees only if distance exceeds threshold
            if (distanceValue > THRESHOLD_DISTANCE) {
                const extraDistance = Math.floor(distanceValue - THRESHOLD_DISTANCE);
                console.log(`Extra distance: ${extraDistance} km`);

                deliveryFee += extraDistance * 10; // Each km above threshold costs an extra 10 pesos
            }

            console.log(`DeliveryFee: ${deliveryFee} pesos`);
            const path = response.routes[0].overview_path;
            const midpointIndex = Math.floor(path.length / 2);

            // if (!infoWindow.value) {
            //     infoWindow.value = new google.maps.InfoWindow();
            // }

            // infoWindow.value.setContent(`<p>Duration: ${duration}</p> <p>${distance}</p> <p>Shipping Fee: ₱${deliveryFee}</p>`);
            // infoWindow.value.setPosition(path[midpointIndex]);
            // infoWindow.value.open(map.value);

            directionsRenderer.value.setDirections(response);

            form.delivery_fee = deliveryFee;
            form.total += deliveryFee;
        })
        .catch((e) => {
            console.log(e)
            locationError.value = true
            $q.notify({
                message: `It seems the marker is placed in an area where no route exists. Please try again with a different location.`,
                color: 'negative',
                textColor: 'white',
                position: 'top'
            })
        });
};

onMounted(() => {
    const loader = initializeLoader(props.google_maps_api_key)

    loader.load().then(() => {
        initMap()
    })
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

    if(mode.value == 'delivery') {
        form.total += form.delivery_fee 
    }
    else {
        form.total = props.subtotal
    }

})


</script>

<template>
    
    <Head title="Checkout" />
    
    <div class="bg-grey-2">
        <q-card class="row justify-between bg-white q-pa-sm" flat bordered square>
            <div class="col-12 text-center text-h6" style="max-width: 1280px; margin: 0 auto; position: relative;">
                Checkout
                <Link :href="route('customer.cart.index')" class="absolute-left">
                    <q-btn :label="$q.screen.lt.md ? '' : 'Go back'" icon="arrow_back" color="black" flat no-caps unelevated />
                </Link>
            </div>
        </q-card>
        <div style="max-width: 1280px; margin: 0 auto;">
            <div class="q-my-md">
                <div class="row q-col-gutter-md">
                    <div class="col-7 col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
                        <q-card flat bordered :square="$q.screen.lt.md">
                            <q-card-section :class="$q.screen.gt.sm ? 'q-pa-md' : 'q-pa-sm'">
                                <div class="text-h6 text-center"></div>
                                <!-- <q-separator class="q-my-sm"/> -->

                                <q-item class="q-pa-none">
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
                                <q-item v-show="form.mode == 'delivery'" class="q-pa-none">
                                    <q-item-section avatar>
                                        <q-icon name="person"></q-icon>
                                    </q-item-section>
                                    <q-item-section>
                                        {{ $page.props.auth.user.first_name + ' ' + $page.props.auth.user.last_name }}
                                        <q-item-label caption>{{ $page.props.auth.user.email }}</q-item-label>
                                    </q-item-section>
                                </q-item>
                                <q-item 
                                    v-show="form.mode == 'delivery'" 
                                    class="rounded-borders q-pa-none q-mt-sm"
                                    :style="locationError ? 'border: 1px solid var(--q-negative)' : ''"
                                >
                                    <q-item-section avatar>
                                        <q-icon name="location_on"></q-icon>
                                    </q-item-section>
                                    <q-item-section>
                                        {{ $page.props.auth.user.phone_number }}
                                        <q-item-label caption>{{ $page.props.auth.user.address }}</q-item-label>
                                        <q-item-label caption>{{ $page.props.auth.user.street }}</q-item-label>
                                    </q-item-section>
                                    <q-item-section side v-if="locationError">
                                        <Link :href="route('customer.edit_profile')">
                                            <q-btn label="Edit Address" color="primary" outline rounded no-caps/>
                                        </Link>
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
                                                    <!-- <q-btn push no-caps label="Walking" @click="changeTravelMode('WALKING')" icon="timeline" /> -->
                                                    <!-- <q-btn push no-caps label="Driving" @click="changeTravelMode('DRIVING')" icon="visibility" /> -->
                                                    <!-- <q-btn push no-caps label="Cycling" @click="changeTravelMode('BICYCLING')" icon="update" /> -->
                                                    <!-- <q-btn push no-caps label="Two Wheeler" @click="changeTravelMode('TWO_WHEELER')" icon="update" /> -->
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
                                <q-item class="q-pa-none">
                                    <q-item-section class="text-h6">Pay With</q-item-section>
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
                                                    <img src="images/right_now.png" fit="cover">
                                                </q-avatar>
                                                <!-- <q-radio v-model="form.payment_method" val="right_now"/> -->
                                            </q-item-section>
                                            <q-item-section>
                                                <q-item-label>Right Now</q-item-label>
                                                <q-item-label caption>Pay immediately using e-wallet, GCash, card, or other digital methods.</q-item-label>
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
                                        <q-item >
                                            <q-item-section avatar>
                                                <q-avatar square>
                                                    <img src="images/walk_in.png">
                                                </q-avatar>
                                                <!-- <q-radio v-model="form.payment_method" val="right_now"/> -->
                                            </q-item-section>
                                            <q-item-section>
                                                <q-item-label>Cash On Delivery</q-item-label>
                                                <q-item-label caption>Pay with cash directly to the delivery person upon arrival.</q-item-label>
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
                                                    <img src="images/walk_in.png">
                                                </q-avatar>
                                                <!-- <q-radio v-model="form.payment_method" val="right_now"/> -->
                                            </q-item-section>
                                            <q-item-section>
                                                <q-item-label>Walk In</q-item-label>
                                                <q-item-label caption>Pay with cash directly to the store upon arrival.</q-item-label>
                                            </q-item-section>
                                        </q-item>
                                    </q-card>
                                </q-list>
                                <q-item class="q-mt-md q-pa-none">
                                    <q-item-section class="text-h6">Order Summary</q-item-section>
                                    <q-item-section side>
                                        <q-chip :class="$q.dark.isActive ? 'bg-grey-9' : ''">{{ cart_products.length }} items</q-chip>
                                    </q-item-section>
                                </q-item>
                                <q-list>
                                    <q-item v-for="(cart_product, index) in cart_products" :key="index" class="q-px-none">
                                        <q-item-section avatar>
                                            <q-img 
                                                :src="`/storage/${cart_product.product.photo}`"
                                                height="70px"
                                                width="70px"
                                                fit="contain"
                                                :class="$q.screen.gt.sm ? 'q-mx-md' : ''"
                                            />
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label>
                                                {{ cart_product.product.name }} ({{ cart_product.product.price }}) - {{ cart_product.quantity }} pcs
                                            </q-item-label>
                                            <template 
                                                v-for="(modifier, index) in cart_product.grouped_modifiers" 
                                                :key="index"
                                            >
                                                <q-item-label caption>{{ modifier.modifier_group.name }}</q-item-label>
                                                <q-item-label 
                                                    caption 
                                                    v-for="(modifier_item, index) in modifier.modifier_items" 
                                                    :key="index"
                                                >
                                                    {{ `${modifier_item.quantity} - ${modifier_item.modifier_item.name} (P${modifier_item.modifier_item.price})` }}
                                                </q-item-label>
                                                
                                            </template>
                                            <q-item-label caption v-if="cart_product.special_instruction">
                                                Note: {{ cart_product.special_instruction }}
                                            </q-item-label>
                                        </q-item-section>
                                        <q-item-section side>
                                            ₱{{ parseFloat(cart_product.total).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
                                        </q-item-section>
                                    </q-item>
                                </q-list>
                            </q-card-section>
                        </q-card>
                    </div>
                    <div class="col-5 col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5"  style="position: relative;">
                        <div style="position: sticky; top: 50px">
                            <q-card flat bordered>
                                <q-card-section class="q-pa-none">
                                    <q-item >
                                        <q-item-section class="text-h6">Order Summary</q-item-section>
                                    </q-item>
                                    <q-item>
                                        <q-item-section>Subtotal</q-item-section>
                                        <q-item-section side>
                                            <!-- {{ subtotal }} -->
                                            ₱{{ parseFloat(subtotal).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
                                        </q-item-section>
                                    </q-item>
                                    <q-item v-if="form.mode == 'delivery'">
                                        <q-item-section>Delivery Fee</q-item-section>
                                        <q-item-section side>
                                            <!-- P{{ form.delivery_fee }} -->
                                            ₱{{ parseFloat(form.delivery_fee).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
                                        </q-item-section>
                                    </q-item>
                                    <q-separator/>
                                    <q-item class="text-h6 text-black">
                                        <q-item-section>Total</q-item-section>
                                        <q-item-section side class="text-black">
                                            <!-- {{  form.total  }} -->
                                            ₱{{ parseFloat(form.total).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
                                        </q-item-section>
                                    </q-item>
                                </q-card-section>
                                <q-card-actions>
                                    <q-btn class="full-width" @click="submit" label="Place Order" no-caps color="primary" rounded/>
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