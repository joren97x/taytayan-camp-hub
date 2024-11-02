<script setup>

import { ref, onMounted, defineEmits } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import { useQuasar, date } from 'quasar'
import { useOrderStore } from '@/Stores/OrderStore'
import OrderedItems from '@/Components/OrderedItems.vue'

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
            {{ getDeliveryTitle(order.status) }}
            <q-item-label caption lines="2">{{ order.cart_products.length }} items</q-item-label>
        </q-item-section>
        <q-item-section v-if="order_statuses.ready_for_pickup == order.status" top side>
            <div class="text-caption">{{ date.getDateDiff(new Date(), order.created_at, 'minutes') }} minutes ago</div>
            <q-btn no-caps color="primary" @click.stop="isOrderCompleteDialog = true" rounded unelevated>Complete</q-btn>
        </q-item-section>
        <q-item-section side top v-else>
            <div class="text-caption">{{ date.getDateDiff(new Date(), order.created_at, 'minutes') }} minutes ago</div>
        </q-item-section>
    </q-item>

    <q-dialog 
        v-model="dialog" 
        :maximized="$q.screen.lt.md"
        transition-show="slide-up"
        transition-hide="slide-down"
    >
        <q-card :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
            <q-card-actions class="justify-center">
                <div class="text-h6 text-center">Order Details</div>
                <q-btn icon="close" class="absolute-top-right q-mr-sm q-mt-xs" round v-close-popup flat/>
            </q-card-actions>
            <q-card-section>
                <!-- <q-item>
                    <q-item-section>
                        <q-item-label class="text-h6">Order Details</q-item-label>
                        <q-item-label caption> {{ order.created_at }}
                        </q-item-label>
                    </q-item-section>
                    <q-item-section side>
                        <q-btn round icon="close" unelevated v-close-popup></q-btn>
                    </q-item-section>
                </q-item> -->
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
                <div :class="['row', $q.screen.gt.sm ? 'q-col-gutter-xl' : '']">
                    <div class="col-8 col-md-8 col-lg-8 col-xl-8 col-xs-12 col-sm-12">
                        <div class="text-h6">Customer</div>
                        <q-item class="q-py-none">
                        <q-item-section avatar>
                            <q-avatar size="100px" class="text-white" color="primary">
                                <q-img :src="`/storage/${order.user.profile_pic}`" v-if="order.user.profile_pic"/>
                                <div v-else>
                                    {{ order.user.first_name[0] }}
                                </div>
                            </q-avatar>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label class="text-weight-bold">{{ order.user.first_name + ' ' + order.user.last_name }}</q-item-label>
                            <q-item-label> <q-icon name="phone"/> {{ order.user.phone_number }}</q-item-label>
                            <q-item-label> <q-icon name="location_on"/> {{ order.user.address }}</q-item-label>
                        </q-item-section>
                        <q-item-section side>
                            <Link :href="route('conversations.chat_user', order.user.id)">
                                <q-btn round icon="message" unelevated color="primary" />
                            </Link>
                        </q-item-section>
                    </q-item>
                        <q-separator class="q-my-md" />
                        <div class="text-h6">
                            Order - {{ order.cart_products.length }} items
                        </div>
                        <OrderedItems :subtotal="order.subtotal" :cart_products="order.cart_products" /> 
                    </div>
                    <div class="col-4 col-md-4 col-lg-4 col-xl-4 col-xs-12 col-sm-12">
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
                        
                    </div>
                </div>
            </q-card-section>
           
        </q-card>
    </q-dialog>

    <q-dialog 
        v-model="isOrderCompleteDialog"
        :maximized="$q.screen.lt.md"
        transition-show="slide-up"
        transition-hide="slide-down"
    >
        <q-card :style="$q.screen.gt.sm ? 'max-width: 50vw; width: 100%;' : ''">
            <q-card-actions class="justify-center">
                <div class="text-h6 text-center">Order Details</div>
                <q-btn icon="close" class="absolute-top-right q-mr-sm q-mt-xs" round v-close-popup flat/>
            </q-card-actions>
            <q-card-section>
                <!-- <div class="justify-between row col-12"> -->
                    <!-- <div class="col text-h6">
                        {{ order.user.first_name + ' ' + order.user.last_name }}
                        <div class="text-caption">{{ date.getDateDiff(new Date(), order.created_at, 'minutes') }} minutes ago</div>
                    </div>
                    <div class="col text-right">
                        <q-chip>{{ order.cart_products.length }} items</q-chip>
                        <q-btn round icon="message" />
                    </div> -->
                    <div class="text-h6">Customer</div>
                    <q-item class="q-py-none">
                        <q-item-section avatar>
                            <q-avatar size="100px" square>
                                <q-img :src="`/storage/${order.user.profile_pic}`"/>
                            </q-avatar>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label class="text-weight-bold">{{ order.user.first_name + ' ' + order.user.last_name }}</q-item-label>
                            <q-item-label> <q-icon name="phone"/> {{ order.user.phone_number }}</q-item-label>
                            <q-item-label> <q-icon name="location_on"/> {{ order.user.address }}</q-item-label>
                        </q-item-section>
                        <q-item-section side>
                            <Link :href="route('conversations.chat_user', order.user.id)">
                                <q-btn round icon="message" unelevated color="primary" />
                            </Link>
                        </q-item-section>
                    </q-item>
                <!-- </div> -->
                <q-separator class="q-my-md" />
                <!-- <q-item>
                    <q-item-section>
                        <q-item-label class="text-h6">
                            {{ order.cart_products.length }} items
                            <q-chip :class="$q.dark.isActive ? 'bg-grey-9' : ''">
                                {{ order.mode }}
                            </q-chip>
                        </q-item-label>
                    </q-item-section>
                </q-item> -->
                
                <div class="text-h6">
                    Order - {{ order.cart_products.length }} items
                </div>
                <OrderedItems :subtotal="order.subtotal" :cart_products="order.cart_products" />                        
            </q-card-section>
            <q-card-actions class="justify-end">
                <q-btn no-caps v-close-popup rounded outline color="primary">Cancel</q-btn>
                <q-btn 
                    no-caps
                    :loading="form.processing"
                    :disable="form.processing"
                    @click="completeOrder()"
                    color="primary"
                    rounded 
                    unelevated
                    label="Complete"    
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
    
</template>