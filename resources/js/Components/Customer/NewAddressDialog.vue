<script setup>

import { useForm, usePage } from '@inertiajs/vue3'
import { computed, onMounted, ref, nextTick, watch, onActivated } from 'vue'
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

onActivated(() => {
    console.log("activated")
})

    // onMounted(async () => {

    //     const Places = await loader.importLibrary('places')

    //     await nextTick()

    //     // Access the input element from the q-input component reference
    //     const inputElement = placeInput.value.$el.querySelector('input')
    //     const autocomplete = new Places.Autocomplete(inputElement)

    //     autocomplete.addListener('place_changed', () => {
    //         const place = autocomplete.getPlace() //this callback is inherent you will see it if you logged autocomplete
    //         console.log('place', place)
    //         console.log('place', place.geometry.location)
    //         console.log('place', place.geometry.location.lat())
    //         coords.value.lat = place.geometry.location.lat()
    //         coords.value.lng = place.geometry.location.lng()
    //         map()
    //     })


    // })

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
    
</template>