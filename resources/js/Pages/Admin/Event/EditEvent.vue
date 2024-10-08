<script setup>

import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useQuasar } from 'quasar'
import { ref } from 'vue'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    event: Object,
    event_statuses: Object
})

const $q = useQuasar()
const form = useForm({
    title: props.event.title,
    description: props.event.description,
    date: props.event.date,
    start_time: props.event.start_time,
    location: props.event.location,
    capacity: props.event.capacity,
    admission_fee: props.event.admission_fee,
    min_ticket: props.event.min_ticket,
    max_ticket: props.event.max_ticket,
    faqs: []
})

const coverPhotoForm = useForm({
    cover_photo: null
})

const submit = () => {
    form.put(route('admin.events.update', props.event.id), {
        onSuccess: () => {
            $q.notify('Event Successfully Updated')
        }
    })
}

const submitCoverPhotoForm = () => {
    coverPhotoForm.post(route('admin.events.update_cover_photo', props.event.id), {
        onSuccess: () => {
            $q.notify('Cover Photo Updated')
            coverPhotoForm.cover_photo = null
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

const statusForm = useForm({
    status: props.event.status
})
const changeStatusDialog = ref(false)
const submitStatusForm = () => {
    statusForm.patch(route('admin.events.change_status', props.event.id), {
        onSuccess: ()=> {
            changeStatusDialog.value = false
            $q.notify('Event Status Updated')
        }
    })
}
</script>

<template>
    
    <Head title="Edit Event" />
    <div class="q-pa-md">
        <q-card>
            <q-card-section style="position: sticky; top: 0; z-index: 99;" class="q-pa-none q-pt-md q-px-md bg-white">
                <div class="row flex justify-center bg-white">
                    <Link :href="route('admin.events.index')">
                        <q-btn icon="arrow_back" flat class="absolute-top-left q-ml-md q-mt-md " label="Go Back" no-caps/>
                    </Link>
                    <div class="text-h6">Edit Event</div>
                    <div class="q-mr-md q-mt-md absolute-top-right">
                        <q-btn 
                            icon="edit_square" 
                            outline
                            class="q-mr-sm"
                            color="primary"
                            @click="changeStatusDialog = true" 
                            :label="event.status" 
                            no-caps 
                        />
                        
                    </div>
                </div>
                <q-separator class="q-mt-md"/>
            </q-card-section>
            <q-card-section>
                <!-- <q-form @submit="submit"> -->
                    <!-- <div class="row justify-between" style="z-index: 400;">
                        <div class="text-h6 text-center col-12" style="position: relative">
                            Edit Event
                            <Link :href="route('admin.events.index')">
                                <q-btn icon="arrow_back" flat class="absolute-left" label="Go Back" no-caps  />
                            </Link>
                            <q-btn icon="edit_square" outline @click="changeStatusDialog = true"  class="absolute-right" :label="event.status" no-caps  />
                        </div>
                    </div>
                    <q-separator class="q-my-md" /> -->
                    <div class="q-mt-md">
                        <p class="text-weight-bold text-h6">Event Cover Photo</p>
                        <q-item class="q-my-md">
                            <q-item-section avatar>
                                <q-img 
                                    :src="coverPhotoForm.cover_photo ? imgPreview : `/storage/${event.cover_photo}`" 
                                    style="width: 100px; height: 100px;" 
                                />
                            </q-item-section>
                            <q-item-section>
                                <q-file 
                                    v-model="coverPhotoForm.cover_photo"
                                    :error="coverPhotoForm.errors.cover_photo ? true : false"
                                    :error-message="coverPhotoForm.errors.cover_photo"
                                    style="display: none;"
                                    ref="eventPhotoRef"
                                    @update:model-value="onFileChange"
                                />
                                <q-item-label>Photos can help customers decide what to order and can increase sale.</q-item-label>
                                <q-item-label caption>File requirement: JPG, PNG</q-item-label>
                                <q-item-label>
                                    <q-btn 
                                        no-caps color="primary" 
                                        v-if="coverPhotoForm.cover_photo" 
                                        @click="submitCoverPhotoForm"
                                        :loading="coverPhotoForm.processing"
                                        :disable="coverPhotoForm.processing"
                                        label="Update"
                                    />
                                    <q-btn no-caps color="primary" v-else @click="triggerFilePicker" label="Change Photo"/>
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                    </div>
                    <q-separator class="q-my-md" />
                    <p class="text-weight-bold text-h6">Event Details</p>
                    <div class="q-mx-sm q-mt-md">
                        <!-- <div>Event title</div>
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
                        <p class="text-red text-h4">!!!</p>
                        <p class="text-red h6">inig ka edit ani kay kung greater than the current capacity ang bag o nga capacity mag create ug bag - o ticket or what</p>
                        <p class="text-red h6">unya what if less than ang bag-o nga capacity epang delete ang ticket,,, no??</p>
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
                        <p class="text-red">unya kung e adjust pod ang price unya naa nay ni purchase nga ticket ma change pod ang total price naa sa dashboard</p>
                        <p class="text-red">like kung naay ni purchase tickets worth of 100 x 3 = 300</p>
                        <p class="text-red">unya kung e update ni ang price dire (ilisag 200) ma ilisan pod to ang price to 200 x 3 = 600</p>
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
                        <q-btn 
                            @click="submit"
                            no-caps 
                            color="primary" 
                            :loading="form.processing"
                            :disable="form.processing"
                            label="Update"
                        />
                        <!-- <q-btn 
                            no-caps 
                            type="submit" 
                            color="primary" 
                            class="q-mr-sm full-width"
                            :loading="form.processing"
                            :disable="form.processing"
                        >
                            Update
                        </q-btn> -->
                    </div>
                    
                    <!-- <div class="q-mx-xl q-mt-md">
                        <div>Frequently Asked Questions (Optional)</div>    
                        <div>Answer questions your attendees may have about the event, like parking, accessibility and refunds.</div>
                        <q-input filled label="Question"></q-input>
                        <q-input filled label="Answer" type="textarea"></q-input>
                        <q-btn class="full-width" color="primary" no-caps>Add question</q-btn>
                    </div> -->
                <!-- </q-form> -->
            </q-card-section>
        </q-card>
    </div>
    <q-dialog 
        transition-show="slide-up"
        transition-hide="slide-down"
        :maximized="$q.screen.lt.md"
        v-model="changeStatusDialog"
    >
        <q-card>
            <q-card-section>
                <div class="text-h6">
                    Change Status
                </div>
                <q-btn icon="close" round v-close-popup class="absolute-top-right q-mt-sm q-mr-sm" flat/>
                <div>
                    Choose a status for your event. This status affects your tickets wherever they are sold online.
                </div>
                <q-select label="Status" class="q-mt-md" :options="event_statuses" v-model="statusForm.status" filled/>
            </q-card-section>
            <q-card-actions class="justify-end">
                <q-btn label="Cancel" no-caps flat v-close-popup />
                <q-btn 
                    label="Submit" 
                    no-caps
                    color="primary" 
                    @click="submitStatusForm"
                    :loading="statusForm.processing"
                    :disable="statusForm.processing"
                    unelevated 
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

<style scoped>
a {
    text-decoration: none;
    color: black
}
</style>