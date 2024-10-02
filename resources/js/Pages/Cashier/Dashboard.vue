<script setup>

import CashierLayout from '@/Layouts/CashierLayout.vue'

defineOptions({
    layout: CashierLayout
})

</script>

<template>
    <div class="q-pa-md">
        <div class="text-h6">Dashboard</div>
        <q-card bordered flat>
            <q-card-section class="">
                <div class="row q-col-gutter-md">
                    <div class="col-4" bordered flat v-for="n in 3">
                        <q-card bordered flat class=" q-pa-md">
                            annyeong
                        </q-card>
                    </div>
                </div>
            </q-card-section>
        </q-card>
    </div>
</template>
