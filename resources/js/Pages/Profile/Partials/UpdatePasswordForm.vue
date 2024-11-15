<script setup>

import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useQuasar } from 'quasar';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);
const $q = useQuasar()

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            $q.notify('Password Updated')
        },
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <q-form @submit="updatePassword">
        <q-card class="q-mt-sm" bordered flat>
            <q-card-section>
                <div class="text-h6">Update Password</div>
                <div class="q-mb-lg">Ensure your account is using a long, random password to stay secure.</div>
                <q-input
                    outlined 
                    rounded
                    v-model="form.current_password"
                    label="Current Password"
                    lazy-rules
                    type="password"
                    :error="form.errors.current_password ? true : false"
                    :error-message="form.errors.current_password"
                    :rules="[ val => val && val.length > 0 || 'Please type something']"
                />
                <q-input
                    outlined 
                    rounded
                    v-model="form.password"
                    label="New Password"
                    lazy-rules
                    type="password"
                    :error="form.password ? true : false"
                    :error-message="form.errors.password"
                    :rules="[ val => val && val.length > 0 || 'Please type something']"
                />
                <q-input
                    outlined 
                    rounded
                    v-model="form.password_confirmation"
                    label="Confirm New Password"
                    lazy-rules
                    type="password"
                    :error="form.errors.password_confirmation ? true : false"
                    :error-message="form.errors.password_confirmation"
                    :rules="[ val => val && val.length > 0 || 'Please type something']"
                />
                <q-btn 
                    label="Save" 
                    :processing="form.processing" 
                    type="submit" 
                    :disabled="form.processing" 
                    unelevated 
                    no-caps 
                    rounded
                    color="primary"
                />
            </q-card-section>
        </q-card>
    </q-form>
</template>
