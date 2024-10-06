<script setup>

import DriverLayout from '@/Layouts/DriverLayout.vue'
import Index from '@/Components/Chat/Index.vue';

defineOptions({
    layout: DriverLayout
})

defineProps({
    conversations: Object
})

</script>

<template>
     <Index :conversations="conversations" />
</template>
