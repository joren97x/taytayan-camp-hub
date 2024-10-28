<script setup>

import { ref } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import OrderedItems from '@/Components/OrderedItems.vue'

const props = defineProps({
    order: Object
})

const $q = useQuasar()
const dialog = ref(false)
const deliverOrderForm = useForm({})
const completeOrderForm = useForm({})

function deliverOrder() {
    deliverOrderForm.patch(route('driver.order.deliver', props.order.id), {
        onSuccess: () => {
            dialog.value = false
            $q.notify('Order Now To Be Delivered')
        }
    })
}

const completeOrder = () => {
    completeOrderForm.patch(route('driver.order.complete_delivery', props.order.id), {
        onSuccess: () => {
            $q.notify('Order Has Been Delivered')
        }
    })
}

</script>

<template>
    <q-btn no-caps color="primary" rounded unelevated @click="dialog = !dialog" :class="$q.screen.lt.md ? 'full-width' : ''" label="View Order" />
    <q-dialog 
        v-model="dialog" 
        :maximized="$q.screen.lt.md"
        transition-show="slide-up"
        transition-hide="slide-down"
    >
        <q-card :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
            <q-card-section style="position: sticky; top: 0; z-index: 99;" class="q-pa-none q-pt-sm q-px-sm bg-white">
                <div class="row flex justify-center bg-white">
                    <q-btn icon="close" flat class="absolute-top-right q-mr-md q-mt-sm" v-close-popup round no-caps/>
                    <div class="text-h6">Deliver Order</div>
                </div>
                <q-separator class="q-mt-sm"/>
            </q-card-section>
            <q-card-section class="row q-col-gutter-md">
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
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
                    <div class="text-h6">Items</div>
                    <OrderedItems :subtotal="order.subtotal" :cart_products="order.cart_products" />  
                </div>
                <div class="col-4 col-xs-12 col-sm-12 col-lg-4 col-md-4 col-xl-4">
                    <div class="text-h6">Order Details</div>
                    <!-- {{ order }} -->
                    <div class="full-width bg-grey" style="height: 200px">
                        Payment Method <br>
                        Delivery Mode <br>
                        Waiting Time
                    </div>
                    <div class="q-mt-md">
                        <q-btn 
                            class="full-width" 
                            color="primary" 
                            no-caps
                            rounded 
                            unelevated
                            :loading="deliverOrderForm.processing"
                            :disable="deliverOrderForm.processing"
                            @click="deliverOrder()"
                            label="Deliver Order"
                            v-if="order.status == 'ready_for_delivery'"
                        />
                        <q-btn
                            no-caps
                            class="full-width q-mt-sm"
                            :loading="completeOrderForm.processing"
                            :disable="completeOrderForm.processing"
                            @click="completeOrder()"
                            rounded
                            color="primary"
                            v-if="order.status == 'delivering'"
                            label="Complete Delivery"
                        />
                    </div>
                </div>
            </q-card-section>
        </q-card>
    </q-dialog>
</template>