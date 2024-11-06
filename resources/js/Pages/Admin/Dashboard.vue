<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { useDrawerStore } from '@/Stores/DrawerStore'
import { onMounted, ref } from 'vue'

defineOptions({ layout: AdminLayout })

defineProps({
    recent_transactions: Object
})

const drawerStore = useDrawerStore()
const columns = [
    { name: 'type', label: 'type', align: 'center', field: 'type', sortable: true },
    { name: 'user', label: 'user', align: 'center', field: 'user', sortable: true },
    { name: 'status', label: 'status', align: 'center', field: 'status', sortable: true },
    { name: 'amount', label: 'amount', align: 'center', field: 'amount', sortable: true },
    { name: 'payment_method', label: 'payment_method', align: 'center', field: 'payment_method', sortable: true },
    { name: 'created_at', label: 'created_at', align: 'center', field: 'created_at', sortable: true },
    // { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

import { Chart, CategoryScale, LinearScale, BarElement, BarController, Legend } from 'chart.js';
Chart.register(CategoryScale, LinearScale, BarElement, BarController, Legend);
const salesCanvas = ref(null);

onMounted(() => {
  const ctx = salesCanvas.value.getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Nov 12', 'Nov 19', 'Nov 26', 'Dec 3', 'Dec 12'],
      datasets: [
        {
          label: 'Delivery',
          data: [400, 350, 420, 370, 380],
          backgroundColor: 'rgba(0, 150, 150, 0.7)',
        },
        {
          label: 'Pickup',
          data: [200, 250, 220, 230, 210],
          backgroundColor: 'rgba(255, 165, 0, 0.7)',
        }
      ]
    },
    options: {
      plugins: {
        legend: {
          display: true,
          position: 'bottom'
        }
      },
      scales: {
        x: {
          stacked: true
        },
        y: {
          stacked: true,
          beginAtZero: true
        }
      }
    }
  });
});

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
                        <div class="text-subtitle1">Revenue</div>
                        <div class="text-h6">P800.00</div>
                        <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-3">
                <q-card borderd>
                    <q-card-section>
                        <div class="text-subtitle1">Active Bookings</div>
                        <div class="text-h6">P800.00</div>
                        <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-3">
                <q-card borderd>
                    <q-card-section>
                        <div class="text-subtitle1">Tickets Sold</div>
                        <div class="text-h6">P800.00</div>
                        <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-3">
                <q-card borderd>
                    <q-card-section>
                        <div class="text-subtitle1">Active Orders</div>
                        <div class="text-h6">P800.00</div>
                        <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-12">
                <div class="text-h6">Sales</div>
                <div>
                    <canvas ref="salesCanvas"></canvas>
                </div>
            </div>
            {{ recent_transactions }}
            <div class="col-7">
                <q-card borderd>
                    <q-table
                        class="my-sticky-header-column-table"
                        flat
                        title="Recent Transactions"
                        v-if="recent_transactions.length > 0"
                        :rows="recent_transactions"
                        :columns="columns"
                        row-key="index"
                        virtual-scroll
                    >
                        <template v-slot:body-cell-actions="props">
                            <q-td :props="props">
                                <q-btn no-caps unelevated @click="showDeleteCategoryDialog(props.row)" label="Delete"/>
                            </q-td>
                        </template>
                    </q-table>
                </q-card>
            </div>
            <div class="col-5">
                <q-card borderd style="padding: 170px">
                    some chart
                </q-card>
            </div>
            <div class="col-12">
                <q-card borderd style="padding: 100px">
                    idk
                </q-card>
            </div>
        </div>
    </div>
    {{ user_chart }}
</template>