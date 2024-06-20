<script setup>

import { ref, watch, onMounted } from 'vue'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import CompletedOrderItem from '@/Components/Customer/Product/CompletedOrderItem.vue'
import PendingOrderItem from '@/Components/Customer/Product/PendingOrderItem.vue'
import CancelledOrderItem from '@/Components/Customer/Product/CancelledOrderItem.vue'
import { Head, router, usePage } from '@inertiajs/vue3'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    orders: Object,
    order_constants: Object
})

const tab = ref('on-progress')

watch(tab, () => {
    console.log('go')
    router.get(route('orders', tab.value), [], {
        onSuccess: (res) => {
            console.log(res)
        }
    })
})

</script>

<template>
    <div>
    <Head title="Orders" />
    {{ order_constants }}
        <!-- ding';
    const STATUS_PREPARING = 'preparing';
    const STATUS_READY_FOR_DELIVERY = 'ready_for_delivery';
    const STATUS_READY_FOR_PICKUP = 'ready_for_pickup';
    const STATUS_DELIVERING = 'delivering';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled'; -->
        <q-btn-group spread class="bg-white q-mb-md">
            <q-btn class="q-pa-md" no-caps label="All" />
            <q-btn class="q-pa-md" v-for="(order_constant, index) in order_constants.statuses" :key="index" no-caps :label="order_constant" />
        </q-btn-group>
        
        <q-input filled placeholder="You can serach by product name, order ID" class="q-mb-md">
            <template v-slot:prepend>
                <q-icon name="search" />
            </template>
        </q-input>

        <q-list>
            <q-list-item v-for="n in 3">
                <q-card flat bordered  class="q-my-md">
                    <q-card-section horizontal>
                        <q-card-section class="col-2 flex flex-center">
                            <q-img
                                height="100px"
                                width="100px"
                                v-for="n in 4"
                                class=""
                                src="https://cdn.quasar.dev/img/parallax2.jpg"
                            />
                        </q-card-section>
                        <q-card-section class="q-pt-xs full-width">
                            <div class="text-h5 q-mt-xl q-mb-xs">Title</div>
                            <div class="text-caption text-grey">
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                            </div>
                            <div class="">
                                3 items
                            </div>
                        </q-card-section>
                        <q-card-section>
                            <q-chip class="text-subtitle-1 text-right text-white" color="secondary">For Delivery</q-chip>
                            <div class="text-h6 text-right q-mr-md q-mt-md">P90.00</div>
                        </q-card-section>
                    </q-card-section>

                    <q-separator />

                    <q-card-actions>
                        <q-space/>
                        <q-btn no-caps padding="md" color="primary">
                            Buy Again
                        </q-btn>
                        <q-btn no-caps padding="md" outline>
                            View Order
                        </q-btn>
                    </q-card-actions>
                </q-card>
            </q-list-item>
        </q-list>

    </div>
</template>

<!-- <template>
    <Head title="Orders" />
    <div>
        <div class="row q-col-gutter-xl">
            <div class="col-3">
                <q-card>
                    <q-card-section>
                        {{ tab }}
                        <q-list>
                            <q-item clickable @click="tab = 'on-progress'">
                                <q-item-section avatar>
                                    <q-icon name="schedule"></q-icon>
                                </q-item-section>
                                <q-item-section>
                                    On Progress
                                </q-item-section>
                            </q-item>
                            <q-item clickable @click="tab = 'completed'">
                                <q-item-section avatar>
                                    <q-icon name="check"></q-icon>
                                </q-item-section>
                                <q-item-section>
                                    Completed
                                </q-item-section>
                            </q-item>
                            <q-item clickable @click="tab = 'cancelled'">
                                <q-item-section avatar>
                                    <q-icon name="cancel"></q-icon>
                                </q-item-section>
                                <q-item-section>
                                    Cancelled
                                </q-item-section>
                            </q-item>
                        </q-list>
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-9">
                <q-card class="q-mb-xl">
                    <q-card-section>
                        <q-item>
                            <q-item-section class="text-h6">{{ tab }}</q-item-section>
                        </q-item>
                        <q-list v-if="orders">
                            <div v-if="tab == 'on-progress'">
                                <PendingOrderItem 
                                    v-for="order in orders" 
                                    :order="order"
                                />
                            </div>
                            <div v-if="tab == 'completed'">
                                <CompletedOrderItem v-for="(order, index) in orders" :key="index" :status="'Completed'" />
                            </div>
                            <div v-if="tab == 'cancelled'">
                                <CancelledOrderItem v-for="(order, index) in orders" :key="index" :status="'Cancelled'" />
                            </div>
                        </q-list>
                    </q-card-section>
                </q-card>
            </div>
        </div>
    </div>
</template> -->