<script setup>

import OrderItem from './OrderItem.vue'
import { ref } from 'vue'

defineProps({ order: Object })

const step = ref(1)
const dialog = ref(false)
const trackOrderDialog = ref(false)

</script>

<template>
    <OrderItem :order="order">
        <q-item-label>Apr 18, 2024</q-item-label>
        <div class="text-grey-8 q-gutter-xs">
            <q-btn 
                class="gt-xs" 
                unelevated 
                outline 
                color="primary" 
                no-caps 
                @click="dialog = true"
            >
                View Order
            </q-btn>
            <q-btn 
                class="gt-xs" 
                unelevated 
                color="primary" 
                no-caps 
                @click="trackOrderDialog = true"
            >
                Track
            </q-btn>
        </div>
    </OrderItem>
    <q-dialog v-model="trackOrderDialog" full-width>
        <q-card>
            <q-card-section>
                <q-item>
                    <q-item-section>
                        <q-item-label class="text-h6">Track Order</q-item-label>
                        <q-item-label caption> April 18, 2024
                        </q-item-label>
                    </q-item-section>
                    <q-item-section side>
                        <q-btn round icon="close" unelevated v-close-popup></q-btn>
                    </q-item-section>
                </q-item>
                <q-stepper
                    v-model="step"
                    color="primary"
                    flat
                    active-icon="hourglass_empty"
                >
                    <q-step
                        :name="1"
                        title="Pending"
                        caption="caption"
                        icon="check"
                        done
                    />

                    <q-step
                        :name="2"
                        title="Preparing"
                        caption="caption"
                        icon="outdoor_grill"
                    />

                    <q-step
                        :name="3"
                        :title="mode == 'Delivery' ? 'Out for delivery' : 'Ready for pick up'"
                        caption="caption"
                        icon="directions_bike"
                    />

                    <q-step
                        :name="4"
                        title="Completed"
                        caption="caption"
                        icon="assignment_turned_in"
                    />
                </q-stepper>
                <div class="row q-col-gutter-xl">
                    <div class="col-6">
                        <q-item>
                            <q-item-section>
                                <q-item-label class="text-h6">
                                    3 items
                                    <q-chip :class="$q.dark.isActive ? 'bg-grey-9' : ''">{{ mode }}</q-chip>
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
                    <div class="col-6">
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
                        <q-btn class="full-width q-mt-md" no-caps color="secondary">Cancel Order (or waitt idk)</q-btn>
                    </div>
                </div>
            </q-card-section>
        </q-card>
    </q-dialog>
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
                                    <img :src="`/images/${cart_product.product.photo}`" style="object-fit: cover;" />
                                    
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
                    </div>
                    <div class="col-4">
                        <q-item>
                            <q-item-section class="text-h6">Order Total</q-item-section>
                        </q-item>
                        <q-item>
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
                        </q-item>
                        <q-separator/>
                        <q-item class="text-h6">
                            <q-item-section>Total</q-item-section>
                            <q-item-section side>
                                {{ order.subtotal }}
                            </q-item-section>
                        </q-item>
                    </div>
                </div>
            </q-card-section>
        </q-card>
    </q-dialog>
</template>