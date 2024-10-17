<script setup>

import { Head, useForm, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useQuasar } from 'quasar'
import { ref } from 'vue'

defineOptions({
    layout: AdminLayout
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

const productPhotoRef = ref(null)
const imgPreview = ref('')

const triggerFilePicker = () => {
    productPhotoRef.value.pickFiles();
};

const onFileChange = (file) => {
    imgPreview.value = URL.createObjectURL(file)
}

const addCategoryDialog = ref(false)

</script>

<template>
    
    <Head title="New Milktea" />
    <div class="q-pa-md">
        <q-card flat bordered>
            <q-card-section  style="position: sticky; top: 0; z-index: 99;" class="q-pa-none q-pt-md q-px-md bg-white">
                <div  class="row flex justify-center bg-white">
                    <Link :href="route('admin.products.index')">
                        <q-btn icon="arrow_back" rounded flat class="absolute-top-left q-ml-md q-mt-md text-black" label="Go Back" no-caps/>
                    </Link>
                    <div class="text-h6">Create Product</div>
                    <q-btn 
                        @click="submit"
                        no-caps 
                        rounded
                        color="primary" 
                        class="q-mr-md q-mt-md absolute-top-right"
                        :loading="form.processing"
                        :disable="form.processing"
                        label="Save"
                    />
                </div>
                <q-separator class="q-mt-md"/>
            </q-card-section>

            <q-card-section>
                <q-form @submit="submit">
                    <!-- <div>Build Your Event Page</div>
                    <div>Add all of your event details and let attendees know what to expect</div> -->
                    <div class="q-mx-md q-mt-md">
                        <!-- <div>Add product photo</div>    
                        <div>Photos can help customers decide what to order and can increase sale.</div> -->
                        <p class="text-subtitle1 text-weight-bold">Product photo</p>
                        <q-item class="q-my-md" :style="form.errors.photo ? 'border: 1px solid red' : ''">
                            <q-item-section avatar>
                                <q-img 
                                    v-if="form.photo"
                                    :src="form.photo == null ? '' : imgPreview" 
                                    style="width: 100px; height: 100px;"
                                />
                                <div v-else style="height: 100px; width: 100px" class="bg-grey">
                                </div>
                            </q-item-section>
                            <q-item-section>
                                <q-file 
                                    v-model="form.photo"
                                    :error="form.errors.photo ? true : false"
                                    :error-message="form.errors.photo"
                                    style="display: none;"
                                    ref="productPhotoRef"
                                    @update:model-value="onFileChange"
                                />
                                <q-item-label>Photos can help customers decide what to order and can increase sale.</q-item-label>
                                <q-item-label caption>File requirement: JPG, PNG</q-item-label>
                                <q-item-label class="text-red" caption>{{ form.errors.photo ? form.errors.photo : '' }}</q-item-label>
                                <q-item-label>
                                    <q-btn 
                                        no-caps color="primary" 
                                        v-if="form.photo" 
                                        @click="triggerFilePicker"
                                        rounded
                                    >
                                        Change
                                    </q-btn>
                                    <q-btn 
                                        no-caps 
                                        color="primary" 
                                        v-else 
                                        rounded
                                        @click="triggerFilePicker"
                                        label="Add photo"
                                    />
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                        <!-- <div>Event Overview</div>     -->
                        <!-- <div>Add cover photo</div>    
                        <div>Add photos to show what your event will be about. See examples</div> -->
                    <p class="text-subtitle1 text-weight-bold">Product Details</p>

                        <q-input 
                            label="Name" 
                            v-model="form.name" 
                            outlined
                            rounded
                            :error="form.errors.name ? true : false"
                            :error-message="form.errors.name"
                        />
                        <!-- <div>Add cover photo</div>    
                        <div>Add photos to show what your event will be about. See examples</div> -->
                        <q-input 
                            label="Description" 
                            v-model="form.description" 
                            outlined 
                            rounded
                            type="textarea"
                            :error="form.errors.description ? true : false"
                            :error-message="form.errors.description"
                        />
                        <!-- <div>Add cover photo</div>    
                        <div>Add photos to show what your event will be about. See examples</div> -->
                        <q-select 
                            outlined 
                            class="q-mt-lg"
                            label="Categories" 
                            multiple
                            rounded
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
                        <!-- <div>Tickets</div>
                        <div>How much do you want to charge for tickets?</div> -->
                        <q-input 
                            outlined 
                            rounded
                            label="Default Price" 
                            v-model="form.price" 
                            placeholder="None" 
                            class="q-mt-md"
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
                    </div>
                </q-form>
            </q-card-section>
        </q-card>
    </div>
    <q-dialog v-model="addCategoryDialog">
        <q-card>
            <q-card-section>

            </q-card-section>
        </q-card>
    </q-dialog>
</template>
