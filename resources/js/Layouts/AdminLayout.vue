<script setup>

import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'

const leftDrawerOpen = ref(false)

</script>


<template>
    <q-layout view="lHh LpR lFf">
        <q-drawer show-if-above v-model="leftDrawerOpen" side="left" bordered>
            <q-img class="absolute-top" src="https://cdn.quasar.dev/img/material.png" style="height: 150px">
                <div class="absolute-bottom bg-transparent">
                    <q-avatar size="56px" class="q-mb-sm">
                        <img src="https://cdn.quasar.dev/img/boy-avatar.png">
                    </q-avatar>
                    <div class="text-weight-bold">Razvan Stoenescu</div>
                    <div>@rstoenescu</div>
                </div>
            </q-img>
            <!-- drawer content -->
            <q-scroll-area style="height: calc(100% - 150px); margin-top: 150px; border-right: 1px solid #ddd">
                <q-list>
                    <q-item clickable v-ripple>
                        <q-item-section avatar>
                            <q-icon name="dashboard" />
                        </q-item-section>
                        <q-item-section>
                            Dashboard
                        </q-item-section>
                    </q-item>
                    <q-expansion-item icon="local_cafe" label="Milkteas">
                        <q-card class="q-mx-md">
                            <Link :href="route('admin.milktea.index')">
                                <q-item clickable v-ripple>
                                    <q-item-section avatar>
                                        <q-icon name="star" />
                                    </q-item-section>
                                    <q-item-section>
                                        Milktea
                                    </q-item-section>
                                </q-item>
                            </Link>
                            <Link :href="route('admin.orders')">
                                <q-item clickable v-ripple>
                                    <q-item-section avatar>
                                        <q-icon name="star" />
                                    </q-item-section>
                                    <q-item-section>
                                        Orders
                                    </q-item-section>
                                </q-item>
                            </Link>
                            <Link :href="route('admin.group_modifiers')">
                                <q-item clickable v-ripple>
                                    <q-item-section avatar>
                                        <q-icon name="star" />
                                    </q-item-section>
                                    <q-item-section>
                                        Group Modifiers
                                    </q-item-section>
                                </q-item>
                            </Link>
                            <Link :href="route('admin.milktea.reviews')">
                                <q-item clickable v-ripple>
                                    <q-item-section avatar>
                                        <q-icon name="star" />
                                    </q-item-section>
                                    <q-item-section>
                                        Reviews
                                    </q-item-section>
                                </q-item>
                            </Link>
                        </q-card>
                    </q-expansion-item>
                    <q-expansion-item icon="event" label="Events">
                        <q-card class="q-mx-md">
                            <q-item clickable v-ripple v-for="n in 3" :key="n">
                                <q-item-section avatar>
                                    <q-icon name="star" />
                                </q-item-section>
                                <q-item-section>
                                    Dashboard
                                </q-item-section>
                            </q-item>
                        </q-card>
                    </q-expansion-item>
                    <q-expansion-item icon="holiday_village" label="Campsites">
                        <q-card class="q-mx-md">
                            <q-item clickable v-ripple v-for="n in 3" :key="n">
                                <q-item-section avatar>
                                    <q-icon name="star" />
                                </q-item-section>
                                <q-item-section>
                                    Dashboard
                                </q-item-section>
                            </q-item>
                        </q-card>
                    </q-expansion-item>
                    <q-item clickable v-ripple>
                        <q-item-section avatar>
                            <q-icon name="send" />
                        </q-item-section>
                        <q-item-section>
                            User Management
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