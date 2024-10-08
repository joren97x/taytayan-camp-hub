<script setup>

import { Head, useForm, Link } from '@inertiajs/vue3'
// import AdminLayout from '@/Layouts/AdminSidebar.vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useQuasar } from 'quasar'
import { ref, watch, onMounted } from 'vue'

defineOptions({
    layout: AdminLayout
})

const props = defineProps({
    categories: Object,
    product: Object,
    modifier_groups: Object
})

const addModifierGroupDialog = ref(false)
const $q = useQuasar()
const selected = ref([])
const form = useForm({
    name: props.product.name,
    description: props.product.description,
    price: props.product.price,
    categories: props.product.categories,
    is_featured: props.product.is_featured ? true : false,
    available: props.product.available ? true : false
})

const photoForm = useForm({
    photo: null
})

const addModifierGroupForm = useForm({
    modifier_group_ids: props.product.modifier_groups.map((m) => m.id)
})

const columns = [
  { name: 'name', align: 'center', label: 'Name', field: 'name', sortable: true },
  { name: 'options', align: 'center', label: 'Options', field: 'options', sortable: true },
]

const submit = () => {
    
    form.put(route('admin.products.update', props.product.id), {
        onSuccess: () => {
            $q.notify('Product Successfully Updated')
        }
    })
}

const submitphotoForm = () => {
    
    photoForm.post(route('admin.products.update_photo', props.product.id), {
        onSuccess: () => {
            $q.notify('Product Photo Successfully Updated')
            photoForm.photo = null
        }
    })
}

const updateProductModifierGroup = () => {
    console.log(props.product.id)
    addModifierGroupForm.put(route('admin.products.update_modifier_group', props.product.id), {
        onSuccess: () => {
            addModifierGroupDialog.value = false
            $q.notify('Product Modifier Groups Updated')
        }
    })
}

const clearModifierGroupForm = useForm({})
const clearModifierGroupDialog = ref(false)

const submitClearModifierGroup = () => {
    clearModifierGroupForm.delete(route('admin.products.clear_modifier_group', props.product.id), {
        onSuccess: () => {
            clearModifierGroupDialog.value = false
            $q.notify('Modifier Groups Cleared')
        }
    })
}

onMounted(() => {
    selected.value = props.modifier_groups.filter(group => 
        addModifierGroupForm.modifier_group_ids.includes(group.id)
    )
    form.categories = props.product.categories.map(row => row.id)
})

watch(selected, (modifier_group) => {
    console.log(modifier_group)
    addModifierGroupForm.modifier_group_ids = modifier_group.map(row => row.id)
})

watch(photoForm, () => {
    const reader = new FileReader();
    reader.onload = (e) => {
      console.log(e.target.result); // Set the preview to the image URL
    };
    console.log('the fuck')
    console.log(photoForm.photo)
    
})

const productPhotoRef = ref(null)
const imgPreview = ref('')

const triggerFilePicker = () => {
    productPhotoRef.value.pickFiles();
};

const onFileChange = (file) => {
    imgPreview.value = URL.createObjectURL(file)
}
</script>

<template>
    
    <Head title="Edit Product" />
    <div class="q-pa-md">
        <q-card bordered flat>
            <q-card-section style="position: sticky; top: 0; z-index: 99;" class="q-pa-none q-pt-md q-px-md bg-white">
                <div  class="row flex justify-center bg-white">
                    <Link :href="route('admin.products.index')">
                        <q-btn icon="arrow_back" flat class="absolute-top-left q-ml-md q-mt-md " label="Go Back" no-caps/>
                    </Link>
                    <div class="text-h6">Edit Product</div>
                    
                </div>
                <q-separator class="q-mt-md"/>
            </q-card-section>
            <q-card-section>
                <q-form @submit="submit">
                    <!-- <div class="row justify-between" style="z-index: 400;">
                        <div class="text-center col-12" style="position: relative">
                            <div class="text-h6 ">Edit Product</div>
                            <Link :href="route('admin.products.index')" class="absolute-left text-subtitle2 items-center flex" >
                                <q-btn icon="arrow_back" label="Go Back" no-caps flat />
                            </Link>
                            <q-btn icon="edit_square" unelevated class="absolute-right" label="Status" no-caps color="negative" />
                        </div>
                    </div> -->
                    <p class="text-weight-bold text-h6">Product photo</p>
                    <q-item class="q-my-md">
                        <q-item-section avatar>
                            <q-img 
                                :src="photoForm.photo ? imgPreview : `/storage/${product.photo}`" 
                                style="width: 100px; height: 100px;" 
                                fit="contain"    
                            />
                        </q-item-section>
                        <q-item-section>
                            <q-file 
                                v-model="photoForm.photo"
                                :error="photoForm.errors.photo ? true : false"
                                :error-message="photoForm.errors.photo"
                                style="display: none;"
                                ref="productPhotoRef"
                                @update:model-value="onFileChange"
                            />
                            <q-item-label>Photos can help customers decide what to order and can increase sale.</q-item-label>
                            <q-item-label caption>File requirement: JPG, PNG</q-item-label>
                            <q-item-label>
                                <q-btn 
                                    no-caps color="primary" 
                                    v-if="photoForm.photo" 
                                    @click="submitphotoForm()"
                                    :loading="photoForm.processing"
                                    :disable="photoForm.processing"
                                >
                                    Save
                                </q-btn>
                                <q-btn no-caps color="primary" v-else @click="triggerFilePicker">Change photo</q-btn>
                            </q-item-label>
                        </q-item-section>
                    </q-item>
                    <q-separator class="q-my-lg" />
                    <p class="text-weight-bold text-h6">Product Details</p>
                    <q-input 
                        label="Name" 
                        v-model="form.name" 
                        filled
                        :error="form.errors.name ? true : false"
                        :error-message="form.errors.name"
                    />
                    
                    <q-input 
                        label="Description" 
                        v-model="form.description" 
                        filled 
                        type="textarea"
                        :error="form.errors.description ? true : false"
                        :error-message="form.errors.description"
                    >
                    </q-input>
                    <q-select 
                        filled 
                        class="q-mt-lg"
                        label="Categories" 
                        multiple
                        v-model="form.categories"
                        option-label="name"
                        option-value="id"
                        emit-value
                        use-chips
                        map-options
                        :options="props.categories"
                        :error="form.errors.categories ? true : false"
                        :error-message="form.errors.categories"
                    >
                    </q-select>
                    <q-input 
                        filled 
                        label="Default Price" 
                        v-model="form.price" 
                        placeholder="None" 
                        class="q-mt-md"
                        :error="form.errors.price ? true : false"
                        :error-message="form.errors.price"
                        mask="#.##"
                        fill-mask="0"
                        reverse-fill-mask
                    >
                        <template v-slot:prepend>
                            <q-icon name="attach_money" />
                        </template>
                    </q-input>
                    <q-checkbox v-model="form.is_featured" label="Feature Product" />
                    <q-checkbox v-model="form.available" label="Available" />
                    <br>
                    <q-btn 
                        @click="submit"
                        no-caps 
                        color="primary" 
                        class=""
                        :loading="form.processing"
                        :disable="form.processing"
                        label="Update"
                    />
                    <!-- <q-btn 
                        type="submit" 
                        no-caps 
                        color="primary" 
                        class="q-mr-sm full-width"
                        :loading="form.processing"
                        :disable="form.processing"
                    >
                        Save
                    </q-btn> -->
                    <q-separator class="q-my-lg" />
                    
                    <p class="text-weight-bold text-h6">Modifier Groups</p>
                    <p class="text-weight-light">
                        Modifier groups allow customers to customize items
                    </p>
                    <q-expansion-item
                        switch-toggle-side
                        expand-separator
                        v-for="modifier_group in product.modifier_groups"
                        :key="modifier_group.id"
                    >
                    <template v-slot:header>
                        <q-item-section>
                            {{ modifier_group.name }}
                        </q-item-section>

                        <q-item-section side v-if="modifier_group.required">
                            <div class="row items-center">
                                <q-chip>Required</q-chip>
                            </div>
                        </q-item-section>
                    </template>
                        <q-card>
                        <q-card-section>
                            <q-list>
                                <q-item v-for="modifier_item in modifier_group.modifier_items" :key="modifier_item.id">
                                    <q-item-section>
                                        <q-item-label>{{ modifier_item.name }}</q-item-label>
                                    </q-item-section>

                                    <q-item-section side top>
                                        <q-item-label caption>P{{ modifier_item.price }}</q-item-label>
                                    </q-item-section>
                                </q-item>
                            </q-list>
                        </q-card-section>
                        </q-card>
                    </q-expansion-item>
                    <div class="q-my-lg">
                        <q-chip 
                            outline 
                            clickable 
                            color="primary" 
                            text-color="white" 
                            size="lg" 
                            @click="addModifierGroupDialog = true"
                        >
                            <span class="text-subtitle2">Add Modifier Group</span>
                        </q-chip>
                        <q-chip 
                            outline 
                            clickable 
                            color="primary" 
                            text-color="white" 
                            size="lg" 
                            @click="clearModifierGroupDialog = true"
                        >
                            <span class="text-subtitle2">Clear</span>
                        </q-chip>
                    </div>
                </q-form>
            </q-card-section>
        </q-card>
    </div>
    <q-dialog 
        v-model="addModifierGroupDialog"
        transition-show="slide-up"
        transition-hide="slide-down"
        :maximized="$q.screen.lt.md"
    >
        <q-card :style="$q.screen.gt.sm ? 'max-width: 70vw; width: 100%;' : ''">
            <q-form @submit="updateProductModifierGroup">
                <q-card-section>
                    <q-table
                        class="my-sticky-header-column-table"
                        flat
                        title="Add Modifier Group"
                        :rows="props.modifier_groups"
                        :columns="columns"
                        row-key="name"
                        selection="multiple"
                        v-model:selected="selected"
                    >
                        <template v-slot:body-cell-name="props">
                            <q-td :props="props">
                                {{ props.row.name }}
                            </q-td>
                        </template>
                        <template v-slot:body-cell-options="props">
                            <q-td :props="props">
                                <span v-for="modifier_item in props.row.modifier_items" :key="modifier_item.id">
                                    {{ modifier_item.name + ', ' }}
                                </span>
                            </q-td>
                        </template>
                    </q-table>
                </q-card-section>
                <q-card-actions align="right">
                    <q-btn unelevated no-caps v-close-popup>Cancel</q-btn>
                    <q-btn 
                        unelevated 
                        no-caps 
                        type="submit" 
                        color="primary" 
                        :loading="addModifierGroupForm.processing"
                        :disable="addModifierGroupForm.processing"
                    >
                        Save
                    </q-btn>
                </q-card-actions>
            </q-form>
        </q-card>
    </q-dialog>
    <q-dialog 
        v-model="clearModifierGroupDialog"
        transition-show="slide-up"
        transition-hide="slide-down"
        :maximized="$q.screen.lt.md"
    >
        <q-card :style="$q.screen.gt.sm ? 'max-width: 50vw; width: 100%;' : ''">
            <q-card-section>
                Clear modifier groups?
            </q-card-section>
            <q-card-actions>
                <q-space/>
                <q-btn v-close-popup no-caps>Cancel</q-btn>
                <q-btn
                    color="negative"
                    @click="submitClearModifierGroup"
                    :loading="clearModifierGroupForm.processing"
                    :disable="clearModifierGroupForm.processing"
                    label="Clear"
                    no-caps
                />
            </q-card-actions>
        </q-card>
    </q-dialog>
</template>

<style scoped>

a {
    text-decoration: none;
}


</style>