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
    images: [],
    guests: 1,
    price: '',
    amenities: []
})

const submit = () => {
    form.post(route('admin.facilities.store'), {
        // onFinish: () => form.reset('name', 'description', 'images', 'price', 'amenities'),
        onSuccess: () => {
            $q.notify('Facility Successfully Added')
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

const handleFileAdded = (files) => {
    console.log('wtf')
    files.forEach(el => form.images.push(el))
console.log(form.images)
}

const handleRemoveFile = (files) => {
    const fileKey = files[0].__key;
    form.images = form.images.filter((img) => img.__key !== fileKey);
}

</script>

<template>
    
    <Head title="Create Facility" />
    <div class="q-pa-md">
        <q-card>
            <q-card-section style="position: sticky; top: 0; z-index: 99;" class="q-pa-none q-pt-md q-px-md bg-white">
                <div  class="row flex justify-center bg-white">
                    <Link :href="route('admin.facilities.index')">
                        <q-btn icon="arrow_back" flat class="absolute-top-left q-ml-md q-mt-md " label="Go Back" no-caps/>
                    </Link>
                    <div class="text-h6">Create Facility</div>
                    <q-btn 
                            @click="submit"
                            no-caps 
                            icon="save"
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
                    <div class="q-mx-xl q-mt-md">
                        <div class="row q-col-gutter-sm">
                            <div class="col-3 text-h6 items-center flex">
                                Faciliy Images
                            </div>
                            <div class="col-9 justify-end flex items-end self-end">
                                <!-- <q-btn no-caps @click="editImages = !editImages">Edit</q-btn>
                                <q-btn no-caps>Add</q-btn> -->
                            </div>
                            <!-- {{ form }} -->
                            <div class="col-12">
                                <q-uploader
                                    url="http://localhost:4444/upload"
                                    label="Upload Files"
                                    multiple
                                    style="width: 100%; height: 50vh;"
                                    @added="handleFileAdded"
                                    @removed="handleRemoveFile"
                                >
                                <template v-slot:list="scope">
                                    <div class="fit bg-grey-4" v-if="scope.files.length < 1">
                                        <div class="absolute-center q-pb-lg">
                                            <q-icon name="upload" size="80px" class="q-mb-xl"></q-icon>
                                        </div>
                                    <div class="absolute-center q-mt-xl items-center">
                                                Drag & Drop To Upload Files
                                            <br>
                                            <div class=" justify-center  flex">
                                                Or
                                            </div>
                                            <div class=" justify-center  flex">
                                                <q-btn label="Upload Files" @click="scope.pickFiles" v-if="scope.canAddFiles" no-caps color="primary" />
                                            </div>
                                    </div>
                                    </div>
                                    <q-list separator>
                                    <q-item v-for="(file, index) in scope.files" :key="file.__key">
                                        <q-item-section avatar>
                                            {{ index + 1 }}
                                        </q-item-section>
                                        <q-item-section>
                                        <q-item-label class="full-width ellipsis">
                                            {{ file.name }}
                                        </q-item-label>

                                        <!-- <q-item-label caption>
                                            Status: {{ file.__status }}
                                        </q-item-label> -->

                                        <q-item-label caption>
                                            {{ file.__sizeLabel }}
                                        </q-item-label>
                                        </q-item-section>

                                        <q-item-section
                                            v-if="file.__img"
                                            thumbnail
                                            class="gt-xs"
                                        >
                                            <img :src="file.__img.src">
                                        </q-item-section>

                                        <q-item-section top side>
                                        <q-btn
                                            class="gt-xs"
                                            size="12px"
                                            flat
                                            dense
                                            round
                                            icon="delete"
                                            @click="scope.removeFile(file)"
                                        />
                                        </q-item-section>
                                    </q-item>

                                    </q-list>
                                </template>
                                </q-uploader>
                            </div>
                            <!-- <div class="col-2" v-for="n in 5">
                                <q-card style="height: 100px; width: 100%" bordered :flat="!editImages" class="imageCard">
                                    <q-img 
                                        src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTym_mmZPWEJQnh9tUlH6IApysMxsFSdQifnw&s" 
                                        style="width: 100%; height: 100%;" 
                                    />
                                        <q-btn icon="delete" class="deleteImageBtn absolute-center" color="negative" flat round></q-btn>
                                </q-card>
                            </div> -->
                        </div>
                        <!-- <div>imagess can help customers decide what to order and can increase sale.</div> -->
                        <!-- <q-item class="q-my-md">
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
                        </q-item> -->
                        <!-- <div>Event Overview</div>     -->
                        <!-- <div>Name</div>    
                        <div>Add photos to show what your event will be about. See examples</div> -->
                        <div class="text-h6 q-my-md">Facility Description</div>
                        <q-input 
                            label="Name" 
                            v-model="form.name" 
                            filled
                            :error="form.errors.name ? true : false"
                            :error-message="form.errors.name"
                        />
                        <!-- <div>Description</div>    
                        <div>Add photos to show what your event will be about. See examples</div> -->
                        <q-input 
                            label="Description" 
                            v-model="form.description" 
                            filled 
                            type="textarea"
                            :error="form.errors.description ? true : false"
                            :error-message="form.errors.description"
                        />
                    <!-- {{ form }} -->
                        <div class="row q-col-gutter-lg">
                            <div class="col">
                                <!-- <div>Price</div>
                                <div>How much do you want to charge for tickets?</div> -->
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
                            </div>
                            <div class="col">
                                <!-- <div>Guests</div>
                                <div>How much do you want to charge for tickets?</div> -->
                                <q-input 
                                    filled 
                                    label="Guests" 
                                    v-model="form.guests" 
                                    placeholder="None" 
                                    class="q-mt-md"
                                    :error="form.errors.guests ? true : false"
                                    :error-message="form.errors.guests"
                                >
                                    <template v-slot:prepend>
                                        <q-icon name="attach_money" />
                                    </template>
                                </q-input>
                            </div>
                        </div>

                        <div class="text-h6 q-my-md">Facility Amenities</div>
                        <!-- <div>Amenities</div>    
                        <div>Add photos to show what your event will be about. See examples</div> -->
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
            </q-card-section>
        </q-card>
    </div>
</template>

<style scoped>

.imageCard .deleteImageBtn {
    display: none;
}

.imageCard:hover .deleteImageBtn {
    display: block;
}

.imageCard:hover .q-img {
    transform: scale(1.05);
    background-color: red /* Semi-transparent black background */
}

</style>