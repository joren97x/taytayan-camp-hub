<script setup>
import { Link, useForm, router } from '@inertiajs/vue3'
import { useQuasar, date } from 'quasar'
import { useNotificationStore } from '@/Stores/NotificationStore'

const notificationStore = useNotificationStore()
const props = defineProps({ notification: Object })
const $q = useQuasar()
const deleteForm = useForm({})
const updateForm = useForm({})
const clickForm = useForm({})
console.log(props.notification)
const deleteNotification = () => {
    deleteForm.delete(route('customer.notifications.destroy', props.notification.id), {
        onSuccess: () => {
            notificationStore.notifications = notificationStore.notifications.filter((notif) => notif.id != props.notification.id)
            $q.notify('Notification Deleted')
        }
    })
}

const updateNotification = () => {
    updateForm.put(route('customer.notifications.update', props.notification.id), {
        onSuccess:() => {
            notificationStore.notifications.map((notif) => {
                if(notif.id == props.notification.id) {
                    notif.is_clicked = !notif.is_clicked 
                    // notif.is_read = !notif.is_read 
                }
            })
        }
    })
}

const onClick = () => {
    updateForm.put(route('customer.notifications.click', props.notification.id), {
        onSuccess:() => {
            notificationStore.notifications.map((notif) => {
                if(notif.id == props.notification.id) {
                    notif.is_clicked = true
                    router.visit(props.notification.link)
                }
            })
        }
    })
}

</script>

<template>
<!-- <Link :href="notification.link"> -->
    <q-item 
        clickable 
        :class="[
            notification.is_clicked ? 'read' : '',
            'q-py-md notif rounded-borders full-width']
        "
        @click="onClick()"
    >
        <q-item-section avatar>
            <q-avatar class="bg-red">
                <q-icon name="close"/>
            </q-avatar>
        </q-item-section>
        <q-item-section>
            <q-item-label> {{ notification.id }} {{ notification.title }}</q-item-label>
            <q-item-label caption>{{ notification.description }}</q-item-label>
            <q-item-label caption>{{ date.formatDate(notification.created_at, 'MMM D, YYYY') }}</q-item-label>
        </q-item-section>
        <q-item-section side>
            <q-item-label>
                <q-btn icon="more_horiz" @click.stop round size="sm" color="white" class="text-black hover-more-horiz">
                    <q-menu>
                        <q-list>
                            <q-item clickable @click="updateNotification()" :disable="updateForm.processing" >
                                <q-item-section avatar>
                                    <q-icon name="check"/>
                                </q-item-section>
                                <q-item-section>
                                    Mark as {{ notification.is_clicked ? 'unread' : 'read' }}
                                </q-item-section>
                            </q-item>
                            <q-item clickable @click="deleteNotification()" :disable="deleteForm.processing">
                                <q-item-section avatar>
                                    <q-icon name="delete"/>
                                </q-item-section>
                                <q-item-section>
                                    Delete this notification
                                </q-item-section>
                            </q-item>
                        </q-list>
                    </q-menu>
                </q-btn>
                <q-icon size="10px" name="circle" class="q-ml-sm" v-show="!notification.is_clicked"/>
            </q-item-label>
        </q-item-section>
    </q-item>
</template>

<style scoped>

a {
    text-decoration: none;
    color: black
}

.hover-more-horiz {
  opacity: 0;
  transition: opacity 0.2s ease;
}

.q-item:hover .hover-more-horiz {
  opacity: 1;
}

.notif:hover {
    background-color: aliceblue
}

.read {
    opacity: 80%;
}

</style>