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
const tab = ref('')
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

    if (page.props.auth.user) {
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

        <q-header :class="$q.dark.isActive ? 'bg-black text-white' : 'bg-white text-black'" style="z-index: 999;">
            <q-toolbar class="row items-center justify-between" style="margin: 0 auto; max-width: 1300px">
    <!-- Logo, Title, Tabs, and Search Bar in a Single Row -->
    <div class="row items-center">
        <!-- Logo -->
        <Link :href="route('homepage')" class="text-primary text-h6 text-weight-bolder">
            <q-avatar size="40px" class="q-mr-sm">
            <q-img src="../logo.jpg" fill="cover" />
        </q-avatar>
    </Link>
        
        <!-- Title -->
        <Link :href="route('homepage')" class="text-primary text-h6 text-weight-bolder q-mr-lg">
            Taytayan CAMP
        </Link>
        
        <!-- Tabs (placed next to the title) -->
        <q-tabs v-model="tab" shrink>
            <q-tab name="tab1" label="Tab 1" />
            <q-tab name="tab2" label="Tab 2" />
            <q-tab name="tab3" label="Tab 3" />
        </q-tabs>
        
        <!-- Search Input (right after the tabs) -->
        <q-input v-model="searchQuery" dense placeholder="Search..." @input="search" outlined rounded class="q-ml-md">
            <template v-slot:append>
                <q-icon name="search" />
            </template>
        </q-input>
    </div>

    <!-- User Actions (separated to the far right) -->
    <div class="row items-center">
        <div v-if="!$page.props.auth.user">
            <Link :href="route('register')" class="q-mr-md text-primary">
                Sign up for free
            </Link>
            <Link :href="route('login')">
                <q-btn no-caps color="primary" unelevated rounded class="text-subtitle2 text-weight-regular">
                    <q-icon left name="login" />
                    Login
                </q-btn>
            </Link>
            <q-btn icon="menu" v-if="!drawer" unelevated @click="drawer = true" class="lt-md" />
            <q-btn icon="close" v-else unelevated @click="drawer = false" class="lt-md" />
        </div>
        <div v-else class="flex">
            <Link>
                <q-item class="q-pa-none q-ma-none">
                    <q-item-section avatar class="q-pa-none q-ma-none">
                        <q-avatar size="3em" color="primary" text-color="white">
                            <q-img class="fit" fit="cover" :src="`/storage/${$page.props.auth.user.profile_pic}`"  v-if="$page.props.auth.user.profile_pic"/>
                            <div v-else>
                                {{ $page.props.auth.user.first_name[0] }}
                            </div>
                        </q-avatar>
                    </q-item-section>
                    <q-item-section class="q-pa-none q-ma-none">
                        {{ $page.props.auth.user.first_name + ' ' + $page.props.auth.user.last_name }}
                    </q-item-section>
                </q-item>
            </Link>
            <!-- <q-btn flat unelevated no-caps class="gt-sm q-px-none">
                <q-avatar size="3em" v-if="$page.props.auth.user.profile_pic">
                    <q-img class="fit" fit="cover" :src="`/storage/${$page.props.auth.user.profile_pic}`" />
                </q-avatar>
                <q-avatar v-else class="bg-primary text-white">
                    {{ $page.props.auth.user.first_name[0] }}
                </q-avatar>
                <span class="q-ml-sm">{{ $page.props.auth.user.first_name + ' ' + $page.props.auth.user.last_name }}</span>
            </q-btn> -->
            <q-btn flat dense rounded>
                <q-icon size="2em" name="notifications" />
                <q-badge color="red" floating v-if="notificationStore.unreadCount > 0">
                    {{ notificationStore.unreadCount }}
                </q-badge>
                <q-menu @show="notificationStore.readNotifications()" class="gt-sm">
                    <q-list style="max-width: 100vw; width: 500px; max-height: 700px;" bordered class="rounded-borders q-pa-sm">
                        <q-item class="text-h6">Notifications</q-item>
                        <NotificationItem :notification="notification" v-for="notification in notificationStore.notifications" />
                    </q-list>
                </q-menu>
            </q-btn>
            <q-btn icon="shopping_cart" flat label="3" rounded />
            <q-btn icon="settings" rounded flat />
        </div>
    </div>
</q-toolbar>
        </q-header>
        <!-- class="bg-grey-3" TIS BELONGED TO Q PAGE CONTAINER -->
        <q-page-container>
            <div class="content-wrapper">
                <slot />
                <Footer class="content-wrapper" />
            </div>
        </q-page-container>

    </q-layout>
</template>

<style scoped>
.content-wrapper {
    width: 100%;
    max-width: 1280px;
    margin: 0 auto;
    /* padding: 0 16px;  */
}



a {
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