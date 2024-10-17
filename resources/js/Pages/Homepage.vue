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


    const showVideo = ref(false)

</script>

<template>
    <Head title="Welcome" />
    <div >
        <q-dialog 
            v-model="showVideo" 
            persistent 
            backdrop-filter="brightness(20%)"
            transition-show="slide-up"
            transition-hide="slide-down"    
        >
            <!-- <div style="height: 200px; width: 200px;" class="bg-red">
            </div> -->
            <div class="fit items-center flex justify-center" style="max-width: 100vw;">
                <q-btn icon="close" class="absolute-top-right q-mt-md q-mr-md" v-close-popup color="white" flat round/>
                <q-card flat style="width: 900px; height: 500px; max-width: 900px">
                    <iframe width="100%" height="100%" src="https://www.youtube.com/embed/vnTXV_Ur_7U?si=1fygKWQ7UkpdCFCn" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </q-card>
            </div>
            <!--  -->
        </q-dialog>
    <!-- <div style="background: linear-gradient(to bottom, #FFFFFF, #EEEEEE)"> -->
        <div class="row q-col-gutter-lg items-center q-my-md">
            <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <div :class="['q-ma-lg', $q.screen.lt.md ? 'text-center' : '']">
                    <p class="text-start text-h3 q-mt-xl text-weight-medium">Taytayan Camp Hub</p>
                    <p class="text-start text-subtitle1"> Experience the ultimate adventure at Taytayan Camp Hub! Order your favorite drinks, book event tickets, and reserve a cozy camp tent for an unforgettable outdoor getaway. Enjoy, discover, and indulge in every moment at Taytayan Camp Hub.</p>
                    <q-btn size="lg" unelevated color="primary" no-caps class="q-px-xl"> Create your account </q-btn>
                </div>
            </div>
            <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                <!-- <div class="q-pa-lg">
                    <q-img src="https://cdn.prod.website-files.com/60411749e60be86afb89d2f0/6386f5e5cd1c0534842c48df_dashboard.png"></q-img>
                </div> -->
                <div class="bg-container" style="position: relative; cursor: pointer;">
                    <div class="full-width q-my-md rounded-borders" style="overflow: hidden;">
                            <div class="blurred-background" :style="`background-image: url('whatsin.jpg');`"></div>
                            <!-- Foreground image (not blurred) -->
                            <q-img 
                                :src="`whatsin.jpg`"
                                class="rounded-borders content-wrapper" 
                                height="100%"
                                fit="contain"
                                style="position: relative; z-index: 2;" 
                            />
                            <div class="overlay" @click="showVideo = true">
                                <div class="q-pa-md" style="border-radius: 50%; background-color: rgba(0,0,0,0.5);">
                                <q-icon name="play_arrow" size="64px" color="white" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <q-separator class="q-my-xl"/> -->

        <p class="text-center text-h5 q-my-xl text-weight-medium">Our Features & Services</p>
        <!-- <p class="text-red text-center">Do the relationships of the models tommorow</p> -->
        <div :class="$q.screen.lt.md ? 'text-center' : ''">
            <div class="row justify-center items-center">
                <div class="col-12 col-xs-12 col-sm-12 lt-md">
                    <div class="q-px-lg">
                        <!-- <q-img src="https://cdn.prod.website-files.com/60411749e60be86afb89d2f0/61815e0160a2242228e341b3_links-kv_2-p-500.png"></q-img> -->
                        <!-- <q-img src="products_pic.png"></q-img> -->
                        <q-img src="hp_product.png" fit="contain" height="380px"></q-img>
                    </div>
                </div>
                <div class="col-12 q-col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="q-mx-lg q-mb-lg">
                        <p class="text-start text-h5 text-weight-medium"> Milk Tea Delights </p>
                        <p class="text-start text-subtitle1"> Craving something refreshing? Indulge in our delicious, customizable milk tea, made just the way you like it. Explore our wide range of refreshing milk tea flavors and customizable options. </p>
                        <q-btn size="lg" unelevated color="primary" no-caps label="Order Now"/>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-5 col-md-5 gt-sm">
                    <!-- <q-img src="https://cdn.prod.website-files.com/60411749e60be86afb89d2f0/61815e0160a2242228e341b3_links-kv_2-p-500.png"></q-img> -->
                    <!-- <q-img src="products_pic.png"></q-img> -->
                    <q-img src="hp_product.png" fit="contain" height="380px"></q-img>
                </div>
            </div>

            <div class="row justify-center items-center q-mt-xl">
                <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="q-px-lg">
                        <!-- <q-img src="facilities_pic.png"></q-img> -->
                        <q-img src="hp_facility.png" height="380px"/>
                    </div>
                </div>
                <div class="col-12 q-col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                    <div class="q-mx-lg q-mb-lg">
                        <p class="text-start text-h5 text-weight-medium"> Nature Retreats </p>
                        <p class="text-start text-subtitle1"> Ready for an adventure? Book your camp tents, huts, or cozy camp rooms in just a few clicks! Discover scenic campsites and book your stay hassle-free. </p>
                        <q-btn size="lg" unelevated color="primary" no-caps> Reserve Now </q-btn>
                    </div>
                </div>
            </div>

            <div class="row justify-center items-center q-my-xl">
                <div class="col-12 col-xs-12 col-sm-12 lt-md">
                    <div class="q-px-lg">
                        <q-img src="hp_event.png" height="380px"></q-img>
                            <!-- <q-img src="events_pic.png"></q-img> -->
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
                    <q-img src="hp_event.png" height="380px"></q-img>
                    <!-- <q-img src="events_pic.png"></q-img> -->
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
            <q-card class="q-pa-lg" style="height: 100%;" bordered flat>
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
        <div class="col-12">
            <p class="text-center text-h5 text-weight-medium q-mt-md"> How it Works </p>
        </div>
        <div class="col-md-6 col-lg-6 col-xl-6 gt-sm justify-center flex items-center">
            <q-img src="step3.png" height="300px"></q-img>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="q-px-lg">
                <q-timeline color="primary">
                    <q-timeline-entry heading>
                    </q-timeline-entry>

                    <q-timeline-entry
                        title="Explore Our Services"
                        subtitle="Step 1"
                    >
                        <div>
                            Browse through our offerings—order food, book a room, or grab event tickets. Find exactly what you need with just a few clicks!
                        </div>
                        <!-- <template v-slot:subtitle>
                            <q-img src="step1.png" height="300px"></q-img>
                        </template> -->
                    </q-timeline-entry>

                    <q-timeline-entry
                        title="Make Your Choice"
                        subtitle="Step 2"
                    >
                        <div class="justify-center flex items-center">
                            Pick your desired service, whether it's a delicious meal, a cozy room, or an exciting event. Customize your selection to suit your needs.
                        </div>
                        <!-- <template v-slot:subtitle>
                            <q-img src="step2.png" height="300px"></q-img>
                        </template> -->
                    </q-timeline-entry>

                    <q-timeline-entry
                        title="Sit Back and Relax"
                        subtitle="Step 3"
                        color="primary"
                        icon="done_all"
                    >
                        <div>
                             Receive your order, check into your booked room, or enjoy the event. We make sure everything is ready, so you can just focus on having a great time!
                        </div>
                        <!-- <template v-slot:subtitle>
                            <q-img src="step3.png" height="300px"></q-img>
                        </template> -->
                    </q-timeline-entry>
                </q-timeline>
            </div>
        </div>
    </div>

    <!-- <div class="bg-grey text-center text-h5" style="height: 350px;">
        Footer
    </div> -->
</template>


<style scoped>
.bg-container {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
}
.overlay {
  position: absolute;
  top: 0;
  
  right: 0;
  bottom: 0;
  left: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9;
  border-radius: 8px;
}

.blurred-background {
   /* Background image you want to blur */
  background-size: cover;
  background-position: center;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  filter: blur(8px); /* Apply blur effect */
  z-index: 1; /* Keep it behind the foreground content */
}

/* Foreground image (clear, no blur) */
.content-wrapper {
  background: rgba(0, 0, 0, 0.5);

  position: relative;
  z-index: 2; /* Ensure it's on top of the blurred background */
}
</style>