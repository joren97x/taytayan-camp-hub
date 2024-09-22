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
        icon: 'restaurant',
        navigations: [
            { 
                label: 'Products', 
                value: 'products', 
                href: 'admin.products.index', 
                component: 'Admin/Product/Products',
                icon: 'restaurant'
            },
            {   
                label: 'Categories', 
                value: 'categories', 
                href: 'admin.categories.index', 
                component: 'Admin/Product/Categories' ,
                icon: 'category'
            },
            {   
                label: 'Modifier Groups', 
                value: 'modifier-groups', 
                href: 'admin.modifier_groups.index', 
                component: 'Admin/Product/ModifierGroups',
                icon: 'checklist_rtl'
            },
            {   
                label: 'Orders', 
                value: 'orders', 
                href: 'admin.orders.index', 
                component: 'Admin/Product/Orders' ,
                icon: 'shopping_cart'
            },
            {   
                label: 'Reviews', 
                value: 'reviews', 
                href: 'admin.product_ratings.index', 
                component: 'Admin/Product/Reviews' ,
                icon: 'star_rate'
            },
        ]
    },
    {
        name: 'Event Management',
        icon: 'event',
        navigations: [
            { 
                label: 'Events', 
                value: 'events', 
                href: 'admin.events.index', 
                component: 'Admin/Event/Events',
                icon: 'event'
            },
            { 
                label: 'Tickets', 
                value: 'tickets', 
                href: 'admin.tickets.index', 
                component: 'Admin/Event/Tickets',
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
                label: 'Facilities', 
                value: 'facility', 
                href: 'admin.facilities.index', 
                component: 'Admin/Facility/Facilities',
                icon: 'cottage'
            },
            { 
                label: 'Bookings', 
                value: 'bookings', 
                href: 'admin.bookings.index', 
                component: 'Admin/Facility/Bookings',
                icon: 'description'
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
            <q-img class="absolute-top" src="https://scontent.fmnl13-1.fna.fbcdn.net/v/t39.30808-6/308874170_458509852988758_1991570530264924406_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=cc71e4&_nc_eui2=AeEDYpGRK6JlvIKI9-sqqS_LETWDJ4miOCcRNYMniaI4J1eUGMw6jKrBkeeEX4msmRNehdwFLxJlrr9bQFpQLG0u&_nc_ohc=U9JIJmNsznYQ7kNvgGRvuBC&_nc_ht=scontent.fmnl13-1.fna&oh=00_AYCzG2-RVtodN2Bh16NKBOZ9lpllUDtwvGUVkjnHwW4qEQ&oe=66F29B29" style="height: 150px">
                    <q-item  class="absolute-bottom">
                        <q-item-section avatar>
                            <q-avatar color="primary" text-color="white">
                                <q-img src="https://scontent.fmnl13-1.fna.fbcdn.net/v/t39.30808-6/234914753_203423238467635_2256368527109143594_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=6ee11a&_nc_eui2=AeFHQZdJ1dT9kUKF9GufPVxJK4ptG1KWT7crim0bUpZPtz_CH6huv9s-JWZaChUxqwFe8pE0XjgPTeQunTOIh67n&_nc_ohc=_ExTeK8E-_sQ7kNvgFAMa-M&_nc_ht=scontent.fmnl13-1.fna&oh=00_AYDLa4QiIYyM9iS8OtJywdAKBLtxXuaBN5RHCEUumO07LQ&oe=66F291E3"></q-img>
                            </q-avatar>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label class="text-subtitle1 text-weight-bold">Taytayan Camp Hub</q-item-label>
                        </q-item-section>

                         <!-- <q-item-section side top>
                            <q-btn 
                                round
                                :icon="$q.dark.isActive ? 'dark_mode' : 'light_mode'" 
                                color="white" 
                                class="text-black" 
                                @click="$q.dark.toggle"
                            />
                        </q-item-section>  -->
                    </q-item>
            </q-img>
            <!-- drawer content -->
            <q-scroll-area style="height: calc(100% - 150px); margin-top: 150px; border-right: 1px solid #ddd;">
                <q-list class="q-mx-sm q-mt-md" style="margin-bottom: 80px">
                    <q-item-label header class="q-pa-none q-px-md q-py-xs">Management</q-item-label>
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
                    <q-expansion-item 
                        :icon="navigation.icon" 
                        :label="navigation.name" 
                        v-for="navigation in navigation_list" 
                        class="rounded-borders"
                    >
                        <q-card class="q-mx-md">
                            <Link :href="route(nav.href)" v-for="nav in navigation.navigations">
                                <q-item clickable v-ripple class="rounded-borders" :active="$page.component == nav.component" active-class="bg-primary text-white">
                                    <q-item-section avatar>
                                        <q-icon :name="nav.icon" />
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
                            <Link :href="route(`admin.users.index`)">
                                <q-item clickable v-ripple >
                                    <q-item-section avatar>
                                        <q-icon name="people" />
                                    </q-item-section>
                                    <q-item-section class="text-capitalize">
                                        <!-- {{ user_role }} -->
                                        User Management
                                    </q-item-section>
                                </q-item>
                            </Link>
                    <q-item-label header class="q-pa-none q-px-md q-py-xs">Communications</q-item-label>
                    <Link :href="route('admin.conversation.index')">
                        <q-item clickable v-ripple>
                            <q-item-section avatar>
                                <q-icon name="drafts" />
                            </q-item-section>
                            <q-item-section>
                                Messages
                            </q-item-section>
                        </q-item>
                    </Link>
                    <q-item-label header class="q-pa-none q-px-md q-py-xs">Settings</q-item-label>
                    <Link :href="route('admin.profile')">
                        <q-item clickable v-ripple>
                            <q-item-section avatar>
                                <q-icon name="drafts" />
                            </q-item-section>
                            <q-item-section>
                                Profile
                            </q-item-section>
                        </q-item>
                    </Link>
                    <q-item clickable v-ripple  @click="$q.dark.toggle">
                            <q-item-section avatar>
                                <q-icon name="drafts" />
                            </q-item-section>
                            <q-item-section>
                                Dark Mode
                            </q-item-section>
                        <q-item-section side top>
                                <q-toggle v-model="$q.dark.isActive"></q-toggle>
                            </q-item-section>
                        </q-item>
                        
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
    color: black
}

</style>