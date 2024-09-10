<script setup>
import { Link } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { ref } from 'vue'

defineProps({ order: Object })
const $q = useQuasar()
const viewOrderDialog = ref(false)

</script>
<template>
    <div>
        <q-item>
            <q-item-section avatar>
                <q-img height="100px" width="100px" src="https://buffer.com/library/content/images/size/w1200/2023/10/free-images.jpg"></q-img>
            </q-item-section>
            <q-item-section>
                <q-item-label>{{ order.created_at }}</q-item-label>
                <q-item-label caption>{{ order.cart_products.length }} items</q-item-label>
                <q-item-label caption>{{ order.created_at }} - {{ order.status }}</q-item-label>
            </q-item-section>
            <q-item-section side>
                <Link :href="route('product.checkout', {
                    cart_id: order.cart_id
                })">
                    <q-btn no-caps>Reorder</q-btn>
                </Link>
                <q-btn no-caps @click="viewOrderDialog = true">View Order</q-btn>
            </q-item-section>
        </q-item>
        <!-- <div class="row">
            <div class="col-4">
                <div style="height: 150px; width: 100%;" class="bg-grey"></div>
            </div>
            <div class="col-8">
                {{ order }}
                {{ order }}
            </div>
        </div> -->
        <q-dialog 
            v-model="viewOrderDialog" 
            :maximized="$q.screen.lt.md"  
            transition-show="slide-up"
            transition-hide="slide-down"
        >   
            <q-card>
                <q-card-section>
                    <q-btn icon="close" v-close-popup></q-btn>
                    hi
                    {{ order }}
                </q-card-section>
            </q-card>
        </q-dialog>
    </div>
</template>