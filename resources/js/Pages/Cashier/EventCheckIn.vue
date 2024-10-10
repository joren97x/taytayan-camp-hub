<script setup>

import CashierLayout from '@/Layouts/CashierLayout.vue'
import { ref, computed } from 'vue'
import EventCheckinDialog from './Partials/EventCheckinDialog.vue'
import { QrcodeStream } from 'vue-qrcode-reader'
import { useQuasar } from 'quasar'
import { router } from '@inertiajs/vue3'

defineOptions({
    layout: CashierLayout
})

const props = defineProps({
    event: Object,
    tickets: Object
})

const $q = useQuasar()
const filter = ref('')
const columns = [
    { name: 'attendee', align: 'center', label: 'Attendee Name', field: 'attendee', sortable: true },
    { name: 'status', align: 'center', label: 'Status', field: 'status', sortable: true },
    { name: 'actions', align: 'center', label: '', field: 'actions', sortable: true },
]
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

</script>

<template>
    
    {{ checkedIn.length }}
    <div class="q-pa-md">
        <q-card class="q-mb-md" bordered flat>
            <q-card-section>
                <q-btn label="Go Back" no-caps icon="arrow_back" flat class="q-pa-none" />
                <q-btn 
                    label="Scan Qr Code" 
                    no-caps 
                    icon="qr_code" 
                    color="primary" 
                    class="absolute-top-right q-mt-md q-mr-md" 
                    @click="showScanner = true"
                />
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
    <q-dialog v-model="showScanner" persistent>
      <q-card flat class="q-pa-md" style="max-width: 90%; max-height: 90%;">
        <q-card-actions class="justify-end">
            <q-btn icon="close" flat round dense @click="showScanner = false"/>
        </q-card-actions>
        <qrcode-stream 
            @decode="onDecode" 
            @init="onInit" 
            @detect="onDetect"
             @camera-on="onCameraReady"
        />
        <q-spinner v-if="loading" />
      </q-card>
    </q-dialog>
</template>
