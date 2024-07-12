<script setup>

import { Head, useForm } from '@inertiajs/vue3'
import ProductLayout from '@/Layouts/ProductLayout.vue'
import { useQuasar } from 'quasar'

defineOptions({
    layout: ProductLayout
})

const props = defineProps({
    categories: Object
})

const $q = useQuasar()
const form = useForm({
    name: '',
    description: '',
    photo: null,
    price: '',
    categories: null
})

const submit = () => {
    form.post(route('admin.products.store'), {
        onFinish: () => form.reset('name', 'description', 'photo', 'price'),
        onSuccess: () => {
            $q.notify('Product Successfully Added')
        }
    })
}

</script>

<template>
    
    <Head title="New Milktea" />
    <div class="q-pa-md q-mb-xl">
        <q-form @submit="submit">
            <div class="row">
                <q-btn icon="arrow_back" flat round></q-btn>
                <span class="text-h6 q-mt-xs q-ml-sm">New Product</span>
                <q-space/>
                <q-btn 
                    type="submit" 
                    no-caps 
                    color="primary" 
                    class="q-mr-sm"
                    :loading="form.processing"
                    :disable="form.processing"
                >
                    Save
                </q-btn>
            </div>
            <q-separator class="q-my-lg" />
            <div>Build Your Event Page</div>
            <div>Add all of your event details and let attendees know what to expect</div>
            <div class="q-mx-xl q-mt-md">
                <div>Add product photo</div>    
                <div>Photos can help customers decide what to order and can increase sale.</div>
                <q-item class="q-my-md">
                    <q-item-section avatar>
                        <q-img src="https://cdn.quasar.dev/img/chicken-salad.jpg" style="width: 100px; height: 100px;" />
                    </q-item-section>
                    <q-item-section>
                        <q-input 
                            type="file" 
                            filled 
                            v-model="form.photo"
                            :error="form.errors.photo ? true : false"
                            :error-message="form.errors.photo"
                        />
                        <q-item-label caption>File requirement: JPG, PNG</q-item-label>
                        <q-item-label>
                            <q-btn no-caps color="primary">Upload photo</q-btn>
                        </q-item-label>
                    </q-item-section>
                </q-item>
                <div>Event Overview</div>    
                <div>Add cover photo</div>    
                <div>Add photos to show what your event will be about. See examples</div>
                <q-input 
                    label="Name" 
                    v-model="form.name" 
                    filled
                    :error="form.errors.name ? true : false"
                    :error-message="form.errors.name"
                />
                <div>Add cover photo</div>    
                <div>Add photos to show what your event will be about. See examples</div>
                <q-input 
                    label="Description" 
                    v-model="form.description" 
                    filled 
                    type="textarea"
                    :error="form.errors.description ? true : false"
                    :error-message="form.errors.description"
                />
                <div>Add cover photo</div>    
                <div>Add photos to show what your event will be about. See examples</div>
                <q-select 
                    filled 
                    class="q-mt-lg"
                    label="Categories" 
                    multiple
                    v-model="form.categories"
                    option-label="name"
                    option-value="id"
                    emit-value
                    use-chips
                    map-options
                    :options="props.categories"
                    :error="form.errors.categories ? true : false"
                    :error-message="form.errors.categories"
                />
                <div>Tickets</div>
                <div>How much do you want to charge for tickets?</div>
                <q-input 
                    filled 
                    label="Default Price" 
                    v-model="form.price" 
                    placeholder="None" 
                    class="q-mt-md"
                    :error="form.errors.price ? true : false"
                    :error-message="form.errors.price"
                >
                    <template v-slot:prepend>
                        <q-icon name="attach_money" />
                    </template>
                </q-input>
            </div>
        </q-form>
    </div>
</template>
