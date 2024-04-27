<script setup>

import { computed } from 'vue'

const emit = defineEmits(['close'])
const props = defineProps({ dialog: Boolean })
const show = computed(() => props.dialog)

</script>

<template>
    <q-dialog full-width v-model="show">
        <q-card class="my-card q-px-md">
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
                    <q-img src="https://cdn.quasar.dev/img/chicken-salad.jpg" style="position: sticky; top: 50px;" />
                </div>
                <div class="col-7">
                    <p>
                        <span class="text-h5">
                            Cafe Basilico
                        </span>
                        <br>
                        <span class="text-h6 text-weight-light">
                            P30.00
                        </span>
                    </p>
                    <p>
                        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam, totam?
                    </p>
                    <q-separator/>
                    <div v-for="n in 2" :key="n">
                        <q-item>
                            <q-item-section class="text-h6">
                                Choose your size
                                <q-item-label caption>Choose 1</q-item-label>
                            </q-item-section>
                            <q-item-section side>
                                <q-chip :class="$q.dark.isActive ? 'bg-grey-8' : ''">Required</q-chip>
                            </q-item-section>
                        </q-item>
                        <q-list>
                            <q-item clickable v-ripple>
                                <q-item-section>
                                    <q-item-label>Small</q-item-label>
                                    <q-item-label caption>Caption</q-item-label>
                                </q-item-section>
                                <q-item-section side>
                                    <q-checkbox color="secondary"/>
                                </q-item-section>
                            </q-item>
                            <q-item clickable v-ripple>
                                <q-item-section>
                                    <q-item-label>Medium</q-item-label>
                                    <q-item-label caption>+P5.00</q-item-label>
                                </q-item-section>
                                <q-item-section side>
                                    <q-checkbox color="secondary"/>
                                </q-item-section>
                            </q-item>
                            <q-item clickable v-ripple>
                                <q-item-section>
                                    <q-item-label>Large</q-item-label>
                                    <q-item-label caption>+P10.00</q-item-label>
                                </q-item-section>
                                <q-item-section side>
                                    <q-checkbox color="secondary"/>
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
                    <q-input type="textarea" filled placeholder="Add a note"></q-input>
                    <q-item>
                        <q-item-section class="text-h6">
                            Quantity
                        </q-item-section>
                    </q-item>
                    <q-btn-group style="align-items: center;">
                        <q-btn round icon="remove" />
                        <span style="margin: 0;">2</span>
                        <q-btn round icon="add" />
                    </q-btn-group>
                    <q-btn class="full-width q-mt-lg" no-caps color="blue">Update</q-btn>
                    <q-btn class="full-width q-mb-lg q-mt-md" outline no-caps color="red">Remove from cart</q-btn>
                </div>
            </div>
        </q-card>
    </q-dialog>
</template>