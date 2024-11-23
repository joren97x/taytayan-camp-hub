<script setup>
import DriverLayout from '@/Layouts/DriverLayout.vue'
import { onMounted, ref } from 'vue';
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

defineOptions({ layout: DriverLayout })
const props = defineProps({
    pending_orders: Object,
    active_deliveries: Object,
    total_earnings: Object,
    recent_orders: Object,
    earnings_data: Object
})

const earningsChart = ref(null)

onMounted(() => {
    new Chart(earningsChart.value.getContext('2d'), {
      type: 'line', // Line chart for earnings over time
      data: {
        labels: props.earnings_data.map(item => item.date), // e.g., "2024-11-01", "2024-11-02"
        datasets: [
          {
            label: 'Earnings',
            data: props.earnings_data.map(item => item.total_earnings), // earnings values
            borderColor: 'rgba(75, 192, 192, 1)',
            fill: false,
            tension: 0.1,
          },
        ],
      },
      options: {
        responsive: true,
        scales: {
          x: {
            title: {
              display: true,
              text: 'Date',
            },
          },
          y: {
            title: {
              display: true,
              text: 'Total Earnings (₱)',
            },
          },
        },
      },
    });
})

const formatMoney = (money) => {
    return `₱${parseFloat(money).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
}
</script>

<template>
  <Head title="Driver Dashboard" />
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
        <div class="flex">
            <div class="text-h6"> Dashboard </div>
        </div>
        <div class="row q-col-gutter-md ">
            <div class="col-3">
                <q-card borderd>
                    <q-card-section>
                        <div :class="$q.screen.gt.sm ? 'text-subtitle1' : ''">Total Earnings</div>
                        <div class="text-h6">
                            {{ total_earnings }}
                        </div>
                        <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-3">
                <q-card borderd>
                    <q-card-section>
                        <div :class="$q.screen.gt.sm ? 'text-subtitle1' : ''">Active Deliveries</div>
                        <div class="text-h6">
                            {{ active_deliveries }}
                        </div>
                        <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-3">
                <q-card borderd>
                    <q-card-section>
                        <div :class="$q.screen.gt.sm ? 'text-subtitle1' : ''">Pending Orders</div>
                        <div class="text-h6">
                            {{ pending_orders }}
                        </div>
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-6">
                <q-card class="q-pa-sm" style="max-height: 450px; height: 450px">
                    <canvas ref="earningsChart"></canvas>
                </q-card>
            </div>
            <div class="col-6">
                <q-card class="q-pa-sm">
                    <q-table
                        flat
                        title="Recent Orders"
                        :rows="recent_orders"
                        row-key="id"
                    >
                        <!-- <template v-slot:body-cell-created_at="props">
                            <q-td :props="props">
                                {{ formatDistanceToNow(props.row.created_at) }} ago 
                            </q-td>
                        </template> -->
                    </q-table>
                </q-card>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
