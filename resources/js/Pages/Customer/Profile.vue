<script setup>
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { Head, Link, usePage } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'

defineOptions({ layout: CustomerLayout })
const $q = useQuasar()
const $page = usePage()

if ($page.props.flash?.success) {
    $q.notify({
        message: $page.props.flash.success,
        color: 'positive',  // Success message style (you can use 'negative', 'warning', 'info', etc.)
        position: 'top',  // Position can be 'top-right', 'bottom-left', etc.
        timeout: 4000,  // Notification stays for 3 seconds
        icon: 'check_circle',  // Optional icon for success
        textColor: 'white',  // Text color inside the notification
        action: {
            label: 'Close',
            color: 'white',  // Close button color
            handler: () => {
                console.log('Notification closed')
            }
        },
        style: {
            borderRadius: '10px',  // Rounded corners
            padding: '50px',  // Padding inside the notification
            fontSize: '16px',  // Font size of the text
        }
    });
}

const ordersComponents = [
    'Customer/Product/Orders',
    'Customer/Product/ShowOrder'
]

const bookingsComponents = [
    'Customer/Facility/Bookings',
    'Customer/Facility/ShowBooking'
]

const ticketsComponents = [
    'Customer/Event/Tickets',
    'Customer/Event/ShowTicket'
]

</script>

<template>
    <Head title="Profile" />
        <div :class="`row q-col-gutter-md ${$q.screen.gt.md ? 'q-mt-lg' : ''}`">
            <div class="col-xs-12 col-sm-12 xol-md-4 col-lg-4 col-xl-4" v-if="$page.component == 'Customer/Profile' || $q.screen.gt.sm">
                <q-card flat bordered :square="$q.screen.lt.md">
                    <q-card-section>
                        <div class=" items-center justify-center flex">
                            <div>
                                <div class="justify-center flex">
                                    <q-avatar size="80px" color="primary" class="text-white">
                                        <q-img :src="`/storage/${$page.props.auth.user.profile_pic}`" fit="cover" class="fit" v-if="$page.props.auth.user.profile_pic" />
                                        <div v-else>
                                            {{ $page.props.auth.user.first_name[0] }}
                                        </div>
                                    </q-avatar>
                                </div>
                                <div>
                                    <div class="text-h6 text-center">
                                        {{ $page.props.auth.user.first_name + ' ' + $page.props.auth.user.last_name }}
                                    </div>
                                    <div class="">{{ $page.props.auth.user.email }}</div>
                                </div>
                            </div>
                        </div>
                        <q-list class="q-my-md">
                            <Link :href="route('customer.edit_profile')">
                                <q-item clickable class="rounded-borders" :active="$page.component == 'Customer/EditProfile'" active-class="bg-primary text-white">
                                    <q-item-section> Account Details </q-item-section>
                                    <q-item-section side class="lt-md">
                                        <q-icon name="chevron_right" color="black"/>
                                    </q-item-section>
                                </q-item>
                            </Link>
                            <Link :href="route('customer.orders.index')">
                                <q-item clickable class="rounded-borders" :active="ordersComponents.includes($page.component)" active-class="bg-primary text-white">
                                    <q-item-section> Orders </q-item-section>
                                    <q-item-section side class="lt-md">
                                        <q-icon name="chevron_right" color="black"/>
                                    </q-item-section>
                                </q-item>
                            </Link>
                            <Link :href="route('customer.bookings.index')">
                                <q-item clickable class="rounded-borders" :active="bookingsComponents.includes($page.component)" active-class="bg-primary text-white">
                                    <q-item-section> Bookings </q-item-section>
                                    <q-item-section side class="lt-md">
                                        <q-icon name="chevron_right" color="black"/>
                                    </q-item-section>
                                </q-item>
                            </Link>
                            <Link :href="route('customer.tickets.index')">
                                <q-item clickable class="rounded-borders" :active="ticketsComponents.includes($page.component)" active-class="bg-primary text-white">
                                    <q-item-section> Ticket Orders </q-item-section>
                                    <q-item-section side class="lt-md">
                                        <q-icon name="chevron_right" color="black"/>
                                    </q-item-section>
                                </q-item>
                            </Link>
                        </q-list>
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-xs-12 col-sm-12 xol-md-8 col-lg-8 col-xl-8">
                <slot />
            </div>
        </div>
</template>

<style scoped>

a {
    color: black;
    text-decoration: none
}

</style>