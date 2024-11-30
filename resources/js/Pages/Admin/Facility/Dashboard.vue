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

Chart.register(
    BarController,
    BarElement,
    CategoryScale,
    LinearScale,
    PieController,
    ArcElement,
    Legend,
    Tooltip,
    LineController,
    LineElement,
    PointElement,
    Title
);

defineOptions({ layout: AdminLayout });

const props = defineProps({
    revenue: Number,
    status_counts: Object,
    total_facilities: Object,
    booked_facilities: Object,
    top_facilities: Object,
    payment_method_revenue: Object,
    upcoming_check_ins: Object,
    upcoming_check_outs: Object,
    occupancy_rate: Object,
});

const revenueChartRef = ref(null);
const bookingStatusChartRef = ref(null);
const paymentMethodChartRef = ref(null);
const bookingsByPaymentMethodCanvas = ref(null)
onMounted(() => {
    // Revenue Chart
    if (revenueChartRef.value) {
        new Chart(revenueChartRef.value, {
            type: 'line',
            data: {
                labels: props.revenue.dates, // Assuming `revenue` contains `dates` array
                datasets: [
                    {
                        label: 'Revenue',
                        data: props.revenue.amounts, // Assuming `revenue` contains `amounts` array
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Revenue Over Time' },
                },
            },
        });
    }

    // Booking Status Chart
    if (bookingStatusChartRef.value) {
        new Chart(bookingStatusChartRef.value, {
            type: 'pie',
            data: {
                labels: Object.keys(props.status_counts),
                datasets: [
                    {
                        label: 'Booking Status',
                        data: Object.values(props.status_counts),
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Booking Status Distribution' },
                },
            },
        });
    }

    new Chart(bookingsByPaymentMethodCanvas.value.getContext('2d'), {
        type: 'bar',
        data: {
            labels: Object.keys(props.payment_method_revenue), // Payment Methods as labels
            datasets: [
                {
                    data: Object.values(props.payment_method_revenue), // Sales data for each payment method
                    backgroundColor: Object.values(props.payment_method_revenue).map(value => {
                        // Assign colors based on order value or other logic
                        if (value > 100) {
                            return 'rgba(75, 192, 192, 0.2)'; // Greenish for high values
                        } else if (value > 50) {
                            return 'rgba(255, 159, 64, 0.2)'; // Orange for medium values
                        } else {
                            return 'rgba(255, 99, 132, 0.2)'; // Red for low values
                        }
                    }),
                    borderColor: Object.values(props.payment_method_revenue).map(value => {
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

    // Payment Method Chart
    if (paymentMethodChartRef.value) {
        new Chart(paymentMethodChartRef.value, {
            type: 'bar',
            data: {
                labels: Object.keys(props.payment_method_revenue),
                datasets: [
                    {
                        label: 'Revenue by Payment Method',
                        data: Object.values(props.payment_method_revenue),
                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    title: { display: true, text: 'Revenue by Payment Method' },
                },
            },
        });
    }
});

const formatMoney = (money) => {
    return `₱${parseFloat(money).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
}
</script>

<template>
    <Head title="Facilities Dashboard" />
   <div :class="$q.screen.gt.sm ? 'q-pa-md' : 'q-pa-sm'">
    <div class="flex">
        <q-btn icon="menu" class="lt-md" @click="drawerStore.drawer = true" flat/>
        <div class="text-h6"> Dashboard </div>
    </div>
    <div class="row q-col-gutter-md ">
        <div class="col-3">
            <q-card borderd>
                <q-card-section>
                    <div class="text-subtitle1">Total Facilities</div>
                    <div class="text-h6">
                        {{ total_facilities }}
                    </div>
                    <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                </q-card-section>
            </q-card>
        </div>
        <div class="col-3">
            <q-card borderd>
                <q-card-section>
                    <div class="text-subtitle1">Total Revenue </div>
                    <div class="text-h6">
                        {{ formatMoney(revenue) }}
                    </div>
                    <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                </q-card-section>
            </q-card>
        </div>
        <div class="col-3">
            <q-card borderd>
                <q-card-section>
                    <div class="text-subtitle1">Booked Facilities</div>
                    <div class="text-h6">
                        {{ booked_facilities }}
                    </div>
                    <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                </q-card-section>
            </q-card>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-lg-6">
            <q-card class="q-pa-sm" style="max-height: 450px; height: 450px">
                <canvas ref="bookingsByPaymentMethodCanvas"></canvas>
            </q-card>
        </div>
        </div>
        </div>
</template>
