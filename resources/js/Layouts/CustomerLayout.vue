<script setup>

import { ref, watch, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'
import Footer from '@/Components/Customer/Footer.vue'
import { router } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import NavLinks from '@/Components/Customer/NavLinks.vue'

const $q = useQuasar()
const rightDrawerOpen = ref(false)
const items = ref(null) 
const notifications = ref([])
const notification_badge = ref(0)
const drawer = ref(false)
const sidebar = ref(false)
const btnToggle = ref('products')
const btnToggleLoading = ref(false)
const notificationMenu = ref(false)

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

watch(btnToggle, () => {
    router.visit(route(`customer.${btnToggle.value}.index`), {
        onStart: () => {
            btnToggleLoading.value = true
        },
        onFinish: () => {
            btnToggleLoading.value = false
        }
    })
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
                            <q-btn flat dense round class="q-mr-md gt-sm">
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
                            <q-btn round flat @click="sidebar = !sidebar" class="lt-md">
                                <q-avatar size="3em" class="bg-red">
                                    <img src="https://pbs.twimg.com/profile_images/1642568071046119428/xtyyRarT_400x400.jpg">
                                </q-avatar>
                            </q-btn>
                            <q-btn class="gt-sm" round>
                                <q-avatar size="3em" class="bg-red">
                                    <img src="https://pbs.twimg.com/profile_images/1642568071046119428/xtyyRarT_400x400.jpg">
                                </q-avatar>
                                <q-menu class="q-pa-sm gt-sm" style="width: 300px">
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
                                    <NavLinks/>
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
            <q-drawer
                v-model="sidebar"
                :width="250"
                behavior="mobile"
                v-if="$q.screen.lt.md"
                side="right"
                @click="sidebar = false"
            >
                <q-scroll-area style="height: calc(100% - 150px); margin-top: 150px; border-right: 1px solid #ddd">
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
                    </q-list>
                    <NavLinks/>
                    <q-item @click="notificationMenu = true" clickable>
                        <q-item-section avatar>
                            <q-avatar size="md">
                                <q-img fit="cover" src="images/facility_logo.png"/>
                            </q-avatar>
                        </q-item-section>
                        <q-item-section>Notifications</q-item-section>
                    </q-item>
                </q-scroll-area>

                <div  class="absolute-top" v-if="$page.props.auth.user">
                    <q-img src="https://cdn.quasar.dev/img/material.png" style="height: 150px">
                        <div class="absolute-bottom bg-transparent">
                            <q-avatar size="56px" class="q-mb-sm">
                            <img src="https://cdn.quasar.dev/img/boy-avatar.png">
                            </q-avatar>
                            <div class="text-weight-bold">{{ $page.props.auth.user.first_name + ' ' + $page.props.auth.user.last_name }}</div>
                            <div>{{ $page.props.auth.user.email }}</div>
                        </div>
                    </q-img>
                </div>
            </q-drawer>
            
        </q-header>
        <!-- class="bg-grey-3" TIS BELONGED TO Q PAGE CONTAINER -->
        <q-page-container>
            <div class="content-wrapper">
                <slot/>
            </div>
        </q-page-container>
        <q-footer reveal elevated class="lt-md" v-show="false" style="z-index: 998;">
            <q-toolbar class="row bg-white q-pa-none q-ma-none">
                <div class="col-12 ">
                    <q-btn-toggle 
                        v-model="btnToggle"
                        toggle-color="primary"
                        spread
                        unelevated
                        class="text-black"
                        :options="[
                            {  value: 'products', slot: 'products'},
                            {  value: 'events', slot: 'events'},
                            {  value: 'facilities', slot: 'facilities'}
                        ]" 
                    >
                        <template v-slot:products>
                            <q-btn 
                                no-caps 
                                unelevated 
                                :ripple="false" 
                                class="full-width" 
                                :loading="btnToggle == 'products' && btnToggleLoading"
                                :disabled="btnToggle == 'products' && btnToggleLoading"
                            >
                                <q-avatar size="sm" class="q-mr-sm">
                                    <q-img fit="cover" src="images/product_logo.png"/>
                                </q-avatar>
                                Products
                            </q-btn>
                        </template>
                        <template v-slot:events>
                            <q-btn 
                                no-caps 
                                unelevated 
                                :ripple="false" 
                                class="full-width" 
                                :loading="btnToggle == 'events' && btnToggleLoading"
                                :disabled="btnToggle == 'events' && btnToggleLoading"
                            >
                                <q-avatar size="sm" class="q-mr-sm">
                                    <q-img fit="cover" src="images/product_logo.png"/>
                                </q-avatar>
                                Events
                            </q-btn>
                        </template>
                        <template v-slot:facilities>
                            <q-btn 
                                no-caps 
                                unelevated 
                                :ripple="false" 
                                class="full-width" 
                                :loading="btnToggle == 'facilities' && btnToggleLoading"
                                :disabled="btnToggle == 'facilities' && btnToggleLoading"
                            >
                                <q-avatar size="sm" class="q-mr-sm">
                                    <q-img fit="cover" src="images/product_logo.png"/>
                                </q-avatar>
                                Facilities
                            </q-btn>
                        </template>
                    </q-btn-toggle>
                </div>
            </q-toolbar>
            <q-dialog 
                maximized 
                v-model="notificationMenu" 
                class="lt-md"      
                transition-show="slide-up"
                transition-hide="slide-down"
            >
                <q-card>
                    <q-card-section>
                        <div class="text-h6">Notifications</div>
                        <q-list>
                            <q-item v-for="n in 5" class="q-pa-none q-ma-none">
                                <q-item-section avatar>
                                    <q-avatar color="primary">
                                        {{ n }}
                                    </q-avatar>
                                </q-item-section>
                                <q-item-section>Notification {{ n }}</q-item-section>
                            </q-item>
                        </q-list>
                    </q-card-section>
                    <q-card-actions  class="absolute-top-right">
                        <q-btn icon="close" v-close-popup round unelevated></q-btn>
                    </q-card-actions>
                </q-card>
            </q-dialog>
        </q-footer>
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