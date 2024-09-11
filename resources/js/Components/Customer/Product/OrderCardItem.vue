<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { ref } from 'vue'

const props = defineProps({ order: Object })
const $q = useQuasar()
const viewOrderDialog = ref(false)
const rateDialog = ref(false)
const completeOrderForm = useForm({})

const completeOrder = () => {
    completeOrderForm.patch(route('customer.orders.update', props.order.id), {
        onSuccess: () => {
            rateDialog.value = true
        }
    })
}

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
                <q-btn 
                    v-if="order.status == 'ready_for_pickup' || order.status == 'delivered'"
                    no-caps 
                    @click="completeOrder()"
                    :loading="completeOrderForm.processing"
                    :disable="completeOrderForm.processing"
                >
                    Complete Order
                </q-btn>
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
        <q-dialog 
            v-model="rateDialog" 
            transition-show="slide-up"
            transition-hide="slide-down"
            :maximized="$q.screen.lt.md"
        >
            <q-card>
                <q-card-section>
                    <q-btn 
                        icon="close" 
                        class="absolute-top-right q-mr-sm q-mt-sm" 
                        round 
                        unelevated 
                        v-close-popup
                    />
                    <div class="text-h6">Rate</div>
                    <div class="text-subtitle1">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Voluptatum, nesciunt?</div>
                    <q-rating size="xl" />
                    <q-input type="textarea" filled label="Write your review here..."/>
                </q-card-section>
                <q-card-actions>
                    <q-btn 
                        class="full-width" 
                        color="primary"
                    >
                        Submit
                    </q-btn>
                </q-card-actions>
            </q-card>
        </q-dialog>
    </div>
</template>