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

const undo = () => {
    form.patch(route('cashier.tickets.undo', props.ticket.id), {
        onSuccess: () => {
            
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
    <div :class="$q.screen.lt.md ? 'q-mt-md' : ''">
        <q-btn 
            no-caps 
            color="primary" 
            unelevated 
            @click="checkIn"
            label="Check-in"
            rounded
            :loading="form.processing"
            :disable="form.processing"
            v-if="ticket.status != 'used'"
        />
        <q-btn 
            round 
            icon="check" 
            color="primary" 
            unelevated 
            size="sm"
            v-else
            @click="undo"
            :loading="form.processing"
            :disable="form.processing"
        />
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