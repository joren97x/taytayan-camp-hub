<script setup>

import { computed, ref } from 'vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import axios from 'axios'

const emit = defineEmits(['close', 'remove_from_cart'])
const page = usePage()
const $q = useQuasar()
const props = defineProps({ dialog: Boolean, cart_item: Object })
const show = computed(() => props.dialog)

const form = useForm({
    product_id: null,
    cart_id: page.props.auth.cart_id,
    special_instruction: '',
    quantity: 1,
    modifiers: []
})

const handleItemSelection = (modifierGroupId, modifierItemId, requiredQuantity) => {
  if (requiredQuantity === 1) {
    // If required_quantity is 1, replace the current selection
    form.modifiers = form.modifiers.filter(modifier => modifier.modifier_group_id !== modifierGroupId);

    form.modifiers.push({
      modifier_group_id: modifierGroupId,
      modifier_item_id: modifierItemId,
      quantity: 1
    });
  } else {
    // For checkboxes (required_quantity > 1), allow multiple selections
    const selectedCount = form.modifiers.filter(modifier => modifier.modifier_group_id === modifierGroupId).length;

    if (isSelected(modifierGroupId, modifierItemId)) {
      // Remove the modifier if it's already selected (toggle off)
      form.modifiers = form.modifiers.filter(
        modifier => !(modifier.modifier_group_id === modifierGroupId && modifier.modifier_item_id === modifierItemId)
      );
    } else if (selectedCount < requiredQuantity) {
      // Add the modifier if it's not selected and under the limit
      form.modifiers.push({
        modifier_group_id: modifierGroupId,
        modifier_item_id: modifierItemId,
        quantity: 1
      });
    }
  }
};

const isSelected = (modifierGroupId, modifierItemId) => {
  return form.modifiers.some(modifier => modifier.modifier_group_id === modifierGroupId && modifier.modifier_item_id === modifierItemId);
};

// Get the selected radio item (for required_quantity === 1)
const getSelectedItem = (modifierGroupId) => {
  const selected = form.modifiers.find(modifier => modifier.modifier_group_id === modifierGroupId);
  return selected ? selected.modifier_item_id : null;
};

// Limit checkbox selections when required_quantity > 1
const isMaxSelected = (modifierGroupId, requiredQuantity) => {
  const selectedCount = form.modifiers.filter(modifier => modifier.modifier_group_id === modifierGroupId).length;
  return selectedCount >= requiredQuantity;
};
const submit = () => {
    form.put(route('customer.cart.update', props.cart_item.id), {
        onSuccess: () => {
            emit('close')
            $q.notify('Food Updated')
        },
        onError: (error) => {
            if(error.modifiers) {
                $q.notify({
                    message: `Required fields must be selected`,
                    color: 'negative', // or any custom color defined in the brand config
                    textColor: 'white',
                    position: 'top'
                })
            }
            console.log(error)
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

const handleRadioSelection = (modifierGroupId, modifierItemId) => {
  // Remove any previously selected item for this modifier group
    form.modifiers = form.modifiers.filter(modifier => modifier.modifier_group_id !== modifierGroupId);
    
    // Add the newly selected item
    form.modifiers.push({
        modifier_group_id: modifierGroupId,
        modifier_item_id: modifierItemId,
        quantity: 1
    });
};

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
            <div class="absolute-top-right q-mt-sm q-mr-sm lt-md">
                <q-btn rounded no-caps icon="close" label="Remove from cart" color="negative" @click="emit('remove_from_cart')" class="q-mr-sm"/>
                <q-btn round icon="close" v-close-popup/>
            </div>

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
                        <q-btn round icon="close" flat @click="emit('close')" class="absolute-top-right q-mt-lg gt-sm"/>
                        <div class="q-mt-md text-center">
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
                                    @click="handleItemSelection(modifier_group.id, modifier_item.id, modifier_group.required_quantity)"
                                >
                                    <q-item-section>
                                        <q-item-label>{{ modifier_item.name }}</q-item-label>
                                        <q-item-label caption class="text-green">+P{{ modifier_item.price }} </q-item-label>
                                    </q-item-section>
                                    <q-item-section side>
                                        <q-radio 
                                            v-if="modifier_group.required_quantity === 1"
                                            color="primary"
                                            :model-value="getSelectedItem(modifier_group.id)"
                                            :val="modifier_item.id"
                                            @update:model-value="selected => handleRadioSelection(modifier_group.id, modifier_item.id)"
                                        />

                                        <!-- Use q-checkbox when required_quantity is greater than 1 -->
                                        <q-checkbox
                                            v-else
                                            color="primary"
                                            :disable="isMaxSelected(modifier_group.id, modifier_group.required_quantity) && !isSelected(modifier_group.id, modifier_item.id)"
                                            :model-value="isSelected(modifier_group.id, modifier_item.id)"
                                            @update:model-value="checked => handleItemSelection(modifier_group.id, modifier_item.id, modifier_group.required_quantity)"
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