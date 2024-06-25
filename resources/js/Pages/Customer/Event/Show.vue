
<script setup>

import { Link } from '@inertiajs/vue3'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'

defineOptions({
    layout: CustomerLayout
})

</script>

<template>
    <div>
        <div class="full-width bg-grey rounded-borders" style="height: 350px;">

        </div>
        <div class="row">
            <div class="col-8">
                <div>Wednesday, March 20</div>
                <div>Sabado Night</div>
                <div>Lorem ipsum dolor sit amet consectetur adipisicing elit. Repudiandae, rem.</div>
                <div>Date And Time</div>
                <div>Wednesday, March 20</div>
                <div>Frequently Asked Questions</div>
                <q-list bordered class="rounded-borders">
                    <q-expansion-item
                        expand-separator
                        icon="perm_identity"
                        label="Account settings"
                        caption="John Doe"
                        v-for="n in 3"
                    >
                        <q-card>
                        <q-card-section>
                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, eius reprehenderit eos corrupti
                            commodi magni quaerat ex numquam, dolorum officiis modi facere maiores architecto suscipit iste
                            eveniet doloribus ullam aliquid.
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
                                    P100.00
                                </div>
                            </q-item-section>
                            <q-item-section side>
                                <q-btn icon="remove"></q-btn>
                                <q-btn icon="add"></q-btn>
                            </q-item-section>
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
  