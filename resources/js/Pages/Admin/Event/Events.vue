<script setup>

import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { ref, computed } from 'vue'
import { useQuasar, date } from 'quasar'
import { useDrawerStore } from '@/Stores/DrawerStore'
import { parse, format } from 'date-fns'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    events: Object
})

const drawerStore = useDrawerStore()
const $q = useQuasar()
const filter = ref('all')
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

const formatTime = (time) => {
    return format(parse(time, 'HH:mm:ss', new Date()), 'h a');
}

const columns = [
    { name: 'event', label: 'Event', align: 'center', field: 'event', sortable: true },
    { name: 'tickets_sold', align: 'center', label: 'Tickets Sold', field: 'tickets_sold', sortable: true },
    // { name: 'gross', align: 'center', label: 'gross', field: 'gross', sortable: true },
    { name: 'status', align: 'center', label: 'Status', field: 'status', sortable: true },
    { name: 'actions', align: 'center', label: 'Actions', field: 'actions', sortable: true },
]

const showSearch = ref(false)
const searchTerm = ref('')

const filteredEvents = computed(() => {
  let filtered = props.events;
  // Apply status filter
  if (filter.value !== 'all') {
    filtered = filtered.filter(event => event.status === filter.value);
  }

  // Apply search term filter
  if (searchTerm.value) {
    filtered = filtered.filter(event => {
      const eventName = event.title.toLowerCase();
      const search = searchTerm.value.toLowerCase();

      return (
        eventName.includes(search)
      );
    });
  }

  return filtered;
});

const formatStatus = (status) => {
    const formatted = {
        all: 'All Events',
        event_ended: 'Event Ended',
        cancelled: 'Cancelled',
        on_sale: 'On Sale'
    };
    return formatted[status] || status;
}

const getStatusColor = (status) => {
    const colors = {
        cancelled: 'red-3',
        on_sale: 'green-3',
        event_ended: 'grey'
    }
    return colors[status]
}

</script>

<template>
    
    <Head title="Reviews" />
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
        <q-card borderd flat>
            <q-table
                class="my-sticky-header-column-table"
                flat
                :rows="filteredEvents"
                :columns="columns"
                row-key="name"
                :grid="$q.screen.lt.md"
            >
                <template v-slot:top>
                    <q-btn icon="menu" flat dense @click="drawerStore.drawer = true" class="lt-md q-mr-sm"/>
                    <div class="text-h6">Events</div>
                    <q-space />
                    <q-btn icon="search" class="q-mr-xs" round dense flat @click="showSearch = !showSearch"/>
                    <q-select 
                        :options="['all', 'on_sale', 'cancelled', 'event_ended']" 
                        v-model="filter"
                        outlined 
                        rounded
                        dense
                    />
                    <Link :href="route('admin.events.create')">
                        <q-btn class="q-ml-sm" no-caps color="primary" rounded unelevated label="Create Event"/>
                    </Link>
                    <div class="full-width q-mt-sm" v-if="showSearch">
                        <q-input
                            v-model="searchTerm"
                            rounded
                            outlined
                            dense
                            label="Search using name, email or facility name..."
                            debounce="300"
                            class="full-width"
                            color="primary"
                        >
                            <template v-slot:append>
                                <q-icon name="search" />
                            </template>
                        </q-input>
                    </div>
                </template>
                <template v-slot:body-cell-status="props">
                    <q-td :props="props">
                        <q-chip :color="getStatusColor(props.row.status)">{{ formatStatus(props.row.status) }}</q-chip>
                    </q-td>
                </template>
                <template v-slot:body-cell-event="props">
                    <q-td :props="props">
                        <q-item class="q-pa-none">
                            <q-item-section avatar class="items-center">
                                <div class="text-weight-bold text-secondary">{{ date.formatDate(props.row.date, 'MMM') }}</div>
                                <div>{{ date.formatDate(props.row.date, 'D') }}</div>
                            </q-item-section>
                            <q-item-section avatar>
                                <q-img :src="`/storage/${props.row.cover_photo}`" style="width: 90px; height: 70px;" class="rounded-borders"/>
                            </q-item-section>
                            <q-item-section class="text-start text-left">
                                <q-item-label>
                                    {{ props.row.title }}
                                </q-item-label>
                                <q-item-label caption>
                                    {{ date.formatDate(props.row.date, 'MMMM D, YYYY') }} at
                                    {{ formatTime(props.row.start_time) }}
                                </q-item-label>
                                <q-item-label caption>
                                    {{ props.row.location }}
                                </q-item-label>
                            </q-item-section>
                        </q-item>
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
                <template v-slot:item="props">
                    <q-card class="col-12 q-mb-md" bordered flat>
                        <q-card-section>
                            <div class="row q-col-gutter-x-md">
                                <div class="col-xs-12 col-sm-12">
                                    <div class="text-caption text-grey">
                                        Event
                                    </div>
                                    <q-item>
                                        <q-item-section avatar class="items-center">
                                            <div class="text-weight-bold text-secondary">{{ date.formatDate(props.row.date, 'MMM') }}</div>
                                            <div>{{ date.formatDate(props.row.date, 'D') }}</div>
                                        </q-item-section>
                                        <q-item-section avatar>
                                            <q-img :src="`/storage/${props.row.cover_photo}`" height="60px" width="60px" class="rounded-borders" />
                                        </q-item-section>
                                        <q-item-section class="items-start">
                                            <q-item-label>{{ props.row.title }}</q-item-label>
                                            <q-item-label caption>{{ date.formatDate(props.row.date, 'MMM D, YYYY') + ' at ' +  formatTime(props.row.start_time) }}</q-item-label>
                                        </q-item-section>
                                    </q-item>
                                </div>
                                <div class="col-xs-6 col-sm-6">
                                    <div class="text-caption text-grey">
                                        Sold
                                    </div>
                                    <q-linear-progress :value="props.row.tickets_sold / props.row.capacity" class="q-mt-xs" size="5px" />
                                    {{ props.row.tickets_sold + ' / ' + props.row.capacity }}
                                </div>
                                <div class="col-xs-6 col-sm-6">
                                    <div class="text-caption text-grey">
                                        Status
                                    </div>
                                    {{ props.row.status }}
                                </div>
                                <!-- <div class="col-xs-6 col-sm-6">
                                    <div class="text-caption text-grey">
                                        Status
                                    </div>
                                    {{ props.row.status }}
                                </div> -->
                                <div class="col-xs-12 col-sm-12 q-mt-sm justify-end flex">
                                    <!-- <div class="text-caption text-grey">
                                        Actions
                                    </div> -->
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
                                </div>
                            </div>
                        </q-card-section>
                    </q-card>
                </template>
            </q-table>
        </q-card>
        <q-dialog 
            v-model="deleteEventDialog"
            :maximized="$q.screen.lt.md"    
            transition-show="slide-up"
            transition-hide="slide-down"
            :position="$q.screen.lt.md ? 'bottom' : 'standard'"
        >
        <q-card :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
            <q-card-section class="row items-center q-pb-none">
                <q-icon name="warning" color="negative" size="32px" />
                <div class="text-h6 q-ml-md">Delete Event</div>
                <q-btn round icon="close" v-close-popup flat class="absolute-top-right q-mt-sm q-mr-sm"/>
            </q-card-section>
            <q-card-section>
                Are you sure you want to delete this event? All data will be permanently removed. This action cannot be undone.
                <q-item class="bg-negative text-white q-my-md q-pa-md rounded-borders">
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