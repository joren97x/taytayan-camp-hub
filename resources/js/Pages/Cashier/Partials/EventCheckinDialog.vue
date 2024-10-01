<script setup>
import { useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { defineEmits, ref } from 'vue'

const emit = defineEmits(['close']) 
const props = defineProps({
    ticket: Object,
})

const $q = useQuasar()
const checkInDialog = ref(false)
const checkOutDialog = ref(false)
const form = useForm({})
const checkIn = () => {
    form.patch(route('cashier.tickets.update', props.ticket.id), {
        onSuccess: () => {
            checkInDialog.value = false
            $q.notify('Customer Checked In')
        }   
    })
}

const checkOut = () => {
    form.patch(route('cashier.tickets.check_out', props.ticket.id), {
        onSuccess: () => {
            checkOutDialog.value = false
            $q.notify('Customer Checked Out')
        }   
    })
}


</script>

<template>
    <div>
        <q-btn no-caps color="primary" unelevated @click="checkInDialog = true">Check-in</q-btn>
    </div>
    <q-dialog v-model="checkInDialog">
        <q-card>
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