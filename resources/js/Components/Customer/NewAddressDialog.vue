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

// console.log(JSON.parse(page.props.auth.user.address_coordinates))
const form = useForm({
    phone_number: page.props.auth.user.phone_number,
    address: page.props.auth.user.address,
    street: page.props.auth.user.street,
    address_coordinates: {
        lat: page.props.auth.user.address_coordinates ? JSON.parse(page.props.auth.user.address_coordinates).lat : null,
        lng: page.props.auth.user.address_coordinates ? JSON.parse(page.props.auth.user.address_coordinates).lng : null
    }
})
// console.log(form)

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
    // console.log("Initializing autocomplete")
    const Places = await loader.importLibrary('places')

    // Define Olango Island bounds to restrict location selection
    olangoBounds.value = new google.maps.LatLngBounds(
        new google.maps.LatLng(10.205, 124.014),  // South-west corner
        new google.maps.LatLng(10.305, 124.085)   // North-east corner
    )

    await nextTick()

    const inputElement = placeInput.value.$el.querySelector('input')
    const autocomplete = new Places.Autocomplete(inputElement, {
        bounds: olangoBounds.value,
        componentRestrictions: { country: 'ph' }
    })

    // Handle selection of a place within valid bounds
    autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace()
        if (isPlaceInOlango(place)) {
            form.address = place.formatted_address
            form.address_coordinates.lat = place.geometry.location.lat()
            form.address_coordinates.lng = place.geometry.location.lng()
            map()  // Initialize map with new valid coordinates
        } else {
            $q.notify({
                message: 'Please select a location within Olango Island, Lapu-Lapu, Philippines.',
                color: 'negative',
                textColor: 'white',
                position: 'top'
            })
        }
    })

    // Initialize map if existing coordinates are valid
    if (form.address_coordinates.lat && form.address_coordinates.lng) {
        map()
    }
}

const isPlaceInOlango = (place) => {
    return place.geometry && olangoBounds.value.contains(place.geometry.location)
}


async function map() {
    try {
        const { Map, InfoWindow } = await loader.importLibrary('maps')
        const { AdvancedMarkerElement } = await loader.importLibrary('marker')

        const map = new Map(document.getElementById('map'), {
            center: form.address_coordinates,
            zoom: 17,
            mapId: '4504f8b37365c3d0',
            mapTypeControl: false,        // Disables Map/Satellite options
            streetViewControl: false,
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
            <div>Please check if your map location is correct</div>
        `
        infoWindow.setContent(content)
        infoWindow.open(map, draggableMarker)

        draggableMarker.addListener('dragend', (event) => {
            const position = draggableMarker.position

            // Check if new marker position is within valid bounds
            if (!olangoBounds.value.contains(position)) {
                $q.notify({
                    message: 'The marker must be within Olango Island, Lapu-Lapu, Philippines.',
                    color: 'negative',
                    textColor: 'white',
                    position: 'top'
                })
                // Reset marker position to last valid coordinates
                draggableMarker.position = new google.maps.LatLng(form.address_coordinates.lat, form.address_coordinates.lng)
            } else {
                // Update form coordinates with new valid marker position
                form.address_coordinates.lat = position.lat
                form.address_coordinates.lng = position.lng
                const updatedContent = `
                    <div class="text-weight-bold text-center text-subtitle2">Your location is here</div>
                    <div>Please check if your map location is correct</div>
                `
                infoWindow.setContent(updatedContent)
                infoWindow.open(map, draggableMarker)
            }
        })
    }
    catch (error) {
        console.error(error)
    }
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
            :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''"
        >
                <q-card-actions class="">
                    <div class="q-mr-xl">
                        <span class="text-h6">New Address (Olango Island)</span>
                        <br>
                        Enter your Olango Island address to proceed with the delivery
                    </div>
                        <q-btn icon="close" round unelevated @click="emit('close')" class="absolute-top-right q-mr-sm q-mt-sm" v-close-popup/>
                </q-card-actions>
            
                <q-card-section>
                    <div class="row q-col-gutter-x-md">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                            <q-input
                                outlined
                                dense 
                                rounded
                                type="number"
                                v-model="form.phone_number"
                                label="Phone Number"
                                :error="form.errors.phone_number ? true : false"
                                :error-message="form.errors.phone_number"
                            />
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                            <q-input 
                                outlined
                                dense 
                                rounded 
                                v-model="form.address" 
                                ref="placeInput"
                                id="place"
                                :error="form.errors.address ? true : false"
                                :error-message="form.errors.address"
                                label="Barangay, Province, City"
                            />
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4">
                            <q-input 
                                outlined
                                dense 
                                rounded 
                                v-model="form.street" 
                                :error="form.errors.street ? true : false"
                                :error-message="form.errors.street"
                                label="Street Name Purok, House No."
                            />
                        </div>
                    </div>
                    <q-banner v-show="form.address" class="q-py-sm q-mb-md rounded-borders" style="border: 1px solid var(--q-warning)">
                        <q-icon name="info" color="warning" class="q-mr-sm"/>
                        Place an accurate pin. We will deliver to your map location. 
                        Please check it is correct, else click the map to adjust.
                    </q-banner>
                    <div 
                        style="width: 100%; height: 500px" 
                        :class="['items-center flex', form.address ? '' : 'non-selectable cursor-not-allowed']"
                    >
                        <div id="map" style="width: 100%; height: 100%;" v-if="form.address">
                        </div>
                        <div class="text-center" style="width: 100%; position: relative;" v-else>
                            <q-btn icon="add" no-caps class="non-selectable cursor-not-allowed" flat label="Add Location"/>
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
                        rounded
                        class="full-width"
                        :loading="form.processing"
                        :disable="form.processing"
                        label="Submit"
                        @click="submit"
                    />
                </q-card-actions>
        </q-card>
    </q-dialog>
</template>

<style scoped>
</style>