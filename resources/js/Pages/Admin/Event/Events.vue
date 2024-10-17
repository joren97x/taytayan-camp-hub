<script setup>

import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref } from 'vue'
import { useQuasar, date } from 'quasar'

defineOptions({
    layout: AdminLayout
})

defineProps({
    events: Object
})

const $q = useQuasar()
const filter = ref('')
const deleteEventForm = useForm({
    event: null
})

const deleteEventDialog = ref(false)
const deleteEvent = () => {
    deleteEventForm.delete(route('admin.events.destroy', deleteEventForm.event.id), {
        onSuccess: () => {
            deleteEventDialog.value = false
            $q.notify('Event Deleted')
        }
    })
}

function showDeleteEventDialog(event) {
    deleteEventForm.event = event
    deleteEventDialog.value = true
}

const columns = [
    { name: 'event', label: 'Event', align: 'center', field: 'event', sortable: true },
    { name: 'date', label: 'Date', align: 'center', field: 'date', sortable: true },
    { name: 'tickets_sold', align: 'center', label: 'Tickets Sold', field: 'tickets_sold', sortable: true },
    // { name: 'gross', align: 'center', label: 'gross', field: 'gross', sortable: true },
    { name: 'status', align: 'center', label: 'Status', field: 'status', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

const formatTime = (timeString) => {
    const dateObj = new Date(`1970-01-01T${timeString}Z`);
    return date.formatDate(dateObj, 'h:mm A'); // 12-hour format with AM/PM
}

</script>

<template>
    
    <Head title="Reviews" />
    <div class="q-pa-md">
        <q-card borderd flat>
            <q-table
                class="my-sticky-header-column-table"
                flat
                :rows="events"
                :columns="columns"
                row-key="name"
                :filter="filter"
            >
                <template v-slot:body-cell-event="props">
                    <q-td :props="props">
                        <q-item class="q-pa-none">
                            <q-item-section avatar>
                                <q-img :src="`/storage/${props.row.cover_photo}`" style="width: 50px; height: 50px;" />
                            </q-item-section>
                            <q-item-section class="text-start text-left">
                                <q-item-label>
                                    {{ props.row.title }}
                                </q-item-label>
                                <q-item-label caption>
                                    {{ props.row.date }}
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                    </q-td>
                </template>
                <template v-slot:body-cell-date="props">
                    <q-td :props="props">
                        {{ date.formatDate(props.row.date, 'MMMM D, YYYY') }} at
                        {{ formatTime(props.row.start_time) }}
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <Link :href="route('admin.events.edit', props.row.id)">
                            <q-btn class="q-ml-sm" no-caps flat>Edit</q-btn>
                        </Link>
                        <q-btn 
                            no-caps 
                            unelevated
                            color="negative"
                            flat
                            @click="showDeleteEventDialog(props.row)"
                        >
                            Delete
                        </q-btn>
                        <!-- <Link :href="route('admin.events.show', props.row.id)">
                            <q-btn class="q-ml-sm" no-caps color="primary">View Event</q-btn>
                        </Link> -->
                    </q-td>
                </template>
                <template v-slot:top>
                    <p class="text-h6 q-pt-md">Events</p>
                    <q-space />
                    <q-input outlined dense label="Search..." rounded debounce="300" color="primary" v-model="filter">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                    <Link :href="route('admin.events.create')">
                        <q-btn class="q-ml-sm" no-caps color="primary" rounded unelevated label="Create Event"/>
                    </Link>
                </template>
            </q-table>
        </q-card>
        <q-dialog v-model="deleteEventDialog">
            <q-card>
                <q-card-section>
                    <p>Delete event</p>
                </q-card-section>
                <q-card-actions>
                    <q-space/>
                    <q-btn no-caps v-close-popup>Cancel</q-btn>
                    <q-btn 
                        no-caps
                        :loading="deleteEventForm.processing"
                        :disable="deleteEventForm.processing"
                        @click="deleteEvent"
                    >
                        Delete
                    </q-btn>
                </q-card-actions>
            </q-card>
        </q-dialog>
        <q-dialog 
        v-model="deleteEventDialog"
        :maximized="$q.screen.lt.md"    
        transition-show="slide-up"
        transition-hide="slide-down"
    >
        <q-card :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
            <q-card-section class="row items-center q-pb-none">
                <q-icon name="warning" color="negative" size="32px" />
                <div class="text-h6 q-ml-md">Delete Event</div>
                <q-btn round icon="close" v-close-popup flat class="absolute-top-right q-mt-sm q-mr-sm"/>
            </q-card-section>
            <q-card-section>
                Are you sure you want to delete this event? All data will be permanently removed. This action cannot be undone.
                <q-item class="bg-negative text-white q-my-md q-pa-md">
                    <q-item-section avatar>
                        <q-img :src="`/storage/${deleteEventForm.event.cover_photo}`" height="80px" width="80px"></q-img>
                    </q-item-section>
                    <q-item-section>
                        <q-item-label class="text-weight-bold text-subtitle1">{{ deleteEventForm.event.title }}</q-item-label>
                        <q-item-label class="">{{ deleteEventForm.event.description }}</q-item-label>
                    </q-item-section>
                </q-item>
            </q-card-section>
            <q-card-actions class="justify-end">
                <q-btn v-close-popup flat rounded no-caps label="Cancel"/>
                <q-btn 
                    :disable="deleteEventForm.processing"
                    :loading="deleteEventForm.processing"
                    @click="deleteEvent"
                    no-caps
                    rounded 
                    color="negative"
                    unelevated
                    label="Delete"    
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
    </div>
</template>