<script setup>

import CashierLayout from '@/Layouts/CashierLayout.vue'
import { useDrawerStore } from '@/Stores/DrawerStore'
import { Head } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'
import { date } from 'quasar'

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

defineOptions({ layout: CashierLayout })
const props = defineProps({
    today_revenue: Object,
    total_transactions: Object,
    pending_payments: Object,
    payment_method_breakdown: Object,
    recent_transactions: Object,
    top_selling_products: Object,
    least_selling_products: Object,
    weekly_sales: Object
})

const paymentMethodBreakdownCanvas = ref(null)
const weeklySalesChart = ref(null)
const drawerStore = useDrawerStore()
const formatMoney = (money) => {
    return `₱${parseFloat(money).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
}

const columns = [
  { name: 'product_id', label: 'Id', align: 'left', field: 'product_id' },
  { name: 'product', label: 'Product', align: 'left', field: 'product' },
  { name: 'total_quantity', label: 'Total Quantity', align: 'left', field: 'total_quantity' },
]

const recentTransactionsColumns = [
  { name: 'type', label: 'Type', align: 'left', field: 'type' },
  { name: 'user', label: 'User', align: 'left', field: 'user' },
  { name: 'status', label: 'Status', align: 'center', field: 'status',  },
  { name: 'total', label: 'total', align: 'center', field: 'total' },
  { name: 'created_at', label: 'Date', align: 'center', field: 'created_at' },
]

const pagination = ref({
    page: 1,
    rowsPerPage: 10,
})
 
onMounted(() => {
    new Chart(weeklySalesChart.value.getContext('2d'), {
        type: 'line',
        data: {
                labels: Object.keys(props.weekly_sales),
                datasets: [
                    {
                        label: 'Sales',
                        data: Object.values(props.weekly_sales),
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                    },
                ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: { display: true, text: 'Weekly Sales Chart' },
            },
        },
    });
})

</script>

<template>
    <Head title="Dashboard" />
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : 'q-pa-sm'">
    <div class="flex items-center">
        <q-btn icon="menu" round class="lt-md" @click="drawerStore.drawer = true" flat/>
        <div class="text-h6"> Dashboard </div>
    </div>
    <div class="row q-col-gutter-sm ">
        <div class="col-3 col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
            <q-card borderd>
                <q-card-section>
                    <div :class="$q.screen.gt.sm ? 'text-subtitle1' : ''">Today Revenue</div>
                    <div class="text-h6">
                        {{ formatMoney(today_revenue) }}
                    </div>
                </q-card-section>
            </q-card>
        </div>
        <div class="col-3 col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
            <q-card borderd>
                <q-card-section>
                    <div :class="$q.screen.gt.sm ? 'text-subtitle1' : ''">{{ $q.screen.gt.sm ? 'Total' : '' }} Transactions Today</div>
                    <div class="text-h6">
                        {{ total_transactions }}
                    </div>
                    <!-- <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon> -->
                </q-card-section>
            </q-card>
        </div>
        <div class="col-3 col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
            <q-card borderd>
                <q-card-section>
                    <div :class="$q.screen.gt.sm ? 'text-subtitle1' : ''">Pending Payments</div>
                    <div class="text-h6">
                        {{ pending_payments }}
                    </div>
                </q-card-section>
            </q-card>
        </div>
        <!-- <div class="col-3 col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
            <q-card borderd>
                <q-card-section>
                    <div :class="$q.screen.gt.sm ? 'text-subtitle1' : ''">Booking Revenue</div>
                    <div class="text-h6">
                        {{ weekly_sales }}
                    </div>
                </q-card-section>
            </q-card>
        </div> -->
        <div class="col-6 col-md-6 col-sm-12 col-xs-12 col-lg-6 col-xl-6">
            <q-card borderd>
                <q-table
                    flat
                    title="Recent Transactions"
                    :rows="recent_transactions"
                    row-key="id"
                    :columns="recentTransactionsColumns"
                    :grid="$q.screen.lt.md"
                    :pagination="pagination"
                >
                    <template v-slot:body-cell-created_at="props">
                        <q-td :props="props">
                            {{ date.formatDate(props.row.created_at, 'MMM D, YYYY') }}
                        </q-td>
                    </template>
                    <template v-slot:body-cell-total="props">
                        <q-td :props="props">
                            {{ formatMoney(props.row.total) }}
                        </q-td>
                    </template>
                    <template v-slot:body-cell-user="props">
                        <q-td :props="props">
                            <q-item class="q-pa-none">
                                <q-item-section avatar>
                                    <q-avatar class="text-white" color="grey">
                                        <q-img v-if="props.row.user.profile_pic" :src="`/storage/${props.row.user.profile_pic}`" class="fit" fit="cover"/>
                                        <div v-else>
                                            {{ props.row.user.first_name[0] }}
                                        </div>
                                    </q-avatar> 
                                </q-item-section>
                                <q-item-section class="items-start">
                                    <q-item-label>{{ props.row.user.first_name + ' ' + props.row.user.last_name  }}</q-item-label>
                                    <q-item-label caption class="ellipsis-2-lines q-mr-xl">{{ props.row.user.email }}</q-item-label>
                                    <!-- <q-item-label caption >{{ formatMoney(props.row.user.price) }}</q-item-label> -->
                                </q-item-section>
                            </q-item>
                        </q-td>
                    </template>
                </q-table>
            </q-card>
        </div>
        <div class="col-6 col-md-6 col-sm-12 col-xs-12 col-lg-6 col-xl-6">
            <!-- <q-card borderd>
                {{ payment_method_breakdown }} turn it into chart 
            </q-card> -->
            <q-card>
                <q-card-section>
                    <canvas ref="weeklySalesChart"></canvas>
                </q-card-section>
            </q-card>
        </div>
        <div class="col-6 col-md-6 col-sm-12 col-xs-12 col-lg-6 col-xl-6">
            <q-card borderd>
                <q-table
                    flat
                    title="Top Selling Products"
                    :rows="top_selling_products"
                    row-key="id"
                    :grid="$q.screen.lt.md"
                    :columns="columns"
                    :paginatin="pagination"
                >
                    <template v-slot:body-cell-product="props">
                        <q-td :props="props">
                            <q-item class="q-pa-none">
                                <q-item-section avatar>
                                    <q-img :src="`/storage/${props.row.product.photo}`" fit="contain"height="60px" width="60px" class="rounded-borders" />
                                </q-item-section>
                                <q-item-section class="items-start">
                                    <q-item-label>{{ props.row.product.name }}</q-item-label>
                                    <q-item-label caption class="ellipsis-2-lines q-mr-xl">{{ props.row.product.description }}</q-item-label>
                                    <q-item-label caption >{{ formatMoney(props.row.product.price) }}</q-item-label>
                                </q-item-section>
                            </q-item>
                        </q-td>
                    </template>
                    <template v-slot:item="props">
                        <div class="col-12 q-mb-xs">
                            <q-card class="q-mx-sm" bordered flat>
                                <q-card-section class="q-pa-none q-py-sm">
                                    <q-item class="q-pa-none">
                                        <q-item-section avatar>
                                            <q-img :src="`/storage/${props.row.product.photo}`" fit="contain"height="60px" width="60px" class="rounded-borders" />
                                        </q-item-section>
                                        <q-item-section class="items-start">
                                            <q-item-label>{{ props.row.product.name }}</q-item-label>
                                            <q-item-label caption class="ellipsis-2-lines q-mr-xl">{{ props.row.product.description }}</q-item-label>
                                            <q-item-label caption >{{ formatMoney(props.row.product.price) }}</q-item-label>
                                        </q-item-section>
                                    </q-item>
                                </q-card-section>
                            </q-card>
                        </div>
                    </template>
                </q-table>
            </q-card>
        </div>
        <div class="col-6 col-md-6 col-sm-12 col-xs-12 col-lg-6 col-xl-6">
            <q-card borderd>
                <q-table
                    flat
                    title="Least Selling Products"
                    :rows="least_selling_products"
                    row-key="id"
                    :columns="columns"
                    :paginatin="pagination"
                    :grid="$q.screen.lt.md"
                >
                    <template v-slot:body-cell-product="props">
                        <q-td :props="props">
                            <q-item class="q-pa-none">
                                <q-item-section avatar>
                                    <q-img :src="`/storage/${props.row.photo}`" fit="contain"height="60px" width="60px" class="rounded-borders" />
                                </q-item-section>
                                <q-item-section class="items-start">
                                    <q-item-label>{{ props.row.name }}</q-item-label>
                                    <q-item-label caption class="ellipsis-2-lines q-mr-xl">{{ props.row.description }}</q-item-label>
                                    <q-item-label caption >{{ formatMoney(props.row.price) }}</q-item-label>
                                </q-item-section>
                            </q-item>
                        </q-td>
                    </template>
                    <template v-slot:body-cell-product_id="props">
                        <q-td :props="props">
                            {{ props.row.id }}
                        </q-td>
                    </template>
                    <template v-slot:item="props">
                        <div class="col-12 q-mb-xs">
                            <q-card class="q-mx-sm" bordered flat>
                                <q-card-section class="q-pa-none q-py-sm">
                                    <q-item class="q-pa-none">
                                        <q-item-section avatar>
                                            <q-img :src="`/storage/${props.row.photo}`" fit="contain"height="60px" width="60px" class="rounded-borders" />
                                        </q-item-section>
                                        <q-item-section class="items-start">
                                            <q-item-label>{{ props.row.name }}</q-item-label>
                                            <q-item-label caption class="ellipsis-2-lines q-mr-xl">{{ props.row.description }}</q-item-label>
                                            <q-item-label caption >{{ formatMoney(props.row.price) }}</q-item-label>
                                        </q-item-section>
                                    </q-item>
                                </q-card-section>
                            </q-card>
                        </div>
                    </template>
                </q-table>
            </q-card>
        </div>
    </div>
</div>
</template>
