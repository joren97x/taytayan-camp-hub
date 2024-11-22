<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
const props = defineProps({ products: Object, events: Object, facilities: Object, ratings: Object })
defineOptions({ layout: CustomerLayout })

</script>

<template>

    <Head title="Home" />
    {{ props }}
</template>

<style>


footer {
  background-color: #1a1a1a;
  color: #ffffff;
}
@keyframes bounce {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(5px);
  }
}

.bounce-animation {
  animation: bounce 1s infinite;
}

/* 

.hover-card:hover {
  transform: translateY(-5px);
  box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
} */

a {
    color: black
}
</style>