<script setup>

import { onMounted, ref } from 'vue'

const conversations = ref([])

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
    <div class="q-pa-md">
        <div class="row">
            <div class="col-4">
                <q-card bordered flat>
                    <q-card-section>
                        <q-list>
                            <q-item v-for="conversation in conversations">
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
                        </q-list>
                    </q-card-section>
                </q-card>
            </div>
            <div class="col-8">
                <div style="height: 500px; width: 500px;" class="bg-grey"></div>
                <slot />
            </div>
        </div>
    </div>
</template>