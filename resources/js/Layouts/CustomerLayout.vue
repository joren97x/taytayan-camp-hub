<script setup>

import { ref } from 'vue'
import Footer from '@/Components/Customer/Footer.vue'
import FoodCardItem from '@/Components/Customer/Milkteas/FoodCardItem.vue'


const rightDrawerOpen = ref(false)
const clearCartDialog = ref(false)

async function onLogout() {
    
}

</script>

<template>
    <q-layout view="hHh lpR lfr">

        <q-header>
            <q-toolbar class="q-pa-md">
                <q-toolbar-title>
                <q-avatar>
                    <img src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg">
                </q-avatar>
                Taytayan Camp Hub
                </q-toolbar-title>
                <q-space />
                <q-btn flat no-caps to="/milktea-menu">Milktea Menu</q-btn>
                <q-btn flat no-caps to="/events">Explore Events</q-btn>
                <q-btn flat no-caps to="/campsites">Book Campsite</q-btn>
                <q-space />
                <div>
                    <q-btn flat no-caps to="/register">Register</q-btn>
                    <q-btn no-caps color="secondary" unelevated to="/login">Login</q-btn>
                </div>
                <div>
                    <q-btn flat icon="search" round></q-btn>
                    <q-btn flat icon="notifications" round>
                        <q-badge color="red" floating>4</q-badge>
                        <q-menu fit>
                            <q-list style="min-width: 400px">
                                <q-item clickable>
                                    <q-item-section>New tab</q-item-section>
                                </q-item>
                                <q-item clickable>
                                    <q-item-section>New incognito tab</q-item-section>
                                </q-item>
                            </q-list>
                        </q-menu>
                    </q-btn>
                    <q-btn flat round icon="shopping_cart" @click="rightDrawerOpen = !rightDrawerOpen">
                        <q-badge color="red" floating>4</q-badge>
                    </q-btn>
                    <q-btn flat rounded :icon="$q.dark.isActive ? 'light_mode' : 'dark_mode'" @click="$q.dark.toggle()"/>
                    <q-btn flat round>
                        <q-avatar size="42px">
                            <img src="https://pbs.twimg.com/profile_images/1642568071046119428/xtyyRarT_400x400.jpg">
                        </q-avatar>
                        <q-menu max-width>
                            <q-list>
                                <q-item>
                                    <q-item-section top avatar>
                                        <q-avatar color="primary" text-color="white">
                                            <img src="https://pbs.twimg.com/profile_images/1642568071046119428/xtyyRarT_400x400.jpg">
                                        </q-avatar>
                                    </q-item-section>
                                    <q-item-section>
                                        <q-item-label>John doe</q-item-label>
                                        <q-item-label caption lines="1">John@doe.com</q-item-label>
                                    </q-item-section>
                                </q-item>
                                <q-separator />
                                <q-item clickable to="/reservations">
                                    <q-item-section avatar>
                                        <q-icon name="date_range" />
                                    </q-item-section>
                                    <q-item-section>Reservations</q-item-section>
                                </q-item>
                                <q-item clickable to="/tickets">
                                    <q-item-section avatar>
                                        <q-icon name="confirmation_number" />
                                    </q-item-section>
                                    <q-item-section>Tickets</q-item-section>
                                </q-item>
                                <q-item clickable to="/orders">
                                    <q-item-section avatar>
                                        <q-icon name="receipt_long" />
                                    </q-item-section>
                                    <q-item-section>Orders</q-item-section>
                                </q-item>
                                <q-separator />
                                <q-item clickable to="/settings">
                                    <q-item-section avatar>
                                        <q-icon name="manage_accounts" />
                                    </q-item-section>
                                    <q-item-section>Settings</q-item-section>
                                </q-item>
                                <q-item clickable @click="onLogout()">
                                    <q-item-section avatar>
                                        <q-icon name="logout" />
                                    </q-item-section>
                                    <q-item-section>Logout</q-item-section>
                                </q-item>
                            </q-list>
                        </q-menu>
                    </q-btn>
                </div>
            </q-toolbar>
        </q-header>

        <q-drawer v-model="rightDrawerOpen" side="right" bordered overlay>
            <!-- drawer content -->
            <q-item>
                <q-item-section class="text-h6">RJC Cafe</q-item-section>
                <q-item-section side>
                    <div class="text-grey-8 q-gutter-xs">
                        <q-btn icon="delete" color="red" round flat no-caps @click="clearCartDialog = true">
                            <q-tooltip>
                                Clear cart
                            </q-tooltip>
                        </q-btn>
                        <q-btn class="gt-xs" flat round icon="close" @click="rightDrawerOpen = false">
                            <q-tooltip>
                                Close cart
                            </q-tooltip>
                        </q-btn>
                    </div>
                </q-item-section>
            </q-item>
            <q-separator/>
            <q-item>
                <q-item-section>
                    2 items
                </q-item-section>
                <q-item-section side>
                    Subtotal: P65.00
                </q-item-section>
            </q-item>
            <q-list>
                <FoodCartItem v-for="n in 3" :key="n" />
            </q-list>
            <div class="q-pa-sm full-width" style="position: absolute; bottom: 0;">
                <q-btn 
                    to="/food-checkout"
                    class="full-width"
                    color="blue" 
                    label="Checkout" 
                    no-caps
                />
            </div>
        </q-drawer>

        <q-dialog v-model="clearCartDialog">
            <q-card class="full-width">
                <q-card-section>
                    <div class="text-h6">Clear Cart</div>
                    Are you sure you want to clear your cart?
                </q-card-section>
                <q-card-actions align="right">
                    <q-btn outline v-close-popup no-caps color="primary">Cancel</q-btn>
                    <q-btn unelevated no-caps color="negative">Clear</q-btn>
                </q-card-actions>
            </q-card>
        </q-dialog>

        <q-page-container class="q-ma-xl">
            <slot/>
        </q-page-container>
        <Footer/>
    </q-layout>
</template>