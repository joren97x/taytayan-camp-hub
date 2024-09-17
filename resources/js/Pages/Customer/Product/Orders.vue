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
        <div class="row">
            <div class="col-12 col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                <q-list>
                    <Link :href="route('customer.orders.index', 'pending')">
                        <q-item clickable>
                            <q-item-section>Pending Orders</q-item-section>
                        </q-item>
                    </Link>
                    <Link :href="route('customer.orders.index', 'past')">
                        <q-item clickable>
                            <q-item-section>Past Orders</q-item-section>
                        </q-item>
                    </Link>
                    <Link :href="route('customer.orders.index', 'all')">
                        <q-item clickable>
                            <q-item-section>All Orders</q-item-section>
                        </q-item>
                    </Link>
                </q-list>
            </div>
            <div class="col-12 col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9" v-for="order in orders">
                <OrderCardItem :order="order" />
            </div>
        </div>
    </div>
</template>
