<script setup>

import { ref } from 'vue'
import { useForm, Link, usePage } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import OrderDetails from '@/Components/OrderDetails.vue'
import { initializeLoader } from '@/Pages/Utils/GoogleMapsLoader'

const props = defineProps({ order: Object, google_maps_api_key: String })
const $page = usePage()
const $q = useQuasar()
const dialog = ref(false)
const deliverOrderForm = useForm({})
const completeOrderForm = useForm({})

function deliverOrder() {
    deliverOrderForm.patch(route('driver.order.deliver', props.order.id), {
        onSuccess: () => {
            dialog.value = false
            $q.notify('Order Now To Be Delivered')
        }
    })
}

const completeOrder = () => {
    completeOrderForm.patch(route('driver.order.complete_delivery', props.order.id), {
        onSuccess: () => {
            $q.notify('Order Has Been Delivered')
        }
    })
}

const map = ref(null)
const directionsService = ref(null)
const directionsRenderer = ref(null)
const infoWindow = ref(null)
const driverMarker = ref(null)

// Initialize Google Map and setup route calculation
const initMap = () => {
    // Initialize directions service and renderer
    directionsService.value = new google.maps.DirectionsService()
    directionsRenderer.value = new google.maps.DirectionsRenderer()

    // Create a map centered on the customer's coordinates
    map.value = new google.maps.Map(document.getElementById("map"), {
        zoom: 12,
        center: props.customers_coordinates,
        mapTypeControl: false,        // Disables Map/Satellite options
        streetViewControl: false,
    })

    // Attach the directions renderer to the map
    directionsRenderer.value.setMap(map.value)

    // Display the route between store, customer, and driver locations
    calculateAndDisplayRoute()
}

const calculateAndDisplayRoute = () => {
    directionsService.value.route({
        origin: {
            lat: 10.25893392782387, 
            lng: 124.03877067362872
        },
        destination: JSON.parse(props.order.user.address_coordinates),
        travelMode: google.maps.TravelMode.DRIVING,
    })
    .then(response => {
        const route = response.routes[0]
        const duration = route.legs.reduce((total, leg) => total + leg.duration.value, 0)
        const durationText = new Date(duration * 1000).toISOString().substr(11, 8)
        const distance = route.legs[0].distance.text

        // Display route info at the midpoint
        const path = route.overview_path
        const midpointIndex = Math.floor(path.length / 2)
        if (!infoWindow.value) infoWindow.value = new google.maps.InfoWindow()
        infoWindow.value.setContent(`<p>Duration: ${durationText}</p><p>Distance: ${distance}</p>`)
        infoWindow.value.setPosition(path[midpointIndex])
        infoWindow.value.open(map.value)

        directionsRenderer.value.setDirections(response)
    })
    .catch(error => {
        console.error("Failed to get directions:", error)
        window.alert("Directions request failed due to " + error.message)
    })
}


const onShow = () => {
    const loader = initializeLoader(props.google_maps_api_key)
    loader.load().then(() => {
        initMap()
        // Uncomment for testing without actual driver movement
        // simulateDriverMovement()
    })
}

</script>

<template>
    <q-btn 
        no-caps 
        color="primary" 
        rounded 
        outline 
        unelevated 
        @click="dialog = !dialog" 
        label="View Order" 
    />
    <q-btn 
        color="primary" 
        no-caps
        rounded 
        unelevated
        :loading="deliverOrderForm.processing"
        :disable="deliverOrderForm.processing"
        @click="deliverOrder()"
        label="Accept Order"
        v-if="order.status == 'ready_for_delivery'"
    />
    <q-dialog 
        v-model="dialog" 
        :maximized="$q.screen.lt.md"
        transition-show="slide-up"
        transition-hide="slide-down"
        @show="onShow"
    >
        <OrderDetails :order="order">
            <div id="map" style="height: 200px; width: 100%;" class="q-mt-md"></div>
            <div class="q-mt-md">
                <q-btn 
                    class="full-width" 
                    color="primary" 
                    no-caps
                    rounded 
                    unelevated
                    :loading="deliverOrderForm.processing"
                    :disable="deliverOrderForm.processing"
                    @click="deliverOrder()"
                    label="Deliver Order"
                    v-if="order.status == 'ready_for_delivery'"
                />
                <q-btn
                    no-caps
                    class="full-width q-mt-sm"
                    :loading="completeOrderForm.processing"
                    :disable="completeOrderForm.processing"
                    @click="completeOrder()"
                    rounded
                    color="primary"
                    v-if="order.status == 'delivering'"
                    label="Complete Delivery"
                />
                <Link :href="route('driver.map', order.id)">
                    <q-btn
                        no-caps
                        class="full-width q-mt-sm"
                        rounded
                        outline
                        color="primary"
                        v-if="order.status == 'delivering'"
                        label="View in Map"
                    />
                </Link>
            </div>
        </OrderDetails>
    </q-dialog>
</template>