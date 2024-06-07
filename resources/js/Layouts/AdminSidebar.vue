<script setup>

import { Link } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3'

const leftDrawerOpen = ref(false)
const user_roles = ref([])

onMounted(() => {
    axios.get(route('admin.get_user_roles'))
    .then((res) => {
        res.data.user_roles.forEach(element => {
            user_roles.value.push(element)
        });
        console.log(res)
    })
    .catch((err) => {
        console.error(err)
    })
})

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

                        <!-- <q-item-section side top>
                            <Link :href="route('logout')" method="post">
                                <q-btn icon="logout" round flat color="red">
                                    <q-tooltip>Logout</q-tooltip>
                                </q-btn>
                            </Link>
                        </q-item-section> -->
                    </q-item>
            </q-img>
            <!-- drawer content -->
            <q-scroll-area style="height: calc(100% - 150px); margin-top: 150px; border-right: 1px solid #ddd">
                <q-list>
                    <Link :href="route('admin.dashboard')">
                        <q-item clickable v-ripple>
                            <q-item-section avatar>
                                <q-icon name="dashboard" />
                            </q-item-section>
                            <q-item-section>
                                Dashboard
                            </q-item-section>
                        </q-item>
                    </Link>
                    <Link :href="route('admin.product.index')">
                        <q-item clickable v-ripple>
                            <q-item-section avatar>
                                <q-icon name="star" />
                            </q-item-section>
                            <q-item-section>
                                Products
                            </q-item-section>
                        </q-item>
                    </Link>
                    <Link :href="route('admin.product.index')">
                        <q-item clickable v-ripple>
                            <q-item-section avatar>
                                <q-icon name="star" />
                            </q-item-section>
                            <q-item-section>
                                Events
                            </q-item-section>
                        </q-item>
                    </Link>
                    <Link :href="route('admin.product.index')">
                        <q-item clickable v-ripple>
                            <q-item-section avatar>
                                <q-icon name="star" />
                            </q-item-section>
                            <q-item-section>
                                Camp
                            </q-item-section>
                        </q-item>
                    </Link>
                    <q-expansion-item icon="event" label="User Management">
                        <q-card class="q-mx-md">
                            <Link :href="route(`admin.user.index`, user_role)" v-for="(user_role, index) in user_roles" :key="index">
                                <q-item clickable v-ripple >
                                    <q-item-section avatar>
                                        <q-icon name="star" />
                                    </q-item-section>
                                    <q-item-section class="text-capitalize">
                                        {{ user_role }}
                                    </q-item-section>
                                </q-item>
                            </Link>
                        </q-card>
                    </q-expansion-item>
                    <!-- <Link :href="route('admin.user.index')">
                        <q-expansion-item caption="User Management">
                            <q-item>
                                <q-item-section avatar>
                                    <q-icon name="send" />
                                </q-item-section>
                                <q-item-section>
                                    User Management
                                </q-item-section>
                            </q-item>
                        </q-expansion-item>
                    </Link> -->
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
                            Etc etc
                        </q-item-section>
                    </q-item>
                </q-list>
            </q-scroll-area>
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