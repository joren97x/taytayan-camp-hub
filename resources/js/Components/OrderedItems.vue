<script setup>

defineProps({
    cart_products: Object,
    subtotal: Number
})

</script>

<template>
     <div class="col-8 col-md-8 col-lg-8 col-xl-8 col-xs-12 col-sm-12">
                <div class="text-h6 q-mb-md">
                    Order - {{ cart_products.length }} items
                </div>

                <q-item v-for="item in cart_products" :class="$q.screen.lt.md ? 'q-mx-none' : ''" class="q-py-sm">
                    <q-item-section avatar>
                        <q-img 
                            :src="`/storage/${item.product.photo}`"
                            height="70px"
                            width="70px"
                            fit="contain"
                            class="q-mx-md"
                        />
                    </q-item-section>
                    <q-item-section>
                        <q-item-label class="text-weight-medium">
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
                        <div class="text-weight-bold text-right">P{{ item.total }}</div>
                    </q-item-section>
                </q-item>

                <q-separator inset class="q-my-md" />

                <div class="row justify-end q-mt-md q-mr-md text-h6">
                    <span class="q-mr-md">Subtotal</span> 
                    <span>P{{ subtotal }}</span>
                </div>
            </div>
</template>