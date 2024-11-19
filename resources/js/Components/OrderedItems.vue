<script setup>

defineProps(
    withDefaults({
        cart_products: Object,
        subtotal: Number,
        delivery_fee: Number,
        show_items_label: Boolean
    }, {
        show_items_label: true
    })
)

</script>

<template>
     <div class="col-8 col-md-8 col-lg-8 col-xl-8 col-xs-12 col-sm-12">
                <div class="text-subtitle1 text-weight-medium text-center" v-if="show_items_label">
                    {{ cart_products.length }} Items
                </div>
                <q-item v-for="item in cart_products" :class="$q.screen.lt.md ? 'q-mx-none q-px-none' : ''">
                    <q-item-section avatar>
                        <q-img 
                            :src="`/storage/${item.product.photo}`"
                            height="70px"
                            width="70px"
                            fit="contain"
                        />
                    </q-item-section>
                    <q-item-section>
                        <q-item-label class="text-weight-medium">
                            • {{ item.product.name }} - {{ item.quantity }} pcs
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
                                <!-- {{ `${modifier_item.quantity} - ${modifier_item.modifier_item.name} (P${modifier_item.modifier_item.price})` }} -->
                                {{ `${modifier_item.quantity} - ${modifier_item.modifier_item.name} (₱${parseFloat(modifier_item.modifier_item.price).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })})` }}
                            </q-item-label>
                            <!-- ₱{{ parseFloat(form.total).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }} -->
                        </template>
                        <q-item-label class="q-pl-sm" caption v-if="item.special_instruction">
                            Note: {{ item.special_instruction }}
                        </q-item-label>
                    </q-item-section>
                    <q-item-section side>
                        <div class="text-weight-bold text-right">₱{{ parseFloat(item.total).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</div>
                    </q-item-section>
                </q-item>

                <q-separator inset class="q-my-xs gt-sm" />
                <div :class="[$q.screen.gt.sm ? ' q-my-md ' : '', 'row q-mr-md']">
                    <div class="col-12 justify-end items-end flex">
                        <span class="q-mr-md">Subtotal</span> 
                        <span>₱{{ parseFloat(subtotal).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</span>
                    </div>
                    <div class="col-12 justify-end items-end flex" v-if="delivery_fee">
                        <span class="q-mr-md">Delivery Fee</span> 
                        <span>₱{{ parseFloat(delivery_fee).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}</span>
                    </div>
                    <div class="col-12 justify-end items-end flex text-weight-bold" v-if="delivery_fee">
                        <span class="q-mr-md">Total</span> 
                        <!-- <span>P{{ parseFloat(subtotal) + parseFloat(delivery_fee) }}</span> -->
                        ₱{{ (parseFloat(subtotal) + parseFloat(delivery_fee)).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) }}
                    </div>
                </div>
            </div>
</template>