<script setup>
import { Head, Link } from '@inertiajs/vue3'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

defineOptions({
    layout: CustomerLayout
})

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        
        
        <div
        >
            <div>
                <header >
                    <nav v-if="canLogin" class="-mx-3 flex flex-1 justify-end">
                        <q-btn
                            v-if="$page.props.auth.user"
                            :to="route('dashboard')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Dashboard
                        </q-btn>

                        <template v-else>
                            <Link 
                                :href="route('login')"
                                v-if="canLogin"
                                >
                                <q-btn>
                                    Log in
                                </q-btn>
                            </Link>

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                            >
                                <q-btn>

                                    Register
                                </q-btn>
                            </Link>
                        </template>
                    </nav>
                </header>


                <footer>
                    {{ $page }}
                    Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
                </footer>
            </div>
        </div>
    </div>
</template>


