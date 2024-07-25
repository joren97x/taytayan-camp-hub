<script setup>

import { ref } from 'vue'
import EditFoodCartDialog from './EditFoodCartDialog.vue'
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    item: Object
})

const form = useForm({
    quantity: props.item.quantity
})

const dialog = ref(false)

</script>

<template>
    <q-item clickable @click="dialog = true">
        <q-select 
            borderless
            v-model="form.quantity" 
            :options="[1,2,3,4,5,6,7,8,9,10]"
        />
        <q-img 
            :src="`/storage/${item.product.photo}`"
            height="70px"
            width="70px"
            class="q-mx-md"
        />
        
        <q-item-section>
            <q-item-label>
                {{ item.product.name }} ({{ item.product.price }})
            </q-item-label>
            <template 
                v-for="(modifier, index) in item.grouped_modifiers" 
                :key="index"
            >
                <q-item-label caption>{{ modifier.modifier_group.name }}</q-item-label>
                <q-item-label 
                    caption 
                    v-for="(modifier_item, index) in modifier.modifier_items" 
                    :key="index"
                >
                    {{ `${modifier_item.quantity} - ${modifier_item.modifier_item.name} (P${modifier_item.total})` }}
                </q-item-label>
                
            </template>
            <q-item-label caption v-if="item.special_instruction">
                Note: {{ item.special_instruction }}
            </q-item-label>
        </q-item-section>
        <q-item-section side>
            P{{ item.total }}
        </q-item-section>
    </q-item>

    <EditFoodCartDialog :dialog="dialog" @close="dialog = false" />

</template>