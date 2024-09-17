<script setup>
console.log('chatlayout . vue')

import { onMounted, ref, watch } from 'vue'
// import AdminSidebar from './AdminSidebar.vue'
import { router } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import axios from 'axios'

const conversations = ref([])
const people = ref([])
const tab = ref('chats')

onMounted(() => {
    axios.get(route(`conversations.get_users_with_convo`))
    .then((res) => {
        console.log(res)
        res.data.conversations.forEach((el) => {
            conversations.value.push(el)
        })
    })
    .catch((err) => {
        console.error(err)
    })

    axios.get(route('conversations.get_users'))
    .then((res) => {
        console.log(res)
        res.data.people.forEach((el) => {
            people.value.push(el)
        })
    })
    .catch((err) => {
        console.error(err)
    })

})

</script>

<template>
        
    <!-- <AdminSidebar> -->
        <!-- <slot/> -->
        <div class="row">
            <div class="col-4">
                <q-card>
                    <q-tabs
                        v-model="tab"
                        dense
                        class="text-grey"
                        active-color="primary"
                        indicator-color="primary"
                        align="justify"
                        narrow-indicator
                    >
                        <q-tab name="chats" label="chats" />
                        <q-tab name="people" label="people" />
                    </q-tabs>
                    <q-separator />
                    <q-tab-panels v-model="tab" animated>
                        <q-tab-panel name="chats">
                            <div class="text-h6">chats 
                            </div>
                            <q-list>
                                <Link v-for="conversation in conversations" :href="route('conversations.show', conversation.id)">
                                    <q-item clickable>
                                    <!-- <q-item v-for="conversation in conversations" clickable @click="getConversation(conversation)"> -->
                                        <q-item-section avatar>
                                            <q-avatar color="primary" class="text-capitalize" text-color="white">
                                                <!-- {{ conversation.user.first_name[0] }} -->
                                                J
                                            </q-avatar>
                                        </q-item-section>
                                        <q-item-section>
                                            {{ conversation.participants[0].first_name }}
                                        </q-item-section>
                                    </q-item> 
                                </Link>
                            </q-list>
                        </q-tab-panel>
                        <q-tab-panel name="people">
                            <div class="text-h6">people</div>
                            <q-list>
                                <q-item v-for="person in people">
                                    <q-item-section avatar>
                                        <q-avatar color="primary" class="text-capitalize" text-color="white">
                                            {{ person.first_name[0] }}
                                        </q-avatar>
                                    </q-item-section>
                                    <q-item-section>
                                        {{ person.first_name + ' ' + person.last_name }}
                                    </q-item-section>
                                </q-item> 
                            </q-list>
                        </q-tab-panel>
                    </q-tab-panels>
                </q-card>

            </div>
            <div class="col-8 q-pb-md">
                <slot/>
                <!-- <ChatBox v-if="currentConversation" :conversation="currentConversation"/> -->
            </div>
        </div>
    <!-- </AdminSidebar> -->
  
  </template>