<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import { useQuasar } from 'quasar'
import EditUserDialog from './Partials/EditUserDialog.vue'
import { useDrawerStore } from '@/Stores/DrawerStore'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    users: Object,
    role: String,
    user_roles: Object
})

props.user_roles.unshift('all')

const drawerStore = useDrawerStore()
const filter = ref('all')
const searchTerm = ref('')
const showSearch = ref(false)

const filteredUsers = computed(() => {
  let filtered = props.users;
  // Apply status filter
  if (filter.value !== 'all') {
    filtered = filtered.filter(user => user.role === filter.value);
  }

  // Apply search term filter
  if (searchTerm.value) {
    filtered = filtered.filter(user => {
        // console.log(order)
      const fullName = `${user.first_name} ${user.last_name}`.toLowerCase();
      const email = user.email.toLowerCase();
      const search = searchTerm.value.toLowerCase();

      return (
        fullName.includes(search) ||
        email.includes(search)
      );
    });
  }

  return filtered;
});

const $q = useQuasar()
const showPassword = ref(false)
const showPassword2 = ref(false)
const newUserDialog = ref(false)
const form = useForm({
    profile_pic: null,
    first_name: '',
    last_name: '',
    phone_number: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'customer'
})

const submit = () => {
    form.post(route('admin.users.store'), {
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
const deleteUserForm = useForm({
    user: null
})

const showDeleteUserDialog = (user) => {
    console.log('anyeong shawtiee')
    deleteUserForm.user = user
    deleteUserDialog.value = true
}

const deleteUser = () => {
    deleteUserForm.delete(route('admin.users.destroy', deleteUserForm.user.id), {
        onSuccess: () => {
            deleteUserDialog.value = false
            $q.notify('User Deleted')
        }
    })
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
    { name: 'email_verified_at', align: 'center', label: 'Verified', field: 'email_verified_at', sortable: true },
    { name: 'role', align: 'center', label: 'Role', field: 'role', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

const profilePicRef = ref(null)
const imgPreview = ref('')

const triggerFilePicker = () => {
    profilePicRef.value.pickFiles();
};

const onFileChange = (file) => {
    imgPreview.value = URL.createObjectURL(file)
}

const initialPagination = {
    sortBy: 'desc',
    descending: false,
    page: 1,
    rowsPerPage: 10
    // rowsNumber: xx if getting data from a server
}

</script>

<template>
    <Head title="User Management" />
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
        <q-card bordered flat>
            <q-table
                flat
                :rows="filteredUsers"
                :columns="columns"
                row-key="name"
                :pagination="initialPagination"
                :grid="$q.screen.lt.md"
            >
                <template v-slot:top>
                    <q-btn icon="menu" flat dense @click="drawerStore.drawer = true" class="lt-md q-mr-sm"/>
                    <div class="text-h6">Users</div>
                    <q-space />
                    <q-btn icon="search" class="q-mr-xs" round dense flat @click="showSearch = !showSearch"/>
                    <q-select 
                        :options="user_roles" 
                        v-model="filter"
                        outlined 
                        rounded
                        dense
                    />
                    <q-btn class="q-ml-sm" rounded unelevated no-caps color="primary" @click="newUserDialog = true" label="Create User" />
                    <div class="full-width q-mt-sm" v-if="showSearch">
                        <q-input
                            v-model="searchTerm"
                            rounded
                            outlined
                            dense
                            label="Search using name"
                            debounce="300"
                            class="full-width"
                            color="primary"
                        >
                            <template v-slot:append>
                                <q-icon name="search" />
                            </template>
                        </q-input>
                    </div>
                </template>
                <template v-slot:body-cell-user="props">
                    <q-td :props="props">
                        <q-item class="q-pa-none">
                            <q-item-section avatar>
                                <q-avatar color="primary" text-color="white">
                                    <q-img class="fit" fit="cover" :src="`/storage/${props.row.profile_pic}`" v-if="props.row.profile_pic"/>
                                    <div v-else>
                                        {{ props.row.first_name[0] }}
                                    </div>
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
                        {{ props.row.phone_number ? props.row.phone_number : 'N\\A' }}
                    </q-td>
                </template>
                <template v-slot:body-cell-email_verified_at="props">
                    <q-td :props="props">
                        <q-chip
                            :class="props.row.email_verified_at ? 'bg-green-3' : 'bg-yellow-3'"
                        >
                            {{ props.row.email_verified_at ? 'Verified' : 'Not Verified' }}
                        </q-chip>
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <q-btn no-caps unelevated @click="showEditUserDialog(props.row)">Edit</q-btn>
                        <q-btn no-caps unelevated @click="showDeleteUserDialog(props.row)" color="negative" flat>Delete</q-btn>
                        <!--  -->
                    </q-td>
                </template>
                <template v-slot:item="props">
                    <div class="col-12 q-mb-sm">
                        <q-card class="q-mx-sm" bordered flat>
                            <q-card-section>
                                <q-item class="q-pa-none">
                                    <q-item-section avatar>
                                        <q-avatar color="primary" text-color="white">
                                            <q-img :src="`/storage/${props.row.profile_pic}`" fit="contain" height="60px" width="60px" class="rounded-borders" v-if="props.row.profile_pic" />
                                            <div v-else>
                                                {{ props.row.first_name[0] }}
                                            </div>
                                        </q-avatar>
                                    </q-item-section>
                                    <q-item-section class="items-start">
                                        <q-item-label>{{ props.row.first_name + ' ' + props.row.last_name }}</q-item-label>
                                        <q-item-label caption class="ellipsis-2-lines q-mr-xl">{{ props.row.email }}</q-item-label>
                                    </q-item-section>
                                    <q-item-section side>
                                        <div class="flex">
                                            <q-chip>{{ props.row.role }}</q-chip>
                                            <q-btn icon="more_horiz" class="text-black" flat color="white" round>
                                                <q-menu>
                                                    <q-list style="min-width: 100px">
                                                        <q-item clickable @click="showEditUserDialog(props.row)">
                                                            <q-item-section>Edit</q-item-section>
                                                        </q-item>
                                                        <q-item clickable @click="showDeleteUserDialog(props.row)">
                                                            <q-item-section>Delete</q-item-section>
                                                        </q-item>
                                                    </q-list>
                                                </q-menu>
                                            </q-btn>
                                        </div>
                                    </q-item-section>
                                </q-item>
                            </q-card-section>
                        </q-card>
                    </div>
                </template>
                
            </q-table>
        </q-card>
    </div>
    <!-- <DeleteUserDialog 
        @close="deleteUserDialog = false" 
        :user="selectedUser" 
        v-if="selectedUser" 
        :dialog="deleteUserDialog" 
    /> -->
    <EditUserDialog 
        @close="editUserDialog = false" 
        :user="selectedUser" 
        v-if="selectedUser" 
        :dialog="editUserDialog" 
    />
    <q-dialog 
        v-model="newUserDialog" 
        persistent 
        :maximized="$q.screen.lt.md" 
        transition-show="slide-up"
        transition-hide="slide-down"
    >
        <q-card :style="$q.screen.gt.sm ? 'max-width: 50vw; width: 100%;' : ''">
            <q-card-section class="row items-center q-pb-none">
                <div class="text-h6">Create New User</div>
                <q-space />
                <q-btn icon="close" flat round dense v-close-popup />
            </q-card-section>
            <q-form @submit.prevent="submit"> 
            <q-card-section>
                <q-item class="q-my-md" :style="form.errors.profile_pic ? 'border: 1px solid red' : ''">
                    <q-item-section avatar>
                        <q-img 
                            v-if="form.profile_pic"
                            :src="form.profile_pic == null ? '' : imgPreview" 
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
                </q-item>
                <div class="row q-col-gutter-md">
                    <div class="col-6">
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
                            v-model="form.phone_number"
                            label="Phone Number"
                            lazy-rules
                            :error="form.errors.phone_number ? true : false"
                            :error-message="form.errors.phone_number"
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
                </div>

                <q-select 
                    v-model="form.role" 
                    outlined 
                    rounded 
                    label="Role"
                    :options="user_roles"
                    :error="form.errors.role ? true : false"
                    :error-message="form.errors.role"
                >

                </q-select>

                <q-input 
                    v-model="form.password" 
                    outlined 
                    rounded 
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
                    outlined 
                    rounded 
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
                <q-btn 
                    color="primary" 
                    no-caps
                    class="full-width"
                    type="submit"
                    rounded 
                    unelevated
                    :loading="form.processing"
                    :disable="form.processing"
                >
                    Add New User
                </q-btn>
            </q-card-actions>
            </q-form>
        </q-card>
    </q-dialog>
    <q-dialog 
        v-model="deleteUserDialog" 
        persistent 
        :maximized="$q.screen.lt.md"
        transition-show="slide-up"
        transition-hide="slide-down"
        :position="$q.screen.lt.md ? 'bottom' : 'standard'"
    >
        <q-card :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
            <q-card-section class="row items-center q-pb-none">
                <q-icon name="warning" color="negative" size="32px" />
                <div class="text-h6 q-ml-md">Delete User</div>
                <q-btn round icon="close" v-close-popup flat class="absolute-top-right q-mt-sm q-mr-sm"/>

            </q-card-section>
            <q-card-section>
                Are you sure you want to delete this user? All data will be permanently removed. This action cannot be undone.
                <q-item class="bg-negative text-white q-my-md">
                    <q-item-section avatar>
                        <q-avatar color="primary" text-color="white">
                            <q-img :src="`/storage/${deleteUserForm.user.profile_pic}`" v-if="deleteUserForm.user.profile_pic" alt="Profile Picture" height="100px" width="100px"/>
                            <div v-else>
                                {{ deleteUserForm.user.first_name[0] }}
                            </div>
                        </q-avatar>
                    </q-item-section>  
                    <q-item-section>
                        <q-item-label class="text-weight-bold text-subtitle1">{{ deleteUserForm.user.first_name + ' ' +  deleteUserForm.user.last_name }}</q-item-label>
                        <q-item-label >{{ deleteUserForm.user.email }}</q-item-label>
                    </q-item-section>
                </q-item>
            </q-card-section>
        
            <q-card-actions align="right">
                <q-btn flat label="Cancel" rounded no-caps color="grey-7" v-close-popup />
                <q-btn 
                    label="Delete" 
                    rounded 
                    no-caps 
                    unelevated 
                    color="negative"
                    @click="deleteUser" 
                    :loading="deleteUserForm.processing"
                    :disable="deleteUserForm.processing"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>
