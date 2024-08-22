<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'
import { date as qdate } from 'quasar'
import { onMounted, ref, watch } from 'vue'

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
        from: '',
        to: ''
    }
})

onMounted(() => {
    processReservedDates()
})

const date = ref(null)
let disabled_dates = ref([])

watch(date, () => {
    if(date.value == null) {
        return
    }
    let checkInDate = date.value.from
    const diff = qdate.getDateDiff(date.value.to, date.value.from, 'days') + 1
    console.log(diff)
    let pwede = true
    for(let i = 0; i < diff; i++) {
        if(!options(checkInDate)) {
            pwede = false
            date.value = null
            alert('Please choose a date that doesnt overlap')
            break;
            console.log('dili pwede')
        }
            checkInDate = qdate.addToDate(checkInDate, { days: 1})
    }
    if(pwede) {
        form.date = date.value
    }

})

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
    const formattedIncomingDate = qdate.formatDate(new Date(date), 'YYYY-MM-DD');
    return !disabled_dates.value.includes(formattedIncomingDate);
}



</script>

<template>
    <div>
    </div>
    <div class="row">
        <div class="col-8">
            {{ facility }}
            <q-img v-for="image in JSON.parse(facility.images)" :src="`../storage/${image}`" style="width: 150px; height: 150px">
            </q-img>
            <hr>
            {{ form }}
            <hr>
        </div>
        <div class="col-4">
            <div class="q-pa-md">
                <p class="text-red">// what if one day ra... </p>
                <q-date
                    v-model="date"
                    landscape
                    range
                    :options="options"
                >
                </q-date>
            </div>
            {{ date }}
            <Link :href="route('facility.checkout')" :data="form.data()">
                <q-btn unelevated color="primary">
                    Reserve
                </q-btn>
            </Link>
        </div>
    </div>
</template>