<script setup>

import { useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { ref, defineEmits } from 'vue'

const $q = useQuasar()
const emit = defineEmits(['delete'])
const props = defineProps({ modifier_item: Object })

const editDialog = ref(false)
const deleteDialog = ref(false)
const form = useForm({
    name: props.modifier_item.name,
    price: props.modifier_item.price
})
const deleteForm = useForm({})

const updateModifierItem = () => {
    form.put(route('admin.modifier_items.update', props.modifier_item.id), {
        onSuccess: () => {
            editDialog.value = false
            $q.notify('Modifier Item Updated')
        }
    })
}

const deleteModifierItem = () => {
    deleteForm.delete(route('admin.modifier_items.destroy', props.modifier_item.id), {
        onSuccess: () => {
            deleteDialog.value = false
            $q.notify('Modifier Item Deleted')
        }
    })
}


</script>

<template>
    <q-card bordered flat class="q-mb-sm">
        <q-item class="q-pa-none q-px-md">
            <q-item-section>
                <q-item-label>{{ modifier_item.name }}</q-item-label>
                <q-item-label caption>P{{ modifier_item.price }}</q-item-label>
            </q-item-section>
            <q-item-section side>
                <q-btn icon="more_horiz" round flat v-if="modifier_item.id">
                    <q-menu>
                        <q-list>
                            <q-item @click="editDialog = true" clickable>
                                <q-item-section>
                                    Edit
                                </q-item-section>
                            </q-item>
                            <q-item @click="deleteDialog = true" clickable>
                                <q-item-section>
                                    Delete
                                </q-item-section>
                            </q-item>
                        </q-list>
                    </q-menu>
                </q-btn>
                <q-btn icon="delete" v-else flat round color="negative" @click="emit('delete')" />
            </q-item-section>
        </q-item>
    </q-card>
    <q-dialog 
        v-model="deleteDialog" 
        persistent 
        :maximized="$q.screen.lt.md"
        transition-show="slide-up"
        :position="$q.screen.lt.md ? 'bottom' : 'standard'"
        transition-hide="slide-down"
    >
        <q-card>
            <q-card-section class="row items-center q-pb-none">
                <q-icon name="warning" color="negative" size="32px" />
                <div class="text-h6 q-ml-md">Delete Modifier Item</div>
                <q-btn round icon="close" v-close-popup flat class="absolute-top-right q-mt-sm q-mr-sm"/>

            </q-card-section>
            <q-card-section>
                Are you sure you want to delete this modifier item? All data will be permanently removed. This action cannot be undone.
                <q-item class="bg-negative text-white q-my-md rounded-borders">
                    <q-item-section>
                        <q-item-label class="text-weight-bold text-subtitle1">{{ modifier_item.name }}</q-item-label>
                        <q-item-label >{{ modifier_item.price }}</q-item-label>
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
                    @click="deleteModifierItem" 
                    :loading="deleteForm.processing"
                    :disable="deleteForm.processing"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
    <q-dialog 
        v-model="editDialog" 
        persistent 
        :maximized="$q.screen.lt.md"
        transition-show="slide-up"
        :position="$q.screen.lt.md ? 'bottom' : 'standard'"
        transition-hide="slide-down"
    >
        <q-card :style="$q.screen.gt.sm ? 'max-width: 40vw; width: 100%;' : ''">
            <q-card-actions class="justify-between">
                <div class="text-h6">Edit Modifier Item</div>
                <q-btn icon="close" v-close-popup round flat />
            </q-card-actions>
            <q-card-section>
                <q-input 
                    label="Name" 
                    rounded 
                    outlined
                    v-model="form.name"
                    :error="form.errors.name ? true : false"
                    :error-message="form.errors.name"
                />
                <q-input 
                    rounded 
                    outlined 
                    label="Additional Price" 
                    placeholder="None" 
                    class="q-mt-md"
                    v-model="form.price"
                    :error="form.errors.price ? true : false"
                    :error-message="form.errors.price"
                    mask="#.##"
                    fill-mask="0"
                    reverse-fill-mask
                >
                    <template v-slot:prepend>
                        <q-icon name="attach_money" />
                    </template>
                </q-input>
                <q-btn 
                        color="primary" 
                        no-caps 
                        class="q-mr-sm full-width"
                        type="submit"
                        rounded 
                        unelevated
                        :loading="form.processing"
                        :disable="form.processing"
                        label="Update"
                        @click="updateModifierItem"
                    />
            </q-card-section>
        </q-card>
    </q-dialog>
</template>

