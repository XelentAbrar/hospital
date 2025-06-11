<template>
  <Head title="Create Department" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ department?.id ? "Update" : "Create" }} Department
          </h1>
            <!-- <form
              @submit.prevent="
                department?.id
                  ? department.put(
                    route('departments.update', {
                        id: department.id,
                      })
                    )
                  : department.post(route('departments.store'), department)
              "
              enctype="multipart/form-data"
            > -->
            <form  @submit.prevent="
            department.processing = true;
            department?.id
                ? department.put(route('departments.update', { id: department.id }))
                : department.post(route('departments.store'), department).finally(() => {
                    department.processing = false;
                })
            ">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 py-6">
              <div>
                <label
                  for="name"
                  class="block text-sm md:text-base font-medium text-gray-900"
                  >Name
                  <span class="text-red-500">*</span>
                  </label
                >
                  <input
                    type="text"
                    name="name"
                    id="name"
                    autocomplete="given-name"
                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                    v-model="department.name"
                  />
                <InputError
                  v-if="department.errors?.name"
                  :message="department.errors?.name"
                />
                  </div>
                  <div v-if="hasAccountsModule">
                <label
                  for="name"
                  class="block text-sm md:text-base font-medium text-gray-900"
                  >Account Head
                  <!-- <span class="text-red-500">*</span> -->
                  </label
                >
                  <multiselect
                  class="mt-1"
                  v-model="selectedCoa"
                  :options="coa"
                  @update:modelValue="onCoaSelect"
                  label="acc_desc"
                  track-by="id"
                  placeholder="Select a Account Head"
                ></multiselect>
                <InputError
                  v-if="department.errors?.coa_id"
                  :message="department.errors?.coa_id"
                />
                  </div>
                </div>
              <div
                class="flex items-center justify-center md:justify-end gap-4 py-4"
              >
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('departments.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                  :disabled="department.processing"
                >
                  Save
                </button>
              </div>
            </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import InputError from "../../Components/InputError.vue";
import Multiselect from "vue-multiselect";
import { ref } from "vue";

const props = defineProps({
  hasAccountsModule:Boolean,
  type:String,
  coa:Array,
  department: {
    type: Object,
    default: null,
  },
});

const department = useForm({
  id: props?.department?.id || null,
  name: props?.department?.name || null,
  coa_id: props?.department?.coa_id || null,
  type: props?.department?.type || props?.type,
});

const selectedCoa = ref(null);
if (props?.department) {
  const coas = props.coa.find(
    (c) => c.id === props.department.coa_id
  );
  if (coas) {
    selectedCoa.value = coas;
  }
}

const onCoaSelect = async (selectedCoa) => {
  department.coa_id = selectedCoa?.id ?? null;
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
