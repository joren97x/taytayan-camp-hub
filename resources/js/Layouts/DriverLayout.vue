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
        <!-- <q-header elevated>
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
        </q-header> -->
  
        <q-drawer
            v-model="leftDrawerOpen"
            show-if-above
            bordered
            class="bg-grey-2"
        >

            <q-img 
                class="absolute-top" 
                src="/taytayan.jpg"
                style="height: 150px"
            >
                <q-item class="absolute-bottom">
                    <q-item-section avatar>
                        <q-avatar color="white" text-color="white">
                            <q-img 
                                src="/logo.png"
                            />
                        </q-avatar>
                    </q-item-section>
                    <q-item-section>
                        <q-item-label class="text-subtitle1 text-weight-bold">Taytayan Camp Hub</q-item-label>
                    </q-item-section>
                </q-item>
            </q-img>
            <q-list class="q-mx-sm" style="margin-top: 160px;">
                <Link :href="route('driver.dashboard')">
                    <q-item clickable class="rounded-borders" :active="$page.component == 'Driver/Dashboard'" active-class="bg-primary text-white">
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
                    <q-item clickable class="rounded-borders" :active="$page.component == 'Driver/Orders'" active-class="bg-primary text-white">
                        <q-item-section avatar>
                            <q-icon name="school" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Orders</q-item-label>
                            <!-- <q-item-label caption>https://quasar.dev</q-item-label> -->
                        </q-item-section>
                    </q-item>
                </Link>
                <Link :href="route('driver.delivery_history')">
                    <q-item clickable class="rounded-borders" :active="$page.component == 'Driver/DeliveryHistory'" active-class="bg-primary text-white">
                        <q-item-section avatar>
                            <q-icon name="school" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Delivery History</q-item-label>
                            <!-- <q-item-label caption>https://quasar.dev</q-item-label> -->
                        </q-item-section>
                    </q-item>
                </Link>
            </q-list>
            <div class="absolute-bottom bg-white">
                <q-list >
                    <Link :href="route('conversations.index')">
                        <q-item clickable class="q-mx-sm rounded-borders">
                            <q-item-section avatar>
                                <q-icon name="inbox" />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>Inbox</q-item-label>
                            </q-item-section>
                            <q-item-section side top>
                                <q-chip>2</q-chip>
                            </q-item-section> 
                        </q-item>
                    </Link>
                    <q-separator></q-separator>
                    <q-item class="q-pa-md">
                        <q-item-section top avatar>
                            <q-avatar color="primary" text-color="white" icon="engineering" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Joren Hyeung Nim</q-item-label>
                            <q-item-label caption lines="2">Administrator</q-item-label>
                        </q-item-section>
                        <q-item-section side top class="">
                            <q-btn color="primary" icon="unfold_more" flat round>
                                <q-menu class="q-pa-sm" anchor="center right" self="bottom start">
                                    <q-item clickable v-ripple  @click="$q.dark.toggle">
                                        <q-item-section avatar>
                                            <q-icon name="drafts" />
                                        </q-item-section>
                                        <q-item-section>
                                            Dark Mode
                                        </q-item-section>
                                    <q-item-section side top>
                                            <q-toggle v-model="$q.dark.isActive"></q-toggle>
                                        </q-item-section>
                                    </q-item>
                                    <Link :href="route('driver.profile')">
                                        <q-item clickable class="rounded-borders" :active="$page.component == 'Cashier/Profile'" active-class="bg-primary text-white">
                                            <q-item-section avatar >
                                                <q-icon name="school" />
                                            </q-item-section>
                                            <q-item-section>
                                                <q-item-label>View Profile</q-item-label>
                                                <!-- <q-item-label caption>https://quasar.dev</q-item-label> -->
                                            </q-item-section>
                                        </q-item>
                                    </Link>
                                    <Link :href="route('logout')" method="post">
                                        <!-- <q-btn icon="logout" round unelevated color="negative" flat label="flat">
                                            <q-tooltip>
                                                Logout
                                            </q-tooltip>
                                        </q-btn> -->
                                        <q-item clickable class="rounded-borders">
                                            <q-item-section avatar >
                                                <q-icon name="logout" />
                                            </q-item-section>
                                            <q-item-section>
                                                <q-item-label>Log out</q-item-label>
                                                <!-- <q-item-label caption>https://quasar.dev</q-item-label> -->
                                            </q-item-section>
                                        </q-item>
                                    </Link>
                                </q-menu>
                            </q-btn>
                        </q-item-section> 
                    </q-item>
                </q-list>
            </div>
        </q-drawer>
  
        <q-page-container>
            <div class="bg-grey-3" style="height: 100vh">
                <!-- <q-btn icon="menu" class="q-mx-md q-mt-md" @click="leftDrawerOpen = !leftDrawerOpen"/>
                <q-avatar>
                    <q-img src="../logo.jpg"></q-img>
                </q-avatar> -->
                <q-toolbar class="bg-white">
                    <q-btn flat round dense icon="menu" @click="leftDrawerOpen = !leftDrawerOpen" class="q-mr-sm" />
                    <q-avatar>
                        <q-img src="../logo.jpg"/>
                    </q-avatar>
                    <q-toolbar-title>Taytayan Camp Hub</q-toolbar-title>
                </q-toolbar>
                <slot/>
            </div>
        </q-page-container>
    </q-layout>
  </template>
  
<style scoped>

a {
    color: black;
    text-decoration: none;
}

</style>