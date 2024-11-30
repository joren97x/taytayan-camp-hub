<script setup>

import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref } from 'vue'
import { useDrawerStore } from '@/Stores/DrawerStore';

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    facilities: Object
})

const drawerStore = useDrawerStore()
const showSearch = ref(false)
const filter = ref('')
const deleteFacilityDialog = ref(false)
const deleteFacilityForm = useForm({
    facility: null
})
const showDeleteFacilityDialog = (facility) => {
    deleteFacilityForm.facility = facility
    deleteFacilityDialog.value = true
}

const deleteFacility = () => {
    deleteFacilityForm.delete(route('admin.facilities.destroy', deleteFacilityForm.facility.id), {
        onSuccess: () => {
            deleteFacilityDialog.value = false
            deleteFacilityForm.reset()
            $q.notify('Facility Deleted')
        }
    })
}

const columns = [
    { name: 'name', label: 'Facility', align: 'center', field: 'name', sortable: true },
    { name: 'price', align: 'center', label: 'Price', field: 'price', sortable: true },
    { name: 'available', align: 'center', label: 'Available', field: 'available', sortable: true },
    // { name: 'guests', align: 'center', label: 'guests', field: 'guests', sortable: true },
    // { name: 'status', align: 'center', label: 'status', field: 'status', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    
    <Head title="Reviews" />
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
        <q-card bordered flat>
            <q-table
                flat
                :rows="facilities"
                :columns="columns"
                row-key="name"
                :filter="filter"
                :grid="$q.screen.lt.md"
            >
                <template v-slot:top>
                    <q-btn icon="menu" flat dense @click="drawerStore.drawer = true" class="lt-md q-mr-sm"/>
                    <div class="text-h6">Facilities</div>
                    <q-space />
                    <q-btn icon="search" class="q-mr-xs" round dense flat @click="showSearch = !showSearch"/>
                    
                    <Link :href="route('admin.facilities.create')">
                        <q-btn class="q-ml-sm" no-caps color="primary" rounded unelevated label="Create Facility"/>
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
                <template v-slot:body-cell-name="props">
                    <q-td :props="props" style="width: 200px;">
                        <q-item class="q-pa-none">
                            <q-item-section avatar>
                                <q-img :src="`/storage/${JSON.parse(props.row.images)[0]}`" height="60px" width="60px" class="rounded-borders" />
                            </q-item-section>
                            <q-item-section class="items-start " style="">
                                <q-item-label>{{ props.row.name }}</q-item-label>
                                <q-item-label caption class="ellipsis" style="max-width: 250px" >{{ props.row.description }}{{ props.row.description }}</q-item-label>
                            </q-item-section>
                        </q-item>
                    </q-td>
                </template>
                <!--  -->
                <template v-slot:body-cell-amenities="props">
                    <q-td :props="props">
                        <!-- {{ JSON.parse(props.row.amenities).length }} amenities -->
                    </q-td>
                </template>
                <template v-slot:body-cell-price="props">
                    <q-td :props="props">
                        P{{ props.row.price }}
                    </q-td>
                </template>
                <template v-slot:body-cell-available="props">
                    <q-td :props="props">
                        {{ props.row.available ? 'Yes' : 'No' }}
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <Link :href="route(`admin.facilities.edit`, props.row.id)">
                            <q-btn no-caps unelevated>Edit</q-btn>
                        </Link>
                        <q-btn no-caps unelevated @click="showDeleteFacilityDialog(props.row)">Delete</q-btn>
                    </q-td>
                </template>
                <template v-slot:item="props">
                    <div class="col-12 q-mb-sm">
                        <q-card class="q-mx-sm" bordered flat>
                            <q-card-section>
                                <q-item class="q-pa-none">
                                    <q-item-section avatar>
                                        <q-img :src="`/storage/${JSON.parse(props.row.images)[0]}`" fit="contain" height="60px" width="60px" class="rounded-borders" />
                                    </q-item-section>
                                    <q-item-section class="items-start">
                                        <q-item-label>{{ props.row.name }}</q-item-label>
                                        <q-item-label caption class="ellipsis-2-lines q-mr-xl">{{ props.row.description }}</q-item-label>
                                        <q-item-label caption >P{{ props.row.price }}</q-item-label>
                                        <q-btn icon="more_horiz" class="absolute-top-right z-top text-black" flat color="white" round>
                                            <q-menu>
                                                <q-list style="min-width: 100px">
                                                    <Link :href="route(`admin.facilities.edit`, props.row.id)">
                                                        <q-item clickable>
                                                            <q-item-section>Edit</q-item-section>
                                                        </q-item>
                                                    </Link>
                                                    <q-item clickable @click="showDeleteFacilityDialog(props.row)">
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
        <q-dialog 
            v-model="deleteFacilityDialog" 
            persistent 
            :maximized="$q.screen.lt.md"
            transition-show="slide-up"
            :position="$q.screen.lt.md ? 'bottom' : 'standard'"
            transition-hide="slide-down"
        >
            <q-card :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
                <q-card-section class="row items-center q-pb-none">
                    <q-icon name="warning" color="negative" size="32px" />
                    <div class="text-h6 q-ml-md">Delete Facility</div>
                    <q-btn round icon="close" v-close-popup flat class="absolute-top-right q-mt-sm q-mr-sm"/>
                </q-card-section>
                <q-card-section>
                    Are you sure you want to delete this facility? All data will be permanently removed. This action cannot be undone.
                    <q-item class="bg-negative text-white q-my-md rounded-borders">
                        <q-item-section avatar>
                            <q-img :src="`/storage/${JSON.parse(deleteFacilityForm.facility.images)[0]}`" height="100px" width="100px"></q-img>
                        </q-item-section>  
                        <q-item-section>
                            <q-item-label class="text-weight-bold text-subtitle1">{{ deleteFacilityForm.facility.name }}</q-item-label>
                            <q-item-label >{{ deleteFacilityForm.facility.description }}</q-item-label>
                            <q-item-label >{{ deleteFacilityForm.facility.price }}</q-item-label>
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
                        @click="deleteFacility" 
                        :loading="deleteFacilityForm.processing"
                        :disable="deleteFacilityForm.processing"
                    />
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>