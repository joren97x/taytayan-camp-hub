<script setup>

import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useQuasar } from 'quasar'

const $q = useQuasar()
const leftDrawerOpen = ref(false)

function toggleLeftDrawer () {
    leftDrawerOpen.value = !leftDrawerOpen.value
}

Echo.private(`orders`)
    .listen('Product\\OrderPending', (data) => {
        console.log(data)
        $q.notify('Someone just ordered frr')
    })


</script>

<template>
    <q-layout view="lHh Lpr lFf" class="bg-white">
        <q-header elevated>
            <q-toolbar>
                <q-btn
                    flat
                    dense
                    round
                    @click="toggleLeftDrawer"
                    aria-label="Menu"
                    icon="menu"
                />
        
                <q-toolbar-title>
                    Quasar App
                </q-toolbar-title>
                <q-btn-group flat>
                <q-btn icon="dark_mode" @click="$q.dark.toggle()"></q-btn>
                <Link :href="route('logout')" method="post">
                    <q-btn icon="logout" flat></q-btn>
                </Link>
            </q-btn-group>
            </q-toolbar>
        </q-header>
  
        <q-drawer
            v-model="leftDrawerOpen"
            show-if-above
            bordered
            class="bg-grey-2"
        >
            <q-list>
                <q-item-label header>Essential Links</q-item-label>
                <Link :href="route('driver.dashboard')">
                    <q-item clickable>
                        <q-item-section avatar>
                            <q-icon name="school" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Dashboard</q-item-label>
                            <!-- <q-item-label caption>https://quasar.dev</q-item-label> -->
                        </q-item-section>
                    </q-item>
                </Link>
                <Link :href="route('driver.orders.index')">
                    <q-item clickable>
                        <q-item-section avatar>
                            <q-icon name="school" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Orders</q-item-label>
                            <!-- <q-item-label caption>https://quasar.dev</q-item-label> -->
                        </q-item-section>
                    </q-item>
                </Link>
                <Link :href="route('driver.orders.index')">
                    <q-item clickable>
                        <q-item-section avatar>
                            <q-icon name="school" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Delivery History</q-item-label>
                            <!-- <q-item-label caption>https://quasar.dev</q-item-label> -->
                        </q-item-section>
                    </q-item>
                </Link>
                <Link :href="route('driver.map')">
                    <q-item clickable>
                        <q-item-section avatar>
                            <q-icon name="school" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Map</q-item-label>
                            <!-- <q-item-label caption>https://quasar.dev</q-item-label> -->
                        </q-item-section>
                    </q-item>
                </Link>
                <Link :href="route('driver.account')">
                    <q-item clickable>
                        <q-item-section avatar>
                            <q-icon name="school" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Account</q-item-label>
                            <!-- <q-item-label caption>https://quasar.dev</q-item-label> -->
                        </q-item-section>
                    </q-item>
                </Link>
                <Link :href="route('driver.inbox')">
                    <q-item clickable>
                        <q-item-section avatar>
                            <q-icon name="school" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Inbox</q-item-label>
                            <!-- <q-item-label caption>https://quasar.dev</q-item-label> -->
                        </q-item-section>
                    </q-item>
                </Link>
            </q-list>
        </q-drawer>
  
        <q-page-container>
            <slot/>
        </q-page-container>
    </q-layout>
  </template>
  
