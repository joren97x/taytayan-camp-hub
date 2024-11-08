<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
<Link :href="route('customer.products.index')">
    <q-item clickable>
        <q-item-section avatar>
            <q-icon name="fastfood" />
        </q-item-section>
        <q-item-section>Products</q-item-section>
    </q-item>
</Link>
<Link :href="route('customer.events.index')">
    <q-item clickable>
        <q-item-section avatar>
            <q-icon name="celebration" />
        </q-item-section>
        <q-item-section>Events</q-item-section>
    </q-item>
</Link>
<Link :href="route('customer.facilities.index')">
    <q-item clickable>
        <q-item-section avatar>
            <q-icon name="cottage" />
        </q-item-section>
        <q-item-section>Facilities</q-item-section>
    </q-item>
</Link>
</template>

<style scoped>

a {
    text-decoration: none;
    color: black;
}

</style>