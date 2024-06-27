
<script setup>

import { Link } from '@inertiajs/vue3'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    event: Object
})

</script>

<template>
    <div>
        <div class="full-width bg-grey rounded-borders" style="height: 350px;">

        </div>
        <div class="row">
            <div class="col-8">
                <div>{{ event.date }}, {{ event.start_time }}</div>
                <div>{{ event.title }}</div>
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
            </div>
            <div class="col-4">
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
                            <!-- <q-item-section side>
                                <q-btn icon="remove"></q-btn>
                                <q-btn icon="add"></q-btn>
                            </q-item-section> -->
                        </q-item>
                        <Link :href="route('event.checkout')">
                            <q-btn class="full-width" color="primary" no-caps>Check Out</q-btn>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  