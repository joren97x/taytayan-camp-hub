<script setup>

import { Link } from '@inertiajs/vue3'
import axios from 'axios'
import { onMounted, ref } from 'vue'

const cartLength = ref(0)

onMounted(() => {
    axios.get(route('customer.cart.length'))
    .then((res) => {
        console.log(res)
        cartLength.value = res.data.cart_length
    })
    .catch((err) => {
        console.error(err)
    })
})
</script>
<template>
    <q-list>
        
        <Link :href="route('customer.cart.index')" class="nav-link">
            <q-item clickable>
                <q-item-section avatar>
                    <q-icon name="inbox" />
                </q-item-section>
                <q-item-section>Cart {{ cartLength }} </q-item-section>
                
            </q-item>
        </Link>
        <Link :href="route('customer.orders.index')" class="nav-link">
            <q-item clickable>
                <q-item-section avatar>
                    <q-icon name="inbox" />
                </q-item-section>
                <q-item-section>Orders</q-item-section>
            </q-item>
        </Link>
        <Link :href="route('customer.bookings.index')" class="nav-link">
            <q-item clickable>
                <q-item-section avatar>
                    <q-icon name="manage_accounts" />
                </q-item-section>
                <q-item-section>Bookings</q-item-section>
            </q-item>
        </Link>
        <Link :href="route('customer.tickets.index')" class="nav-link">
            <q-item clickable>
                <q-item-section avatar>
                    <q-icon name="logout" />
                </q-item-section>
                <q-item-section>Tickets</q-item-section>
            </q-item>
        </Link>
        <q-separator />
        <q-item clickable @click="$q.dark.toggle()">
            <q-item-section avatar>
                <q-icon name="dark_mode" />
            </q-item-section>
            <q-item-section>Dark Mode(buggy)</q-item-section>
            <q-item-section avatar>
                <q-toggle v-model="$q.dark.isActive"></q-toggle>
            </q-item-section>
        </q-item>
        <Link :href="route('customer.inbox')" class="nav-link">
            <q-item clickable>
                <q-item-section avatar>
                    <q-icon name="inbox" />
                </q-item-section>
                <q-item-section>Inbox</q-item-section>
            </q-item>
        </Link>
        <Link :href="route('customer.profile')" class="nav-link">
            <q-item clickable>
                <q-item-section avatar>
                    <q-icon name="manage_accounts" />
                </q-item-section>
                <q-item-section>Profile</q-item-section>
            </q-item>
        </Link>
        <Link :href="route('logout')" method="post" class="nav-link">
            <q-item clickable>
                <q-item-section avatar>
                    <q-icon name="logout" />
                </q-item-section>
                <q-item-section>Logout</q-item-section>
            </q-item>
        </Link>
    </q-list>
</template>

<style scoped>

.nav-link {
    text-decoration: none;
    color: black;
}

</style>