<script setup>

import { ref } from 'vue'
import FoodCardItem from '@/Components/Customer/Product/FoodCardItem.vue'
import { Link, Head } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    order_constants: Object,
    items: Object,
    subtotal: Number,
    google_maps_api_key: Object,
})

const form = useForm({
    items: props.items,
    payment_method: 'gcash',
    mode: 'delivery'
})

const submit = () => {
    form.post(route('product.pay'), {
        onSuccess: (response) => {
            console.log(response)
        }
    })
}

</script>

<template>
    
    <Head title="Checkout" />
    <div>
        <Link :href="route('customer.cart.index')">
            <q-btn label="Go back" icon="arrow_back" color="blue" flat no-caps unelevated class="q-mx-xl q-my-md" />
        </Link>
        <div class="q-mb-xl q-mx-xl">
            <div class="row q-col-gutter-xl">
                <div class="col-7">
                    <q-card flat bordered>
                        <q-card-section>
                            <q-item>
                                <q-item-section class="text-h6">{{ form.mode }} Details</q-item-section>
                                <q-item-section side>
                                    <q-btn-toggle
                                        v-model="form.mode"
                                        no-caps
                                        rounded
                                        unelevated
                                        toggle-color="primary"
                                        color="white"
                                        text-color="primary"
                                        :options="[
                                            { label: 'Delivery', value: 'delivery' },
                                            { label: 'Pickup', value: 'pickup' }
                                        ]"
                                    />
                                </q-item-section>
                            </q-item>
                            <q-item v-if="form.mode == 'delivery'">
                                <q-item-section avatar>
                                    <q-icon name="location_on"></q-icon>
                                </q-item-section>
                                <q-item-section>
                                    <!-- 09123456789 -->
                                    {{ $page.props.auth.user.phone_number }}
                                    <q-item-label>{{ $page.props.auth.user.address }}</q-item-label>
                                    <!-- <q-item-label>Purok Sacred heart, Buagsong, Cordova, Cebu</q-item-label> -->
                                </q-item-section>
                            </q-item>
                            <div v-else>
                                <div class="rounded-borders bg-grey q-mt-sm" style="height: auto">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3926.0373439137084!2d124.03639717545175!3d10.258571868531218!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a99184b152420b%3A0x6bebeab9d8bca659!2sRJC%20CAFE!5e0!3m2!1sen!2sph!4v1717932087938!5m2!1sen!2sph" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                    <!-- <img :src="`https://maps.googleapis.com/maps/api/staticmap?center=Berkeley,CA&zoom=14&size=400x400&key=${google_maps_api_key}`" alt=""> -->
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
                                <q-item 
                                    tag="label" v-ripple 
                                    v-for="(payment_method, index) in order_constants.payment_methods" 
                                    :key="index"
                                >
                                    <q-item-section side top>
                                        <q-radio v-model="form.payment_method" :val="payment_method"/>
                                    </q-item-section>
                                    <q-item-section>
                                        <q-item-label>{{ payment_method }}</q-item-label>
                                    </q-item-section>
                                    <q-item-section side>
                                        <q-avatar square>
                                            <img src="https://cdn.quasar.dev/img/boy-avatar.png">
                                        </q-avatar>
                                    </q-item-section>
                                </q-item>
                                <!-- <q-item tag="label" v-ripple v-if="mode == 'delivery'">
                                    <q-item-section side top>
                                        <q-radio v-model="form.payment_method" val="cash_on_delivery"/>
                                    </q-item-section>
                                    <q-item-section>
                                        <q-item-label>Cash On delivery</q-item-label>
                                    </q-item-section>
                                    <q-item-section side>
                                        <q-avatar square>
                                            <img src="https://cdn.quasar.dev/img/boy-avatar.png">
                                        </q-avatar>
                                    </q-item-section>
                                </q-item>
                                <q-item tag="label" v-ripple v-else>
                                    <q-item-section side top>
                                        <q-radio v-model="form.payment_method" val="walk_in"/>
                                    </q-item-section>
                                    <q-item-section>
                                        <q-item-label>Walk In</q-item-label>
                                    </q-item-section>
                                    <q-item-section side>
                                        <q-avatar square>
                                            <img src="https://cdn.quasar.dev/img/boy-avatar.png">
                                        </q-avatar>
                                    </q-item-section>
                                </q-item> -->
                            </q-list>
                            <q-separator class="q-my-md" />
                            <q-item>
                                <q-item-section class="text-h6">Order Summary</q-item-section>
                                <q-item-section side>
                                    <q-chip :class="$q.dark.isActive ? 'bg-grey-9' : ''">{{ items.length }} items</q-chip>
                                </q-item-section>
                            </q-item>
                            <q-list>
                                <FoodCardItem :item="item" v-for="(item, index) in items" :key="index" />
                            </q-list>
                        </q-card-section>
                    </q-card>
                </div>
                <div class="col-5"  style="position: relative;">
                    <div style="position: sticky; top: 50px">
                        <q-card>
                            <q-card-section>
                                <q-item>
                                    <q-item-section class="text-h6">Order Summary</q-item-section>
                                </q-item>
                                <q-item>
                                    <q-item-section>Subtotal</q-item-section>
                                    <q-item-section side>
                                        {{ subtotal }}
                                    </q-item-section>
                                </q-item>
                                <q-item v-if="form.mode == 'delivery'">
                                    <q-item-section>delivery fee</q-item-section>
                                    <q-item-section side>
                                        P5.00
                                    </q-item-section>
                                </q-item>
                                <q-separator/>
                                <q-item class="text-h6">
                                    <q-item-section>Total</q-item-section>
                                    <q-item-section side>
                                        {{  subtotal  }}
                                    </q-item-section>
                                </q-item>
                            </q-card-section>
                        </q-card>
                        <q-btn class="full-width q-mt-xl" @click="submit" label="Continue to payment" no-caps color="blue" size="lg"></q-btn>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>