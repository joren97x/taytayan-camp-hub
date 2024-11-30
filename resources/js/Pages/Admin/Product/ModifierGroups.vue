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

const props = defineProps({
    modifier_groups: Object
})

const drawerStore = useDrawerStore()
const filter = ref('')
const showSearch = ref(false)
const $q = useQuasar()
const deleteModifierGroupDialog = ref(false)
const deleteModifierGroupForm = useForm({
    modifier_group: null
})

function showDeleteModifierGroupDialog(modifier_group) {
    deleteModifierGroupDialog.value = true
    deleteModifierGroupForm.modifier_group = modifier_group
}

const deleteModifierGroup = () => {
    deleteModifierGroupForm.delete(route('admin.modifier_groups.destroy', deleteModifierGroupForm.modifier_group.id), {
        onSuccess: () => {
            deleteModifierGroupDialog.value = false
            $q.notify('Modifier Group Deleted')
        }
    })
}

const columns = [
  { name: 'name', label: 'Name', align: 'center', field: 'name', sortable: true },
  { name: 'contains', align: 'center', label: 'Contains', field: 'contains', sortable: true },
//   { name: 'productsUsing', align: 'center', label: 'Products using', field: 'productsUsing', sortable: true },
  { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

const initialPagination = {
    sortBy: 'desc',
    descending: false,
    page: 1,
    rowsPerPage: 10
}

</script>

<template>
    
    <Head title="Modifier Groups" />
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
        <q-card bordered flat>
            <q-table
                class="my-sticky-header-column-table"
                flat
                title="Treats"
                :rows="props.modifier_groups"
                :columns="columns"
                row-key="name"
                :filter="filter"
                :grid="$q.screen.lt.md"
                :pagination="initialPagination"
            >
                <template v-slot:top>
                    <q-btn icon="menu" flat dense @click="drawerStore.drawer = true" class="lt-md q-mr-sm"/>
                    <div class="text-h6">Modifier Groups</div>
                    <q-space />
                    <q-btn icon="search" class="q-mr-xs" round dense flat @click="showSearch = !showSearch"/>
                    
                    <Link :href="route('admin.modifier_groups.create')">
                        <q-btn class="q-ml-sm" no-caps color="primary" rounded unelevated label="Create Modifier"/>
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
                    <div class="text-h6">Modifier Groups</div>
                    <q-space />
                        <q-input rounded outlined dense label="Search..." debounce="300" color="primary" v-model="filter">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                    <Link :href="route('admin.modifier_groups.create')">
                        <q-btn class="q-ml-sm" color="primary" rounded no-caps label="Create Modifier Group" unelevated/>
                    </Link>
                </template> -->
                <template v-slot:body-cell-contains="props">
                    <q-td :props="props">
                        <q-chip v-for="(modifier_item, index) in props.row.modifier_items" :key="modifier_item.id">
                            {{ modifier_item.name }}
                        </q-chip>
                    </q-td>
                </template>
                <!-- <template v-slot:body-cell-productsUsing="props">
                    <q-td :props="props">
                        <span v-for="product in props.row.products" :key="product.id">
                            {{ product.name + ', ' }}
                        </span>
                    </q-td>
                </template> -->
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <Link :href="route('admin.modifier_groups.edit', props.row.id)">
                            <q-btn no-caps unelevated class="text-black">Edit</q-btn>
                        </Link>
                        <q-btn flat no-caps unelevated color="negative" @click="showDeleteModifierGroupDialog(props.row)">Delete</q-btn>
                    </q-td>
                </template>
                <template v-slot:item="props">
                    <div class="col-12 q-mb-sm">
                        <q-card class="q-mx-sm" bordered flat>
                            <q-card-section>
                                <q-item class="q-pa-none">
                                    <q-item-section class="items-start">
                                        <q-item-label>{{ props.row.name }}</q-item-label>
                                        <q-item-label caption class="ellipsis-2-lines q-mr-xl">
                                            <q-chip v-for="(modifier_item, index) in props.row.modifier_items" :key="modifier_item.id">
                                                {{ modifier_item.name }}
                                            </q-chip>
                                        </q-item-label>
                                       
                                        <q-btn icon="more_horiz" class="absolute-top-right text-black" flat color="white" round>
                                            <q-menu>
                                                <q-list style="min-width: 100px">
                                                    <Link :href="route('admin.modifier_groups.edit', props.row.id)">
                                                        <q-item clickable>
                                                            <q-item-section>Edit</q-item-section>
                                                        </q-item>
                                                    </Link>
                                                    <q-item clickable @click="showDeleteModifierGroupDialog(props.row)">
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
        <!-- <q-dialog v-model="deleteModifierGroupDialog">
            <q-card>
                <q-card-section>
                    Delete modifier group
                </q-card-section>
                <q-card-actions>
                    <q-btn v-close-popup>Cancel</q-btn>
                    <q-btn 
                        @click="deleteModifierGroup"
                        :loading="deleteModifierGroupForm.processing"
                        :disable="deleteModifierGroupForm.processing"
                    >
                        Delete
                    </q-btn>
                </q-card-actions>
            </q-card>
        </q-dialog> -->
        <q-dialog 
            v-model="deleteModifierGroupDialog"
            :maximized="$q.screen.lt.md"    
            transition-show="slide-up"
            transition-hide="slide-down"
            :position="$q.screen.lt.md ? 'bottom' : 'standard'"
            >
            <q-card :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
                <q-card-section class="row items-center q-pb-none">
                    <q-icon name="warning" color="negative" size="32px" />
                    <div class="text-h6 q-ml-md">Delete Modifier Group</div>
                    <q-btn round icon="close" v-close-popup flat class="absolute-top-right q-mt-sm q-mr-sm"/>
                </q-card-section>
                <q-card-section>
                    Are you sure you want to delete this modifier group? All data will be permanently removed. This action cannot be undone.
                    <q-item class="bg-negative text-white q-my-md rounded-borders">
                        <q-item-section>
                            <q-item-label class="text-weight-bold text-subtitle1">
                                {{ deleteModifierGroupForm.modifier_group.name }}
                                <br>
                                <q-chip v-for="(modifier_item, index) in deleteModifierGroupForm.modifier_group.modifier_items" :key="modifier_item.id">
                                    {{ modifier_item.name }}
                                </q-chip>
                            </q-item-label>
                               
                        </q-item-section>
                    </q-item>
                </q-card-section>
                <q-card-actions class="justify-end">
                    <q-btn v-close-popup flat rounded no-caps label="Cancel"/>
                    <q-btn 
                        :disable="deleteModifierGroupForm.processing"
                        :loading="deleteModifierGroupForm.processing"
                        @click="deleteModifierGroup"
                        no-caps
                        rounded 
                        color="negative"
                        unelevated
                        label="Delete"    
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>

<style scoped>

a {
    text-decoration: none;
    color: black;
}
</style>