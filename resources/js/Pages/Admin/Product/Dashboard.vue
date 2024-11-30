<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useDrawerStore } from '@/Stores/DrawerStore';
import { onMounted, ref } from 'vue';
import { formatDistanceToNow } from 'date-fns';
import {
    Chart,
    CategoryScale,
    LinearScale,
    PieController,
    BarController,
    BarElement,
    ArcElement,
    Legend,
    Tooltip,
    LineController,
    LineElement,
    PointElement,
    Title,
} from 'chart.js';

Chart.register(BarController, BarElement, CategoryScale, LinearScale, PieController, ArcElement, Legend, Tooltip, LineController, LineElement, PointElement, Title);

defineOptions({ layout: AdminLayout });

const props = defineProps({
    total_orders: Number,
    total_revenue: Number,
    orders_by_status: Object,
    popular_products: Object,
    orders_by_payment_method: Object,
    orders_by_payment_method: Object,
    orders_by_mode: Object,
    featured_products: Object,
    top_selling_product: Object,
    average_order_value: Number,
    pending_orders: Number,
    least_selling_products: Object
});

   
const drawerStore = useDrawerStore();
const ordersByStatusCanvas = ref(null);
const ordersByPaymentMethodCanvas = ref(null)
const ordersByModeCanvas = ref(null)
const popularProductsCanvas = ref(null)

onMounted(() => {
    new Chart(ordersByModeCanvas.value.getContext('2d'), {
        type: 'pie',
        data: {
        labels: Object.keys(props.orders_by_mode),
        datasets: [
                {
                    data: Object.values(props.orders_by_mode),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            return `${context.label}: $${value.toLocaleString()}`;
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Orders By Mode',
                    padding: {
                        top: 10,
                        bottom: 10
                    },
                }
            }
        }
    });
    new Chart(ordersByPaymentMethodCanvas.value.getContext('2d'), {
        type: 'bar',
        data: {
            labels: Object.keys(props.orders_by_payment_method), // Payment Methods as labels
            datasets: [
                {
                    data: Object.values(props.orders_by_payment_method), // Sales data for each payment method
                    backgroundColor: Object.values(props.orders_by_payment_method).map(value => {
                        // Assign colors based on order value or other logic
                        if (value > 100) {
                            return 'rgba(75, 192, 192, 0.2)'; // Greenish for high values
                        } else if (value > 50) {
                            return 'rgba(255, 159, 64, 0.2)'; // Orange for medium values
                        } else {
                            return 'rgba(255, 99, 132, 0.2)'; // Red for low values
                        }
                    }),
                    borderColor: Object.values(props.orders_by_payment_method).map(value => {
                        // Matching border colors
                        if (value > 100) {
                            return 'rgba(75, 192, 192, 1)';
                        } else if (value > 50) {
                            return 'rgba(255, 159, 64, 1)';
                        } else {
                            return 'rgba(255, 99, 132, 1)';
                        }
                    }),
                    label: 'Total Sales by Payment Method', // Main dataset label
                    borderWidth: 1,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw;
                            const label = context.label;
                            return `${label}: ${value} Orders`; // Label with value
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Orders By Payment Method',
                    padding: {
                        top: 10,
                        bottom: 10
                    },
                }
            }
        }
    });
});

const columns = [
  { name: 'product', label: 'Product', align: 'left', field: 'product' },
  { name: 'price', label: 'Price', align: 'center', field: 'price' },
  { name: 'quantity', label: 'Total Sold', align: 'center', field: 'quantity' },
]

const pagination = ref({
    page: 1,
    rowsPerPage: 10,
})

const formatMoney = (money) => {
    return `₱${parseFloat(money).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
}

</script>

<template>
    <Head title="Products Dashboard" />
<div :class="$q.screen.gt.sm ? 'q-pa-md' : 'q-pa-sm'">
    <div class="flex">
        <q-btn icon="menu" class="lt-md" @click="drawerStore.drawer = true" flat/>
        <div class="text-h6"> Dashboard </div>
    </div>
    <div class="row q-col-gutter-md ">
        <div class="col-3">
            <q-card borderd>
                <q-card-section>
                    <div class="text-subtitle1">Total Revenue</div>
                    <div class="text-h6">
                        {{ formatMoney(total_revenue) }}
                        <!-- {{ total_revenue }} -->
                    </div>
                    <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                </q-card-section>
            </q-card>
        </div>
        <div class="col-3">
            <q-card borderd>
                <q-card-section>
                    <div class="text-subtitle1">Total Orders</div>
                    <div class="text-h6">{{ total_orders }}</div>
                    <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                </q-card-section>
            </q-card>
        </div>
        <div class="col-3">
            <q-card borderd>
                <q-card-section>
                    <div class="text-subtitle1">Average Order Value</div>
                    <div class="text-h6">
                        {{ formatMoney(average_order_value) }}
                    </div>
                    <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                </q-card-section>
            </q-card>
        </div>
        <div class="col-3">
            <q-card borderd>
                <q-card-section>
                    <div class="text-subtitle1">Pending Orders</div>
                    <div class="text-h6">{{ pending_orders }}</div>
                    <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                </q-card-section>
            </q-card>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-lg-6">
            <!-- <q-card class="q-pa-sm" style="max-height: 450px; height: 450px">
                <canvas ref="ordersByStatusCanvas"></canvas>
            </q-card> -->
            <q-card class="q-pa-sm" style="max-height: 450px; height: 450px">
                <canvas ref="ordersByModeCanvas"></canvas>
            </q-card>
        </div>
        
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-lg-6">
            <q-card class="q-pa-sm" style="max-height: 450px; height: 450px">
                <canvas ref="ordersByPaymentMethodCanvas"></canvas>
            </q-card>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <q-card bordered>
                <q-table
                    flat
                    title="Most Popular Products"
                    :rows="popular_products"
                    :columns="columns"
                    row-key="id"
                    :paginatin="pagination"
                >
                    <template v-slot:body-cell-product="props">
                        <q-td :props="props">
                            <q-item class="q-pr-none">
                                <q-item-section avatar>
                                    <q-img :src="`/storage/${props.row.photo}`" height="50px"></q-img>
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label>{{ props.row.name }}</q-item-label>
                                    <q-item-label caption>{{ props.row.description }}</q-item-label>
                                </q-item-section>
                            </q-item>
                        </q-td>
                    </template>
                    <template v-slot:body-cell-price="props">
                        <q-td :props="props">
                            {{ formatMoney(props.row.price) }}
                        </q-td>
                    </template>
                </q-table>
            </q-card>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <q-card bordered>
                <q-table
                    flat
                    title="Least Selling Products"
                    :rows="least_selling_products"
                    :columns="columns"
                    row-key="id"
                    :paginatin="pagination"
                >
                    <template v-slot:body-cell-product="props">
                        <q-td :props="props">
                            <q-item class="q-pr-none">
                                <q-item-section avatar>
                                    <q-img :src="`/storage/${props.row.photo}`" height="50px"></q-img>
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label>{{ props.row.name }}</q-item-label>
                                    <q-item-label caption>{{ props.row.description }}</q-item-label>
                                </q-item-section>
                            </q-item>
                        </q-td>
                    </template>
                    <template v-slot:body-cell-price="props">
                        <q-td :props="props">
                            {{ formatMoney(props.row.price) }}
                        </q-td>
                    </template>
                </q-table>
            </q-card>
        </div>
        <!-- <div class="col-6">
            <q-card class="q-pa-sm" style="max-height: 450px; height: 450px">
                <canvas ref="ordersByModeCanvas"></canvas>
            </q-card>
        </div> -->
        <!-- <div class="col-6">
            <q-card class="q-pa-sm" style="max-height: 450px; height: 450px">
                <canvas ref="popularProductsCanvas"></canvas>
            </q-card>
        </div> -->
<!--         
        <div class="col-6">
            <q-card class="q-pa-sm full-height">
                <canvas ref="userRegistrationsChart"></canvas>
            </q-card>
        </div>
        <div class="col-6">
            <q-card class="q-pa-sm" style="max-height: 100%; height: 100%">
                <canvas ref="paymentCanvas"></canvas>
            </q-card>
        </div>
        <div class="col-6">
            <q-card class="q-pa-sm" style="max-height: 350px; height: 350px">
                <canvas ref="eventsCanva"></canvas>
            </q-card>
        </div> -->
    </div>
</div>
</template>