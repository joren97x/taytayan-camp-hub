<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { useQuasar, date } from 'quasar'
import { onMounted, ref } from 'vue'
// import FoodCardItem from './FoodCardItem.vue'

const props = defineProps({ order: Object })
const $q = useQuasar()
const viewOrderDialog = ref(false)
const rateDialog = ref(false)
const completeOrderForm = useForm({})
const ratingForm = useForm({
    rating: 0,
    review: ''
})
const step = ref(1)
const order = ref(props.order)

const completeOrder = () => {
    completeOrderForm.patch(route('customer.orders.update', order.value.id), {
        onSuccess: () => {
            rateDialog.value = true
        }
    })
}

const submitRatingForm = () => {
    ratingForm.post(route('customer.product_rating.store'), {
        onSuccess: () => {
            rateDialog.value = false
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
        order.value = data.order
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
// const date1 = new Date(2017, 4, 12) April 12 2017
// const date2 = new Date(2017, 3, 8)  March 8 2017
// const unit = 'days'

// const diff = date.getDateDiff(date1, date2, unit)
// `diff` is 34 (days)
</script>
<template>
    <q-card bordered flat class="q-my-sm">
        <q-item clickable  @click="viewOrderDialog = true">
            <q-item-section avatar>
                    <!-- {{ order }} -->
                    <div style="height: 100px; width: 200px;" class="row">
                        <div class="col-6" v-for="cart_product in order.cart_products">
                            <q-img :src="`../storage/${cart_product.product.photo}`" fit="contain" height="50px" width="100px"></q-img>
                        </div>
                    </div>
            </q-item-section>
            <q-item-section top>
                <!-- waiting time {{ date.getDateDiff(order.waiting_time, Date.now(), 'minutes') }} minutes -->
                <div class="ellipsi-2-lines">
                    <!-- <span v-for="(p, index) in order.cart_products">
                        {{ p.product.name }}, 
                    </span> -->
                    {{ date.formatDate(order.created_at, 'MMMM D, YYYY') }}
                </div>
                <q-item-label caption>
                    {{ order.cart_products.length }} items - P{{ order.subtotal }}
                </q-item-label>
                <q-item-label caption>
                    {{ order.mode }}
                </q-item-label>
            </q-item-section>
            <q-item-section side top>
                {{ order.status }}
            </q-item-section>
        </q-item>
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
            <q-card-section>
                <p class="text-h6">Order Details</p>
                <q-separator/>
                <q-btn 
                    icon="close" 
                    v-close-popup
                    round
                    class="absolute-top-right q-mt-sm q-mr-sm"
                    unelevated
                />
                <q-stepper
                    flat
                    v-model="step"
                    ref="stepper"
                    color="primary"
                    animated
                    alternative-labels
                    active-icon="hourglass_top"
                    :vertical="$q.screen.lt.md"
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
                        <!-- <FoodCardItem :item="product" /> -->
                        <q-item v-for="item in order.cart_products">
                            <q-item-section avatar>
                                <q-img 
                                    :src="`/storage/${item.product.photo}`"
                                    height="70px"
                                    width="70px"
                                    fit="contain"
                                    class="q-mx-md"
                                />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>
                                    {{ item.product.name }} ({{ item.product.price }}) - {{ item.quantity }} pcs
                                </q-item-label>
                                <template 
                                    v-for="(modifier, index) in item.grouped_modifiers" 
                                    :key="index"
                                >
                                    <q-item-label caption>{{ modifier.modifier_group.name }}</q-item-label>
                                    <q-item-label 
                                        caption 
                                        v-for="(modifier_item, index) in modifier.modifier_items" 
                                        :key="index"
                                    >
                                        {{ `${modifier_item.quantity} - ${modifier_item.modifier_item.name} (P${modifier_item.total})` }}
                                    </q-item-label>
                                    
                                </template>
                                <q-item-label caption v-if="item.special_instruction">
                                    Note: {{ item.special_instruction }}
                                </q-item-label>
                            </q-item-section>
                            <q-item-section side>
                                P{{ item.total }}
                            </q-item-section>
                        </q-item>
                        <q-separator/>
                        <div class="row">
                            <q-space/>
                            <div class="q-mt-md q-mr-md text-subtitle1">
                                <span class="q-mr-md">Subtotal</span> P{{ order.subtotal }}
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4"> 
                        <div style="height: 250px; position: relative" class="full-width bg-grey-3">
                            <div v-if="order.driver">
                                <!-- {{ order.driver }} -->
                                <q-item class="bg-grey">
                                    <q-item-section avatar>
                                        <q-avatar color="secondary">
                                            <q-img :src="`/storage/${order.driver.profile_pic}`" v-if="order.driver.profile_pic"/>
                                            <div v-else>{{ order.driver.first_name[0] }}</div>
                                        </q-avatar>
                                    </q-item-section>
                                    <q-item-section>
                                        <q-item-label>{{ order.driver.first_name + ' ' + order.driver.last_name }}</q-item-label>
                                        <q-item-label label>{{ order.driver.phone_number }}</q-item-label>
                                    </q-item-section>
                                </q-item>
                                {{ order }}
                            </div>
                        </div>
                        <Link :href="route('product.checkout', { cart_id: order.cart_id })">
                            <q-btn class="full-width q-mt-sm" label="Reorder" color="primary" no-caps unelevated />
                        </Link>
                        <Link :href="route('conversations.show', order.conversation_id)" v-if="order.driver">
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