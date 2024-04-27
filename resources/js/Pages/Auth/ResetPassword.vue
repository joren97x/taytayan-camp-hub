<script setup>

import AuthLayout from '@/Layouts/AuthLayout.vue'
import { Head, useForm } from '@inertiajs/vue3';

defineOptions({
    layout: AuthLayout
})

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Reset Password" />
    <q-form @submit="submit">
        <q-card class="q-pa-xl" flat>
            <Link :href="route('login')">
                <q-btn no-caps flat color="primary">
                    <q-icon name="arrow_back" class="q-mr-sm"></q-icon>
                    Go back to login
                </q-btn>
            </Link>
            <q-card-section>
                <div class="text-h6 q-mb-lg">Password Reset</div>

                <q-input
                    filled
                    label="Email"
                    v-model="form.email"
                    :error="form.errors.email ? true : false"
                    :error-message="form.errors.email"
                >

                </q-input>
               
                <q-input
                    filled
                    v-model="form.password"
                    label="New Password"
                    :type="!showPassword ? 'password' : 'text'" 
                    lazy-rules
                    :error="form.errors.password ? true : false"
                    :error-message="form.errors.password"
                    :rules="[ val => val && val.length > 0 || 'Please type something']"
                >
                    <template v-slot:append>
                        <q-icon
                            :name="showPassword ? 'visibility_off' : 'visibility'"
                            class="cursor-pointer"
                            @click="showPassword = !showPassword"
                        />
                    </template>
                </q-input>
                <q-input
                    filled
                    v-model="form.password_confirmation"
                    label="Confirm Password"
                    lazy-rules
                    :type="!showPassword2 ? 'password' : 'text'" 
                    :rules="[ val => val && val.length > 0 || 'Please type something']"
                >
                    <template v-slot:append>
                        <q-icon
                            :name="showPassword2 ? 'visibility_off' : 'visibility'"
                            class="cursor-pointer"
                            @click="showPassword2 = !showPassword2"
                        />
                    </template>
                </q-input>
                <q-btn label="Reset Password" :loading="form.processing" :disabled="form.processing" no-caps type="submit" class="full-width" color="primary"/>
            </q-card-section>

        </q-card>
    </q-form>
</template>
