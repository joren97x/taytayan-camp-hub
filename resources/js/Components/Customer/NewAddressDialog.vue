<script setup>

import { useForm, usePage } from '@inertiajs/vue3'
import { computed, onMounted, ref, nextTick, watch } from 'vue'
import { useQuasar } from 'quasar'
import { Loader } from "@googlemaps/js-api-loader"

const emit = defineEmits(['close'])
const props = defineProps({
    dialog: Boolean,
    google_maps_api_key: String
})

const $q = useQuasar()
const page = usePage()
const show = computed(() => props.dialog)
    
const form = useForm({
    phone_number: page.props.auth.user.phone_number,
    address: page.props.auth.user.address
})

const submit = () => {
    form.put(route('add-address'), {
        onSuccess: () => {
            emit('close')
            $q.notify('Address Successfully Set')
        }
    })
}

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

watch(() => props.dialog, async (newValue) => {
    console.log('dialog prop changed:', newValue)
    if(newValue) {
        console.log('annyeong')
        const Places = await loader.importLibrary('places')

        await nextTick()

        const inputElement = placeInput.value.$el.querySelector('input')
        const autocomplete = new google.maps.places.Autocomplete(inputElement)
        console.log(autocomplete)
        autocomplete.addListener('place_changed', () => {
            console.log('place', place)
            const place = autocomplete.getPlace() //this callback is inherent you will see it if you logged autocomplete
            console.log('place', place.geometry.location)
            console.log('place', place.geometry.location.lat())
            coords.value.lat = place.geometry.location.lat()
            coords.value.lng = place.geometry.location.lng()
            map()
        })
        console.log('annyeong')

    }
})

onMounted(async () => {

    
   

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
    <q-dialog v-model="show" class="full-width">
        <q-card>
            <q-form @submit="submit">
                <q-card-section>
                    <div class="text-h6">New Address</div>
                    <div>To place order, please add a delivery address</div>
                </q-card-section>
                <q-card-section>
                    {{ form }}
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
                    <!-- <q-input
                        id="place"
                        v-model="location"
                        ref="placeInput"
                        filled
                        placeholder="Enter a location"
                        @change="handlePlaceChanged"
                    /> -->
                    <div id="map" style="width: 100%; height: 500px"></div>
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