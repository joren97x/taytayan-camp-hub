<script setup>

import { onMounted, ref } from 'vue'

defineProps({
    conversation: Object
})

</script>

<template>
    <div class="q-pa-md">
        <q-card bordered flat>
            <q-card-section>
                {{ conversation }}
            </q-card-section>
        </q-card>
    </div>
</template>