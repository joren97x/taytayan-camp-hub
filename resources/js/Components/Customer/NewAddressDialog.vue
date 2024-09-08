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
            emit('close')
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
            <div class="text-weight-bold text-center text-subtitle2">Your location is here</div>
            <div>Please check your map location is correct</div>
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
                <div class="text-weight-bold text-center text-subtitle2">Your location is here</div>
                <div>Please check your map location is correct</div>
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
         transition-show="slide-up"
        transition-hide="slide-down"
    >
        <q-card 
            :style="[
                $q.screen.gt.xs ? 'width: 100%; max-width: 70vw;' : '',
                $q.screen.sm ? 'width: 100%; max-width: 90vw;' : '',
            ]"
        >

            <q-form @submit="submit">
                <q-card-section class="row justify-between">
                    <div>
                        <span class="text-h6">New Address</span>
                        <br>
                        To place order, please add a delivery address
                    </div>
                    <div>
                        <q-btn icon="close" round unelevated @click="emit('close')" v-close-popup/>
                    </div>
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
                    <!-- <q-banner dense class="bg-red-1 q-mb-md" v-if="form.address">
                     
                    </q-banner> -->
                    <div v-show="form.address" class="q-pa-sm q-mb-md bg-red-1">
                        <span class="text-primary text-subtitle1">
                            <q-icon name="warning" color="red" class="q-mx-md"></q-icon>
                            Place an accurate pin.
                            <span class="text-weight-light text-body2">
                                We will deliver to your map location. 
                            Please check it is correct, else click the map to adjust.
                            </span>
                        </span>
                    </div>
                    <div 
                        style="width: 100%; height: 500px" 
                        :class="['bg-grey items-center flex', form.address ? '' : 'non-selectable cursor-not-allowed']"
                    >
                        <div id="map" style="width: 100%; height: 100%;" v-if="form.address"></div>
                        <div class="text-center" style="width: 100%; position: relative;" v-else>
                            <q-btn icon="add" no-caps class="non-selectable cursor-not-allowed">Add Location</q-btn>
                        </div>
                    </div>
                </q-card-section>
                <q-card-actions :class="['bg-white', $q.screen.lt.sm ? 'fixed-bottom' : '']">
                    <q-space/>
                    <q-btn 
                        v-close-popup 
                        color="primary"
                        type="submit" 
                        unelevated 
                        no-caps
                        class="full-width"
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