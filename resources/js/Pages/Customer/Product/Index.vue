<script setup>

import { Head } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import ProductCard from '@/Components/Customer/Product/ProductCard.vue'
import FeaturedProductCard from '@/Components/Customer/Product/FeaturedProductCard.vue'

defineOptions({
    layout: CustomerLayout
})

const props = defineProps({
    categories: Object,
    products: Object,
    featured_products: Object
})

const slide = ref(1)
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
            <span>4.6 <q-icon name="star"></q-icon> (310+)</span>
            <br>
            <span>San Vicente, Olango Island</span>
        </p>
        <div class="row q-col-gutter-md">
            <div class="col-8">
                <p class="text-h6">Rating and reviews</p>
                <q-card bordered>
                    <q-card-section horizontal>
                        <q-card-section class="text-center q-pa-md">
                            <span class="text-h4">4.6</span>
                            <br>
                            <q-rating size="sm" />
                            <br>
                            <span>310+ Ratings</span>
                        </q-card-section>
                        <q-card-section>
                            <q-card-section v-for="n in 2">
                                <span>"I love love love love!!! Their Thai tea and cream cheese bagels. Best breakfast on the go. And cheap too!!"</span>
                                <br>
                                <q-rating /> • Giuliana R. • 07/26/23
                            </q-card-section>  
                            <q-btn icon="arrow_downward" no-caps color="primary">Show More</q-btn>
                        </q-card-section> 
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-4">
                <q-card bordered>
                    <div style="height: 200px;" class="bg-grey">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d123065.8494400433!2d123.92886618907012!3d10.25398460253611!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a99184b152420b%3A0x6bebeab9d8bca659!2sRJC%20CAFE!5e0!3m2!1sen!2sph!4v1724885213335!5m2!1sen!2sph" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <q-card-section>
                        <q-item title="San vicente olango" subtitle="boang">
                            <q-item-section avatar>
                                <q-icon name="signal_wifi_off" />
                            </q-item-section>
                            <q-item-section >
                                <q-item-label>RJC CAFE</q-item-label>
                                <q-item-label caption>725Q+CHH, Lapu-Lapu City, Cebu</q-item-label>
                            </q-item-section>
                            <q-item-section side>Side</q-item-section>
                        </q-item>
                    </q-card-section>
                </q-card>
            </div>
        </div>
        <!-- <q-separator/> -->
        <div class="row q-my-lg">
            <div class="col-8">
                <span class="text-h6">Menu</span>
                <br>
                <span>Open till 8AM to 5PM or ambot</span>
            </div>
            <div class="col-4">
                <q-input placeholder="Search..." filled>
                    <template v-slot:prepend>
                        <q-icon name="search"></q-icon>
                    </template>
                </q-input>
            </div>
        </div>
        <div>
            ari dire kay mga categories unya na kay di ko kamao mobuhat
        </div>
        <div 
                    v-for="(category, i) in props.categories" 
                    :key="i" 
                    class="q-mt-md category" 
                    :id="category.id"
                >
                    <q-item class="text-h6">{{ category.name }}</q-item>
                    <div class="row q-col-gutter-md">
                        <div class="col-6" v-for="product in category.products" :key="product.id">
                            <ProductCard :product="product" />
                        </div>
                    </div>
                </div>
        <!-- <div class="row">
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
                            :active="currentCategory == category.id" 
                            clickable 
                            @click="scrollToSection(category.id)" 
                            v-ripple 
                            v-for="category in props.categories" 
                            :key="category.id"
                        >
                            <q-item-section>
                                {{ category.name }}
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
                        class="full-width  bg-transparent"
                    >
                        <q-carousel-slide :name="1">
                            <div class="row fit items-center q-col-gutter-sm">
                                <FeaturedProductCard v-for="product in featured_products" :product="product" :key="product.id"/>
                            </div>
                        </q-carousel-slide>
                        <q-carousel-slide :name="2">
                            <div class="row fit items-center q-col-gutter-sm">
                                <FeaturedProductCard v-for="product in featured_products" :product="product" :key="product.id"/>
                            </div>
                        </q-carousel-slide>
                    </q-carousel>
                </div>
                <div 
                    v-for="(category, i) in props.categories" 
                    :key="i" 
                    class="q-mt-md category" 
                    :id="category.id"
                >
                    <q-item class="text-h6">{{ category.name }}</q-item>
                    <div class="row q-col-gutter-md">
                        <div class="col-6" v-for="product in category.products" :key="product.id">
                            <ProductCard :product="product" />
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
</template>