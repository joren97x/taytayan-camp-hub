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

        <q-header :class="$q.dark.isActive ? 'bg-black text-white' : 'bg-white text-black'" >
            <q-toolbar class="row q-py-sm q-px-lg">
                <!-- <div class="row bg-red"> -->
                    <div class="col-3 flex items-start justify-start">
                        <Link :href="route('homepage')" style="text-decoration: none;">
                            <q-toolbar-title>
                                <q-avatar size="70px">
                                    <q-img src="../logo.jpg"></q-img>
                                </q-avatar>
                                <span class="text-primary text-h5 text-weight-bolder q-ml-md">Taytayan Camp Hub</span>
                            </q-toolbar-title>
                        </Link>
                    </div>
                    <div class="col-6 flex items-center justify-center">
                        <Link :href="route('customer.products.index')" class="q-mx-lg text-subtitle1 navlink">
                            <q-avatar size="lg">
                                <q-img fit="cover" src="images/product_logo.png"/>
                            </q-avatar>
                            Products
                        </Link>
                        <Link :href="route('customer.events.index')" class="q-mx-lg text-subtitle1 navlink">
                            <q-avatar size="lg">
                                <q-img fit="cover" src="images/event_logo.png"/>
                            </q-avatar>
                            Events
                        </Link>
                        <Link :href="route('customer.facilities.index')" class="q-mx-lg text-subtitle1 navlink">
                            <q-avatar size="lg">
                                <q-img fit="cover" src="images/facility_logo.png"/>
                            </q-avatar>
                            Facilities
                        </Link>
                    </div>
                    <div class="col-3 flex items-end justify-end">
                        <div v-if="!$page.props.auth.user">
                            <Link :href="route('register')" class="text-subtitle1 q-mr-md navlink text-primary">
                                Sign up for free
                                <!-- <q-btn flat no-caps to="/register" class="text-subtitle1 text-primary text-weight-regular">Sign up for free</q-btn> -->
                            </Link>
                            <Link :href="route('login')">
                                <q-btn no-caps color="primary" unelevated to="/login" class="text-subtitle1 text-weight-regular q-px-xl">Login</q-btn>
                            </Link>
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
                                        <Link :href="route('customer.inbox')" class="user-menu-link">
                                            <q-item clickable>
                                                <q-item-section avatar>
                                                    <q-icon name="inbox" />
                                                </q-item-section>
                                                <q-item-section>Cart</q-item-section>
                                            </q-item>
                                        </Link>
                                        <Link :href="route('customer.inbox')" class="user-menu-link">
                                            <q-item clickable>
                                                <q-item-section avatar>
                                                    <q-icon name="inbox" />
                                                </q-item-section>
                                                <q-item-section>Orders</q-item-section>
                                            </q-item>
                                        </Link>
                                        <Link :href="route('profile.edit')" class="user-menu-link">
                                            <q-item clickable>
                                                <q-item-section avatar>
                                                    <q-icon name="manage_accounts" />
                                                </q-item-section>
                                                <q-item-section>Reservations</q-item-section>
                                            </q-item>
                                        </Link>
                                        <Link :href="route('tickets')" class="user-menu-link">
                                            <q-item clickable>
                                                <q-item-section avatar>
                                                    <q-icon name="logout" />
                                                </q-item-section>
                                                <q-item-section>Tickets</q-item-section>
                                            </q-item>
                                        </Link>
                                        <q-separator />
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

</style>