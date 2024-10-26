<script setup>

defineProps({
    cart_products: Object,
    subtotal: Number
})

</script>

<template>
    <q-item v-for="item in cart_products" :class="$q.screen.lt.md ? 'q-mx-none' : ''">
        <q-item-section avatar>
            <q-img 
                :src="`/storage/${item.product.photo}`"
                height="70px"
                width="70px"
                fit="contain"
                class="q-mx-md"
            />
        </q-item-section>
        <q-item-section >
            <q-item-label >
                • {{ item.product.name }} ({{ item.product.price }}) - {{ item.quantity }} pcs
            </q-item-label>
            <template 
                v-for="(modifier, index) in item.grouped_modifiers" 
                :key="index"
            >
                <q-item-label caption class="q-pl-sm">{{ modifier.modifier_group.name }}</q-item-label>
                <q-item-label 
                    class="q-pl-sm"
                    caption 
                    v-for="(modifier_item, index) in modifier.modifier_items" 
                    :key="index"
                >
                    {{ `${modifier_item.quantity} - ${modifier_item.modifier_item.name} (P${modifier_item.total})` }}
                </q-item-label>
                
            </template>
            <q-item-label class="q-pl-sm" caption v-if="item.special_instruction">
                Note: {{ item.special_instruction }}
            </q-item-label>
        </q-item-section>
        <q-item-section side>
            P{{ item.total }}
        </q-item-section>
    </q-item>
    <q-separator/>
    <div class="row">
        <q-space/>
        <div class="q-mt-md q-mr-md text-subtitle1">
            <span class="q-mr-md">Subtotal</span> P{{ subtotal }}
        </div>
    </div>
</template>