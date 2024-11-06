<script setup>

import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, computed } from 'vue'
import { date } from 'quasar'
import { useDrawerStore } from '@/Stores/DrawerStore'
import OrderDetails from '@/Components/OrderDetails.vue'
// import OrderedItems from '@/Components/OrderedItems.vue'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    orders: Object,
    order_statuses: Array
})

props.order_statuses.statuses.unshift('all')

const drawerStore = useDrawerStore()
const filter = ref('all')
const showSearch = ref(false)
const searchTerm = ref('')

const filteredOrders = computed(() => {
  let filtered = props.orders;
  // Apply status filter
  if (filter.value !== 'all') {
    filtered = filtered.filter(order => order.status === filter.value);
  }

  // Apply search term filter
  if (searchTerm.value) {
    filtered = filtered.filter(order => {
        // console.log(order)
      const fullName = `${order.user.first_name} ${order.user.last_name}`.toLowerCase();
      const orderId = order.id.toLowerCase();
      const search = searchTerm.value.toLowerCase();

      return (
        fullName.includes(search) ||
        orderId.includes(search) ||
        facilityName.includes(search)
      );
    });
  }

  return filtered;
});

const columns = [
    { name: 'user', label: 'User', align: 'center', field: 'user', sortable: true },
    { name: 'cart_products', align: 'center', label: 'Items', field: 'cart_products', sortable: true },
    { name: 'subtotal', align: 'center', label: 'Subtotal', field: 'subtotal', sortable: true },
    { name: 'status', align: 'center', label: 'Status', field: 'status', sortable: true },
    { name: 'mode', align: 'center', label: 'Mode', field: 'mode', sortable: true },
    { name: 'created_at', align: 'center', label: 'Ordered At', field: 'created_at', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

const viewOrderDialog = ref(false)
const selectedOrder = ref(null)

const showOrderDialog = (order) => {
    console.log(order)
    selectedOrder.value = order
    viewOrderDialog.value = true
}

</script>

<template>
    
    <Head title="Reviews" />
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
        <q-card flat bordered style="border-radius: 20px">
            <q-table
                class="my-sticky-header-column-table"
                flat
                title="Treats"
                :rows="filteredOrders"
                :columns="columns"
                row-key="name"
                :grid="$q.screen.lt.md"
            >
                <template v-slot:top>
                    <q-btn icon="menu" flat dense @click="drawerStore.drawer = true" class="lt-md q-mr-sm"/>
                    <div class="text-h6">Orders</div>
                    <q-space />
                    <q-btn icon="search" class="q-mr-xs" round dense flat @click="showSearch = !showSearch"/>
                    <q-select 
                        :options="order_statuses.statuses" 
                        v-model="filter"
                        outlined 
                        rounded
                        dense
                    />
                    <!-- <Link :href="route('admin.products.create')">
                        <q-btn class="q-ml-sm" no-caps color="primary" rounded unelevated label="Create Product"/>
                    </Link> -->
                    <div class="full-width q-mt-sm" v-if="showSearch">
                        <q-input
                            v-model="searchTerm"
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
                <template v-slot:body-cell-user="props">
                    <q-td :props="props">
                        {{ props.row.user.first_name + ' ' + props.row.user.last_name }}
                    </q-td>
                </template>
                <template v-slot:body-cell-created_at="props">
                    <q-td :props="props">
                        {{ date.formatDate(props.row.created_at, 'MMM D, YYYY') }}
                    </q-td>
                </template>
                <template v-slot:body-cell-cart_products="props">
                    <q-td :props="props">
                        {{ props.row.cart_products.length }} items
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <q-btn no-caps color="primary" rounded @click="showOrderDialog(props.row)" label="View Order"/>
                    </q-td>
                </template>
                <template v-slot:item="props">
                    <div class="col-12">
                        <q-card bordered flat class="q-mb-sm q-mx-sm">
                            <q-item clickable>
                                <q-item-section>
                                    <q-item-label caption>Order id</q-item-label>
                                    {{ props.row.id }}
                                    <q-item-label caption>Customer</q-item-label>
                                    {{ props.row.user.first_name + ' ' + props.row.user.last_name }}
                                        <!-- {{ date.formatDate(props.row.created_at, 'MMMM D, YYYY') }} -->
                                    <q-item-label caption>
                                    </q-item-label>
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label caption>Status</q-item-label>
                                    {{ props.row.status }}
                                    <q-item-label caption>Items Summary</q-item-label>
                                    {{ props.row.cart_products.length }} items - P{{ props.row.subtotal }}
                                </q-item-section>
                                <q-item-section side top>
                                    <q-btn icon="more_horiz" class="text-black" flat color="white" round>
                                        <q-menu>
                                            <q-list style="min-width: 100px">
                                                <q-item clickable @click="showOrderDialog(props.row)">
                                                    <q-item-section>View</q-item-section>
                                                </q-item>
                                                <!-- <q-item clickable @click="showDeleteProductDialog(props.row)">
                                                    <q-item-section>Delete</q-item-section>
                                                </q-item> -->
                                            </q-list>
                                        </q-menu>
                                    </q-btn>
                                </q-item-section>
                            </q-item>
                        </q-card>
                    </div>
                </template>
                
            </q-table>
        </q-card>
        <!-- <q-dialog v-model="deleteProductDialog">
            <q-card>
                <q-card-section>
                    <p>Delete product</p>
                </q-card-section>
                <q-card-actions>
                    <q-space/>
                    <q-btn no-caps v-close-popup>Cancel</q-btn>
                    <q-btn 
                        no-caps
                        :loading="deleteProductForm.processing"
                        :disable="deleteProductForm.processing"
                        @click="deleteProduct"
                    >
                        Delete
                    </q-btn>
                </q-card-actions>
            </q-card>
        </q-dialog> -->
        <q-dialog 
        v-model="viewOrderDialog" 
        :maximized="$q.screen.lt.md"  
        transition-show="slide-up"
        transition-hide="slide-down"
    >   
        <OrderDetails  :order="selectedOrder">
            <div v-if="selectedOrder.driver">
                <q-item class="bg-grey">
                    <q-item-section avatar>
                        <q-avatar color="secondary">
                            <q-img :src="`/storage/${selectedOrder.driver.profile_pic}`" fit="cover" class="fit" v-if="selectedOrder.driver.profile_pic"/>
                            <div v-else>{{ selectedOrder.driver.first_name[0] }}</div>
                        </q-avatar>
                    </q-item-section>
                    <q-item-section>
                        <q-item-label>{{ selectedOrder.driver.first_name + ' ' + selectedOrder.driver.last_name }}</q-item-label>
                        <q-item-label label>{{ selectedOrder.driver.phone_number }}</q-item-label>
                    </q-item-section>
                </q-item>
            </div>
        </OrderDetails>
        <!-- <q-card :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
            <q-card-actions class="justify-between">
                <div class="text-h6">Order Details</div>
                    <q-btn  
                        icon="close" 
                        v-close-popup
                        round
                        class="absolute-top-right"
                        unelevated
                    />
            </q-card-actions>
            <q-card-section class="q-py-none">
                <div class="text-h6">Items</div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
                        <OrderedItems :subtotal="selectedOrder.subtotal" :cart_products="selectedOrder.cart_products" />                        
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4"> 
                        <div style="height: 250px; position: relative" class="full-width bg-grey-3">
                            <div v-if="selectedOrder.driver">
                               
                                <q-item class="bg-grey">
                                    <q-item-section avatar>
                                        <q-avatar color="secondary">
                                            <q-img :src="`/storage/${selectedOrder.driver.profile_pic}`" fit="cover" class="fit" v-if="selectedOrder.driver.profile_pic"/>
                                            <div v-else>{{ selectedOrder.driver.first_name[0] }}</div>
                                        </q-avatar>
                                    </q-item-section>
                                    <q-item-section>
                                        <q-item-label>{{ selectedOrder.driver.first_name + ' ' + selectedOrder.driver.last_name }}</q-item-label>
                                        <q-item-label label>{{ selectedOrder.driver.phone_number }}</q-item-label>
                                    </q-item-section>
                                </q-item>

                            </div>
                        </div>
                    </div>
                </div>
            </q-card-section>
        </q-card> -->
    </q-dialog>
    </div>
</template>