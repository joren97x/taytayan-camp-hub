<script setup>

import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref } from 'vue'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    orders: Object
})

const filter = ref('')

const columns = [
    { name: 'user', label: 'User', align: 'center', field: 'user', sortable: true },
    { name: 'cart_products', align: 'center', label: 'Items', field: 'cart_products', sortable: true },
    { name: 'subtotal', align: 'center', label: 'subtotal', field: 'subtotal', sortable: true },
    { name: 'mode', align: 'center', label: 'mode', field: 'mode', sortable: true },
    { name: 'created_at', align: 'center', label: 'created_at', field: 'created_at', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    
    <Head title="Reviews" />
    <div class="q-pa-md">
        <q-card flat bordered>
            <q-table
                class="my-sticky-header-column-table"
                flat
                title="Treats"
                :rows="orders"
                :columns="columns"
                row-key="name"
                :filter="filter"
            >
                <template v-slot:body-cell-user="props">
                    <q-td :props="props">
                        {{ props.row.user.first_name + ' ' + props.row.user.last_name }}
                    </q-td>
                </template>
                <template v-slot:body-cell-cart_products="props">
                    <q-td :props="props">
                        {{ props.row.cart_products.length }} items
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <q-btn no-caps color="primary">View Order</q-btn>
                    </q-td>
                </template>
                <template v-slot:top>
                    <p class="text-h6 q-pt-md">Orders</p>
                    <q-space />
                    <q-input filled dense label="Search..." v-model="filter" class="q-mx-md" debounce="300" color="primary">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
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