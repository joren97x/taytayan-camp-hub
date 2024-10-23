<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref } from 'vue'
import { Link, Head, useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
// import DeleteProductDialog from './Partials/DeleteProductDialog.vue'

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
        <q-card flat bordered style="border-radius: 20px">
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
                            <q-btn no-caps unelevated rounded>Edit</q-btn>
                        </Link>
                        <!-- <DeleteProductDialog :product="props.row" /> -->
                        <q-btn no-caps unelevated color="negative" flat rounded @click="showDeleteProductDialog(props.row)">Delete</q-btn>
                    </q-td>
                </template>
                <template v-slot:body-cell-available="props">
                    <q-td :props="props">
                        {{ props.row.available ? 'Yes' : 'No' }}
                    </q-td>
                </template>
                <template v-slot:top>
                    <div class="text-h6">Products</div>
                    <q-space />
                    <!-- <q-select
                        style="width: 200px"
                        label="Category"
                        filled
                        dense
                        v-model="category"
                        :options="categoryOptions"
                    
                    /> -->
                    <q-input dense  label="Search..." class="q-mx-md" rounded outlined debounce="300" color="primary" v-model="filter">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                    <Link :href="route('admin.products.create')">
                        <q-btn no-caps color="primary" rounded label="Create Product" unelevated/>
                    </Link>
                </template>
            </q-table>
        </q-card>
        <q-dialog 
            v-model="deleteProductDialog" 
            persistent 
            :maximized="$q.screen.lt.md"
            transition-show="slide-up"
            transition-hide="slide-down"
        >
            <q-card :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
                <q-card-section class="row items-center q-pb-none">
                    <q-icon name="warning" color="negative" size="32px" />
                    <div class="text-h6 q-ml-md">Delete Product</div>
                    <q-btn round icon="close" v-close-popup flat class="absolute-top-right q-mt-sm q-mr-sm"/>

                </q-card-section>
                <q-card-section>
                    Are you sure you want to delete this product? All data will be permanently removed. This action cannot be undone.
                    <q-item class="bg-negative text-white q-my-md">
                        <q-item-section avatar>
                            <q-img :src="`/storage/${deleteProductForm.product.photo}`" height="100px" width="100px"></q-img>
                        </q-item-section>  
                        <q-item-section>
                            <q-item-label class="text-weight-bold text-subtitle1">{{ deleteProductForm.product.name }}</q-item-label>
                            <q-item-label >{{ deleteProductForm.product.description }}</q-item-label>
                            <q-item-label >{{ deleteProductForm.product.price }}</q-item-label>
                        </q-item-section>
                    </q-item>
                </q-card-section>
            
                <q-card-actions align="right">
                    <q-btn flat label="Cancel" rounded no-caps color="grey-7" v-close-popup />
                    <q-btn 
                        label="Delete" 
                        rounded 
                        no-caps 
                        unelevated 
                        color="negative"
                        @click="deleteProduct" 
                        :loading="deleteProductForm.processing"
                        :disable="deleteProductForm.processing"
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>
