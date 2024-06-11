<script setup>

import { GoogleMap, Marker } from 'vue3-google-map'
import { Loader } from "@googlemaps/js-api-loader"
import { onMounted, ref, nextTick } from 'vue'

const props = defineProps({
    google_maps_api_key: String
})

const location = ref('')
const placeInput = ref(null)
const coords = ref({
    lat: null,
    lng: null
})

const loader = new Loader({
    apiKey: props.google_maps_api_key,
    version: 'weekly',
    libraries: ['maps', 'marker']
})

onMounted(async () => {

    const Places = await loader.importLibrary('places')

    await nextTick()

// Access the input element from the q-input component reference
    const inputElement = placeInput.value.$el.querySelector('input')
    const autocomplete = new Places.Autocomplete(inputElement)

    autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace() //this callback is inherent you will see it if you logged autocomplete
        console.log('place', place)
        console.log('place', place.geometry.location)
        console.log('place', place.geometry.location.lat())
        coords.value.lat = place.geometry.location.lat()
        coords.value.lng = place.geometry.location.lng()
        map()
    })
   

})

async function map() {
    const { Map, InfoWindow } = await loader.importLibrary('maps')
    const { AdvancedMarkerElement } = await loader.importLibrary('marker')

    const map = new Map(document.getElementById('map'), {
        center: coords.value,
        zoom: 14,
        mapId: '4504f8b37365c3d0',
    })

    const infoWindow = new InfoWindow()
    const draggableMarker = new AdvancedMarkerElement({
        map,
        position: coords.value,
        gmpDraggable: true,
        title: 'This marker is draggable.',
    })

    draggableMarker.addListener('dragend', (event) => {
        const position = draggableMarker.position
        console.log(event)
        console.log(position.lat)
        const content = `
            <div class="text-weight-bold text-center text-subtitle1">Your address is here</div>
            <div class="text-subtitle2">Please check your map location is correct</div>
        `
        // infoWindow.close()
        infoWindow.setContent(content)
        // infoWindow.setContent(`Pin dropped at: ${position.lat}, ${position.lng}`)
        infoWindow.open(map, draggableMarker)
    })
}

</script>

<template>
  <div>
    <!-- <input id="place" type="text" placeholder="Enter a location" /> -->
    <q-input
        id="place"
        v-model="location"
        ref="placeInput"
        filled
        placeholder="Enter a location"
        @change="handlePlaceChanged"
    />
    <div id="map" style="width: 100%; height: 500px"></div>
    <!-- <GoogleMap
      ref="map"
      :api-key="google_maps_api_key"
      style="width: 100%; height: 500px"
      :center="center"
      :zoom="15"
    /> -->
  </div>
</template>
