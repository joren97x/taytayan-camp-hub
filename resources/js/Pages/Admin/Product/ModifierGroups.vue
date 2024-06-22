<script setup>

import ProductLayout from '@/Layouts/ProductLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'

defineOptions({
    layout: ProductLayout
})

const props = defineProps({
    modifier_groups: Object
})

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
    deleteModifierGroupForm.delete(route('admin.modifier_group.destroy', deleteModifierGroupForm.modifier_group.id), {
        onSuccess: () => {
            deleteModifierGroupDialog.value = false
            $q.notify('Modifier Group Deleted')
        }
    })
}

const columns = [
  { name: 'name', label: 'Name', align: 'center', field: 'name', sortable: true },
  { name: 'contains', align: 'center', label: 'Contains', field: 'contains', sortable: true },
  { name: 'productsUsing', align: 'center', label: 'Products using', field: 'productsUsing', sortable: true },
  { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    
    <Head title="Group Modifiers" />
    <div class="q-pa-md">
        <q-table
            class="my-sticky-header-column-table"
            flat
            title="Treats"
            :rows="props.modifier_groups"
            :columns="columns"
            row-key="name"
        >
            <template v-slot:top>
                <p class="text-h6 q-pt-md">Modifier Groups</p>
                <q-space />
                    <q-input filled dense label="Search..." debounce="300" color="primary" v-model="filter">
                    <template v-slot:append>
                        <q-icon name="search" />
                    </template>
                </q-input>
                <Link :href="route('admin.modifier_group.create')">
                    <q-btn class="q-ml-sm" color="primary" no-caps label="New Group" />
                </Link>
            </template>
            <template v-slot:body-cell-contains="props">
                <q-td :props="props">
                    <span v-for="modifier_item in props.row.modifier_items" :key="modifier_item.id">
                        {{ modifier_item.name + ', ' }}
                    </span>
                </q-td>
            </template>
            <template v-slot:body-cell-productsUsing="props">
                <q-td :props="props">
                    <span v-for="product in props.row.products" :key="product.id">
                        {{ product.name + ', ' }}
                    </span>
                </q-td>
            </template>
            <template v-slot:body-cell-actions="props">
                <q-td :props="props">
                    <Link :href="route('admin.modifier_group.edit', props.row.id)">
                        <q-btn no-caps unelevated>Edit</q-btn>
                    </Link>
                    <q-btn no-caps unelevated @click="showDeleteModifierGroupDialog(props.row)">Delete</q-btn>
                </q-td>
            </template>
        </q-table>
        <q-dialog v-model="deleteModifierGroupDialog">
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
        </q-dialog>
    </div>
</template>
