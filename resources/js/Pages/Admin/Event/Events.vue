<script setup>

import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref } from 'vue'
import { useQuasar } from 'quasar'

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
    { name: 'tickets_sold', align: 'center', label: 'Tickets Sold', field: 'tickets_sold', sortable: true },
    // { name: 'gross', align: 'center', label: 'gross', field: 'gross', sortable: true },
    { name: 'status', align: 'center', label: 'Status', field: 'status', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

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
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <q-btn 
                            no-caps 
                            unelevated
                            @click="showDeleteEventDialog(props.row)"
                        >
                            Delete
                        </q-btn>
                        <Link :href="route('admin.events.edit', props.row.id)">
                            <q-btn class="q-ml-sm" no-caps color="primary">Edit</q-btn>
                        </Link>
                        <!-- <Link :href="route('admin.events.show', props.row.id)">
                            <q-btn class="q-ml-sm" no-caps color="primary">View Event</q-btn>
                        </Link> -->
                    </q-td>
                </template>
                <template v-slot:top>
                    <p class="text-h6 q-pt-md">Events</p>
                    <q-space />
                    <q-input filled dense label="Search..." debounce="300" color="primary" v-model="filter">
                        <template v-slot:append>
                            <q-icon name="search" />
                        </template>
                    </q-input>
                    <Link :href="route('admin.events.create')">
                        <q-btn class="q-ml-sm" no-caps color="primary">Create Event</q-btn>
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
    </div>
</template>