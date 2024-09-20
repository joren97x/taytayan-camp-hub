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
                    <div class="text-subtitle1 ellipsis"> {{ product.name }} </div>
                    <div class="text-regular">P{{ product.price }} </div>
                    <div class="text-caption text-grey ellipsis-2-lines">
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

.my-card:hover {
    transform: scale(1.05);
  
  /* Increase box shadow to give a lifted effect */
  /* box-shadow: 100 100px 200px rgba(0, 0, 0, 0.911); */
  /* border: 1px solid blue; */
  /* Optional: change background color on hover */
  background-color: red;
}

</style>