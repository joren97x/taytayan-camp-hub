<script setup>

import { Head } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import ProductCard from '@/Components/Customer/Milkteas/ProductCard.vue'
import FeaturedProductCard from '@/Components/Customer/Milkteas/FeaturedProductCard.vue'

defineOptions({
    layout: CustomerLayout
})

const slide = ref(1)
const categories = [
    'Milktea',
    'Lorem upsum',
    'Mango',
    'Shake',
    'Or other things',
]

const currentCategory = ref(null)

const observer = new IntersectionObserver(entries => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            currentCategory.value = entry.target.id
        }
    })
}, {threshold: 0.5})

onMounted(() => {
    const categoryElements = document.querySelectorAll('.category')
    categoryElements.forEach(categoryElement => {
        observer.observe(categoryElement)
    })
})

function scrollToSection(section) {
    document.getElementById(section).scrollIntoView({ behavior: 'smooth', block: 'start' })
}

</script>

<template>
    <Head title="Milktea Menu" />
    <div>
        <q-img
            cover
            src="masarap milktea.jpeg"
            :ratio="16/9"
            height="300px"
            class="rounded-borders"
        />
        <p class="q-mt-md">
            <span class="text-h3">RJC Cafe</span>
            <br>
            <span class="text-h6">San Vicente, Olango Island</span>
        </p>
        <q-separator/>
        <div class="row">
            <div class="col-3 sidebar" style="position: relative;">
                <div style="position: sticky; top: 100px;">
                    <q-list padding class="q-mr-xl">
                        <q-item-label header>Categories</q-item-label>
                        <q-item clickable v-ripple>
                            <q-item-section>
                                Featured items
                            </q-item-section>
                        </q-item>
                        <q-item
                            active-class="bg-secondary text-white" 
                            :active="currentCategory == category" 
                            clickable 
                            @click="scrollToSection(category)" 
                            v-ripple 
                            v-for="category in categories" 
                            :key="category"
                        >
                            <q-item-section>
                                {{ category }}
                            </q-item-section>
                        </q-item>
                    </q-list>
                </div>
            </div>
            <div class="col-9">
                <q-item>
                    <q-item-section class="text-h6">
                        Featured items
                    </q-item-section>
                    <q-item-section side>
                        <div class="text-grey-8 q-gutter-xs">
                            <q-btn class="gt-xs" size="12px" round icon="arrow_back" @click="slide--" />
                            <q-btn class="gt-xs" size="12px" round icon="arrow_forward" @click="slide++" />
                        </div>
                    </q-item-section>
                </q-item>
                <div class="row">
                    <q-carousel
                        v-model="slide"
                        transition-prev="slide-right"
                        transition-next="slide-left"
                        swipeable
                        animated
                        height="300px"
                        class="full-width"
                    >
                        <q-carousel-slide :name="1">
                            <div class="row fit items-center q-col-gutter-sm">
                                <FeaturedProductCard v-for="n in 4" :key="n"/>
                            </div>
                        </q-carousel-slide>
                        <q-carousel-slide :name="2">
                            <div class="row fit items-center q-col-gutter-sm">
                                <FeaturedProductCard v-for="n in 4" :key="n"/>
                            </div>
                        </q-carousel-slide>
                    </q-carousel>
                </div>
                <div v-for="(category, i) in categories" :key="i" class="q-mt-md category" :id="category">
                    <q-item class="text-h6">{{ category }}</q-item>
                    <div class="row q-col-gutter-md">
                        <div class="col-6" v-for="n in 5" :key="n">
                            <ProductCard/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>