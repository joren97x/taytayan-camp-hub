<script setup>

import DriverLayout from '@/Layouts/DriverLayout.vue'

defineOptions({
    layout: DriverLayout
})

defineProps({
    orders: Object
})

</script>

<template>
    <h1>Delviery History</h1>
    {{ orders }}
</template>
