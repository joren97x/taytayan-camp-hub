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
                IDK unsay Order title 
            </q-item-label>
            <q-item-label caption>
                {{ order.cart_products.length }} items - P{{ order.subtotal }}
            </q-item-label>
            <q-item-label caption>
                {{ order.status }} - Order ABC123
            </q-item-label>
        </q-item-section>
        <q-item-section side top>
            <slot/>
        </q-item-section>
    </q-item>

</template>