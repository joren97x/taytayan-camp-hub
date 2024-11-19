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
    event_data: Object,
    event_labels: Object,
    total_events: Object,
    total_revenue: Object,
    total_tickets_sold: Object,
    tickets_sold_today: Object,
});

const eventsCanva = ref(null)
onMounted(() => {
    new Chart(eventsCanva.value.getContext('2d'), {
        type: 'bar',
        data: {
            labels: props.event_labels, // Dates
            datasets: [{
                label: 'Tickets Sold',
                data: props.event_data, // Ticket counts
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
                    text: 'Tickets Sold per Event',
                }
            }
        }
    });
})
const formatMoney = (money) => {
    return `₱${parseFloat(money).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`
}
</script>

<template>
    <Head title="Events Dashboard" />
<div :class="$q.screen.gt.sm ? 'q-pa-md' : 'q-pa-sm'">
    <div class="flex">
        <q-btn icon="menu" class="lt-md" @click="drawerStore.drawer = true" flat/>
        <div class="text-h6"> Dashboard </div>
    </div>
    <div class="row q-col-gutter-md ">
        <div class="col-3">
            <q-card borderd>
                <q-card-section>
                    <div class="text-subtitle1">Total Events</div>
                    <div class="text-h6">
                        {{ total_events }}
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
                        {{ formatMoney(total_revenue) }}
                    </div>
                    <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                </q-card-section>
            </q-card>
        </div>
        <div class="col-3">
            <q-card borderd>
                <q-card-section>
                    <div class="text-subtitle1">Total Tickets Sold</div>
                    <div class="text-h6">
                        {{ formatMoney(total_tickets_sold) }}
                    </div>
                    <q-icon name="attach_money" size="xl" class="absolute-top-right q-mr-md q-mt-lg"></q-icon>
                </q-card-section>
            </q-card>
        </div>
        <div class="col-3">
            <q-card borderd>
                <q-card-section>
                    <div class="text-subtitle1">Tickets Sold Today</div>
                    <div class="text-h6">
                        {{ tickets_sold_today }}
                    </div>
                </q-card-section>
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