<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import Index from '@/Components/Chat/Index.vue'
import Show from '@/Components/Chat/Show.vue'

defineOptions({
    layout: CustomerLayout
})

// defineProps({
//     conversations: Object,
//     conversation: Object
// })

</script>

<template>
    <Index v-if="$q.screen.gt.sm">
        <Show  />
    </Index>
    <Show v-else/>
</template>
