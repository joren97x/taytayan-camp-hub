<script setup>

import { ref } from 'vue'
import FoodDescriptionDialog from './FoodDescriptionDialog.vue'
defineProps({ product: Object })

const dialog = ref(false)

</script>

<template>

    <q-card class="product-card cursor-pointer" flat bordered @click="dialog = true" >
        <div class="row">
            <div class="col-6">
                <q-card-section>
                    <div class="text-subtitle1 ellipsis"> {{ product.name }} </div>
                    <div class="text-regular">₱{{ parseFloat(product.price).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</div>
                    <div class="text-caption text-grey ellipsis-3-lines">
                        {{ product.description }}
                    </div>
                </q-card-section>
            </div>
            <div class="col-6">
                <q-img height="170px" fit="contain" :src="`/storage/${product.photo}`">
                    
                </q-img>
                <!-- <q-btn>hllo</q-btn> -->
                <q-btn 
                    round 
                    icon="add" 
                    @click="dialog = true" 
                    color="primary" 
                    class="absolute-bottom-right q-mb-sm q-mr-sm" 
                >
                </q-btn>
            </div>
        </div>
    </q-card>

    <FoodDescriptionDialog 
        :dialog="dialog" 
        @close="dialog = false" 
        :product="product"
    />
    
</template>

<style scoped>

.product-card:hover {
    border-color: var(--q-primary)
}

</style>