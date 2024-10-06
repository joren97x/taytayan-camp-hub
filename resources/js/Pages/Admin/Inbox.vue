<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import Index from '@/Components/Chat/Index.vue';

defineOptions({
    layout: AdminLayout
})

defineProps({
    conversations: Object
})

</script>

<template>
     <Index :conversations="conversations" />
</template>
