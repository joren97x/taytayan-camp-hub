<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import { date } from 'quasar'
import { useDrawerStore } from '@/Stores/DrawerStore'
import { parse, format } from 'date-fns'

defineOptions({ layout: AdminLayout })
const props = defineProps({ events: Object })

const drawerStore = useDrawerStore()
const filter = ref('all')
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

const formatTime = (time) => {
    return format(parse(time, 'HH:mm:ss', new Date()), 'h a');
}

const columns = [
    { name: 'event', align: 'center', label: 'Event', field: 'event', sortable: true },
    { name: 'tickets_sold', align: 'center', label: 'Sold', field: 'tickets_sold', sortable: true },
    { name: 'gross', align: 'center', label: 'Gross', field: 'gross', sortable: true },
    { name: 'status', align: 'center', label: 'Status', field: 'status', sortable: true },
    { name: 'actions', align: 'center', label: '', field: 'actions', sortable: true },
]
</script>

<template>
    <Head  title="Tickets" />
    <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
        <q-card bordered flat>
            <q-table
                class="my-sticky-header-column-table"
                flat
                :grid="$q.screen.lt.md"
                :rows="filteredEvents"
                :columns="columns"
                row-key="name"
            >
                <template v-slot:top>
                    <q-btn icon="menu" @click="drawerStore.drawer = true" flat dense class="lt-md q-mr-sm"/>
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
                
                <template v-slot:body-cell-event="props">
                    <q-td :props="props">
                        <Link :href="route('admin.events.dashboard', props.row.id)">
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
                        </Link>
                    </q-td>
                </template>
                <template v-slot:body-cell-tickets_sold="props">
                    <q-td :props="props">
                        {{ props.row.tickets_sold + ' / ' + props.row.capacity }}
                        <q-linear-progress :value="props.row.tickets_sold / props.row.capacity" class="q-mt-xs" size="5px" />
                    </q-td>
                </template>
                <template v-slot:body-cell-gross="props">
                    <q-td :props="props">
                        P{{ props.row.tickets_sold * props.row.admission_fee }}
                    </q-td>
                </template>
                <template v-slot:body-cell-actions="props">
                    <q-td :props="props">
                        <Link :href="route('admin.events.event_dashboard', props.row.id)">
                            <q-btn no-caps unelevated color="primary">View Tickets</q-btn>
                        </Link>
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
                                        Gross
                                    </div>
                                    {{ props.row.tickets_sold * props.row.admission_fee }}
                                </div>
                                <!-- <div class="col-xs-6 col-sm-6">
                                    <div class="text-caption text-grey">
                                        Status
                                    </div>
                                    {{ props.row.status }}
                                </div> -->
                                <div class="col-xs-12 col-sm-12 q-mt-sm">
                                    <!-- <div class="text-caption text-grey">
                                        Actions
                                    </div> -->
                                    <Link :href="route('admin.events.event_dashboard', props.row.id)" class="full-width">
                                        <q-btn label="View Tickets" no-caps color="primary" rounded unelevated class="full-width"/>
                                    </Link>
                                </div>
                            </div>
                        </q-card-section>
                    </q-card>
                </template>
            </q-table>
        </q-card>
    </div>
</template>

<style scoped>
a {
    text-decoration: none;
    color: black;
}
</style>