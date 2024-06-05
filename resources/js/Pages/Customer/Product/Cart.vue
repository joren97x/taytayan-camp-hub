<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link, Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'
import NewAddressDialog from '@/Components/Customer/NewAddressDialog.vue'
import { router } from '@inertiajs/vue3'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    items: Object,
    subtotal: Number
})

const showNewAddressDialog = ref(false)
const page = usePage()
const submit = () => {
    console.log(page.props.auth)
    if(page.props.auth.user.address == null) {
        showNewAddressDialog.value = true
    }
    else {
        router.visit(route('product.checkout'))
    }
}

const card = ref(false)
const columns = [
    { name: 'photo', label: '', align: 'center', field: 'photo', sortable: true },
    { name: 'item', label: 'Item', align: 'center', field: 'item', sortable: true },
    { name: 'quantity', align: 'center', label: 'Quantity', field: 'quantity', sortable: true },
    { name: 'total', align: 'center', label: 'Total', field: 'total', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    
    <Head title="My Cart" />
    <div class="row q-mb-xl q-col-gutter-md">
        <div class="col-8">
            <q-table
                class="my-sticky-header-column-table"
                flat
                title="My Cart"
                :rows="items"
                :columns="columns"
                row-key="name"
            >
                <template v-slot:body-cell-photo="props">
                    <q-td :props="props">
                        <q-img height="80px" width="80px" :src="`images/${props.row.product.photo}`"></q-img>
                    </q-td>
                </template>
                <template v-slot:body-cell-item="props">
                    <q-td :props="props" class="text-left">
                        {{ props.row.product.name }} ({{ props.row.product.price }})
                        <template 
                            v-for="(modifier, index) in props.row.grouped_modifiers" 
                            :key="index"
                        >
                            <q-item-label caption>{{ modifier.modifier_group.name }}</q-item-label>
                            <q-item-label 
                                caption 
                                v-for="(modifier_item, index) in modifier.modifier_items" 
                                :key="index"
                            >
                                {{ `${modifier_item.quantity} - ${modifier_item.modifier_item.name} (P${modifier_item.modifier_item.price})` }}
                            </q-item-label>
                            
                        </template>
                        <!-- <q-item-label caption>
                            {{ props.row.grouped_modifiers }}
                        </q-item-label> -->
                        <q-item-label caption v-if="props.row.special_instruction">
                            Note: {{ props.row.special_instruction }}
                        </q-item-label>
                    </q-td>
                </template>
                <template v-slot:body-cell-quantity="props">
                    <q-td :props="props">
                            <q-btn flat size="xs" icon="remove" />
                            <span class="q-mt-xs q-mx-sm">{{ props.row.quantity }} </span>
                            <q-btn flat size="xs" icon="add" />
                    </q-td>
                </template>
                <template v-slot:body-cell-total="props">
                    <q-td :props="props">
                        P{{ props.row.total }}
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <q-btn no-caps unelevated>Edit</q-btn>
                        <q-btn no-caps unelevated color="red" flat @click="card = true">Remove</q-btn>
                    </q-td>
                </template>
            </q-table>
        </div>
        <div class="col-4">
            <q-card>
                <q-card-section>
                    <div class="text-h6">Cart Total</div>
                    <q-separator></q-separator>
                    <q-item>
                        <q-item-section>
                            <q-item-label>Subtotal</q-item-label>
                        </q-item-section>
                        <q-item-section side top>
                            <q-item-label>{{ subtotal }}</q-item-label>
                        </q-item-section>
                    </q-item>
                    <q-item>
                        <q-item-section>
                            <q-item-label>Total</q-item-label>
                        </q-item-section>
                        <q-item-section side top>
                            <q-item-label>{{ subtotal }}</q-item-label>
                        </q-item-section>
                    </q-item>
                </q-card-section>
                
                <q-card-section vertical>
                    <q-separator class="q-mb-sm"></q-separator>
                    <!-- <Link :href="route('product-checkout')"> -->
                        <q-btn color="primary" @click="submit" class="full-width" no-caps>Go To Checkout</q-btn>
                    <!-- </Link> -->
                </q-card-section>
            </q-card>
        </div>
        <q-dialog v-model="card">
            <q-card class="my-card">
                <q-img src="https://cdn.quasar.dev/img/chicken-salad.jpg" />
                <q-card-section>
                    <div class="row no-wrap items-center">
                        <p class="col text-h6 ellipsis">
                            Cafe Basilico
                        </p>
                    </div>
                </q-card-section>

                <q-card-section class="q-pt-none">
                    <div class="text-subtitle1">
                        $・Italian, Cafe
                    </div>
                    <div class="text-caption text-grey">
                        Small plates, salads & sandwiches in an intimate setting.
                    </div>
                </q-card-section>

                <q-separator />

                <q-card-actions align="right">
                    <q-btn v-close-popup flat color="primary" label="Reserve" />
                    <q-btn v-close-popup flat color="primary" round icon="event" />
                </q-card-actions>
            </q-card>
        </q-dialog>
        <NewAddressDialog @close="showNewAddressDialog = false" :dialog="showNewAddressDialog" />
    </div>

</template>