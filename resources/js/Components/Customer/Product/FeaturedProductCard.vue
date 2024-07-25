<script setup>

import { ref } from 'vue'
import FoodDescriptionDialog from './FoodDescriptionDialog.vue'
defineProps({ product: Object })

const dialog = ref(false)

</script>

<template>

    <q-card class="col-3 bg-white" flat >
        <q-img :src="`/storage/${product.photo}`" height="180px" />
        <q-card-section>
            <q-btn
                round
                color="primary"
                icon="add"
                class="absolute"
                @click="dialog = !dialog"
                size="md"
                style="top: 0; right: 12px; transform: translateY(-50%);"
            />

            <div class="row no-wrap items-center">
                <div class="col text-h6 ellipsis">
                    {{ product.name }}
                </div>
            </div>
            <div class="text-caption text-grey">
                {{ product.description }}
            </div>
        </q-card-section>
    </q-card>

    <FoodDescriptionDialog :dialog="dialog" :product="product" @close="dialog = false" />

</template>