<script setup>

import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useQuasar } from 'quasar'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    modifier_items: Object
})


const modifierItemForm = useForm({
    name: '',
    description: '',
    price: 0.00
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

const submitModifierItemForm = () => {
    modifierItemForm.post(route('admin.modifier_items.store'), {
        onFinish: () => modifierItemForm.reset('name', 'price'),
        onSuccess: () => {
            $q.notify('Modifier Item Successfully Added')
            dialog.value = false
        }
    })
}

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
            <div class="row">
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
            <q-separator class="q-my-md" />
            <q-input 
                v-model="modifierGroupForm.name"
                :error="modifierGroupForm.errors.name ? true : false"
                :error-message="modifierGroupForm.errors.name"
                label="Name" 
                filled
            >
            </q-input>
            {{ item }}
            <q-select 
                filled 
                emit-value
                use-chips
                map-options
                v-model="modifierGroupForm.items" 
                label="Items" 
                multiple
                option-value="id"
                option-label="name"
                class="q-mt-md" 
                :options="props.modifier_items"
                :options-html="true"
                :error="modifierGroupForm.errors.items ? true : false"
                :error-message="modifierGroupForm.errors.items"
            >
            </q-select>
            
            <br>
            <div>
                max quantity
                <q-input
                    filled
                    type="number"
                    label="Whats the maximum amount of items the customer can select?"
                    v-model="modifierGroupForm.max_quantity"
                    :error="modifierGroupForm.errors.max_quantity ? true : false"
                    :error-message="modifierGroupForm.errors.max_quantity"
                >
                </q-input>
                required quantity
                <q-input
                    filled
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
        </div>
    </q-form>
    <q-dialog v-model="dialog" position="right" full-height>
        <q-card style="width: 500px">
            <q-form @submit="submitModifierItemForm">
                <q-card-section class="row items-center no-wrap">
                        <div class="text-weight-bold text-subtitle1">New Item</div>
                        <q-space />
                        <q-btn 
                            color="primary" 
                            no-caps 
                            class="q-mr-sm"
                            type="submit"
                            :loading="modifierItemForm.processing"
                            :disable="modifierItemForm.processing"
                        >
                            Save
                        </q-btn>
                        <q-btn flat round icon="close" />
                </q-card-section>
                <q-card-section>
                    <q-input 
                        label="Name" 
                        filled
                        v-model="modifierItemForm.name"
                        :error="modifierItemForm.errors.name ? true : false"
                        :error-message="modifierItemForm.errors.name"
                    />
                    <q-input 
                        label="Description (Optional)" 
                        filled 
                        class="q-mt-md" 
                        type="textarea" 
                        v-model="modifierItemForm.description"
                        :error="modifierItemForm.errors.description ? true : false"
                        :error-message="modifierItemForm.errors.description"
                    />
                    <q-input 
                        filled 
                        label="Default Price" 
                        placeholder="None" 
                        class="q-mt-md"
                        v-model="modifierItemForm.price"
                        :error="modifierItemForm.errors.price ? true : false"
                        :error-message="modifierItemForm.errors.price"
                        mask="#.##"
                        fill-mask="0"
                        reverse-fill-mask
                    >
                        <template v-slot:prepend>
                            <q-icon name="attach_money" />
                        </template>
                    </q-input>
                </q-card-section>
            </q-form>
        </q-card>
    </q-dialog>
</template>
