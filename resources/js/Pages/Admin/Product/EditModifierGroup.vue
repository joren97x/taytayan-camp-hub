<script setup>

import { Head, useForm } from '@inertiajs/vue3'
import { ref, onMounted, watch } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useQuasar } from 'quasar'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    modifier_items: Object,
    modifier_group: Object
})


const modifierItemForm = useForm({
    name: '',
    description: '',
    price: 0.00
})
console.log(props)
const form = useForm({
    name: props.modifier_group.name,
    required: props.modifier_group.required,
    // items: null,
    required_quantity: props.modifier_group.required_quantity,
    max_quantity: props.modifier_group.max_quantity,
    modifier_items: props.modifier_items.map((m) => m.id)
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

const submitForm = () => {
    form.put(route('admin.modifier_groups.update', props.modifier_group.id), {
        onSuccess: () => {
            $q.notify('Modifier Group Successfully Added')
        }
    })
}

const selected = ref([])
console.log(props.modifier_group)
onMounted(() => {
    selected.value = props.modifier_group.modifier_items.map(modifier_item => modifier_item.id)
    form.modifier_items = selected.value
})

watch(selected, (modifier_item) => {
    // form.modifier_items = modifier_item.map(row => row.id)
})

</script>

<template>
    
    <Head title="New Modifiers Groups" />
    <q-form @submit="submitForm">
        <div class="q-pa-md">
            {{ form }}
            {{ selected }}
            <div class="row">
                <q-btn icon="arrow_back" flat round></q-btn>
                <span class="text-h6 q-mt-xs q-ml-sm">Edit Modifier Group</span>
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
                    :loading="form.processing"
                    :disable="form.processing"
                >
                    Save
                </q-btn>
            </div>
            <q-separator class="q-my-md" />
            <q-input 
                v-model="form.name"
                :error="form.errors.name ? true : false"
                :error-message="form.errors.name"
                label="Name" 
                filled
            />
            {{ item }}
            <q-select 
                filled 
                emit-value
                use-chips
                map-options
                v-model="form.modifier_items" 
                label="Items" 
                multiple
                option-value="id"
                option-label="name"
                class="q-mt-md" 
                :options="props.modifier_items"
                :options-html="true"
                :error="form.errors.modifier_items ? true : false"
                :error-message="form.errors.modifier_items"
            />
            
            <br>
            <div>
                <q-input
                    filled
                    type="number"
                    label="Whats the maximum amount of items the customer can select?"
                    v-model="form.max_quantity"
                    :error="form.errors.max_quantity ? true : false"
                    :error-message="form.errors.max_quantity"
                />
                <q-input
                    filled
                    type="number"
                    label="How many times can customers select any single item?"
                    v-model="form.required_quantity"
                    :error="form.errors.required_quantity ? true : false"
                    :error-message="form.errors.required_quantity"
                />
            </div>
            <q-checkbox 
                label="Require customers to select item?" 
                v-model="form.required"
                :error="form.errors.required ? true : false"
                :error-message="form.errors.required"
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
