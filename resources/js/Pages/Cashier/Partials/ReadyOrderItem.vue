<script setup>

import { ref, onMounted, defineEmits } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import { useQuasar, date } from 'quasar'
import { useOrderStore } from '@/Stores/OrderStore'
import OrderedItems from '@/Components/OrderedItems.vue'
import OrderDetails from '@/Components/OrderDetails.vue'
import { formatDistance, differenceInMinutes } from 'date-fns'

const props = defineProps({
    order: Object,
    order_statuses: Object
})

const orderStore = useOrderStore()
const emit = defineEmits(['order_updated'])
const dialog = ref(false)
const isOrderCompleteDialog = ref(false)
const $q = useQuasar()

const form = useForm({
    status: props.order_statuses.completed
})

function completeOrder() {
    form.patch(route('cashier.orders.update_status', props.order.id), {
        onSuccess: () => {
            emit('order_updated')
            orderStore.getOrders()
            console.log('go fetch')
            isOrderCompleteDialog.value = false
            $q.notify('Order marked as completed')
        }
    })
}


const pickupSteps = [
    {
        name: 'pending',
        title: 'Pending',
        icon: 'close'
    },
    {
        name: 'preparing',
        title: 'Preparing',
        icon: 'close'
    },
    {
        name: 'ready_for_pickup',
        title: 'Ready For Pickup',
        icon: 'close'
    },
    {
        name: 'completed',
        title: 'Complete',
        icon: 'close'
    },
]

// const isDone = (index, status) => {
//     return index <= step && 
// }

const deliverySteps = [
    {
        name: 'pending',
        title: 'Pending',
        icon: 'close'
    },
    {
        name: 'preparing',
        title: 'Preparing',
        icon: 'close'
    },
    {
        name: 'ready_for_delivery',
        title: 'Ready For Delivery',
        icon: 'close'
    },
    {
        name: 'delivering',
        title: 'Delivering',
        icon: 'close'
    },
    {
        name: 'delivered',
        title: 'Delivered',
        icon: 'close'
    },
    {
        name: 'completed',
        title: 'Complete',
        icon: 'close'
    }
]

const getDeliveryTitle = (status) => {
    const step = deliverySteps.find(step => step.name === status);
    return step ? step.title : 'Ready For Pickup';
}

const order = ref(props.order)
const step = ref(1)

onMounted(() => {
    calculateSteps()
})

function calculateSteps() {
    var newStep
    if(order.value.mode == 'pickup') {
        newStep = pickupSteps.findIndex((s) => order.value.status == s.name)
    }
    else {
        newStep = deliverySteps.findIndex((s) => order.value.status == s.name)
    }
    step.value = newStep 

    // if complete na increment ang step para mo mark as done sa QStepper
    // idk y
    if(order.value.status == 'completed') {
        step.value++
    }
}

</script>

<template>
    <q-item :class="[order.status == 'pending' ? 'bg-blue-2' : 'bg-grey-4', 'q-my-sm rounded-borders']" clickable v-ripple @click="dialog = true">
        <q-item-section>
            <q-item-label>{{ order.user.first_name + ' ' + order.user.last_name }}</q-item-label>
            <!-- {{ getDeliveryTitle(order.status) }} -->
            <q-item-label caption lines="2">{{ order.cart_products.length }} items</q-item-label>
        </q-item-section>
        <q-item-section v-if="order_statuses.ready_for_pickup == order.status" top side>
            <div class="text-caption">{{ formatDistance(new Date(), order.created_at) }} ago</div>
            <q-btn no-caps color="primary" @click.stop="isOrderCompleteDialog = true" rounded unelevated>Complete</q-btn>
        </q-item-section>
        <q-item-section side top v-else>
            <div class="text-caption">{{ formatDistance(new Date(), order.created_at) }} ago</div>
        </q-item-section>
    </q-item>

    <q-dialog 
        v-model="dialog" 
        :maximized="$q.screen.lt.md"
        transition-show="slide-up"
        transition-hide="slide-down"
    >
        <OrderDetails :order="order">
            <div v-if="order.driver">
                <div class="text-h6">Driver</div>
                <q-item clickable>
                    <q-item-section avatar>
                        <q-avatar>
                            <img src="https://variety.com/wp-content/uploads/2021/04/Avatar.jpg?w=800&h=533&crop=1"/>
                        </q-avatar>
                    </q-item-section>
                    <q-item-section>
                        <q-item-label>{{ order.driver.first_name + ' ' + order.driver.last_name }}</q-item-label>
                        <q-item-label caption>{{ order.driver.phone_number }}</q-item-label>
                    </q-item-section>
                    <q-item-section side>
                        <Link :href="route('conversations.chat_user', order.user.id)">
                            <q-btn icon="message" color="primary" round />
                        </Link>
                    </q-item-section>
                </q-item>
            </div>
            <div v-else class="justify-center items-center flex q-pa-xl">
                <div v-if="order.mode == 'delivery'">Waiting to be delivered...</div>
                <div v-else>Waiting to be picked up...</div>
            </div>
        </OrderDetails>
    </q-dialog>

    <q-dialog 
        v-model="isOrderCompleteDialog"
        :maximized="$q.screen.lt.md"
        transition-show="slide-up"
        transition-hide="slide-down"
    >
        <OrderDetails :order="order">
            <div>
                <q-btn 
                    no-caps
                    :loading="form.processing"
                    :disable="form.processing"
                    @click="completeOrder()"
                    color="primary"
                    rounded 
                    class="full-width q-mt-md"
                    unelevated
                    label="Complete"    
                />
            </div>
        </OrderDetails>
    </q-dialog>
    
</template>