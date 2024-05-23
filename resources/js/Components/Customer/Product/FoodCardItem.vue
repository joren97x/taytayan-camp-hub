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
            :src="`images/${item.product_photo}`"
            height="70px"
            width="70px"
            class="q-mx-md"
        />
        
        <q-item-section>
            <q-item-label>
                {{ item.product_name }}
            </q-item-label>
            <template 
                v-for="(modifier_group, index) in item.modifier_groups" 
                :key="index"
            >
                <q-item-label caption>{{ modifier_group.modifier_group_name }}</q-item-label>
                <q-item-label 
                    caption 
                    v-for="(modifier_item, index) in modifier_group.modifiers" 
                    :key="index"
                >
                    {{ `${modifier_item.quantity} - ${modifier_item.modifier_item_name} (P${modifier_item.total_modifier_price})` }}
                </q-item-label>
            </template>
        </q-item-section>
        <q-item-section side>
            P{{ item.total_product_price }}
        </q-item-section>
    </q-item>

    <EditFoodCartDialog :dialog="dialog" @close="dialog = false" />

</template>