<script setup>

import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import MilkteaLayout from '@/Layouts/MilkteaLayout.vue'

defineOptions({
    layout: AdminLayout
})

const item = ref(null)
const dialog = ref(false)
const price = ref('None')

</script>

<template>
    
    <Head title="New Modifiers Groups" />
    <MilkteaLayout>
        <div class="q-pa-md">
            <div class="row">
                <q-btn icon="arrow_back" flat round></q-btn>
                <span class="text-h6 q-mt-xs q-ml-sm">New Milktea</span>
                <q-space/>
                <q-btn no-caps color="primary" class="q-mr-sm">Save</q-btn>
            </div>
            <q-separator class="q-my-md" />
            <q-input label="Name" filled></q-input>
            <q-card class="my-card" flat bordered>
                <q-card-section horizontal>
                    <q-img src="https://cdn.quasar.dev/img/chicken-salad.jpg" style="width: 100px; height: 100px;" />
                </q-card-section>
                <q-card-section>hello</q-card-section>
            </q-card>
            <br>
            <q-btn icon="add" flat @click="dialog = !dialog" class="q-mt-md" no-caps color="primary" label="Create New Item" />
            <br>
            <q-checkbox label="Require customers to select item?"/>
            <br>
            <div>
                Whats the maximum amount of items the customer can select?"
                <q-input filled></q-input>
            </div>
        </div>
    </MilkteaLayout>
    <q-dialog v-model="dialog" position="right" full-height>
        <q-card style="width: 500px">
            <q-card-section class="row items-center no-wrap">
                    <div class="text-weight-bold text-subtitle1">New Item</div>
                    <q-space />
                    <q-btn color="primary" no-caps class="q-mr-sm">Save</q-btn>
                    <q-btn flat round icon="close" />
            </q-card-section>
            <q-card-section>
                <q-input label="Name" filled/>
                <q-input label="Description (Optional)" filled class="q-mt-md" type="textarea" />
                <q-input filled label="Default Price" placeholder="None" class="q-mt-md">
                    <template v-slot:prepend>
                        <q-icon name="attach_money" />
                    </template>
                </q-input>
            </q-card-section>
        </q-card>
    </q-dialog>
</template>
