<script setup>

import AdminSidebar from '@/Layouts/AdminSidebar.vue';

defineOptions({
    layout: AdminSidebar
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