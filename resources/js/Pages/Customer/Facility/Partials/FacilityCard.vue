<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({ facility: Object })
const slide = ref(0)
const page = usePage()
const url = page.url
const images = JSON.parse(props.facility.images)
function shareOnFacebook() {
    alert('not working rn')
    // uncomment code below if deployed
    // const facebookShareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
    // console.log(facebookShareUrl)
    // window.open(facebookShareUrl, '_blank');
}

</script>

<template>
    <div class="facility-card">
        <Link :href="route('customer.facilities.show', facility.id)">
            <q-card bordered flat class="rounded-borders">
                <q-card-section class="q-pa-none">
                    <q-carousel
                        v-model="slide"
                        transition-prev="slide-right"
                        transition-next="slide-left"
                        animated
                        swipeable
                        control-color="white"
                        control-text-color="black"
                        height="280px"
                        class="rounded-borders"
                        ref="carousel"
                    >
                        <q-carousel-slide id="carousel-container" :name="index" class="q-pa-none" v-for="(image, index) in images">
                            <q-img  class="fit" :src="`/storage/${image}`" />
                        </q-carousel-slide>
                        <template v-slot:control>
                            <q-carousel-control
                                position="left"
                                :offset="[6, 18]"
                                class="q-gutter-xs items-center flex"
                            >
                                <q-btn
                                    push round dense color="white" text-color="black" icon="chevron_left"
                                    @click.prevent="$refs.carousel.previous()"
                                    v-if="slide != 0"
                                />
                               
                            </q-carousel-control>
                            <q-carousel-control
                                position="right"
                                :offset="[6, 18]"
                                class="q-gutter-xs items-center flex"
                            >
                                <q-btn
                                    push round dense color="white" text-color="black" icon="chevron_right"
                                    @click.prevent="$refs.carousel.next()"
                                    v-if="slide != images.length-1"
                                />
                            </q-carousel-control>
                        </template>
                        <!-- <template v-slot:navigation-icon="{ active, btnProps, onClick }">
                            <q-btn v-if="active" size="5px" :icon="btnProps.icon" color="primary" flat round dense @click.stop="onClick" />
                            <q-btn v-else size="5px" :icon="btnProps.icon" color="grey" flat round dense @click.stop="onClick" />
                        </template> -->
                    </q-carousel>
                </q-card-section>
                <Link :href="route('customer.facilities.show', facility.id)">
                    <q-card-section class="q-py-xs q-px-md">
                        <div class="text-h6 ellipsis q-mr-xl">{{ facility.name }}</div>
                        <div class="ellipsis-2-lines">{{ facility.description }}</div>
                        <div>P{{ facility.price }}</div>
                        <div class="absolute-top-right q-mt-sm q-mr-sm">
                            <q-icon name="star" color="orange" size="xs"/> {{ parseFloat(facility.average_rating).toFixed(2) }}
                        </div>
                    </q-card-section>
                </Link>
            </q-card>
        </Link>
    </div>
    <!-- <q-card>
        <q-item>
            <q-item-section>
                <q-img :src="`/storage/${images[0]}`" height="200"></q-img>
            </q-item-section>
            <q-item-section>
                <q-item-label>{{ facility.name }}</q-item-label>
                <q-item-label caption>{{ facility.name }}</q-item-label>
                <q-item-label>{{ facility.price }}</q-item-label>
            </q-item-section>
            <q-item-section side class="q-mt-lg">
                <q-btn>Book</q-btn>
            </q-item-section>
        </q-item>
    </q-card> -->
</template>

<style scoped>

.facility-card:hover .q-card {
    border-color: var(--q-primary)
}

.q-card, .facility-card, .q-img, #carousel-container {
    border-radius: 15px;
}

a {
    text-decoration: none;
    color: black;
}
</style>