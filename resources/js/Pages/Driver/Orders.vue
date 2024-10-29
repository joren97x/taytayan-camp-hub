<script setup>

import DriverLayout from '@/Layouts/DriverLayout.vue'
// import ViewOrderDialog from '@/Components/Driver/ViewOrderDialog.vue'
import ViewOrderDialog from './Partials/ViewOrderDialog.vue'
import { useQuasar } from 'quasar'
import { ref } from 'vue'
import axios from 'axios'
import { Head } from '@inertiajs/vue3'

defineOptions({
    layout: DriverLayout
})

const props = defineProps({
    orders: Object
})

const $q = useQuasar()
const orders = ref([])
props.orders.forEach(order => {
    orders.value.push(order)
})

// const columns = [
//     { name: 'name', label: 'User', align: 'center', field: 'name', sortable: true },
//     { name: 'items', label: 'Items', align: 'center', field: 'items', sortable: true },
//     { name: 'payment_method', align: 'center', label: 'Payment Method', field: 'payment_method', sortable: true },
//     { name: 'status', align: 'center', label: 'status', field: 'status', sortable: true },
//     { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
// ]

const columns = [
  { name: 'name', label: 'User', align: 'center', field: row => row.user.first_name + ' ' + row.user.last_name, sortable: true },
  { name: 'items', label: 'Items', align: 'center', field: 'items', sortable: false },
  { name: 'address', label: 'Address', align: 'center', field: row => row.user.address, sortable: false },
  { name: 'phone', label: 'Phone', align: 'center', field: row => row.user.phone_number, sortable: false },
  { name: 'status', align: 'center', label: 'Status', field: 'status', sortable: true },
  { name: 'payment_method', align: 'center', label: 'Payment Method', field: 'payment_method', sortable: true },
  { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: false },
];

Echo.private('orders')
    .listen('Product\\OrderReadyForDelivery', (data) => {
        console.log(data)
        $q.notify('yuhh new order arrived')
        axios.get(route('driver.orders.show', data.order.id))
        .then((orderData) => {
            orders.value.push(orderData.data)
        })
        .catch((err) => {
            console.error(err)
        })

        
    })

</script>

<template>
    <Head title="Orders" />
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
        <q-card bordered flat>
            <q-table
                class="my-sticky-header-column-table"
                flat
                title="Orders"
                :rows="orders"
                :columns="columns"
                row-key="name"
                bordered 
                :grid="$q.screen.lt.md"
            >
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <ViewOrderDialog :order="props.row"/>
                    </q-td>
                </template>
                <template v-slot:body-cell-items="props">
                    <q-td :props="props">
                        {{ props.row.cart_products.length }} items
                    </q-td>
                </template>
                <template v-slot:item="props">
                    <q-card class="col-12 q-mb-md" bordered flat>
                        <q-card-section>
                            <div class="row">
                                <div class="col-xs-6 col-sm-6">
                                    <div class="text-caption text-grey">
                                        User
                                    </div>
                                    {{ props.row.user.first_name + ' ' + props.row.user.last_name }}
                                </div>
                                <div class="col-xs-6 col-sm-6">
                                    <div class="text-caption text-grey">
                                        Items
                                    </div>
                                    {{ props.row.cart_products.length }} items
                                </div>
                                <div class="col-xs-6 col-sm-6">
                                    <div class="text-caption text-grey">
                                        Address
                                    </div>
                                    {{ props.row.user.address }}
                                </div>
                                <div class="col-xs-6 col-sm-6">
                                    <div class="text-caption text-grey">
                                        Phone
                                    </div>
                                    {{ props.row.user.phone_number }}
                                </div>
                                <div class="col-xs-6 col-sm-6">
                                    <div class="text-caption text-grey">
                                        Status
                                    </div>
                                    {{ props.row.status }}
                                </div>
                                <div class="col-xs-6 col-sm-6">
                                    <div class="text-caption text-grey">
                                        Payment Method
                                    </div>
                                    {{ props.row.payment_method }}
                                </div>
                                <div class="col-xs-12 col-sm-12">
                                    <div class="text-caption text-grey">
                                        Actions
                                    </div>
                                    <ViewOrderDialog :order="props.row" />
                                </div>
                            </div>
                        </q-card-section>
                    </q-card>
                </template>
                <template v-slot:no-data>
                    <div class="full-width row flex-center q-gutter-sm" style="height: 70vh;">
                        <!-- <q-icon size="2em" name="shopping_cart" /> -->
                        <span>
                            There are currently no orders, Check again later...
                        </span>
                    </div>
                </template>
            </q-table>
        </q-card>
    </div>
</template>
