<script setup>

import DriverLayout from '@/Layouts/DriverLayout.vue'
import ViewOrderDialog from '@/Components/Driver/ViewOrderDialog.vue'

defineOptions({
    layout: DriverLayout
})

defineProps({
    orders: Object
})

const columns = [
    { name: 'name', label: 'User', align: 'center', field: 'name', sortable: true },
    { name: 'payment_method', align: 'center', label: 'Payment Method', field: 'payment_method', sortable: true },
    { name: 'status', align: 'center', label: 'status', field: 'status', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    <h1>Orders </h1>
    <q-table
        class="my-sticky-header-column-table q-ma-xl"
        flat
        title="Orders"
        :rows="orders"
        :columns="columns"
        row-key="name"
    >
        <template v-slot:body-cell-name="props">
            <q-td :props="props">
                {{ props.row.user.first_name + ' ' + props.row.user.last_name }}
            </q-td>
        </template>
        <template v-slot:body-cell-actions="props">
            <q-td :props="props">
                <ViewOrderDialog :order="props.row"/>
            </q-td>
        </template>
    </q-table>
</template>
