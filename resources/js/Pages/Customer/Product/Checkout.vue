<script setup>

    import { ref } from 'vue'
    import FoodCardItem from '@/Components/Customer/Product/FoodCardItem.vue'

    const mode = ref('Delivery')
    const payment_method = ref('GCash')

    const payment_methods = [
        'GCash',
        'Credit/Debit Card',
        'GrabPay',
        'Maya'
    ]

</script>

<template>
    
    <div>
        <q-item clickable to="/milktea-menu">
            <q-item-section avatar>
                <q-btn round flat icon="arrow_back"></q-btn>
            </q-item-section>
            <q-item-section class="text-h6">Go back</q-item-section>
        </q-item>
        <div class="q-ma-xl">
            <div class="row q-col-gutter-xl">
                <div class="col-7">
                    <q-card>
                        <q-card-section>
                            <q-item>
                                <q-item-section class="text-h6">{{ mode }} Details</q-item-section>
                                <q-item-section side>
                                    <q-btn-toggle
                                        v-model="mode"
                                        no-caps
                                        rounded
                                        unelevated
                                        toggle-color="primary"
                                        color="white"
                                        text-color="primary"
                                        :options="[
                                            { label: 'Delivery', value: 'Delivery' },
                                            { label: 'Pickup', value: 'Pickup' }
                                        ]"
                                    />
                                </q-item-section>
                            </q-item>
                            <q-item v-if="mode == 'Delivery'">
                                <q-item-section avatar>
                                    <q-icon name="location_on"></q-icon>
                                </q-item-section>
                                <q-item-section>
                                    09123456789
                                    <q-item-label>Purok Sacred heart, Buagsong, Cordova, Cebu</q-item-label>
                                </q-item-section>
                            </q-item>
                            <div v-else>
                                <div class="full-width rounded-borders bg-grey q-mt-sm" style="height: 180px">
                                    map
                                </div>
                                <q-item>
                                    <q-item-section avatar>
                                        <q-icon name="home"></q-icon>
                                    </q-item-section>
                                    <q-item-section class="text-h6">
                                        Store Location
                                        <q-item-label caption>Barangay San Vicente, Olango Island</q-item-label>
                                    </q-item-section>
                                </q-item>
                            </div>
                                <q-separator class="q-my-md" />
                                <q-item>
                                    <q-item-section class="text-h6">Pay with</q-item-section>
                                    <q-item-section side>
                                        <q-chip :class="$q.dark.isActive ? 'bg-grey-9' : ''">Required</q-chip>
                                    </q-item-section>
                                </q-item>
                            <q-list>
                                <q-item tag="label" v-ripple v-for="(pm, i) in payment_methods" :key="i">
                                    <q-item-section side top>
                                        <q-radio v-model="payment_method" :val="pm"/>
                                    </q-item-section>
                                    <q-item-section>
                                        <q-item-label>{{ pm }}</q-item-label>
                                    </q-item-section>
                                    <q-item-section side>
                                        <q-avatar square>
                                            <img src="https://cdn.quasar.dev/img/boy-avatar.png">
                                        </q-avatar>
                                    </q-item-section>
                                </q-item>
                                <q-item tag="label" v-ripple v-if="mode == 'Delivery'">
                                    <q-item-section side top>
                                        <q-radio v-model="payment_method" val="Cash On Delivery"/>
                                    </q-item-section>
                                    <q-item-section>
                                        <q-item-label>Cash On Delivery</q-item-label>
                                    </q-item-section>
                                    <q-item-section side>
                                        <q-avatar square>
                                            <img src="https://cdn.quasar.dev/img/boy-avatar.png">
                                        </q-avatar>
                                    </q-item-section>
                                </q-item>
                                <q-item tag="label" v-ripple v-else>
                                    <q-item-section side top>
                                        <q-radio v-model="payment_method" val="Walk In"/>
                                    </q-item-section>
                                    <q-item-section>
                                        <q-item-label>Walk In</q-item-label>
                                    </q-item-section>
                                    <q-item-section side>
                                        <q-avatar square>
                                            <img src="https://cdn.quasar.dev/img/boy-avatar.png">
                                        </q-avatar>
                                    </q-item-section>
                                </q-item>
                            </q-list>
                            <q-separator class="q-my-md" />
                            <q-item>
                                <q-item-section class="text-h6">Order Summary</q-item-section>
                                <q-item-section side>
                                    <q-chip :class="$q.dark.isActive ? 'bg-grey-9' : ''">3 items</q-chip>
                                </q-item-section>
                            </q-item>
                            <q-list>
                                <FoodCardItem v-for="n in 3" :key="n"></FoodCardItem>
                            </q-list>
                        </q-card-section>
                    </q-card>
                </div>
                <div class="col-5"  style="position: relative;">
                    <div style="position: sticky; top: 50px">
                        <q-card>
                            <q-card-section>
                                <q-item>
                                    <q-item-section class="text-h6">Order Total</q-item-section>
                                </q-item>
                                <q-item>
                                    <q-item-section>Subtotal</q-item-section>
                                    <q-item-section side>
                                        P90.00
                                    </q-item-section>
                                </q-item>
                                <q-item>
                                    <q-item-section>Tax or somn</q-item-section>
                                    <q-item-section side>
                                        P5.00
                                    </q-item-section>
                                </q-item>
                                <q-separator/>
                                <q-item class="text-h6">
                                    <q-item-section>Total</q-item-section>
                                    <q-item-section side>
                                        P95.00
                                    </q-item-section>
                                </q-item>
                            </q-card-section>
                        </q-card>
                        <q-btn class="full-width q-mt-xl" label="Continue to payment" no-caps color="blue" size="lg"></q-btn>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>