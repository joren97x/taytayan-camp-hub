<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link, Head } from '@inertiajs/vue3'
import FacilityCard from './Partials/FacilityCard.vue'

// defineOptions({
//     layout: CustomerLayout
// })

defineProps({
    facilities: Object
})

</script>

<template>
    <Head title="Facilities" />
    <CustomerLayout>
    <template v-slot:cover>
        <div style="position: absolute; width: 100vw; height: 55vh; overflow: hidden; top: 0" class="bg-grey-3">
            <div style="
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(to bottom, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0) 80%);
                z-index: 1;
            "></div>
            <q-img src="images/huts2.jpg" class="fit" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" />
            <div style="
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 80%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                color: white;
                z-index: 2;
                text-align: center;
                padding: 20px; /* For smaller screens */
            ">
                <div 
                    style=" margin: 0; font-weight: bold; text-shadow: 20px 20px 20px rgba(0, 2, 2, 2.9);" 
                    :class="[$q.screen.gt.sm ? 'text-h2' : 'text-h5 q-pt-xl', 'text-weight-bold']"
                >
                    Explore Our Camp Facilities
                </div>
                <div 
                    style=" text-shadow: 10px 10px 10px rgba(10, 10, 10, 10); max-width: 800px;"
                    :class="[$q.screen.gt.sm ? 'text-h6' : 'text-subtitle1', 'text-weight-medium']"
                >
                    Find your perfect spot to relax and enjoy nature
                </div>
            </div>
        </div>
    </template>
    <div style="margin-top: 50vh">
        <!-- <q-card bordered flat style="height: 40vh; position: relative;" class="full-width bg-grey row justify-center" v-if="facilities.length > 0">
            <q-img class="fit" fit="cover" src="images/huts2.jpg" />
            <div style="position: absolute; inset: 0; background: rgba(0, 0, 0, 0.5);" class="flex items-center justify-center">
                <div class="text-center text-white">
                    <div class="text-h2 q-mb-sm text-weight-bold">Explore Our Camp Facilities</div>
                    <div class="text-h6">Find your perfect spot to relax and enjoy nature</div>
                </div>
            </div>
        </q-card>
        <div v-else style="width: 100%; height: 50vh;" class="rounded-borders bg-grey items-center flex justify-center" >
            <div class="text-center text-white">
                <div class="text-h3 q-mb-md">No Facilities Available</div>
                <div>Check back soon. Stay tuned for updates!</div>
            </div>
        </div> -->
        
        <div class="q-ma-sm q-mb-xl">
            <div class="text-h6 q-my-md">Facilities</div>

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
</CustomerLayout>
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