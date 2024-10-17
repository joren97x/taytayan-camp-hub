<script setup>

import { useForm } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { ref } from 'vue'

const $q = useQuasar()
const props = defineProps({
    product: Object
})
const dialog = ref(false)
const form = useForm({})

const deleteProduct = () => {
    form.delete(route('admin.products.destroy', props.product.id), {
        onSuccess: () => {
            dialog.value = false
            $q.notify('Product Deleted')
        }
    })
}

</script>

<template>
    <q-btn
        rounded
        flat
        no-caps 
        label="Delete"
        color="negative"
        @click="dialog = true"
    />
    <q-dialog 
        v-model="dialog" 
        persistent 
        :maximized="$q.screen.lt.md"
        transition-show="slide-up"
        transition-hide="slide-down"
    >
        <q-card :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
            <q-card-section class="row items-center q-pb-none">
                <q-icon name="warning" color="negative" size="32px" />
                <div class="text-h6 q-ml-md">Delete Product</div>
                <q-btn round icon="close" v-close-popup flat class="absolute-top-right q-mt-sm q-mr-sm"/>

            </q-card-section>
            <q-card-section>
                Are you sure you want to delete this product? All data will be permanently removed. This action cannot be undone.
                <q-item class="bg-negative text-white q-my-md">
                    <q-item-section avatar>
                        <q-img :src="`/storage/${product.photo}`" height="100px" width="100px"></q-img>
                    </q-item-section>  
                    <q-item-section>
                        <q-item-label class="text-weight-bold text-subtitle1">{{ product.name }}</q-item-label>
                        <q-item-label >{{ product.description }}</q-item-label>
                        <q-item-label >{{ product.price }}</q-item-label>
                    </q-item-section>
                </q-item>
            </q-card-section>
        
            <q-card-actions align="right">
                <q-btn flat label="Cancel" rounded no-caps color="grey-7" v-close-popup />
                <q-btn 
                    label="Delete" 
                    rounded 
                    no-caps 
                    unelevated 
                    color="negative"
                    @click="deleteProduct" 
                    :loading="form.processing"
                    :disable="form.processing"
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

