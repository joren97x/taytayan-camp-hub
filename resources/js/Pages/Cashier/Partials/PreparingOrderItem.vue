<script setup>

import { ref, defineEmits } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import { useQuasar, date } from 'quasar'
import { formatDistance } from 'date-fns'
import OrderedItems from '@/Components/OrderedItems.vue'

const props = defineProps({
    order: Object,
    order_statuses: Object
})

// const orderStore = useOrderStore()
const emit = defineEmits(['order_updated'])
const dialog = ref(false)
const orderReadyDialog = ref(false)
const cancelOrderDialog = ref(false)
const readyOrderDialog = ref(false)
const $q = useQuasar()

const acceptOrderForm = useForm({
    waiting_time: 15,
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

// function acceptOrder() {
//     acceptOrderForm.patch(route('cashier.order.update_status', props.order.id), {
//         onSuccess: () => {
//             dialog.value = false
//             $q.notify('Order Accepted')
//         }
//     })
// }

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
        :maximized="$q.screen.lt.md"
    >
        <q-card :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
            <q-card-actions class="justify-center">
                <div class="text-h6 text-center">Order Details</div>
                <q-btn icon="close" class="absolute-top-right q-mr-sm q-mt-xs" round v-close-popup flat/>
            </q-card-actions>
            <q-card-section class="row q-col-gutter-md">
                <div class="col-8 col-md-8 col-lg-8 col-xl-8 col-xs-12 col-sm-12">
                    <!-- <div class="justify-between row col-12">
                        <div class="col"> -->
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
                            <!-- {{ order.user.first_name + ' ' + order.user.last_name }}
                            <div class="text-caption">{{ date.getDateDiff(new Date(), order.created_at, 'minutes') }} minutes ago</div> -->
                        <!-- </div>
                        <div class="col text-right">
                            <q-chip size="md" class="q-px-lg text-capitalize q-my-md">{{ order.mode }}</q-chip>
                            <Link :href="route('conversations.chat_user', order.user.id)">
                                <q-btn icon="message" color="primary" round />
                            </Link>
                        </div>
                    </div> -->
                    <q-separator class="q-my-md" />
                    <!-- <q-list> -->
                        <div class="text-h6">
                            Order - {{ order.cart_products.length }} items
                        </div>
                        <OrderedItems :subtotal="order.subtotal" :cart_products="order.cart_products" />  
                </div>
                <div class="col-4 col-md-4 col-lg-4 col-xl-4 col-xs-12 col-sm-12 " v-if="order.status == 'pending'">
                    <div class="text-h6 text-center">Waiting Time</div>
                    <q-input
                        outlined 
                        rounded
                        v-model="acceptOrderForm.waiting_time"
                        label="Waiting Time In Minutes"
                        unmasked-value
                        :error="acceptOrderForm.errors.waiting_time ? true : false"
                        :error-message="acceptOrderForm.errors.waiting_time"
                    />
                    <div class="">
                        <q-btn 
                            class="full-width" 
                            color="primary" no-caps
                            :loading="acceptOrderForm.processing"
                            :disable="acceptOrderForm.processing"
                            @click="acceptOrder()"
                            rounded 
                            unelevated
                        >
                            Accept Order
                        </q-btn>
                        <q-btn 
                            class="full-width q-my-sm" 
                            color="red" 
                            outline 
                            rounded
                            no-caps
                            @click="cancelOrderDialog = true"
                        >
                            Cancel Order
                        </q-btn>
                    </div>
                </div>
            </q-card-section>
           
        </q-card>
    </q-dialog>
    <q-dialog 
        v-model="readyOrderDialog"
        transition-show="slide-up"
        transition-hide="slide-down"
        :maximized="$q.screen.lt.md"
    >
        <q-card :style="$q.screen.gt.sm ? 'max-width: 50vw; width: 100%;' : ''">
            <q-card-actions class="justify-center">
                <div class="text-h6 text-center">Order Details</div>
                <q-btn icon="close" class="absolute-top-right q-mr-sm q-mt-xs" round v-close-popup flat/>
            </q-card-actions>
            <q-card-section>
                <div class="justify-between row col-12">
                    <div class="col text-h6">
                        {{ order.user.first_name + ' ' + order.user.last_name }}
                    <div class="text-caption">{{ formatDistance(new Date(), order.created_at) }} ago</div>
                    </div>
                    <div class="col text-right">
                        <q-chip>{{ order.mode }}</q-chip>
                        <Link :href="route('conversations.chat_user', order.user.id)">
                            <q-btn icon="message" color="primary" round />
                        </Link>
                    </div>
                </div>
                <q-separator class="q-my-md" />
                <div class="text-h6">
                    Order - {{ order.cart_products.length }} items
                </div>
                <OrderedItems :subtotal="order.subtotal" :cart_products="order.cart_products" /> 
            </q-card-section>
            <q-card-actions class="justify-end">
                <q-btn no-caps v-close-popup flat>No</q-btn>
                <q-btn 
                    no-caps
                    :loading="orderForm.processing"
                    :disable="orderForm.processing"
                    @click="readyOrder()"
                    color="primary"
                    rounded 
                    unelevated
                >
                    Ready
                </q-btn>
            </q-card-actions>
        </q-card>
    </q-dialog>
    <q-dialog v-model="cancelOrderDialog">
        <q-card>
            <q-card-section>
                Cancel Order?
                {{ orderForm }}
            </q-card-section>
            <q-card-actions>
                <q-btn no-caps v-close-popup>No</q-btn>
                <q-btn 
                    no-caps
                    :loading="orderForm.processing"
                    :disable="orderForm.processing"
                    @click="cancelOrder()"
                    rounded 
                    unelevated
                >
                    Yes
                </q-btn>
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>