<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link, Head } from '@inertiajs/vue3'
import { ref, nextTick, onMounted } from 'vue'
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

const isAdressSet = () => {
    
}
const selectedRows = ref([]);
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
                $q.notify('Address Successfully Set')
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
        }
    })
}

function updateCartItemQuantity(id, operation) {
    updateCartItemQuantityForm.operation = operation
    updateCartItemQuantityForm.put(route('customer.cart.update_cart_item_quantity', id), {
        onSuccess: () => {
            $q.notify('basta gi addan nimo shag quantity')
        }
    })
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

const onSelection = (selection) => {
    if (selection.added) {
        // Add selected IDs to cart_products
        const addedIds = selection.rows.map(row => row.id);
        form.cart_products = [...form.cart_products, ...addedIds];
    } else {
        // Remove unselected IDs from cart_products
        const removedIds = selection.rows.map(row => row.id);
        form.cart_products = form.cart_products.filter(id => !removedIds.includes(id));
    }
};
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
    <div class="row">
        <div class="col-8 col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
            <q-table
                class="my-sticky-header-column-table"
                bordered
                flat
                :rows="cart_products"
                :columns="columns"
                row-key="id"
                selection="multiple"
                v-model:selected="selectedRows"
                @selection="onSelection"
            >
                <template v-slot:top>
                    <span class="text-h6">Cart</span> 
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
                        <q-item-label caption v-if="props.row.special_instruction">
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
                        P{{ props.row.total }}
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <q-btn no-caps unelevated @click="showEditCartItemDialog(props.row)">Edit</q-btn>
                        <q-btn no-caps 
                            unelevated color="red" flat 
                            
                            @click="deleteCartItem(props.row.id)"
                        >
                            Remove
                        </q-btn>
                    </q-td>
                </template>
                <template v-slot:no-data>
                    <div class="full-width row flex-center q-gutter-sm" style="height: 50vh">
                        No Orders Found
                    </div>
                </template>
            </q-table>
        </div>
        <div class="col-4 col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <q-card bordered flat :class="['q-mx-md', $q.screen.lt.md ? 'q-mt-md' : '']">
                <q-card-section>
                    <div class="text-h6">Order Summary</div>
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
                        <q-btn color="primary" @click="submit()" class="full-width" no-caps rounded label="Go To Checkout"/>
                    <!-- </Link> -->
                </q-card-section>
            </q-card>
        </div>
    </div>
    <!-- <q-dialog v-model="showNewAddressDialog" style="z-index: 1;" @show="initializeAutocomplete">
        <div>
            <q-card style=" margin-top: 100px;">
                <q-form @submit="submit">
                    <q-card-section>
                        <div class="text-h6">New Address</div>
                        <div>To place order, please add a delivery address</div>
                    </q-card-section>
                
                    <q-card-section>
                        <q-input
                            filled
                            v-model="form.phone_number"
                            label="Phone"
                            :error="form.errors.phone_number ? true : false"
                            :error-message="form.errors.phone_number"
                        />

                        <q-input 
                            filled 
                            v-model="form.address" 
                            class="q-my-md" 
                            ref="placeInput"
                            id="place"
                            :error="form.errors.address ? true : false"
                            :error-message="form.errors.address"
                            label="Barangay, City, Province"
                        />
                        <q-banner dense class="bg-red-1 q-mb-md" v-if="form.address">
                            <template v-slot:avatar>
                                <q-icon name="alert" color="primary" />
                            </template>
                            <p class="text-weight-bold text-primary text-h6">Place an accurate pin</p>
                            <p class="text-subtitle-1">
                                We will deliver to your map location. 
                                Please check it is correct, else click the map to adjust.
                            </p>
                        </q-banner>
                        <div id="map" style="width: 100%; height: 600px"  v-if="form.address"></div>
                    </q-card-section>
                    <q-card-actions>
                        <q-space/>
                        <q-btn v-close-popup no-caps flat>Cancel</q-btn>
                        <q-btn 
                            v-close-popup 
                            color="primary"
                            type="submit" 
                            unelevated 
                            no-caps
                            :loading="form.processing"
                            :disable="form.processing"
                        >
                            Submit
                        </q-btn>
                    </q-card-actions>
                </q-form>
            </q-card>
        </div>
    </q-dialog> -->

    <EditCartItemDialog :dialog="showFoodCartItemDialog" :cart_item="selectedCartItem" @close="showFoodCartItemDialog = false" />

</template>

<style scoped>

.pac-container {
  z-index: 10000; /* Adjust the z-index as needed */
}

</style>