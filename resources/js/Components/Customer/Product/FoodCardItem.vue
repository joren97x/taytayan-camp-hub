<script setup>

import { ref } from 'vue'
import EditFoodCartDialog from './EditFoodCartDialog.vue'
defineProps({
    item: Object
})

const dialog = ref(false)

</script>

<template>
    <q-item @click="dialog = true" clickable>
        <q-item-section>
            <span>
                <q-chip size="sm" :class="$q.dark.isActive ? 'bg-grey-9' : ''">1</q-chip>
                {{ item.product.name }}
        </span>
            <q-item-label 
                class="q-my-md"
                caption 
                v-for="modifier in item.grouped_modifiers" 
                :key="modifier.id"
            >
                {{ modifier.name }}
                <q-item-label 
                    caption
                    v-for="item in modifier.items"
                    :key="item.id"
                >
                    {{ item.name }}
                </q-item-label>
            </q-item-label>
        </q-item-section>
        
        <q-item-section thumbnail>
            <img :src="`/images/${item.product.photo}`"/>
            <q-item-section side top class="q-mt-sm">
                <q-item-label>P{{ item.product.price }}</q-item-label>
            </q-item-section>
        </q-item-section>
    </q-item>

    <EditFoodCartDialog :dialog="dialog" @close="dialog = false" />

</template>