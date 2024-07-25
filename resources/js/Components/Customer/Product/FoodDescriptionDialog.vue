<script setup>

import { computed } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'

const emit = defineEmits(['close'])
const page = usePage()
const $q = useQuasar()
const props = defineProps({ dialog: Boolean, product: Object })
const show = computed(() => props.dialog)
const form = useForm({
    product_id: props.product.id,
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
};

const isSelected = (modifierGroupId, modifierItemId) => {
    return form.modifiers.some(modifier => modifier.modifier_group_id === modifierGroupId && modifier.modifier_item_id === modifierItemId);
};

const submit = () => {
    form.post(route('customer.cart.store'), {
        onSuccess: () => {
            $q.notify(props.product.name + ' Added To Cart')
            emit('close')
        }
    })
}

</script>

<template>
    <q-dialog full-width v-model="show">
        <q-card class="q-px-md">
            {{ form }}
            <q-form @submit="submit">
                <q-item>
                    <q-item-section class="text-h6">
                        Food description
                    </q-item-section>
                    <q-item-section side>
                        <q-btn round icon="close" flat @click="emit('close')"></q-btn>
                    </q-item-section>
                </q-item>
                <div class="row q-col-gutter-md">
                    <div class="col-5" style="position: relative;">
                        <q-img :src="`/storage/${product.photo}`" style="position: sticky; top: 50px;" />
                    </div>
                    <div class="col-7">
                        <p>
                            <span class="text-h5">
                                {{ product.name }}
                            </span>
                            <br>
                            <span class="text-h6 text-weight-light">
                                P{{ product.price }}
                            </span>
                        </p>
                        <p>
                            {{ product.description }}
                        </p>
                        <q-separator/>
                        <div v-for="(modifier_group, index) in product.modifier_groups" :key="index">
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
                        <q-item>
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
                        </q-btn>
                    </div>
                </div>
            </q-form>
        </q-card>
    </q-dialog>
</template>