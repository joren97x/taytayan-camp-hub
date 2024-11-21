<script setup>

import { ref, watch, computed, onMounted, onUnmounted } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import Footer from '@/Components/Footer.vue'
import NotificationItem from './Partials/NotificationItem.vue'
import { useNotificationStore } from '@/Stores/NotificationStore'
import AuthLinks from '@/Layouts/Partials/AuthLinks.vue'
import MainLinks from '@/Layouts/Partials/MainLinks.vue'
// import axios from 'axios'
import { useDrawerStore } from '@/Stores/DrawerStore'

const drawerStore = useDrawerStore()
const notificationStore = useNotificationStore()
const $q = useQuasar()
const $page = usePage()
const drawer = ref(false)
const notificationMenu = ref(false)
const tab = ref('')

const components = [
    'Customer/Product/Index',
    'Customer/Event/Index',
    'Customer/Facility/Index',
    'Customer/Index',
    'Homepage',
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

// const cartLength = ref(0)
// onMounted(() => {
//     if ($page.props.auth.user) {
//         axios.get(route('customer.cart.length'))
//         .then((res) => {
//             console.log(res)
//             $q.notify(res.data.cart_length)
//             cartLength.value = res.data.cart_length
//         })
//         .catch((err) => {
//             console.error(err)
//         })
//     }
// })

const isScrolled = ref(false);

const handleScroll = () => {
  // Toggle `isScrolled` based on scroll position
  isScrolled.value = window.scrollY > 10;
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});

const getTextColor = computed((component) => {
    if(!isScrolled.value && components.includes($page.component)) {
        return 'text-white'
    }
    else if(components.includes(component)) {
        return 'text-primary'
    }
    else {
        return 'text-black'
    }
}) 

</script>

<template>
    <q-layout view="hHh lpR lfr">
        <q-header 
            :class="[
                'q-header',
                isScrolled ? 'bg-white text-black ' : 'bg-transparent text-white',
            ]" 
            style="z-index: 999;" 
            :bordered="isScrolled"
        >
            <q-toolbar class="row items-center justify-between" style="margin: 0 auto; max-width: 1280px">
                <div class="row items-center">
                    <Link :href="route('home')" class="text-primary text-h6 text-weight-bolder">
                        <q-avatar size="lg" class="q-mr-sm">
                            <q-img src="../logo.jpg" fill="cover" />
                        </q-avatar>
                    </Link>

                    <Link :href="route('home')" class="text-primary text-h6 text-weight-bolder q-mr-lg">
                        <!-- Taytayan CAMP -->
                        <div class="text-design">
                            taytayan<br>
                            camp
                        </div>
                    </Link>
                </div>
                 <div class="text-red gt-xs">
                    <Link :href="route('customer.products.index')" :class="getTextColor">
                        Products
                    </Link>
                    <Link :href="route('customer.events.index')" :class="`${getTextColor} q-mx-lg`">
                        Events
                    </Link>
                    <Link :href="route('customer.facilities.index')" :class="getTextColor">
                        Facilities
                    </Link>
                 </div>
                <div class="row items-center flex">
                    <!-- <div class="items-center flex">
                        <q-input v-model="query" dense placeholder="Search..." @keyup.enter="search" outlined rounded
                            class="q-mr-md gt-sm">
                            <template v-slot:append>
                                <q-icon name="search" />
                            </template>
                        </q-input>
                    </div> -->
                    <Link :href="route('search')" >
                        <q-btn icon="search" round flat :class="getTextColor"/>
                    </Link>
                    <div v-if="!$page.props.auth.user">
                        <div class="gt-sm items-center flex justify-center full-height">
                            <Link :href="route('register')" :class="`${getTextColor} q-mx-md`">
                                Sign up
                            </Link>
                            <Link :href="route('login')" :class="getTextColor">
                                Login
                            </Link>
                        </div>
                        <div class="lt-md">
                            <q-btn icon="menu" round unelevated @click="drawer = true" class="lt-md">
                                <q-menu style="width: 200px;">
                                    <q-list>
                                        <MainLinks />
                                        <q-separator />
                                        <AuthLinks />
                                    </q-list>
                                </q-menu>
                            </q-btn>
                        </div>

                    </div>
                    <div v-else class="flex items-center">
                        <q-btn flat round class="q-mx-xs" @click="notificationMenu = true" :class="getTextColor" >
                            <q-icon name="notifications" />
                            <q-badge color="red" floating v-if="notificationStore.unreadCount > 0" class="q-mt-xs q-mr-xs">
                                {{ notificationStore.unreadCount }}
                            </q-badge>
                            <q-menu @show="notificationStore.readNotifications()" class="gt-sm">
                                <q-list style="max-width: 100vw; width: 500px; max-height: 700px;" bordered
                                    class="rounded-borders q-pa-sm">
                                    <q-item class="text-h6">Notifications</q-item>
                                    <NotificationItem :notification="notification"
                                        v-for="notification in notificationStore.notifications" />
                                        <div v-if="notificationStore.notifications.length == 0"
                                            class="q-pa-xl flex items-center justify-center">
                                            No notifications...
                                        </div>
                                </q-list>
                            </q-menu>
                        </q-btn>
                        <q-btn class="q-px-xs" flat unelevated no-caps :class="getTextColor">
                            <q-icon name="menu" class="lt-md" />
                            
                            <q-avatar size="md" text-color="white" color="primary" class="gt-sm">
                                <q-img class="fit" fit="cover" :src="`/storage/${$page.props.auth.user.profile_pic}`"
                                    v-if="$page.props.auth.user.profile_pic" />
                                <div v-else>
                                    {{ $page.props.auth.user.first_name[0] }}
                                </div>
                            </q-avatar>
                            <span class="q-ml-sm gt-sm">{{ $page.props.auth.user.first_name }}</span>
                            <q-menu class="q-pa-sm" style="width: 250px" auto-close @show="drawerStore.getCartLength">
                                <q-list>
                                    <div class="lt-md">
                                        <MainLinks />
                                    </div>
                                    <q-separator class="lt-md" />
                                    <Link :href="route($q.screen.gt.sm ? 'customer.edit_profile' : 'customer.profile')" class="text-black">
                                        <q-item clickable class="rounded-borders">
                                            <!-- <q-item-section avatar>
                                                <q-icon name="person" />
                                            </q-item-section> -->
                                            <q-item-section>Profile</q-item-section>
                                        </q-item>
                                    </Link>
                                    <Link :href="route('customer.cart.index')" class="text-black">
                                    <q-item clickable class="rounded-borders">
                                        <!-- <q-item-section avatar>
                                            <q-icon name="shopping_cart" />
                                        </q-item-section> -->
                                        <q-item-section>Cart</q-item-section>
                                        <q-item-section side>
                                            <q-chip>{{ drawerStore.cart }}</q-chip>
                                        </q-item-section>
                                    </q-item>
                                    </Link>
                                    <Link :href="route('conversations.index')" class="text-black">
                                    <q-item clickable class="rounded-borders">
                                        <!-- <q-item-section avatar>
                                            <q-icon name="inbox" />
                                        </q-item-section> -->
                                        <q-item-section>Inbox</q-item-section>
                                    </q-item>
                                    </Link>
                                    <q-item clickable class="rounded-borders text-negative" @click="logout()" >
                                        <!-- <q-item-section avatar>
                                            <q-icon name="logout" />
                                        </q-item-section> -->
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
            
                <slot name="cover"/>
            
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

.link, a{
    text-decoration: none;
}

.text-black:hover, .text-white:hover {
    color: var(--q-primary) !important;
}

/* 
.link:hover {
  color: var(--q-primary);
}

.hover-primary:hover {
  color: var(--q-primary);
} */

.user-menu-link {
    text-decoration: none;
    color: inherit;
}

.slide-down-enter {
    transform: translateY(-100%);
}

.q-header {
  transition: background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
}

.slide-down-leave {
    transform: translateY(0);
}

.slide-down-active {
    transition: transform 0.3s ease;
}
.text-design {
    font-family: 'Poppins', sans-serif;
    font-size: 1rem; /* Adjust font size to match image */
    font-weight: 700;
    color: #04b804; /* bright green color */
    text-align: center;
    line-height: 0.9; /* Tighten line height */
}
</style>