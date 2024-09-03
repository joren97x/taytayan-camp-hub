<script setup>

import { ref, nextTick, computed, defineEmits } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { initializeLoader } from '@/Pages/Utils/GoogleMapsLoader'

const props = defineProps({
    google_maps_api_key: String,
    dialog: Boolean
})

const show = computed(() => props.dialog)
const emit = defineEmits(['close'])
const page = usePage()
const $q = useQuasar()

const form = useForm({
    phone_number: page.props.auth.user.phone_number,
    address: page.props.auth.user.address,
    address_coordinates: {
        lat: null,
        lng: null
    }
})

const submit = () => {
    form.put(route('add-address'), {
        onSuccess: () => {
            $q.notify('Address Successfully Set')
        }
    })
}

const placeInput = ref(null)
const loader = initializeLoader(props.google_maps_api_key)
let olangoBounds = ref(null)

const initializeAutocomplete = async () => {
    console.log("annyeong")
    const Places = await loader.importLibrary('places')
    olangoBounds.value = new google.maps.LatLngBounds(
        new google.maps.LatLng(10.2150, 124.0100),  // South-west corner
        new google.maps.LatLng(10.2700, 124.0600)   // North-east corner
    )
    await nextTick()

    const inputElement = placeInput.value.$el.querySelector('input')
    const autocomplete = new Places.Autocomplete(inputElement, {
        bounds: olangoBounds.value,
        componentRestrictions: { country: 'ph' }
    })

    console.log(autocomplete)
    autocomplete.addListener('place_changed', (e) => {
        const place = autocomplete.getPlace()
        if (isPlaceInOlango(place)) {
            form.address = place.formatted_address;
            form.address_coordinates.lat = place.geometry.location.lat();
            form.address_coordinates.lng = place.geometry.location.lng();
            map();
        } else {
            alert('Please select a location within Olango Island, Lapu-Lapu, Philippines.');
        } 
        // form.address = place.formatted_address
        // form.address_coordinates.lat = place.geometry.location.lat()
        // form.address_coordinates.lng = place.geometry.location.lng()
        // map()
    })

}

const isPlaceInOlango = (place) => {
  return olangoBounds.value.contains(place.geometry.location);
}

async function map() {
    const { Map, InfoWindow } = await loader.importLibrary('maps')
    const { AdvancedMarkerElement } = await loader.importLibrary('marker')

    const map = new Map(document.getElementById('map'), {
        center: form.address_coordinates,
        zoom: 17,
        mapId: '4504f8b37365c3d0',
    })

    const infoWindow = new InfoWindow()
    const draggableMarker = new AdvancedMarkerElement({
        map,
        position: form.address_coordinates,
        gmpDraggable: true,
        title: 'This marker is draggable.',
    })

    const content = `
            <div class="text-weight-bold text-center text-subtitle1">Yo joren you can actually move this marker</div>
            <div class="text-subtitle2">waitt frr?</div>
        `
        // infoWindow.close()
        infoWindow.setContent(content)
        infoWindow.open(map, draggableMarker)

    draggableMarker.addListener('dragend', (event) => {
        const position = draggableMarker.position


        if (!olangoBounds.value.contains(position)) {
            alert('The marker must be within Olango Island, Lapu-Lapu, Philippines.');
            draggableMarker.position = new google.maps.LatLng(form.address_coordinates.lat, form.address_coordinates.lng)
        } else {
            form.address_coordinates.lat = position.lat
            form.address_coordinates.lng = position.lng
            const content = `
                <div class="text-weight-bold text-center text-subtitle1">Your address is here</div>
                <div class="text-subtitle2">Please check your map location is correct</div>
            `;
            infoWindow.setContent(content);
            infoWindow.open(map, draggableMarker);
        }

    })
}

</script>

<template>
    <q-dialog 
        v-model="show" 
        @show="initializeAutocomplete"
        :maximized="$q.screen.lt.sm"
        style="z-index: 999;"
    >
        <q-card 
            :style="[
                $q.screen.gt.xs ? 'width: 100%; max-width: 70vw;' : '',
                $q.screen.sm ? 'width: 100%; max-width: 90vw;' : '',
            ]"
        >
        {{ $q.screen.width >= 700 }}
        {{ $q.screen.lt.sm }}

            <q-form @submit="submit">
                <q-card-section>
                    <div class="text-h6">New Address</div>
                    <div>To place order, please add a delivery address</div>
                </q-card-section>
            
                <q-card-section>
                    <!-- <q-input filled label="Phone Number"></q-input> -->
                    <q-input
                        filled
                        v-model="form.phone_number"
                        label="Phone"
                        :error="form.errors.phone_number ? true : false"
                        :error-message="form.errors.phone_number"
                    />

                    <q-input 
                        filled 
                        v-model="form.address" 
                        class="q-my-md" 
                        ref="placeInput"
                        id="place"
                        :error="form.errors.address ? true : false"
                        :error-message="form.errors.address"
                        label="Barangay, City, Province"
                    />
                    <q-banner dense class="bg-red-1 q-mb-md" v-if="form.address">
                        <template v-slot:avatar>
                            <q-icon name="alert" color="primary" />
                        </template>
                        <p class="text-weight-bold text-primary text-h6">Place an accurate pin</p>
                        <p class="text-subtitle-1">
                            We will deliver to your map location. 
                            Please check it is correct, else click the map to adjust.
                        </p>
                    </q-banner>
                    <div id="map" style="width: 100%; height: 600px"  v-if="form.address"></div>
                </q-card-section>
                <q-card-actions>
                    <q-space/>
                    <q-btn v-close-popup no-caps flat @click="emit('close')">Cancel</q-btn>
                    <q-btn 
                        v-close-popup 
                        color="primary"
                        type="submit" 
                        unelevated 
                        no-caps
                        :loading="form.processing"
                        :disable="form.processing"
                    >
                        Submit
                    </q-btn>
                </q-card-actions>
            </q-form>
        </q-card>
    </q-dialog>
</template>