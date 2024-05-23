<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import FoodCardItem from '@/Components/Customer/Product/FoodCardItem.vue'
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'

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
    <q-list separator class="bg-white">
        <FoodCardItem 
            v-for="(item, index) in items" 
            :key="index"
            :item="item"
        />
        
    </q-list>
    <Link :href="route('product-checkout')">
        <q-btn>Checkout</q-btn>
    </Link>

</template>