<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'

defineOptions({
    layout: AdminLayout
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
    deleteCategoryForm.delete(route('admin.categories.destroy', deleteCategoryForm.category.id), {
        onSuccess: () => {
            deleteCategoryDialog.value = false
            deleteCategoryForm.category = null
            $q.notify('Category Deleted')
        }
    })
}

const editCategoryNameForm = useForm({
    id: null,
    name: ''
})

function showEditCategoryNameMenu(category) {
    editCategoryNameForm.name = category.name
    editCategoryNameForm.id = category.id
}

const updateCategoryName = () => {
    editCategoryNameForm.put(route('admin.categories.update', editCategoryNameForm.id), {
        onSuccess: () => {
            $q.notify('Category Name Updated')
        }
    })
}

const columns = [
    { name: 'name', label: 'Name', align: 'center', field: 'name', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    
    <Head title="Categories" />
        <div class="q-pa-md">
            <q-card bordered flat>
                <q-table
                    class="my-sticky-header-column-table"
                    flat
                    title="Treats"
                    :rows="props.categories"
                    :columns="columns"
                    row-key="name"
                >
                <template v-slot:body-cell-name="props">
                        <q-td :props="props">
                            {{ props.row.name }}
                            <q-btn color="primary" size="sm" flat icon="edit" @click="showEditCategoryNameMenu(props.row)">
                                <q-menu persistent>
                                    <q-card bordered>
                                        <q-form @submit.prevent="updateCategoryName()">
                                            <q-card-section>
                                                <div class="q-mb-sm">Edit Category Name</div>
                                                <q-input filled v-model="editCategoryNameForm.name"/>
                                            </q-card-section>
                                            <q-card-actions>
                                                <q-btn no-caps>Cancel</q-btn>
                                                <q-btn 
                                                    no-caps color="blue" 
                                                    :disable="editCategoryNameForm.processing"
                                                    :loading="editCategoryNameForm.processing"
                                                    type="submit"
                                                >
                                                    Save
                                                </q-btn>
                                            </q-card-actions>
                                        </q-form>
                                    </q-card>
                                </q-menu>
                                </q-btn>
                        </q-td>
                    </template>
                    <!-- <template v-slot:body-cell-items="props">
                        <q-td :props="props">
                            <span v-for="product in props.row.products" :key="product.id">
                                {{ product.name + ', ' }}
                            </span>
                        </q-td>
                    </template> -->
                    <template v-slot:body-cell-actions="props">
                        <q-td :props="props">
                            <q-btn no-caps unelevated @click="showDeleteCategoryDialog(props.row)" label="Delete"/>
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
                        <Link :href="route('admin.categories.create')">
                            <q-btn class="q-ml-sm" color="primary" no-caps label="Create Category" />
                        </Link>
                    </template>
                </q-table>
            </q-card>
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
