<script setup>

import { Head, Link } from '@inertiajs/vue3'
import FacilityLayout from '@/Layouts/FacilityLayout.vue'

defineOptions({
    layout: FacilityLayout
})

defineProps({
    facilities: Object
})

</script>

<template>
    
    <Head title="Reviews" />
    <h1>Facilities</h1>
    <div class="q-pa-md">
        <q-table
            class="my-sticky-header-column-table"
            flat
            title="Treats"
            :rows="facilities"
            :columns="columns"
            row-key="name"
        >
            <template v-slot:body-cell-images="props">
                <q-td :props="props">
                    {{ props.row.images }}
                    <!-- <q-img :src="`/storage/${props.row.images}`" style="width: 50px; height: 50px;" /> -->
                </q-td>
            </template>
            <template v-slot:body-cell-price="props">
                <q-td :props="props">
                    P{{ props.row.price }}
                </q-td>
            </template>
            <template v-slot:body-cell-actions="props">
                <q-td :props="props">
                    <Link :href="route(`admin.products.edit`, props.row.id)">
                        <q-btn no-caps unelevated>Edit</q-btn>
                    </Link>
                    <q-btn no-caps unelevated @click="showDeletesDialog(props.row)">Delete</q-btn>
                </q-td>
            </template>
            <template v-slot:top>
                <p class="text-h6 q-pt-md">Facilities</p>
                <q-space />
                <q-select
                    style="width: 200px"
                    label="Category"
                    filled
                    dense
                />
                <q-input filled dense label="Search..." class="q-mx-md" debounce="300" color="primary">
                    <template v-slot:append>
                        <q-icon name="search" />
                    </template>
                </q-input>
                <Link :href="route('admin.facilities.create')">
                    <q-btn no-caps color="primary">Create Facility</q-btn>
                </Link>
            </template>
        </q-table>
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