<script setup>

import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'

defineOptions({
    layout: AdminLayout
})

defineProps({
    user_chart: Object
})

import { usePage } from '@inertiajs/vue3';
const page = usePage()
const shareOnFacebook = () => {
  const urlToShare = encodeURIComponent(page.url);
  console.log(urlToShare)
  const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${urlToShare}`;
  
  // Open Facebook sharing dialog
  window.open(shareUrl, '_blank');
};

</script>

<template>
    <Head title="Dashboard" />
    <q-btn @click="shareOnFacebook" icon="mdi-facebook" label="Share on Facebook" />
    <a :href="`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(route('admin.products.index'))}`" target="_blank">
            Share on Facebook
        </a>
        <a href="google.com" target="_blank">
            hii
        </a>
    <div class="q-mx-md">
        <div class="text-h5 q-my-md">
            Dashboard
        </div>
        
        <div class="row q-col-gutter-md ">
            <div class="col-3" v-for="n in 4">
                <q-card borderd class="q-pa-xl">
                    Lorem {{ n }}
                </q-card>
            </div>
            <div class="col-7">
                <q-card borderd style="padding: 170px">
                    some chart
                </q-card>
            </div>
            <div class="col-5">
                <q-card borderd style="padding: 170px">
                    some chart
                </q-card>
            </div>
            <div class="col-12">
                <q-card borderd style="padding: 100px">
                    idk
                </q-card>
            </div>
        </div>
    </div>
    {{ user_chart }}
</template>