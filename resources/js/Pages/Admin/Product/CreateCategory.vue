<script setup>

import { Head, useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useQuasar } from 'quasar'

defineOptions({
    layout: AdminLayout
})

const $q = useQuasar()
const form = useForm({
    name: ''
})

const submit = () => {
    form.post(route('admin.categories.store'), {
        onFinish: () => form.reset('name'),
        onSuccess: () => {
            $q.notify('Category Successfully Added')
        }
    })
}

</script>

<template>
    
    <Head title="New Category" />
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
        <q-card class="round-border">
            <q-card-section  style="position: sticky; top: 0; z-index: 99;" class="q-pa-none q-pt-md q-px-md bg-white">
                <div  class="row flex justify-center bg-white">
                    <Link :href="route('admin.categories.index')">
                        <q-btn icon="arrow_back" flat class="absolute-top-left q-mt-md " rounded :label="$q.screen.gt.sm ? 'Go Back' : ''" no-caps/>
                    </Link>
                    <div class="text-h6">Create Category</div>
                    <q-btn 
                        @click="submit"
                        no-caps 
                        color="primary" 
                        class="q-mr-md q-mt-md absolute-top-right"
                        :loading="form.processing"
                        :disable="form.processing"
                        label="Save"
                        rounded
                    />
                </div>
                <q-separator class="q-mt-md"/>
            </q-card-section>
            <q-card-section>
                <q-input 
                    label="Name" 
                    :error="form.errors.name ? true : false"  
                    v-model="form.name" 
                    rounded 
                    outlined
                    :error-message="form.errors.name"
                />
            </q-card-section>
        </q-card>
    </div>
    <!-- <div class="q-pa-md">
        <q-form @submit="submit">
            <div class="row">
                <q-btn icon="arrow_back" flat round></q-btn>
                <span class="text-h6 q-mt-xs q-ml-sm">New Category</span>
                <q-space/>
                <q-btn 
                    no-caps 
                    type="submit" 
                    color="primary" 
                    class="q-mr-sm"
                    :loading="form.processing"
                    :disable="form.processing"
                >
                    Save
                </q-btn>
            </div>
            <q-separator class="q-my-md" />
            <q-input 
                label="Name" 
                :error="form.errors.name ? true : false"  
                v-model="form.name" 
                filled
                :error-message="form.errors.name"
            >
            </q-input>
        </q-form>
    </div> -->
</template>
