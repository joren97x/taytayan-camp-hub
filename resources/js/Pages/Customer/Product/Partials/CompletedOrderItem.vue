<script setup>

import { ref } from 'vue'
import OrderItem from './OrderItem.vue'

defineProps({ status: String })

const dialog = ref(false)

</script>

<template>
    <OrderItem :status="status">
        <q-item-label>Apr 18, 2024</q-item-label>
        <div class="text-grey-8 q-gutter-xs">
            <q-btn class="gt-xs" unelevated outline color="primary" no-caps @click="dialog = true">View Order</q-btn>
            <q-btn class="gt-xs" unelevated color="primary" no-caps to="/food-checkout">Reorder</q-btn>
        </div>
    </OrderItem>
    <q-dialog v-model="dialog" full-width>
        <q-card>
            <q-card-section>
                <q-item>
                    <q-item-section>
                        <q-item-label class="text-h6">Order Details</q-item-label>
                        <q-item-label caption> April 18, 2024</q-item-label>
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
                                    3 items
                                    <q-chip :class="$q.dark.isActive ? 'bg-grey-9' : ''">Delivery</q-chip>
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-list>
                            <q-item v-for="n in 3" :key="n">
                                <q-item-section>
                                    <span>
                                        <q-chip size="sm" :class="$q.dark.isActive ? 'bg-grey-9' : ''">1</q-chip>
                                            Milktea
                                    </span>
                                    <q-item-label caption>Secondary line text. Lorem ipsum dolor sit amet
                                        consectetur adipiscit elit.
                                    </q-item-label>
                                </q-item-section>
                                <q-item-section thumbnail>
                                    <img src="https://cdn.quasar.dev/img/chicken-salad.jpg"/>
                                    <q-item-section side top class="q-mt-sm">
                                        <q-item-label>P30.00</q-item-label>
                                    </q-item-section>
                                </q-item-section>
                            </q-item>
                        </q-list>
                    </div>
                    <div class="col-4">
                        <q-item>
                            <q-item-section class="text-h6">Order Total</q-item-section>
                        </q-item>
                        <q-item>
                            <q-item-section>Subtotal</q-item-section>
                            <q-item-section side>
                                P90.00
                            </q-item-section>
                        </q-item>
                        <q-item>
                            <q-item-section>Tax or somn</q-item-section>
                            <q-item-section side>
                                P5.00
                            </q-item-section>
                        </q-item>
                        <q-separator/>
                        <q-item class="text-h6">
                            <q-item-section>Total</q-item-section>
                            <q-item-section side>
                                P95.00
                            </q-item-section>
                        </q-item>
                    </div>
                </div>
            </q-card-section>
        </q-card>
    </q-dialog>
</template>