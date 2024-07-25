<script setup>

import { Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
    facility: Object,
    date: Array,
    reservation_constants: Object
})

const form = useForm({
    payment_method: null
})

</script>

<template>
    <div class="row">
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
        </div>
        <div class="col-4">
            <div>
                {{ props }}
            </div>
        </div>
    </div>
</template>