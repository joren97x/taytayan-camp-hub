<script setup>
import DriverLayout from '@/Layouts/DriverLayout.vue'
import { onMounted, ref } from 'vue'
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
Chart.register(BarController, BarElement, CategoryScale, LinearScale, PieController, ArcElement, Legend, Tooltip, LineController, LineElement, PointElement, Title);

defineOptions({ layout: DriverLayout })
const props = defineProps({
    active_deliveries: Object,
    total_earnings: Object,
    recent_delivery_orders: Object,
    earnings_data: Object,
    ready_for_delivery_orders: Number,
    total_deliveries: Number
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

const recentOrdersColumns = [
    { name: 'id', label: 'Order Id', align: 'center', field: 'id' },
    { name: 'user', label: 'User', align: 'left', field: 'user' },
    { name: 'status', label: 'Status', align: 'left', field: 'status',  },
    { name: 'total', label: 'total', align: 'right', field: 'total' },
    { name: 'payment_method', label: 'Payment Method', align: 'left', field: 'payment_method' },
    { name: 'created_at', label: 'Date', field: 'created_at', align: 'center' },
]

</script>

<template>
  <Head title="Driver Dashboard" />
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : 'q-pa-sm'">
        <div class="flex">
            <div class="text-h6"> Dashboard </div>
        </div>
        <div class="row q-col-gutter-sm ">
            <div class="col-3 col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                <q-card borderd>
                    <q-card-section>
                        <div :class="$q.screen.gt.sm ? 'text-subtitle1' : ''">Total Earnings</div>
                        <div class="text-h6">
                            {{ formatMoney(total_earnings) }}
                        </div>
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-3 col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                <q-card borderd>
                    <q-card-section>
                        <div :class="$q.screen.gt.sm ? 'text-subtitle1' : ''">Total Deliveries</div>
                        <div class="text-h6">
                            {{ total_deliveries }}
                        </div>
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-3 col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                <q-card borderd>
                    <q-card-section>
                        <div :class="$q.screen.gt.sm ? 'text-subtitle1' : ''">Active Deliveries</div>
                        <div class="text-h6">
                            {{ active_deliveries }}
                        </div>
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-3 col-xs-6 col-sm-6 col-md-3 col-lg-3 col-xl-3">
                <q-card borderd>
                    <q-card-section>
                        <div :class="$q.screen.gt.sm ? 'text-subtitle1' : ''">Ready For Delivery Orders</div>
                        <div class="text-h6">
                            {{ ready_for_delivery_orders }}
                        </div>
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-md-6 col-sm-12 col-xs-12 col-lg-6 col-xl-6">
                <q-card class="q-pa-sm" style="max-height: 450px; height: 450px">
                    <canvas ref="earningsChart"></canvas>
                </q-card>
            </div>
            <div class="col-6">
                <q-card class="q-pa-sm">
                    <q-table
                        flat
                        title="Recent Orders"
                        :rows="recent_delivery_orders"
                        row-key="id"
                        :columns="recentOrdersColumns"
                    >
                        <template v-slot:body-cell-created_at="props">
                            <q-td>
                                {{ date.formatDate(props.row.created_at, 'MMM D, YYYY') }}
                            </q-td>
                        </template>
                        <template v-slot:body-cell-total="props">
                            <q-td class="">
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
                                        <q-item-label caption class="ellipsis-2-lines q-mr-xl">{{ props.row.user.phone_number }}</q-item-label>
                                    </q-item-section>
                                </q-item>
                            </q-td>
                        </template>
                        
                    </q-table>
                </q-card>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
