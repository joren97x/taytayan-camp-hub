<script setup>

import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useQuasar, date } from 'quasar'

const props = defineProps({
    order: Object,
    order_statuses: Object
})

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
            console.log(e)
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
            console.log(e)
            readyOrderDialog.value = false
            $q.notify('Order Accepted')
        }
    })
}

function cancelOrder() {
    orderForm.status = 'cancelled'
    orderForm.patch(route('cashier.orders.update_status', props.order.id), {
        onSuccess: (e) => {
            console.log(e)
            cancelOrderDialog.value = false
            $q.notify('Order Cancelled')
        }
    })
}

</script>

<template>
    <!-- <OrderItem :order="order" @click="dialog = true"> -->
        <q-item :class="[order.status == 'pending' ? 'bg-blue-2' : 'bg-grey-4', 'q-my-sm']" :clickable="order.status == order_statuses.pending" v-ripple @click="dialog = true">
            <q-item-section>
                <q-item-label>{{ order.user.first_name + ' ' + order.user.last_name }}</q-item-label>
                <q-item-label caption lines="2">{{ order.cart_products.length }} items</q-item-label>
            </q-item-section>
            <q-item-section side top v-if="order_statuses.pending == order.status">
                New
                <q-item-label caption>{{ order.created_at }}</q-item-label>
                <!-- <q-btn no-caps color="primary" @click.stop="isOrderReadyDialog = true">
                    View Order
                </q-btn> -->
            </q-item-section>
            <q-item-section side top v-else>
                <q-item-label caption>{{ order.created_at }}</q-item-label>
                <q-btn no-caps color="primary" @click.stop="readyOrderDialog = true">
                    Ready Order
                </q-btn>
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
            <q-card-section class="row q-col-gutter-xl">
                
                <div class="col-8 col-md-8 col-lg-8 col-xl-8 col-xs-12 col-sm-12">
                    <div class="justify-between row col-12">
                        <div class="col text-h6">
                            {{ order.user.first_name + ' ' + order.user.last_name }}
                            <div class="text-caption">{{ date.getDateDiff(new Date(), order.created_at, 'minutes') }} minutes ago</div>
                        </div>
                        <div class="col text-right">
                            <q-chip>{{ order.cart_products.length }} items</q-chip>
                        </div>
                    </div>
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
                    <q-list>
                        <q-item v-for="(cart_product, index) in order.cart_products" :key="index">
                            <q-item-section avatar>
                                <img :src="`/storage/${cart_product.product.photo}`" fill="contain" height="70px" width="70px"/>
                            </q-item-section>
                            <q-item-section>
                                <span>
                                    <q-chip size="sm" :class="$q.dark.isActive ? 'bg-grey-9' : ''">
                                        {{ cart_product.quantity }}
                                    </q-chip>
                                        {{ cart_product.product.name }}
                                </span>
                                <q-item-label v-for="(grouped_modifier, index) in cart_product.grouped_modifiers" :key="index">
                                    {{ grouped_modifier.modifier_group.name }}
                                    <q-item-label caption v-for="modifier in grouped_modifier.modifier_items" :key="modifier.id">
                                        {{ modifier.quantity }} - {{ modifier.modifier_item.name }} etc or should i display the price or total price
                                    </q-item-label>
                                </q-item-label>
                                <q-item-label v-if="cart_product.special_instruction">
                                    Note: {{ cart_product.special_instruction }}
                                </q-item-label>
                            </q-item-section>
                            <q-item-section side>
                                <q-item-label>
                                    {{ cart_product.total }}
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                    </q-list>
                    <q-separator/>
                    <div class="row">
                        <q-space/>
                        <div class="q-mt-md q-mr-md text-subtitle1">
                            <span class="q-mr-md">Subtotal</span> {{ order.subtotal }}
                        </div>
                    </div>
                </div>
                <div class="col-4 col-md-4 col-lg-4 col-xl-4 col-xs-12 col-sm-12" v-if="order.status == 'pending'">
                    <div class="q-pa-md" style="position: relative">
                        <div class="text-h6 text-center q-mt-xl">Waiting Time</div>
                        <q-btn round icon="close" flat class="absolute-top-right"/>
                    </div>
                    <q-input
                        filled
                        v-model="acceptOrderForm.waiting_time"
                        label="Waiting Time In Minutes"
                        unmasked-value
                        :error="acceptOrderForm.errors.waiting_time ? true : false"
                        :error-message="acceptOrderForm.errors.waiting_time"
                    />
                    <div class="text-center text-green">
                        <q-chip size="md" class="q-px-lg text-capitalize q-my-md">{{ order.mode }}</q-chip>
                    </div>
                    <!-- <q-item>
                        <q-item-section>Subtotal</q-item-section>
                        <q-item-section side>
                            {{ order.subtotal }}
                        </q-item-section>
                    </q-item>
                    <q-item>
                        <q-item-section>Delivery fee if kung delivery? or naa ba na?</q-item-section>
                        <q-item-section side>
                            P5.00
                        </q-item-section>
                    </q-item> -->
                    <q-separator/>
                    <!-- <q-item class="text-h6">
                        <q-item-section>Total</q-item-section>
                        <q-item-section side>
                            {{ order.subtotal }}
                        </q-item-section>
                    </q-item> -->
                    <div class="q-mt-md">
                        <q-btn 
                            class="full-width" 
                            color="primary" no-caps
                            :loading="acceptOrderForm.processing"
                            :disable="acceptOrderForm.processing"
                            @click="acceptOrder()"
                        >
                            Accept Order
                        </q-btn>
                        <q-btn 
                            class="full-width q-my-sm" 
                            color="red" 
                            outline 
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
            <q-card-section>
                <div class="justify-between row col-12">
                    <div class="col text-h6">
                        {{ order.user.first_name + ' ' + order.user.last_name }}
                        <div class="text-caption">{{ date.getDateDiff(new Date(), order.created_at, 'minutes') }} minutes ago</div>
                    </div>
                    <div class="col text-right">
                        <q-chip>{{ order.cart_products.length }} items</q-chip>
                    </div>
                </div>
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
                <q-list>
                    <q-item v-for="(cart_product, index) in order.cart_products" :key="index">
                        <q-item-section avatar>
                            <img :src="`/storage/${cart_product.product.photo}`" fill="contain" height="70px" width="70px"/>
                        </q-item-section>
                        <q-item-section>
                            <span>
                                <q-chip size="sm" :class="$q.dark.isActive ? 'bg-grey-9' : ''">
                                    {{ cart_product.quantity }}
                                </q-chip>
                                    {{ cart_product.product.name }}
                            </span>
                            <q-item-label v-for="(grouped_modifier, index) in cart_product.grouped_modifiers" :key="index">
                                {{ grouped_modifier.modifier_group.name }}
                                <q-item-label caption v-for="modifier in grouped_modifier.modifier_items" :key="modifier.id">
                                    {{ modifier.quantity }} - {{ modifier.modifier_item.name }} etc or should i display the price or total price
                                </q-item-label>
                            </q-item-label>
                            <q-item-label v-if="cart_product.special_instruction">
                                Note: {{ cart_product.special_instruction }}
                            </q-item-label>
                        </q-item-section>
                        <q-item-section side>
                            <q-item-label>
                                {{ cart_product.total }}
                            </q-item-label>
                        </q-item-section>
                    </q-item>
                </q-list>
                <q-separator/>
                <div class="row">
                    <q-space/>
                    <div class="q-mt-md q-mr-md text-subtitle1">
                        <span class="q-mr-md">Subtotal</span> {{ order.subtotal }}
                    </div>
                </div>
            </q-card-section>
            <q-card-actions class="justify-end">
                <q-btn no-caps v-close-popup flat>No</q-btn>
                <q-btn 
                    no-caps
                    :loading="orderForm.processing"
                    :disable="orderForm.processing"
                    @click="readyOrder()"
                    color="primary"
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
                >
                    Yes
                </q-btn>
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>