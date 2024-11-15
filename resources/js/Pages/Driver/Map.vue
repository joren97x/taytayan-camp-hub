<script setup>
import DriverLayout from '@/Layouts/DriverLayout.vue'
import { ref, onMounted } from 'vue'
import { initializeLoader } from '@/Pages/Utils/GoogleMapsLoader'

defineOptions({
    layout: DriverLayout
})

// Define props for passing coordinates and Google Maps API key
const props = defineProps({
    customers_coordinates: Object,
    stores_coordinates: Object,
    drivers_coordinates: Object,
    google_maps_api_key: String
})

// Map and Google Maps services references
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
        center: props.customers_coordinates
    })

    // Attach the directions renderer to the map
    directionsRenderer.value.setMap(map.value)

    // Display the route between store, customer, and driver locations
    calculateAndDisplayRoute()
}

// Function to calculate and display the route on the map
const calculateAndDisplayRoute = () => {
    directionsService.value.route({
        origin: props.stores_coordinates,
        destination: props.drivers_coordinates,
        waypoints: [{ location: props.customers_coordinates, stopover: true }],
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

// Update the driver's marker on the map and optionally recalculate the route
const updateDriverLocation = (position) => {
    const { latitude, longitude } = position.coords
    const driverLatLng = new google.maps.LatLng(latitude, longitude)

    if (driverMarker.value) {
        driverMarker.value.setPosition(driverLatLng)
    } else {
        driverMarker.value = new google.maps.Marker({
            position: driverLatLng,
            map: map.value,
            title: "Driver's Location",
            icon: {
                url: 'path/to/driver-icon.png',
                scaledSize: new google.maps.Size(40, 40)
            }
        })
    }

    // Recalculate route if necessary (optional)
    calculateAndDisplayRoute()
}

// Watch the driver's location in real-time using geolocation
const watchDriverLocation = () => {
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(
            updateDriverLocation,
            error => console.error("Error getting driver location:", error),
            { enableHighAccuracy: true, maximumAge: 0, timeout: 5000 }
        )
    } else {
        window.alert('Geolocation is not supported by this browser.')
    }
}

// Simulate driver movement for testing purposes
// const simulateDriverMovement = () => {
//     const positions = [
//         { lat: 10.248812, lng: 124.036674 },
//         { lat: 10.251812, lng: 124.038674 },
//         { lat: 10.254812, lng: 124.040674 },
//         { lat: 10.257812, lng: 124.042674 }
//     ]
    
//     let index = 0
//     setInterval(() => {
//         if (index < positions.length) {
//             const simulatedPosition = {
//                 coords: {
//                     latitude: positions[index].lat,
//                     longitude: positions[index].lng
//                 }
//             }
//             updateDriverLocation(simulatedPosition)
//             index++
//         }
//     }, 5000) // Update every 5 seconds
// }

// Initialize the map and start tracking the driver's location on component mount
onMounted(() => {
    const loader = initializeLoader(props.google_maps_api_key)
    loader.load().then(() => {
        initMap()
        watchDriverLocation()
        // Uncomment for testing without actual driver movement
        // simulateDriverMovement()
    })
})
</script>

<template>
    <div id="map" style="height: 90vh; width: 100vw;"></div>
</template>