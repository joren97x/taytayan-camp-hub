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
    <!-- <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Update Password</h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Ensure your account is using a long, random password to stay secure.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>
                <InputLabel for="current_password" value="Current Password" />

                <TextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="current-password"
                />

                <InputError :message="form.errors.current_password" class="mt-2" />
            </div>

            <div>
                <InputLabel for="password" value="New Password" />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div>
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                />

                <InputError :message="form.errors.password_confirmation" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                </Transition>
            </div>
        </form>
    </section> -->
    <q-form @submit="updatePassword">
        <q-card class="q-mt-md" bordered flat>
            <q-card-section>
                <div class="text-h6 q-mb-sm">Update Password</div>
                <div class="q-mb-lg">Ensure your account is using a long, random password to stay secure.</div>
                <q-input
                    outlined 
                    rounded
                    v-model="form.current_password"
                    label="Current Password"
                    lazy-rules
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
                    :error="form.password ? true : false"
                    :error-message="form.password"
                    :rules="[ val => val && val.length > 0 || 'Please type something']"
                />
                <q-input
                    outlined 
                    rounded
                    v-model="form.password_confirmation"
                    label="Confirm New Password"
                    lazy-rules
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
