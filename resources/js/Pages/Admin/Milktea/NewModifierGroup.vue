<script setup>

import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import MilkteaLayout from '@/Layouts/MilkteaLayout.vue'

defineOptions({
    layout: AdminLayout
})

const item = ref(null)
const drawer = ref(false)

</script>

<template>
    
    <Head title="New Modifiers Groups" />
    <MilkteaLayout>
        <div class="q-pa-md">
            <div class="row">
                <q-btn icon="arrow_back" flat round></q-btn>
                <span class="text-h6 q-mt-xs q-ml-sm">New Modifier Group</span>
                <q-space/>
                <q-btn no-caps color="primary" class="q-mr-sm">Save</q-btn>
            </div>
            <q-separator class="q-my-md" />
            <q-input label="Name" filled></q-input>
            <q-select 
                filled 
                v-model="item" 
                label="Items" 
                multiple 
                class="q-mt-md" 
                :options="[
                    'Google', 'Facebook', 'Twitter', 'Apple', 'Oracle'
                ]"
            >
            </q-select>
            <q-btn icon="add" @click="drawer = !drawer" class="q-mt-md" no-caps color="primary" label="Create New Item" />
            <br>
            <q-checkbox label="Require customers to select item?"/>
            <br>
            <q-checkbox label="Whats the maximum amount of items the customer can select?"/>
        </div>
    </MilkteaLayout>
</template>
