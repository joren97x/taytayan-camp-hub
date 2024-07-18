<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Link } from '@inertiajs/vue3'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    facility: Object
})

</script>

<template>
    <div>
        {{ facility }}
    </div>
</template>