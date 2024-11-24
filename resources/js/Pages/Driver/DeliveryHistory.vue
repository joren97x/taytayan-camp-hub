<script setup>

import DriverLayout from '@/Layouts/DriverLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import OrderCard from './Partials/OrderCard.vue';

defineOptions({ layout: DriverLayout })
defineProps({ orders: Object, google_maps_api_key: String})

const columns = [
  { name: 'name', label: 'User', align: 'center', field: row => row.user.first_name + ' ' + row.user.last_name, sortable: true },
  { name: 'items', label: 'Items', align: 'center', field: 'items', sortable: false },
  { name: 'address', label: 'Address', align: 'center', field: row => row.user.address, sortable: false },
  { name: 'phone', label: 'Phone', align: 'center', field: row => row.user.phone_number, sortable: false },
  { name: 'status', align: 'center', label: 'Status', field: 'status', sortable: true },
  { name: 'payment_method', align: 'center', label: 'Payment Method', field: 'payment_method', sortable: true },
  { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: false },
];
</script>

<template>
    <Head title="Orders" />
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
        <q-card bordered flat :square="$q.screen.lt.md">
            <q-table
                class="my-sticky-header-column-table"
                flat
                title="Delivery History"
                :rows="orders"
                :columns="columns"
                row-key="name"
                bordered 
                grid
            >
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <ViewOrderDialog :order="props.row" :google_maps_api_key="google_maps_api_key"/>
                    </q-td>
                </template>
                <template v-slot:body-cell-items="props">
                    <q-td :props="props">
                        {{ props.row.cart_products.length }} items
                    </q-td>
                </template>
                <template v-slot:item="props">
                    <div class="col-md-6 col-xs-12 col-sm-12 col-lg-6 col-xl-6">
                        <OrderCard :order="props.row" :google_maps_api_key="google_maps_api_key" />
                    </div>
                </template>
                <template v-slot:no-data>
                    <div class="flex items-center justify-center" style="height: 70vh; width: 100%;">
                        <div class="text-center">
                            <q-img src="/images/empty_orders.png" style="height: 100px; width: 100px;"></q-img>
                            <div>
                                <div class="text-subtitle1 text-grey-7">No Active Deliveries Found</div>
                                <Link :href="route('driver.orders.index')">
                                    <q-btn class="q-mt-xl" color="primary" rounded no-caps label="Go To Orders"/>
                                </Link>
                            </div>
                        </div>
                    </div>
                </template>
            </q-table>
        </q-card>
    </div>
</template>
