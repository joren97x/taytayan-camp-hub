<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { useQuasar, date } from 'quasar'
import { ref } from 'vue'
import FoodCardItem from './FoodCardItem.vue'

const props = defineProps({ order: Object })
const $q = useQuasar()
const viewOrderDialog = ref(false)
const rateDialog = ref(false)
const completeOrderForm = useForm({})
const step = ref(1)

const completeOrder = () => {
    completeOrderForm.patch(route('customer.orders.update', props.order.id), {
        onSuccess: () => {
            rateDialog.value = true
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


var newStep
if(props.order.mode == 'pickup') {
    newStep = pickupSteps.findIndex((s) => props.order.status == s.name)
}
else {
    newStep = deliverySteps.findIndex((s) => props.order.status == s.name)
}
step.value = newStep 
console.log(newStep)
// const date1 = new Date(2017, 4, 12) April 12 2017
// const date2 = new Date(2017, 3, 8)  March 8 2017
// const unit = 'days'

// const diff = date.getDateDiff(date1, date2, unit)
// `diff` is 34 (days)
</script>
<template>
    <div>
        <q-item>
            <q-item-section avatar>
                <q-img height="100px" width="100px" src="https://buffer.com/library/content/images/size/w1200/2023/10/free-images.jpg"></q-img>
            </q-item-section>
            <q-item-section>
                <br>
                <span class="text-red">if hurot na ang waiting time(negative) unsay mahitabo</span>
                waiting time {{ date.getDateDiff(order.waiting_time, Date.now(), 'minutes') }} minutes
                <q-item-label>{{ order.created_at }}</q-item-label>
                <q-item-label caption>{{ order.cart_products.length }} items</q-item-label>
                <q-item-label caption>{{ order.created_at }} - {{ order.status }}</q-item-label>
            </q-item-section>
            <q-item-section side>
                <Link :href="route('product.checkout', {
                    cart_id: order.cart_id
                })">
                    <q-btn no-caps>Reorder</q-btn>
                </Link>
                <q-btn no-caps @click="viewOrderDialog = true">View Order</q-btn>
                <q-btn 
                    v-if="order.status == 'ready_for_pickup' || order.status == 'delivered'"
                    no-caps 
                    @click="completeOrder()"
                    :loading="completeOrderForm.processing"
                    :disable="completeOrderForm.processing"
                >
                    Complete Order
                </q-btn>
            </q-item-section>
        </q-item>
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
            full-width
        >   
            <q-card>
                <q-card-section>
                    <p class="text-h6">Order Details</p>
                    <q-btn 
                        icon="close" 
                        v-close-popup
                        round
                        class="absolute-top-right q-mt-sm q-mr-sm"
                        unelevated
                    />
                    <div class="text-red">ang cancelled status nalang kulang dong</div>
                    
                    <q-stepper
                        v-model="step"
                        ref="stepper"
                        :contracted="$q.screen.lt.md"
                        color="primary"
                        animated
                        alternative-labels
                    >
                        <q-step
                            :name="index"
                            :title="s.title"
                            icon="settings"
                            v-for="(s, index) in pickupSteps"
                            :done="step > index || order.status == 'completed'"
                            v-if="order.mode == 'pickup'"
                        />
                        <q-step
                            :name="index"
                            :title="s.title"
                            icon="settings"
                            v-for="(s, index) in deliverySteps"
                            :done="step > index || order.status == 'completed'"
                            v-else
                        />

                        <template v-slot:message v-if="$q.screen.lt.md">
                            <q-banner class="bg-primary text-white q-px-lg text-capitalize text-center" dense>
                                {{ order.status }}
                            </q-banner>
                        </template>
                    </q-stepper>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8" v-for="product in order.cart_products">
                            <FoodCardItem :item="product" />
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4"> 
                            Subtotal: {{ order.subtotal }}
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
        >
            <q-card>
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
                    <q-rating size="xl" />
                    <q-input type="textarea" filled label="Write your review here..."/>
                </q-card-section>
                <q-card-actions>
                    <q-btn 
                        class="full-width" 
                        color="primary"
                    >
                        Submit
                    </q-btn>
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>