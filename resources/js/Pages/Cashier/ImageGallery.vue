<template>
    <div class="image-gallery">
      <q-img
        v-for="(image, index) in images"
        :key="index"
        :src="image"
        class="gallery-image"
        :class="getGridClass(images.length)"
        style="height: 80px;"
      />
    </div>
  </template>
  
  <script setup>
  import { computed } from 'vue';
  import { defineProps } from 'vue';
  
  // Props
  const props = defineProps({
    images: {
      type: Array,
      required: true
    }
  });
  
  // Method to return grid class based on the number of images
  const getGridClass = (imageCount) => {
    switch (imageCount) {
      case 1:
        return 'full';
      case 2:
        return 'half';
      case 3:
        return 'three';
      case 4:
        return 'four';
      default:
        return 'four'; // Default for more than 4 images
    }
  };
  </script>
  
  <style scoped>
  .image-gallery {
    display: grid;
    gap: 10px;
  }
  
  .full {
    grid-template-columns: 1fr;
  }
  
  .half {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .three {
    grid-template-columns: 2fr 1fr;
    grid-template-rows: repeat(2, 1fr);
  }
  
  .four {
    grid-template-columns: repeat(2, 1fr);
    grid-template-rows: repeat(2, 1fr);
  }
  
  .gallery-image {
    width: 100%;
    height: auto;
  }
  </style>
  