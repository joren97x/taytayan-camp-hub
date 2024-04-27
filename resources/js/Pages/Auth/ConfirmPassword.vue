<script setup>

import AuthLayout from '@/Layouts/AuthLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'

defineOptions({
    layout: AuthLayout
})

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <q-banner>
            This is a secure area of the application. Please confirm your password before continuing.
        </q-banner>

        <q-form @submit="submit">
            <q-card>
                <q-card-section>
                    <div class="text-h6 q-mb-lg">Forgot Password</div>
                    <q-banner inline-actions class="text-white bg-positive q-mb-md" v-if="status">
                        {{ status }}
                    </q-banner>
                    <q-input
                        filled
                        v-model="form.password"
                        label="Password"
                        lazy-rules
                        :error="form.errors.password ? true : false"
                        :error-message="form.errors.password"
                        :rules="[ val => val && val.length > 0 || 'Please type something']"
                    />
                    <q-btn label="Reset" :loading="form.processing" :disable="form.processing" no-caps type="submit" class="full-width" color="primary"/>
                </q-card-section>
            </q-card>
        </q-form>
    </GuestLayout>
</template>
