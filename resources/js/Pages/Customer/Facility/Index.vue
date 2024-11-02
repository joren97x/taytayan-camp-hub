<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link, Head } from '@inertiajs/vue3'
import FacilityCard from './Partials/FacilityCard.vue'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    facilities: Object
})

</script>

<template>
    <Head title="Facilities" />
    <div>
        <q-card bordered flat style="height: 40vh;" class="full-width bg-grey row justify-center" v-if="facilities.length > 0">
        </q-card>
        <div v-else style="width: 100%; height: 50vh;" class="rounded-borders bg-grey items-center flex justify-center" >
            <div class="text-center text-white">
                <div class="text-h3 q-mb-md">No Facilities Available</div>
                <div>Check back soon. Stay tuned for updates!</div>
            </div>
        </div>
        
        <div class="q-ma-md">
            <div class="text-h6">Facilities</div>

            <div class="row q-col-gutter-md">
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 col-xl-3" v-for="facility in facilities" v-if="facilities.length > 0">
                    <FacilityCard :facility="facility" />
                </div>
                <q-card bordered flat v-else class="items-center col-12 q-mt-lg flex-center flex bg-grey-3 text-grey" style="height: 40vh">
                    No Facilities Found
                </q-card>
            </div>
            
        </div>
    </div>
</template>

<style scoped>

a {
    text-decoration: none;
}

</style>
<!-- <div class="row">
    <div class="col-3" v-for="facility in facilities" >
        <q-card class="q-my-md">
        {{ facility.name }}
        <Link :href="route('customer.facilities.show', facility.id)">
            <q-btn>button</q-btn>
        </Link>
    </q-card>
    </div>
</div> -->