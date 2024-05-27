<script setup>

import { ref, watch } from 'vue'
import { Link } from '@inertiajs/vue3'
import Footer from '@/Components/Customer/Footer.vue'
import FoodCardItem from '@/Components/Customer/Product/FoodCardItem.vue'
import { router } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'

const $q = useQuasar()
const rightDrawerOpen = ref(false)
const clearCartDialog = ref(false)
const items = ref(null) 

watch(rightDrawerOpen, (newVal) => {
    if (newVal) {
        try {
            router.get('/cart/items', {}, {
            onSuccess: (res) => {
                items.value = res.props.items.cart_products
            }
        })
        } catch (error) {
            console.error('Error fetching cart items:', error)
        }
    }
})

</script>

<template>
    <q-layout view="hHh lpR lfr">

        <q-header :class="$q.dark.isActive ? 'bg-black text-white' : 'bg-white text-black'" elevated>
            <q-toolbar class="q-my-sm">
                <Link :href="route('homepage')" style="text-decoration: none;">
                    <q-toolbar-title>
                        <q-avatar size="50px">
                            <q-img src="logo.jpg"></q-img>
                        </q-avatar>
                        <span class="text-primary text-weight-medium q-ml-sm">Taytayan Camp Hub</span>
                    </q-toolbar-title>
                </Link>
                <!-- <Link :href="route('products')">
                    <q-btn flat no-caps>Product Menu</q-btn>
                </Link> -->
                <q-space/>
                <q-btn-dropdown no-caps label="Foods" flat dropdown-icon="keyboard_arrow_down">
                    <q-list style="min-width: 200px" class="text-center q-pa-sm">
                        <Link :href="route('products')">
                            <q-item clickable v-close-popup>
                                <q-item-section>Foods</q-item-section>
                            </q-item>
                        </Link>
                        <Link :href="route('orders')">
                            <q-item clickable v-close-popup>
                                <q-item-section>Orders</q-item-section>
                            </q-item>
                        </Link>
                        <Link :href="route('customer.cart.index')">
                            <q-item clickable v-close-popup>
                                <q-item-section>Cart</q-item-section>
                            </q-item>
                        </Link>
                    </q-list>
                </q-btn-dropdown>
                <q-btn-dropdown no-caps label="Accommodations" flat dropdown-icon="keyboard_arrow_down">
                    <q-list style="min-width: 100px" class="text-center q-pa-sm">
                        <q-item clickable v-close-popup>
                            <q-item-section>New tab</q-item-section>
                        </q-item>
                        <q-item clickable v-close-popup>
                            <q-item-section>Trips</q-item-section>
                        </q-item>
                        <q-item clickable v-close-popup>
                            <q-item-section>Recent tabs</q-item-section>
                        </q-item>
                    </q-list>
                </q-btn-dropdown>
                <q-btn-dropdown no-caps label="Events" flat dropdown-icon="keyboard_arrow_down">
                    <q-list style="min-width: 100px" class="text-center q-pa-sm">
                        <q-item clickable v-close-popup>
                            <q-item-section>Search Events</q-item-section>
                        </q-item>
                        <q-item clickable v-close-popup>
                            <q-item-section>New Tab</q-item-section>
                        </q-item>
                        <q-item clickable v-close-popup>
                            <q-item-section>Tickets</q-item-section>
                        </q-item>
                        
                    </q-list>
                </q-btn-dropdown>
                <q-space />
                <div v-if="!$page.props.auth.user">
                    <Link :href="route('register')">
                        <q-btn flat no-caps to="/register">Register</q-btn>
                    </Link>
                    <Link :href="route('login')">
                        <q-btn no-caps color="secondary" unelevated to="/login">Login</q-btn>
                    </Link>
                </div>
                <div v-else>
                    <!-- <q-btn flat icon="search" round></q-btn> -->
                    <q-btn flat dense icon="notifications" class="q-mr-sm">
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
                    <q-btn flat no-caps>
                        <q-avatar size="30px" class="q-mr-sm">
                            <img src="https://pbs.twimg.com/profile_images/1642568071046119428/xtyyRarT_400x400.jpg">
                        </q-avatar>
                        Joren
                        <q-menu class="q-pa-sm" style="width: 250px">
                            <q-list>
                                <q-item>
                                    <q-item-section top avatar>
                                        <q-avatar color="primary" text-color="white">
                                            <img src="https://pbs.twimg.com/profile_images/1642568071046119428/xtyyRarT_400x400.jpg">
                                        </q-avatar>
                                    </q-item-section>
                                    <q-item-section>
                                        <q-item-label> {{ $page.props.auth.user.first_name + ' ' + $page.props.auth.user.last_name }} </q-item-label>
                                        <q-item-label caption lines="1"> {{ $page.props.auth.user.email }} </q-item-label>
                                    </q-item-section>
                                </q-item>
                                <q-separator />
                                <Link :href="route('profile.edit')">
                                    <q-item clickable>
                                        <q-item-section avatar>
                                            <q-icon name="manage_accounts" />
                                        </q-item-section>
                                        <q-item-section>Profile</q-item-section>
                                    </q-item>
                                </Link>
                                <Link :href="route('logout')" method="post">
                                    <q-item clickable>
                                        <q-item-section avatar>
                                            <q-icon name="logout" />
                                        </q-item-section>
                                        <q-item-section>Logout</q-item-section>
                                    </q-item>
                                </Link>
                            </q-list>
                        </q-menu>
                    </q-btn>
                </div>
            </q-toolbar>
        </q-header>

        <q-page-container class="bg-grey-3">
            <div class="q-mx-xl q-mt-lg">
                <slot/>
            </div>
        </q-page-container>
        <Footer/>
    </q-layout>
</template>