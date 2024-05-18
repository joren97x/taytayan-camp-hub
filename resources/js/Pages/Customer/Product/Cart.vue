<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    products: Object
})

</script>

<template>
    <h1>Cart page</h1>
    {{ products }}
</template>