
<script setup>

import { router, Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import ProductCard from './Product/Partials/ProductCard.vue'
import EventCard from './Event/Partials/EventCard.vue'
import FacilityCard from './Facility/Partials/FacilityCard.vue'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'

defineOptions({ layout: CustomerLayout })
defineProps({
    products: Object,
    events: Object,
    facilities: Object
})

const $q = useQuasar()
const tab = ref('products')
const query = ref('')

const search = () => {
    router.get(route('search', query.value))
}

</script>



<template>
    <Head title="Search" />
    <q-toolbar :class="$q.screen.gt.sm ? 'q-mt-xl' : ''">
        <q-toolbar-title>
            <q-input 
                outlined 
                rounded 
                type="search" 
                v-model="query" 
                dense 
                label="Search for products, events, facilities" 
                @keyup.enter="search"
            />
        </q-toolbar-title>
    </q-toolbar>
    <q-tabs
        v-model="tab"
        dense
        class="text-grey"
        active-color="primary"
        indicator-color="primary"
        align="justify"
        no-caps
        narrow-indicator
    >
        <q-tab name="products" label="Products">
            <q-badge color="red" floating v-if="products && products.length > 0">
                {{ products.length }}
            </q-badge>
        </q-tab>
        <q-tab name="events" label="Events">
            <q-badge color="red" floating v-if="events && events.length > 0">
                {{ events.length }}
            </q-badge>
        </q-tab>
        <q-tab name="facilities" label="Facilities">
            <q-badge color="red" floating v-if="facilities && facilities.length > 0">
                {{ facilities.length }}
            </q-badge>
        </q-tab>
    </q-tabs>

        <q-separator />

        <q-tab-panels v-model="tab" animated>
            <q-tab-panel name="products">
                <div class="row q-col-gutter-md">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-xl-4 col-lg-4" v-for="product in products" >
                        <ProductCard :product="product" />
                    </div>
                </div>
                <div class="q-pa-xl items-center flex justify-center" v-if="!products || products.length <= 0">No products found...</div>
            </q-tab-panel>

            <q-tab-panel name="events">
                <div class="row q-col-gutter-md">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-xl-6 col-lg-6" v-for="event in events"  >
                        <EventCard :event="event"/>
                    </div>
                </div>
                <div class="q-pa-xl items-center flex justify-center" v-if="!events || events.length <= 0">No events found...</div>
            </q-tab-panel>

            <q-tab-panel name="facilities">
                <div class="row q-col-gutter-md">
                    <div class="col-xs-12 col-sm-12 col-md-4 col-xl-4 col-lg-4" v-for="facility in facilities">
                        <FacilityCard :facility="facility" />
                    </div>
                </div>
                <div class="q-pa-xl items-center flex justify-center" v-if="!facilities || facilities.length <= 0">No facilities found...</div>
            </q-tab-panel>
        </q-tab-panels>
</template>
