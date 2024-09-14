
<script setup>

import { Link } from '@inertiajs/vue3'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { ref } from 'vue'
import { useQuasar } from 'quasar';

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
    <div>
        <div class="full-width bg-grey q-my-md rounded-borders" style="height: 50vh;">
            <q-img :src="`/storage/${event.cover_photo}`" class="rounded-borders" height="100%"></q-img>
        </div>
        <div class="row q-px-md">
            <div :class="[$q.screen.width >= 700 ? 'col-8' : 'col-12']">
                <div>{{ event.date }}, {{ event.start_time }}</div>
                <div class="text-h5">{{ event.title }}</div>
                <div>{{ event.description }}</div>
                <div>Date And Time</div>
                <div>{{ event.date }}, {{ event.start_time }}</div>
                <div>Frequently Asked Questions</div>
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
                    </q-list>
                <h6>
                    {{ $q.screen.width >= 700 }}
                    {{ $q.platform.is.desktop }}
                </h6>
                <h1>bruh</h1>
            </div>
            <div class="col-4" v-if="$q.screen.width >= 700">
                {{ event.tickets_sold }}
                {{ event.capacity }}
                hello
                <div>
                    <div>
                        <q-item>
                            <q-item-section>
                                <div>
                                    Admission
                                </div>
                                <div>
                                    {{ event.admission_fee }}
                                </div>
                            </q-item-section>
                            <q-item-section side>
                                <q-btn icon="remove" @click="attendees--"></q-btn>
                                {{ attendees }}
                                <q-btn icon="add" @click="attendees++"></q-btn>
                            </q-item-section>
                        </q-item>
                        <Link :href="route('event.checkout')" :data="{ event_id: event.id, attendees }">
                            <q-btn class="full-width" color="primary" no-caps>Check Out</q-btn>
                        </Link>
                        <!-- Button for Mobile Screens -->
                        
                    </div>
                </div>
                
            </div>
            <div class="q-pa-md bg-white fixed-bottom" v-else>
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
        </div>
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
  