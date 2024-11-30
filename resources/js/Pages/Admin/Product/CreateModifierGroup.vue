<script setup>

import { Head, useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useQuasar } from 'quasar'
import CreateModifierItemDialog from './Partials/CreateModifierItemDialog.vue'
import ModifierItemCard from './Partials/ModifierItemCard.vue'

defineOptions({ layout: AdminLayout })
const props = defineProps({ modifier_items: Object })

const modifierGroupForm = useForm({
    name: '',
    required: false,
    required_quantity: 1,
    max_quantity: 1,
    modifier_items: []
})

const $q = useQuasar()
const submitModifierGroupForm = () => {
    modifierGroupForm.post(route('admin.modifier_groups.store'), {
        onFinish: () => modifierGroupForm.reset('name', 'required', 'required_quantity', 'max_quantity', 'items'),
        onSuccess: () => {
            $q.notify('Modifier Group Successfully Added')
        }
    })
}
const onModifierItemCreated = (data) => {
    console.log(data)
    modifierGroupForm.modifier_items.push(data)
}

const onModifierItemDeleted = (index) => {
    modifierGroupForm.modifier_items.splice(index, 1)
}

</script>

<template>
    
    <Head title="Create Modifiers Group" />
    <q-form @submit="submitModifierGroupForm">
        <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
            <q-card bordered flat style="border-radius: 20px">
                <q-card-section style="position: sticky; top: 0; z-index: 99;" class="q-pa-none q-pt-md q-px-md bg-white">
                    <div class="row flex justify-center bg-white">
                        <Link :href="route('admin.modifier_groups.index')">
                            <q-btn icon="arrow_back" flat class="absolute-top-left q-mt-md text-black" rounded :label="$q.screen.gt.sm ? 'Go Back' : ''" no-caps/>
                        </Link>
                        <div class="text-h6">Create Modifier Group</div>
                        <!-- <CreateModifierItemDialog /> -->
                        <q-btn 
                            no-caps 
                            color="primary" 
                            class="q-mr-sm absolute-top-right q-mt-md"
                            rounded 
                            unelevated
                            type="submit"
                            :loading="modifierGroupForm.processing"
                            :disable="modifierGroupForm.processing"
                            label="Save"    
                        />
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
                    <!-- <q-select 
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
                    /> -->
                    <!-- <CreateModifierItemDialog /> -->
                    <!-- <br> -->
                    <div class="q-mt-md">
                        <!-- max quantity -->
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
                        <!-- required quantity -->
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
                    <div class="q-mb-md">
                        <div :class="`${$q.screen.gt.sm ? '' : 'justify-between'} row q-mb-sm`">
                            <div class="text-h6 q-mr-md">Modifier Items</div>
                            <CreateModifierItemDialog @created="onModifierItemCreated"/>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                <q-list>
                                    <ModifierItemCard @delete="onModifierItemDeleted(index)" :modifier_item="modifier_item" v-for="(modifier_item, index) in modifierGroupForm.modifier_items" />
                                </q-list>
                            </div>
                        </div>
                    </div>
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
