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
            <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" v-for="order in orders">
                <OrderCardItem :order="order" />
            </div>
        </div>
    </div>
</template>
