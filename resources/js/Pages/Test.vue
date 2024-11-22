<script setup>

import CustomerLayout from '@/Layouts/CustomerLayout.vue'
const props = defineProps({ products: Object, events: Object, facilities: Object, ratings: Object })

</script>

<template>

    <Head title="Home" />
    
    <CustomerLayout>
        <template v-slot:cover>
            <div
                :style="`position: absolute; width: 100vw; height: ${$q.screen.gt.sm ? '60vh' : '40vh'}; overflow: hidden; top: 0`">
                <div style="
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0) 80%);
        z-index: 1;
      ">
                    <!-- <div class="absolute-bottom text-center text-subtitle1 bounce-animation q-mb-sm">
                        <div>Explore Taytayan Camp</div>
                        <q-btn icon="south" flat round />
                    </div> -->

                </div>
                <q-img src="taytayan.jpg" class="fit"
                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" />
                <div style="
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 80%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        z-index: 2;
        text-align: center;
      ">
                    <div style="
          margin: 0;
          font-weight: bold;
          text-shadow: 20px 20px 20px rgba(0, 2, 2, 2.9);
        " :class="[$q.screen.gt.sm ? 'text-h2' : 'text-h5 q-pt-xl', 'text-weight-bold']">
                        Welcome to Taytayan Camp
                    </div>
                    <div :class="[$q.screen.gt.sm ? 'text-h6' : 'text-subtitle1', 'text-weight-medium']" style="
          font-weight: bold;
          text-shadow: 10px 10px 10px rgba(10, 10, 10, 10);
          max-width: 800px;
        ">
                        Sip, Explore, and Stay - Discover More at Taytayan Camp Adventure Island
                    </div>
                </div>
            </div>
        </template>

        {{ props }}

    </CustomerLayout>
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