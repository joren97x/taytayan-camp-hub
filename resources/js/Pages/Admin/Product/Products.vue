<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref } from 'vue'
import { Link, Head, useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    categories: Object,
    products: Object
})

const $q = useQuasar()
const categoryOptions = props.categories.map(category => category.name)
const category = ref('')
const filter = ref('')
const deleteProductDialog = ref(false)
const deleteProductForm = useForm({
    product: null
})

function showDeleteProductDialog(product) {
    deleteProductForm.product = product
    deleteProductDialog.value = true
}

const deleteProduct = () => {
    deleteProductForm.delete(route('admin.products.destroy', deleteProductForm.product.id), {
        onSuccess: () => {
            deleteProductDialog.value = false
            deleteProductForm.reset()
            $q.notify('Product Deleted')
        }
    })
}

const columns = [
//   { name: 'photo', label: 'Photo', align: 'center', field: 'photo', sortable: true },
  { name: 'name', align: 'center', label: 'Product', field: 'name', sortable: true },
  { name: 'price', align: 'center', label: 'Price', field: 'price', sortable: true },
//   { name: 'categories', align: 'center', label: 'Categories', field: 'categories', sortable: true },
  { name: 'available', align: 'center', label: 'Available', field: 'available', sortable: true },
  { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    
    <Head title="Products" />
    <div class="q-pa-md bg-grey-3" style="height: 100vh;">
        <!-- kung naa pa bay stock or wala na ug sa modifier group pod apili -->
        <q-card flat bordered>
            <q-table
                class="my-sticky-header-column-table"
                flat
                title="Treats"
                :rows="props.products"
                :columns="columns"
                row-key="name"
                :filter="filter"
            >
                <template v-slot:body-cell-name="props">
                    <q-td :props="props" style="width: 200px;">
                        <q-item class="q-pa-none">
                            <q-item-section avatar>
                                <q-img :src="`/storage/${props.row.photo}`" fit="contain"height="60px" width="60px" class="rounded-borders" />
                            </q-item-section>
                            <q-item-section class="items-start">
                                <q-item-label>{{ props.row.name }}</q-item-label>
                                <!-- <q-item-label caption class="ellipsis" style="max-width: 250px" >{{ props.row.description }}{{ props.row.description }}</q-item-label> -->
                            </q-item-section>
                        </q-item>
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
                        <Link :href="route(`admin.products.edit`, props.row.id)">
                            <q-btn no-caps unelevated>Edit</q-btn>
                        </Link>
                        <q-btn no-caps unelevated @click="showDeletesDialog(props.row)">Delete</q-btn>
                    </q-td>
                </template>
                <template v-slot:body-cell-available="props">
                    <q-td :props="props">
                        {{ props.row.available ? 'Yes' : 'No' }}
                    </q-td>
                </template>
                <template v-slot:top>
                    <p class="text-h6 q-pt-md">Products</p>
                    <q-space />
                    <!-- <q-select
                        style="width: 200px"
                        label="Category"
                        filled
                        dense
                        v-model="category"
                        :options="categoryOptions"
                    
                    /> -->
                    <q-input filled dense label="Search..." class="q-mx-md" debounce="300" color="primary" v-model="filter">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                    <Link :href="route('admin.products.create')">
                        <q-btn no-caps color="primary">Create Product</q-btn>
                    </Link>
                </template>
            </q-table>
        </q-card>
        <q-dialog v-model="deleteProductDialog">
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
        </q-dialog>
    </div>
</template>
