<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

defineOptions({
    layout: AdminLayout
})

defineProps({
    recent_transactions: Object
})

const columns = [
    { name: 'type', label: 'type', align: 'center', field: 'type', sortable: true },
    { name: 'user', label: 'user', align: 'center', field: 'user', sortable: true },
    { name: 'status', label: 'status', align: 'center', field: 'status', sortable: true },
    { name: 'amount', label: 'amount', align: 'center', field: 'amount', sortable: true },
    { name: 'payment_method', label: 'payment_method', align: 'center', field: 'payment_method', sortable: true },
    { name: 'created_at', label: 'created_at', align: 'center', field: 'created_at', sortable: true },
    // { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]
</script>

<template>
    <Head title="Dashboard" />
    <div class="q-pa-md">
        <div class="text-h6 q-mb-md"> Dashboard </div>
        
        <div class="row q-col-gutter-md ">
            <div class="col-3">
                <q-card borderd>
                    <q-card-section>
                        <div class="text-subtitle1">Revenue</div>
                        <div class="text-h6">P800.00</div>
                        <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-3">
                <q-card borderd>
                    <q-card-section>
                        <div class="text-subtitle1">Active Bookings</div>
                        <div class="text-h6">P800.00</div>
                        <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-3">
                <q-card borderd>
                    <q-card-section>
                        <div class="text-subtitle1">Tickets Sold</div>
                        <div class="text-h6">P800.00</div>
                        <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-3">
                <q-card borderd>
                    <q-card-section>
                        <div class="text-subtitle1">Users Registered</div>
                        <div class="text-h6">P800.00</div>
                        <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                    </q-card-section>
                </q-card>
            </div>
            
            {{ recent_transactions }}
            <div class="col-7">
                <q-card borderd>
                    <q-table
                        class="my-sticky-header-column-table"
                        flat
                        title="Recent Transactions"
                        :rows="recent_transactions"
                        :columns="columns"
                        row-key="index"
                        virtual-scroll
                    >
                        <template v-slot:body-cell-actions="props">
                            <q-td :props="props">
                                <q-btn no-caps unelevated @click="showDeleteCategoryDialog(props.row)" label="Delete"/>
                            </q-td>
                        </template>
                    </q-table>
                </q-card>
            </div>
            <div class="col-5">
                <q-card borderd style="padding: 170px">
                    some chart
                </q-card>
            </div>
            <div class="col-12">
                <q-card borderd style="padding: 100px">
                    idk
                </q-card>
            </div>
        </div>
    </div>
    {{ user_chart }}
</template>