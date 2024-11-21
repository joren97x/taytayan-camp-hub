<script setup>

import { useQuasar, date as qdate } from 'quasar'
import { defineEmits, ref, onMounted } from 'vue'

const emit = defineEmits(['close', 'setBookingDates'])
const props = defineProps({
    dialog: Boolean,
    reserved_dates: Object,
    booked_dates: Object
})


const dialog = ref(props.dialog)
const date = ref(null)
const bookingDates = ref({
    check_in: null,
    check_out: null
})
const $q = useQuasar()
let disabled_dates = ref([])

const setDates = () => {
    console.log(date.value)
    if(date.value == null) {
        return
    }
    if(date.value.to || date.value.from) {
        let checkInDate = date.value.from
        const diff = qdate.getDateDiff(date.value.to, date.value.from, 'days') + 1
        console.log(diff)
        let pwede = true
        for(let i = 0; i < diff; i++) {
            if(!options(checkInDate)) {
                pwede = false
                date.value = null
                // alert('Please choose a date that doesnt overlap')
                $q.notify({
                    message: `Please choose a date that doesnt overlap`,
                    color: 'negative', // or any custom color defined in the brand config
                    textColor: 'white',
                    position: 'top'
                })
                break;
            }
            checkInDate = qdate.addToDate(checkInDate, { days: 1})
        }
        if(pwede) {
            bookingDates.value.check_in = date.value.from
            bookingDates.value.check_out = date.value.to
            emit('setBookingDates', bookingDates.value)
            // form.date = date.value
            dialog.value = false
        }
    }
    else {
        bookingDates.value.check_in = date.value
        bookingDates.value.check_out = date.value
        emit('setBookingDates', bookingDates.value)
        // form.date = date.value
        dialog.value = false
    }
    
}

const clearDates = () => {
    date.value = null
    bookingDates.value.check_in = null
    bookingDates.value.check_out = null
    emit('setBookingDates', bookingDates.value)
    // form.date.from = ''
    // form.date.to = ''
}

onMounted(() => {
    processReservedDates()
})

if(props.booked_dates) {
    date.value = {
        to: props.booked_dates.check_out,
        from: props.booked_dates.check_in,
    }
    setDates()
}
else {
    console.log('wa')
}

function processReservedDates() {
    if (props.reserved_dates) {
        for (let i = 0; i < props.reserved_dates.length; i++) {
            let checkInDate = props.reserved_dates[i].check_in
            const checkOutDate = props.reserved_dates[i].check_out

            disabled_dates.value.push(qdate.formatDate(checkInDate, 'YYYY-MM-DD'))
            const diff = qdate.getDateDiff(checkOutDate, checkInDate, 'days')
            for(let j = 0; j < diff; j++) {
                checkInDate = qdate.addToDate(checkInDate, { days: 1})
                disabled_dates.value.push(qdate.formatDate(checkInDate, 'YYYY-MM-DD'))
            }
        }
    }
}

function options(date) {
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Set the time to 00:00:00 to only compare dates

    const formattedIncomingDate = qdate.formatDate(new Date(date), 'YYYY-MM-DD');
    const formattedToday = qdate.formatDate(today, 'YYYY-MM-DD'); // Format today's date as 'YYYY-MM-DD'

    // Disable dates that are before today or already booked
    return formattedIncomingDate >= formattedToday && !disabled_dates.value.includes(formattedIncomingDate);
}

</script>

<template>
    <q-dialog 
        v-model="props.dialog" 
        :maximized="$q.screen.lt.md"  
        transition-show="slide-up"
        transition-hide="slide-down"
        :position="$q.screen.lt.md ? 'bottom' : 'standard'"
    >
        <q-card style="width: 100%; ">
            <q-card-actions>
                <div class="q-mr-xl">
                    <span class="text-h6">Choose Your Stay Dates</span>
                    <br>
                    Select the dates for your stay at Taytayan Camp Hut and enjoy a relaxing getaway close to nature.
                </div>
                <q-btn icon="close" class="absolute-top-right q-mt-sm q-mr-sm" round unelevated @click="emit('close')" v-close-popup/>
            </q-card-actions>
            <q-card-section>
                <q-date
                    v-model="date"
                    range
                    :options="options"
                    style="width: 100%;"
                    :subtitle="bookingDates.check_in && bookingDates.check_out ? `${qdate.formatDate(bookingDates.check_in, 'MMM D, YYYY')} - ${qdate.formatDate(bookingDates.check_out, 'MMM D, YYYY')}` : 'Add your travel dates'"
                >

                </q-date>
            </q-card-section>
            <q-card-actions class="row q-col-gutter-md">
                <div class="col-6" v-if="bookingDates.check_in && bookingDates.check_out">
                    <q-btn class="full-width" color="accent" no-caps rounded @click="clearDates()">Clear Dates</q-btn>
                </div>
                <div :class="bookingDates.check_in && bookingDates.check_out ? 'col-6' : 'col-12'">
                    <q-btn class="full-width" color="primary" no-caps rounded @click="setDates">Save</q-btn>
                </div>
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>