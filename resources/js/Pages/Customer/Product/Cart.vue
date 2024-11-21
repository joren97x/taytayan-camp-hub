<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link, Head } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import { usePage, useForm } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { initializeLoader } from '@/Pages/Utils/GoogleMapsLoader'
// import EditCartItemDialog from '@/Components/Customer/Product/EditCartItemDialog.vue'
import NewAddressDialog from '@/Components/Customer/NewAddressDialog.vue'
import EditCartItemDialog from './Partials/EditCartItemDialog.vue'

defineOptions({
    layout: CustomerLayout
})

const props = defineProps({
    cart_products: Object,
    subtotal: Number,
    google_maps_api_key: String,
    cart: Object
})

const showNewAddressDialog = ref(false)
const showFoodCartItemDialog = ref(false)
const selectedCartItem = ref(null)
const page = usePage()
const $q = useQuasar()
const selectedRows = ref([]);
const selectAllChecked = ref(false)
const form = useForm({
    cart_id: props.cart.id,
    cart_products: []
})

const submit = () => {
    if(page.props.auth.user.email_verified_at == null) {
        return router.visit(route('verification.notice'))
    }
    if(page.props.auth.user.address == null) {
        showNewAddressDialog.value = true
    }
    else {
        // router.visit(route('product.checkout'), {
        //     data: {
        //         cart_id: props.cart.id
        //     }
        // })
        form.get(route('customer.checkout'), {
            onSuccess: () => {
                // $q.notify('Address Successfully Set')
            },
            onError: () => {
                $q.notify({
                    message: `You have not selected any items for checkout`,
                    color: 'negative', // or any custom color defined in the brand config
                    textColor: 'white',
                    position: 'top'
                })
            }
        })
    }
    
}

const deleteCartItemForm = useForm({})
const updateCartItemQuantityForm = useForm({
    operation: ''
})

function deleteCartItem(id) {
    deleteCartItemForm.delete(route('customer.cart.destroy', id), {
        onSuccess: () => {
            $q.notify('Cart Item Removed From Cart')
            showFoodCartItemDialog.value = false

        }
    })
}

function updateCartItemQuantity(id, operation) {
    updateCartItemQuantityForm.operation = operation
    updateCartItemQuantityForm.put(route('customer.cart.update_cart_item_quantity', id))
}

function showEditCartItemDialog(cartItem) {
    selectedCartItem.value = cartItem
    showFoodCartItemDialog.value = true
    console.log(cartItem)
}

const columns = [
    { name: 'photo', label: '', align: 'center', field: 'photo', sortable: true },
    { name: 'item', label: 'Items', align: 'center', field: 'item', sortable: true },
    { name: 'quantity', align: 'center', label: 'Quantity', field: 'quantity', sortable: true },
    { name: 'total', align: 'center', label: 'Total', field: 'total', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

const initialPagination = {
    sortBy: 'desc',
    descending: false,
    page: 1,
    rowsPerPage: props.cart_products.length
    // rowsNumber: xx if getting data from a server
}


const onSelection = (selection) => {
    if (selection.added) {
        const addedIds = selection.rows.map(row => row.id);
        form.cart_products = [...form.cart_products, ...addedIds];
    } else {
        const removedIds = selection.rows.map(row => row.id);
        form.cart_products = form.cart_products.filter(id => !removedIds.includes(id));
    }
    selectAllChecked.value = selectedRows.value.length === props.cart_products.length;
};

const total = computed(() => {
    let subtotal = 0
    selectedRows.value.map((row) => subtotal += row.total)
    return subtotal
})

// const selectAllChecked = ref(false)
const selectAll = () => {
    if (selectAllChecked.value) {
        // Select all rows
        selectedRows.value = props.cart_products;
        form.cart_products = props.cart_products.map(row => row.id);
    } else {
        // Deselect all rows
        selectedRows.value = [];
        form.cart_products = [];
    }
};


const onGridSelection = (id) => {
    const existingIndex = form.cart_products.findIndex((product) => product.id === id);

    if (existingIndex !== -1) {
        // Remove the item if it exists in cart_products
        form.cart_products.splice(existingIndex, 1);
    } else {
        // Add the item if it does not exist in cart_products
        form.cart_products.push({ id });
    }
}

</script>

<template>
    
    <Head title="Cart" />
    <!-- {{ props }} -->
    <NewAddressDialog 
        :dialog="showNewAddressDialog" 
        @close="showNewAddressDialog = false"
        :google_maps_api_key="google_maps_api_key" 
        v-if="$page.props.auth.user"
    />
    <!-- {{ form }} -->
    <div :class="`row ${$q.screen.gt.md ? 'q-mt-md' : ''}`">
        <div class="col-8 col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
            <q-table
                bordered
                flat
                :rows="cart_products"
                :columns="columns"
                row-key="id"
                selection="multiple"
                v-model:selected="selectedRows"
                @selection="onSelection"
                :pagination="initialPagination"
                :grid="$q.screen.lt.md"
            >
                <template v-slot:top>
                    <q-checkbox
                        v-model="selectAllChecked"
                        @update:model-value="selectAll"
                        class="lt-md"
                        size="xs"
                    />
                    <span class="text-h6 q-ml-sm">Cart</span> 
                    <q-space/>
                    <span class="text-subtitle1">{{ cart_products.length }} items</span>
                </template>
                <template v-slot:body-cell-photo="props">
                    <q-td :props="props">
                        <q-img height="80px" width="80px" :src="`/storage/${props.row.product.photo}`"></q-img>
                    </q-td>
                </template>
                <template v-slot:body-cell-item="props">
                    <q-td :props="props" class="text-left">
                        <div class="text-weight-bold">{{ props.row.product.name }}</div>
                        <template v-for="(modifier, index) in props.row.grouped_modifiers" :key="index">
                            <q-item-label caption>{{ modifier.modifier_group.name }}</q-item-label>
                            <q-item-label caption v-for="(modifier_item, index) in modifier.modifier_items" :key="index">
                            {{ `${modifier_item.quantity} - ${modifier_item.modifier_item.name} (P${modifier_item.modifier_item.price})` }}
                            </q-item-label>
                        </template>
                        <q-item-label caption v-if="props.row.special_instruction" class="text-grey-7">
                            Note: {{ props.row.special_instruction }}
                        </q-item-label>
                    </q-td>
                </template>
                <template v-slot:body-cell-quantity="props">
                    <q-td :props="props">
                            <q-btn flat size="xs" icon="remove" @click="updateCartItemQuantity(props.row.id, '-')" v-show="props.row.quantity > 1" />
                            <span class="q-mt-xs q-mx-sm">{{ props.row.quantity }} </span>
                            <q-btn flat size="xs" icon="add" @click="updateCartItemQuantity(props.row.id, '+')" />
                    </q-td>
                </template>
                <template v-slot:body-cell-total="props">
                    <q-td :props="props">
                        <span class="text-weight-medium">₱{{ parseFloat(props.row.total).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</span>
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <q-btn no-caps unelevated @click="showEditCartItemDialog(props.row)" color="primary" flat label="Edit"/>
                        <q-btn no-caps 
                            unelevated color="red" flat 
                            label="Remove"
                            @click="deleteCartItem(props.row.id)"
                        />
                    </q-td>
                </template>
                
                <template v-slot:no-data>
                    <div class="full-width row flex-center q-gutter-sm" style="height: 50vh">
                        No Items Found
                    </div>
                </template>
                <template v-slot:item="props">
                    <div class="col-12 q-mb-sm">
                        <q-card class="q-mx-sm" bordered flat>
                            <q-card-section class="q-pr-xs q-py-xs">
                                <q-item class="q-pa-none q-pr-sm" @click="showEditCartItemDialog(props.row)" clickable>
                                    <q-item-section avatar class="q-px-none">
                                        <div>
                                            <q-checkbox
                                                v-model="selectedRows"
                                                :val="props.row"
                                                @update:model-value="onGridSelection(props.row.id)"
                                                size="xs"
                                                class="q-pa-none q-ma-none"
                                            />
                                            <q-img :src="`/storage/${props.row.product.photo}`" fit="contain"height="60px" width="60px" class="rounded-borders" />
                                        </div>
                                        
                                    </q-item-section>
                                    <q-item-section class="items-start">
                                        <q-item-label class="q-mr-xl">{{ props.row.product.name }} ({{ props.row.quantity }})</q-item-label>
                                        <q-item-label caption>
                                            <template v-for="(modifier, index) in props.row.grouped_modifiers" :key="index">
                                                <q-item-label caption>{{ modifier.modifier_group.name }}</q-item-label>
                                                <q-item-label caption v-for="(modifier_item, index) in modifier.modifier_items" :key="index">
                                                {{ `${modifier_item.quantity} - ${modifier_item.modifier_item.name} (P${modifier_item.modifier_item.price})` }}
                                                </q-item-label>
                                            </template>
                                            <q-item-label caption v-if="props.row.special_instruction" class="text-grey-7">
                                                Note: {{ props.row.special_instruction }}
                                            </q-item-label>
                                        </q-item-label>
                                        <!-- <q-item-label caption class="ellipsis q-mr-xl">{{ props.row.product.description }}</q-item-label> -->
                                        <!-- <q-item-label caption >{{ props.row.quantity }} qty</q-item-label> -->
                                        <div class="absolute-top-right text-black q-mr-sm text-weight-medium">
                                            ₱{{ parseFloat(props.row.total).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
                                        </div>
                                    </q-item-section>
                                </q-item>
                            </q-card-section>
                        </q-card>
                    </div>
                </template>
            </q-table>
            <!-- {{ selectedRows }}
            <q-separator></q-separator>
            {{ form.cart_products }} -->
        </div>
        <div class="col-4 col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 gt-sm">
            <q-card bordered flat :class="['sticky', $q.screen.lt.md ? 'q-mt-md q-mx-sm' : 'q-mx-md']" style="position: sticky; top: 60px;">
                <q-card-section class="q-pb-none">
                    <div class="text-h6">Order Summary</div>
                    <!-- <q-separator/> -->
                    <q-list>
                        <!-- <q-item-label header>Selected Items</q-item-label> -->
                        <q-item v-for="row in selectedRows">
                            <q-item-section avatar>
                                <q-avatar square>
                                    <q-img class="fit" fit="cover" :src="`/storage/${row.product.photo}`" />
                                </q-avatar>
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>{{ row.product.name }}</q-item-label>
                            </q-item-section>
                            <q-item-section side>
                                ₱{{ parseFloat(row.total).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
                            </q-item-section>
                        </q-item>
                        <div class="bg-grey-3 q-mb-md items-center justify-center flex q-pa-xl" v-if="selectedRows && selectedRows.length <= 0">
                            No selected items...
                        </div>
                    </q-list>
                    <q-separator/>
                    <q-item>
                        <q-item-section>
                            <q-item-label class="text-weight-medium text-subtitle1">Total</q-item-label>
                        </q-item-section>
                        <q-item-section class="flex items-end">
                            <div class="text-weight-medium text-subtitle1">₱{{ parseFloat(total).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</div>
                        </q-item-section>
                    </q-item>
                </q-card-section>
                <q-card-section>
                    <q-btn color="primary" @click="submit()" class="full-width" no-caps rounded label="Go To Checkout"/>
                </q-card-section>
            </q-card>
        </div>
    </div>
    <q-card class="bg-white fixed-bottom lt-md z-top" bordered square v-if="!showNewAddressDialog">
        <q-card-section class="row justify-between q-pa-sm">
            <div class="col">
                <div>Total ({{ form.cart_products.length }} items)</div>
                <div class="text-weight-bold">₱{{ parseFloat(total).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</div>
            </div>
            <div class="col justify-end items-center flex">
                
                <q-btn
                    label="Checkout"
                    color="primary"
                    no-caps
                    rounded
                    @click="submit"
                />  
                
            </div>
        </q-card-section>
    </q-card>
    <EditCartItemDialog :dialog="showFoodCartItemDialog" :cart_item="selectedCartItem" @close="showFoodCartItemDialog = false"  @remove_from_cart="deleteCartItem(selectedCartItem.id)"/>

</template>

<style scoped>

.pac-container {
  z-index: 10000; /* Adjust the z-index as needed */
}

</style>