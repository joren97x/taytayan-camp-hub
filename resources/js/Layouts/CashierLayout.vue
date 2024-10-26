<script setup>

import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { useDrawerStore } from '@/Stores/DrawerStore'
import { useOrderStore } from '@/Stores/OrderStore'

const $q = useQuasar()
const orderStore = useOrderStore()
const drawerStore = useDrawerStore()
const leftDrawerOpen = ref(false)

function toggleLeftDrawer () {
    leftDrawerOpen.value = !leftDrawerOpen.value
}

const components = [
    'Cashier/Tickets',
    'Cashier/EventCheckIn',
    'Cashier/VerifyTicketOrder'
]


</script>

<template>
    <q-layout view="lHh Lpr lFf" class="bg-white">
        <q-drawer
            v-model="drawerStore.drawer"
            show-if-above
            bordered
        >
            <q-img 
                class="absolute-top" 
                src="/taytayan.jpg"
                style="height: 150px"
            >
                <q-item  class="absolute-bottom">
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
            <q-list class="q-mx-sm" style="margin-top: 150px;">
                <q-separator class="q-my-md"/>
                <!-- <q-item-label header>Essential Links</q-item-label> -->
                <Link :href="route('cashier.dashboard')">
                    <q-item clickable class="rounded-borders" :active="$page.component == 'Cashier/Dashboard'" active-class="bg-primary text-white">
                        <q-item-section avatar>
                            <q-icon name="dashboard" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Dashboard</q-item-label>
                            <!-- <q-item-label caption>https://quasar.dev</q-item-label> -->
                        </q-item-section>
                    </q-item>
                </Link>
                <Link :href="route('cashier.orders.index')">
                    <q-item clickable class="rounded-borders" :active="$page.component == 'Cashier/Orders'" active-class="bg-primary text-white">
                        <q-item-section avatar>
                            <q-icon name="shopping_cart" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Orders</q-item-label>
                            <!-- <q-item-label caption>https://quasar.dev</q-item-label> -->
                        </q-item-section>
                        <q-item-section side>
                            <q-chip>{{ orderStore.orders.length }}</q-chip>
                        </q-item-section>
                    </q-item>
                </Link>
                <Link :href="route('cashier.tickets.index')">
                    <q-item clickable class="rounded-borders" :active="components.includes($page.component)" active-class="bg-primary text-white">
                        <q-item-section avatar>
                            <q-icon name="confirmation_number" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Tickets</q-item-label>
                            <!-- <q-item-label caption>https://quasar.dev</q-item-label> -->
                        </q-item-section>
                    </q-item>
                </Link>
                <Link :href="route('cashier.bookings.index')">
                    <q-item clickable class="rounded-borders" :active="$page.component == 'Cashier/Bookings'" active-class="bg-primary text-white">
                        <q-item-section avatar>
                            <q-icon name="description" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Bookings</q-item-label>
                            <!-- <q-item-label caption>https://quasar.dev</q-item-label> -->
                        </q-item-section>
                    </q-item>
                </Link>
                
                <!-- <Link :href="route('cashier.profile')">
                    <q-item clickable class="rounded-borders" :active="$page.component == 'Cashier/Profile'" active-class="bg-primary text-white">
                        <q-item-section avatar>
                            <q-icon name="school" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Profile</q-item-label>
                        </q-item-section>
                    </q-item>
                </Link> -->
                <!-- <Link :href="route('cashier.inbox')">
                    <q-item clickable class="rounded-borders" :active="$page.component == 'Cashier/Inbox'" active-class="bg-primary text-white">
                        <q-item-section avatar>
                            <q-icon name="school" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Inbox</q-item-label>
                        </q-item-section>
                    </q-item>
                </Link> -->
            </q-list>
            <div class="absolute-bottom bg-white">
                <q-list >
                    <Link :href="route('conversations.index')">
                        <q-item clickable class="q-mx-sm rounded-borders" :active="$page.component == 'Cashier/Inbox'" active-class="bg-primary text-white">
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
                    <q-item clickable :active="$page.component == 'Cashier/Profile'" active-class="bg-primary text-white q-ma-sm rounded-borders">
                        <q-item-section top avatar>
                            <q-avatar color="primary" text-color="white">
                                <q-img class="fit" fit="cover" :src="`/storage/${$page.props.auth.user.profile_pic}`"/>
                            </q-avatar>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>{{ $page.props.auth.user.first_name + ' ' + $page.props.auth.user.last_name }}</q-item-label>
                            <q-item-label caption lines="2" :class="$page.component == 'Cashier/Profile' ? 'text-white' : 'text-black'">Cashier</q-item-label>
                        </q-item-section>
                        <q-item-section side top class="">
                            <q-btn :color="$page.component == 'Cashier/Profile' ? 'white' : 'black'" icon="unfold_more" flat round>
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
                                    <Link :href="route('cashier.profile')">
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
  
        <q-page-container class="bg-grey-3" style="height: 100vh">
            <slot @close="() => { alert('hi') }"/>
        </q-page-container>
    </q-layout>
  </template>

  <style scoped>

a {
    text-decoration: none;
    color: black
}
</style>
  
