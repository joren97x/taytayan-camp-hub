<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { date } from 'quasar'
import { parse, format, formatDistanceToNow } from 'date-fns'

const props = defineProps({ event: Object })

const page = usePage()
const url = page.url
function shareOnFacebook() {
    alert('not working rn')
    // uncomment code below if deployed
    // const facebookShareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
    // console.log(facebookShareUrl)
    // window.open(facebookShareUrl, '_blank');
}

const formattedTime = format(parse(props.event.start_time, 'HH:mm:ss', new Date()), 'h a');

</script>

<template>
    <div class="event-card">
        <Link :href="route('customer.events.show', event.id)">
            <q-card bordered flat class="rounded-borders">
                <q-card-section :horizontal="$q.screen.gt.sm" class="q-pa-none">
                    <q-card-section :class="`text-center ${$q.screen.lt.md ? 'q-pa-none' : 'q-px-md'}`">
                        <div class="full-width  rounded-borders" style="height: 150px; position: relative; overflow: hidden;">
                            <div class="blurred-background" :style="`background-image: url('/storage/${event.cover_photo}');`"></div>
                            <q-img 
                                :src="`/storage/${event.cover_photo}`"
                                class="rounded-borders content-wrapper" 
                                height="100%"
                                :width="$q.screen.gt.sm ? '220px' : '100%'" 
                                fit="contain"
                                style="position: relative; z-index: 2;" 
                            />
                        </div>
                    </q-card-section>
                    <q-card-section class="q-pa-md full-width">
                        <q-item class="q-pa-none">
                            <q-item-section>
                                <q-item-label class="text-h6">
                                    <slot name="badge" />
                                    {{ event.title }}
                                </q-item-label>
                                <q-item-label caption class="">
                                    {{ date.formatDate(event.date, 'MMMM D, YYYY') }}
                                    at {{ formattedTime }}
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                        <p class="q-mt-md ellipsis-2-lines">
                            {{ event.description }}
                        </p>
                        <!-- <div class="absolute-bottom-right q-mb-md q-mr-md gt-sm">
                            <q-btn @click="shareOnFacebook" rounded  label="Share" no-caps icon="share" unelevated class="q-mr-xs"/>
                            <Link :href="route('customer.events.show', event.id)">
                                <q-btn unelevated no-caps color="primary" label="Button" rounded />
                            </Link>
                        </div> -->
                    </q-card-section> 
                    <!-- <q-card-actions class="justify-end lt-md">
                        <q-btn @click="shareOnFacebook" rounded  label="Share" no-caps icon="share" unelevated class="q-mr-xs"/>
                        <Link :href="route('customer.events.show', event.id)">
                            <q-btn unelevated no-caps color="primary" label="Button" rounded />
                        </Link>
                    </q-card-actions> -->
                </q-card-section>
            </q-card>
        </Link>
    </div>
</template>

<style scoped>

.event-card:hover .q-card {
    border-color: var(--q-primary)
}

a {
    color: black;
    text-decoration: none;
}

/* .q-card, .event-card, .q-img {
    border-radius: 15px;
} */

.blurred-background {
   /* Background image you want to blur */
  background-size: cover;
  background-position: center;
  position: absolute;
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