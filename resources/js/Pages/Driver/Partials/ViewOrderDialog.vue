<script setup>

import { ref } from 'vue'
import { useForm, Link } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import OrderDetails from '@/Components/OrderDetails.vue'

const props = defineProps({
    order: Object
})

const $q = useQuasar()
const dialog = ref(false)
const deliverOrderForm = useForm({})
const completeOrderForm = useForm({})

function deliverOrder() {
    deliverOrderForm.patch(route('driver.order.deliver', props.order.id), {
        onSuccess: () => {
            dialog.value = false
            $q.notify('Order Now To Be Delivered')
        }
    })
}

const completeOrder = () => {
    completeOrderForm.patch(route('driver.order.complete_delivery', props.order.id), {
        onSuccess: () => {
            $q.notify('Order Has Been Delivered')
        }
    })
}

</script>

<template>
    <q-btn no-caps color="primary" rounded unelevated @click="dialog = !dialog" :class="$q.screen.lt.md ? 'full-width' : ''" label="View Order" />
    <q-dialog 
        v-model="dialog" 
        :maximized="$q.screen.lt.md"
        transition-show="slide-up"
        transition-hide="slide-down"
    >
        <OrderDetails :order="order">
            <div class="q-mt-md">
                <q-btn 
                    class="full-width" 
                    color="primary" 
                    no-caps
                    rounded 
                    unelevated
                    :loading="deliverOrderForm.processing"
                    :disable="deliverOrderForm.processing"
                    @click="deliverOrder()"
                    label="Deliver Order"
                    v-if="order.status == 'ready_for_delivery'"
                />
                <q-btn
                    no-caps
                    class="full-width q-mt-sm"
                    :loading="completeOrderForm.processing"
                    :disable="completeOrderForm.processing"
                    @click="completeOrder()"
                    rounded
                    color="primary"
                    v-if="order.status == 'delivering'"
                    label="Complete Delivery"
                />
                <Link :href="route('driver.map')">
                    <q-btn
                        no-caps
                        class="full-width q-mt-sm"
                        rounded
                        outline
                        color="primary"
                        v-if="order.status == 'delivering'"
                        label="View in Map"
                    />
                </Link>
            </div>
        </OrderDetails>
    </q-dialog>
</template>