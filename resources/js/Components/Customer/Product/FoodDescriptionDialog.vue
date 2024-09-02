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
        onSuccess: (e) => {
            console.log(e)
            $q.notify(props.product.name + ' Added To Cart')
            emit('close')
        }
    })
}

</script>

<template>
    <q-dialog 
        v-model="show" 
        :maximized="$q.screen.width <= 700"  
        transition-show="slide-up"
        transition-hide="slide-down"
    >
        <!-- <q-img height="170px" :src="`/storage/${product.photo}`">
            <q-btn round icon="close" flat @click="emit('close')"></q-btn>
        </q-img> -->

        <q-card :class="$q.screen.width > 700 ? 'q-pa-sm' : ''" :style="$q.screen.width >= 700 ? 'width: 100%; max-width: 60vw;' : ''">
            <!-- {{ form }} -->
            <q-form @submit="submit">
                <div class="row q-col-gutter-md">
                    <div class="col-12 col-md-5 col-lg-5 col-xl-5 col-sm-12 col-xs-12" style="position: relative;">
                        <q-img fill="cover" :src="`/storage/${product.photo}`" style="position: sticky; top: 50px;" height="40vh"/>
                    </div>
                    <div class="col-12 col-md-7 col-lg-7 col-xl-7 col-sm-12 col-xs-12">
                        <!-- <p>
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
                        </p> -->
                        <q-item>
                            <q-item-section>
                                <q-item-label class="text-h6">{{ product.name }}</q-item-label>
                                <q-item-label >P{{ product.price }}</q-item-label>
                                <q-item-label caption>P{{ product.description }}</q-item-label>
                            </q-item-section>
                            <q-item-section side top>
                                <q-btn round icon="close" flat @click="emit('close')"></q-btn>
                            </q-item-section>
                        </q-item>
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
                            <q-item-section class="text-subtitle1">
                                Special instructions
                            </q-item-section>
                            <q-item-section side>
                                <q-chip :class="$q.dark.isActive ? 'bg-grey-8' : ''">Optional</q-chip>
                            </q-item-section>
                        </q-item>
                        <q-input type="textarea" class="q-mx-md" v-model="form.special_instruction" filled placeholder="Add a note"></q-input>
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
                        <div :class="['row q-col-gutter-md bg-white ', $q.screen.width <= 700 ? 'fixed-bottom' : '']">
                            <div class="col-8">
                                <q-item>
                                    <q-item-section>
                                        <div class="text-subtitle1">
                                            Quantity
                                        </div>
                                        <div>
                                            {{ product.price }}
                                        </div>
                                    </q-item-section>
                                    <q-item-section side>
                                        <q-btn-group style="align-items: center;">
                                            <q-btn round icon="remove" @click="form.quantity--" />
                                            <span style="margin: 0;">{{ form.quantity }}</span>
                                            <q-btn round icon="add" @click="form.quantity++" />
                                        </q-btn-group>
                                    </q-item-section>
                                </q-item>
                            </div>
                            <div class="col-4 self-center">
                                <q-btn
                                    label="Add To Cart"
                                    color="primary"
                                    class="full-width"
                                    type="submit"
                                    no-caps
                                    :loading="form.processing"
                                    :disable="form.processing"
                                />  
                            </div>
                        </div>
                    </div>
                </div>
            </q-form>
        </q-card>
    </q-dialog>
</template>