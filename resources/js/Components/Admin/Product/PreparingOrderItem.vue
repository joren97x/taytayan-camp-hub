<script setup>

import OrderItem from './OrderItem.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'

const props = defineProps({
    order: Object,
    order_statuses: Object
})

const dialog = ref(false)
const isOrderReadyDialog = ref(false)
const $q = useQuasar()

const acceptOrderForm = useForm({
    waiting_time: '15 mins',
    status: props.order_statuses.preparing
})

const readyOrderForm = useForm({
    status: props.order.mode == 'pickup' ? props.order_statuses.ready_for_pickup : props.order_statuses.ready_for_delivery
})

function acceptOrder() {
    acceptOrderForm.patch(route('cashier.orders.update_status', props.order.id), {
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
    readyOrderForm.patch(route('cashier.orders.update_status', props.order.id), {
        onSuccess: (e) => {
            console.log(e)
            isOrderReadyDialog.value = false
            $q.notify('Order Accepted')
        }
    })
}

</script>

<template>
    <OrderItem :order="order" @click="dialog = true">
        <q-item-section side top v-if="order_statuses.pending == order.status">
            New
            <q-item-label caption>{{ order.created_at }}</q-item-label>
            <!-- <q-btn no-caps color="primary" @click.stop="isOrderReadyDialog = true">
                View Order
            </q-btn> -->
        </q-item-section>
        <q-item-section side top v-else>
            <q-item-label caption>{{ order.created_at }}</q-item-label>
            <q-btn no-caps color="primary" @click.stop="isOrderReadyDialog = true">
                Ready Order
            </q-btn>
        </q-item-section> 
    </OrderItem>
    <q-dialog v-model="dialog" full-width>
        <q-card>
            <q-card-section>
                <q-item>
                    <q-item-section>
                        <q-item-label class="text-h6">Order Details</q-item-label>
                        <q-item-label caption> {{ order.created_at }}
                        </q-item-label>
                    </q-item-section>
                    <q-item-section side>
                        <q-btn round icon="close" unelevated v-close-popup></q-btn>
                    </q-item-section>
                </q-item>
                <div class="row q-col-gutter-xl">
                    <div class="col-8">
                        <q-item>
                            <q-item-section>
                                <q-item-label class="text-h6">
                                    {{ order.cart_products.length }} items
                                    <q-chip :class="$q.dark.isActive ? 'bg-grey-9' : ''">
                                        {{ order.mode }}
                                    </q-chip>
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-list>
                            <q-item v-for="(cart_product, index) in order.cart_products" :key="index">
                                <q-item-section thumbnail>
                                    <img :src="`/storage/${cart_product.product.photo}`" style="object-fit: cover;" />
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
                                <q-item-section side top class="q-mt-sm">
                                    <q-item-label>
                                        {{ cart_product.total }}
                                    </q-item-label>
                                </q-item-section>
                            </q-item>
                        </q-list>
                        <q-separator/>
                        <div class="row">
                            <q-space/>
                            Subtotal - {{ order.subtotal }}
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center text-h6">Waiting Time</div>
                        <div class="text-center text-h6">15 min</div>
                        <q-input
                            filled
                            v-model="acceptOrderForm.waiting_time"
                            label="Waiting Time"
                            mask="###/mins"
                            unmasked-value
                            hint="Mask: ###/##"
                        />
                        <div class="text-center  text-subtitle-1 text-green">
                            Suggested
                        </div>
                        <div class="text-center">
                            <q-btn rounded no-caps>
                                Edit
                            </q-btn>
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
                            <q-btn class="full-width q-my-sm" color="red" outline no-caps>Cancel Order</q-btn>
                        </div>
                    </div>
                </div>
            </q-card-section>
           
        </q-card>
    </q-dialog>
    <q-dialog v-model="isOrderReadyDialog">
        <q-card>
            <q-card-section>
                Is the order ready?
            </q-card-section>
            <q-card-actions>
                <q-btn no-caps v-close-popup>No</q-btn>
                <q-btn 
                    no-caps
                    :loading="readyOrderForm.processing"
                    :disable="readyOrderForm.processing"
                    @click="readyOrder()"
                >
                    Yes
                </q-btn>
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>