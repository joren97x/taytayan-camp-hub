<script setup>

import { ref } from 'vue'
import FoodDescriptionDialog from './FoodDescriptionDialog.vue'
defineProps({ product: Object })

const dialog = ref(false)

</script>

<template>

    <q-card class="my-card" flat bordered>
        <div class="row">
            <div class="col-6">
                <q-card-section>
                    <div class="text-subtitle1"> {{ product.name }} </div>
                    <div class="text-regular">P{{ product.price }} </div>
                    <div class="text-caption text-grey">
                        {{ product.description }}
                    </div>
                </q-card-section>
            </div>
            <div class="col-6">
                <q-img height="170px" :src="`/storage/${product.photo}`"/>
            </div>
        </div>
        <q-btn round icon="add" size="md" @click="dialog = true" color="blue" class="absolute" style="bottom: 5px; right: 5px;"></q-btn>
    </q-card>

    <FoodDescriptionDialog 
        :dialog="dialog" 
        @close="dialog = false" 
        :product="product"
    />
    
</template>