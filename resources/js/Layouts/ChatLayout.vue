<script setup>

import { onMounted, ref, watch } from 'vue'
import AdminSidebar from './AdminSidebar.vue'
import { router } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import axios from 'axios'

const conversations = ref([])
const tab = ref('chats')

onMounted(() => {
    axios.get(route(`conversations.index`))
    .then((res) => {
        console.log(res)
        res.data.conversations.forEach((el) => {
            conversations.value.push(el)
        })
    })
    .catch((err) => {
        console.error(err)
    })
})

</script>

<template>
        
    <AdminSidebar>
        {{ conversations }}
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
                            <div class="text-h6">chats</div>
                            <q-list>
                                <q-item v-for="conversation in conversations" clickable @click="router.get(route(`${$page.props.auth.user.role}.conversation.show`, conversation.id))">
                                    <!-- <q-item v-for="conversation in conversations" clickable @click="getConversation(conversation)"> -->
                                    <q-item-section avatar>
                                        <q-avatar color="primary" class="text-capitalize" text-color="white">
                                            <!-- {{ conversation.user.first_name[0] }} -->
                                            J
                                        </q-avatar>
                                    </q-item-section>
                                    <q-item-section>
                                        <!-- {{ conversation.user.first_name + ' ' + conversation.user.last_name }} -->
                                        Name
                                    </q-item-section>
                                </q-item> 
                            </q-list>
                        </q-tab-panel>
                        <q-tab-panel name="people">
                            <div class="text-h6">people</div>
                            <q-list>
                                <q-item v-for="user in users" clickable @click="getChat(user)">
                                    <q-item-section avatar>
                                        <q-avatar color="primary" class="text-capitalize" text-color="white">
                                            {{ user.first_name[0] }}
                                        </q-avatar>
                                    </q-item-section>
                                    <q-item-section>
                                        {{ user.first_name + ' ' + user.last_name }}
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
    </AdminSidebar>
  
  </template>