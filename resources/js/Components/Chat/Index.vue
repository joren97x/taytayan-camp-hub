<script setup>

import { Link } from '@inertiajs/vue3'

defineProps({
    conversations: Object
})

</script>

<template>
    <div class="q-pa-md">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3">
                <q-card bordered flat>
                    <q-list class="q-pa-sm">
                        <q-item-label header>Chats</q-item-label>
                        <Link :href="route('conversations.show', conversation.id)" v-for="conversation in conversations" >
                            <q-item clickable>
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
            <div class="col-md-9 col-xs-12 col-sm-12 col-lg-9 col-xl-9 bg-grey">
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
</template>