<script setup>

import { Link, usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue';
import CashierLayout from '@/Layouts/CashierLayout.vue'
import DriverLayout from '@/Layouts/DriverLayout.vue'
import CustomerLayout from '@/Layouts/CustomerLayout.vue'
import { useDrawerStore } from '@/Stores/DrawerStore';


defineProps({
    conversations: Object
})

const drawerStore = useDrawerStore()
const page = usePage()
// const drawer = ref(false)

// const layoutComponent = computed(() => {
//     switch (page.props.auth.user.role) {
//         case 'admin':
//             return AdminLayout
//         case 'cashier':
//             return CashierLayout
//         case 'driver':
//             return DriverLayout
//         case 'customer':
//             return CustomerLayout
//         default:
//             return CustomerLayout // Fallback to customer layout or any default layout
//     }
// })

</script>

<template>
    <!-- <component :is="layoutComponent"> -->
        <div :class="$q.screen.gt.sm ? 'q-pa-md' : ''">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                    <q-card bordered flat style="height: 95vh;">
                        <q-list class="q-pa-sm">
                            <!-- {{ layoutComponent }} -->
                            <q-btn icon="menu" class="lt-md" flat @click="drawerStore.drawer = true"/>
                            <div>Chats</div>
                            <Link :href="route('conversations.show', conversation.id)" v-for="conversation in conversations" >
                                <q-item clickable class="rounded-borders">
                                    <q-item-section avatar>
                                        <q-avatar color="primary" class="text-capitalize" text-color="white">
                                            <div v-for="participant in conversation.participants">
                                                <div v-if="participant.id != $page.props.auth.user.id">
                                                    {{ participant.first_name[0] }}
                                                </div>
                                            </div>
                                        </q-avatar>
                                    </q-item-section>
                                    <q-item-section>
                                        <!-- {{ conversation.user.first_name + ' ' + conversation.user.last_name }} -->
                                        <div v-for="participant in conversation.participants">
                                            <div v-if="participant.id != $page.props.auth.user.id">
                                                {{ participant.first_name + ' ' + participant.last_name }}
                                            </div>
                                        </div>
                                    </q-item-section>
                                </q-item>
                            </Link>
                            <div v-if="conversations.length == 0" class="text-center q-my-md">
                                You dont have any conversations...
                            </div>
                        </q-list>
                    </q-card>
                </div>
                <div class="col-md-9 col-xs-12 col-sm-12 col-lg-9 col-xl-9 bg-grey-3">
                    <div 
                        style="height: 80vh; width: 100%;" 
                        class="justify-center items-center flex text-h6 gt-sm" 
                        v-if="
                            $page.component == 'Cashier/Inbox' ||
                            $page.component == 'Customer/Inbox' ||
                            $page.component == 'Admin/Inbox' ||
                            $page.component == 'Driver/Inbox'
                        ">
                        <q-icon name="chat" size="lg" class="q-mt-xs q-mr-md" />
                        <div>
                            Choose a conversation
                        </div>
                    </div>
                    <slot />
                </div>
            </div>
        </div>
    <!-- </component> -->
</template>