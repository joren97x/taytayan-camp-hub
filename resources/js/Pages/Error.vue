<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({ status: Number })

const title = computed(() => {
  return {
    503: '503: Service Unavailable',
    500: '500: Server Error',
    404: '404: Page Not Found',
    403: '403: Forbidden',
  }[props.status]
})

const description = computed(() => {
  return {
    503: 'Sorry, we are doing some maintenance. Please check back soon.',
    500: 'Whoops, something went wrong on our servers.',
    404: 'Sorry, the page you are looking for could not be found.',
    403: 'Sorry, you are forbidden from accessing this page.',
  }[props.status]
})
</script>

<template>
    <div class="row reverse justify-center q-pa-xl items-center flex" style="height: 100vh;">
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
            <div class="justify-center items-center flex">
                <q-avatar size="50px">
                    <q-img src="/logo.jpg"></q-img>
                </q-avatar>
                <span class="text-h6 q-ml-md text-primary text-weight-bold">Taytayan Camp Hub</span>
            </div>
            <div class="text-center q-py-xl">
                <div class="text-h3">{{ title }}</div>
                <div class="text-subtitle1 q-my-md">{{ description }}</div>
                <Link :href="route('home')">
                    <q-btn label="Go Back" color="primary"/>
                </Link>
            </div>
        </div>
    </div>
</template>

<style scoped>
a {
    text-decoration: none;
    color: black;
}
</style>