<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
<Link :href="route('register')" class="text-primary">
    <q-item clickable>
        <q-item-section>Sign Up</q-item-section>
    </q-item>
</Link>
<Link :href="route('login')" class="text-primary">
    <q-item clickable>
        <q-item-section>Login</q-item-section>
    </q-item>
</Link>
</template>

<style scoped>

a {
    text-decoration: none;
    color: black;
}

</style>