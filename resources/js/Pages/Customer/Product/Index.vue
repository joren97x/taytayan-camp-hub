<script setup>

import { Head, Link } from '@inertiajs/vue3'
import { ref, onMounted, computed } from 'vue'
import { useQuasar } from 'quasar'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import ProductCard from './Partials/ProductCard.vue'
import FeaturedProductCard from './Partials/FeaturedProductCard.vue'
import NewAddressDialog from '@/Components/Customer/NewAddressDialog.vue'

defineOptions({
    layout: CustomerLayout
})

const $q = useQuasar()
const props = defineProps({
    categories: Object,
    products: Object,
    featured_products: Object,
    google_maps_api_key: String,
    ratings: Object
})
const overAllRating = ref(4)
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
const menuHeight = ref(0);  

const scrollToCategory = (category) => {
    console.log('clicked')
    categoryMenu.value = false
    setTimeout(() => {
        const element = categoryRefs.value[category.id];
        selectedCategory.value = category.name;
        
        if (element) {
            const offset = element.getBoundingClientRect().top + window.pageYOffset - menuHeight.value - 150;
            window.scrollTo({ top: offset, behavior: 'smooth' });
        }
        
        console.log(element);
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

// Method to scroll left
const scrollLeftFeatured = () => {
  const container = scrollContainer.value;
  container.scrollBy({
    left: -container.clientWidth, // Scroll left by the width of the container
    behavior: 'smooth'
  });
};

// Method to scroll right
const scrollRightFeatured = () => {
  const container = scrollContainer.value;
  container.scrollBy({
    left: container.clientWidth, // Scroll right by the width of the container
    behavior: 'smooth'
  });
};

const search = ref('');

// Computed property to filter products
const filteredCategories = computed(() => {
  if (!search.value) {
    // Return all categories with their original products
    return props.categories.map(category => ({
      ...category,
      filteredProducts: category.products
    }));
  }

  // Lowercase search term for case-insensitive search
  const searchTerm = search.value.toLowerCase();
  console.log('hi')
  console.log(searchTerm)
  // Filter each category based on the search in products' name or description
//   return props.categories
//     .map(category => {
//       // Filter the products within each category
//       const filteredProducts = category.products.filter(product =>
//         product.name.toLowerCase().includes(searchTerm) ||
//         product.description.toLowerCase().includes(searchTerm)
//       );

//       // Return the category with the filtered products
      
//       return {
//         ...category,
//         filteredProducts
//       };
//     })
//     .filter(category => category.filteredProducts.length > 0); // Remove categories with no matching products
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

const slide = ref('style')
const lorem = ref('stylestylestylestylestylestyle')

</script>

<template>
    <Head title="Milktea Menu" />
    <div>
        <NewAddressDialog 
            :dialog="showNewAddressDialog" 
            @close="showNewAddressDialog = false"
            :google_maps_api_key="google_maps_api_key" 
            v-if="$page.props.auth.user"
        />
        <!-- <q-img
            cover
            src="masarap milktea.jpeg"
            :ratio="16/9"
            height="300px"
            class="rounded-borders"
        /> -->
        <div>
        <q-carousel
            v-model="slide"
            transition-prev="slide-right"
            transition-next="slide-left"
            animated
            swipeable
            control-color="red"
            navigation
        >
            <q-carousel-slide name="tv" class="column no-wrap flex-center q-pa-none">
                <q-img src="https://scontent.fmnl8-6.fna.fbcdn.net/v/t39.30808-6/327176035_2597203457087463_6666288379572413234_n.jpg?_nc_cat=100&ccb=1-7&_nc_sid=cc71e4&_nc_eui2=AeHT6bAH_xzh6Vg9iFUnt9gsxyJehLiQb3PHIl6EuJBvcw4nDhus6WwDL6T4fK7p9R8-8LqvpNDq2urrB75YENcK&_nc_ohc=E7uRMRCh2pcQ7kNvgFqMqeT&_nc_ht=scontent.fmnl8-6.fna&oh=00_AYADGlYxrGEIUMRv-umEj4-opVYiYHGjWNA0Af3WDHP9nA&oe=66F32406"></q-img>
            </q-carousel-slide>
            <q-carousel-slide name="style" class="column no-wrap flex-center q-pa-none">
                <q-img src="https://t3.ftcdn.net/jpg/04/64/30/76/360_F_464307620_CYWfA00cdwwtYPxXMfFpbTjVjaFYmP93.jpg"></q-img>
            </q-carousel-slide>
            <q-carousel-slide name="layers" class="column no-wrap flex-center q-pa-none">
                <q-img src="https://scontent.fmnl8-1.fna.fbcdn.net/v/t39.30808-6/429651402_889256136542523_718280217750389034_n.png?_nc_cat=108&ccb=1-7&_nc_sid=cc71e4&_nc_eui2=AeHGGK0N0_9U3uEEKF4u42UKzlPQJZH18AfOU9AlkfXwB7Um-T8361oFY_dmTbLWO6gXGJ5aBriU_bqyr-tCIUig&_nc_ohc=T36VYrD1TpwQ7kNvgHAG6iL&_nc_ht=scontent.fmnl8-1.fna&_nc_gid=AtWspy-0XSBL8CaIaUaOrsc&oh=00_AYBTfSFPnmVVkZc-Q0__gNDt5KJyH_ChrO4HPWKr-S5FnQ&oe=66F331CE"></q-img>
            </q-carousel-slide>
            <q-carousel-slide name="map" class="column no-wrap flex-center q-pa-none">
                <q-icon name="terrain" color="primary" size="56px" />
                <div class="q-mt-md text-center">
                    {{ lorem }}
                </div>
            </q-carousel-slide>
            <template v-slot:navigation-icon="{ active, btnProps, onClick }">
                <q-btn v-if="active" size="5px" :icon="btnProps.icon" color="primary" flat round dense @click="onClick" />
                <q-btn v-else size="5px" :icon="btnProps.icon" color="grey" flat round dense @click="onClick" />
            </template>
        </q-carousel>

    </div>
        <div class="q-mx-sm">
            <div class="row q-col-gutter-md q-mt-xs">
                <div class="col-12 col-xs-12 col-sm col-md-8 col-lg-8 col-xl-8">
                    <p :class="[$q.screen.lt.md ? 'text-center' : '']">
                        <span class="text-h4 text-weight-bold">RJC Cafe</span>
                        <br>
                        <span><q-icon name="star"></q-icon>4.6 • (6)</span>
                        <br>
                        <span>San Vicente, Olango Island</span>
                    </p>
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Qui, explicabo bomboclattt lmao.</p>
                    <div v-if="$page.props.auth.user">
                        <q-banner 
                            rounded 
                            class="bg-primary" 
                            v-if="!$page.props.auth.user.address"
                        >
                            <span class="text-white text-weight-bold text-subtitle1">E butang imong address para ka order ka.</span>
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
                            <span class="text-white text-weight-bold text-subtitle1">E butang imong address para ka order ka.</span>
                            <Link :href="route('login')">
                                <q-btn 
                                    icon="pin_drop" 
                                    label="Place your address" 
                                    no-caps 
                                    color="white" 
                                    class="full-width text-black q-my-xs"
                                >
                                </q-btn>   
                            </Link>         
                        </q-banner>
                    </div>

                    <div class="row justify-between q-mt-md q-mb-sm">
                        <div class="text-h6">
                            Rating and Reviews
                        </div>
                        <!-- <div class="">
                            <q-btn icon="arrow_downward" no-caps color="primary">Show More</q-btn>
                        </div> -->
                    </div>
                    <div ref="scrollContainer" class="row q-col-gutter-md no-wrap hide-scrollbar q-py-xs" style="overflow-x: auto; scroll-behavior: smooth;">
                        <div class="col-md-2 col-xs-5 col-sm-5 col-lg-2 col-xl-2">
                            <q-card class="q-py-md" style="max-height: 600px; height: 115px">
                                <q-card-section class="q-pa-none text-center">
                                    <div class="text-h5">4.8</div>
                                    <div class="column items-center">
                                        <q-rating size="xs" v-model="overAllRating" />
                                    </div>
                                    6 Ratings
                                </q-card-section>
                            </q-card>
                        </div>
                        <div 
                            class="col-5 col-xs-7 col-sm-7 col-md-4 col-lg-4 col-xl-4"
                            v-for="(review, i) in 10"
                            :key="review.id"
                        >
                            <q-card style="max-height: 600px; height: 115px">
                                <q-card-section class="q-pb-none ellipsis-2-lines">
                                    "Lorem ipsum dolor sit amet consectetur adipisicing elit. Commo.pisicing elit. Commo"
                                </q-card-section>
                                <q-card-actions class="q-pt-sm">
                                    <q-rating/>
                                    • Lissa H. • 02/01/23
                                </q-card-actions>
                            </q-card>
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
                        <q-card-section>
                            <q-item title="San vicente olango" subtitle="boang">
                                <q-item-section avatar>
                                    <q-icon name="signal_wifi_off" />
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
            <div class="featured-items-container q-mt-md">
                <div class="text-h6 row">
                    <div class="col">
                        Featured items
                    </div>
                    <div class="col flex justify-end">
                        <q-btn icon="arrow_back" flat @click="scrollLeftFeatured"/>
                        <q-btn icon="arrow_forward" flat @click="scrollRightFeatured"/>
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
            </div>
            <div class="menu-header bg-white q-pt-md">
                <div class="row">
                    <div class="col-6 col-md-8 col-lg-8 col-xl-8 col-xs-6 col-sm-6">
                        <span class="text-h6">Menu</span>
                        <br>
                        <span>Open till 8AM to 5PM or ambot</span>
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
                                @click="scrollToCategory(category)" 
                            />
                        </q-tabs>
                    </div>
                    <q-btn icon="arrow_back" flat @click="scrollLeft" />
                    <q-btn icon="arrow_forward" flat @click="scrollRight" />
                </div>
            </div>
            <div style="height: 30vh;" class="column bg-grey q-mt-md" v-if="filteredCategories.length <= 0">
                <div class="col items-center self-center flex">
                    No Products Found
                </div>
            
            </div>
            <div 
                v-for="(category, i) in filteredCategories" 
                :key="i" 
                class="q-mt-md category" 
                :id="category.id"
                :ref="el => categoryRefs[category.id] = el"
            >
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
            
        </div>
        <div class="q-mx-sm">
            <div class="q-my-md">
                <div class="text-h6">Rating and Reviews</div>
                <div><q-icon name="star"></q-icon> 4.7 • 110+ Ratings • 3 Reviews</div>
            </div>
            <div class="row q-col-gutter-md">
                <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6" v-for="rating in ratings">
                    <q-card bordered>
                        <q-card-section>
                            <span class="text-subtitle1">{{ rating.user.first_name + ' ' + rating.user.last_name }}</span>
                            <br>
                            <q-rating></q-rating> • {{ rating.created_at }}
                            <br>
                            {{ rating.review }}
                        </q-card-section>
                    </q-card>
                </div>
            </div>
        </div>
    </div>

    <q-dialog 
        v-model="categoryMenu" 
        class="lt-sm" 
        transition-show="slide-up"
        transition-hide="slide-down"
        maximized
        v-if="$q.screen.lt.sm"
    >
        <q-card style="margin-top: 30%;">
            <q-card-section>
                <div class="text-center">Lorem idivsum dolor sit amet.</div>
                <div class="text-center">Lorem idivsum dolor sit amet.</div>
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
                <q-btn v-close-popup color="primary" no-caps class="full-width">Close</q-btn>
            </q-card-actions>
        </q-card>
    </q-dialog>

    <div style="height: 200px;" class="bg-grey q-mt-md">
        ang footer ayaw kalimti
    </div>
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
    top: 55px; /* Adjust this value to control the sticky position */
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