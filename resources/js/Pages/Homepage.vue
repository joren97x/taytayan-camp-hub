<script setup>
import { Head, Link } from '@inertiajs/vue3'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { useQuasar } from 'quasar'

import { computed, ref } from 'vue'

const slide = ref('style1')
const $q = useQuasar()
const layout = computed(() => {
    return $q.screen.lt.sm ? 'dense' : ($q.screen.lt.md ? 'comfortable' : 'loose')
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
                    <p class="text-start text-h3 q-mt-xl text-weight-medium">Lorem ipsum dolor emit ambatukam</p>
                    <p class="text-start text-subtitle1"> Lorem ipsum dolor sit amet consectetur adipisicing elit. Repellendus accusantium, numquam porro, exercitationem quos debitis fugit ducimus </p>
                    <q-btn size="lg" unelevated color="blue" no-caps class="q-px-xl"> Create your account </q-btn>
                </div>
            </div>
            <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <q-img src="https://cdn.prod.website-files.com/60411749e60be86afb89d2f0/6386f5e5cd1c0534842c48df_dashboard.png"></q-img>
            </div>
        </div>
        <!-- <q-separator class="q-my-xl"/> -->

        <p class="text-center text-h5 q-ma-lg text-weight-medium">Our Services Lorem ipsum dolor emit</p>
        <!-- <p class="text-red text-center">Do the relationships of the models tommorow</p> -->
        <div :class="$q.screen.lt.md ? 'text-center' : ''">
            <div class="row q-col-gutter-xl justify-center items-center">
                <div class="col-12 col-xs-12 col-sm-12 lt-md">
                    <q-img src="https://cdn.prod.website-files.com/60411749e60be86afb89d2f0/61815e0160a2242228e341b3_links-kv_2-p-500.png"></q-img>
                </div>
                <div class="col-12 q-col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="q-ma-lg">
                        <p class="text-start text-h5 text-weight-medium"> Milk Tea Delights </p>
                        <p class="text-start text-subtitle1"> Explore our wide range of refreshing milk tea flavors and customizable options </p>
                        <q-btn size="lg" unelevated color="blue" no-caps> Order Milk Tea </q-btn>
                    </div>
                </div>
                <div class="col-5 gt-sm">
                    <q-img src="https://cdn.prod.website-files.com/60411749e60be86afb89d2f0/61815e0160a2242228e341b3_links-kv_2-p-500.png"></q-img>
                </div>
            </div>

            <div class="row q-col-gutter-xl justify-center items-center">
                <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <q-img src="https://cdn.prod.website-files.com/60411749e60be86afb89d2f0/61815e0022599b246dcf73e5_api-kv_2-p-500.png"></q-img>
                </div>
                <div class="col-12 q-col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="q-ma-lg">
                        <p class="text-start text-h5 text-weight-medium"> Nature Retreats </p>
                        <p class="text-start text-subtitle1"> Plan your next outdoor adventure with our camp reservation system. Discover scenic campsites and book your stay hassle-free. </p>
                        <q-btn size="lg" unelevated color="blue" no-caps> Reserve Campsite </q-btn>
                    </div>
                </div>
            </div>

            <div class="row q-col-gutter-xl justify-center items-center">
                <div class="col-12 col-xs-12 col-sm-12 lt-md">
                    <q-img src="https://cdn.prod.website-files.com/60411749e60be86afb89d2f0/61815e0160a2242228e341b3_links-kv_2-p-500.png"></q-img>
                </div>
                <div class="col-12 q-col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 ">
                    <div class="q-ma-lg">
                        <p class="text-start text-h5 text-weight-medium"> Exciting Events </p>
                        <p class="text-start text-subtitle1"> Browse upcoming events and concerts. Secure your tickets and be part of unforgettable experiences. </p>
                        <q-btn size="lg" unelevated color="blue" no-caps> Buy Event Tickets </q-btn>
                    </div>
                </div>
                <div class="col-5 gt-sm">
                    <q-img src="https://cdn.prod.website-files.com/60411749e60be86afb89d2f0/629dfe065fd8cf63e7ff530e_plugin-kv-p-500.png"></q-img>
                </div>
            </div>
        </div>
    </div>
    <p class="text-center text-h4 q-mt-xl text-weight-bold"> Here's what they have to say. </p>

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

        <q-carousel-slide :name="`style${n}`" class="column no-wrap" v-for="n in 5" :key="n">
            <div class="row q-col-gutter-x-md">
                <div class="col-6">
                    <q-card class="q-pa-md">
                        <img style="height: 50px; width: 50px" src="https://assets-global.website-files.com/609cbadfde419c81adafa17b/609db59d8c7a879cbd837708_Quotation%20Mark.svg" alt="">
                        <div class="q-mt-md text-start">
                            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Temporibus, consectetur!
                            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Temporibus, consectetur!
                            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Temporibus, consectetur!
                            <br>
                            <br>
                            - John Doe
                        </div>
                    </q-card>
                </div>
                <div class="col-6">
                    <q-card class="q-pa-md">
                        <img style="height: 50px; width: 50px" src="https://assets-global.website-files.com/609cbadfde419c81adafa17b/609db59d8c7a879cbd837708_Quotation%20Mark.svg" alt="">
                        <div class="q-mt-md text-start">
                            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Temporibus, consectetur!
                            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Temporibus, consectetur!
                            Lorem ipsum dolor sit amet consectetur, adipisicing elit. Temporibus, consectetur!
                            <br>
                            <br>
                            - John Doe
                        </div>
                    </q-card>
                </div>
            </div>
        </q-carousel-slide>
    </q-carousel>

    <div class="q-px-lg q-py-md">
        <q-timeline :layout="layout" color="secondary">
            <q-timeline-entry heading>
                <p class="text-center text-h4 q-mt-xl text-weight-bold"> How it Works </p>
            </q-timeline-entry>

            <q-timeline-entry
                title="Choose Your Service"
                subtitle="February 22, 1986"
                side="left"
            >
                <div>
                    Users select from ordering food, reserving a room, or buying tickets.
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </div>
                <template v-slot:subtitle>
                    <q-img src="https://www.littlethings.info/wp-content/uploads/2014/04/dummy-image-green-e1398449160839.jpg" style="height: 300px;"></q-img>
                </template>
            </q-timeline-entry>

            <q-timeline-entry
                title="Make a Selection"
                subtitle="February 22, 1986"
                side="right"
            >
                <div>
                    Users browse through the offerings and make a choice. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </div>
                <template v-slot:subtitle>
                    <q-img src="https://www.littlethings.info/wp-content/uploads/2014/04/dummy-image-green-e1398449160839.jpg" style="height: 300px;"></q-img>
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
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </div>
                <template v-slot:subtitle>
                    <q-img src="https://www.littlethings.info/wp-content/uploads/2014/04/dummy-image-green-e1398449160839.jpg" style="height: 300px;"></q-img>
                </template>
            </q-timeline-entry>
        </q-timeline>
    </div>
    <div class="bg-grey text-center text-h5" style="height: 350px;">
        Footer
    </div>
</template>


