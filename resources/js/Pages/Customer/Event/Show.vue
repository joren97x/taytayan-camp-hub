
<script setup>

import { Link, Head } from '@inertiajs/vue3'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { ref } from 'vue'
import { useQuasar, date } from 'quasar';

defineOptions({
    layout: CustomerLayout
})

defineProps({
    event: Object
})

const $q = useQuasar()
const attendees = ref(1)

</script>

<template>
    <Head :title="event.title" />
    <div>
        <!-- <div class="full-width bg-grey q-my-md rounded-borders" style="height: 50vh;"> -->
        <div class="full-width q-my-md rounded-borders" style="height: 50vh; position: relative; overflow: hidden;">
            <div class="blurred-background" :style="`background-image: url('/storage/${event.cover_photo}');`"></div>
            <!-- Foreground image (not blurred) -->
            <q-img 
                :src="`/storage/${event.cover_photo}`"
                class="rounded-borders content-wrapper" 
                height="100%"
                fit="contain"
                style="position: relative; z-index: 2;" 
            />
        </div>

        <div class="row q-ma-md">
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
                <div class="text-subtitle1">
                    {{ date.formatDate(event.date, 'dddd, MMMM D') }}
                </div>
                <div class="text-h3 text-weight-bold">
                    {{ event.title }}
                </div>
                <div>
                    {{ event.description }}
                </div>
                <q-separator class="q-my-md"/>
                <div>
                    <div class="text-h6">Date & Time</div>
                    <q-item>
                        <q-item-section avatar>
                            <q-icon name="event" />
                        </q-item-section>
                        <q-item-section>
                            {{ date.formatDate(event.date, 'dddd, MMMM D') }} - {{ event.start_time }} PM <span class="text-red text-caption">PM and AM (fix)</span>
                        </q-item-section>
                    </q-item>
                </div>
                <q-separator class="q-my-md"/>
                <div style="margin-bottom: 80px">
                    <div class="text-h6">Location</div>
                    <q-item>
                        <q-item-section avatar>
                            <q-icon name="location_on" />
                        </q-item-section>
                        <q-item-section>
                            <q-item-label>RJC Cafe</q-item-label>
                            <q-item-label caption>Olango Island</q-item-label>
                        </q-item-section>
                    </q-item>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 gt-sm ">
                <q-card bordered>
                    <q-card-section class="row">
                        <div class="col-6">
                            <div>Admission</div>
                            <div>{{ event.admission_fee }}</div>
                        </div>
                        <div class="col-6 justify-end items-center flex">
                            <q-btn icon="remove" round unelevated class="bg-grey-4" @click="attendees--"></q-btn>
                            <span class="q-mx-md text-subtitle1">{{ attendees }}</span>
                            <q-btn icon="add" round unelevated class="bg-grey-4" @click="attendees++"></q-btn>
                        </div>
                    </q-card-section>
                    <!-- <q-item>
                        <q-item-section>
                            <div>
                                Admission
                            </div>
                            <div>
                                {{ event.admission_fee }}
                            </div>
                        </q-item-section>
                        <q-item-section side class="item-center flex justify-center self-center">
                            <q-btn icon="remove" @click="attendees--"></q-btn>
                            {{ attendees }}
                            <q-btn icon="add" @click="attendees++"></q-btn>
                        </q-item-section>
                    </q-item> -->
                    <q-card-actions>
                        <Link :href="route('event.checkout')" class="full-width" :data="{ event_id: event.id, attendees }">
                            <q-btn class="full-width" rounded color="primary" no-caps>Check Out</q-btn>
                        </Link>
                    </q-card-actions>
                </q-card>
            </div>
        </div>
        <div class="q-pa-md bg-white fixed-bottom lt-md">
            <div class="row q-pa-none q-col-gutter-md">
                <div class="col-8">
                    <q-item class="q-pa-none">
                        <q-item-section>
                            <div>
                                Admission
                            </div>
                            <div>
                                {{ event.admission_fee }}
                            </div>
                        </q-item-section>
                        <q-item-section side>
                            <q-btn-group>
                                <q-btn icon="remove" @click="attendees--"></q-btn>
                                {{ attendees }}
                                <q-btn icon="add" @click="attendees++"></q-btn>
                            </q-btn-group>
                        </q-item-section>
                    </q-item>
                </div>
                <div class="col-4 self-center">
                    <Link :href="route('event.checkout')" :data="{ event_id: event.id, attendees }">
                        <q-btn
                            label="Checkout"
                            color="primary"
                            class="full-width"
                            no-caps
                        />  
                    </Link>
                </div>
            </div>
        </div>

        <!-- <div class="row q-px-md">
            <div :class="[$q.screen.width >= 700 ? 'col-8' : 'col-12']">
                <div>{{ event.date }}, {{ event.start_time }}</div>
                <div class="text-h5">{{ event.title }}</div>
                <div>{{ event.description }}</div>
                <div>Date And Time</div>
                <div>{{ event.date }}, {{ event.start_time }}</div> -->
                <!-- <div>Frequently Asked Questions</div>
                <q-list bordered class="rounded-borders">
                    <q-expansion-item
                        expand-separator
                        icon="perm_identity"
                        label="Some uhh questions"
                        v-for="n in 3"
                    >
                        <q-card>
                        <q-card-section>
                            some uhh answers
                        </q-card-section>
                        </q-card>
                    </q-expansion-item>
                    </q-list> -->
                <!-- <h6>
                    {{ $q.screen.width >= 700 }}
                    {{ $q.platform.is.desktop }}
                </h6>
                <h1>bruh</h1>
            </div>
            
        </div> -->
        <!-- <q-dialog
      v-model="dialog"
      persistent
      :maximized="true"
      transition-show="slide-up"
      transition-hide="slide-down"
        style="height: 200px"
    >
      <q-card >
        <q-bar>
          <q-space />

          <q-btn dense flat icon="close" v-close-popup>
            <q-tooltip class="bg-white text-primary">Close</q-tooltip>
          </q-btn>
        </q-bar>

        <q-card-section>
          <div class="text-h6">Alert</div>
        </q-card-section>

        <q-card-section class="q-pt-none">
            <q-item-section side>
                <q-btn icon="remove" @click="attendees--"></q-btn>
                {{ attendees }}
                <q-btn icon="add" @click="attendees++"></q-btn>
            </q-item-section>
        </q-card-section>
        <q-card-actions class="fixed-bottom">
            <Link :href="route('event.checkout')" :data="{ event_id: event.id, attendees }" class="full-width">
                <q-btn class="full-width" color="primary" no-caps>Check Out</q-btn>
            </Link>
        </q-card-actions>
      </q-card>
    </q-dialog> -->
    </div>
</template>
  
<style scoped>

/* CSS IS HARD FRRR(i used chatGPT XD) */
/* Blurred background */
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
  position: relative;
  z-index: 2; /* Ensure it's on top of the blurred background */
}

</style>