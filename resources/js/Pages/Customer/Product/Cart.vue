<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link, Head } from '@inertiajs/vue3'
import { ref, nextTick, onMounted } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { initializeLoader } from '@/Pages/Utils/GoogleMapsLoader'
import EditCartItemDialog from '@/Components/Customer/Product/EditCartItemDialog.vue'
import NewAddressDialog from '@/Components/Customer/NewAddressDialog.vue'

defineOptions({
    layout: CustomerLayout
})

const props = defineProps({
    items: Object,
    subtotal: Number,
    google_maps_api_key: String,
    cart: Object
})

const showNewAddressDialog = ref(false)
const showFoodCartItemDialog = ref(false)
const selectedCartItem = ref(null)
const page = usePage()
const $q = useQuasar()

const isAdressSet = () => {
    if(page.props.auth.user.address == null) {
        showNewAddressDialog.value = true
    }
    else {
        router.visit(route('product.checkout'), {
            data: {
                cart_id: props.cart.id
            }
        })
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

const deleteCartItemForm = useForm({})
const updateCartItemQuantityForm = useForm({
    operation: ''
})

function deleteCartItem(id) {
    deleteCartItemForm.delete(route('customer.cart.destroy', id), {
        onSuccess: () => {
            $q.notify('Cart Item Removed From Cart')
        }
    })
}

function updateCartItemQuantity(id, operation) {
    updateCartItemQuantityForm.operation = operation
    updateCartItemQuantityForm.put(route('customer.cart.update_cart_item_quantity', id), {
        onSuccess: () => {
            $q.notify('basta gi addan nimo shag quantity')
        }
    })
}

function showEditCartItemDialog(cartItem) {
    selectedCartItem.value = cartItem
    showFoodCartItemDialog.value = true
    console.log(cartItem)
}

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
    <!-- {{ props }} -->
    <NewAddressDialog 
        :dialog="showNewAddressDialog" 
        @close="showNewAddressDialog = false"
        :google_maps_api_key="google_maps_api_key" 
        v-if="$page.props.auth.user"
    />
    {{ cart }}
    <div class="row q-mb-xl q-col-gutter-md">
        <div class="col-8 col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
            <q-table
                class="my-sticky-header-column-table q-mb-xl"
                bordered
                flat
                :rows="items"
                :columns="columns"
                row-key="name"
            >
                <template v-slot:top>
                        <span class="text-h6">Cart</span> 
                        <q-space/>
                        <span class="text-subtitle1">3 items</span>
                </template>
                <template v-slot:body-cell-photo="props">
                    <q-td :props="props">
                        <q-img height="80px" width="80px" :src="`/storage/${props.row.product.photo}`"></q-img>
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
                        <q-item-label caption v-if="props.row.special_instruction">
                            Note: {{ props.row.special_instruction }}
                        </q-item-label>
                    </q-td>
                </template>
                <template v-slot:body-cell-quantity="props">
                    <q-td :props="props">
                            <q-btn flat size="xs" icon="remove" @click="updateCartItemQuantity(props.row.id, '-')" v-show="props.row.quantity > 1" />
                            <span class="q-mt-xs q-mx-sm">{{ props.row.quantity }} </span>
                            <q-btn flat size="xs" icon="add" @click="updateCartItemQuantity(props.row.id, '+')" />
                    </q-td>
                </template>
                <template v-slot:body-cell-total="props">
                    <q-td :props="props">
                        P{{ props.row.total }}
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <q-btn no-caps unelevated @click="showEditCartItemDialog(props.row)">Edit</q-btn>
                        <q-btn no-caps 
                            unelevated color="red" flat 
                            :loading="deleteCartItemForm.processing"
                            :disable="deleteCartItemForm.processing"
                            @click="deleteCartItem(props.row.id)"
                        >
                            Remove
                        </q-btn>
                    </q-td>
                </template>
                <template v-slot:no-data>
                    <div class="full-width row flex-center q-gutter-sm" style="height: 50vh">
                        No Orders Found
                    </div>
                </template>
            </q-table>
        </div>
        <div class="col-4 col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <q-card>
                <q-card-section>
                    <div class="text-h6">Order Summary</div>
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
    </div>
    <!-- <q-dialog v-model="showNewAddressDialog" style="z-index: 1;" @show="initializeAutocomplete">
        <div>
            <q-card style=" margin-top: 100px;">
                <q-form @submit="submit">
                    <q-card-section>
                        <div class="text-h6">New Address</div>
                        <div>To place order, please add a delivery address</div>
                    </q-card-section>
                
                    <q-card-section>
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
    </q-dialog> -->

    <EditCartItemDialog :dialog="showFoodCartItemDialog" :cart_item="selectedCartItem" @close="showFoodCartItemDialog = false" />

</template>

<style scoped>

.pac-container {
  z-index: 10000; /* Adjust the z-index as needed */
}

</style>