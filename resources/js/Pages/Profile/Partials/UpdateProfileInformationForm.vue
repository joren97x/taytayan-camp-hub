<script setup>

import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    first_name: user.first_name,
    last_name: user.last_name,
    email: user.email,
});

const profilePicForm = useForm({
    profile_pic: null,
    hi: 'hi'
})

const filePicker = ref(null);
const triggerFilePicker = () => {
    filePicker.value.pickFiles();
}

</script>

<template>
    <q-form class="q-mb-lg" >
        <q-card>
            <q-card-section>
                <div class="text-h6 q-mb-sm">Profile Picture</div>
                <div class="q-mb-lg">Update your account's profile information and email address.</div>
                {{ profilePicForm }}
                {{ profilePicForm.profile_pic }}
                <div class="row q-col-gutter-md ">
                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2 justify-center flex">
                        <q-avatar size="100px">
                            <q-img src="https://p16-tm-sg.tiktokmusic.me/img/tos-alisg-v-2102/04dc6545750e489bbd4228b640874126~c5_750x750.image"></q-img>
                        </q-avatar>
                    </div>
                    <div :class="['col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-10 flex', $q.screen.lt.md ? 'justify-center' : 'items-center']">
                        <div>
                            <q-btn 
                                no-caps
                                class="q-mr-md"
                                type="submit" 
                                @click="profilePicForm.patch(route('profile.update_profile_pic'))"
                                v-if="profilePicForm.profile_pic"
                                :disable="profilePicForm.processing"
                                :loading="profilePicForm.processing"
                            >
                                Save
                            </q-btn>
                            <q-btn no-caps class="q-mr-md" v-else @click="triggerFilePicker">Edit</q-btn>
    <!-- Delete Button -->
                            <q-btn no-caps>Delete</q-btn>

                            <!-- Hidden File Picker -->
                            <q-file 
                                v-model="profilePicForm.profile_pic"
                                ref="filePicker"
                                class="q-mt-md"
                                style="display: none" 
                            />
                        </div>
                    </div>
                </div>
            </q-card-section>
        </q-card>
    </q-form>
    <q-form @submit="form.patch(route('profile.update'))">
        <q-card>
            <q-card-section>
                <div class="text-h6 q-mb-sm">Profile Information</div>
                <div class="q-mb-lg">Update your account's profile information and email address.</div>
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
                    filled
                    v-model="form.email"
                    label="Email Address"
                    lazy-rules
                    :hint="!mustVerifyEmail || !user.email_verified_at === null ? 'Verified' : ''"
                    :error="form.errors.email ? true : false"
                    :error-message="form.errors.email"
                    :rules="[ val => val && val.length > 0 || 'Please type something']"
                />
                <q-banner :class="$q.dark.isActive ? 'bg-grey-8' : 'bg-grey-4'" v-if="mustVerifyEmail && user.email_verified_at === null">
                    Your email has not been verified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                    >
                        <q-btn outline no-caps color="blue" label="Resend Verification Email" />
                    </Link>
                </q-banner>
                <q-btn label="Save" type="submit" :loading="form.processing" :disable="form.processing" class="q-mt-sm" unelevated no-caps color="blue" />
            </q-card-section>
        </q-card>
    </q-form>
</template>
