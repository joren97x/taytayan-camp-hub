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
            <p class="text-h5 text-weight-bold">Featured milkteas</p>
            <div class="row q-col-gutter-md">
                <div class="col-3" v-for="product in products" :key="product.id">
                    <!-- {{ product }} -->
                    <FeaturedProductCard :product="product"/>
                </div>
            </div>
        </div>
        <div class="q-my-md">
            <p class="text-h5 text-weight-bold">Upcoming Events</p>
            <div class="row q-col-gutter-md">
                <div class="col-6" v-for="n in 4" :key="n">
                    <!-- <FeaturedProductCard/> -->
                </div>
            </div>
        </div>
        <div class="q-my-md">
            <p class="text-h5 text-weight-bold">Book Campsite</p>
            <div class="row q-col-gutter-md">
                <div class="col-4" v-for="n in 4" :key="n">
                    <!-- <FeaturedProductCard/> -->
                </div>
            </div>
        </div>
    </div>
</template>