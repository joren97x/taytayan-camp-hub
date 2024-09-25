<script setup>

import { ref } from 'vue'
import FoodDescriptionDialog from './FoodDescriptionDialog.vue'
defineProps({ product: Object })

const dialog = ref(false)

</script>

<template>

    <q-card
        flat
        bordered
    >
        <q-card-section class="q-pa-none">
            <q-img
                :src="`./storage/${product.photo}`"
                alt="product image"
                height="160px"
                fit="contain"
            >
                <!-- <div class="absolute-top-left bg-green-6 text-white text-caption">
                    #{{ i + 1 }} most liked
                </div> -->
            </q-img>
            <q-btn
                icon="add"
                round
                class="absolute-bottom-right q-mb-sm q-mr-sm"
                color="primary"
                @click="dialog = true"
            />
        </q-card-section>
        <q-card-section class="q-pa-sm">
            <!-- <span>hi</span> -->
            <div class="text-subtitle1 text-weight-regular ellipsis">{{ product.name }}</div>
            <div class="text-subtitle2 text-weight-regular">{{ product.price }}</div>
            <div class="text-caption ellipsis-2-lines">{{ product.description }}</div>
        </q-card-section>
    </q-card>

    <FoodDescriptionDialog :dialog="dialog" :product="product" @close="dialog = false" />

</template>