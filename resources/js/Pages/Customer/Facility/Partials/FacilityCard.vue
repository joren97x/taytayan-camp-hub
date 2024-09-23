<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({ facility: Object })

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
const slide = ref(0)

</script>

<template>
    <div class="facility-card">
        <q-card bordered flat class="rounded-borders">
            <q-card-section class="q-pa-none">
                <q-carousel
                    v-model="slide"
                    transition-prev="slide-right"
                    transition-next="slide-left"
                    animated
                    control-type="regular"
                    swipeable
                    control-color="white"
                    control-text-color="black"
                    navigation
                    arrows
                    height="280px"
                >
                    <q-carousel-slide id="carousel-container" :name="index" class="q-pa-none bg-red" v-for="(image, index) in images">
                        <q-img 
                            class="fit"
                            :src="`/storage/${image}`"
                        />
                    </q-carousel-slide>
                    
                    <template v-slot:navigation-icon="{ active, btnProps, onClick }">
                        <q-btn v-if="active" size="5px" :icon="btnProps.icon" color="primary" flat round dense @click="onClick" />
                        <q-btn v-else size="5px" :icon="btnProps.icon" color="grey" flat round dense @click="onClick" />
                    </template>
                </q-carousel>
                <!-- <q-img 
                    height="200px"
                    width="100%"
                    src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/45/Endicott_College_Stoneridge_Hall_empty_dorm_room.jpg/800px-Endicott_College_Stoneridge_Hall_empty_dorm_room.jpg"
                ></q-img> -->
            </q-card-section>
            <q-card-section class="q-py-xs q-px-md">
                <div class="text-subtitle1">{{ facility.name }}</div>
                <div>{{ facility.amenities }}</div>
                <div>P{{ facility.price }}</div>
            </q-card-section>
        </q-card>
    </div>
</template>

<style scoped>

.facility-card:hover {
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
    border-color: #4CAF50;
    /* border: 1px solid #4CAF50; */
}

.q-card, .facility-card, .q-img, #carousel-container {
    border-radius: 15px;
}

a {
    text-decoration: none;
}
</style>