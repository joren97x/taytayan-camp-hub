<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({
    layout: AdminLayout
})

defineProps({
    users: Object
})

</script>

<template>
    {{ users }}
    <div class="text-h1">
        User Management
    </div>
</template>