<script setup>

import { useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { computed, defineEmits, onMounted, ref } from 'vue'

const $q = useQuasar()
const emit = defineEmits(['close'])
const props = defineProps({
    user: Object,
    dialog: Boolean
})

const user2 = computed(() => props.user)
const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    phone_number: '',
    address: '',
    role: '',
})

// onMounted(() => {
//     setForm()
// })

const setForm = () => {
    form.first_name = props.user.first_name
    form.last_name = props.user.last_name
    form.email = props.user.email
    form.phone_number = props.user.phone_number
    form.address = props.user.address
    form.role = props.user.role
}

const submit = () => {
    form.put(route('admin.users.update', props.user.id), {
        onSuccess: () => {
            emit('close')
            $q.notify('User Succesfully Updated')
        }
    })
}

// const profilePicRef = ref(null)
// const imgPreview = ref('')

// const triggerFilePicker = () => {
//     profilePicRef.value.pickFiles();
// };

// const onFileChange = (file) => {
//     imgPreview.value = URL.createObjectURL(file)
// }

</script>

<template>
    <q-dialog
        v-model="props.dialog"
        transition-show="slide-up"
        transition-hide="slide-down"
        :maximized="$q.screen.lt.md"
        @show="setForm"
    >
        <q-card :style="$q.screen.gt.sm ? 'max-width: 50vw; width: 100%;' : ''">
            <!-- <q-card-section>
                {{ form }}
                <div class="row justify-between q-my-md">
                    <div class="text-h6 text-start col-12" style="position: relative">
                        Edit User
                        <q-btn icon="close" @click="emit('close')" unelevated class="absolute-right"/>
                    </div>
                </div> -->
            <q-card-section class="row items-center q-pb-none">
                <div class="text-h6">Create New User</div>
                <q-space />
                <q-btn icon="close" flat round dense v-close-popup @click="emit('close')" />
            </q-card-section>
            <q-card-section>
                <!-- <q-item class="q-my-md" :style="form.errors.profile_pic ? 'border: 1px solid red' : ''">
                    <q-item-section avatar>
                        {{ form.profile_pic }}
                        <q-img 
                            v-if="form.profile_pic"
                            :src="`/storage/${form.profile_pic}`" 
                            style="width: 100px; height: 100px;"
                        />
                        <div style="height: 100px; width: 100px" class="bg-grey" v-else></div>
                    </q-item-section>
                    <q-item-section>
                        <q-file 
                            v-model="form.profile_pic"
                            :error="form.errors.profile_pic ? true : false"
                            :error-message="form.errors.profile_pic"
                            style="display: none;"
                            ref="profilePicRef"
                            @update:model-value="onFileChange"
                        />
                        <q-item-label>User's Profile Picture</q-item-label>
                        <q-item-label caption>File requirement: JPG, PNG</q-item-label>
                        <q-item-label class="text-red" caption>{{ form.errors.profile_pic ? form.errors.profile_pic : '' }}</q-item-label>
                        <q-item-label>
                            <q-btn 
                                no-caps color="primary" 
                                v-if="form.profile_pic" 
                                @click="triggerFilePicker"
                                rounded 
                                unelevated
                                label="Change"
                            />
                            <q-btn no-caps color="primary" v-else @click="triggerFilePicker" rounded unelevated label="Add Photo" />
                        </q-item-label>
                    </q-item-section>
                </q-item> -->
                <div class="row q-col-gutter-md">
                    <div class="col-12">
                        <q-input
                            outlined 
                            rounded
                            v-model="form.email"
                            label="Email Address"
                            lazy-rules
                            :error="form.errors.email ? true : false"
                            :error-message="form.errors.email"
                            :rules="[ val => val && val.length > 0 || 'Please type something']"
                        />
                    </div>
                    <div class="col-6">
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
                    <div class="col-6">
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
                    <div class="col-6">
                        <q-input
                            outlined 
                            rounded
                            v-model="form.address"
                            label="address Address"
                            lazy-rules
                            :error="form.errors.address ? true : false"
                            :error-message="form.errors.address"
                            :rules="[ val => val && val.length > 0 || 'Please type something']"
                        />
                    </div>
                    <div class="col-6">
                        <q-input
                            outlined 
                            rounded
                            v-model="form.phone_number"
                            label="Phone Number"
                            lazy-rules
                            :error="form.errors.phone_number ? true : false"
                            :error-message="form.errors.phone_number"
                            :rules="[ val => val && val.length > 0 || 'Please type something']"
                        />
                    </div>
                    
                </div>
                <q-select 
                    v-model="form.role" 
                    outlined 
                    rounded 
                    label="Role"
                    :options="['admin', 'cashier', 'customer', 'driver']"
                    :error="form.errors.role ? true : false"
                    :error-message="form.errors.role"
                ></q-select>
            </q-card-section>
            <q-card-actions>
                <q-space/>
                <q-btn 
                    color="primary" 
                    no-caps
                    class="full-width"
                    type="submit"
                    @click="submit"
                    rounded 
                    unelevated
                    :loading="form.processing"
                    :disable="form.processing"
                >
                    Update
                </q-btn>
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

