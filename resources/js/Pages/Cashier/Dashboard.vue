<script setup>

import CashierLayout from '@/Layouts/CashierLayout.vue'
import { useDrawerStore } from '@/Stores/DrawerStore'
import { Head } from '@inertiajs/vue3'

defineOptions({
    layout: CashierLayout
})

const drawerStore = useDrawerStore()

</script>

<template>
    <Head title="Dashboard" />
    <!-- <div class="q-pa-md">
        <q-btn icon="menu" flat @click="drawerStore.drawer =true" class="lt-md"/>
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
    </div> -->
</template>
