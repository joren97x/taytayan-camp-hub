<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import FeaturedProductCard from '@/Components/Customer/Product/FeaturedProductCard.vue'
import { Head } from '@inertiajs/vue3'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    products: Object
})

</script>

<template>
    <Head title="Home" />
    <div>
        <p class="text-h4 text-weight-bold">Nice to see you, {{ $page.props.auth.user.first_name }}👋</p>
        <div class="row q-col-gutter-md">
            <div class="col-4" v-for="n in 3" :key="n">
                <q-card class="my-card q-py-md q-pl-md" bordered flat>
                    <q-card-section horizontal>
                        <img
                            src="https://cdn.quasar.dev/img/mountains.jpg"
                            width="40%"
                        />
                        <p class="q-ml-md">
                            <span class="text-h6 text-weight-bold">Milktea Menu</span>
                            <br>
                            <span class="text-subtitle2 text-weight-regular">Lorem ipsum dolor, sit amet consectetur</span>
                        </p>
                        <q-card-actions vertical class="justify-around q-px-md">
                            <q-btn icon="chevron_right" flat></q-btn>
                        </q-card-actions>
                    </q-card-section>
                </q-card>
            </div>
        </div>
        
        <div class="q-my-md">
            <p class="text-h5 text-weight-bold">Products</p>
            <div class="row q-col-gutter-md">
                // some product card
            </div>
        </div>
        <div class="q-my-md">
            <p class="text-h5 text-weight-bold">Events</p>
            <div class="row q-col-gutter-md">
                // some event card
            </div>
        </div>
        <div class="q-my-md">
            <p class="text-h5 text-weight-bold">Facilities</p>
            <div class="row q-col-gutter-md">
                // some facility card
            </div>
        </div>
        <p class="text-center text-red">designi inig human sa laing pages</p>
        <q-img src="https://avatars.githubusercontent.com/u/112835241?s=400&u=5c5ce654a02c61684c3e00d25d695ce142ede5ef&v=4"></q-img>
    </div>
</template>