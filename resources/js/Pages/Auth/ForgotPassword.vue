<script setup>

import AuthLayout from '@/Layouts/AuthLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3';

defineOptions({
    layout: AuthLayout
})

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Forgot Password" />
    <q-form @submit="submit">
        <q-card flat>
            <Link :href="route('login')">
                <q-btn no-caps flat color="primary" rounded class="text-black q-mt-md">
                    <q-icon name="arrow_back" class="q-mr-sm"></q-icon>
                    Go back to login
                </q-btn>
            </Link>
            <q-card-section>
                <div class="text-h6 q-mb-lg">Forgot Password</div>
                <p>
                    Forgot your password? No problem. Just let us know your email address and we will email you a password reset
                    link that will allow you to choose a new one.
                </p>
                <q-banner inline-actions class="text-white bg-positive q-mb-md" v-if="status">
                    {{ status }}
                </q-banner>
                <q-input
                    rounded 
                    outlined
                    v-model="form.email"
                    label="Email"
                    lazy-rules
                    :error="form.errors.email ? true : false"
                    :error-message="form.errors.email"
                    :rules="[ val => val && val.length > 0 || 'Please type something']"
                />
                <q-btn 
                    label="Reset" 
                    :loading="form.processing" 
                    :disable="form.processing" 
                    no-caps 
                    type="submit" 
                    class="full-width" 
                    rounded
                    unelevated
                    color="primary"
                />
            </q-card-section>

        </q-card>
    </q-form>
</template>
