<script setup>

import { ref } from 'vue'
import EditFoodCartDialog from './EditFoodCartDialog.vue'

const dialog = ref(false)

</script>

<template>
    <q-item @click="dialog = true" clickable>
        <q-item-section>
            <span>
                <q-chip size="sm" :class="$q.dark.isActive ? 'bg-grey-9' : ''">1</q-chip>
            Milktea
        </span>
            <q-item-label caption>Secondary line text. Lorem ipsum dolor sit amet
                , consectetur adipiscit elit.</q-item-label>
        </q-item-section>
        
        <q-item-section thumbnail>
            <img src="https://cdn.quasar.dev/img/chicken-salad.jpg"/>
            <q-item-section side top class="q-mt-sm">
                <q-item-label>P30.00</q-item-label>
            </q-item-section>
        </q-item-section>
    </q-item>

    <EditFoodCartDialog :dialog="dialog" @close="dialog = false" />

</template>