<script setup>

import { ref, watch, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import Footer from '@/Components/Customer/Footer.vue'
import FoodCardItem from '@/Components/Customer/Product/FoodCardItem.vue'
import { router } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'

const $q = useQuasar()
const rightDrawerOpen = ref(false)
const items = ref(null) 
const notifications = ref([])
const notification_badge = ref(0)

import axios from 'axios'

onMounted(() => {
    axios.get(route('customer.notifications'))
    .then((res) => {
        // console.log(res)
        res.data.notifications.forEach(el => {
            notifications.value.push(el)
            if(!el.is_read) {
                notification_badge.value++
            }
        })
    })
    .catch((err) => {
        console.error(err)
    })
})

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
            <q-toolbar>
                <Link :href="route('homepage')" style="text-decoration: none;">
                    <q-toolbar-title>
                        <q-avatar size="50px">
                            <q-img src="../logo.jpg"></q-img>
                        </q-avatar>
                        <span class="text-primary text-weight-medium q-ml-sm">Taytayan Camp Hub</span>
                    </q-toolbar-title>
                </Link>
                <!-- <Link :href="route('products')">
                    <q-btn flat no-caps>Product Menu</q-btn>
                </Link> -->
                <q-space/>
                <!-- <q-btn-dropdown no-caps label="Foods" flat dropdown-icon="keyboard_arrow_down">
                    <q-list style="min-width: 200px" class="text-center q-pa-sm">
                        <Link :href="route('products')">
                            <q-item clickable v-close-popup>
                                <q-item-section>Foods</q-item-section>
                            </q-item>
                        </Link>
                        <Link :href="route('orders', 'on-progress')">
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
                <q-btn-dropdown no-caps label="Facilities" flat dropdown-icon="keyboard_arrow_down">
                    <q-list style="min-width: 100px" class="text-center q-pa-sm">
                        <Link :href="route('customer.facilities.index')">
                            <q-item clickable v-close-popup>
                                <q-item-section>Facilities</q-item-section>
                            </q-item>
                        </Link>
                        <Link :href="route('reservations')">
                            <q-item clickable v-close-popup>
                                <q-item-section>reservations</q-item-section>
                            </q-item>
                        </Link>
                    </q-list>
                </q-btn-dropdown> -->
                <!-- <q-btn-dropdown no-caps label="Events" flat dropdown-icon="keyboard_arrow_down">
                    <q-list style="min-width: 100px" class="text-center q-pa-sm">
                        <Link :href="route('customer.events.index')">
                            <q-item clickable v-close-popup>
                                <q-item-section>Events</q-item-section>
                            </q-item>
                        </Link>
                        <Link :href="route('tickets')">
                            <q-item clickable v-close-popup>
                                <q-item-section>Tickets</q-item-section>
                            </q-item>
                        </Link>
                    </q-list>
                </q-btn-dropdown> -->
                <q-btn-group unelevated>
                    <Link :href="route('customer.products.index')">
                        <q-btn align="around" class="q-py-md" color="primary" flat>
                            <q-icon left size="sm" class="q-mr-md" name="map" />
                            Products
                        </q-btn>
                    </Link>
                    <Link :href="route('customer.events.index')">
                        <q-btn align="around" class="q-py-md" color="primary" flat >
                            <q-icon left size="sm" class="q-mr-md" name="map" />
                            Events
                        </q-btn>
                    </Link>
                    <Link :href="route('customer.facilities.index')">
                        <q-btn align="around" class="q-py-md" color="primary" flat >
                            <q-icon left size="sm" class="q-mr-md" name="map" />
                            Facilities
                        </q-btn>
                    </Link>
                </q-btn-group>
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
                        <q-badge color="red" floating>{{ notification_badge }}</q-badge>
                        <q-menu fit>
                            <q-list style="min-width: 400px">
                                <q-item class="text-h6">Notifications</q-item>
                                <q-item clickable v-for="notification in notifications" :class="!notification.is_clicked ? 'bg-grey-4' : ''">
                                    <q-item-section>{{ notification }}</q-item-section>
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
                                <Link :href="route('customer.inbox')">
                                    <q-item clickable>
                                        <q-item-section avatar>
                                            <q-icon name="inbox" />
                                        </q-item-section>
                                        <q-item-section>Cart</q-item-section>
                                    </q-item>
                                </Link>
                                <Link :href="route('customer.inbox')">
                                    <q-item clickable>
                                        <q-item-section avatar>
                                            <q-icon name="inbox" />
                                        </q-item-section>
                                        <q-item-section>Orders</q-item-section>
                                    </q-item>
                                </Link>
                                <Link :href="route('profile.edit')">
                                    <q-item clickable>
                                        <q-item-section avatar>
                                            <q-icon name="manage_accounts" />
                                        </q-item-section>
                                        <q-item-section>Reservations</q-item-section>
                                    </q-item>
                                </Link>
                                <Link :href="route('logout')" method="post">
                                    <q-item clickable>
                                        <q-item-section avatar>
                                            <q-icon name="logout" />
                                        </q-item-section>
                                        <q-item-section>Tickets</q-item-section>
                                    </q-item>
                                </Link>
                                <q-separator />
                                <Link :href="route('customer.inbox')">
                                    <q-item clickable>
                                        <q-item-section avatar>
                                            <q-icon name="inbox" />
                                        </q-item-section>
                                        <q-item-section>Inbox</q-item-section>
                                    </q-item>
                                </Link>
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
        <!-- <Footer/> -->
    </q-layout>
</template>