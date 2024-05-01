<script setup>

import { ref } from 'vue'
import FoodDescriptionDialog from './FoodDescriptionDialog.vue'
defineProps({ product: Object })

const dialog = ref(false)

</script>

<template>

    <q-card class="col-3" flat>
        <q-img src="https://cdn.quasar.dev/img/chicken-salad.jpg" />

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
                    Cafe Basilico
                </div>
            </div>
            <div class="text-caption text-grey">
                Small plates, salads & sandwiches in an intimate setting.
            </div>
        </q-card-section>
    </q-card>

    <FoodDescriptionDialog :dialog="dialog" @close="dialog = false" />

</template>