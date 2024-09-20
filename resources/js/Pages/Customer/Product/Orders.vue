<script setup>

import { ref, watch, onMounted } from 'vue'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import OrderCardItem from '@/Components/Customer/Product/OrderCardItem.vue'
import { Head, router, Link } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    orders: Object,
    order_constants: Object
})

const $q = useQuasar()
const tab = ref('on-progress')

watch(tab, () => {
    console.log('go')
    router.get(route('orders', tab.value), [], {
        onSuccess: (res) => {
            console.log(res)
        }
    })
})

</script>

<template>
    <div>
        <Head title="Orders" />
            <div class="row q-ma-xl">
                <div class="col self-start text-h6">
                    Orders
                </div>
                <div class="col text-right">
                    <Link :href="route('customer.orders.past_orders')">
                        See Past Orders
                    </Link>
                </div>
            </div>
            <div class="row">
                
                <div 
                    class="col-12 col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9" 
                    v-for="order in orders"

                >
                    <OrderCardItem :order="order"/>
                </div>
                <!-- <div class="col-12 items-center self-center flex " style="height: 50vh;" v-else>
                    <div class="col text-center">
                        There is currently no orders...
                    </div>
                </div> -->
            </div>
        </div>
</template>
