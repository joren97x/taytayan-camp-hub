<script setup>

import ChatLayout from '@/Layouts/ChatLayout.vue'
defineProps({
    conversation: Object
})

defineOptions({
    layout: ChatLayout
})

</script>

<template>
    {{ conversation }}
</template>