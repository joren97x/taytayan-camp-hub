<script setup>

import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref } from 'vue'
import { date } from 'quasar'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    facilities: Object
})

const filter = ref('')

const columns = [
    { name: 'name', label: 'Facility', align: 'center', field: 'name', sortable: true },
    { name: 'price', align: 'center', label: 'Price', field: 'price', sortable: true },
    // { name: 'guests', align: 'center', label: 'guests', field: 'guests', sortable: true },
    // { name: 'status', align: 'center', label: 'status', field: 'status', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    
    <Head title="Reviews" />
    <div class="q-pa-md">
        <q-card bordered flat>
            <q-table
                class="my-sticky-header-column-table"
                flat
                title="Treats"
                :rows="facilities"
                :columns="columns"
                row-key="name"
                :filter="filter"
            >
                <template v-slot:body-cell-name="props">
                    <q-td :props="props" style="width: 200px;">
                        <q-item class="q-pa-none">
                            <q-item-section avatar>
                                <q-img :src="`/storage/${JSON.parse(props.row.images)[0]}`" height="60px" width="60px" class="rounded-borders" />
                            </q-item-section>
                            <q-item-section class="items-start " style="">
                                <q-item-label>{{ props.row.name }}</q-item-label>
                                <q-item-label caption class="ellipsis" style="max-width: 250px" >{{ props.row.description }}{{ props.row.description }}</q-item-label>
                            </q-item-section>
                        </q-item>
                    </q-td>
                </template>
                <!--  -->
                <template v-slot:body-cell-amenities="props">
                    <q-td :props="props">
                        <!-- {{ JSON.parse(props.row.amenities).length }} amenities -->
                    </q-td>
                </template>
                <template v-slot:body-cell-price="props">
                    <q-td :props="props">
                        P{{ props.row.price }}
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <Link :href="route(`admin.facilities.edit`, props.row.id)">
                            <q-btn no-caps unelevated>Edit</q-btn>
                        </Link>
                        <q-btn no-caps unelevated @click="showDeletesDialog(props.row)">Delete</q-btn>
                    </q-td>
                </template>
                <template v-slot:top>
                    <p class="text-h6 q-pt-md">Facilities</p>
                    <q-space />
                    <q-input rounded outlined dense label="Search..." v-model="filter" class="q-mx-md" debounce="300" color="primary">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                    <Link :href="route('admin.facilities.create')">
                        <q-btn no-caps color="primary" label="Create Facility" rounded unelevated />
                    </Link>
                </template>
            </q-table>
        </q-card>
        <!-- <q-dialog v-model="deleteProductDialog">
            <q-card>
                <q-card-section>
                    <p>Delete product</p>
                </q-card-section>
                <q-card-actions>
                    <q-space/>
                    <q-btn no-caps v-close-popup>Cancel</q-btn>
                    <q-btn 
                        no-caps
                        :loading="deleteProductForm.processing"
                        :disable="deleteProductForm.processing"
                        @click="deleteProduct"
                    >
                        Delete
                    </q-btn>
                </q-card-actions>
            </q-card>
        </q-dialog> -->
    </div>
</template>