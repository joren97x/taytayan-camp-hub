<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import MilkteaLayout from '@/Layouts/ProductLayout.vue'
import { ref } from 'vue'
import { Link, Head } from '@inertiajs/vue3';

defineOptions({
    layout: AdminLayout
})

const category = ref('')

const columns = [
  { name: 'photo', label: 'Photo', align: 'center', field: 'photo', sortable: true },
  { name: 'name', align: 'center', label: 'Name', field: 'name', sortable: true },
  { name: 'price', align: 'center', label: 'Price', field: 'price', sortable: true },
  { name: 'category', align: 'center', label: 'Category', field: 'category', sortable: true },
  { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

const rows = [
  {
    name: 'Matcha Milktea',
    photo: 'https://cdn.quasar.dev/img/chicken-salad.jpg',
    price: '30.00',
    category: 'Milktea',
    actions: 'still figuring out how'
  },
  {
    name: 'Chocolate Milktea',
    photo: 'https://cdn.quasar.dev/img/chicken-salad.jpg',
    price: '40.00',
    category: 'Some uhh',
    actions: 'still figuring out how'
  },
]

</script>

<template>
    
    <Head title="Milkteas" />
    <MilkteaLayout>
        <div class="q-pa-md">
            <q-table
                class="my-sticky-header-column-table"
                flat
                title="Treats"
                :rows="rows"
                :columns="columns"
                row-key="name"
            >
                <template v-slot:body-cell-photo="props">
                    <q-td :props="props">
                        <q-img :src="props.row.photo" style="width: 50px; height: 50px;" />
                    </q-td>
                </template>
                <template v-slot:body-cell-price="props">
                    <q-td :props="props">
                        P{{ props.row.price }}
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <q-btn no-caps unelevated>Edit</q-btn>
                        <q-btn no-caps unelevated>Delete</q-btn>
                    </q-td>
                </template>
                <template v-slot:top>
                    <p class="text-h6 q-pt-md">Milkteas</p>
                    <q-space />
                    <q-select
                        style="width: 200px"
                        label="Category"
                        filled
                        dense
                        v-model="category"
                        :options="[
                            'Milkteas',
                            'Drinks',
                            'Or some uhh',
                            'wateva'
                        ]"
                    />
                    <q-input filled dense label="Search..." class="q-mx-md" debounce="300" color="primary" v-model="filter">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                    <Link :href="route('admin.product.create')">
                        <q-btn no-caps color="primary">Add new item</q-btn>
                    </Link>
                </template>
            </q-table>
        </div>
    </MilkteaLayout>
</template>
