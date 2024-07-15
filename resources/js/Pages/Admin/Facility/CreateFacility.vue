<script setup>

import { Head, useForm } from '@inertiajs/vue3'
import FacilityLayout from '@/Layouts/FacilityLayout.vue'
import { useQuasar } from 'quasar'
import { ref } from 'vue'

defineOptions({
    layout: FacilityLayout
})

const props = defineProps({
    categories: Object
})

const $q = useQuasar()
const form = useForm({
    name: '',
    description: '',
    images: null,
    price: '',
    amenities: []
})

const submit = () => {
    form.post(route('admin.facilities.store'), {
        onFinish: () => form.reset('name', 'description', 'images', 'price', 'amenities'),
        onSuccess: () => {
            $q.notify('Product Successfully Added')
        }
    })
}

const amenities = ref([])

for(var i = 0; i < 10; i++) {
    amenities.value.push(
        {
            id: i,
            icon: 'style',
            name: 'Amenity'
        }
    )
}

function addAmenity(amenity) {
    if(!form.amenities.includes(amenity)) {
        form.amenities.push(amenity)
    }
}

function deleteAmenity(amenity) {
    form.amenities = form.amenities.filter(a => a.id !== amenity.id)
}

</script>

<template>
    
    <Head title="Create Facility" />
    <div class="q-pa-md q-mb-xl">
        <q-form @submit="submit">
            <div class="row">
                <q-btn icon="arrow_back" flat round></q-btn>
                <span class="text-h6 q-mt-xs q-ml-sm">Create Facility</span>
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
                <div>Images</div>    
                <div>imagess can help customers decide what to order and can increase sale.</div>
                <q-item class="q-my-md">
                    <q-item-section avatar>
                        <q-img src="https://cdn.quasar.dev/img/chicken-salad.jpg" style="width: 100px; height: 100px;" />
                    </q-item-section>
                    <q-item-section>
                        <q-input 
                            type="file" 
                            filled 
                            v-model="form.images"
                            :error="form.errors.images ? true : false"
                            :error-message="form.errors.images"
                            multiple
                        />
                        <q-item-label caption>You can choose up to 2-5 images</q-item-label>
                        <q-item-label>
                            <q-btn no-caps color="primary">Upload photo</q-btn>
                        </q-item-label>
                    </q-item-section>
                </q-item>
                <!-- <div>Event Overview</div>     -->
                <div>Name</div>    
                <div>Add photos to show what your event will be about. See examples</div>
                <q-input 
                    label="Name" 
                    v-model="form.name" 
                    filled
                    :error="form.errors.name ? true : false"
                    :error-message="form.errors.name"
                />
                <div>Description</div>    
                <div>Add photos to show what your event will be about. See examples</div>
                <q-input 
                    label="Description" 
                    v-model="form.description" 
                    filled 
                    type="textarea"
                    :error="form.errors.description ? true : false"
                    :error-message="form.errors.description"
                />
               {{ form }}
                <div>Price</div>
                <div>How much do you want to charge for tickets?</div>
                <q-input 
                    filled 
                    label="Price" 
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
                <div>Amenities</div>    
                <div>Add photos to show what your event will be about. See examples</div>
                <div class="row q-col-gutter-md">
                    <div class="col-3 cursor-pointer" v-for="(amenity, i) in amenities">
                        <q-card  flat bordered @click="addAmenity(amenity)" :class="form.amenities.includes(amenity) ? 'bg-primary' : ''">
                            <q-item>
                                <q-item-section avatar>
                                    <q-icon :name="amenity.icon" />
                                </q-item-section>
                                <q-item-section>
                                    <q-item-label>
                                        {{ amenity.name }} {{ i }}
                                    </q-item-label>
                                </q-item-section>
                                <q-item-section right>
                                    <q-btn icon="close" v-if="form.amenities.includes(amenity)" flat @click.stop="deleteAmenity(amenity)" />
                                </q-item-section>
                            </q-item>
                        </q-card>
                    </div>
                </div>
            </div>
        </q-form>
    </div>
</template>
