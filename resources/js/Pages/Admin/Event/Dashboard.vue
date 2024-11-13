<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, computed } from 'vue'
import { useQuasar, date } from 'quasar'
import { Link } from '@inertiajs/vue3'

defineOptions({ layout: AdminLayout })
const props = defineProps({ event: Object, tickets: Object })

const $q = useQuasar()
const filter = ref('')
const columns = [
    { name: 'attendee', align: 'center', label: 'Attendee Name', field: 'attendee', sortable: true },
    { name: 'status', align: 'center', label: 'Status', field: 'status', sortable: true },
    { name: 'checked_in', align: 'center', label: 'Checked in', field: 'checked_in', sortable: true },
    { name: 'actions', align: 'center', label: '', field: 'actions', sortable: true },
]
const checkedIn = computed(() => {
    return props.tickets.filter((tix) => tix.status == 'used') 
})

</script>

<template>
    
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
        <q-card class="q-mb-md" bordered flat>
            <q-card-actions class="justify-center">
                <div class="flex justify-center items-center">
                    <Link :href="route('admin.tickets.index')" class="absolute-left q-mt-sm">
                        <q-btn rounded label="Go Back" no-caps icon="arrow_back" flat />
                    </Link>
                    <div class="text-h6">Event Dashboard</div>
                </div>
            </q-card-actions>
            <q-card-section >
                <!-- <q-btn 
                    label="Scan Qr Code" 
                    no-caps 
                    icon="qr_code" 
                    color="primary" 
                    class="absolute-top-right q-mt-md q-mr-md" 
                    @click="showScanner = true"
                    rounded 
                    unelevated
                /> -->
                <q-item class="q-my-sm">
                    <q-item-section avatar class="items-center">
                        <div class="text-weight-bold text-secondary">{{ date.formatDate(event.date, 'MMM') }}</div>
                        <div>{{ date.formatDate(event.date, 'D') }}</div>
                    </q-item-section>
                    <q-item-section avatar>
                        <q-img :src="`/storage/${event.cover_photo}`" height="60px" width="60px" class="rounded-borders" />
                    </q-item-section>
                    <q-item-section class="items-start">
                        <q-item-label class="text-weight-bold">{{ event.title }}</q-item-label>
                        <q-item-label caption>{{ date.formatDate(event.date, 'MMM D, YYYY') + ' at ' +  event.start_time}}</q-item-label>
                    </q-item-section>
                </q-item>
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
                    <q-input rounded outlined dense label="Search..." v-model="filter" class="q-mx-md" debounce="300" color="primary">
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
                        {{ props.row.name }}
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <EventCheckinDialog :ticket="props.row" />
                    </q-td>
                </template>
                <template v-slot:body-cell-checked_in="props">
                    <q-td :props="props">
                        <q-chip v-if="props.row.status == 'used'" color="green-3">Checked-in</q-chip>
                        <q-chip v-else>Pending</q-chip>
                    </q-td>
                </template>
                <template v-slot:no-data>
                    <div class="full-width row flex-center q-gutter-sm text-grey" style="height: 50vh">
                        No Attendees Found. Check again later...
                    </div>
                </template>
                <template v-slot:item="props">
                    <div class="col-12 q-mb-sm">
                        <q-card class="q-mx-sm" bordered flat>
                            <q-card-section>
                                <q-item class="q-pa-none">
                                    <q-item-section class="items-start">
                                        <q-item-label>{{ props.row.name }}</q-item-label>
                                        <q-item-label caption class="ellipsis-2-lines q-mr-xl">{{ props.row.status }}</q-item-label>
                                    </q-item-section>
                                    <q-item-section side>
                                        <q-chip>{{ props.row.status == 'used' ? 'Checked in' : 'Pending' }}</q-chip>
                                    </q-item-section>
                                </q-item>
                            </q-card-section>
                        </q-card>
                    </div>
                </template>
            </q-table>
        </q-card>
    </div>
    <!-- <q-dialog v-model="showScanner" persistent>
      <q-card flat class="q-pa-md" style="max-width: 90%; max-height: 90%;">
        <q-card-actions class="justify-end">
            <q-btn icon="close" flat round dense @click="showScanner = false"/>
        </q-card-actions>
        <qrcode-stream 
            @decode="onDecode" 
            @init="onInit" 
            @detect="onDetect"
            @error="cameraError"
            @camera-on="onCameraReady"
        />
        <q-spinner v-if="loading" />
      </q-card>
    </q-dialog> -->
</template>

<style scoped>
a {
    color: black;
}
</style>