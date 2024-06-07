<script setup>

const props = defineProps({
    order: Object
})

</script>

<template>
    <q-item :class="[order.status == 'pending' ? 'bg-blue-2' : 'bg-grey-4', 'q-my-sm']" clickable v-ripple>
        <q-item-section>
            <q-item-label>{{ order.user.first_name + ' ' + order.user.last_name }}</q-item-label>
            <q-item-label caption lines="2">{{ order.cart_products.length }} items</q-item-label>
        </q-item-section>
        <slot/>
    </q-item>
</template>