<script setup>

import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useQuasar } from 'quasar'
import { useDrawerStore } from '@/Stores/DrawerStore'
import NewAddressDialog from '@/Components/Customer/NewAddressDialog.vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    google_maps_api_key: String
});

const drawerStore = useDrawerStore()
const $q = useQuasar()
const user = usePage().props.auth.user;
const dialog = ref(false)
const form = useForm({
    first_name: user.first_name,
    last_name: user.last_name,
    email: user.email,
});

const profilePicForm = useForm({
    profile_pic: null,
    hi: 'hi'
})

const submitProfilePicForm = () => {
    profilePicForm.post(route('profile.update_profile_pic'), {
        onSuccess: () => {
            profilePicForm.profile_pic = null
            $q.notify('Profile Picture Updated')
        }
    })
}

const filePicker = ref(null);
const imgPreview = ref('')
const triggerFilePicker = () => {
    filePicker.value.pickFiles();
}

const onFileChange = (file) => {
    imgPreview.value = URL.createObjectURL(file)
}

</script>

<template>
    <q-form class="q-mb-sm" >
        <q-card bordered flat>
            <q-card-section>
                <div class="flex justify-center q-mb-md" style="position: relative" v-if="$page.props.auth.user.role == 'cashier' || $page.props.auth.user.role == 'admin'">
                    <q-btn 
                        icon="menu" 
                        class="lt-md absolute-left" 
                        flat 
                        @click="drawerStore.drawer = true" 
                    />
                    <div class="text-h6">Edit Profile</div>
                </div>
                <div class="text-h6">Profile Picture</div>
                <div class="q-mb-lg">Update your account's profile information and email address.</div>
                <div class="row q-col-gutter-md ">
                    <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2 ">
                        <q-avatar size="100px" v-if="$page.props.auth.user.profile_pic || imgPreview">
                            <q-img class="fit" fit="cover" :src="imgPreview ? imgPreview : `/storage/${$page.props.auth.user.profile_pic}`" />
                        </q-avatar>
                        <q-avatar color="primary" class="text-white" v-else size="100px">
                            {{ $page.props.auth.user.first_name[0] }}
                        </q-avatar>
                    </div>
                    <div :class="['col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-10 flex', $q.screen.lt.md ? 'justify-center' : 'items-center']">
                        <q-file 
                            v-model="profilePicForm.profile_pic"
                            ref="filePicker"
                            class="q-mt-md"
                            style="display: none" 
                            @update:model-value="onFileChange"
                        />
                    </div>
                    <div class="col-12">
                        <q-btn 
                            no-caps
                            class="q-mr-md"
                            type="submit" 
                            @click="submitProfilePicForm"
                            v-if="profilePicForm.profile_pic"
                            :disable="profilePicForm.processing"
                            :loading="profilePicForm.processing"
                            color="primary"
                            label="Save"
                            rounded 
                            unelevated
                        />
                        <q-btn 
                            no-caps 
                            unelevated 
                            color="primary" 
                            class="q-mr-md" 
                            v-else 
                            @click="triggerFilePicker" 
                            rounded 
                            label="Change Avatar" 
                        />
                        <!-- <q-btn 
                            no-caps 
                            unelevated 
                            color="primary" 
                            class="q-mr-md" 
                            @click="dialog = true" 
                            rounded 
                            label="Update Address" 
                        /> -->
                    </div>
                </div>
            </q-card-section>
        </q-card>
    </q-form>
    <q-card bordered flat class="q-my-sm">
        <NewAddressDialog 
            :dialog="dialog" 
            @close="dialog = false"
            :google_maps_api_key="google_maps_api_key" 
        />
        <q-card-section>
            <div class="text-h6">Address Information</div>
            <div class="q-mb-lg">Update your account's address and phone number.</div>
            <div class="row q-col-gutter-md">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <div class="text-caption text-weight-light">Location</div>
                    <div>{{ $page.props.auth.user.address }}</div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <div class="text-caption text-weight-light">Street, House No, Purok</div>
                    <div>{{ $page.props.auth.user.street }}</div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <div class="text-caption text-weight-light">Phone Number</div>
                    <div>{{ $page.props.auth.user.phone_number }}</div>
                </div>
            </div>

            <q-btn  rounded 
                unelevated
                label="Edit Address" 
                type="submit" 
                @click="dialog = true"
                class="q-mt-sm" 
                no-caps 
                color="primary" 
            />
        </q-card-section>
    </q-card>
    <q-form @submit="form.patch(route('profile.update'))">
        <q-card bordered flat>
            <q-card-section>
                <div class="text-h6">Profile Information</div>
                <div class="q-mb-lg">Update your account's profile information and email address.</div>
                <div class="row q-col-gutter-md">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <q-input
                            outlined 
                            rounded
                            v-model="form.first_name"
                            label="First Name"
                            lazy-rules
                            :error="form.errors.first_name ? true : false"
                            :error-message="form.errors.first_name"
                            :rules="[ val => val && val.length > 0 || 'Please type something']"
                        />
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                        <q-input
                            outlined 
                            rounded
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
                    outlined 
                    rounded
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
                        <q-btn 
                            outline 
                            no-caps 
                            rounded 
                            unelevated 
                            color="primary" 
                            label="Resend Verification Email" 
                        />
                    </Link>
                </q-banner>
                <q-btn  rounded 
                    unelevated
                    label="Save" 
                    type="submit" 
                    :loading="form.processing" 
                    :disable="form.processing"
                    class="q-mt-sm" 
                    no-caps 
                    color="primary" 
                />
            </q-card-section>
        </q-card>
    </q-form>
    

</template>
