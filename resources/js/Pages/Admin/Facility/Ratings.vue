<script setup>

import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref } from 'vue'
import { useDrawerStore } from '@/Stores/DrawerStore'

defineOptions({
    layout: AdminLayout
})

defineProps({
    ratings: Object
})

const filter = ref('')
const showSearch = ref(false)
const drawerStore = useDrawerStore()
const columns = [
    { name: 'user', label: 'User', align: 'center', field: 'user', sortable: true },
    { name: 'rating', align: 'center', label: 'Rating', field: 'rating', sortable: true },
    { name: 'review', align: 'center', label: 'review', field: 'review', sortable: true },
    { name: 'created_at', align: 'center', label: 'created_at', field: 'created_at', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    
    <Head title="Reviews" />
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
        <q-card bordered flat style="border-radius: 20px">
            <q-table
                class="my-sticky-header-column-table"
                flat
                title="Treats"
                :rows="ratings"
                :columns="columns"
                row-key="name"
                :filter="filter"
            >
                <template v-slot:top>
                    <q-btn icon="menu" flat dense @click="drawerStore.drawer = true" class="lt-md q-mr-sm"/>
                    <div class="text-h6">Ratings</div>
                    <q-space />
                    <q-btn icon="search" class="q-mr-xs" round dense flat @click="showSearch = !showSearch"/>
                    
                    <Link :href="route('admin.facilities.create')">
                        <q-btn class="q-ml-sm" no-caps color="primary" rounded unelevated label="Create Facility"/>
                    </Link>
                    <div class="full-width q-mt-sm" v-if="showSearch">
                        <q-input
                            v-model="filter"
                            rounded
                            outlined
                            dense
                            label="Search using name"
                            debounce="300"
                            class="full-width"
                            color="primary"
                        >
                            <template v-slot:append>
                                <q-icon name="search" />
                            </template>
                        </q-input>
                    </div>
                </template>
                <template v-slot:body-cell-user="props">
                    <q-td :props="props">
                        {{ props.row.user.first_name + ' ' + props.row.user.last_name }}
                    </q-td>
                </template>
                <template v-slot:body-cell-rating="props">
                    <q-td :props="props">
                        {{ props.row.rating }} star
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <q-btn no-caps color="primary">Button</q-btn>
                        <q-btn no-caps color="red" icon="delete" class="q-ml-sm"></q-btn>
                    </q-td>
                </template>
                <!-- <template v-slot:top>
                    <div class="text-h6">Rating and Reviews</div>
                    <q-space />
                    <q-input outlined rounded dense label="Search..." v-model="filter" class="q-mx-md" debounce="300" color="primary">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                </template> -->
            </q-table>
        </q-card>
    </div>
</template>