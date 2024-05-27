<script setup>

import { useForm, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { useQuasar } from 'quasar'

const emit = defineEmits(['close'])
const props = defineProps({
    dialog: Boolean
})

const $q = useQuasar()
const page = usePage()
const show = computed(() => props.dialog)
    
const form = useForm({
    phone_number: page.props.auth.user.phone_number,
    address: page.props.auth.user.address
})

const submit = () => {
    form.put(route('add-address'), {
        onSuccess: () => {
            emit('close')
            $q.notify('Address Successfully Set')
        }
    })
}
</script>

<template>
    <q-dialog v-model="show">
        <q-card style="width: 50vw">
            <q-form @submit="submit">
                <q-card-section>
                    <div class="text-h6">New Address</div>
                    <div>To place order, please add a delivery address</div>
                </q-card-section>
                <q-card-section>
                    {{ form }}
                    <!-- <q-input filled label="Phone Number"></q-input> -->
                    <q-input
                        filled
                        v-model="form.phone_number"
                        label="Phone"
                        :error="form.errors.phone_number ? true : false"
                        :error-message="form.errors.phone_number"
                    />

                    <q-input 
                        filled 
                        v-model="form.address" 
                        class="q-my-md" 
                        :error="form.errors.address ? true : false"
                        :error-message="form.errors.address"
                        label="Region, Province, City, Barangay"
                    />

                    <q-input filled label="Street name, Building, House No"></q-input>
                </q-card-section>
                <q-card-actions>
                    <q-space/>
                    <q-btn v-close-popup no-caps flat>Cancel</q-btn>
                    <q-btn 
                        v-close-popup 
                        color="primary"
                        type="submit" 
                        unelevated 
                        no-caps
                        :loading="form.processing"
                        :disable="form.processing"
                    >
                        Submit
                    </q-btn>
                </q-card-actions>
            </q-form>
        </q-card>
    </q-dialog>
</template>