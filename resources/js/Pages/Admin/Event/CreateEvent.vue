<script setup>

import { Head, Link, useForm } from '@inertiajs/vue3'
import EventLayout from '@/Layouts/EventLayout.vue'
import { useQuasar } from 'quasar'


defineOptions({
    layout: EventLayout
})

const $q = useQuasar()
const form = useForm({
    title: '',
    cover_photo: null,
    summary: '',
    date: null,
    location: '',
    faqs: []
})

const submit = () => {
    form.post(route('admin.events.store'), {
        onFinish: () => form.reset('title'),
        onSuccess: () => {
            $q.notify('Event Successfully Added')
        }
    })
}

</script>

<template>
    
    <Head title="Reviews" />
    <div class="q-pa-md">
        <q-form @submit="submit">
            <div class="row">
                <q-btn icon="arrow_back" flat round></q-btn>
                <span class="text-h6 q-mt-xs q-ml-sm">Create Event</span>
                <q-space/>
                <q-btn 
                    no-caps 
                    type="submit" 
                    color="primary" 
                    class="q-mr-sm"
                    :loading="form.processing"
                    :disable="form.processing"
                >
                    Save
                </q-btn>
            </div>
            <q-separator class="q-my-md" />
            <div>Build Your Event Page</div>
            <div>Add all of your event details and let attendees know what to expect</div>
            <div class="q-mx-xl q-mt-md">
                <div>Add cover photo</div>    
                <div>Add photos to show what your event will be about. See examples</div>
                <div class="full-width bg-grey" style="height: 200px">
                </div>
            </div>
            <div class="q-mx-xl q-mt-md">
                <div>Event Overview</div>    
                <div>Event title</div>
                <div>Be clear and descriptive with a title that tells people what your event is about.</div>
                <q-input filled label="Title"></q-input>
                <div>Summary</div>
                <div>Grab people's attention with a short description about your event. Attendees will see this at the top of your event page. (255 characters max)</div>
                <q-input filled label="Summary"></q-input>
                <div>Tickets</div>
                <div>How much do you want to charge for tickets?</div>
                <q-input filled label="Price"></q-input>
                <div>How many tickets are you willing to sell.</div>
                <q-input filled label="Quantity"></q-input>
                <div>TIckets per order</div>
                <div class="row q-col-gutter-md">
                    <div class="col-4">
                        <q-input filled label="Price"></q-input>
                    </div>
                    <div class="col-4">
                        <q-input filled label="Price"></q-input>
                    </div>
                </div>
            </div>
            <div class="q-mx-xl q-mt-md">
                <div>Date and location</div>    
                <div>Date and time</div>
                <q-input filled label="Price"></q-input>
                <div>Location</div>
                <div> maybe a map or what></div>
            </div>
            <div class="q-mx-xl q-mt-md">
                <div>Frequently Asked Questions</div>    
                <div>Answer questions your attendees may have about the event, like parking, accessibility and refunds.</div>
                <q-input filled label="Question"></q-input>
                <q-input filled label="Answer" type="textarea"></q-input>
                <q-btn class="full-width" color="primary" no-caps>Add question</q-btn>
            </div>
        </q-form>
    </div>
</template>