<script setup>

import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useQuasar } from 'quasar'
import { ref } from 'vue'


defineOptions({
    layout: AdminLayout
})

const $q = useQuasar()
const form = useForm({
    title: '',
    cover_photo: null,
    description: '',
    date: '2024/06/30',
    start_time: '06:00',
    location: '',
    capacity: 10,
    admission_fee: 100.00,
    min_ticket: 1,
    max_ticket: 5,
    faqs: []
})

const submit = () => {
    form.post(route('admin.events.store'), {
        onSuccess: () => {
            $q.notify('Event Successfully Added')
        }
    })
}

const eventPhotoRef = ref(null)
const imgPreview = ref('')

const triggerFilePicker = () => {
    eventPhotoRef.value.pickFiles();
};

const onFileChange = (file) => {
    imgPreview.value = URL.createObjectURL(file)
}

</script>

<template>
    
    <Head title="Create Event" />
    <div class="q-pa-md">
        <q-card>
            <q-card-section style="position: sticky; top: 0; z-index: 99;" class="q-pa-none q-pt-md q-px-md bg-white">
                <div  class="row flex justify-center bg-white">
                    <Link :href="route('admin.facilities.index')">
                        <q-btn icon="arrow_back" flat class="absolute-top-left q-ml-md q-mt-md " label="Go Back" no-caps/>
                    </Link>
                    <div class="text-h6">Create Event</div>
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
                    <!-- <div class="row justify-between" style="z-index: 400;">
                        <div class="text-h6 text-center col-12" style="position: relative">
                            Create Event
                        </div>
                    </div>
                    <q-separator class="q-my-md" /> -->
                    <div class="">
                        <p class="text-weight-bold text-h6">Event Cover Photo</p>
                        <div class=" q-mx-xl q-my-md rounded-borders" style="height: 50vh; position: relative; overflow: hidden;">
                            
                            <div class="justify-center text-subtitle1 items-center flex full-height bg-grey-4" v-if="!imgPreview">
                                <q-icon name="image" class="q-mr-md" size="lg"/>
                                Choose a photo
                            </div>
                            <div class="blurred-background " v-else :style="`background-image: url('${imgPreview}');`">
                            </div>
                            <!-- Foreground image (not blurred) -->
                            <q-img 
                                :src="imgPreview"
                                v-if="form.cover_photo"
                                class="rounded-borders content-wrapper" 
                                height="100%"
                                fit="contain"
                                style="position: relative; z-index: 2;" 
                            />
                        </div>
                        <q-item class="q-my-md" :style="form.errors.cover_photo ? 'border: 1px solid red' : ''">
                            <!-- <q-item-section avatar>
                                <q-img 
                                    v-if="form.cover_photo"
                                    :src="form.cover_photo == null ? '' : imgPreview" 
                                    style="width: 100px; height: 100px;"
                                />
                            </q-item-section> -->
                            <q-item-section>
                                <q-file 
                                    v-model="form.cover_photo"
                                    :error="form.errors.cover_photo ? true : false"
                                    :error-message="form.errors.cover_photo"
                                    style="display: none;"
                                    ref="eventPhotoRef"
                                    @update:model-value="onFileChange"
                                />
                                <q-item-label>Photos can help customers decide what to order and can increase sale.</q-item-label>
                                <q-item-label caption>File requirement: JPG, PNG</q-item-label>
                                <q-item-label class="text-red" caption>{{ form.errors.cover_photo ? form.errors.cover_photo : '' }}</q-item-label>
                                <q-item-label>
                                    <q-btn 
                                        no-caps color="primary" 
                                        v-if="form.cover_photo" 
                                        @click="triggerFilePicker"
                                    >
                                        Change
                                    </q-btn>
                                    <q-btn no-caps color="primary" v-else @click="triggerFilePicker">Add photo</q-btn>
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                        
                    </div>
<!--                     
                    <div class="q-mx-xl q-mt-md">
                        <div>Add cover photo</div>    
                        <div>Add photos to show what your event will be about. See examples</div>
                        <div class="full-width bg-grey-3" style="height: 200px">
                            <q-input 
                                type="file" 
                                filled 
                                v-model="form.cover_photo"
                                :error="form.errors.cover_photo ? true : false"
                                :error-message="form.errors.cover_photo"
                            />
                        </div>
                    </div> -->
                    <q-separator class="q-my-md" />
                    <div class="text-h6">Event Details</div>
                    <div class="q-mx-md q-mt-md">
                        <!-- <div>Event Overview</div>    
                        <div>Event title</div>
                        <div>Be clear and descriptive with a title that tells people what your event is about.</div> -->
                        <q-input 
                            filled 
                            label="Title"
                            v-model="form.title" 
                            :error="form.errors.title ? true : false"
                            :error-message="form.errors.title"
                        />
                        <!-- <div>Description</div>
                        <div>Grab people's attention with a short description about your event. Attendees will see this at the top of your event page. (255 characters max)</div> -->
                        <q-input 
                            filled 
                            label="Description"
                            v-model="form.description" 
                            :error="form.errors.description ? true : false"
                            :error-message="form.errors.description"
                        />
                        <!-- <div>Date and location</div>    
                        <div>Date and time</div> -->
                        <div class="row q-col-gutter-md">
                            <div class="col-3">
                                <q-input 
                                    filled 
                                    mask="date" 
                                    label="Date" 
                                    :rules="['date']"
                                    v-model="form.date" 
                                    :error="form.errors.date ? true : false"
                                    :error-message="form.errors.date"
                                >
                                    <template v-slot:append>
                                        <q-icon name="event" class="cursor-pointer">
                                            <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                                <q-date v-model="form.date">
                                                    <div class="row items-center justify-end">
                                                        <q-btn no-caps v-close-popup label="Close" color="primary" flat />
                                                    </div>
                                                </q-date>
                                            </q-popup-proxy>
                                        </q-icon>
                                    </template>
                                </q-input>
                            </div>
                            <div class="col-3">
                                <q-input 
                                    filled 
                                    mask="time" 
                                    label="Start Time" 
                                    :rules="['time']"
                                    v-model="form.start_time" 
                                    :error="form.errors.start_time ? true : false"
                                    :error-message="form.errors.start_time"
                                >
                                    <template v-slot:append>
                                        <q-icon name="access_time" class="cursor-pointer">
                                            <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                                <q-time v-model="form.start_time">
                                                    <div class="row items-center justify-end">
                                                        <q-btn no-caps v-close-popup label="Close" color="primary" flat />
                                                    </div>
                                                </q-time>
                                            </q-popup-proxy>
                                        </q-icon>
                                    </template>
                                </q-input>
                            </div>
                        </div>
                        <!-- <div>Location</div>
                        <div> maybe a map or what></div> -->
                        <q-input 
                            filled 
                            label="Location"
                            v-model="form.location" 
                            :error="form.errors.location ? true : false"
                            :error-message="form.errors.location"
                        />
                        <!-- <div>Capacity</div>
                        <div>capacity = amount of tickets u want to sell</div> -->
                        <q-input 
                            filled 
                            label="Capacity"
                            type="number" 
                            v-model="form.capacity" 
                            :error="form.errors.capacity ? true : false"
                            :error-message="form.errors.capacity"
                        />
                        <!-- <div>Tickets</div>
                        <div>How much do you want to charge for tickets?</div> -->
                        <q-input 
                            filled 
                            label="Price"
                            type="number" 
                            v-model="form.admission_fee" 
                            :error="form.errors.admission_fee ? true : false"
                            :error-message="form.errors.admission_fee"
                            mask="#.##"
                            fill-mask="0"
                            reverse-fill-mask
                        />
                        <div>TIckets per order</div>
                        <div class="row q-col-gutter-md">
                            <div class="col-4">
                                <q-input 
                                    filled
                                    type="number" 
                                    label="Minimum"
                                    v-model="form.min_ticket" 
                                    :error="form.errors.min_ticket ? true : false"
                                    :error-message="form.errors.min_ticket"
                                />
                            </div>
                            <div class="col-4">
                                <q-input 
                                    filled
                                    type="number" 
                                    label="Maximum"
                                    v-model="form.max_ticket" 
                                    :error="form.errors.max_ticket ? true : false"
                                    :error-message="form.errors.max_ticket"
                                />
                            </div>
                        </div>
                        <!-- <q-btn 
                            no-caps 
                            type="submit" 
                            color="primary" 
                            class="q-mr-sm full-width"
                            :loading="form.processing"
                            :disable="form.processing"
                        >
                            Create
                        </q-btn> -->
                    </div>
                    
                    <!-- <div class="q-mx-xl q-mt-md">
                        <div>Frequently Asked Questions (Optional)</div>    
                        <div>Answer questions your attendees may have about the event, like parking, accessibility and refunds.</div>
                        <q-input filled label="Question"></q-input>
                        <q-input filled label="Answer" type="textarea"></q-input>
                        <q-btn class="full-width" color="primary" no-caps>Add question</q-btn>
                    </div> -->
                </q-form>
            </q-card-section>
        </q-card>
    </div>
</template>

<style scoped>

/* CSS IS HARD FRRR(i used chatGPT XD) */
/* Blurred background */
.blurred-background {
   /* Background image you want to blur */
  background-size: cover;
  background-position: center;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  filter: blur(8px); /* Apply blur effect */
  z-index: 1; /* Keep it behind the foreground content */
}

/* Foreground image (clear, no blur) */
.content-wrapper {
  position: relative;
  z-index: 2; /* Ensure it's on top of the blurred background */
}

</style>