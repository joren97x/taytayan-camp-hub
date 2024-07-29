<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'
import { date as qdate } from 'quasar'
import { onMounted, ref } from 'vue'

defineOptions({
    layout: CustomerLayout
})

const props = defineProps({
    facility: Object,
    reserved_dates: Object
})

const form = useForm({
    facility_id: props.facility.id,
    date: {
        from: '2024/07/01',
        to: '2024/07/20'
    }
})

onMounted(() => {
    processReservedDates()
})

let disabled_dates = ref([])

// if(prop.reserved_dates) {
//         for(let i = 0; i < prop.reserved_dates.length; i++) {
//             for(let date = new Date(prop.reserved_dates[i].checkin); date <= new Date(prop.reserved_dates[i].checkout); date.setDate(date.getDate() + 1)) {
//                 const year = date.getFullYear();
//                 const month = String(date.getMonth() + 1).padStart(2, '0');
//                 const day = String(date.getDate()).padStart(2, '0');
//                 disabled_dates.push(`${year}-${month}-${day}`);
//             }
//         }
//     }
//     console.log(prop.reserved_dates)
//     // E DISABLE ANG DATES SA MGA NE-AGING ADLAW
//     for (let date = new Date('2023-01-01'); date < today; date.setDate(date.getDate() + 1)) {
//         const year = date.getFullYear();
//         const month = String(date.getMonth() + 1).padStart(2, '0');
//         const day = String(date.getDate()).padStart(2, '0');
//         disabled_dates.push(`${year}-${month}-${day}`);
//     }
// const today = qdate.formatDate(new Date(), 'YYYY-MM-DD')
    function processReservedDates() {
        if (props.reserved_dates) {
            for (let i = 0; i < props.reserved_dates.length; i++) {
                let checkInDate = props.reserved_dates[i].check_in
                const checkOutDate = props.reserved_dates[i].check_out

                disabled_dates.value.push(checkInDate)
                const diff = qdate.getDateDiff(checkOutDate, checkInDate, 'days')
                for(let j = 0; j < diff; j++) {
                    checkInDate = qdate.addToDate(checkInDate, { days: 1})
                    disabled_dates.value.push(checkInDate)
                }
            }
        }
    }

function options(date) {
    const date2 = qdate.extractDate(date)
    // const formattedDate = `${date.year}/${String(date.month).padStart(2, '0')}/${String(date.day).padStart(2, '0')}`
    console.log('from options')
    console.log(date2)
    return !disabled_dates.value.includes(date2)
}

</script>

<template>
    <div>
        {{ facility }}
    </div>
    <div class="row">
        <div class="col-8">
            {{ form }}
            <hr>
            {{ form.data() }}
            <hr>
            Reserved Dates
        {{ reserved_dates }}
        <hr>
        Dates that should be disabled
        {{ disabled_dates }}
        </div>
        <div class="col-4">
            <div class="q-pa-md">
                <q-date
                    v-model="form.date"
                    landscape
                    range
                    :options="options"
                />
            </div>
            <Link :href="route('facility.checkout')" :data="form.data()">
                <q-btn unelevated color="primary">
                    Reserve
                </q-btn>
            </Link>
        </div>
    </div>
</template>