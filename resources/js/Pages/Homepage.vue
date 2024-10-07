<script setup>
import { Head, Link } from '@inertiajs/vue3'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { useQuasar } from 'quasar'

import { computed, ref } from 'vue'

const slide = ref('style1')
const $q = useQuasar()
const layout = computed(() => {
    return $q.screen.lt.md ? 'dense' : ($q.screen.lt.md ? 'comfortable' : 'loose')
})

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

defineOptions({
    layout: CustomerLayout
})

Echo.channel('hello-channel')
    .listen('.hello.event', (e) => {
        $q.notify(e)
    })

</script>

<template>
    <Head title="Welcome" />
    <div style="background: linear-gradient(to bottom, #FFFFFF, #EEEEEE)">
        <div class="row q-col-gutter-lg items-center">
            <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div :class="['q-ma-lg', $q.screen.lt.md ? 'text-center' : '']">
                    <p class="text-start text-h3 q-mt-xl text-weight-medium">Taytayan Camp Hub</p>
                    <p class="text-start text-subtitle1"> Experience the ultimate adventure at Taytayan Camp Hub! Order your favorite drinks, book event tickets, and reserve a cozy camp tent for an unforgettable outdoor getaway. Enjoy, discover, and indulge in every moment at Taytayan Camp Hub.                    </p>
                    <q-btn size="lg" unelevated color="primary" no-caps class="q-px-xl"> Create your account </q-btn>
                </div>
            </div>
            <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div class="q-pa-lg">
                    <q-img src="https://cdn.prod.website-files.com/60411749e60be86afb89d2f0/6386f5e5cd1c0534842c48df_dashboard.png"></q-img>
                </div>
            </div>
        </div>
        <!-- <q-separator class="q-my-xl"/> -->

        <p class="text-center text-h5 q-ma-lg text-weight-medium">Our Features & Services</p>
        <!-- <p class="text-red text-center">Do the relationships of the models tommorow</p> -->
        <div :class="$q.screen.lt.md ? 'text-center' : ''">
            <div class="row q-col-gutter-xl justify-center items-center">
                <div class="col-12 col-xs-12 col-sm-12 lt-md">
                    <div class="q-px-lg">
                        <!-- <q-img src="https://cdn.prod.website-files.com/60411749e60be86afb89d2f0/61815e0160a2242228e341b3_links-kv_2-p-500.png"></q-img> -->
                        <q-img src="products_pic.png"></q-img>
                    </div>
                </div>
                <div class="col-12 q-col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="q-mx-lg q-mb-lg">
                        <p class="text-start text-h5 text-weight-medium"> Milk Tea Delights </p>
                        <p class="text-start text-subtitle1"> Craving something refreshing? Indulge in our delicious, customizable milk tea, made just the way you like it. Explore our wide range of refreshing milk tea flavors and customizable options. </p>
                        <q-btn size="lg" unelevated color="primary" no-caps> Order Milk Tea </q-btn>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-5 col-md-5 gt-sm">
                    <!-- <q-img src="https://cdn.prod.website-files.com/60411749e60be86afb89d2f0/61815e0160a2242228e341b3_links-kv_2-p-500.png"></q-img> -->
                    <q-img src="products_pic.png"></q-img>
                </div>
            </div>

            <div class="row q-col-gutter-xl justify-center items-center">
                <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="q-px-lg">
                        <q-img src="facilities_pic.png"></q-img>
                    </div>
                </div>
                <div class="col-12 q-col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="q-mx-lg q-mb-lg">
                        <p class="text-start text-h5 text-weight-medium"> Nature Retreats </p>
                        <p class="text-start text-subtitle1"> Ready for an adventure? Book your camp tents, huts, or cozy camp rooms in just a few clicks! Discover scenic campsites and book your stay hassle-free. </p>
                        <q-btn size="lg" unelevated color="primary" no-caps> Reserve Campsite </q-btn>
                    </div>
                </div>
            </div>

            <div class="row q-col-gutter-xl justify-center items-center">
                <div class="col-12 col-xs-12 col-sm-12 lt-md">
                    <div class="q-px-lg">
                        <q-img src="events_pic.png"></q-img>
                    </div>
                </div>
                <div class="col-12 q-col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 ">
                    <div class="q-mx-lg q-mb-lg">
                        <p class="text-start text-h5 text-weight-medium"> Exciting Events </p>
                        <p class="text-start text-subtitle1"> Planning a night out? Reserve your tickets online for exclusive events like discos and more! Browse upcoming events and concerts. Secure your tickets and be part of unforgettable experiences. </p>
                        <q-btn size="lg" unelevated color="primary" no-caps> Buy Event Tickets </q-btn>
                    </div>
                </div>
                <div class="col-5 gt-sm">
                    <q-img src="events_pic.png"></q-img>
                </div>
            </div>
        </div>
    </div>
    <p class="text-center text-h5 q-mt-lg text-weight-medium"> Here's what they have to say. </p>
    <q-carousel
        v-model="slide"
        transition-prev="jump-right"
        transition-next="jump-left"
        swipeable
        animated
        navigation
        padding
        arrows
        :control-color="$q.dark.isActive ? 'white' : 'black'"
        height="300px"
        class="rounded-borders"
    >
        <template v-slot:navigation-icon="{ active, onClick }">
            <q-btn v-if="active" size="lg" icon="minimize" color="blue" flat round dense @click="onClick" />
            <q-btn v-else size="lg" icon="minimize" :color="$q.dark.isActive ? 'white' : 'black'" flat round dense @click="onClick" />
        </template>
        <q-carousel-slide :name="`style${n}`" v-for="n in 5" :key="n">
            <q-card class="q-pa-lg" style="height: 100%;">
                <img style="height: 35px; width: 35px" src="https://assets-global.website-files.com/609cbadfde419c81adafa17b/609db59d8c7a879cbd837708_Quotation%20Mark.svg" alt="">
                <div class="q-mt-md text-start ellipsis-3-lines">
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Temporibus, consectetur!
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Temporibus, consectetur!
                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Temporibus, consectetur!
                </div>
                <br>
                - John Doe
            </q-card>
        </q-carousel-slide>
    </q-carousel>

    <div class="row justify-center">
        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 ">
            <div class="q-px-lg q-py-md">
                <q-timeline :layout="layout" color="secondary">
                    <q-timeline-entry heading>
                        <p class="text-center text-h5 q-mt-xl text-weight-medium"> How it Works </p>
                    </q-timeline-entry>

                    <q-timeline-entry
                        title="Choose Your Service"
                        subtitle="February 22, 1986"
                        side="left"
                    >
                        <div>
                            Users select from ordering food, reserving a room, or buying tickets.
                            Lo dolor in reprehenderit in voluptate velit et non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </div>
                        <template v-slot:subtitle>
                            <q-img src="https://www.littlethings.info/wp-content/uploads/2014/04/dummy-image-green-e1398449160839.jpg" style="height: 200px;"></q-img>
                        </template>
                    </q-timeline-entry>

                    <q-timeline-entry
                        title="Make a Selection"
                        subtitle="February 22, 1986"
                        side="right"
                    >
                        <div>
                            Users browse through the offerings and make a choice. Lorem ipsum dolor sit amet, coat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </div>
                        <template v-slot:subtitle>
                            <q-img src="https://www.littlethings.info/wp-content/uploads/2014/04/dummy-image-green-e1398449160839.jpg" style="height: 200px;"></q-img>
                        </template>
                    </q-timeline-entry>

                    <q-timeline-entry
                        title="Enjoy!"
                        subtitle="February 22, 1986"
                        side="left"
                        color="orange"
                        icon="done_all"
                    >
                        <div>
                            Users receive their order, check in, or attend the event.
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magserunt mollit anim id est laborum.
                        </div>
                        <template v-slot:subtitle>
                            <q-img src="https://www.littlethings.info/wp-content/uploads/2014/04/dummy-image-green-e1398449160839.jpg" style="height: 200px;"></q-img>
                        </template>
                    </q-timeline-entry>
                </q-timeline>
            </div>
        </div>
    </div>

    <div class="bg-grey text-center text-h5" style="height: 350px;">
        Footer
    </div>
</template>


