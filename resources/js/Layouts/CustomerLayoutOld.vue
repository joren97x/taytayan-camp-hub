<script setup>

import { ref, watch, onMounted } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import NavLinks from '@/Components/Customer/NavLinks.vue'
import Footer from '@/Components/Footer.vue'
import { usePage } from '@inertiajs/vue3'
import NotificationItem from './Partials/NotificationItem.vue'
import { useNotificationStore } from '@/Stores/NotificationStore'

const notificationStore = useNotificationStore()
const $q = useQuasar()
const page = usePage()
const rightDrawerOpen = ref(false)
const items = ref(null) 
const drawer = ref(false)
const sidebar = ref(false)
const btnToggle = ref('products')
const btnToggleLoading = ref(false)
const notificationMenu = ref(false)

import axios from 'axios'

onMounted(() => {

    // axios.get(route('customer.notifications'))
    // .then((res) => {
    //     // console.log(res)
    //     res.data.notifications.forEach(el => {
    //         notifications.value.push(el)
    //         if(!el.is_read) {
    //             notification_badge.value++
    //         }
    //     })
    // })
    // .catch((err) => {
    //     console.error(err)
    // })

    if(page.props.auth.user) {
        // Echo.private(`notifications.${page.props.auth.user.id}`, )
        // .listen('Notify', (data) => {
        //     console.log('isud sa notifycaoiotns')
        //     // notifications.value.push(data)
        //     notificationStore.notifications.push(data)
        //     $q.notify('notification checkkkk')
        //     // orderStore.getOrders()
        //     console.log(data)
        // })
    }

})

// watch(rightDrawerOpen, (newVal) => {
//     if (newVal) {
//         try {
//             router.get('/cart/items', {}, {
//             onSuccess: (res) => {
//                 items.value = res.props.items.cart_products
//             }
//         })
//         } catch (error) {
//             console.error('Error fetching cart items:', error)
//         }
//     }
// })

// watch(btnToggle, () => {
//     router.visit(route(`customer.${btnToggle.value}.index`), {
//         onStart: () => {
//             btnToggleLoading.value = true
//         },
//         onFinish: () => {
//             btnToggleLoading.value = false
//         }
//     })
// })


</script>

<template>
    <q-layout view="hHh lpR lfr">

        <q-header :class="$q.dark.isActive ? 'bg-black text-white' : 'bg-white text-black'"  style="z-index: 999;">
            <q-toolbar class="row q-py-xs q-px-sm" style="margin: 0 auto; max-width: 1300px">
                <!-- <div class="col-1 flex items-start justify-start col-md-3 col-lg-3 col-xl-3 col-sm-1 col-xs-1"> -->
                    
                    <q-avatar size="">
                                <q-img src="../logo.jpg" fill="cover" />
                            </q-avatar>
                        <q-toolbar-title>
                    <Link :href="route('homepage')" style="text-decoration: none;">

                            <span class="text-primary text-h6 text-weight-bolder q-ml-xs">Taytayan CAMP</span>
                            <!-- {{ $page.component }} -->
                    </Link>

                        </q-toolbar-title>
                        <q-tabs v-model="tab" shrink>
                        <q-tab name="tab1" label="Tab 1" />
                        <q-tab name="tab2" label="Tab 2" />
                        <q-tab name="tab3" label="Tab 3" />
                    </q-tabs>
                <!-- </div> -->
                <!-- <div class="flex items-center justify-start col-md-6 col-lg-6 col-xl-6 gt-sm"> -->
                    <!-- {{ $q.screen.gt.xs }} -->
                    
                    <q-input v-model="searchQuery" class="q-m" dense placeholder="Search..." @input="search" outlined rounded>
                        <template v-slot:append>
                            <q-icon name="search"/>
                        </template>
                    </q-input>
                    <!-- <Link :href="route('customer.products.index')" class="text-subtitle2 navlink q-mr-xl">
                        <q-avatar square size="md">
                            <q-img fit="contain" src="/product_icon2.png"/>
                        </q-avatar>
                        Products
                    </Link>
                    <Link :href="route('customer.events.index')" class="text-subtitle2 navlink q-mr-xl">
                        <q-avatar square size="md">
                            <q-img fit="contain" src="/event_icon2.png"/>
                        </q-avatar>
                        Events
                    </Link>
                    <Link :href="route('customer.facilities.index')" class="text-subtitle2 navlink q-mr-xl">
                        <q-avatar square size="md">
                            <q-img fit="contain" src="/facility_icon2.png"/>
                        </q-avatar>
                        Facilities
                    </Link> -->
                <!-- </div> -->
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
                                rounded
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
                        
                        <q-btn round flat @click="sidebar = !sidebar" class="lt-md">
                            <q-avatar size="3em" v-if="$page.props.auth.user.profile_pic">
                                <q-img class="fit" fit="cover" :src="`/storage/${$page.props.auth.user.profile_pic}`"/>
                            </q-avatar>
                            <q-avatar v-else class="bg-primary text-white">
                                {{ $page.props.auth.user.first_name[0] }}
                            </q-avatar>
                        </q-btn>
                        <q-btn class="gt-sm q-px-none" flat unelevated no-caps>
                            <q-avatar size="3em" v-if="$page.props.auth.user.profile_pic">
                                <q-img class="fit" fit="cover" :src="`/storage/${$page.props.auth.user.profile_pic}`"/>
                            </q-avatar>
                            <q-avatar v-else class="bg-primary text-white">
                                {{ $page.props.auth.user.first_name[0] }}
                            </q-avatar>
                            <span class="q-ml-sm">{{ $page.props.auth.user.first_name + ' ' + $page.props.auth.user.last_name }}</span>
                            <q-menu class="q-pa-sm gt-sm" style="width: 300px">
                                <Link :href="route('customer.profile')" class="user-menu-link">
                                    <q-item>
                                        <q-item-section top avatar>
                                            <q-avatar size="3em" class="bg-red" v-if="$page.props.auth.user.profile_pic">
                                                <q-img class="fit" fit="cover" :src="`/storage/${$page.props.auth.user.profile_pic}`"/>
                                            </q-avatar>
                                            <q-avatar v-else class="bg-primary text-white">
                                                {{ $page.props.auth.user.first_name[0] }}
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
                        <q-btn flat dense round class="q-mr-md gt-sm">
                            <q-icon size="2em" name="notifications" />
                            <!-- uncomment soon -->
                            <q-badge color="red" floating v-if="notificationStore.unreadCount > 0">{{ notificationStore.unreadCount }}</q-badge>
                            <q-menu @show="notificationStore.readNotifications()" class="gt-sm">
                                <q-list style="max-width: 100vw; width: 500px; max-height: 700px;" bordered class="rounded-borders q-pa-sm">
                                    <q-item class="text-h6">Notifications</q-item>
                                    <NotificationItem :notification="notification" v-for="notification in notificationStore.notifications" />
                                </q-list>
                            </q-menu>
                        </q-btn>
                    </div>
                </div>
            </q-toolbar>
                
            <q-card v-show="drawer" class="fixed full-width lt-md">
                <q-list class="q-pa-none">
                    <Link :href="route('customer.products.index')" class="text-subtitle2 navlink">
                        <q-item clickable>
                            <q-item-section avatar>
                                <q-avatar size="md">
                                <q-img fit="cover" src="/product_icon2.png"/>
                            </q-avatar>
                            </q-item-section>
                            <q-item-section>Products</q-item-section>
                        </q-item>
                    </Link>
                    <Link :href="route('customer.events.index')" class="text-subtitle2 navlink">
                        <q-item clickable>
                            <q-item-section avatar>
                                <q-avatar size="md">
                                <q-img fit="cover" src="/event_icon2.png"/>
                            </q-avatar>
                            </q-item-section>
                            <q-item-section>Events</q-item-section>
                        </q-item>
                    </Link>
                    <Link :href="route('customer.facilities.index')" :class="['text-subtitle2 navlink']">
                        <q-item clickable>
                            <q-item-section avatar>
                                <q-avatar size="md">
                                    <q-img fit="cover" src="/facility_icon2.png"/>
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
                                    <q-img fit="cover" src="/product_icon2.png"/>
                                </q-avatar>
                                </q-item-section>
                                <q-item-section>Products</q-item-section>
                            </q-item>
                        </Link>
                        <Link :href="route('customer.events.index')" class="text-subtitle2 navlink">
                            <q-item clickable>
                                <q-item-section avatar>
                                    <q-avatar size="md">
                                    <q-img fit="cover" src="/event_icon2.png"/>
                                </q-avatar>
                                </q-item-section>
                                <q-item-section>Events</q-item-section>
                            </q-item>
                        </Link>
                        <Link :href="route('customer.facilities.index')" :class="['text-subtitle2 navlink']">
                            <q-item clickable>
                                <q-item-section avatar>
                                    <q-avatar size="md">
                                        <q-img fit="cover" src="/facility_icon.png"/>
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
                                <q-img fit="cover" src="/images/facility_logo.png"/>
                            </q-avatar>
                        </q-item-section>
                        <q-item-section>Notifications</q-item-section>
                        <q-item-section side>
                            <q-chip floating v-if="notificationStore.unreadCount > 0">{{ notificationStore.unreadCount }}</q-chip>
                        </q-item-section>
                    </q-item>
                </q-scroll-area>

                <div  class="absolute-top" v-if="$page.props.auth.user">
                    <q-img src="https://cdn.quasar.dev/img/material.png" style="height: 150px">
                        <div class="absolute-bottom bg-transparent">
                            <q-avatar size="3em" v-if="$page.props.auth.user.profile_pic">
                                <q-img class="fit" fit="cover" :src="`/storage/${$page.props.auth.user.profile_pic}`"/>
                            </q-avatar>
                            <q-avatar v-else class="bg-primary text-white">
                                {{ $page.props.auth.user.first_name[0] }}
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
                <Footer class="content-wrapper"/>
            </div>
            <q-page-sticky position="bottom-right" :offset="[18, 18]">
                <q-btn round icon="message" size="xl">
                    <q-menu anchor="top left" self="bottom right" >
                        <q-card style="max-width: 450px; width: 450px; height: 500px">
                            <q-item clickable>
                                <q-item-section>New tab</q-item-section>
                            </q-item>
                            <q-item clickable>
                                <q-item-section>New incognito tab</q-item-section>
                            </q-item>
                        </q-card>
                    </q-menu>
                </q-btn>
            </q-page-sticky>
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
                @show="notificationStore.readNotifications()"
            >
                <q-card>
                    <q-list style="max-width: 100vw; width: 500px; max-height: 700px;" bordered class="rounded-borders q-pa-sm">
                        <q-item class="text-h6">Notifications</q-item>
                        <NotificationItem @click="notificationMenu = false" :notification="notification" v-for="notification in notificationStore.notifications" />
                    </q-list>
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