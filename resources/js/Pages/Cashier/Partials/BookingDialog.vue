<script setup>
import { useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { defineEmits, ref } from 'vue'

const emit = defineEmits(['close']) 
const props = defineProps({
    dialog: Boolean,
    order: Object,
    booking_statuses: Array
})

const $q = useQuasar()
const checkInDialog = ref(false)
const checkOutDialog = ref(false)
const form = useForm({})
const checkIn = () => {
    form.patch(route('cashier.bookings.check_in', props.order.id), {
        onSuccess: () => {
            checkInDialog.value = false
            $q.notify('Customer Checked In')
        }   
    })
}

const checkOut = () => {
    form.patch(route('cashier.bookings.check_out', props.order.id), {
        onSuccess: () => {
            checkOutDialog.value = false
            $q.notify('Customer Checked Out')
        }   
    })
}


</script>

<template>
    <div>
        <q-icon name="check" class="q-mr-md" size="sm" v-if="order.status == booking_statuses.checked_in || order.status == booking_statuses.checked_out">
            <q-tooltip>Checked-in</q-tooltip>
        </q-icon>
        <q-icon name="close" size="sm" v-if="order.status == booking_statuses.checked_out">
            <q-tooltip>Checked-out</q-tooltip>
        </q-icon>
        <q-btn 
            @click="checkInDialog = true" 
            label="Check-in"
            v-if="order.status == booking_statuses.pending || order.status == booking_statuses.confirmed"
        />
        <q-btn 
            @click="checkOutDialog = true" 
            label="Check-out"
            v-if="order.status == booking_statuses.checked_in"
        />
        <q-chip v-if="order.status == booking_statuses.complete">Complete</q-chip>
        <q-btn>View</q-btn>
    </div>
    <q-dialog v-model="checkInDialog">
        <q-card>
            {{ order }}
            hiii
            <q-card-section>
                CHeck in?
            </q-card-section>
            <q-card-actions>
                <q-btn v-close-popup>No</q-btn>
                <q-btn 
                    @click="checkIn"
                    :loading="form.processing" 
                    :disable="form.processing"
                    label="Check-in"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
    <q-dialog v-model="checkOutDialog">
        <q-card>
            <q-card-section>
                Check out?
            </q-card-section>
            <q-card-actions>
                <q-btn v-close-popup>No</q-btn>
                <q-btn 
                    @click="checkOut"
                    :loading="form.processing" 
                    :disable="form.processing"
                    label="Check-out"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>