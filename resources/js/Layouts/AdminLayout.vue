<script setup>

import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useQuasar } from 'quasar'

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
        navigations: [
            { 
                label: 'Products', 
                value: 'products', 
                href: 'admin.products.index', 
                component: 'Admin/Product/Products',
                icon: ''
            },
            {   
                label: 'Categories', 
                value: 'categories', 
                href: 'admin.categories.index', 
                component: 'Admin/Product/Categories' ,
                icon: ''
            },
            {   
                label: 'Modifier Groups', 
                value: 'modifier-groups', 
                href: 'admin.modifier_groups.index', 
                component: 'Admin/Product/ModifierGroups',
                icon: ''
            },
            {   
                label: 'Orders', 
                value: 'orders', 
                href: 'admin.orders.index', 
                component: 'Admin/Product/Orders' ,
                icon: ''
            },
            {   
                label: 'Reviews', 
                value: 'reviews', 
                href: 'admin.product_ratings.index', 
                component: 'Admin/Product/Reviews' ,
                icon: ''
            },
        ]
    },
    {
        name: 'Event Management',
        navigations: [
            { 
                label: 'Events', 
                value: 'events', 
                href: 'admin.events.index', 
                component: 'Admin/Event/Events',
                icon: ''
            },
            { 
                label: 'Tickets', 
                value: 'tickets', 
                href: 'admin.tickets.index', 
                component: 'Admin/Event/Tickets',
                icon: ''
            },
            { 
                label: 'Reviews', 
                value: 'reviews', 
                href: 'admin.events.reviews', 
                component: 'Admin/Event/Reviews',
                icon: ''
            },
        ]
    },
    {
        name: 'Facility Management',
        navigations: [
            { 
                label: 'Facilities', 
                value: 'facility', 
                href: 'admin.facilities.index', 
                component: 'Admin/Facility/Facilities',
                icon: ''
            },
            { 
                label: 'Bookings', 
                value: 'bookings', 
                href: 'admin.bookings.index', 
                component: 'Admin/Facility/Bookings',
                icon: ''
            },
            // { 
            //     label: 'Reviews', 
            //     value: 'reviews', 
            //     href: 'admin.facilities.reviews', 
            //     component: 'Admin/Facility/Reviews',
            //     icon: ''
            // },
        ]
    },

]

</script>


<template>
    <q-layout view="lHh LpR lFf">
        <q-drawer show-if-above v-model="leftDrawerOpen" side="left" bordered>
            <q-img class="absolute-top" src="https://cdn.quasar.dev/img/material.png" style="height: 150px">
                    <q-item  class="absolute-bottom">
                        <q-item-section top avatar>
                            <q-avatar color="primary" text-color="white" icon="bluetooth" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>Joren Hyeung Nim</q-item-label>
                            <q-item-label caption lines="2" class="text-white">Administrator</q-item-label>
                        </q-item-section>

                         <q-item-section side top>
                            <q-btn 
                                round
                                :icon="$q.dark.isActive ? 'dark_mode' : 'light_mode'" 
                                color="white" 
                                class="text-black" 
                                @click="$q.dark.toggle"
                            />
                        </q-item-section> 
                    </q-item>
            </q-img>
            <!-- drawer content -->
            <q-scroll-area style="height: calc(100% - 150px); margin-top: 150px; border-right: 1px solid #ddd">
                <q-list class="q-mx-sm q-mt-md">
                    <Link :href="route('admin.dashboard')">
                        <q-item clickable v-ripple class="rounded-borders" :active="$page.component == 'Admin/Dashboard'" active-class="bg-primary text-white">
                            <q-item-section avatar>
                                <q-icon name="dashboard" />
                            </q-item-section>
                            <q-item-section>
                                Dashboard
                            </q-item-section>
                        </q-item>
                    </Link>
                    <q-expansion-item icon="supervisor_account" :label="navigation.name" v-for="navigation in navigation_list" class="rounded-borders">
                        <q-card class="q-mx-md">
                            <Link :href="route(nav.href)" v-for="nav in navigation.navigations">
                                <q-item clickable v-ripple class="rounded-borders" :active="$page.component == nav.component" active-class="bg-primary text-white">
                                    <q-item-section avatar>
                                        <q-icon name="star" />
                                    </q-item-section>
                                    <q-item-section>
                                        {{ nav.label }}
                                    </q-item-section>
                                </q-item>
                            </Link>
                        </q-card>
                    </q-expansion-item>
                    
                    <!-- <Link :href="route('admin.events.index')">
                        <q-item clickable v-ripple>
                            <q-item-section avatar>
                                <q-icon name="star" />
                            </q-item-section>
                            <q-item-section>
                                Events
                            </q-item-section>
                        </q-item>
                    </Link>
                    <Link :href="route('admin.facilities.index')">
                        <q-item clickable v-ripple>
                            <q-item-section avatar>
                                <q-icon name="star" />
                            </q-item-section>
                            <q-item-section>
                                Facilities
                            </q-item-section>
                        </q-item>
                    </Link> -->
                    <!-- <q-expansion-item icon="supervisor_account" label="User Management">
                        <q-card class="q-mx-md"> -->
                            <Link :href="route(`admin.user_management`)">
                                <q-item clickable v-ripple >
                                    <q-item-section avatar>
                                        <q-icon name="star" />
                                    </q-item-section>
                                    <q-item-section class="text-capitalize">
                                        <!-- {{ user_role }} -->
                                        User Management
                                    </q-item-section>
                                </q-item>
                            </Link>
                    <q-item clickable v-ripple>
                        <q-item-section avatar>
                            <q-icon name="drafts" />
                        </q-item-section>
                        <q-item-section>
                            Etc etc
                        </q-item-section>
                    </q-item>
                    <q-item clickable v-ripple>
                        <q-item-section avatar>
                            <q-icon name="drafts" />
                        </q-item-section>
                        <q-item-section>
                            Unsa pa akong e butang ari
                        </q-item-section>
                    </q-item>
                    <Link :href="route('admin.conversation.index')">
                        <q-item clickable v-ripple>
                            <q-item-section avatar>
                                <q-icon name="drafts" />
                            </q-item-section>
                            <q-item-section>
                                Inbox
                            </q-item-section>
                        </q-item>
                    </Link>
                </q-list>
            </q-scroll-area>
            <div>
                <q-item  class="absolute-bottom bg-white">
                    <q-item-section top avatar>
                        <q-avatar color="primary" text-color="white" icon="bluetooth" />
                    </q-item-section>
                    <q-item-section>
                        <q-item-label>Joren Hyeung Nim</q-item-label>
                        <q-item-label caption lines="2">Administrator</q-item-label>
                    </q-item-section>

                        <q-item-section side top>
                        <q-btn 
                            round
                            icon="unfold_more"
                            color="white" 
                            class="text-black" 
                        >
                            <q-menu>
                                <q-list>
                                    <q-item
                                        round
                                        color="white" 
                                        class="text-black" 
                                        clickable
                                        @click="$q.dark.toggle"
                                    >
                                        <q-item-section>
                                            Dark Mode
                                        </q-item-section>
                                        <q-item-section avatar>
                                            <q-icon color="primary" :name="$q.dark.isActive ? 'dark_mode' : 'light_mode'" />
                                        </q-item-section>
                                    </q-item>
                                    <Link :href="route('logout')" method="post">
                                        <q-item clickable>
                                            <q-item-section>
                                                Logout
                                            </q-item-section>
                                            <q-item-section avatar>
                                                <q-icon color="primary" name="logout" />
                                            </q-item-section>
                                        </q-item>
                                    </Link>
                                </q-list>
                            </q-menu>
                        </q-btn>
                    </q-item-section> 
                </q-item>
            </div>
            
        </q-drawer>
    
        <q-page-container>
            <slot/>
        </q-page-container>
    </q-layout>
</template>

<style scoped>

a {
    text-decoration: none;
}

</style>