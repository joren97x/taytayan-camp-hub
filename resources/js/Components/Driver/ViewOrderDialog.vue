<script setup>

import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    order: Object
})

const dialog = ref(false)
const deliverOrderForm = useForm({})

function deliverOrder() {
    deliverOrderForm.patch(route('driver.order.deliver', props.order.id), {
        onSuccess: () => {
            dialog.value = false
            $q.notify('Order Now To Be Delivered')
        }
    })
}

</script>

<template>
    <q-btn no-caps color="primary" @click="dialog = !dialog">Button</q-btn>
    <q-dialog v-model="dialog" full-width>
        <q-card>
            <q-card-section>
                <q-item>
                    <q-item-section>
                        <q-item-label class="text-h6">Customer Details</q-item-label>
                    </q-item-section>
                    <q-item-section side>
                        <q-btn round icon="close" unelevated v-close-popup></q-btn>
                    </q-item-section>
                    
                </q-item>
                <div class="q-mx-md">
                    {{ order.user.first_name + ' ' + order.user.last_name }}
                    <br>
                    {{ order.user.address }}
                    <br>
                    {{ order.user.phone_number }}
                </div>
                <q-item>
                    <q-item-section>
                        <q-item-label class="text-h6">Order Details</q-item-label>
                        <q-item-label caption> {{ order.created_at }}
                        </q-item-label>
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
                        <div class="q-mt-md">
                            <q-btn 
                                class="full-width" 
                                color="primary" no-caps
                                :loading="deliverOrderForm.processing"
                                :disable="deliverOrderForm.processing"
                                @click="deliverOrder()"
                            >
                                Deliver Order
                            </q-btn>
                            <!-- <q-btn class="full-width q-my-sm" color="red" outline no-caps>Cancel Order</q-btn> -->
                        </div>
                    </div>
                </div>
            </q-card-section>
           
        </q-card>
    </q-dialog>
</template>