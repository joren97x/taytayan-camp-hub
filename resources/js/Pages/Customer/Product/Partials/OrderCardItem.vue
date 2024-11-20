<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { useQuasar, date } from 'quasar'
import { onMounted, ref } from 'vue'
import OrderedItems from '@/Components/OrderedItems.vue'
import { formatDistance } from 'date-fns'
// import FoodCardItem from './FoodCardItem.vue'

const props = defineProps({ order: Object })
const $q = useQuasar()
const viewOrderDialog = ref(false)
const rateDialog = ref(false)
const completeOrderForm = useForm({})
const reorderForm = useForm({
    cart_id: props.order.cart_id,
    cart_products: props.order.cart_products.map(cart_product => cart_product.id)
})
const ratingForm = useForm({
    rating: 0,
    review: ''
})
const step = ref(1)
const order = ref(props.order)

const completeOrder = () => {
    completeOrderForm.patch(route('customer.orders.update', order.value.id), {
        onSuccess: () => {
            $q.notify('hi')
            rateDialog.value = true
            $q.notify('hi')
            console.log(rateDialog.value)
        }
    })
}

const submitRatingForm = () => {
    ratingForm.post(route('customer.product_rating.store'), {
        onSuccess: () => {
            rateDialog.value = false
            $q.notify('Thank you for rating!!')
        }
    })
}
// so i want to get the index of the step based from the order,status
// and then set the index to the step



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

Echo.private(`orders.${order.value.id}`)
    .listen('Product\\OrderStatusUpdated', (data) => {
        $q.notify('new order arrived')
        console.log(data.order)
        order.value.status = data.order.status
        console.log(order.value)
        calculateSteps()
        // axios.get(route('cashier.orders.show', data.order.id))
        // .then((orderData) => {
        //     $q.notify('fetched and ykwis bruh')
        //     console.log(orderData)
        //     orders.value.push(orderData.data)
        // })
        // .catch((err) => {
        //     console.error(err)
        // })
    })

const reorder = () => {
    reorderForm.get(route('customer.checkout'))
}
// const date1 = new Date(2017, 4, 12) April 12 2017
// const date2 = new Date(2017, 3, 8)  March 8 2017
// const unit = 'days'

// const diff = date.getDateDiff(date1, date2, unit)
// `diff` is 34 (days)
const getStatusColor = (status) => {
    switch (status) {
        case 'pending':
            return 'orange';
        case 'preparing':
            return 'blue';
        case 'ready_for_delivery':
            return 'purple';
        case 'ready_for_pickup':
            return 'teal';
        case 'delivering':
            return 'indigo';
        case 'delivered':
            return 'green';
        case 'completed':
            return 'light-green';
        case 'cancelled':
            return 'red';
        default:
            return 'grey';
    }
}
</script>
<template>
    <q-card bordered flat class="q-my-sm">
        <q-item @click="viewOrderDialog = true">
            <q-item-section>
                <q-item-label caption>Date Placed</q-item-label>
                <q-item-label>{{ date.formatDate(order.created_at, 'ddd, MMM D, YYYY') }}</q-item-label>
            </q-item-section>
            <q-item-section class="gt-sm">
                <q-item-label caption>Fullfillment Type</q-item-label>
                <q-item-label>
                    {{ order.mode }}
                </q-item-label>
            </q-item-section>
            <q-item-section>
                <q-item-label caption>Order Status</q-item-label>
                <q-item-label>{{ order.status }}</q-item-label>
            </q-item-section>
            <q-item-section side>
                <div class="button-group gt-sm">
                    <Link :href="route('customer.orders.show', order.id)">
                        <q-btn label="View Order" no-caps color="primary" outline rounded />
                    </Link>
                    <q-btn 
                        @click="reorder()" 
                        label="Reorder" 
                        color="primary" 
                        no-caps 
                        unelevated 
                        rounded
                        v-if="order.status == 'completed' || order.status == 'cancelled'" 
                    />
                    
                    <q-btn 
                        v-if="order.status == 'delivered' || order.status == 'ready_for_pickup'"
                        no-caps 
                        @click="completeOrder()"
                        :loading="completeOrderForm.processing"
                        :disable="completeOrderForm.processing"
                        color="primary"
                        rounded
                        label="Complete Order"
                    />
                </div>
                <q-btn icon="more_horiz" round flat class="lt-md"></q-btn>
            </q-item-section>
        </q-item>
        <q-separator class="q-my-xs"/>
            <OrderedItems 
                :subtotal="order.subtotal" 
                :cart_products="order.cart_products" 
                :delivery_fee="order.delivery_fee" 
                :show_items_label="false"
            />   
        <!-- <q-item clickable @click="viewOrderDialog = true">
            <q-item-section>
                <q-item-label class="text-subtitle1 text-primary">
                    Order #{{ order.id }}
                </q-item-label>
                <q-item-label>
                    {{ order.cart_products.length }} items
                </q-item-label>
            </q-item-section>
            <q-item-section side>
                <q-badge :color="getStatusColor(order.status)" class="text-capitalize">
                    {{ order.status }}
                </q-badge>
                ₱{{ parseFloat(order.total).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
            </q-item-section>
        </q-item> -->
            <!-- <q-btn rounded no-caps label="View Details" class="absolute-bottom-right q-mb-sm q-mr-sm" icon="visibility" unelevated color="primary" /> -->
    </q-card>
        <!-- <div class="row">
            <div class="col-4">
                <div style="height: 150px; width: 100%;" class="bg-grey"></div>
            </div>
            <div class="col-8">
                {{ order }}
                {{ order }}
            </div>
        </div> -->
    <q-dialog 
        v-model="viewOrderDialog" 
        :maximized="$q.screen.lt.md"  
        transition-show="slide-up"
        transition-hide="slide-down"
    >   
        <q-card :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
            <q-card-actions class="justify-between">
                <div class="text-h6">Order #{{ order.id }}</div>
                <q-btn  
                    icon="close" 
                    v-close-popup
                    round
                    class="absolute-top-right q-mr-sm q-mt-sm"
                    unelevated
                />
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
                <div class="row q-mb-md">
                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
                        <OrderedItems 
                            :subtotal="order.subtotal" 
                            :cart_products="order.cart_products" 
                            :delivery_fee="order.delivery_fee" 
                        />                        
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4"> 
                        <div class="text-subtitle1 text-weight-medium q-m-md text-center">Order Information</div>
                        <div class="row text-center q-col-gutter-md">
                            <div class="col-6">
                                <div class="text-caption text-grey">Ordered At</div>
                                <div>{{ formatDistance(new Date(), order.created_at) }} ago</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption text-grey">Payment Method</div>
                                <div>{{ order.payment_method }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption text-grey">Fulfillment Type</div>
                                <div>{{ order.mode }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-caption text-grey">Status</div>
                                <div>{{ order.status }}</div>
                            </div>
                        </div>
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
                        <q-btn 
                            class="full-width q-mt-md" 
                            @click="reorder()" 
                            label="Reorder" 
                            color="primary" 
                            no-caps 
                            unelevated 
                            rounded
                            v-if="order.status == 'completed' || order.status == 'cancelled'" 
                        />
                        
                        <q-btn 
                            v-if="order.status == 'delivered' || order.status == 'ready_for_pickup'"
                            no-caps 
                            class="q-mt-sm full-width"
                            @click="completeOrder()"
                            :loading="completeOrderForm.processing"
                            :disable="completeOrderForm.processing"
                            color="primary"
                            rounded
                            label="Complete Order"
                        />
                        <!-- <Link :href="route('customer.inbox', 3)">
                            <q-btn no-caps>Message Driver</q-btn>
                        </Link> -->
                    </div>
                </div>
            </q-card-section>
        </q-card>
    </q-dialog>
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
</template>

<style scoped>
.button-group {
    display: flex;
    flex-direction: row;
    gap: 4px; /* Adds spacing between buttons */
}
</style>