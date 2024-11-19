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
  revenue: Object,
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
</script>

<template>
  <div>
    <Head title="Admin Dashboard" />
    <div class="q-px-lg q-py-md">
      <q-page class="q-gutter-lg">
        <q-card class="q-pa-md">
          <div class="text-h5 text-center">Admin Dashboard</div>
        </q-card>
        
        <!-- Metrics Section -->
        <q-card class="q-my-md">
          <div class="q-pa-md row q-col-gutter-md">
            <q-card-section class="col-xs-12 col-md-4 text-center">
              <div class="text-h6">Occupancy Rate</div>
              <div class="text-h5 text-primary">{{ props.occupancy_rate }}%</div>
            </q-card-section>
            <q-card-section class="col-xs-12 col-md-4 text-center">
              <div class="text-h6">Total Facilities</div>
              <div class="text-h5">{{ props.total_facilities }}</div>
            </q-card-section>
            <q-card-section class="col-xs-12 col-md-4 text-center">
              <div class="text-h6">Booked Facilities</div>
              <div class="text-h5 text-success">{{ props.booked_facilities }}</div>
            </q-card-section>
          </div>
        </q-card>

        <!-- Charts Section -->
        <q-card class="q-my-md">
          <q-card-section>
            <canvas ref="revenueChartRef"></canvas>
          </q-card-section>
        </q-card>

        <q-card class="q-my-md">
          <q-card-section>
            <canvas ref="bookingStatusChartRef"></canvas>
          </q-card-section>
        </q-card>

        <q-card class="q-my-md">
          <q-card-section>
            <canvas ref="paymentMethodChartRef"></canvas>
          </q-card-section>
        </q-card>

        <!-- Top Facilities Section -->
        <q-card class="q-my-md">
          <q-card-section>
            <div class="text-h6">Top Facilities</div>
            <q-list>
              <q-item v-for="facility in top_facilities" :key="facility.id">
                <q-item-section>{{ facility.name }}</q-item-section>
                <q-item-section>{{ facility.revenue | currency }}</q-item-section>
              </q-item>
            </q-list>
          </q-card-section>
        </q-card>

        <!-- Upcoming Check-Ins and Check-Outs -->
        <q-card class="q-my-md">
          <q-card-section>
            <div class="text-h6">Upcoming Check-Ins</div>
            <q-list>
              <q-item v-for="checkIn in props.upcoming_check_ins" :key="checkIn.id">
                <q-item-section>{{ checkIn.facility.name }}</q-item-section>
                <q-item-section>{{ formatDistanceToNow(new Date(checkIn.check_in)) }}</q-item-section>
              </q-item>
            </q-list>
          </q-card-section>
          <q-card-section>
            <div class="text-h6">Upcoming Check-Outs</div>
            <q-list>
              <q-item v-for="checkOut in props.upcoming_check_outs" :key="checkOut.id">
                <q-item-section>{{ checkOut.facility.name }}</q-item-section>
                <q-item-section>{{ formatDistanceToNow(new Date(checkOut.check_out)) }}</q-item-section>
              </q-item>
            </q-list>
          </q-card-section>
        </q-card>
      </q-page>
    </div>
  </div>
</template>
