<script setup>

import { useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { defineEmits } from 'vue'

const $q = useQuasar()
const emit = defineEmits(['close'])
const props = defineProps({
    user: Object,
    dialog: Boolean
})

const form = useForm({
    first_name: props.user.first_name,
    last_name: props.user.last_name,
    email: props.user.email,
    phone_number: props.user.phone_number,
    address: props.user.address,
    role: props.user.role,
})

const updateeUser = () => {
    form.patch(route('admin.users.update', props.user.id), {
        onSuccess: () => {
            emit('close')
            $q.notify('User Succesfully Updated')
        }
    })
}

</script>

<template>
    <q-dialog full-width v-model="props.dialog">
        <q-card>
            <q-card-section>
                {{ form }}
                <div class="row justify-between q-my-md">
                    <div class="text-h6 text-start col-12" style="position: relative">
                        Edit User
                        <q-btn icon="close" @click="emit('close')" unelevated class="absolute-right"/>
                    </div>
                </div>
                <div class="row q-col-gutter-md">
                    <div class="col-6">
                        <q-input 
                            v-model="form.first_name"
                            filled
                            label="First Name"
                            :error="form.errors.first_name ? true : false"
                            :error-message="form.errors.first_name"
                        />
                    </div>
                    <div class="col-6">
                        <q-input 
                            v-model="form.last_name"
                            filled
                            label="Last Name"
                            :error="form.errors.last_name ? true : false"
                            :error-message="form.errors.last_name"
                        />
                    </div>
                    <div class="col-12">
                        <q-input 
                            v-model="form.email"
                            filled
                            label="Email"
                            :error="form.errors.email ? true : false"
                            :error-message="form.errors.email"
                        />
                    </div>
                    <div class="col-6">
                        <q-input 
                            v-model="form.phone_number"
                            filled
                            label="Phone Number"
                            :error="form.errors.phone_number ? true : false"
                            :error-message="form.errors.phone_number"
                        />
                    </div>
                    <div class="col-6">
                        <q-input 
                            v-model="form.address"
                            filled
                            label="Address"
                            :error="form.errors.address ? true : false"
                            :error-message="form.errors.address"
                        />
                    </div>
                </div>
            </q-card-section>
            <q-card-actions class="justify-end">
                <q-btn @click="emit('close')">Cancel</q-btn>
                <q-btn
                    :loading="form.processing"
                    :disable="form.processing"
                    label="Update"
                    @click="updateeUser"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

