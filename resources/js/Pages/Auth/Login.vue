<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

defineOptions({
    layout: AuthLayout
})

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
})

const form = useForm({
    email: '',
    password: '',
    remember: false,
})

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    })
}
</script>

<template>
    <Head title="Log in" />
    <q-form @submit="submit">
        <q-card class="q-pa-xl" flat>
            <q-card-section>
                <div class="text-h6 q-mb-lg">Login to Taytayan Camp Hub</div>
                <q-input
                    filled
                    v-model="form.email"
                    label="Email Address"
                    lazy-rules
                    :error="form.errors.email ? true : false"
                    :error-message="form.errors.email"
                    :rules="[ val => val && val.length > 0 || 'Please type something']"
                />
                <q-input 
                    v-model="form.password" 
                    filled 
                    :error="form.errors.password ? true : false"
                    :error-message="form.errors.password"
                    :type="!showPassword ? 'password' : 'text'" 
                    label="Password"
                >
                    <template v-slot:append>
                        <q-icon
                            :name="showPassword ? 'visibility_off' : 'visibility'"
                            class="cursor-pointer"
                            @click="showPassword = !showPassword"
                        />
                    </template>
                </q-input>
                
                <div class="row" justify="between">
                    <q-checkbox v-model="form.remember" label="Remember me" />
                    <q-space></q-space>
                    <p class="text-right q-mt-md">
                        <Link 
                            :href="route('password.request')" 
                            class="text-primary" 
                            style="text-decoration: none"
                        >
                            Forgot your password?
                        </Link>
                    </p>
                </div>

                <q-btn label="Log in" :loading="form.processing" :disable="form.processing" no-caps type="submit" class="full-width" color="primary"/>
            </q-card-section>

            <q-card-section>
                <p class="text-center">
                    Don't have an account? 
                    <Link 
                        :href="route('register')"  
                        class="text-primary" 
                        style="text-decoration: none"
                    >
                        Sign up here for free
                    </Link>
                </p>
            </q-card-section>

        </q-card>
    </q-form>
</template>
