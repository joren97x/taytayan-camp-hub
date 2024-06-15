<script setup>

import DriverLayout from '@/Layouts/DriverLayout.vue'
import { ref, onMounted } from 'vue'
import { initializeLoader } from '@/Pages/Utils/GoogleMapsLoader'

defineOptions({
    layout: DriverLayout
})

const props = defineProps({
    customers_coordinates: Object,
    stores_coordinates: Object,
    drivers_coordinates: Object,
    google_maps_api_key: String
})


const map = ref(null)
const directionsService = ref(null)
const directionsRenderer = ref(null)
const infoWindow = ref(null)
const travelMode = ref(null)
const driverMarker = ref(null)

const initMap = () => {
    
    directionsService.value = new google.maps.DirectionsService()
    directionsRenderer.value = new google.maps.DirectionsRenderer()
    map.value = new google.maps.Map(document.getElementById("map"), {
        zoom: 7,
        center: props.customers_coordinates
    })

    directionsRenderer.value.setMap(map.value)
    travelMode.value = google.maps.TravelMode.DRIVING
    calculateAndDisplayRoute()
}


const calculateAndDisplayRoute = () => {
    directionsService.value
        .route({
            origin: props.stores_coordinates,
            destination: props.drivers_coordinates,
            waypoints: [
                {
                    location: props.customers_coordinates,
                    stopover: true, // Indicates this is a stop
                },
            ],
            // origin: props.customers_coordinates,
            // destination: { lat: 10.258812370722216, lng: 124.03867488692084 },
            travelMode: travelMode.value,
        })
        .then((response) => {
            console.log("please get the fkn time")
            console.log(response)

            // const duration = response.routes[0].legs[0].duration.text
            const duration = response.routes[0].legs.reduce((total, leg) => total + leg.duration.value, 0)
            const durationText = new Date(duration * 1000).toISOString().substr(11, 8)
            const distance = response.routes[0].legs[0].distance.text

            const path = response.routes[0].overview_path
            const midpointIndex = Math.floor(path.length / 2)

            if (!infoWindow.value) {
                infoWindow.value = new google.maps.InfoWindow()
            }

            infoWindow.value.setContent(`<p>Duration: ${durationText}</p> <p>${distance}</p>`)
            infoWindow.value.setPosition(path[midpointIndex])
            infoWindow.value.open(map.value)

            directionsRenderer.value.setDirections(response)
        })
        .catch((e) => 
            window.alert("Directions request failed due to " + e)
        )
}

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
        })
    }   

    // Optionally update the route if needed
    calculateAndDisplayRoute()
}

const simulateDriverMovement = () => {
  const positions = [
    { lat: 10.248812, lng: 124.036674 }, // Start position in Olango Island
    { lat: 10.251812, lng: 124.038674 },
    { lat: 10.254812, lng: 124.040674 },
    { lat: 10.257812, lng: 124.042674 },
  ];
  
  let index = 0;
  setInterval(() => {
    if (index < positions.length) {
      const simulatedPosition = {
        coords: {
          latitude: positions[index].lat,
          longitude: positions[index].lng,
        }
      };
      updateDriverLocation(simulatedPosition);
      index++;
    }
  }, 5000); // Update every 5 seconds
};

const watchDriverLocation = () => {
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(
        updateDriverLocation,
            (error) => {
                console.error('Error getting driver location: ', error);
            },
            {
                enableHighAccuracy: true,
                maximumAge: 0,
                timeout: 5000,
            }
        );
    } else {
        window.alert('Geolocation is not supported by this browser.');
    }
};


onMounted(() => {
    const loader = initializeLoader(props.google_maps_api_key)

    loader.load().then(() => {
        initMap()
        simulateDriverMovement()
        // uncomment if u at olango
        // watchDriverLocation()
    })
})

</script>

<template>
    {{ props }}
    <div id="map" style="height: 600px; width: 100%;"></div>
</template>
