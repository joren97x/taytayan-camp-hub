<script setup>
import { Link, usePage } from '@inertiajs/vue3'
defineProps({ event: Object })

const page = usePage()
const url = page.url
function shareOnFacebook() {
    alert('not working rn')
    // uncomment code below if deployed
    // const facebookShareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
    // console.log(facebookShareUrl)
    // window.open(facebookShareUrl, '_blank');
}
</script>

<template>
    <div class="event-card">
        <q-card bordered flat class="rounded-borders">
            <q-card-section horizontal>
                <q-card-section class="text-center q-pa-none">
                    <q-img 
                        fill="cover" 
                        height="200px" 
                        width="220px" 
                        :src="`/storage/${event.cover_photo}`"
                    />
                </q-card-section>
                <q-card-section class="q-pa-md">
                    <q-item  class="q-pa-none">
                        <q-item-section>
                            <q-item-label class="text-h6">{{ event.title }}</q-item-label>
                            <q-item-label caption class="">
                                Wednesday, August 28, 2024
                                {{ event.date }}
                            </q-item-label>
                        </q-item-section>
                    </q-item>
                    <!-- <p class="q-mt-md ellipsis-2-lines">Let's GO DISCO sa OLANGO this Wednesday hello annyeong 🍻🍾💃🕺</p> -->
                    <p class="q-mt-md ellipsis-2-lines">
                        {{ event.description }}
                    </p>
                    <!-- {{ event }} -->
                    
                    <div class="absolute-bottom-right q-mb-md q-mr-md">
                        <q-btn @click="shareOnFacebook" label="Share" no-caps icon="share" unelevated class="q-mr-xs"/>
                        <Link :href="route('customer.events.show', event.id)">
                            <q-btn icon="delete" no-caps color="primary" label="Button" rounded />
                        </Link>
                    </div>
                </q-card-section> 
                
            </q-card-section>
        </q-card>
    </div>
</template>

<style scoped>

.event-card:hover {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    border-color: #4CAF50;
    /* border: 1px solid #4CAF50; */
}


.q-card, .event-card, .q-img {
    border-radius: 15px;
}

</style>