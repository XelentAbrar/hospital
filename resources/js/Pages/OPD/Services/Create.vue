<template>
  <Head title="Create Service" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg">
          <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <h1 class="font-semibold text-3xl text-primary">
              {{ form?.id ? "Update" : "Create" }} Service
            </h1>
            <form
              @submit.prevent="
                form?.id
                  ? form.put(route('services.update', { id: form.id }))
                  : form.post(route('services.store'), form)
              "
            >
                <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-4 py-6">
                  <div>
                    <label
                      for="name"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Name
                      <span class="text-red-500">*</span>
                    </label>
                      <input
                        id="name"
                        name="name"
                        type="text"
                        autocomplete="name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.name"
                      />
                    <InputError
                      v-if="form.errors?.name"
                      :message="form.errors?.name"
                    />
                  </div>
                  <div>
                    <label
                      for="type"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Service Type
                      <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedType"
                        :options="typeOptions"
                        :custom-label="typeCustomLabel"
                        @select="onTypeSelect"
                        label="type"
                        track-by="value"
                        placeholder="Select Service Type"
                      ></multiselect>
                    </div>
                    <InputError
                      v-if="form.errors?.type"
                      :message="form.errors?.type"
                    />
                  </div>
                  <div>
                    <label
                      for="category_id"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Category
                      <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedCategory"
                        :options="categoryOptions"
                        @select="onCategorySelect"
                          @remove="onCategoryUnselect"
                        label="name"
                        track-by="id"
                        placeholder="Select Service cATEGORY"
                      ></multiselect>
                    </div>
                    <InputError
                      v-if="form.errors?.category_id"
                      :message="form.errors?.category_id"
                    />
                  </div>

                  <div>
                    <label
                      for="charges"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Charges
                      <span class="text-red-500">*</span>
                    </label>
                      <input
                        id="charges"
                        name="charges"
                        type="number"
                        autocomplete="charges"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.charges"
                      />
                    <InputError
                      v-if="form.errors?.charges"
                      :message="form.errors?.charges"
                    />
                  </div>
                  <div class="sm:col-span-3 md:col-span-2 col-span-12" v-if="hasAccountsModule">
                    <label
                      for="coa_id"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Account Head
                      <span class="text-red-500">*</span>
                    </label>
                    <multiselect
                      v-model="selectedCoa"
                      :options="coa"
                      @update:modelValue="onCoaSelect"
                      label="acc_desc"
                      track-by="id"
                      placeholder="Select a Account Head"
                    ></multiselect>
                    <InputError
                      v-if="form.errors?.coa_id"
                      :message="form.errors?.coa_id"
                    />
                  </div>
                  <div>
                    <label
                      for="is_nursing"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Is Nurse
                    </label>
                      <input
                        id="is_nursing"
                        name="is_nursing"
                        type="checkbox"
                        autocomplete="is_nursing"
                        class="mt-1   rounded border-0 px-4 py-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base "
                        :true-value="true"
                        :false-value="false"
                        :checked="form.is_nursing == '1'"
                        v-model="form.is_nursing"
                      />
                    <InputError
                      v-if="form.errors?.is_nursing"
                      :message="form.errors?.is_nursing"
                    />
                  </div>

                  <div>
                    <label
                      for="is_advance"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Is Advance
                    </label>
                      <input
                        id="is_advance"
                        name="is_advance"
                        type="checkbox"
                        class="mt-1   rounded border-0 px-4 py-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base "
                        v-model="form.is_advance"
                      />
                    <InputError
                      v-if="form.errors?.is_advance"
                      :message="form.errors?.is_advance"
                    />
                  </div>
                  <div class="sm:col-span-4">
                    <label
                      for="description"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Description</label
                    >
                    <div class="mt-1">
                      <textarea
                        id="description"
                        name="description"
                        row="3"
                        autocomplete="description"
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                        v-model="form.description"
                      ></textarea>
                    </div>
                    <InputError
                      v-if="form.errors?.description"
                      :message="form.errors?.description"
                    />
                  </div>
                </div>
              <div
                class="flex items-center justify-center md:justify-end gap-4 py-4"
              >
                <InertiaLink
                  class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('services.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                >
                  Save
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
  <script setup>
import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import Multiselect from "vue-multiselect";
import { ref, computed, defineComponent, watch } from "vue";
import InputError from "../../Components/InputError.vue";

const props = defineProps({
  hasAccountsModule:Boolean,
  coa: Array,
  service: Object,
  categories: Object,
});

const form = useForm({
  id: props?.service?.id || null,
  name: props?.service?.name || null,
  type: props?.service?.type || null,
  charges: props?.service?.charges || null,
  is_nursing: props?.service?.is_nursing == '1' ? true : false,
  is_advance: props?.service?.is_advance == '1' ? true : false,
  category_id: props?.service?.category_id || null,
  description: props?.service?.description || null,
  coa_id: props?.service?.coa_id || null,
});

// Service Type
const selectedType = ref();
const typeOptions = computed(() => {
  const deductionType = [
    { value: "IPD", label: "IPD" },
    { value: "OPD", label: "OPD" },
    { value: "Other", label: "Other" },
  ];
  return deductionType;
});


const categories = ref(props.categories || []);
const selectedCategory = ref(null);
watch(selectedCategory, (newVal) => {
  form.category_id = newVal ? newVal.id : null;
});


const categoryOptions = computed(() => {
  return categories.value.length ? categories.value : [];
});

const onCategorySelect = (selectedCategory) => {
    console.log(selectedCategory);
  form.category_id = selectedCategory ? selectedCategory.id : null;
};
const onCategoryUnselect = () => {
  form.category_id = null;
  selectedCategory.value = null;
};

if (props?.service) {
  const country = categories.value.find(
    (c) => c.id == props.service.category_id
  );
  if (country) {
    selectedCategory.value = country;
  }
}

const selectedCoa = ref(null);
if (props?.service) {
  const coas = props.coa.find(
    (c) => c.id === props.service.coa_id
  );
  if (coas) {
    selectedCoa.value = coas;
  }
}

const onCoaSelect = async (selectedCoa) => {
  form.coa_id = selectedCoa?.id ?? null;
};


// const categoryOptions = computed(() => {
//   return categories.value && categories.value.length ? categories.value : [];
// });

// const onCategorySelect = async (selectedCategory) => {
//   form.category_id = selectedCategory.id;
// };

const typeCustomLabel = (option) => option.label;
const onTypeSelect = async (selectedType) => {
  form.type = selectedType.value;
};
if (props?.service?.type) {
  selectedType.value = typeOptions.value.find(
    (option) => option.value === props.service.type
  );
}

const cancel = () => {
  Inertia.visit(route("services.index"));
};

defineComponent({
  components: {
    Multiselect,
  },
});
</script>
  <style src="vue-multiselect/dist/vue-multiselect.css"></style>
