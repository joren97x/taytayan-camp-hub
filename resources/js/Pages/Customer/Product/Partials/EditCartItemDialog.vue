<script setup>

import { computed, ref } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import axios from 'axios'

const emit = defineEmits(['close'])
const page = usePage()
const $q = useQuasar()
const props = defineProps({ dialog: Boolean, cart_item: Object })
const show = computed(() => props.dialog)

console.log(props)
const form = useForm({
    product_id: null,
    cart_id: page.props.auth.cart_id,
    special_instruction: '',
    quantity: 1,
    modifiers: []
})

const handleItemSelection = (modifierGroupId, modifierItemId) => {
    const index = form.modifiers.findIndex(modifier => modifier.modifier_group_id === modifierGroupId && modifier.modifier_item_id === modifierItemId);
    if (index === -1) {
        form.modifiers.push({
            modifier_group_id: modifierGroupId,
            modifier_item_id: modifierItemId,
            quantity: 1 // You can manage quantity if needed
        });
    } else {
        form.modifiers.splice(index, 1); // Remove the modifier if already selected
    }
}




const isSelected = (modifierGroupId, modifierItemId) => {
    return form.modifiers.some(modifier => modifier.modifier_group_id === modifierGroupId && modifier.modifier_item_id === modifierItemId);
};

const submit = () => {
    form.put(route('customer.cart.update', props.cart_item.id), {
        onSuccess: () => {
            emit('close')
            $q.notify('Food Updated')
        }
    })
}

const modifierGroups = ref(null)

function onDialogShow() {

    form.product_id = props.cart_item.product.id
    form.special_instruction = props.cart_item.special_instruction
    form.quantity = props.cart_item.quantity
    const groupedModifiers = props.cart_item.grouped_modifiers

// Loop through each modifier group in the groupedModifiers
    for (const key in groupedModifiers) {
        if (groupedModifiers.hasOwnProperty(key)) {
            const group = groupedModifiers[key];
            const modifierGroupId = group.modifier_group.id;

            // Loop through each modifier item in the current group
            group.modifier_items.forEach(item => {
                form.modifiers.push({
                    modifier_group_id: modifierGroupId,
                    modifier_item_id: item.modifier_item.id,
                    quantity: item.quantity // Use the quantity from the item if needed
                });
            });
        }
    }

    axios.get(route('product.show', props.cart_item.product.id))
    .then((res) => {
        modifierGroups.value = res.data.modifier_groups
        console.log(res)
    })
    .catch((err) => {
        console.error(err)
    })

   
}

</script>

<template>
    <q-dialog 
        v-model="show" 
        @show="onDialogShow()"
        :maximized="$q.screen.lt.md"  
        transition-show="slide-up"
        transition-hide="slide-down"
    >
        <q-card class="q-px-md" :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
            <q-form @submit="submit">
                <!-- <q-item>
                    <q-item-section class="text-h6">
                        Food description
                    </q-item-section>
                    <q-item-section side>
                        <q-btn round icon="close" flat @click="emit('close')"></q-btn>
                    </q-item-section>
                </q-item> -->
                <div class="row q-col-gutter-md">
                    <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5" style="position: relative;">
                        <q-img 
                            fit="contain"
                            :src="`/storage/${cart_item.product.photo}`" 
                            style="max-width: 100%; height: 40vh; position: sticky;"
                        />
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7" style="position: relative;">
                        <q-btn round icon="close" flat @click="emit('close')" class="absolute-top-right q-mt-lg"/>
                        <div class="q-mt-md">
                            <span class="text-h6">
                                {{ cart_item.product.name }}
                            </span>
                            <br>
                            <span class="text-subtitle1">
                                P{{ cart_item.product.price }}
                            </span>
                            <br>
                            {{ cart_item.product.description }}
                        </div>
                        <q-separator/>
                        <div v-for="(modifier_group, index) in modifierGroups" :key="index">
                            <q-item>
                                <q-item-section class="text-h6">
                                    {{ modifier_group.name }}
                                    <q-item-label caption>Choose {{ modifier_group.required_quantity }}</q-item-label>
                                </q-item-section>
                                <q-item-section side v-if="modifier_group.required">
                                    <q-chip :class="$q.dark.isActive ? 'bg-grey-8' : ''">Required</q-chip>
                                </q-item-section>
                            </q-item>
                            <q-list>
                                <q-item
                                    clickable
                                    v-ripple
                                    v-for="modifier_item in modifier_group.modifier_items"
                                    :key="modifier_item.id"
                                    @click="handleItemSelection(modifier_group.id, modifier_item.id)"

                                >
                                    <q-item-section>
                                        <q-item-label>{{ modifier_item.name }}</q-item-label>
                                        <q-item-label caption class="text-green">+P{{ modifier_item.price }} </q-item-label>
                                    </q-item-section>
                                    <q-item-section side>
                                        <q-checkbox
                                            color="secondary"
                                            :model-value="isSelected(modifier_group.id, modifier_item.id)"
                                            @update:model-value="checked => handleItemSelection(modifier_group.id, modifier_item.id)"
                                        />
                                    </q-item-section>
                                </q-item>
                            </q-list>
                            <q-separator/>
                        </div>
                        <q-item>
                            <q-item-section class="text-h6">
                                Special instructions
                            </q-item-section>
                            <q-item-section side>
                                <q-chip :class="$q.dark.isActive ? 'bg-grey-8' : ''">Optional</q-chip>
                            </q-item-section>
                        </q-item>
                        <q-input type="textarea" v-model="form.special_instruction" filled placeholder="Add a note"></q-input>
                        <!-- <q-item>
                            <q-item-section class="text-h6">
                                Quantity
                            </q-item-section>
                        </q-item>
                        <q-btn-group style="align-items: center;">
                            <q-btn round icon="remove" @click="form.quantity--" />
                            <span style="margin: 0;">{{ form.quantity }}</span>
                            <q-btn round icon="add" @click="form.quantity++" />
                        </q-btn-group>
                        <q-btn
                            class="full-width q-my-lg"
                            type="submit"
                            no-caps
                            color="blue"
                            :loading="form.processing"
                            :disable="form.processing"
                        >
                            Add to cart
                        </q-btn> -->
                        <q-card flat>
                            <q-card-section class="row">
                                <div class="col-4 items-center flex">
                                    <div>Quantity</div>
                                </div>
                                <div class="col-8 justify-end items-center flex">
                                    <q-btn icon="remove" size="sm" round unelevated class="bg-grey-4" @click="form.quantity--"></q-btn>
                                    <span class="q-mx-md text-subtitle1">{{ form.quantity }}</span>
                                    <q-btn icon="add" size="sm" round unelevated class="bg-grey-4" @click="form.quantity++"></q-btn>
                                    <q-btn
                                        type="submit"
                                        no-caps
                                        rounded
                                        class="q-ml-sm"
                                        color="primary"
                                        :loading="form.processing"
                                        :disable="form.processing"
                                    >
                                        Update
                                    </q-btn>
                                </div>
                            </q-card-section>
                        </q-card>
                    </div>
                </div>
            </q-form>
        </q-card>
    </q-dialog>
</template>