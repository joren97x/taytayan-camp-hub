<script setup>

import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useQuasar } from 'quasar'
import { ref } from 'vue'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    facility: Object
})

const $q = useQuasar()
const form = useForm({
    name: props.facility.name,
    description: props.facility.description,
    price: props.facility.price,
    guests: props.facility.guests,
    amenities: props.facility.amenities,
    available: props.facility.available ? true : false
})

const imagesForm = useForm({
    images: JSON.parse(props.facility.images)
})

const submit = () => {
    form.put(route('admin.facilities.update', props.facility.id), {
        onSuccess: () => {
            $q.notify('Facility Successfully Updated')
        }
    })
}

const submitImagesForm = () => {
    imagesForm.post(route('admin.facilities.update_images', props.facility.id), {
        onSuccess: () => {
            $q.notify('Cover Photo Updated')
            // coverPhotoForm.images = null
        }
    })
}

// const amenities = ref([])

// for(var i = 0; i < 10; i++) {
//     amenities.value.push(
//         {
//             id: i,
//             icon: 'style',
//             name: 'Amenity'
//         }
//     )
// }

// function addAmenity(amenity) {
//     // if(form.amenities.find((amenity))) {
//     //     form.amenities.push(amenity)
//     // }
//     if(!form.amenities.find((el) => el.id == amenity.id)) {
//         form.amenities.push(amenity)
//     }

// }

// function deleteAmenity(amenity) {
//     console.log(amenity)
//     console.log(form.amenities)
//     form.amenities = form.amenities.filter(a => a.id != amenity.id)
// }

const facilityImagesRef = ref(null)
const imgPreview = ref([])

const triggerFilePicker = () => {
    facilityImagesRef.value.pickFiles();
};

const onFileChange = (file) => {
    console.log(imagesForm.images)
    imgPreview.value.push(URL.createObjectURL(file))
    imagesForm.images.push(file)
    console.log(imagesForm.images)
    // imgPreview.value = URL.createObjectURL(file)
}

const editImages = ref(false)
const facilityImages = ref(JSON.parse(props.facility.images))

const handleRemoveImage = (img) => {
    imagesForm.images = imagesForm.images.filter(a => a !== img)
    facilityImages.value = facilityImages.value.filter(a => a !== img)
}

const deleteFacilityDialog = ref(false)
const deleteFacilityForm = useForm({})
const submitDeleteFacilityForm = () => {
    deleteFacilityForm.delete(route('admin.facilities.destroy', props.facility.id), {
        onSuccess: () => {
            $q.notify('Facility Deleted Successfully')
        }
    })
}
</script>

<template>
    
    <Head title="Edit Event" />
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
        <q-card bordered flat>
            <q-card-section style="position: sticky; top: 0; z-index: 99;" class="q-pa-none q-pt-md q-px-md bg-white">
                <div  class="row flex justify-center bg-white">
                    <Link :href="route('admin.facilities.index')">
                        <q-btn icon="arrow_back" flat class="absolute-top-left q-mt-md text-black" rounded :label="$q.screen.gt.sm ? 'Go Back' : ''" no-caps/>
                    </Link>
                    <div class="text-h6">Edit Facility</div>
                </div>
                <q-separator class="q-mt-md"/>
            </q-card-section>
            <q-card-section>
                    <div>
                        <div class="text-subtitle1 text-weight-bold items-center flex q-mb-md" style="position: relative">
                            Facility Images
                            <q-btn 
                                no-caps 
                                v-if="imgPreview.length > 0" 
                                color="primary"
                                :disable="imagesForm.processing"
                                :loading="imagesForm.processing"
                                @click="submitImagesForm()"
                                label="Save"
                                rounded 
                                unelevated
                                class="absolute-top-right"
                            />
                        </div>
                        
                        <div class="row q-col-gutter-sm">
                              
                            <div class="col-xl-2 col-lg-2 col-md-2 col-xs-4" v-for="img in facilityImages">
                                <q-card 
                                    style="height: 130px; width: 100%" 
                                    bordered 
                                >
                                    <q-img 
                                        :src="`/storage/${img}`" 
                                        style="width: 100%; height: 100%;" 
                                        fit="cover"
                                    />
                                </q-card>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-xs-4" v-if="imgPreview.length > 0" v-for="img in imgPreview">
                                <q-card 
                                    style="height: 130px; width: 100%" 
                                    bordered 
                                    @click="handleRemoveImage(img)"
                                >
                                    <q-img 
                                        :src="img" 
                                        style="width: 100%; height: 100%;" 
                                        fit="cover"
                                    />
                                </q-card>
                            </div>
                            <div class="col-xl-2 col-lg-2 col-md-2 col-xs-4">
                                <q-card 
                                    style="height: 130px; width: 100%" 
                                    bordered 
                                    :flat="!editImages"
                                    @click="triggerFilePicker"
                                    class="cursor-pointer"
                                >
                                        <q-btn 
                                            icon="add" 
                                            class="absolute-center" 
                                            label="Add" 
                                            no-caps 
                                            unelevated
                                        />
                                </q-card>
                            </div>
                        </div>
                        <q-file 
                            style="display: none;"
                            ref="facilityImagesRef"
                            @update:model-value="onFileChange"
                        />
                        <q-separator class="q-my-md" />
                        <p class="text-weight-bold text-subtitle1">Facility Details</p>
                    </div>
                    <div class="q-mx-sm q-mt-md">
                        <!-- <div>Event title</div>
                        <div>Be clear and descriptive with a title that tells people what your event is about.</div> -->
                        <q-input 
                            rounded 
                            outlined 
                            label="Name"
                            v-model="form.name" 
                            :error="form.errors.name ? true : false"
                            :error-message="form.errors.name"
                        />
                        <!-- <div>Description</div>
                        <div>Grab people's attention with a short description about your event. Attendees will see this at the top of your event page. (255 characters max)</div> -->
                        <q-input 
                            rounded 
                            outlined 
                            label="Description"
                            type="textarea"
                            v-model="form.description" 
                            :error="form.errors.description ? true : false"
                            :error-message="form.errors.description"
                        />
                        <q-input 
                            rounded 
                            outlined 
                            label="Ameneties"
                            type="textarea"
                            v-model="form.amenities" 
                            :error="form.errors.amenities ? true : false"
                            :error-message="form.errors.amenities"
                        />
                        <!-- <div>Date and location</div>    
                        <div>Date and time</div> -->
                        <!-- <div>Location</div>
                        <div> maybe a map or what></div> -->
                        <!-- <div>Capacity</div>
                        <p class="text-red text-h4">!!!</p>
                        <p class="text-red h6">inig ka edit ani kay kung greater than the current capacity ang bag o nga capacity mag create ug bag - o ticket or what</p>
                        <p class="text-red h6">unya what if less than ang bag-o nga capacity epang delete ang ticket,,, no??</p>
                        <div>capacity = amount of tickets u want to sell</div> -->
                        <!-- <q-input 
                            rounded 
                            outlined 
                            label="Price"
                            type="number" 
                            v-model="form.capacity" 
                            :error="form.errors.capacity ? true : false"
                            :error-message="form.errors.capacity"
                        /> -->
                        <!-- <div>Tickets</div>
                        <p class="text-red">unya kung e adjust pod ang price unya naa nay ni purchase nga ticket ma change pod ang total price naa sa dashboard</p>
                        <p class="text-red">like kung naay ni purchase tickets worth of 100 x 3 = 300</p>
                        <p class="text-red">unya kung e update ni ang price dire (ilisag 200) ma ilisan pod to ang price to 200 x 3 = 600</p>
                        <div>How much do you want to charge for tickets?</div> -->
                        <q-input 
                            rounded 
                            outlined 
                            label="Price"
                            type="number" 
                            v-model="form.price" 
                            :error="form.errors.price ? true : false"
                            :error-message="form.errors.price"
                            mask="#.##"
                            fill-mask="0"
                            reverse-fill-mask
                        />
                        
                        <!-- <div>TIckets per order</div> -->
                        <q-input 
                            rounded 
                            outlined
                            type="number" 
                            label="Guests"
                            v-model="form.guests" 
                            :error="form.errors.guests ? true : false"
                            :error-message="form.errors.guests"
                        />
                            
                        <q-checkbox v-model="form.available" label="Available" />
                        
                        <!-- <q-separator class="q-my-md" />
                        <p class="text-weight-bold text-h6">Facility Ameneties</p>
                        <div class="row q-col-gutter-md">
                            <div class="col-3 cursor-pointer" v-for="(amenity, i) in amenities" v-if="amenities">
                                <q-card  
                                    flat 
                                    bordered 
                                    @click="addAmenity(amenity)" 
                                    :class="form.amenities.find((el) => amenity.id == el.id) ? 'bg-primary text-white' : ''"
                                >
                                    <q-item>
                                        <q-item-section avatar>
                                            <q-icon :name="amenity.icon" />
                                        </q-item-section>
                                        <q-item-section>
                                            <q-item-label>
                                                {{ amenity.name }} {{ i }}
                                            </q-item-label>
                                        </q-item-section>
                                        <q-item-section side>
                                            <q-btn 
                                                icon="close" 
                                                v-if="form.amenities.find((el) => amenity.id == el.id)"
                                                round
                                                unelevated
                                                color="white"
                                                flat
                                                @click.stop="deleteAmenity(amenity)" />
                                        </q-item-section>
                                    </q-item>
                                </q-card>
                            </div>
                        </div> -->
                        <!-- <q-btn 
                            no-caps 
                            type="submit" 
                            color="primary" 
                            class="q-mr-sm full-width q-my-lg"
                            :loading="form.processing"
                            :disable="form.processing"
                        >
                            Save
                        </q-btn> -->
                    </div>
                    
                    
                    <!-- <div class="q-mx-xl q-mt-md">
                        <div>Frequently Asked Questions (Optional)</div>    
                        <div>Answer questions your attendees may have about the event, like parking, accessibility and refunds.</div>
                        <q-input rounded 
                        outlined label="Question"></q-input>
                        <q-input rounded 
                        outlined label="Answer" type="textarea"></q-input>
                        <q-btn class="full-width" color="primary" no-caps>Add question</q-btn>
                    </div> -->
                <!-- </q-form> -->
                <q-btn 
                        @click="submit"
                        no-caps 
                        rounded 
                        unelevated
                        color="primary" 
                        :loading="form.processing"
                        :disable="form.processing"
                        label="Update"
                    />
            </q-card-section>
        </q-card>
    </div>
    <q-dialog v-model="deleteFacilityDialog">
        <q-card>
            <q-card-section>
                Delete Facility?
            </q-card-section>
            <q-card-actions>
                <q-btn v-close-popup label="Cancel" />
                <q-btn 
                    label="Delete"
                    @click="submitDeleteFacilityForm"
                    :loading="deleteFacilityForm.processing"
                    :disable="deleteFacilityForm.processing"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

<style scoped>

.imageCard .deleteImageBtn {
    display: none;
}

.imageCard:hover .deleteImageBtn {
    display: block;
}

.imageCard:hover {  
    transform: scale(1.05);
    border: 1px solid red
    /* Semi-transparent black background */
}

</style>