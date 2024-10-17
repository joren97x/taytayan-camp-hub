<script setup>

import { Head, useForm, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useQuasar } from 'quasar'
import CreateModifierItemDialog from './Partials/CreateModifierItemDialog.vue'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    modifier_items: Object
})

const modifierGroupForm = useForm({
    name: '',
    required: false,
    items: null,
    required_quantity: 1,
    max_quantity: 1
})

const $q = useQuasar()
const item = ref(null)
const dialog = ref(false)

const submitModifierGroupForm = () => {
    modifierGroupForm.post(route('admin.modifier_groups.store'), {
        onFinish: () => modifierGroupForm.reset('name', 'required', 'required_quantity', 'max_quantity', 'items'),
        onSuccess: () => {
            $q.notify('Modifier Group Successfully Added')
        }
    })
}

</script>

<template>
    
    <Head title="New Modifiers Groups" />
    <q-form @submit="submitModifierGroupForm">
        <div class="q-pa-md">
            <q-card bordered flat style="border-radius: 20px">
                <q-card-section style="position: sticky; top: 0; z-index: 99;" class="q-pa-none q-pt-md q-px-md bg-white">
                    <div class="row flex justify-center bg-white">
                        <Link :href="route('admin.products.index')">
                            <q-btn icon="arrow_back" flat class="absolute-top-left q-ml-md q-mt-md text-black" rounded label="Go Back" no-caps/>
                        </Link>
                        <div class="text-h6">Create Modifier Group</div>
                        <CreateModifierItemDialog />
                    </div>
                    <q-separator class="q-mt-md"/>
                </q-card-section>
                <q-card-section>
                    <q-input 
                        v-model="modifierGroupForm.name"
                        :error="modifierGroupForm.errors.name ? true : false"
                        :error-message="modifierGroupForm.errors.name"
                        label="Name" 
                        rounded 
                        outlined
                    />
                    {{ item }}
                    <q-select 
                        rounded 
                        outlined 
                        emit-value
                        use-chips
                        map-options
                        v-model="modifierGroupForm.items" 
                        label="Items" 
                        multiple
                        option-value="id"
                        option-label="name"
                        :options="props.modifier_items"
                        :options-html="true"
                        :error="modifierGroupForm.errors.items ? true : false"
                        :error-message="modifierGroupForm.errors.items"
                    />
                    <br>
                    <div>
                        max quantity
                        <q-input
                            rounded 
                            outlined
                            type="number"
                            label="Whats the maximum amount of items the customer can select?"
                            v-model="modifierGroupForm.max_quantity"
                            :error="modifierGroupForm.errors.max_quantity ? true : false"
                            :error-message="modifierGroupForm.errors.max_quantity"
                        >
                        </q-input>
                        required quantity
                        <q-input
                            rounded 
                            outlined
                            type="number"
                            label="How many times can customers select any single item?"
                            v-model="modifierGroupForm.required_quantity"
                            :error="modifierGroupForm.errors.required_quantity ? true : false"
                            :error-message="modifierGroupForm.errors.required_quantity"
                        >
                        </q-input>
                    </div>
                    <q-checkbox 
                        label="Require customers to select item?" 
                        v-model="modifierGroupForm.required"
                        :error="modifierGroupForm.errors.required ? true : false"
                        :error-message="modifierGroupForm.errors.required"
                    />
                    <br>
                    <q-btn 
                        no-caps 
                        color="primary" 
                        class="q-mr-sm"
                        rounded 
                        unelevated
                        type="submit"
                        :loading="modifierGroupForm.processing"
                        :disable="modifierGroupForm.processing"
                        label="Save"    
                    />
                </q-card-section>
            </q-card>
            <!-- <div class="row">
                <q-btn icon="arrow_back" flat round></q-btn>
                <span class="text-h6 q-mt-xs q-ml-sm">New Modifier Group</span>
                <q-space/>
                <q-btn 
                    @click="dialog = !dialog" 
                    no-caps 
                    color="primary" 
                    class="q-mr-sm" 
                    outline
                >
                    New Modifier Item
                </q-btn>
                <q-btn 
                    no-caps 
                    color="primary" 
                    class="q-mr-sm"
                    type="submit"
                    :loading="modifierGroupForm.processing"
                    :disable="modifierGroupForm.processing"
                >
                    Save
                </q-btn>
            </div>
            <q-separator class="q-my-md" /> -->
            
        </div>
    </q-form>
</template>
