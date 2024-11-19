<script setup>

import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import { useDrawerStore } from '@/Stores/DrawerStore'

const drawerStore = useDrawerStore()
const leftDrawerOpen = ref(false)
const $q = useQuasar()
// onMounted(() => {
//     axios.get(route('admin.get_user_roles'))
//     .then((res) => {
//         res.data.user_roles.forEach(element => {
//             user_roles.value.push(element)
//         });
//     })
//     .catch((err) => {
//         console.error(err)
//     })
// })

const navigation_list = [
    {
        name: 'Product Management',
        icon: 'restaurant',
        navigations: [
            { 
                label: 'Dashboard', 
                value: 'dashboard', 
                href: 'admin.products.dashboard', 
                component: 'Admin/Product/Dashboard',
                components: [],
                icon: 'restaurant'
            },
            { 
                label: 'Products', 
                value: 'products', 
                href: 'admin.products.index', 
                component: 'Admin/Product/Products',
                components: [
                    'Admin/Product/CreateProduct',
                    'Admin/Product/EditProduct'
                ],
                icon: 'restaurant'
            },
            {   
                label: 'Categories', 
                value: 'categories', 
                href: 'admin.categories.index', 
                component: 'Admin/Product/Categories' ,
                components: [
                    'Admin/Product/CreateCategory',
                ],
                icon: 'category'
            },
            {   
                label: 'Modifier Groups', 
                value: 'modifier-groups', 
                href: 'admin.modifier_groups.index', 
                component: 'Admin/Product/ModifierGroups',
                components: [
                    'Admin/Product/CreateModifierGroup',
                    'Admin/Product/EditModifierGroup',
                ],
                icon: 'checklist_rtl'
            },
            {   
                label: 'Orders', 
                value: 'orders', 
                href: 'admin.orders.index', 
                component: 'Admin/Product/Orders' ,
                components: [],
                icon: 'shopping_cart'
            },
            {   
                label: 'Reviews', 
                value: 'reviews', 
                href: 'admin.product_ratings.index', 
                component: 'Admin/Product/Ratings' ,
                components: [],
                icon: 'star_rate'
            },
        ]
    },
    {
        name: 'Event Management',
        icon: 'event',
        navigations: [
            { 
                label: 'Dashboard', 
                value: 'dashboard', 
                href: 'admin.events.dashboard', 
                component: 'Admin/Event/Dashboard',
                components: [],
                icon: 'restaurant'
            },
            { 
                label: 'Events', 
                value: 'events', 
                href: 'admin.events.index', 
                component: 'Admin/Event/Events',
                components: [
                    'Admin/Event/CreateEvent',
                    'Admin/Event/EditEvent',
                    'Admin/Event/ShowEvent',
                    'Admin/Event/EventDashboard',
                ],
                icon: 'event'
            },
            { 
                label: 'Tickets', 
                value: 'tickets', 
                href: 'admin.tickets.index', 
                component: 'Admin/Event/Tickets',
                components: [],
                icon: 'confirmation_number'
            },
            // { 
            //     label: 'Reviews', 
            //     value: 'reviews', 
            //     href: 'admin.events.reviews', 
            //     component: 'Admin/Event/Reviews',
            //     icon: ''
            // },
        ]
    },
    {
        name: 'Facility Management',
        icon: 'cottage',
        navigations: [
            { 
                label: 'Dashboard', 
                value: 'dashboard', 
                href: 'admin.facilities.dashboard', 
                component: 'Admin/Facility/Dashboard',
                components: [],
                icon: 'restaurant'
            },
            { 
                label: 'Facilities', 
                value: 'facility', 
                href: 'admin.facilities.index', 
                component: 'Admin/Facility/Facilities',
                components: [
                    'Admin/Facility/CreateFacility',
                    'Admin/Facility/EditFacility',
                ],
                icon: 'cottage'
            },
            { 
                label: 'Bookings', 
                value: 'bookings', 
                href: 'admin.bookings.index', 
                component: 'Admin/Facility/Bookings',
                components: [],
                icon: 'description'
            },
            { 
                label: 'Ratings', 
                value: 'ratings', 
                href: 'admin.facility_ratings.index', 
                component: 'Admin/Facility/Ratings',
                components: [],
                icon: ''
            },
        ]
    },

]

</script>


<template>
    <q-layout view="lHh LpR lFf">
        <q-drawer show-if-above v-model="drawerStore.drawer" side="left" bordered>
            <q-item  class="absolute-top q-my-md">
                <q-item-section avatar>
                    <q-avatar color="primary" text-color="white">
                        <q-img 
                            src="/logo.jpg"
                        />
                    </q-avatar>
                </q-item-section>
                <q-item-section>
                    <q-item-label class="text-h6 text-weight-bold">Taytayan Camp Hub</q-item-label>
                </q-item-section>
            </q-item>
            <!-- drawer content -->
            <q-scroll-area style="height: calc(100% - 150px); margin-top: 60px; border-right: 1px solid #ddd;">
                <q-list class="q-mx-sm q-mt-md" style="margin-bottom: 130px">
                    <q-item-label header class="q-pa-none q-px-md q-py-xs">Management</q-item-label>
                    <Link :href="route('admin.dashboard')">
                        <q-item clickable v-ripple class="rounded-borders" :active="$page.component == 'Admin/Dashboard'" active-class="bg-primary text-white">
                            <!-- <q-item-section avatar>
                                <q-icon name="dashboard" />
                            </q-item-section> -->
                            <q-item-section>
                                Dashboard
                            </q-item-section>
                        </q-item>
                    </Link>
                    <q-expansion-item 
                        :label="navigation.name" 
                        v-for="navigation in navigation_list" 
                        class="rounded-borders"
                    >
                        <q-card class="q-mx-md">
                            <Link :href="route(nav.href)" v-for="nav in navigation.navigations">
                                <q-item clickable v-ripple class="rounded-borders" :active="$page.component == nav.component || nav.components.includes($page.component)" active-class="bg-primary text-white">
                                    <!-- <q-item-section avatar>
                                        <q-icon :name="nav.icon" />
                                    </q-item-section> -->
                                    <q-item-section>
                                        {{ nav.label }}
                                    </q-item-section>
                                </q-item>
                            </Link>
                        </q-card>
                    </q-expansion-item>
                    <Link :href="route(`admin.users.index`)">
                        <q-item clickable v-ripple class="rounded-borders" :active="$page.component == 'Admin/UserManagement'" active-class="bg-primary text-white">
                            <!-- <q-item-section avatar>
                                <q-icon name="people" />
                            </q-item-section> -->
                            <q-item-section class="text-capitalize">
                                <!-- {{ user_role }} -->
                                User Management
                            </q-item-section>
                        </q-item>
                    </Link>
                </q-list>
            </q-scroll-area>
            <div class="absolute-bottom bg-white">
                <q-list >
                    <Link :href="route('conversations.index')">
                        <q-item clickable class="q-mx-sm rounded-borders" :active="$page.component == 'Admin/Inbox'" active-class="bg-primary text-white">
                            <!-- <q-item-section avatar>
                                <q-icon name="inbox" />
                            </q-item-section> -->
                            <q-item-section>
                                <q-item-label>Inbox</q-item-label>
                            </q-item-section>
                            <!-- <q-item-section side top>
                                <q-chip>2</q-chip>
                            </q-item-section>  -->
                        </q-item>
                    </Link>
                    <q-separator></q-separator>
                    <q-item class="q-pa-md rounded-borders q-ma-sm" :active="$page.component == 'Admin/Profile'" active-class="bg-primary text-white">
                        <q-item-section top avatar>
                            <q-avatar color="primary" text-color="white">
                                <q-img :src="`/storage/${$page.props.auth.user.profile_pic}`" v-if="$page.props.auth.user.profile_pic" class="fit" fit="cover" />
                                <div v-else>
                                    {{ $page.props.auth.user.first_name[0] }}
                                </div>
                            </q-avatar>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>{{ $page.props.auth.user.first_name + ' ' + $page.props.auth.user.last_name }}</q-item-label>
                            <q-item-label caption lines="2" :class="$page.component == 'Admin/Profile' ? 'text-white' : 'text-black'">Administrator</q-item-label>
                        </q-item-section>
                        <q-item-section side top class="">
                            <q-btn :color="$page.component == 'Admin/Profile' ? 'white' : 'black'" icon="unfold_more" flat round>
                                <q-menu class="q-pa-sm" anchor="center right" self="bottom start" style="max-width: 180px; width: 180px;">
                                    <!-- <q-item clickable v-ripple  @click="$q.dark.toggle">
                                      
                                        <q-item-section>
                                            Dark Mode
                                        </q-item-section>
                                    <q-item-section side top>
                                            <q-toggle v-model="$q.dark.isActive"></q-toggle>
                                        </q-item-section>
                                    </q-item> -->
                                    <Link :href="route('admin.profile')">
                                        <q-item clickable class="rounded-borders" :active="$page.component == 'Cashier/Profile'" active-class="bg-primary text-white">
                                            <!-- <q-item-section avatar >
                                                <q-icon name="school" />
                                            </q-item-section> -->
                                            <q-item-section>
                                                <q-item-label>Profile</q-item-label>
                                                <!-- <q-item-label caption>https://quasar.dev</q-item-label> -->
                                            </q-item-section>
                                        </q-item>
                                    </Link>
                                    <Link :href="route('logout')" method="post">
                                        <!-- <q-btn icon="logout" round unelevated color="negative" flat label="flat">
                                            <q-tooltip>
                                                Logout
                                            </q-tooltip>
                                        </q-btn> -->
                                        <q-item clickable class="rounded-borders text-negative">
                                            <!-- <q-item-section avatar >
                                                <q-icon name="logout" />
                                            </q-item-section> -->
                                            <q-item-section>
                                                <q-item-label>Log out</q-item-label>
                                                <!-- <q-item-label caption>https://quasar.dev</q-item-label> -->
                                            </q-item-section>
                                        </q-item>
                                    </Link>
                                </q-menu>
                            </q-btn>
                        </q-item-section> 
                    </q-item>
                </q-list>
            </div>
            <!-- <div>
                <q-item  class="absolute-bottom bg-white">
                    <q-item-section top avatar>
                        <q-avatar color="primary" text-color="white" icon="engineering" />
                    </q-item-section>
                    <q-item-section>
                        <q-item-label>Joren Hyeung Nim</q-item-label>
                        <q-item-label caption lines="2">Administrator</q-item-label>
                    </q-item-section>
                    <q-item-section side top class="">
                        <Link :href="route('logout')" method="post">
                            <q-btn icon="logout" round unelevated color="negative" flat >
                                <q-tooltip>
                                    Logout
                                </q-tooltip>
                            </q-btn>
                        </Link>
                    </q-item-section> 
                </q-item>
            </div> -->
            
        </q-drawer>
    
        <q-page-container>
            <div class="bg-grey-3" style="height: 100vh">
                <slot/>
            </div>
        </q-page-container>
    </q-layout>
</template>

<style scoped>

a {
    text-decoration: none;
    color: black
}

</style>