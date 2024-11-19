<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { useQuasar, date } from 'quasar'
import { onMounted, ref } from 'vue'
import OrderedItems from '@/Components/OrderedItems.vue'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import Profile from '../Profile.vue'
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
const rateDialog = ref(false)
const submitRatingForm = () => {
    ratingForm.post(route('customer.product_rating.store'), {
        onSuccess: () => {
            rateDialog.value = false,
            $q.notify('Thank you for rating!!')
        }
    })
}

</script>

<template>
    <Profile>
    <q-card :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''" flat class="q-my-md">
        <q-card-actions class="justify-center">
            <div class="text-h6">Order Details</div>
            <q-chip class="absolute-top-right q-mt-md q-mr-md">
                {{ order.status }}
            </q-chip>
        </q-card-actions>
        <q-card-section class="q-py-none">
            <q-stepper
                flat
                v-model="step"
                ref="stepper"
                color="primary"
                animated
                alternative-labels
                active-icon="hourglass_top"
                :vertical="$q.screen.lt.md"
                class="q-pa-none"
            >
                <div class="text-h6">Status</div>
                <q-step
                    :name="index"
                    :title="order.status == 'cancelled' ? 'Cancelled' : s.title"
                    :icon="s.icon"
                    :error="order.status == 'cancelled'"
                    v-for="(s, index) in pickupSteps"
                    :done="step > index || order.status == 'completed'"
                    v-if="order.mode == 'pickup'"
                />
                <q-step
                    :name="index"
                    :title="order.status == 'cancelled' ? 'Cancelled' : s.title"
                    :icon="s.icon"
                    :error="order.status == 'cancelled'"
                    v-for="(s, index) in deliverySteps"
                    :done="step > index || order.status == 'completed'"
                    v-else
                />
            </q-stepper>
            <div class="text-h6">Items</div>
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
                    <OrderedItems :subtotal="order.subtotal" :cart_products="order.cart_products" />                        
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4"> 
                    <div style="height: 250px; position: relative" class="full-width bg-grey-3" v-if="order.driver">
                        <q-item class="bg-grey">
                            <q-item-section avatar>
                                <q-avatar color="secondary">
                                    <q-img :src="`/storage/${order.driver.profile_pic}`" fit="cover" class="fit" v-if="order.driver.profile_pic"/>
                                    <div v-else>{{ order.driver.first_name[0] }}</div>
                                </q-avatar>
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>{{ order.driver.first_name + ' ' + order.driver.last_name }}</q-item-label>
                                <q-item-label label>{{ order.driver.phone_number }}</q-item-label>
                            </q-item-section>
                        </q-item>
                    </div>
                    <Link :href="route('product.checkout', { cart_id: order.cart_id })">
                        <q-btn class="full-width q-mt-sm" label="Reorder" rounded color="primary" no-caps unelevated />
                    </Link>
                    <Link :href="route('conversations.show', order.conversation_id)" v-if="order.driver && order.status != 'completed'">
                        <q-btn class="full-width q-mt-sm" no-caps label="Message Driver"/>
                    </Link>
                    <q-btn 
                        v-if="order.status == 'delivered' || order.status == 'ready_for_pickup'"
                        no-caps 
                        class="q-mt-sm full-width"
                        @click="completeOrder()"
                        :loading="completeOrderForm.processing"
                        :disable="completeOrderForm.processing"
                        rounded
                        label="Complete Order"
                        color="primary"
                    />
                    <!-- <Link :href="route('customer.inbox', 3)">
                        <q-btn no-caps>Message Driver</q-btn>
                    </Link> -->
                </div>
            </div>
        </q-card-section>
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
</Profile>
</template>