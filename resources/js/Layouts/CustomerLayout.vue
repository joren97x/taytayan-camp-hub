<script setup>

import { ref, watch, computed, onMounted } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import Footer from '@/Components/Footer.vue'
import NotificationItem from './Partials/NotificationItem.vue'
import { useNotificationStore } from '@/Stores/NotificationStore'
import AuthLinks from '@/Layouts/Partials/AuthLinks.vue'
import MainLinks from '@/Layouts/Partials/MainLinks.vue'
import axios from 'axios'

const notificationStore = useNotificationStore()
const $q = useQuasar()
const $page = usePage()
const drawer = ref(false)
const notificationMenu = ref(false)
const tab = ref('')

const components = [
    'Customer/Facility/Show',
    'Customer/Product/Index',
    'Customer/Event/Index',
    'Customer/Facility/Index',
    'Customer/Event/Index'
]

const currentComponent = computed(() => $page.component)

watch(currentComponent, (newComponent) => {
    if (!components.includes(newComponent)) {
        tab.value = ''
    }
})

const query = ref('')
const search = () => {
    router.get(route('search', query.value))
}

const logout = () => {
    router.post(route('logout'), {}, {
        onBefore: () => {
            Echo.leave(`notifications.${$page.props.auth.user.id}`)
        }
    })
}

const cartLength = ref(0)
onMounted(() => {
    if ($page.props.auth.user) {
        axios.get(route('customer.cart.length'))
            .then((res) => {
                console.log(res)
                cartLength.value = res.data.cart_length
            })
            .catch((err) => {
                console.error(err)
            })
    }
})

</script>

<template>
    <q-layout view="hHh lpR lfr">

        <q-header :class="$q.dark.isActive ? 'bg-black text-white' : 'bg-white text-black'" style="z-index: 999;" bordered>
            <q-toolbar class="row items-center justify-between" style="margin: 0 auto;">
                <div class="row items-center">
                    <Link :href="route('homepage')" class="text-primary text-h6 text-weight-bolder">
                    <q-avatar size="40px" class="q-mr-sm">
                        <q-img src="../logo.jpg" fill="cover" />
                    </q-avatar>
                    </Link>

                    <Link :href="route('homepage')" class="text-primary text-h6 text-weight-bolder q-mr-lg">
                    Taytayan CAMP
                    </Link>

                    <q-tabs v-model="tab" shrink no-caps inline-label class="gt-sm">
                        <Link :href="route('customer.products.index')">
                        <q-tab name="products" label="Products" icon="fastfood"
                            :class="tab == 'products' ? 'text-primary' : ''" />
                        </Link>
                        <Link :href="route('customer.events.index')">
                        <q-tab name="events" label="Events" icon="celebration"
                            :class="tab == 'events' ? 'text-primary' : ''" />
                        </Link>
                        <Link :href="route('customer.facilities.index')">
                        <q-tab name="facilities" label="Facilities" icon="cottage"
                            :class="tab == 'facilities' ? 'text-primary' : ''" />
                        </Link>
                    </q-tabs>

                </div>
                <div class="row">
                    <div class="items-center flex">
                        <q-input v-model="query" dense placeholder="Search..." @keyup.enter="search" outlined rounded
                            class="q-mr-md gt-sm">
                            <template v-slot:append>
                                <q-icon name="search" />
                            </template>
                        </q-input>
                    </div>
                    <Link :href="route('search')" class="lt-md">
                    <q-btn icon="search" round flat />
                    </Link>
                    <div v-if="!$page.props.auth.user">
                        <div class="gt-sm">
                            <Link :href="route('register')">
                            <q-btn label="Sign up for free" unelevated rounded no-caps class="text-primary" />
                            </Link>
                            <Link :href="route('login')">
                            <q-btn label="Login" rounded color="primary" no-caps unelevated icon="login" />
                            </Link>
                        </div>
                        <div class="lt-md">
                            <q-btn icon="menu" unelevated @click="drawer = true" class="lt-md">
                                <q-menu>
                                    <q-list>
                                        <MainLinks />
                                        <q-separator />
                                        <AuthLinks />
                                    </q-list>
                                </q-menu>
                            </q-btn>
                        </div>

                    </div>
                    <div v-else class="flex">
                        <q-btn flat dense rounded class="q-mr-sm" @click="notificationMenu = true">
                            <q-icon size="2em" name="notifications" />
                            <q-badge color="red" floating v-if="notificationStore.unreadCount > 0">
                                {{ notificationStore.unreadCount }}
                            </q-badge>
                            <q-menu @show="notificationStore.readNotifications()" class="gt-sm">
                                <q-list style="max-width: 100vw; width: 500px; max-height: 700px;" bordered
                                    class="rounded-borders q-pa-sm">
                                    <q-item class="text-h6">Notifications</q-item>
                                    <NotificationItem :notification="notification"
                                        v-for="notification in notificationStore.notifications" />
                                </q-list>
                            </q-menu>
                        </q-btn>
                        <q-btn class="q-px-xs" flat unelevated no-caps>
                            <q-icon name="menu" class="lt-md" />
                            <q-avatar size="3em" text-color="white" color="primary" class="gt-sm">
                                <q-img class="fit" fit="cover" :src="`/storage/${$page.props.auth.user.profile_pic}`"
                                    v-if="$page.props.auth.user.profile_pic" />
                                <div v-else>
                                    {{ $page.props.auth.user.first_name[0] }}
                                </div>
                            </q-avatar>
                            <span class="q-ml-sm gt-sm">{{ $page.props.auth.user.first_name + ' ' +
                                $page.props.auth.user.last_name
                                }}</span>
                            <q-menu class="q-pa-sm" style="width: 300px">
                                <q-list>
                                    <div class="lt-md">
                                        <MainLinks />
                                    </div>
                                    <q-separator class="lt-md" />
                                    <Link :href="route('customer.profile')">
                                    <q-item clickable class="rounded-borders">
                                        <q-item-section avatar>
                                            <q-icon name="person" />
                                        </q-item-section>
                                        <q-item-section>Profile</q-item-section>
                                    </q-item>
                                    </Link>
                                    <Link :href="route('customer.cart.index')">
                                    <q-item clickable class="rounded-borders">
                                        <q-item-section avatar>
                                            <q-icon name="shopping_cart" />
                                        </q-item-section>
                                        <q-item-section>Cart</q-item-section>
                                        <q-item-section side>
                                            <q-chip>{{ cartLength }}</q-chip>
                                        </q-item-section>
                                    </q-item>
                                    </Link>
                                    <Link :href="route('conversations.index')" class="nav-link">
                                    <q-item clickable class="rounded-borders">
                                        <q-item-section avatar>
                                            <q-icon name="inbox" />
                                        </q-item-section>
                                        <q-item-section>Inbox</q-item-section>
                                    </q-item>
                                    </Link>
                                    <q-item clickable class="rounded-borders text-negative" @click="logout()">
                                        <q-item-section avatar>
                                            <q-icon name="logout" />
                                        </q-item-section>
                                        <q-item-section>Logout</q-item-section>
                                    </q-item>
                                </q-list>
                            </q-menu>
                        </q-btn>
                    </div>
                </div>
            </q-toolbar>
            <q-dialog :maximized="$q.screen.lt.md" v-model="notificationMenu" class="lt-md" v-if="$q.screen.lt.md" transition-show="slide-up"
                transition-hide="slide-down" @show="notificationStore.readNotifications()">
                <q-card>
                    <q-list style="max-width: 100vw; width: 500px; max-height: 700px;" bordered
                        class="rounded-borders q-pa-sm">
                        <q-item class="text-h6">Notifications</q-item>
                        <NotificationItem @click="notificationMenu = false" :notification="notification"
                            v-for="notification in notificationStore.notifications" />
                        <div v-if="notificationStore.notifications.length == 0"
                            class="q-pa-xl flex items-center justify-center">
                            No notifications...
                        </div>
                    </q-list>
                    <q-card-actions class="absolute-top-right">
                        <q-btn icon="close" v-close-popup round unelevated></q-btn>
                    </q-card-actions>
                </q-card>
            </q-dialog>
        </q-header>
        <q-page-container>
            <div class="content-wrapper">
                <slot />
                <!-- <Footer class="content-wrapper" /> -->
            </div>
        </q-page-container>
        
    </q-layout>
    <Footer />
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