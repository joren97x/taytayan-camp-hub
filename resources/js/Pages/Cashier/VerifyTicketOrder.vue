<script setup>

import { Head } from '@inertiajs/vue3'
import CashierLayout from '@/Layouts/CashierLayout.vue'
import { date, useQuasar } from 'quasar'
import { ref } from 'vue'
import EventCheckinDialog from './Partials/EventCheckinDialog.vue'
import { useDrawerStore } from '@/Stores/DrawerStore'

defineOptions({
    layout: CashierLayout
})

defineProps({
    ticket_order: Object
})

// const selected = ref([])
const drawerStore = useDrawerStore()
const $q = useQuasar()
const filter = ref('')
const columns = [
    { name: 'attendee', align: 'center', label: 'Name', field: 'attendee', sortable: true },
    { name: 'status', align: 'center', label: 'Status', field: 'status', sortable: true },
    { name: 'actions', align: 'center', label: '', field: 'actions', sortable: true },
]
</script>

<template>
    <Head />
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
        <q-card flat style="z-index: 400;">
            <q-card-section class="flex">
                <q-btn icon="menu" flat dense @click="drawerStore.drawer =true" class="lt-md q-mr-sm"/>
                <div class="text-h6">Ticket Order</div>
            </q-card-section>
            <!-- <div class="text-h6 text-center col-12" style="position: relative">
                Order {{ ticket_order.id }}
                <q-btn icon="arrow_back" flat class="absolute-left" label="Go Back" no-caps />
            </div> -->
            <q-card-section class="row justify-between">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
                    <q-card flat>
                        <div class="full-width" style="height: 200px; position: relative; overflow: hidden;">
                            <div class="blurred-background" :style="`background-image: url('/storage/${ticket_order.event.cover_photo}');`"></div>
                            <q-img 
                                :src="`/storage/${ticket_order.event.cover_photo}`"
                                class="content-wrapper" 
                                height="100%"
                                fit="contain"
                                style="position: relative; z-index: 2;" 
                            />
                        </div>
                        <!-- <q-img :src="`/storage/${ticket_order.event.cover_photo}`" fit="contain" height="200px" class="rounded-borders" /> -->
                        <q-card-section>
                            <div class="text-subtitle1 text-weight-bold">{{ ticket_order.event.title }}</div>
                            <div>
                                <q-icon name="event" size="xs" class="q-mr-sm" /> 
                                {{ date.formatDate(ticket_order.event.date, 'MMMM D, YYYY') }}
                            </div>
                        </q-card-section>
                    </q-card>
                </div>
                <div :class="['col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8', $q.screen.gt.sm ? ' q-pa-md q-mt-sm' : '']">
                    <div class="text-h6">Order Details</div>
                    <div class="row">
                        <q-item class="col-4">
                            <q-item-section>
                                <q-item-label caption>Buyer Name</q-item-label>
                                <q-item-label >John Doe</q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-item class="col-4">
                            <q-item-section>
                                <q-item-label caption>Order Total</q-item-label>
                                <q-item-label>P{{ ticket_order.amount }}</q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-item class="col-4">
                            <q-item-section>
                                <q-item-label caption>Payment Details</q-item-label>
                                <q-item-label>{{ ticket_order.payment_method }}</q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-item class="col-4">
                            <q-item-section>
                                <q-item-label caption>Purchase Date</q-item-label>
                                <q-item-label>{{ date.formatDate(ticket_order.created_at, 'MMMM D, YYYY') }}</q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-item class="col-4">
                            <q-item-section>
                                <q-item-label caption>Total Amount</q-item-label>
                                <q-item-label>John Doe</q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-item class="col-4">
                            <q-item-section>
                                <q-item-label caption>Status</q-item-label>
                                <q-item-label>
                                    <q-chip>{{ ticket_order.status }}</q-chip>
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                    </div>
                </div>
            </q-card-section>
        </q-card>
        <q-card flat bordered class="q-mt-md">
            <q-table
                class="my-sticky-header-column-table"
                flat
                :grid="$q.screen.lt.md"
                :rows="ticket_order.ticket_order_items"
                :columns="columns"
                row-key="name"
                :filter="filter"
            >
                <template v-slot:top>
                    <div class="text-h6">Attendees</div>
                    <q-space />
                    <q-input rounded outlined dense label="Search..." v-model="filter" debounce="300" color="primary">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                    <!-- <Link :href="route('admin.facilities.create')">
                        <q-btn no-caps color="primary">Create Facility</q-btn>
                    </Link> -->
                </template>
                <template v-slot:body-cell-attendee="props">
                    <q-td :props="props">
                        {{ props.row.ticket.ticket_holder.name }}
                    </q-td>
                </template>
                <template v-slot:body-cell-status="props">
                    <q-td :props="props">
                        {{ props.row.ticket.status }}
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <EventCheckinDialog :ticket="props.row.ticket" />
                    </q-td>
                </template>
                <template v-slot:item="props">
                    <q-card class="col-12 q-mb-md" bordered flat>
                        <q-card-section>
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-caption text-grey">
                                        Name
                                    </div>
                                    {{ props.row.ticket.ticket_holder.name }}
                                </div>
                                <div class="col-6">
                                    <div class="text-caption text-grey">
                                        Status
                                    </div>
                                    {{ props.row.ticket.status }}
                                </div>
                                <div class="col-12">
                                    <EventCheckinDialog :ticket="props.row.ticket" />
                                </div>
                            </div>
                        </q-card-section>
                    </q-card>
                </template>
            </q-table>
        </q-card>
    </div>
</template>

  
<style scoped>

/* CSS IS HARD FRRR(i used chatGPT XD) */
/* Blurred background */
.blurred-background {
   /* Background image you want to blur */
  background-size: cover;
  background-position: center;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  filter: blur(8px); /* Apply blur effect */
  z-index: 1; /* Keep it behind the foreground content */
}

/* Foreground image (clear, no blur) */
.content-wrapper {
  position: relative;
  z-index: 2; /* Ensure it's on top of the blurred background */
}

</style>