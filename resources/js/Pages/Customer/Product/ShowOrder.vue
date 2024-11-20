<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { useQuasar, date } from 'quasar'
import { onMounted, ref } from 'vue'
import OrderedItems from '@/Components/OrderedItems.vue'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import Profile from '../Profile.vue'
import { formatDistance } from 'date-fns'
// import FoodCardItem from './FoodCardItem.vue'

const props = defineProps({ order: Object })
defineOptions({ layout: CustomerLayout })
const $q = useQuasar()
const completeOrderForm = useForm({})
const step = ref(1)
const completeOrder = () => {
    completeOrderForm.patch(route('customer.orders.update', props.order.id), {
        onSuccess: () => {
            rateDialog.value = true
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


onMounted(() => {
    calculateSteps()
})

function calculateSteps() {
    var newStep
    if(props.order.mode == 'pickup') {
        newStep = pickupSteps.findIndex((s) => props.order.status == s.name)
    }
    else {
        newStep = deliverySteps.findIndex((s) => props.order.status == s.name)
    }
    step.value = newStep 

    // if complete na increment ang step para mo mark as done sa QStepper
    // idk y
    if(props.order.status == 'completed') {
        step.value++
    }
}

const ratingForm = useForm({
    rating: 0,
    review: ''
})
const reorderForm = useForm({
    cart_id: props.order.cart_id,
    cart_products: props.order.cart_products.map(cart_product => cart_product.id)
})

const rateDialog = ref(false)

const submitRatingForm = () => {
    ratingForm.post(route('customer.product_rating.store'), {
        onSuccess: () => {
            rateDialog.value = false,
            $q.notify('Thank you for rating!!')
        }
    })
}

const reorder = () => {
    reorderForm.get(route('customer.checkout'))
}

const cancelOrderDialog = ref(false)
const cancelOrderForm = useForm({ })
function cancelOrder() {
    cancelOrderForm.status = 'cancelled'
    cancelOrderForm.patch(route('customer.orders.cancel', props.order.id), {
        onSuccess: (e) => {
            cancelOrderDialog.value = false
            $q.notify('Order Cancelled')
        }
    })
}

</script>

<template>
    <Profile>
    <q-card flat bordered class="">
        <q-card-section>
            <div class="text-h6 text-weight-medium q-m-md">Order Details</div>
            <q-item @click="viewOrderDialog = true">
                <q-item-section>
                    <q-item-label caption>Date Placed</q-item-label>
                    <q-item-label>{{ date.formatDate(order.created_at, 'ddd, MMM D, YYYY') }}</q-item-label>
                </q-item-section>
                <q-item-section>
                    <q-item-label caption>Order Status</q-item-label>
                    <q-item-label>{{ order.status }}</q-item-label>
                </q-item-section>
                <q-item-section>
                    <q-item-label caption>Payment Method</q-item-label>
                    <q-item-label>{{ order.payment_method }}</q-item-label>
                </q-item-section>
                <q-item-section class="gt-sm">
                    <q-item-label caption>Fulfillment Type</q-item-label>
                    <q-item-label>
                        {{ order.mode }}
                    </q-item-label>
                </q-item-section>
            </q-item>
        </q-card-section>
        <q-card-section class="q-py-none">
            <q-stepper
                flat
                v-model="step"
                ref="stepper"
                color="primary"
                animated
                alternative-labels
                active-icon="hourglass_top"
                class="q-pa-none gt-sm q-ma-none"
                v-if="order.status != 'cancelled' && order.status != 'completed'"
            >
                <q-step
                    :name="index"
                    :title="order.status == 'cancelled' ? 'Cancelled' : s.title"
                    :icon="s.icon"
                    :error="order.status == 'cancelled'"
                    v-for="(s, index) in pickupSteps"
                    :done="step > index || order.status == 'completed'"
                    v-if="order.mode == 'pickup'"
                    class="q-pa-none q-ma-none"
                />
                <q-step
                    :name="index"
                    :title="order.status == 'cancelled' ? 'Cancelled' : s.title"
                    :icon="s.icon"
                    :error="order.status == 'cancelled'"
                    v-for="(s, index) in deliverySteps"
                    :done="step > index || order.status == 'completed'"
                    class="q-pa-none q-ma-none"
                    v-else
                />
            </q-stepper>
            <div class="row q-mb-md">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <OrderedItems 
                        :subtotal="order.subtotal" 
                        :cart_products="order.cart_products" 
                        :delivery_fee="order.delivery_fee" 
                    />                        
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4"> 
                    <q-item class="bg-grey-3 q-my-md" v-if="order.driver && order.status != 'completed'">
                        <q-item-section avatar>
                            <q-avatar color="secondary">
                                <q-img :src="`/storage/${order.driver.profile_pic}`" fit="cover" class="fit" v-if="order.driver.profile_pic"/>
                                <div v-else>{{ order.driver.first_name[0] }}</div>
                            </q-avatar>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>{{ order.driver.first_name + ' ' + order.driver.last_name }}</q-item-label>
                            <q-item-label caption>Delivery Rider</q-item-label>
                        </q-item-section>
                        <q-item-section side top>
                            <Link :href="route('conversations.show', order.conversation_id)">
                                <q-btn class="full-width q-mt-sm" no-caps color="primary" icon="chat" round/>
                            </Link>
                        </q-item-section>
                    </q-item>
                    
                </div>
            </div>
        </q-card-section>
        <q-card-actions class="justify-end q-mt-none q-pt-none">
            <q-btn 
                class="q-px-md" 
                @click="reorder()" 
                label="Reorder" 
                color="primary" 
                no-caps 
                unelevated 
                size="md"
                rounded
                v-if="order.status == 'completed' || order.status == 'cancelled'" 
            />
            <q-btn 
                class="" 
                color="red" 
                outline 
                rounded
                no-caps
                @click="cancelOrderDialog = true"
                label="Cancel Order"
                v-if="order.status == 'pending'"
            />
            <q-btn 
                v-if="order.status == 'delivered' || order.status == 'ready_for_pickup'"
                no-caps 
                class=""
                @click="completeOrder()"
                :loading="completeOrderForm.processing"
                :disable="completeOrderForm.processing"
                color="primary"
                rounded
                label="Complete Order"
            />
        </q-card-actions>
    </q-card>
    <q-dialog 
        v-model="rateDialog" 
        transition-show="slide-up"
        transition-hide="slide-down"
        :maximized="$q.screen.lt.md"
        persistent
    >
        <q-card>
            <q-form @submit="submitRatingForm()">
                <q-card-section>
                    <q-btn 
                        icon="close" 
                        class="absolute-top-right q-mr-sm q-mt-sm" 
                        round 
                        unelevated 
                        v-close-popup
                    />
                    <div class="text-h6">Rate</div>
                    <div class="text-subtitle1">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Voluptatum, nesciunt?</div>
                    <q-rating 
                        size="xl" 
                        v-model="ratingForm.rating" 
                    />
                    <div class="text-red" v-if="ratingForm.errors.rating ? true : false">
                        {{ ratingForm.errors.rating }}
                    </div>
                    <q-input 
                        type="textarea" 
                        v-model="ratingForm.review" 
                        filled 
                        label="Write your review here..."
                    />
            </q-card-section>
                <q-card-actions>
                    <q-btn 
                        class="full-width" 
                        color="primary"
                        :loading="ratingForm.processing"
                        :disable="ratingForm.processing"
                        type="submit"
                    >
                        Submit
                    </q-btn>
                </q-card-actions>
            </q-form>
        </q-card>
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
                    :loading="cancelOrderForm.processing"
                    :disable="cancelOrderForm.processing"
                    @click="cancelOrder()"
                    rounded 
                    unelevated
                    label="Yes"
                    color="negative"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</Profile>
</template>