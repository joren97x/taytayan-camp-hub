<script setup>

import { ref } from 'vue'
import FoodDescriptionDialog from './FoodDescriptionDialog.vue'
defineProps({ product: Object })

const dialog = ref(false)

</script>

<template>

    <q-card class="my-card" flat bordered>
        <q-card-section horizontal>
            <q-card-section>
                <div class="text-h5"> Title </div>
                <div class="text-regular">P60.00</div>
                <div class="text-caption text-grey">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                </div>
            </q-card-section>
            <q-img class="col-5" src="https://cdn.quasar.dev/img/parallax2.jpg">
            </q-img>
            <q-btn  round icon="add" size="md" @click="dialog = true" color="blue" class="absolute" style="bottom: 5px; right: 5px;"></q-btn>
        </q-card-section>
    </q-card>

    <FoodDescriptionDialog :dialog="dialog" @close="dialog = false" />
    
</template>