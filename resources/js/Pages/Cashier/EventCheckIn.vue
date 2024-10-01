<script setup>

import CashierLayout from '@/Layouts/CashierLayout.vue'
import { ref, computed } from 'vue'
import EventCheckinDialog from './Partials/EventCheckinDialog.vue'

defineOptions({
    layout: CashierLayout
})

const props = defineProps({
    event: Object,
    tickets: Object
})

const filter = ref('')
const columns = [
    { name: 'attendee', align: 'center', label: 'Attendee Name', field: 'attendee', sortable: true },
    { name: 'status', align: 'center', label: 'Status', field: 'status', sortable: true },
    { name: 'actions', align: 'center', label: '', field: 'actions', sortable: true },
]
const checkedIn = computed(() => {
    return props.tickets.filter((tix) => tix.status == 'used') 
})

</script>

<template>
    
    {{ checkedIn.length }}
    <div class="q-pa-md">
        <q-card class="q-mb-md" bordered flat>
            <q-card-section>
                <q-btn label="Go Back" no-caps icon="arrow_back" flat class="q-pa-none" />
                <div class="text-h6">Check-in</div>
                <div>Check in attendees using their name or email</div>
                <div class="row items-center flex">
                    <div class="col-10"><q-linear-progress :value="checkedIn.length / tickets.length" /></div>
                    <div class="col-2 text-center text-subtitle1">
                        {{ checkedIn.length }} / {{ tickets.length }}
                    </div>
                </div>
            </q-card-section>
        </q-card>
        <q-card bordered flat>
            <q-table
                class="my-sticky-header-column-table"
                flat
                :grid="$q.screen.lt.md"
                title="Treats"
                :rows="tickets"
                :columns="columns"
                row-key="name"
                :filter="filter"
            >
                <template v-slot:top>
                    <p class="text-h6 q-pt-md">Attendees</p>
                    <q-space />
                    <q-input filled dense label="Search..." v-model="filter" class="q-mx-md" debounce="300" color="primary">
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
                        {{ props.row.ticket_holder.name }}
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <EventCheckinDialog :ticket="props.row" />
                    </q-td>
                </template>
            </q-table>
        </q-card>
    </div>

</template>
