<script setup>

import { Head, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import MilkteaLayout from '@/Layouts/ProductLayout.vue'
import { useQuasar } from 'quasar'
import { ref } from 'vue'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    categories: Object,
    product: Object,
    modifier_groups: Object
})

const addModifierGroupDialog = ref(false)
const $q = useQuasar()
const form = useForm({
    name: props.product.name,
    description: props.product.description,
    photo: props.product.photo,
    price: props.product.price,
    categories: props.product.categories
})

const submit = () => {
    form.post(route('admin.product.store'), {
        onFinish: () => form.reset('name', 'description', 'photo', 'price'),
        onSuccess: () => {
            $q.notify('Product Successfully Added')
        }
    })
}

</script>

<template>
    
    <Head title="Edit Product" />
    <MilkteaLayout>
        <div class="q-pa-md q-mb-xl">
            <p class="text-weight-bold">Categories: </p>
            {{ props.categories }}
            <q-separator></q-separator>
            <p class="text-weight-bold">Modifier Groups: </p>
            {{ props.modifier_groups }}
            <q-separator></q-separator>
            <p class="text-weight-bold">Product: </p>
            {{ props.product.categories }}
            <q-form @submit="submit">
                <div class="row">
                    <q-btn icon="arrow_back" flat round></q-btn>
                    <span class="text-h6 q-mt-xs q-ml-sm">Edit Product</span>
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
                <q-input 
                    label="Name" 
                    v-model="form.name" 
                    filled
                    :error="form.errors.name ? true : false"
                    :error-message="form.errors.name"
                >
                </q-input>
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
                        >
                        </q-input>
                        <q-item-label>Photos can help customers decide what to order and can increase sale.</q-item-label>
                        <q-item-label caption>File requirement: JPG, PNG</q-item-label>
                        <q-item-label>
                            <q-btn no-caps color="primary">Upload photo</q-btn>
                        </q-item-label>
                    </q-item-section>
                </q-item>
                <q-input 
                    label="Description" 
                    v-model="form.description" 
                    filled 
                    type="textarea"
                    :error="form.errors.description ? true : false"
                    :error-message="form.errors.description"
                >
                </q-input>
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
                >
                </q-select>
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
                <p class="text-weight-bold text-h6">Modifier Groups</p>
                <p class="text-weight-light">
                    Modifier groups allow customers to 
                    use toppings and more to customize items
                </p>
                <q-expansion-item
                    switch-toggle-side
                    expand-separator
                    v-for="n in 3"
                    :key="n"
                >
                <template v-slot:header>
                    <q-item-section>
                        Choose your size
                    </q-item-section>

                    <q-item-section side>
                        <div class="row items-center">
                            <q-chip>Required</q-chip>
                            <q-btn icon="more_vert" round unelevated></q-btn>
                        </div>
                    </q-item-section>
                </template>
                    <q-card>
                    <q-card-section>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem, eius reprehenderit eos corrupti
                        commodi magni quaerat ex numquam, dolorum officiis modi facere maiores architecto suscipit iste
                        eveniet doloribus ullam aliquid.
                    </q-card-section>
                    </q-card>
                </q-expansion-item>
                <div class="q-my-lg">
                    <q-chip 
                        outline 
                        clickable 
                        color="primary" 
                        text-color="white" 
                        size="lg" 
                        @click="addModifierGroupDialog = true"
                    >
                        <span class="text-subtitle2">Add Modifier Group</span>
                    </q-chip>
                    <q-chip 
                        outline 
                        clickable 
                        color="primary" 
                        text-color="white" 
                        size="lg" 
                    >
                        <span class="text-subtitle2">Clear</span>
                    </q-chip>
                </div>
            </q-form>
        </div>
        <q-dialog v-model="addModifierGroupDialog">
            <q-card>
                <q-card-section>Add modifier group dialog yippie :3</q-card-section>
            </q-card>
        </q-dialog>
    </MilkteaLayout>
</template>
