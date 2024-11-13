<script setup>

import CashierLayout from '@/Layouts/CashierLayout.vue'
import { ref, computed } from 'vue'
import EventCheckinDialog from './Partials/EventCheckinDialog.vue'
import { QrcodeStream } from 'vue-qrcode-reader'
import { useQuasar, date } from 'quasar'
import { router, Link } from '@inertiajs/vue3'
import { useDrawerStore } from '@/Stores/DrawerStore'

defineOptions({
    layout: CashierLayout
})

const props = defineProps({
    event: Object,
    tickets: Object
})

const drawerStore = useDrawerStore()
const $q = useQuasar()
const filter = ref('all')
const showSearch = ref(false)
const searchTerm = ref('')
const columns = [
    { name: 'attendee', align: 'center', label: 'Attendee Name', field: 'attendee', sortable: true },
    { name: 'status', align: 'center', label: 'Status', field: 'status', sortable: true },
    { name: 'actions', align: 'center', label: '', field: 'actions', sortable: true },
]

const filteredTickets = computed(() => {
  let filtered = props.tickets;
  // Apply status filter
  if (filter.value !== 'all') {
    filtered = filtered.filter(ticket => ticket.status === filter.value);
  }

  // Apply search term filter
  if (searchTerm.value) {
    filtered = filtered.filter(ticket => {
    const fullName = ticket.name.toLowerCase();
    const search = searchTerm.value.toLowerCase();

        return (
            fullName.includes(search)
        );
    });
  }

  return filtered;
});

const checkedIn = computed(() => {
    return props.tickets.filter((tix) => tix.status == 'used') 
})

const showScanner = ref(false)
const loading = ref(false)

const onDecode = (result) => {
    $q.notify('helo its decoded')
    showScanner.value = false;
    loading.value = false;
    // Do something with the QR code result
    console.log('QR Code Result:', result);
    // You can display it, use it, or redirect the user based on the result.
}

const onInit = (promise) => {
    promise.then(() => {
        this.loading = false;
    }).catch((error) => {
    console.error(error);
        this.loading = false;
        this.showScanner = false;
    });
}

const onDetect = (detectedCodes) => {
    $q.notify('detecented?')
    console.log(detectedCodes)
    router.visit(detectedCodes[0].rawValue)
}

const selectedConstraints = ref({ facingMode: 'environment' })
const defaultConstraintOptions = [
  { label: 'rear camera', constraints: { facingMode: 'environment' } },
  { label: 'front camera', constraints: { facingMode: 'user' } }
]
const constraintOptions = ref(defaultConstraintOptions)

async function onCameraReady() {
  // NOTE: on iOS we can't invoke `enumerateDevices` before the user has given
  // camera access permission. `QrcodeStream` internally takes care of
  // requesting the permissions. The `camera-on` event should guarantee that this
  // has happened.
  const devices = await navigator.mediaDevices.enumerateDevices()
  const videoDevices = devices.filter(({ kind }) => kind === 'videoinput')

  constraintOptions.value = [
    ...defaultConstraintOptions,
    ...videoDevices.map(({ deviceId, label }) => ({
      label: `${label} (ID: ${deviceId})`,
      constraints: { deviceId }
    }))
  ]

//   error.value = ''
}

const cameraError = (err) => {
    console.log(err)
    $q.notify({
        message: `Something went wrong`,
        color: 'negative', // or any custom color defined in the brand config
        textColor: 'white',
        position: 'top'
    })
}

</script>

<template>
    
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
        <q-card class="q-mb-md" bordered flat>
            <q-card-section>
                <!-- <Link :href="route('cashier.tickets.index')">
                    <q-btn rounded label="Go Back" no-caps icon="arrow_back" flat />
                </Link> -->
                <div :class="`flex ${$q.screen.gt.sm ? 'items-center justify-center' : ''}`">
                    <Link :href="route('cashier.tickets.index')" class="gt-sm absolute-left q-mt-sm">
                        <q-btn rounded label="Go Back" no-caps icon="arrow_back" flat />
                    </Link> 
                    <q-btn rounded @click="drawerStore.toggle" no-caps class="lt-md" icon="menu" flat />
                    <div class="text-h6">Check-in</div>
                </div>

                
                <q-btn 
                    label="Scan Qr Code" 
                    no-caps 
                    icon="qr_code" 
                    color="primary" 
                    class="absolute-top-right q-mt-md q-mr-md" 
                    @click="showScanner = true"
                    rounded 
                    unelevated
                />
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
                :rows="filteredTickets"
                :columns="columns"
                row-key="name"
            >
                <template v-slot:top>
                    <p class="text-h6 q-pt-md">Attendees</p>
                    <q-space />
                    <!-- <q-input rounded outlined dense label="Search..." v-model="searchTerm" class="q-mx-md" debounce="300" color="primary">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input> -->
                    <q-btn icon="search" class="q-mr-xs" round dense flat @click="showSearch = !showSearch"/>
                    <q-select 
                        :options="['all', 'used', 'sold']" 
                        v-model="filter"
                        outlined 
                        rounded
                        dense
                    />
                    <div class="full-width q-mt-sm" v-if="showSearch">
                        <q-input
                            v-model="searchTerm"
                            rounded
                            outlined
                            dense
                            label="Search using name"
                            debounce="300"
                            class="full-width"
                            color="primary"
                        >
                            <template v-slot:append>
                                <q-icon name="search" />
                            </template>
                        </q-input>
                    </div>
                    <!-- <Link :href="route('admin.facilities.create')">f
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
                <template v-slot:item="props">
                    <div class="col-12 q-mb-sm">
                        <q-card class="q-mx-sm" bordered flat>
                            <q-card-section>
                                <q-item class="q-pa-none">
                                    <q-item-section class="items-start">
                                        {{ props.row }}
                                        <q-item-label>{{ props.row.name }}</q-item-label>
                                        <q-item-label caption>Attendee Name</q-item-label>
                                    </q-item-section>
                                    <q-item-section>
                                        <q-item-label>{{ props.row.status }}</q-item-label>
                                        <q-item-label caption>Status</q-item-label>
                                    </q-item-section>
                                    <q-item-section side>
                                        <EventCheckinDialog :ticket="props.row" />
                                    </q-item-section>
                                </q-item>
                            </q-card-section>
                        </q-card>
                    </div>
                </template>
                <template v-slot:no-data>
                    <div class="full-width row flex-center q-gutter-sm text-grey" style="height: 50vh">
                        No Attendees Found. Check again later...
                    </div>
                </template>
            </q-table>
        </q-card>
    </div>
    <q-dialog v-model="showScanner" persistent>
      <q-card flat class="q-pa-md" style="max-width: 90%; max-height: 90%;">
        <!-- <q-card flat class="q-pa-md" :style="$q.screen.gt.sm ? 'max-width: 90%; max-height: 90%;' : 'max-width: 90%; max-height: 90%;'"> -->
        <q-card-actions class="justify-between">
            <div class="text-h6">Scan Qr Code</div>
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
    </q-dialog>
</template>

<style scoped>
a {
    color: black;
}
</style>