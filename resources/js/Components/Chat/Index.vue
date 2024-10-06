<script setup>

import { Link } from '@inertiajs/vue3'

defineProps({
    conversations: Object
})

</script>

<template>
    <div class="q-pa-md">
        <div class="row">
            <div class="col-3">
                <q-card bordered flat>
                    <q-list class="q-pa-sm">
                        {{ $page.props.auth.user.role }}
                        conversations . show
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
                    </q-list>
                </q-card>
            </div>
            <div class="col-9 bg-grey">
                <div style="height: 100vh; width: 100%;" class="justify-center items-center flex text-h6" v-if="$page.component == 'Cashier/Inbox'">
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