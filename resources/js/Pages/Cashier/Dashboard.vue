<script setup>

import CashierLayout from '@/Layouts/CashierLayout.vue'
import { useDrawerStore } from '@/Stores/DrawerStore'
import { Head } from '@inertiajs/vue3'

defineOptions({ layout: CashierLayout })

const drawerStore = useDrawerStore()
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
    </div>
</div>
</template>
