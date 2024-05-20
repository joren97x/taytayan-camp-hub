
<script setup>
const tents = [
    {
        img: 'https://img.cdn.mountainwarehouse.com/product/m409a2/m409a2_001_4mbelltentplusfireretardantcotton320gsm20230222052117_1.jpg?w=500', // Replace with actual image URL
        title: 'Tent 1',
        rating: 4.9,
        reviews: 35,
        details: '2 guests · 3 beds · 1 bath\nRelax in this peaceful, family friendly campground, in the foothills...',
        price: 'P200.00 night',
    },
    {
        img: 'https://img.cdn.mountainwarehouse.com/product/m409a2/m409a2_001_4mbelltentplusfireretardantcotton320gsm20230222052117_1.jpg?w=500', // Replace with actual image URL
        title: 'Tent 2',
        rating: 4.9,
        reviews: 35,
        details: '2 guests · 3 beds · 1 bath\nRelax in this peaceful, family friendly campground, in the foothills...',
        price: 'P202.00 night',
    },
    {
        img: 'https://img.cdn.mountainwarehouse.com/product/m409a2/m409a2_001_4mbelltentplusfireretardantcotton320gsm20230222052117_1.jpg?w=500', // Replace with actual image URL
        title: 'Tent 3',
        rating: 4.9,
        reviews: 35,
        details: '2 guests · 3 beds · 1 bath\nRelax in this peaceful, family friendly campground, in the foothills...',
        price: 'P203.00 night',
    },
    {
        img: 'https://img.cdn.mountainwarehouse.com/product/m409a2/m409a2_001_4mbelltentplusfireretardantcotton320gsm20230222052117_1.jpg?w=500', // Replace with actual image URL
        title: 'Tent 4',
        rating: 4.9,
        reviews: 35,
        details: '4 guests · 3 beds · 1 bath\nRelax in this peaceful, family friendly campground, in the foothills...',
        price: 'P200.00 night',
    },
]
</script>

<template>
      <div class="row q-col-gutter-md q-ma-xs">
        <div class="col-6" v-for="(tent, index) in tents" :key="index" >
            <q-card class="my-card">
          <q-img :src="tent.img" :alt="`Image of ${tent.title}`" class="my-img">
            <template v-slot:after>
              <q-btn flat dense round icon="chevron_right" class="absolute-bottom-right" />
            </template>
          </q-img>
          <q-card-section>
            <div class="row justify-between items-center">
              <div class="text-h6">{{ tent.title }}</div>
              <div>
                <q-icon name="star" color="amber" /> {{ tent.rating }} ({{ tent.reviews }})
              </div>
            </div>
            <div>{{ tent.details }}</div>
            <div class="row q-my-sm">
              <q-icon name="wifi" /> Free wifi
              <q-space />
              <q-icon name="pets" /> Pets allowed
            </div>
            <div>{{ tent.price }}</div>
            <q-btn color="primary" label="Button" class="q-mt-md" />
          </q-card-section>
        </q-card>
        </div>
      </div>
  </template>
  
  
  <style scoped>
  
  .my-img {
    height: 200px;
  }
  </style>
  