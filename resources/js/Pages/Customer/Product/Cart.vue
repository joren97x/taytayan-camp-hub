<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { ref } from 'vue'

const quantity = ref(1)

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
    {{ subtotal }}

    <q-list bordered separator>
      <q-item >
        <q-item-section avatar top>
            <q-select v-model="quantity" :options="[1,2,3,4,5,6,7,8,9,10]"></q-select>
        </q-item-section>
        <q-item-section>
            <q-item-label>Food name</q-item-label>
            <template v-for="n in 2" :key="n">
                <q-item-label caption>Choose your size</q-item-label>
                <q-item-label caption>Large(P10.00)</q-item-label>
            </template>
        </q-item-section>
        <q-item-section side top>P60.00</q-item-section>
      </q-item>
      </q-list>
    <div class="row">
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
                    <p v-for="item in modifiers.items" :key="item.id" class="bg-green">
                        {{ item.name }}
                        <br>
                        {{ item.quantity }} quantity 
                        <br>
                        P {{ item.price }}
                        <br>
                        subtotal: {{ item.quantity * item.price }}
                    </p>
                </q-card-section>
            </q-card>
        </div>
    </div>

</template>