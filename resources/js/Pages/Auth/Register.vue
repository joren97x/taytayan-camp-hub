<script setup>

import AuthLayout from '@/Layouts/AuthLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

defineOptions({
    layout: AuthLayout
})

const showPassword = ref(false)
const showPassword2 = ref(false)

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />
    <q-form @submit="submit">
        <q-card flat>
            <q-card-section>
                <p class="text-h6 q-mb-lg">Create your account</p>
                <q-input
                    filled
                    v-model="form.email"
                    label="Email Address"
                    lazy-rules
                    :error="form.errors.email ? true : false"
                    :error-message="form.errors.email"
                    :rules="[ val => val && val.length > 0 || 'Please type something']"
                />

                <div class="row q-col-gutter-md">
                    <div class="col-6">
                        <q-input
                            filled
                            v-model="form.first_name"
                            label="First Name"
                            lazy-rules
                            :error="form.errors.first_name ? true : false"
                            :error-message="form.errors.first_name"
                            :rules="[ val => val && val.length > 0 || 'Please type something']"
                        />
                    </div>
                    <div class="col-6">
                        <q-input
                            filled
                            v-model="form.last_name"
                            label="Last Name"
                            lazy-rules
                            :error="form.errors.last_name ? true : false"
                            :error-message="form.errors.last_name"
                            :rules="[ val => val && val.length > 0 || 'Please type something']"
                        />
                    </div>
                </div>

                <q-input 
                    v-model="form.password" 
                    filled 
                    :type="!showPassword ? 'password' : 'text'" 
                    label="Password"
                    :error="form.errors.password ? true : false"
                    :error-message="form.errors.password"
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
                    v-model="form.password_confirmation" 
                    filled 
                    :error="form.errors.password_confirmation ? true : false"
                    :error-message="form.errors.password_confirmation"
                    :type="!showPassword2 ? 'password' : 'text'" 
                    label="Confirm Password"
                >
                    <template v-slot:append>
                        <q-icon
                            :name="showPassword2 ? 'visibility_off' : 'visibility'"
                            class="cursor-pointer"
                            @click="showPassword2 = !showPassword2"
                        />
                    </template>
                </q-input>

                <q-btn label="Register" :loading="form.processing" :disabled="form.processing" no-caps type="submit" class="full-width q-mt-lg" color="primary"/>
            </q-card-section>

            <q-card-section>
                <p class="text-center">
                    Already have an account? 
                    <Link 
                        :href="route('login')"  
                        class="text-primary" 
                        style="text-decoration: none"
                    >
                        Log in here
                    </Link>
                </p>
            </q-card-section>

        </q-card>
    </q-form>
</template>
