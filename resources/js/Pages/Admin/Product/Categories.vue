<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { useDrawerStore } from '@/Stores/DrawerStore'

defineOptions({
    layout: AdminLayout
})

const drawerStore = useDrawerStore()
const showSearch = ref(false)
const filter = ref('')
const $q = useQuasar()
const props = defineProps({
    categories: Object
})

const deleteCategoryForm = useForm({
    category: null
})
const deleteCategoryDialog = ref(false)
const showCategoryProductsDialog = ref(false)
const currentCategory = ref(null)

const showDeleteCategoryDialog = (category) => {
    deleteCategoryForm.category = category
    deleteCategoryDialog.value = true
}

const showCategoryProducts = (category) => {
    showCategoryProductsDialog.value = true
    currentCategory.value = category
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
    { name: 'items', label: 'Items', align: 'center', field: 'items', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    
    <Head title="Categories" />
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
        <q-card bordered flat>
            <q-table
                class="my-sticky-header-column-table"
                flat
                title="Treats"
                :rows="props.categories"
                :columns="columns"
                row-key="name"
                :grid="$q.screen.lt.md"
                :filter="filter"
            >
                <template v-slot:top>
                    <q-btn icon="menu" flat dense @click="drawerStore.drawer = true" class="lt-md q-mr-sm"/>
                    <div class="text-h6">Categories</div>
                    <q-space />
                    <q-btn icon="search" class="q-mr-xs" round dense flat @click="showSearch = !showSearch"/>
                    
                    <Link :href="route('admin.categories.create')">
                        <q-btn class="q-ml-sm" no-caps color="primary" rounded unelevated label="Create Category"/>
                    </Link>
                    <div class="full-width q-mt-sm" v-if="showSearch">
                        <q-input
                            v-model="filter"
                            rounded
                            outlined
                            dense
                            label="Search using name"
                            debounce="300"
                            class="full-width"
                            color="primary"
                        >
                            <template v-slot:append>
                                <q-icon name="search" />
                            </template>
                        </q-input>
                    </div>
                </template>
                <!-- <template v-slot:top>
                    <div class="text-h6">Categories</div>
                    <q-space />
                        <q-input outlined rounded dense label="Search..." debounce="300" color="primary" v-model="filter">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                    <Link :href="route('admin.categories.create')">
                        <q-btn class="q-ml-sm" rounded color="primary" unelevated no-caps label="Create Category" />
                    </Link>
                </template> -->
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
                <template v-slot:body-cell-items="props">
                    <q-td :props="props">
                        {{ props.row.products.length }} items
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <q-btn no-caps unelevated @click="showCategoryProducts(props.row)" label="View"/>
                        <q-btn no-caps unelevated color="negative" flat @click="showDeleteCategoryDialog(props.row)" label="Delete"/>
                    </q-td>
                </template>
                <template v-slot:item="props">
                    <div  class="col-12 q-mb-sm">
                        <q-card bordered flat class="q-mx-sm">
                            <q-card-section>
                                <q-item class="q-pa-none">
                                    <q-item-section class="items-start">
                                        <q-item-label>{{ props.row.name }}</q-item-label>
                                        <q-item-label caption>{{ props.row.products.length }} items</q-item-label>
                                        <q-btn icon="more_horiz" class="absolute-top-right text-black" flat color="white" round>
                                            <q-menu>
                                                <q-list style="min-width: 100px">
                                                    <q-item clickable @click="showCategoryProducts(props.row)">
                                                        <q-item-section>View</q-item-section>
                                                    </q-item>
                                                    <q-item clickable @click="showDeleteCategoryDialog(props.row)">
                                                        <q-item-section>Delete</q-item-section>
                                                    </q-item>
                                                </q-list>
                                            </q-menu>
                                        </q-btn>
                                    </q-item-section>
                                </q-item>
                            </q-card-section>
                        </q-card>
                    </div>
                </template>
            </q-table>
        </q-card>
        
    </div>
    <q-dialog 
        v-model="deleteCategoryDialog"
        :maximized="$q.screen.lt.md"    
        transition-show="slide-up"
        transition-hide="slide-down"
        :position="$q.screen.lt.md ? 'bottom' : 'standard'"
        >
        <q-card :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
            <q-card-section class="row items-center q-pb-none">
                <q-icon name="warning" color="negative" size="32px" />
                <div class="text-h6 q-ml-md">Delete Category</div>
                <q-btn round icon="close" v-close-popup flat class="absolute-top-right q-mt-sm q-mr-sm"/>
            </q-card-section>
            <q-card-section>
                Are you sure you want to delete this category? All data will be permanently removed. This action cannot be undone.
                <q-item class="bg-negative text-white q-my-md rounded-borders">
                    <q-item-section>
                        <q-item-label class="text-weight-bold text-subtitle1">{{ deleteCategoryForm.category.name }}</q-item-label>
                    </q-item-section>
                </q-item>
            </q-card-section>
            <q-card-actions class="justify-end">
                <q-btn v-close-popup flat rounded no-caps label="Cancel"/>
                <q-btn 
                    :disable="deleteCategoryForm.processing"
                    :loading="deleteCategoryForm.processing"
                    @click="deleteCategory"
                    no-caps
                    rounded 
                    color="negative"
                    unelevated
                    label="Delete"    
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
    <q-dialog 
        v-model="showCategoryProductsDialog" 
        :maximized="$q.screen.lt.md"    
        transition-show="slide-up"
        transition-hide="slide-down"
        :position="$q.screen.lt.md ? 'bottom' : 'standard'"
        >
        <q-card :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
            <q-card-section>
                <div class="text-h6">{{ currentCategory.name }}</div>
                <q-btn round icon="close" v-close-popup flat class="absolute-top-right q-mt-sm q-mr-sm"/>
            </q-card-section>
            <q-card-section>
                <q-list>
                    <q-item-label header>Products Belonged To this Category</q-item-label>
                    <q-item v-for="product in currentCategory.products">
                        <q-item-section avatar>
                            <q-img :src="`/storage/${product.photo}`" height="70px" width="70px"/>
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>{{ product.name }}</q-item-label>
                        </q-item-section>
                    </q-item>
                    <p v-if="currentCategory.products.length == 0" class="text-center q-my-md">
                        Theres no product yet in this category....
                    </p>
                </q-list>
            </q-card-section>
        </q-card>
    </q-dialog>
</template>
