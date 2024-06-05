<script setup>

defineProps({ order: Object })

</script>

<template>
    <q-item class="q-my-sm">
        <q-item-section thumbnail> 
            <img src="https://cdn.quasar.dev/img/chicken-salad.jpg"/>
        </q-item-section>
        <q-item-section>
            <q-item-label>
                <!-- Food name  -->
                {{ order }}
            </q-item-label>
            <q-item-label caption>
                3 items - P95.00
                {{ order.cart.cart_products.length }}
                {{ order }}
            </q-item-label>
            <q-item-label caption>
                {{ status }} - Order ABC123
            </q-item-label>
        </q-item-section>
        <q-item-section side top>
            <slot/>
        </q-item-section>
    </q-item>

</template>