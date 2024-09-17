<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import Chat from '@/Components/Chat.vue'

defineOptions({
    layout: CustomerLayout
})

defineProps({
    selected_user: Object
})


</script>

<template>
    <Chat :selected_user="selected_user" />
</template>
