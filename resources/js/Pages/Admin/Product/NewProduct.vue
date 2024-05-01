<script setup>

import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import MilkteaLayout from '@/Layouts/ProductLayout.vue'

defineOptions({
    layout: AdminLayout
})

</script>

<template>
    
    <Head title="New Milktea" />
    <MilkteaLayout>
        <div class="q-pa-md q-mb-xl">
            <div class="row">
                <q-btn icon="arrow_back" flat round></q-btn>
                <span class="text-h6 q-mt-xs q-ml-sm">New Product</span>
                <q-space/>
                <q-btn no-caps color="primary" class="q-mr-sm">Save</q-btn>
            </div>
            <q-separator class="q-my-lg" />
            <q-input label="Name" filled></q-input>
            <q-item class="q-my-md">
                <q-item-section avatar>
                    <q-img src="https://cdn.quasar.dev/img/chicken-salad.jpg" style="width: 100px; height: 100px;" />
                </q-item-section>
                <q-item-section>
                    <q-item-label>Photos can help customers decide what to order and can increase sale.</q-item-label>
                    <q-item-label caption>File requirement: JPG, PNG</q-item-label>
                    <q-item-label>
                        <q-btn no-caps color="primary">Upload photo</q-btn>
                    </q-item-label>
                </q-item-section>
            </q-item>
            <q-input label="Description (Optional)" filled type="textarea"></q-input>
            <q-select 
                filled 
                class="q-mt-lg"
                label="Categories" 
                :options="[
                    'Milktea',
                    'Drink',
                    'or uhh',
                    'lorem'
                ]"
            >
            </q-select>
            <q-input filled label="Default Price" placeholder="None" class="q-mt-md">
                <template v-slot:prepend>
                    <q-icon name="attach_money" />
                </template>
            </q-input>
        </div>
    </MilkteaLayout>
</template>
