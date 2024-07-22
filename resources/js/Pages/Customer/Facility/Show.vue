<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'

defineOptions({
    layout: CustomerLayout
})

const props = defineProps({
    facility: Object
})

const form = useForm({
    facility_id: props.facility.id,
    date: {
        check_in: '2024/07/01',
        check_out: '2024/07/20'
    }
})

const disabled_dates = [
    '2024/07/07',
    '2024/07/08',
    '2024/07/09'
]

function options(date) {
    const formattedDate = `${date.year}/${String(date.month).padStart(2, '0')}/${String(date.day).padStart(2, '0')}`
    return !disabled_dates.includes(formattedDate)
}

</script>

<template>
    <div>
        {{ facility }}
    </div>
    <div class="q-pa-md">
    <q-date
      v-model="form.date"
      landscape
      range
      :options="options"
    />
  </div>
</template>