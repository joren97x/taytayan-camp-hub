<script setup>
    import CustomerLayout from '@/Layouts/CustomerLayout.vue'
    import { Head, Link } from '@inertiajs/vue3'

    defineOptions({
        layout: CustomerLayout
    })

    import { useQuasar } from 'quasar';

    const $q = useQuasar()

</script>

<template>
    <Head title="Dashboard" />

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        <q-btn @click="$q.notify('hi')">Notify</q-btn>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">You're logged in!</div>
                    <Link :href="route('profile.edit')"> Profile </Link>
                </div>
            </div>
        </div>
</template>
