<script setup>

import { ref, defineEmits, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { formatDistance, differenceInMinutes } from 'date-fns'
import OrderDetails from '@/Components/OrderDetails.vue'
import { initializeLoader } from '@/Pages/Utils/GoogleMapsLoader'

const props = defineProps({
    order: Object,
    order_statuses: Object,
    google_maps_api_key: String
})

const emit = defineEmits(['order_updated'])
const dialog = ref(false)
const cancelOrderDialog = ref(false)
const readyOrderDialog = ref(false)
const $q = useQuasar()

const acceptOrderForm = useForm({
    waiting_time: 20,
    status: props.order_statuses.preparing
})

const orderForm = useForm({
    status: props.order.mode == 'pickup' ? props.order_statuses.ready_for_pickup : props.order_statuses.ready_for_delivery
})

function acceptOrder() {
    acceptOrderForm.patch(`/cashier/orders/prepare-order/${props.order.id}`, {
        onSuccess: (e) => {
            emit('order_updated')
            // orderStore.getOrders()
            dialog.value = false
            $q.notify('Order Accepted')
        }
    })
}



function readyOrder() {
    orderForm.patch(route('cashier.orders.update_status', props.order.id), {
        onSuccess: (e) => {
            // emit('order_updated')
            emit('order_updated')

            // orderStore.getOrders()
            console.log('go fetch')
            readyOrderDialog.value = false
            $q.notify('Order Accepted asdsads')
        }
    })
}

function cancelOrder() {
    orderForm.status = 'cancelled'
    orderForm.patch(route('cashier.orders.update_status', props.order.id), {
        onSuccess: (e) => {
            emit('order_updated')
            cancelOrderDialog.value = false
            $q.notify('Order Cancelled')
        }
    })
}

const directionsService = ref(null)
const duration = ref(null)
const delivery_time = ref('')
const getDuration = () => {
    console.log(props.google_maps_api_key)
    const loader = initializeLoader(props.google_maps_api_key)
    loader.load().then(() => {
    directionsService.value = new google.maps.DirectionsService()
    directionsService.value
        .route({
            origin: JSON.parse(props.order.user.address_coordinates),
            destination: { lat: 10.258812370722216, lng: 124.03867488692084 },
            travelMode: google.maps.TravelMode.DRIVING
        })
        .then((response) => {
            console.error(response)
            // duration.value = response.routes[0].legs[0].duration.text;
            delivery_time.value = response.routes[0].legs[0].duration.text
            // Convert duration to minutes and add extra 10 minutes
            const durationInMinutes = parseDurationToMinutes(delivery_time.value)
            duration.value = durationInMinutes
            acceptOrderForm.waiting_time = durationInMinutes + 10
        })
        .catch((e) => {
            console.error(e)
            $q.notify({
                message: `It seems the marker is placed in an area where no route exists.`,
                color: 'negative',
                textColor: 'white',
                position: 'top'
            })
        });
    })
};
const parseDurationToMinutes = (durationText) => {
    let totalMinutes = 0;
    const hoursMatch = durationText.match(/(\d+)\s*hour/)
    const minutesMatch = durationText.match(/(\d+)\s*min/)

    if (hoursMatch) {
        totalMinutes += parseInt(hoursMatch[1]) * 60
    }
    if (minutesMatch) {
        totalMinutes += parseInt(minutesMatch[1])
    }

    return totalMinutes
}

</script>

<template>
    <!-- <OrderItem :order="order" @click="dialog = true"> -->
        <q-item :class="[order.status == 'pending' ? 'bg-blue-2' : 'bg-grey-4', 'q-my-sm', 'rounded-borders']" :clickable="order.status == order_statuses.pending" v-ripple @click="dialog = true">
            <q-item-section>
                <q-item-label>{{ order.user.first_name + ' ' + order.user.last_name }}</q-item-label>
                <q-item-label caption lines="2">{{ order.cart_products.length }} items</q-item-label>
            </q-item-section>
            <q-item-section side top v-if="order_statuses.pending == order.status">
                New
                <!-- <q-item-label caption>{{ date.getDateDiff(new Date(), order.created_at, 'minutes') }} minutes ago</q-item-label> -->
                <q-item-label caption>{{ formatDistance(new Date(), order.created_at) }} ago</q-item-label>
                <!-- <q-btn no-caps color="primary" @click.stop="isOrderReadyDialog = true">
                    View Order
                </q-btn> -->
            </q-item-section>
            <q-item-section side top v-else>
                <q-btn no-caps color="primary" rounded unelevated @click.stop="readyOrderDialog = true">
                    Ready Order
                </q-btn>
                <q-item-label caption>{{ formatDistance(new Date(), order.created_at) }} ago</q-item-label>
                <!-- <q-item-label caption>{{ date.getDateDiff(new Date(), order.created_at, 'minutes') }} minutes ago</q-item-label> -->
            </q-item-section> 
        </q-item>
<!-- </OrderItem> -->
<q-dialog 
    v-model="dialog" 
    transition-show="slide-up"
    transition-hide="slide-down"
    @show="getDuration"
    :maximized="$q.screen.lt.md"
>
    <OrderDetails :order="order">
        <div class="text-subtitle1 text-weight-medium text-center q-mt-md">Estimated Time of Arrival</div>
        Delivery Time: {{ delivery_time }}
            <q-input
                outlined 
                rounded
                v-model="acceptOrderForm.waiting_time"
                label="Recommended Waiting Time In Minutes"
                unmasked-value
                :error="acceptOrderForm.errors.waiting_time ? true : false"
                :error-message="acceptOrderForm.errors.waiting_time"
                type="number"
            />

            <div>
                <q-btn 
                    class="full-width q-mb-sm" 
                    color="primary" no-caps
                    :loading="acceptOrderForm.processing"
                    :disable="acceptOrderForm.processing"
                    @click="acceptOrder()"
                    rounded 
                    unelevated
                    label="Accept Order"
                />
                <q-btn 
                    class="full-width" 
                    color="red" 
                    outline 
                    rounded
                    no-caps
                    @click="cancelOrderDialog = true"
                    label="Cancel Order"
                />
            </div>
    </OrderDetails>
</q-dialog>

    <q-dialog 
        v-model="readyOrderDialog"
        transition-show="slide-up"
        transition-hide="slide-down"
        :maximized="$q.screen.lt.md"
    >
        <OrderDetails :order="order">
            <div class="text-subtitle1 text-weight-medium text-center q-mt-md">Estimated Time of Arrival</div>
            <div class="row text-center q-col-gutter-md">
                <div class="col-6">
                    <div class="text-caption text-grey">ETA</div>
                    <div>{{ differenceInMinutes(order.waiting_time, order.updated_at) }} minutes</div>
                    <!-- <div>{{ date.formatDate(order.updated_at, 'm') }} </div> -->
                </div>
                <div class="col-6">
                    <div class="text-caption text-grey">Remaining Time</div>
                    <div>{{ differenceInMinutes(order.waiting_time, new Date()) }} minutes</div>
                </div>
            </div>
            <div>
                <q-btn 
                    no-caps
                    :loading="orderForm.processing"
                    :disable="orderForm.processing"
                    @click="readyOrder()"
                    color="primary"
                    rounded 
                    class="full-width q-mt-md"
                    unelevated
                    label="Ready"
                />
            </div>
        </OrderDetails>
    </q-dialog>
    <q-dialog 
        v-model="cancelOrderDialog" 
        transition-show="slide-up"
        transition-hide="slide-down"
    >
        <q-card>
            <q-card-section class="row items-center q-pb-none">
                <q-icon name="warning" color="negative" size="32px" />
                <div class="text-h6 q-ml-md">Cancel Order</div>
                <q-btn round icon="close" v-close-popup flat class="absolute-top-right q-mt-sm q-mr-sm"/>
            </q-card-section>
            <q-card-section>
                <q-item class="bg-negative text-white q-my-md q-pa-md rounded-borders">
                    <q-item-section>
                        <q-item-label class="text-weight-bold text-subtitle1">Are you sure you want to cancel this order? This action cannot be undone.</q-item-label>
                    </q-item-section>
                </q-item>
            </q-card-section>
            <q-card-actions class="justify-end">
                <q-btn no-caps v-close-popup label="No" flat/>
                <q-btn 
                    no-caps
                    :loading="orderForm.processing"
                    :disable="orderForm.processing"
                    @click="cancelOrder()"
                    rounded 
                    unelevated
                    label="Yes"
                    color="negative"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>