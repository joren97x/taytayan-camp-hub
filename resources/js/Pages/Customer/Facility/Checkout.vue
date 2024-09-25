<script setup>

import { Link, useForm } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'

const page = usePage()
const props = defineProps({
    facility: Object,
    date: Array,
    reservation_constants: Object
})

const form = useForm({
    payment_method: 'gcash',
    total: props.facility.price,
    guests: 1,
    check_in: props.date.from,
    check_out: props.date.to,
    facility_id: props.facility.id
})

const submit = () => {
    form.post(route('facility.pay'), {
        onSuccess: () => {
            console.log('ok')
        }
    })
}

</script>

<template>
    <Head title="Checkout" />
    <div class="bg-grey-2">
        <q-card class="row justify-between bg-white q-pa-sm" flat bordered>
            <div class="col-12 text-center text-h6" style="max-width: 1280px; margin: 0 auto; position: relative;">
               <q-avatar size="lg">
                    <q-img src="../logo.png" fill="cover" />
                </q-avatar>
                    Taytayan CAMP
                <Link :href="route('customer.cart.index')" class="absolute-left">
                    <q-btn :label="$q.screen.lt.md ? '' : 'Go back'" icon="arrow_back" color="black" flat no-caps unelevated />
                </Link>
            </div>
        </q-card>
        <div style="max-width: 1280px; margin: 0 auto;">
            <div class="q-mt-sm">
                <div class="row q-col-gutter-md">
                    <div class="col-7 col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
                        <q-card flat bordered>
                            <q-card-section>
                                <div class="text-h6 text-center">Checkout</div>
                            </q-card-section>
                        </q-card>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="row justify-between lt-md">
            <div class="col-12 text-center center text-h6">
                Checkout
                <Link :href="route('customer.cart.index')" class="absolute-left">
                    <q-btn label="Go back" icon="arrow_back" color="blue" flat no-caps unelevated />
                </Link>
            </div>
        </div>

    <div class="row q-col-gutter-md q-ma-lg">
        <div class="col-8">
            <q-list>
                <q-item 
                    tag="label" v-ripple 
                    v-for="(payment_method, index) in reservation_constants.payment_methods" 
                    :key="index"
                >
                    <q-item-section side top>
                        <q-radio v-model="form.payment_method" :val="payment_method"/>
                    </q-item-section>
                    <q-item-section>
                        <q-item-label class="text-capitalize">{{ payment_method }}</q-item-label>
                    </q-item-section>
                    <q-item-section side>
                        <q-avatar square>
                            <img src="https://cdn.quasar.dev/img/boy-avatar.png">
                        </q-avatar>
                    </q-item-section>
                </q-item>
            </q-list>
            <p>
                Check in: {{ date.from }}
            </p>
            <p>
                Check out: {{ date.to }}
            </p>
        </div>
        <div class="col-4">
            <div>
                {{ props }}
            </div>
            <q-btn class="full-width" color="primary" @click="submit">
                Check Out
            </q-btn>
        </div>
        {{ form }}
    </div>
</template>