<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import { useQuasar } from 'quasar'
import EditUserDialog from './Partials/EditUserDialog.vue'
import DeleteUserDialog from './Partials/DeleteUserDialog.vue'

defineOptions({
    layout: AdminLayout
})

defineProps({
    users: Object,
    role: String,
    user_roles: Object
})

const filter = ref('')
const $q = useQuasar()
const showPassword = ref(false)
const showPassword2 = ref(false)
const newUserDialog = ref(false)
const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'customer'
})

const submit = () => {
    form.post(route('admin.user_management.store'), {
        onSuccess: () => {
            form.reset()
            newUserDialog.value = false
            $q.notify('User Successfully Added')
        }
    })
}

const deleteUserDialog = ref(false)
const editUserDialog = ref(false)
const selectedUser = ref(null)

const showDeleteUserDialog = (user) => {
    console.log('anyeong shawtiee')
    selectedUser.value = user
    deleteUserDialog.value = true
}

const showEditUserDialog = (user) => {
    selectedUser.value = user
    editUserDialog.value = true
}

const columns = [
    // { name: 'photo', label: 'Photo', align: 'center', field: 'photo', sortable: true },
    { name: 'user', align: 'center', label: 'User', sortable: true },
    { name: 'email', align: 'center', label: 'Email', field: 'email', sortable: true },
    { name: 'contact', align: 'center', label: 'Contact', field: 'contact', sortable: true },
    { name: 'role', align: 'center', label: 'Role', field: 'role', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

</script>

<template>
    <Head title="User Management" />
    <div class="q-pa-md">
        <q-card bordered flat>
            <q-table
                class="my-sticky-header-column-table"
                flat
                :rows="users"
                :columns="columns"
                row-key="name"
                :filter="filter"
            >
                <!-- <template v-slot:top>
                    <p class="text-h6 q-pt-md">Modifier Groups</p>
                    <q-space />
                        <q-input filled dense label="Search..." debounce="300" color="primary" v-model="filter">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                    <Link :href="route('admin.modifier_groups.create')">
                        <q-btn class="q-ml-sm" color="primary" no-caps label="Create Modifier Group" />
                    </Link>
                </template> -->
                <template v-slot:body-cell-user="props">
                    <q-td :props="props">
                        <q-item class="q-pa-none">
                            <q-item-section avatar>
                                {{ props.row.profile_pic }}
                                <q-avatar v-if="props.row.profile_pic">
                                    <q-img :src="`/storage/${props.row.profile_pic}`" ></q-img>
                                </q-avatar>
                                <q-avatar v-else>
                                    <q-img fit="cover" src="https://static.vecteezy.com/system/resources/thumbnails/020/911/737/small_2x/user-profile-icon-profile-avatar-user-icon-male-icon-face-icon-profile-icon-free-png.png" ></q-img>
                                </q-avatar>
                            </q-item-section>
                            <q-item-section class="text-left">
                                <q-item-label>{{ props.row.first_name + ' ' + props.row.last_name }}</q-item-label>
                            </q-item-section>
                        </q-item>
                    </q-td>
                </template>
                <template v-slot:body-cell-contact="props">
                    <q-td :props="props">
                        {{ props.row.phone_number }}
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <q-btn no-caps unelevated @click="showEditUserDialog(props.row)">Edit</q-btn>
                        <q-btn no-caps unelevated @click="showDeleteUserDialog(props.row)">Delete</q-btn>
                        <!--  -->
                    </q-td>
                </template>
                <template v-slot:top>
                    <p class="text-h6 q-pt-md text-capitalize">User Management</p>
                    <q-space />
                    <!-- <q-input filled dense label="Search..." debounce="300" color="primary" v-model="filter">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input> -->
                    <q-input filled dense label="Search by email" class="q-mx-md" debounce="300" color="primary" v-model="filter">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                    <q-btn class="q-ml-md" no-caps color="primary" @click="newUserDialog = true" label="Create User" />

                </template>
            </q-table>
        </q-card>
    </div>
    <!-- {{ selectedUser }} -->
    <DeleteUserDialog 
        @close="deleteUserDialog = false" 
        :user="selectedUser" 
        v-if="selectedUser" 
        :dialog="deleteUserDialog" 
    />
    <EditUserDialog 
        @close="editUserDialog = false" 
        :user="selectedUser" 
        v-if="selectedUser" 
        :dialog="editUserDialog" 
    />
    <q-dialog v-model="newUserDialog" persistent>
        <q-card>
            <q-card-section class="row items-center q-pb-none">
                    <div class="text-h6">Create New User</div>
                    <q-space />
                    <q-btn icon="close" flat round dense v-close-popup />
            </q-card-section>
            <q-form @submit.prevent="submit"> 
            <q-card-section>
                {{ form }}
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

                <q-select 
                    v-model="form.role" 
                    filled 
                    label="Role"
                    :options="user_roles"
                    :error="form.errors.role ? true : false"
                    :error-message="form.errors.role"
                >

                </q-select>

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
            </q-card-section>
            <q-card-actions>
                <q-space/>
                <q-btn v-close-popup no-caps>Cancel</q-btn>
                <q-btn 
                    color="primary" 
                    no-caps
                    type="submit"
                    :loading="form.processing"
                    :disable="form.processing"
                >
                    Add New User
                </q-btn>
            </q-card-actions>
            </q-form>
        </q-card>
    </q-dialog>
</template>