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
        from: '2024/07/01',
        to: '2024/07/20'
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
    <div class="row">
        <div class="col-8">
            {{ form }}
            <hr>
            {{ form.data() }}
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