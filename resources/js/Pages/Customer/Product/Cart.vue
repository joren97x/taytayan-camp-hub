<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    items: Object,
    subtotal: Number
})

</script>

<template>
    <h1>Cart page</h1>
    <div class="row">
        {{ subtotal }}
        {{ items }}
        <div class="col-12">
            <q-card 
                v-for="cart_product in items.cart_products" 
                :key="cart_product.id"
                bordered
                class="q-my-md"
            >
                <q-card-section>
                    <div class="text-subtitle2">{{ cart_product.quantity }}</div>
                    <div class="text-subtitle2">{{ cart_product.product.price }}</div>
                    <div class="text-h6">{{ cart_product.product.name }}</div>
                    <q-img :src="`images/${cart_product.product.photo}`" height="100px" width="100px"></q-img>
                </q-card-section>
                <q-card-section class="q-pt-none" 
                    v-for="modifiers in cart_product.grouped_modifiers"
                    :key="modifiers.id"
                >
                    {{ modifiers }}
                    <p v-for="item in modifiers.items" :key="item.id">
                        {{ item.name }}
                        <br>
                        {{ item.quantity }} quantity 
                        <br>
                        P {{ item.price }}
                    </p>
                </q-card-section>
            </q-card>
        </div>
    </div>

</template>