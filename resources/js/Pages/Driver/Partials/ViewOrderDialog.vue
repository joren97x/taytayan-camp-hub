<script setup>

import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'

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
    <q-btn no-caps color="primary" @click="dialog = !dialog" :class="$q.screen.lt.md ? 'full-width' : ''" label="Button" />
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
                        <!-- <div class="row" style="position: relative">
                            <div class="col-6">
                                Name
                            </div>
                            <div class="col-6">
                                Contact Number
                            </div>
                            <div class="col-6">
                                Address
                            </div>
                            <div class="col-6">
                                Payment Method
                            </div>
                            <div class="col-6">
                                Address
                            </div>
                            <q-avatar size="100px" square class="absolute-top-right">
                                <q-img :src="`/storage/${order.user.profile_pic}`"/>
                            </q-avatar>
                        </div> -->
                        <q-item class="q-py-none">
                            <q-item-section>
                                <q-item-label class="text-weight-bold">{{ order.user.first_name + ' ' + order.user.last_name }}</q-item-label>
                                <q-item-label>{{ order.user.phone_number }}</q-item-label>
                                <q-item-label>{{ order.user.address }}</q-item-label>
                            </q-item-section>
                            <q-item-section side>
                                <q-avatar size="100px" square>
                                    <q-img :src="`/storage/${order.user.profile_pic}`"/>
                                </q-avatar>
                            </q-item-section>
                        </q-item>
                    <div class="text-h6">Items</div>
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
                <div class="col-4 col-xs-12 col-sm-12 col-lg-4 col-md-4 col-xl-4">
                    <div class="text-h6">Order Details</div>
                    
                    <div class="full-width bg-grey" style="height: 200px"></div>
                    <div class="q-mt-md">
                        <q-btn 
                            class="full-width" 
                            color="primary" 
                            no-caps
                            :loading="deliverOrderForm.processing"
                            :disable="deliverOrderForm.processing"
                            @click="deliverOrder()"
                        >
                            Deliver Order
                        </q-btn>
                        <q-btn
                            no-caps
                            class="full-width"
                            :loading="completeOrderForm.processing"
                            :disable="completeOrderForm.processing"
                            @click="completeOrder()"

                        >
                            Complete Order
                        </q-btn>
                    </div>
                </div>
            </q-card-section>
        </q-card>
    </q-dialog>
</template>