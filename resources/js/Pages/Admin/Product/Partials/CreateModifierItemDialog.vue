<script setup>

import { useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { ref } from 'vue'

const $q = useQuasar()
const dialog = ref(false)
const form = useForm({
    name: '',
    description: '',
    price: 0.00
})

const submit = () => {
    form.post(route('admin.modifier_items.store'), {
        onFinish: () => form.reset('name', 'price'),
        onSuccess: () => {
            $q.notify('Modifier Item Successfully Added')
            dialog.value = false
        }
    })
}

</script>

<template>
    <q-btn 
        @click="dialog = !dialog" 
        no-caps 
        rounded
        color="primary" 
        class="q-mr-sm q-mt-md absolute-top-right" 
        outline
        label="New Modifier Item"    
    />
    <q-dialog v-model="dialog" position="right" full-height>
        <q-card style="width: 500px">
            <q-form @submit="submit">
                <q-card-section class="row items-center no-wrap">
                        <div class="text-weight-bold text-subtitle1">New Item</div>
                        <q-space />
                        
                        <q-btn flat round icon="close" />
                </q-card-section>
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
                        label="Description (Optional)" 
                        rounded 
                        outlined 
                        class="q-mt-md" 
                        type="textarea" 
                        v-model="form.description"
                        :error="form.errors.description ? true : false"
                        :error-message="form.errors.description"
                    />
                    <q-input 
                        rounded 
                        outlined 
                        label="Default Price" 
                        placeholder="None" 
                        class="q-mt-md"
                        v-model="form.price"
                        :error="form.errors.price ? true : false"
                        :error-message="form.errors.price"
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
                            label="Save"
                        />
                </q-card-section>
                
                
            </q-form>
        </q-card>
    </q-dialog>
</template>

