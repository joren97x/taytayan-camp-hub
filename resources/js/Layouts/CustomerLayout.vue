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
const drawer = ref(false)

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

        <q-header :class="$q.dark.isActive ? 'bg-black text-white' : 'bg-white text-black'"  style="z-index: 999;">
            <q-toolbar class="row q-py-xs q-px-md">
                <!-- <div class="row bg-red"> -->
                    <div class="col-1 flex items-start justify-start col-md-3 col-lg-3 col-xl-3 col-sm-1 col-xs-1">
                        <Link :href="route('homepage')" style="text-decoration: none;">
                            <q-toolbar-title>
                                <q-avatar size="50px">
                                    <q-img src="../logo.jpg" fill="cover" />
                                </q-avatar>
                                <span class="text-primary text-h6 text-weight-bolder q-ml-md">Taytayan Camp Hub</span>
                            </q-toolbar-title>
                        </Link>
                    </div>
                    <div class="flex items-center justify-center col-md-6 col-lg-6 col-xl-6 gt-sm">
                        <!-- {{ $q.screen.gt.xs }} -->
                        <Link :href="route('customer.products.index')" class="text-subtitle2 navlink q-mr-xl">
                            <q-avatar size="sm" class="">
                                <q-img fit="cover" src="images/product_logo.png"/>
                            </q-avatar>
                            Products
                        </Link>
                        <Link :href="route('customer.events.index')" class="text-subtitle2 navlink q-mr-xl">
                            <q-avatar size="sm" class="">
                                <q-img fit="cover" src="images/event_logo.png"/>
                            </q-avatar>
                            Events
                        </Link>
                        <Link :href="route('customer.facilities.index')" class="text-subtitle2 navlink q-mr-xl">
                            <q-avatar size="sm" class="">
                                <q-img fit="cover" src="images/facility_logo.png"/>
                            </q-avatar>
                            Facilities
                        </Link>
                    </div>
                    <div class="col-1 flex items-end justify-end col-md-3 col-lg-3 col-xl-3 col-sm-11 col-xs-11">
                        <div v-if="!$page.props.auth.user">
                            <Link :href="route('register')" class=" q-mr-md navlink text-primary gt-xs">
                                Sign up for free
                            </Link>
                            <Link :href="route('login')" class="gt-xs">
                                <q-btn 
                                    no-caps 
                                    color="primary" 
                                    unelevated 
                                    class="text-subtitle2 text-weight-regular"
                                >
                                <div>
                                    <q-icon left name="login" />
                                </div>
                                    Login
                                    </q-btn>
                            </Link>
                            <q-btn icon="menu" v-if="!drawer" unelevated @click="drawer = true" class="lt-md" />
                            <q-btn icon="close" v-else unelevated @click="drawer = false" class="lt-md" />
                        </div>
                        <div v-else>
                            <!-- <q-btn flat icon="search" round></q-btn> -->
                            <q-btn flat dense round class="q-mr-md">
                                <q-icon size="2em" name="notifications" />
                                <!-- uncomment soon -->
                                <!-- <q-badge color="red" floating>{{ notification_badge }}</q-badge> -->
                                <q-menu fit>
                                    <q-list style="min-width: 400px">
                                        <q-item class="text-h6">Notifications</q-item>
                                        <q-item clickable v-for="notification in notifications" :class="!notification.is_clicked ? 'bg-grey-4' : ''">
                                            <q-item-section>{{ notification }}</q-item-section>
                                        </q-item>
                                    </q-list>
                                </q-menu>
                            </q-btn>
                            <q-btn flat round no-caps>
                                <q-avatar size="3em">
                                    <img src="https://pbs.twimg.com/profile_images/1642568071046119428/xtyyRarT_400x400.jpg">
                                </q-avatar>
                                <!-- Joren -->
                                <q-menu class="q-pa-sm" style="width: 300px">
                                    <q-list>
                                        <Link :href="route('profile.edit')" class="user-menu-link">
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
                                        </Link>
                                        <q-separator />
                                        <Link :href="route('customer.cart.index')" class="user-menu-link">
                                            <q-item clickable>
                                                <q-item-section avatar>
                                                    <q-icon name="inbox" />
                                                </q-item-section>
                                                <q-item-section>Cart</q-item-section>
                                            </q-item>
                                        </Link>
                                        <Link :href="route('customer.orders.index')" class="user-menu-link">
                                            <q-item clickable>
                                                <q-item-section avatar>
                                                    <q-icon name="inbox" />
                                                </q-item-section>
                                                <q-item-section>Orders</q-item-section>
                                            </q-item>
                                        </Link>
                                        <Link :href="route('customer.bookings.index')" class="user-menu-link">
                                            <q-item clickable>
                                                <q-item-section avatar>
                                                    <q-icon name="manage_accounts" />
                                                </q-item-section>
                                                <q-item-section>Bookings</q-item-section>
                                            </q-item>
                                        </Link>
                                        <Link :href="route('customer.tickets.index')" class="user-menu-link">
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
                                            <q-item-section>Dark Mode</q-item-section>
                                            <q-item-section avatar>
                                                <q-toggle v-model="$q.dark.isActive"></q-toggle>
                                            </q-item-section>
                                        </q-item>
                                        <Link :href="route('customer.inbox')" class="user-menu-link">
                                            <q-item clickable>
                                                <q-item-section avatar>
                                                    <q-icon name="inbox" />
                                                </q-item-section>
                                                <q-item-section>Inbox</q-item-section>
                                            </q-item>
                                        </Link>
                                        <Link :href="route('profile.edit')" class="user-menu-link">
                                            <q-item clickable>
                                                <q-item-section avatar>
                                                    <q-icon name="manage_accounts" />
                                                </q-item-section>
                                                <q-item-section>Profile</q-item-section>
                                            </q-item>
                                        </Link>
                                        <Link :href="route('logout')" method="post" class="user-menu-link">
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
                    </div>
                <!-- </div> -->
            </q-toolbar>
                
            <q-card v-show="drawer" class="fixed full-width lt-md">
                <q-list class="q-pa-none">
                    <Link :href="route('customer.products.index')" class="text-subtitle2 navlink">
                        <q-item clickable>
                            <q-item-section avatar>
                                <q-avatar size="md">
                                <q-img fit="cover" src="images/product_logo.png"/>
                            </q-avatar>
                            </q-item-section>
                            <q-item-section>Products</q-item-section>
                        </q-item>
                    </Link>
                    <Link :href="route('customer.events.index')" class="text-subtitle2 navlink">
                        <q-item clickable>
                            <q-item-section avatar>
                                <q-avatar size="md">
                                <q-img fit="cover" src="images/event_logo.png"/>
                            </q-avatar>
                            </q-item-section>
                            <q-item-section>Events</q-item-section>
                        </q-item>
                    </Link>
                    <Link :href="route('customer.facilities.index')" :class="['text-subtitle2 navlink']">
                        <q-item clickable>
                            <q-item-section avatar>
                                <q-avatar size="md">
                                    <q-img fit="cover" src="images/facility_logo.png"/>
                                </q-avatar>
                            </q-item-section>
                            <q-item-section>Facilities</q-item-section>
                        </q-item>
                    </Link>
                    <Link :href="route('register')" class="navlink xs text-weight-bold text-primary">
                        <q-item clickable>
                            <q-item-section>Sign up for free</q-item-section>
                        </q-item>
                    </Link>
                    <Link :href="route('login')" class="navlink xs text-weight-bold text-primary">
                        <q-item clickable>
                            <q-item-section>Login</q-item-section>
                        </q-item>
                    </Link>
                </q-list>
            </q-card>
        </q-header>
        <!-- class="bg-grey-3" TIS BELONGED TO Q PAGE CONTAINER -->
        <q-page-container>
            <div class="content-wrapper">
                <slot/>
            </div>
        </q-page-container>
        <!-- <Footer/> -->
    </q-layout>
</template>

<style scoped>

.content-wrapper {
    width: 100%;
    max-width: 1280px;
    margin: 0 auto;
    /* padding: 0 16px;  */
}
  
.navlink {
    text-decoration: none;
    color: black;
}

.navlink:hover {
    color: #1976D2;
}

.user-menu-link {
    text-decoration: none;
    color: inherit;
}
.slide-down-enter {
  transform: translateY(-100%);
}

.slide-down-leave {
  transform: translateY(0);
}

.slide-down-active {
  transition: transform 0.3s ease;
}

</style>