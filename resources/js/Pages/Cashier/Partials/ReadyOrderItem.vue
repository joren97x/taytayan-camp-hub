<script setup>

import { ref, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useQuasar, date } from 'quasar'

const props = defineProps({
    order: Object,
    order_statuses: Object
})

const dialog = ref(false)
const isOrderCompleteDialog = ref(false)
const $q = useQuasar()

const form = useForm({
    status: props.order_statuses.completed
})

function completeOrder() {
    form.patch(route('cashier.orders.update_status', props.order.id), {
        onSuccess: () => {
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
    <q-item :class="[order.status == 'pending' ? 'bg-blue-2' : 'bg-grey-4', 'q-my-sm']" clickable v-ripple @click="dialog = true">
        <q-item-section>
            <q-item-label>{{ order.user.first_name + ' ' + order.user.last_name }}</q-item-label>
            <q-item-label caption lines="2">{{ order.cart_products.length }} items</q-item-label>
        </q-item-section>
        <q-item-section v-if="order_statuses.ready_for_pickup == order.status" top side>
            {{ order.created_at }}
            {{ order.status }}
            <q-btn no-caps color="primary" @click.stop="isOrderCompleteDialog = true">Complete</q-btn>
        </q-item-section>
        <q-item-section side top v-else>
            {{ order.created_at }}
            {{ order.status }}
        </q-item-section>
    </q-item>

    <q-dialog 
        v-model="dialog" 
        :maximized="$q.screen.lt.md"
        transition-show="slide-up"
        transition-hide="slide-down"
    >
        <q-card :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
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
                <div class="row q-col-gutter-xl">
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
                    <div class="col-4 col-md-4 col-lg-4 col-xl-4 col-xs-12 col-sm-12">
                        <q-item>
                            <q-item-section class="text-h6">Order Total</q-item-section>
                        </q-item>
                        <q-item>
                            <q-item-section>Subtotal</q-item-section>
                            <q-item-section side>
                                {{ order.subtotal }}
                            </q-item-section>
                        </q-item>
                        <!-- <q-item>
                            <q-item-section>Delivery fee if kung delivery? or naa ba na?</q-item-section>
                            <q-item-section side>
                                P5.00
                            </q-item-section>
                        </q-item> -->
                        <q-separator/>
                        <q-item class="text-h6">
                            <q-item-section>Total</q-item-section>
                            <q-item-section side>
                                {{ order.subtotal }}
                            </q-item-section>
                        </q-item>
                        <div class="q-mt-md">
                            <!-- <q-btn 
                                class="full-width" 
                                color="primary" no-caps
                                :loading="acceptOrderForm.processing"
                                :disable="acceptOrderForm.processing"
                                @click="acceptOrder()"
                            >
                                Accept Order
                            </q-btn> -->
                            <q-btn class="full-width q-my-sm" color="red" outline no-caps>Cancel Order</q-btn>
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
                <q-btn no-caps v-close-popup>No</q-btn>
                <q-btn 
                    no-caps
                    :loading="form.processing"
                    :disable="form.processing"
                    @click="completeOrder()"
                    color="primary"
                >
                    Complete
                </q-btn>
            </q-card-actions>
        </q-card>
    </q-dialog>
    
</template>