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

const submit = () => {
    form.post(route('customer.cart.store'), {
        onSuccess: (e) => {
            console.log(e)
            $q.notify(props.product.name + ' Added To Cart')
            emit('close')
            form.reset('modifiers', 'quantity', 'special_instruction')
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

// Handle checkbox selection when required_quantity > 1
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

// Check if a modifier item is selected
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

const decrementQuantity = () => {
    if(form.quantity > 1) {
        form.quantity--
    }
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
                        <!-- so kuhaa ang modifier group id and then check pilay required quantity then if 1 ra kay poydi if nalapas
                        kay dili na  -->
                        <q-img
                            fit="contain"
                            :src="`/storage/${product.photo}`" 
                            style="max-width: 100%; height: 40vh; position: sticky;"
                        />
                        <q-btn round icon="close" flat @click="emit('close')" class="absolute-top-right q-mt-md lt-md"></q-btn>
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
                        <div class="row justify-end">
                            <div>
                                <q-btn round icon="close" flat @click="emit('close')" class="gt-sm"></q-btn>
                            </div>
                        </div>
                        <q-item>
                            <q-item-section class="text-center">
                                <q-item-label class="text-h6">{{ product.name }}</q-item-label>
                                <q-item-label class="text-subtitle1">P{{ product.price }}</q-item-label>
                                <q-item-label caption>P{{ product.description }}</q-item-label>
                            </q-item-section>
                        </q-item>
                        <!-- <q-separator/> -->
                        <div v-for="(modifier_group, index) in product.modifier_groups" :key="index">
                            <q-item>
                                <q-item-section class="text-h6">
                                    {{ modifier_group.name }}
                                    <q-item-label caption>Choose {{ modifier_group.required_quantity }}</q-item-label>
                                    <!-- <q-item-label caption>Max Quantity each item {{ modifier_group.max_quantity }}</q-item-label> -->
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
                                    <!-- <q-item-section side>
                                        <q-checkbox 
                                            color="primary"
                                            :model-value="isSelected(modifier_group.id, modifier_item.id)"
                                            @update:model-value="checked => handleItemSelection(modifier_group.id, modifier_item.id)"
                                        />

                                    </q-item-section> -->
                                    <q-item-section side>
                                        <!-- Use q-radio when required_quantity is 1 -->
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
                        </div>
                        <div :class="`q-mx-md ${$q.screen.lt.md ? 'q-mb-xl' : ''}`">
                            <q-item class="q-pa-none">
                                <q-item-section class="text-h6">
                                    Special instructions
                                </q-item-section>
                                <q-item-section side>
                                    <q-chip :class="$q.dark.isActive ? 'bg-grey-8' : ''">Optional</q-chip>
                                </q-item-section>
                            </q-item>
                            <q-input type="textarea" v-model="form.special_instruction" filled placeholder="Add a note"></q-input>
                            <div :class="['row q-col-gutter-md bg-white q-mt-xs', $q.screen.width <= 700 ? 'fixed-bottom' : '']">
                                <div class="col-8">
                                    <q-card flat>
                                        <q-card-section class="row">
                                            <div class="col-6 items-center flex">
                                                <div>Quantity</div>
                                            </div>
                                            <div class="col-6 justify-end items-center flex">
                                                <q-btn icon="remove" size="sm" round unelevated class="bg-grey-4" @click="decrementQuantity"></q-btn>
                                                <span class="q-mx-md text-subtitle1">{{ form.quantity }}</span>
                                                <q-btn icon="add" size="sm" round unelevated class="bg-grey-4" @click="form.quantity++"></q-btn>
                                            </div>
                                        </q-card-section>
                                    </q-card>
                                </div>
                                <div class="col-4 self-center">
                                    <q-btn
                                        label="Add To Cart"
                                        color="primary"
                                        class="full-width"
                                        type="submit"
                                        rounded
                                        no-caps
                                        :loading="form.processing"
                                        :disable="form.processing"
                                    />  
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </q-form>
        </q-card>
    </q-dialog>
</template>