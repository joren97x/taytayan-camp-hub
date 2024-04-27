<script setup>

import AuthLayout from '@/Layouts/AuthLayout.vue'
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineOptions({
    layout: AuthLayout
})

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');
</script>

<template>
    <Head title="Email Verification" />
        <q-card class="q-pa-xl q-mt-xl">
            <q-card-section>
                <p class="text-h6 q-mb-lg">Email Verification</p>
                <p>
                    Thanks for signing up! Before getting started, could you verify your email address by clicking on the link
                    we just emailed to you? If you didn't receive the email, we will gladly send you another.
                </p>
                <q-banner inline-actions class="text-white bg-positive q-mb-md" v-if="verificationLinkSent">
                    A new verification link has been sent to the email address you provided during registration.
                </q-banner>
            </q-card-section>
            
            <q-card-actions align="between">
                <q-form class="q-pa-xl" @submit="submit">
                    <q-btn no-caps color="primary" type="submit" :loading="form.processing" :disabled="form.processing">Resend Verification Email</q-btn>
                </q-form>
                <Link
                    :href="route('logout')"
                    method="post"
                >
                    <q-btn no-caps outline color="negative">Logout</q-btn>
                </Link>
            </q-card-actions>
        </q-card>
</template>
