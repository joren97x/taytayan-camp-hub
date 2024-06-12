<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link, Head } from '@inertiajs/vue3'
import { ref, nextTick, onMounted } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { Loader } from "@googlemaps/js-api-loader"
import { initializeLoader } from '@/Pages/Utils/GoogleMapsLoader'

defineOptions({
    layout: CustomerLayout
})

const props = defineProps({
    items: Object,
    subtotal: Number,
    google_maps_api_key: String
})

const showNewAddressDialog = ref(false)
const page = usePage()
const $q = useQuasar()

const isAdressSet = () => {
    if(page.props.auth.user.address == null) {
        showNewAddressDialog.value = true
    }
    else {
        router.visit(route('product.checkout'))
    }
}

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
// const loader = new Loader({
//     apiKey: props.google_maps_api_key,
//     version: 'weekly',
//     libraries: ['maps', 'marker']
// })

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


        // console.log(event)
        // console.log(position.lat)
        // const content = `
        //     <div class="text-weight-bold text-center text-subtitle1">Your address is here</div>
        //     <div class="text-subtitle2">Please check your map location is correct</div>
        // `
        // infoWindow.setContent(content)
        // // infoWindow.setContent(`Pin dropped at: ${position.lat}, ${position.lng}`)
        // infoWindow.open(map, draggableMarker)
    })
}

const card = ref(false)
const columns = [
    { name: 'photo', label: '', align: 'center', field: 'photo', sortable: true },
    { name: 'item', label: 'Item', align: 'center', field: 'item', sortable: true },
    { name: 'quantity', align: 'center', label: 'Quantity', field: 'quantity', sortable: true },
    { name: 'total', align: 'center', label: 'Total', field: 'total', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    
    <Head title="Cart" />
    <div class="row q-mb-xl q-col-gutter-md">
        <div class="col-8">
            <q-table
                class="my-sticky-header-column-table q-mb-xl"
                flat
                title="Cart"
                :rows="items"
                :columns="columns"
                row-key="name"
            >
                <template v-slot:body-cell-photo="props">
                    <q-td :props="props">
                        <q-img height="80px" width="80px" :src="`images/${props.row.product.photo}`"></q-img>
                    </q-td>
                </template>
                <template v-slot:body-cell-item="props">
                    <q-td :props="props" class="text-left">
                        {{ props.row.product.name }} ({{ props.row.product.price }})
                        <template 
                            v-for="(modifier, index) in props.row.grouped_modifiers" 
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
                        <!-- <q-item-label caption>
                            {{ props.row.grouped_modifiers }}
                        </q-item-label> -->
                        <q-item-label caption v-if="props.row.special_instruction">
                            Note: {{ props.row.special_instruction }}
                        </q-item-label>
                    </q-td>
                </template>
                <template v-slot:body-cell-quantity="props">
                    <q-td :props="props">
                            <q-btn flat size="xs" icon="remove" />
                            <span class="q-mt-xs q-mx-sm">{{ props.row.quantity }} </span>
                            <q-btn flat size="xs" icon="add" />
                    </q-td>
                </template>
                <template v-slot:body-cell-total="props">
                    <q-td :props="props">
                        P{{ props.row.total }}
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <q-btn no-caps unelevated>Edit</q-btn>
                        <q-btn no-caps unelevated color="red" flat @click="card = true">Remove</q-btn>
                    </q-td>
                </template>
            </q-table>
        </div>
        <div class="col-4">
            <q-card>
                <q-card-section>
                    <div class="text-h6">Cart Total</div>
                    <q-separator></q-separator>
                    <q-item>
                        <q-item-section>
                            <q-item-label>Subtotal</q-item-label>
                        </q-item-section>
                        <q-item-section side top>
                            <q-item-label>{{ subtotal }}</q-item-label>
                        </q-item-section>
                    </q-item>
                    <q-item>
                        <q-item-section>
                            <q-item-label>Total</q-item-label>
                        </q-item-section>
                        <q-item-section side top>
                            <q-item-label>{{ subtotal }}</q-item-label>
                        </q-item-section>
                    </q-item>
                </q-card-section>
                
                <q-card-section vertical>
                    <q-separator class="q-mb-sm"></q-separator>
                    <!-- <Link :href="route('product-checkout')"> -->
                        <q-btn color="primary" @click="isAdressSet" class="full-width" no-caps>Go To Checkout</q-btn>
                    <!-- </Link> -->
                </q-card-section>
            </q-card>
        </div>
        <q-dialog v-model="card">
            <q-card class="my-card">
                <q-img src="https://cdn.quasar.dev/img/chicken-salad.jpg" />
                <q-card-section>
                    <div class="row no-wrap items-center">
                        <p class="col text-h6 ellipsis">
                            Cafe Basilico
                        </p>
                    </div>
                </q-card-section>

                <q-card-section class="q-pt-none">
                    <div class="text-subtitle1">
                        $・Italian, Cafe
                    </div>
                    <div class="text-caption text-grey">
                        Small plates, salads & sandwiches in an intimate setting.
                    </div>
                </q-card-section>

                <q-separator />

                <q-card-actions align="right">
                    <q-btn v-close-popup flat color="primary" label="Reserve" />
                    <q-btn v-close-popup flat color="primary" round icon="event" />
                </q-card-actions>
            </q-card>
        </q-dialog>
        <!-- <NewAddressDialog @close="showNewAddressDialog = false" :dialog="showNewAddressDialog" :google_maps_api_key="google_maps_api_key" /> -->
    </div>
    <q-dialog v-model="showNewAddressDialog" style="z-index: 1;" @show="initializeAutocomplete">
        <div>
            <q-card style=" margin-top: 100px;">
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
                        <q-btn v-close-popup no-caps flat>Cancel</q-btn>
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
        </div>
    </q-dialog>

</template>

<style scoped>

.pac-container {
  z-index: 10000; /* Adjust the z-index as needed */
}

</style>