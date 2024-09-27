<script setup>

import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref } from 'vue'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    facilities: Object
})

const filter = ref('')

const columns = [
    { name: 'facility', label: 'Facility', align: 'center', field: 'name', sortable: true },
    { name: 'description', align: 'center', label: 'description', field: 'description', sortable: true },
    { name: 'price', align: 'center', label: 'price', field: 'price', sortable: true },
    { name: 'images', align: 'center', label: 'images', field: 'images', sortable: true },
    { name: 'amenities', align: 'center', label: 'amenities', field: 'amenities', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    
    <Head title="Reviews" />
    <div class="q-pa-md bg-grey-3">
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
                <template v-slot:body-cell-images="props">
                    <q-td :props="props">
                        {{ JSON.parse(props.row.images).length }} images
                        <!-- <q-img :src="`/storage/${props.row.images}`" style="width: 50px; height: 50px;" /> -->
                    </q-td>
                </template>
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
                            {{ props.row.id }}
                            <q-btn no-caps unelevated>Edit</q-btn>
                        </Link>
                        <q-btn no-caps unelevated @click="showDeletesDialog(props.row)">Delete</q-btn>
                    </q-td>
                </template>
                <template v-slot:top>
                    <p class="text-h6 q-pt-md">Facilities</p>
                    <q-space />
                    <q-input filled dense label="Search..." v-model="filter" class="q-mx-md" debounce="300" color="primary">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                    <Link :href="route('admin.facilities.create')">
                        <q-btn no-caps color="primary">Create Facility</q-btn>
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