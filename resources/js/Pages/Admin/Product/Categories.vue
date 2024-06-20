<script setup>

import ProductLayout from '@/Layouts/ProductLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'

defineOptions({
    layout: ProductLayout
})

const $q = useQuasar()
const props = defineProps({
    categories: Object
})

const deleteCategoryForm = useForm({
    category: null
})
const deleteCategoryDialog = ref(false)

function showDeleteCategoryDialog(category) {
    deleteCategoryForm.category = category
    deleteCategoryDialog.value = true
}

const deleteCategory = () => {
    deleteCategoryForm.delete(route('admin.category.destroy', deleteCategoryForm.category.id), {
        onSuccess: () => {
            deleteCategoryDialog.value = false
            deleteCategoryForm.category = null
            $q.notify('Category Deleted')
        }
    })
}

const columns = [
    { name: 'name', label: 'Name', align: 'center', field: 'name', sortable: true },
    { name: 'items', align: 'center', label: 'Items', field: 'items', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    
    <Head title="Categories" />
        <div class="q-pa-md">
            <q-table
                class="my-sticky-header-column-table"
                flat
                title="Treats"
                :rows="props.categories"
                :columns="columns"
                row-key="name"
            >
                <template v-slot:body-cell-items="props">
                    <q-td :props="props">
                        <span v-for="product in props.row.products" :key="product.id">
                            {{ product.name + ', ' }}
                        </span>
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <q-btn no-caps unelevated>Edit</q-btn>
                        <q-btn no-caps unelevated @click="showDeleteCategoryDialog(props.row)">Delete</q-btn>
                    </q-td>
                </template>
                <template v-slot:top>
                    <p class="text-h6 q-pt-md">Categories</p>
                    <q-space />
                        <q-input filled dense label="Search..." debounce="300" color="primary" v-model="filter">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                    <Link :href="route('admin.category.create')">
                        <q-btn class="q-ml-sm" color="primary" no-caps label="New Category" />
                    </Link>
                </template>
            </q-table>
            <q-dialog v-model="deleteCategoryDialog">
                <q-card>
                    <q-card-section>
                        Delete category?
                    </q-card-section>
                    <q-card-actions>
                        <q-btn v-close-popup>Cancel</q-btn>
                        <q-btn 
                            :disable="deleteCategoryForm.processing"
                            :loading="deleteCategoryForm.processing"
                            @click="deleteCategory"
                        >
                            Delete
                        </q-btn>
                    </q-card-actions>
                </q-card>
            </q-dialog>
        </div>
</template>
