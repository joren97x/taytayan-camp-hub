<script setup>

import { Head, Link } from '@inertiajs/vue3'
import { ref, onMounted, computed } from 'vue'
import { date, useQuasar } from 'quasar'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import ProductCard from './Partials/ProductCard.vue'
import FeaturedProductCard from './Partials/FeaturedProductCard.vue'
import NewAddressDialog from '@/Components/Customer/NewAddressDialog.vue'

// defineOptions({
//     layout: CustomerLayout
// })

const $q = useQuasar()
const props = defineProps({
    categories: Object,
    featured_products: Object,
    google_maps_api_key: String,
    ratings: Object,
    rating_stats: Object
})
// const slide = ref(1)
// const currentCategory = ref(null)

// const observer = new IntersectionObserver(entries => {
//     entries.forEach(entry => {
//         if (entry.isIntersecting) {
//             currentCategory.value = entry.target.id
//         }
//     })
// }, {threshold: 0.5})

onMounted(() => {
    // const categoryElements = document.querySelectorAll('.category')
    // categoryElements.forEach(categoryElement => {
    //     observer.observe(categoryElement)
    // })

    const menuElement = document.querySelector('.categories-wrapper');
    if (menuElement) {
        menuHeight.value = menuElement.offsetHeight;
    }
})

const categoryMenu = ref(false)
const categoriesWrapper = ref(null);
const selectedCategory = ref(null);
const categoryRefs = ref({});
const menuHeight = ref(0)
const ratingsContainer = ref(null)

const scrollToRatings = () => {
    console.log('hii')
    if (ratingsContainer.value) {
        ratingsContainer.value.scrollTop = ratingsContainer.value.scrollHeight;
    }
};

const scrollToCategory = (category) => {
    categoryMenu.value = false
    setTimeout(() => {
        const element = categoryRefs.value[category.id];
        selectedCategory.value = category.name;
        
        if (element) {
            const offset = element.getBoundingClientRect().top + window.pageYOffset - menuHeight.value - 150;
            window.scrollTo({ top: offset, behavior: 'smooth' });
        }
    }, 150);
  
};

const scrollLeft = () => {
  if (categoriesWrapper.value) {
    categoriesWrapper.value.scrollBy({
      left: -200,
      behavior: 'smooth'
    });
  }
};

const scrollRight = () => {
  if (categoriesWrapper.value) {
    categoriesWrapper.value.scrollBy({
      left: 200,
      behavior: 'smooth'
    });
  }
};

const showNewAddressDialog = ref(false)
const scrollContainer = ref(null);

const scrollLeftFeatured = () => {
  const container = scrollContainer.value;
  container.scrollBy({
    left: -container.clientWidth, // Scroll left by the width of the container
    behavior: 'smooth'
  });
};

const scrollRightFeatured = () => {
  const container = scrollContainer.value;
  container.scrollBy({
    left: container.clientWidth, // Scroll right by the width of the container
    behavior: 'smooth'
  });
};

const search = ref('');

const filteredCategories = computed(() => {
  if (!search.value) {
    // Return all categories with their original products
    return props.categories.map(category => ({
      ...category,
      filteredProducts: category.products
    }));
  }

  const searchTerm = search.value.toLowerCase();
  console.log('hi')
  console.log(searchTerm)

    return props.categories
    .map(category => {
      // Filter products within this category based on search term
      let filteredProducts = category.products.filter(product =>
        product.name.toLowerCase().includes(searchTerm) ||
        product.description.toLowerCase().includes(searchTerm)
      );

        filteredProducts.filter(product =>
            product.name.toLowerCase().includes(searchTerm) ||
            product.description.toLowerCase().includes(searchTerm)
        );
        console.log("from da here")
        console.log(filteredProducts.length)
      // Return the category with only the filtered products
      return {
        ...category,
        filteredProducts
      };
    })
    .filter(category => category.filteredProducts.length > 0); 
});

const overAllRating = parseFloat(props.rating_stats.average_rating).toFixed(2)
</script>

<template>
    <Head title="Milktea Menu" />
    <CustomerLayout>
        <template v-slot:cover>
            <div style="position: absolute; width: 100vw; height: 50vh; overflow: hidden; top: 0" class="bg-grey-3">
                <div style="
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: linear-gradient(to bottom, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0) 80%);
                    z-index: 1;
                "></div>
                <q-img src="images/rjc1.jpg" class="fit" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;" />
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
                    padding: 20px; /* For smaller screens */
                ">
                    <h1 style="
                        margin: 0;
                        font-size: 3.5rem;
                        font-weight: bold;
                        text-shadow: 20px 20px 20px rgba(0, 2, 2, 2.9);
                        " 
                        class="text-h1 text-weight-bold"
                    >
                        RJC Cafe
                    </h1>
                    <div style="
                        font-size: 1.25rem;
                        font-weight: bold;
                        text-shadow: 10px 10px 10px rgba(10, 10, 10, 10);
                        max-width: 800px;
                    ">
                        Savor the Flavor, Embrace the Adventure!
                    </div>
                </div>
            </div>
        </template>
    <div style="margin-top: 45vh">
        <NewAddressDialog 
            :dialog="showNewAddressDialog" 
            @close="showNewAddressDialog = false"
            :google_maps_api_key="google_maps_api_key" 
            v-if="$page.props.auth.user"
        />
        <!-- <q-card bordered flat style="height: 40vh; position: relative;" class="full-width bg-grey row justify-center">
            <q-img class="fit" fit="cover" src="images/rjc1.jpg" />
        </q-card> -->
        <div class="q-mx-sm">
            <div class="row q-col-gutter-md q-mt-xs">
                <div class="col-12 col-xs-12 col-sm col-md-8 col-lg-8 col-xl-8">
                    <div :class="[$q.screen.lt.md ? 'text-center' : '']">
                        <span class="text-h4 text-weight-bold">RJC Cafe</span>
                        <br>
                        <span><q-icon name="star"></q-icon>{{ overAllRating }} • ({{ rating_stats.rating_count }})</span>
                    </div>
                    <div>
                        RJK stands for Ruby Ann, Joan, Key Marie. Its a snack shop that sells cold drinks burgers and more😊.
                    </div>
                    <div v-if="$page.props.auth.user">
                        <q-banner 
                            rounded 
                            class="bg-primary" 
                            v-if="!$page.props.auth.user.address"
                        >
                            <span class="text-white text-weight-bold text-subtitle1">Please enter your address to place an order.</span>
                            <q-btn 
                                icon="pin_drop" 
                                label="Place your address" 
                                no-caps 
                                color="white" 
                                class="full-width text-black q-my-xs"
                                @click="showNewAddressDialog = true"
                            >
                            </q-btn>            
                        </q-banner>
                    </div>
                    <div v-else>
                        <q-banner rounded class="bg-primary">
                            <span class="text-white text-weight-bold text-subtitle1">Please enter your address to place an order.</span>
                            <Link :href="route('login')">
                                <q-btn 
                                    icon="pin_drop" 
                                    label="Place your address" 
                                    no-caps 
                                    color="white"
                                    rounded 
                                    unelevated 
                                    class="full-width text-black q-my-xs"
                                >
                                </q-btn>   
                            </Link>         
                        </q-banner>
                    </div>

                    <div v-if="ratings.length > 0">
                        <div class="row justify-between q-mt-md q-mb-sm">
                            <div class="text-h6">
                                Rating and Reviews
                            </div>
                            <div class=" flex justify-end items-center">
                                <q-btn rounded no-caps flat label="View All" class="bg-grey-3" @click="scrollToRatings"/>
                                <q-btn round :size="$q.screen.lt.md ? 'sm' : 'md'" icon="arrow_back" flat @click="scrollLeftFeatured('products')" class="bg-grey-3 q-mx-xs"/>
                                <q-btn round :size="$q.screen.lt.md ? 'sm' : 'md'" icon="arrow_forward" flat @click="scrollRightFeatured('products')" class="bg-grey-3"/>
                            </div>
                        </div>
                        <div ref="scrollContainer" class="row q-col-gutter-md no-wrap hide-scrollbar q-py-xs" style="overflow-x: auto; scroll-behavior: smooth;">
                            <div class="col-md-2 col-xs-5 col-sm-5 col-lg-2 col-xl-2">
                                <q-card class="q-py-md" style="max-height: 600px; height: 115px">
                                    <q-card-section class="q-pa-none text-center">
                                        <div class="text-h5">{{ overAllRating }}</div>
                                        <div class="column items-center">
                                            <q-rating size="xs" v-model="overAllRating" />
                                        </div>
                                        {{ rating_stats.rating_count }} Ratings
                                    </q-card-section>
                                </q-card>
                            </div>
                            <div 
                                class="col-5 col-xs-7 col-sm-7 col-md-4 col-lg-4 col-xl-4"
                                v-for="(rating, i) in ratings"
                                :key="rating.id"
                            >
                                <q-card style="max-height: 600px; height: 115px">
                                    <q-card-section class="q-pb-none ellipsis-2-lines">
                                        {{ rating.review }}
                                    </q-card-section>
                                    <q-card-actions class="q-pt-sm">
                                        <q-rating v-model="rating.rating" readonly/>
                                        • {{ rating.user.first_name }} • {{ date.formatDate(rating.created_at, 'M/D/YYYY') }}
                                    </q-card-actions>
                                </q-card>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="col-4 gt-sm">
                
                    <q-card bordered>
                        <div style="height: 200px;" class="bg-grey">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d123065.8494400433!2d123.92886618907012!3d10.25398460253611!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x33a99184b152420b%3A0x6bebeab9d8bca659!2sRJC%20CAFE!5e0!3m2!1sen!2sph!4v1724885213335!5m2!1sen!2sph" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade"
                            >
                            </iframe>
                        </div>
                        <q-card-section class="q-pa-none q-mx-md">
                            <q-item class="q-pa-none q-mx-none">
                                <q-item-section avatar>
                                    <q-icon name="shopping_cart" />
                                </q-item-section>
                                <q-item-section >
                                    <q-item-label>RJC CAFE</q-item-label>
                                    <!-- <q-item-label caption>725Q+CHH, Lapu-Lapu City, Cebu</q-item-label> -->
                                </q-item-section>
                                <q-item-section side>Side</q-item-section>
                            </q-item>
                        </q-card-section>
                    </q-card>
                </div>
            </div>
            <!-- <q-separator/> -->
            <div class="featured-items-container q-my-md" v-if="featured_products.length > 0">
                <div class="text-h6 row">
                    <div class="col">
                        Featured items
                    </div>
                    <div class="col flex justify-end">
                        <q-btn class="bg-grey-3 q-mr-sm" round :size="$q.screen.lt.md ? 'sm' : 'md'" icon="arrow_back" flat @click="scrollLeftFeatured"/>
                        <q-btn class="bg-grey-3" round :size="$q.screen.lt.md ? 'sm' : 'md'" icon="arrow_forward" flat @click="scrollRightFeatured"/>
                    </div>
                </div>
                <div ref="scrollContainer" class="row q-col-gutter-md no-wrap hide-scrollbar" style="overflow-x: auto; scroll-behavior: smooth;">
                    <div 
                        class="col-5 col-xs-5 col-sm-4 col-md-3 col-lg-3 col-xl-3"
                        v-for="(product, i) in featured_products"
                        :key="product.id"
                    >
                        <FeaturedProductCard :product="product" />
                    </div>
                </div>

                <q-card bordered flat style="height: 100px" class="bg-grey-3 q-mt-md items-center justify-center flex text-grey" v-if="featured_products.length == 0">
                    No featured products found
                </q-card>
            </div>
            <div class="menu-header bg-white q-pt-md">
                <div class="row">
                    <div class="col-6 col-md-8 col-lg-8 col-xl-8 col-xs-6 col-sm-6">
                        <span class="text-h6">Menu</span>
                        <br>
                        <span>Open till 8 AM to 5 PM</span>
                    </div>
                    <div class="col-6 col-xs-6 col-sm-6 col-md-4 col-lg-4 col-xl-4 self-center">
                        <q-input placeholder="Search..." v-model="search" debounce="300" outlined dense rounded>
                            <template v-slot:prepend>
                                <q-icon name="search"></q-icon>
                            </template>
                        </q-input>
                    </div>
                </div>
                <div class="category-container q-py-sm">
                    <q-btn icon="menu" flat @click="categoryMenu = !categoryMenu">
                        <q-menu class="gt-xs" v-if="$q.screen.gt.xs">
                            <q-list>
                                <q-item 
                                    v-for="category in props.categories" 
                                    @click="scrollToCategory(category)" 
                                    clickable
                                    :key="category.id"
                                >
                                    <q-item-section>{{ category.name }}</q-item-section>
                                </q-item>
                            </q-list>
                        </q-menu>
                    </q-btn>
                    <div class="categories-wrapper" ref="categoriesWrapper">
                        <q-tabs
                            v-model="selectedCategory"
                            class="text-grey"
                            active-color="black"
                            align="left"
                            dense
                        >
                            <q-tab 
                                no-caps 
                                v-for="category in props.categories" 
                                :key="category.id" 
                                :name="category.name" 
                                :label="category.name" 
                                inline-label
                                class="text-black"
                                @click="scrollToCategory(category)" 
                            />
                        </q-tabs>
                    </div>
                    <q-btn class="bg-grey-3 q-mr-sm lt-md" round :size="$q.screen.lt.md ? 'sm' : 'md'" icon="arrow_back" flat @click="scrollLeft" />
                    <q-btn class="bg-grey-3 lt-md" round :size="$q.screen.lt.md ? 'sm' : 'md'" icon="arrow_forward" flat @click="scrollRight" />
                </div>
            </div>
            <q-card flat bordered class="q-pa-xl items-center justify-center flex bg-grey-3" v-if="filteredCategories.length <= 0">
                No Products Found
            </q-card>
            <div 
                v-for="(category, i) in filteredCategories" 
                :key="i" 
                class="q-mt-md category" 
                :id="category.id"
                :ref="el => categoryRefs[category.id] = el"
            >
                <div v-show="category.filteredProducts.length > 0">
                    <q-item class="text-h6">{{ category.name }}</q-item>
                    <div class="row q-col-gutter-md">
                        <div 
                            class="col-12 col-md-4 cold-lg-4 col-xl-4 col-sm-6 col-xs-12" 
                            v-for="product in category.filteredProducts" 
                            :key="product.id"
                        >
                            <ProductCard :product="product" />
                        </div>
                    </div>
                </div>
                <!-- <q-card bordered flat v-if="category.filteredProducts.length == 0" class="text-grey bg-grey-3 justify-center items-center flex q-mt-md" style="height: 100px">
                    No products found...
                </q-card> -->
            </div>
        </div>
        <div class="q-mx-sm q-mb-md" ref="ratingsContainer">
            <div class="q-my-md">
                <div class="text-h6">Rating and Reviews</div>
                <div><q-icon name="star"></q-icon> {{ overAllRating }} • {{ rating_stats.rating_count }} Ratings</div>
            </div>
            <div class="row q-col-gutter-md" v-if="ratings.length > 0">
                <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" v-for="rating in ratings">
                    <q-card bordered flat class="q-py-md">
                        <q-item>
                            <q-item-section avatar>
                                <q-avatar color="primary" text-color="white">
                                    <q-img v-if="rating.user.profile_pic" :src="`/storage/${rating.user.profile_pic}`" class="fit" fit="cover"/>
                                    <div v-else>
                                        {{ rating.user.first_name[0] }}
                                    </div>
                                </q-avatar>
                            </q-item-section>
                            <q-item-section>
                                <q-item-label>
                                    {{ rating.user.first_name + ' ' + rating.user.last_name }} • 
                                    <span class="text-caption">
                                        {{ date.formatDate(rating.created_at, 'M/D/YYYY') }}
                                    </span>
                                </q-item-label>
                                <q-item-label>{{ rating.review }}</q-item-label>
                            </q-item-section>
                            <q-item-section side top>
                                <q-rating v-model="rating.rating" readonly/> 
                            </q-item-section>
                        </q-item>
                    </q-card>
                </div>
            </div>
            <q-card bordered flat class="bg-grey-3 items-center justify-center flex q-pa-xl" v-if="ratings.length == 0">
                No ratings found
            </q-card>
        </div>
    </div>

    <q-dialog 
        v-model="categoryMenu" 
        class="lt-sm" 
        transition-show="slide-up"
        transition-hide="slide-down"
        maximized
        v-if="$q.screen.lt.sm"
        position="bottom"
    >
        <q-card>
            <q-card-section>
                <div class="text-start text-h6">Categories</div>
                <q-list>
                    <q-item 
                        v-for="category in props.categories" 
                        @click="scrollToCategory(category)" 
                        clickable
                        :key="category.id"
                    >
                        <q-item-section>{{ category.name }}</q-item-section>
                    </q-item>
                </q-list>
            </q-card-section>
            <q-card-actions class="fixed-bottom">
                <q-btn v-close-popup color="primary" rounded no-caps class="full-width">Close</q-btn>
            </q-card-actions>
        </q-card>
    </q-dialog>
</CustomerLayout>

</template>

<style scoped>
.category-container {
  display: flex;
  align-items: center;
  overflow-x: hidden;
  width: 100%;
}

.categories-wrapper {
  display: flex;
  overflow-x: auto;
  scroll-behavior: smooth;
  flex-grow: 1;
  padding: 0 8px; /* Adjusts padding to match the design */
  scrollbar-width: none; /* Firefox */
  -ms-overflow-style: none;
}

.q-tab {
  white-space: nowrap;
  padding: 0 12px; /* Adjusts spacing between tabs */
  font-weight: normal;
}

.q-tabs__content {
  display: flex;
  align-items: center; /* Vertically centers the tabs */
  border-bottom: 2px solid black; /* Adds a bottom border to the active tab */
}

/* .q-btn {
  min-width: 0;
  width: auto;
} */

::-webkit-scrollbar {
  height: 4px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
  background: #888;
}

::-webkit-scrollbar-thumb:hover {
  background: #555;
}

.menu-header {
    position: -webkit-sticky; /* For Safari */
    position: sticky;
    top: 50px; /* Adjust this value to control the sticky position */
    z-index: 100;
}

/* .card-image {
  position: relative;
} */

/* .absolute-top-left {
  top: 0;
  left: 0;
}

.absolute-bottom-right {
  bottom: 0;
  right: 0;
} */

/* .featured-items-container {
    margin: auto;
} */

</style>