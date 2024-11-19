<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useDrawerStore } from '@/Stores/DrawerStore';
import { onMounted, ref } from 'vue';
import { date } from 'quasar';
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
import { formatDistanceToNow } from 'date-fns';

Chart.register(BarController, BarElement, CategoryScale, LinearScale, PieController, ArcElement, Legend, Tooltip, LineController, LineElement, PointElement, Title);

defineOptions({ layout: AdminLayout });

const props = defineProps({
  totalSales: Object,
  products_sales: Object,
  tickets_sales: Object,
  booking_revenue: Object,
  sales_breakdown: Object,  // Ensure salesBreakdown has food, events, and bookings data
  newCustomersThisMonth: Object,
  salesTrend: Object,
  recent_transactions: Array,
  payment_counts: Object,
  sales_data: Object,
  user_labels: Object,
  user_data: Object
});

const drawerStore = useDrawerStore();
const salesBreakdownCanvas = ref(null);
const salesTrendCanvas = ref(null)
const paymentCanvas = ref(null)
const userRegistrationsChart = ref(null)

onMounted(() => {
    new Chart(salesBreakdownCanvas.value.getContext('2d'), {
        type: 'pie',
        data: {
        labels: ['Food Orders', 'Event Tickets', 'Facility Bookings'],
        datasets: [
            {
                label: 'Sales Breakdown',
                data: [
                    props.sales_breakdown.orders,
                    props.sales_breakdown.ticket_orders,
                    props.sales_breakdown.bookings
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.6)', // Food Orders color
                    'rgba(255, 159, 64, 0.6)', // Event Tickets color
                    'rgba(153, 102, 255, 0.6)' // Facility Bookings color
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
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
                    text: 'Sales Breakdown',
                    padding: {
                        top: 10,
                        bottom: 10
                    },
                }
            }
        }
    });
    new Chart(salesTrendCanvas.value.getContext('2d'), {
        type: 'line',
        data: {
            labels: props.sales_data.months,  // Month labels
            datasets: [
                {
                    label: 'Food Orders',
                    data: props.sales_data.product_sales, // Food Orders data
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true,
                },
                {
                    label: 'Event Tickets',
                    data: props.sales_data.event_sales, // Event Tickets data
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: true,
                },
                {
                    label: 'Facility Bookings',
                    data: props.sales_data.facility_sales, // Bookings data
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true,
                },
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Sales Trend',
                    padding: {
                        top: 10,
                        bottom: 10
                    },
                }
            }
        }
    });
    new Chart(paymentCanvas.value.getContext('2d'), {
        type: 'line',
        data: {
            labels: Object.keys(props.payment_counts),  // Month labels
            datasets: [{
                label: 'Total Sales',
                data: Object.values(props.payment_counts),  // Combined sales data
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                fill: true,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Sales Trend',
                    padding: {
                        top: 10,
                        bottom: 10
                    },
                }
            }
        }
    });
    new Chart(userRegistrationsChart.value.getContext('2d'), {
        type: 'line', // Use 'line' or 'bar' chart
        data: {
            labels: props.user_labels, // Labels from controller (dates)
            datasets: [{
                label: 'User Registrations',
                data: props.user_data,  // Data from controller (counts)
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'User Registrations Over the Last Month'
                }
            }
        }
    });

});

const columns = [
  { name: 'type', label: 'Type', align: 'left', field: 'type' },
  { name: 'user', label: 'User', align: 'left', field: 'user' },
  { name: 'status', label: 'Status', align: 'left', field: 'status',  },
  { name: 'amount', label: 'Amount', align: 'right', field: 'amount' },
  { name: 'payment_method', label: 'Payment Method', align: 'left', field: 'payment_method' },
  { name: 'created_at', label: 'Date', align: 'left', field: 'created_at' },
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
    <Head title="Dashboard" />
<div :class="$q.screen.gt.sm ? 'q-pa-md' : 'q-pa-sm'">
    <div class="flex">
        <q-btn icon="menu" class="lt-md" @click="drawerStore.drawer = true" flat/>
        <div class="text-h6"> Dashboard </div>
    </div>
    <div class="row q-col-gutter-md ">
        <div class="col-3">
            <q-card borderd>
                <q-card-section>
                    <div class="text-subtitle1">Total Sales All Time</div>
                    <div class="text-h6">
                        {{ formatMoney(totalSales) }}
                    </div>
                    <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                </q-card-section>
            </q-card>
        </div>
        <div class="col-3">
            <q-card borderd>
                <q-card-section>
                    <div class="text-subtitle1">Products Sales</div>
                    <div class="text-h6">
                        {{ formatMoney(products_sales) }}
                    </div>
                    <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                </q-card-section>
            </q-card>
        </div>
        <div class="col-3">
            <q-card borderd>
                <q-card-section>
                    <div class="text-subtitle1">Event Tickets Sales</div>
                    <div class="text-h6">
                        {{ formatMoney(tickets_sales) }}
                    </div>
                    <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                </q-card-section>
            </q-card>
        </div>
        <div class="col-3">
            <q-card borderd>
                <q-card-section>
                    <div class="text-subtitle1">Booking Revenue</div>
                    <div class="text-h6">
                        {{ formatMoney(booking_revenue) }}
                    </div>
                </q-card-section>
            </q-card>
        </div>
        <div class="col-4">
            <q-card class="q-pa-sm" style="max-height: 450px; height: 450px">
                <canvas ref="salesBreakdownCanvas"></canvas>
            </q-card>
        </div>
        <div class="col-8">
            <q-card class="q-pa-sm" style="max-height: 450px; height: 450px">
                <canvas ref="salesTrendCanvas"></canvas>
            </q-card>
        </div>
        <div class="col-6">
            <q-card borderd>
                <q-table
                    flat
                    title="Recent Transactions"
                    :rows="Object.values(recent_transactions)"
                    :columns="columns"
                    row-key="id"
                    :paginatin="pagination"
                >
                    <template v-slot:body-cell-created_at="props">
                        <q-td :props="props">
                            {{ formatDistanceToNow(props.row.created_at) }} ago 
                        </q-td>
                    </template>
                </q-table>
            </q-card>
        </div>
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
        </div>
        
    </div>
</div>
</template>