<script setup>

import DriverLayout from '@/Layouts/DriverLayout.vue'

defineOptions({
    layout: DriverLayout
})

defineProps({
    orders: Object
})

const columns = [
    { name: 'name', label: 'User', align: 'center', field: 'name', sortable: true },
    { name: 'payment_method', align: 'center', label: 'Payment Method', field: 'payment_method', sortable: true },
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
                {{ props.row.user_id }}
            </q-td>
        </template>
        <template v-slot:body-cell-actions="props">
            <q-td :props="props">
                <q-btn no-caps color="primary" >Button</q-btn>
            </q-td>
        </template>
    </q-table>
</template>
