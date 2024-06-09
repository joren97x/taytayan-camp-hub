<script setup>

import ProductLayout from '@/Layouts/ProductLayout.vue'
import { ref } from 'vue'
import { Link, Head } from '@inertiajs/vue3';

defineOptions({
    layout: ProductLayout
})

const props = defineProps({
    categories: Object,
    products: Object
})

const categoryOptions = props.categories.map(category => category.name)
const category = ref('')

const columns = [
  { name: 'photo', label: 'Photo', align: 'center', field: 'photo', sortable: true },
  { name: 'name', align: 'center', label: 'Name', field: 'name', sortable: true },
  { name: 'price', align: 'center', label: 'Price', field: 'price', sortable: true },
  { name: 'categories', align: 'center', label: 'Categories', field: 'categories', sortable: true },
  { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    
    <Head title="Products" />
    <div class="q-pa-md">
        <q-table
            class="my-sticky-header-column-table"
            flat
            title="Treats"
            :rows="props.products"
            :columns="columns"
            row-key="name"
        >
            <template v-slot:body-cell-photo="props">
                <q-td :props="props">
                    <q-img :src="`/images/${props.row.photo}`" style="width: 50px; height: 50px;" />
                </q-td>
            </template>
            <template v-slot:body-cell-price="props">
                <q-td :props="props">
                    P{{ props.row.price }}
                </q-td>
            </template>
            <template v-slot:body-cell-categories="props">
                <q-td :props="props">
                    <span v-for="category in props.row.categories" :key="category.id">
                        {{ category.name + ', ' }}
                    </span>
                </q-td>
            </template>
            <template v-slot:body-cell-actions="props">
                <q-td :props="props">
                    <Link :href="route(`admin.product.edit`, props.row.id)">
                        <q-btn no-caps unelevated>Edit</q-btn>
                    </Link>
                    <q-btn no-caps unelevated>Delete</q-btn>
                </q-td>
            </template>
            <template v-slot:top>
                <p class="text-h6 q-pt-md">Products</p>
                <q-space />
                <q-select
                    style="width: 200px"
                    label="Category"
                    filled
                    dense
                    v-model="category"
                    :options="categoryOptions"
                />
                <q-input filled dense label="Search..." class="q-mx-md" debounce="300" color="primary" v-model="filter">
                    <template v-slot:append>
                        <q-icon name="search" />
                    </template>
                </q-input>
                <Link :href="route('admin.product.create')">
                    <q-btn no-caps color="primary">New Product</q-btn>
                </Link>
            </template>
        </q-table>
    </div>
</template>
