<script setup>

import CashierLayout from '@/Layouts/CashierLayout.vue'
import Index from '@/Components/Chat/Index.vue';

defineOptions({
    layout: CashierLayout
})

defineProps({
    conversations: Object
})

</script>

<template>
     <Index :conversations="conversations" />
</template>
