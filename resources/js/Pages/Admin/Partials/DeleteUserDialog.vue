<script setup>

import { useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { defineEmits } from 'vue'

const emit = defineEmits(['close'])
const $q = useQuasar()
const props = defineProps({
    user: Object,
    dialog: Boolean
})

const form = useForm({})
console.log('manee')
const deleteUser = () => {
    form.delete(route('admin.users.destroy', props.user.id), {
        onSuccess: () => {
            emit('close')
            $q.notify('User Deleted')
        }
    })
}

</script>

<template>
    <q-dialog v-model="props.dialog">
        <q-card>
            <q-card-section>
                Delete User?
            </q-card-section>
            <q-card-actions>
                <q-btn @click="emit('close')">Cancel</q-btn>
                <q-btn @click="deleteUser()" :disable="form.processing" :loading="form.processing">Delete</q-btn>
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

