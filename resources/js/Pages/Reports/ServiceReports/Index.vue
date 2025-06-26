<template>
  <Head title="Service Report" />
  <AppLayout title="Service Report">
    <form
      @submit.prevent="form.get(route('service-reports-by-type.index'))"
      class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2"
      enctype="multipart/form-data"
    >
      <div class="px-4 py-6 sm:p-8">
        <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-3">
          <div>
            <label
              for="type"
              class="block text-sm md:text-base font-medium text-gray-900"
              >Service Type
              <span class="text-red-500">*</span>
            </label>
            <div class="mt-2 flex rounded-md shadow-sm">
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
          </div>
        </div>
      </div>
      <div
        class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8"
      >
        <button
          type="submit"
          class="rounded-md bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
          :disabled="form.processing"
        >
          Search
        </button>
      </div>
    </form>
    <div class="w-full mx-auto pt-6">
      <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <div
            class="flex flex-col md:flex-row items-start md:items-center justify-between"
          >
            <h1 class="font-semibold text-3xl text-primary">Service report by type</h1>
          </div>

          <div class="mt-6 flow-root">
            <div
              class="ring-1 ring-gray-200 sm:rounded-lg overflow-x-auto overflow-y-hidden"
            >
                <table class="min-w-full border-separate border-spacing-0 divide-y divide-gray-200 overflow-auto">
                  <thead>
                    <tr class="divide-x divide-gray-200">
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                      >
                        ID
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                      >
                        Name
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                      >
                        Service Type
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                      >
                        Charges
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                      >
                        Description
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      class="divide-x divide-gray-200"
                      v-for="(service, index) in services"
                      :key="service.id"
                      :class="index % 2 === 0 ? 'bg-gray-50' : 'bg-white'"
                    >
                      <td
                        class="whitespace-nowrap relative p-4 text-center text-gray-900 text-sm md:text-base"
                      >
                        {{ service.id }}
                      </td>
                      <td
                        class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base"
                      >
                        {{ service.name }}
                      </td>
                      <td
                        class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base"
                      >
                        {{ service.type }}
                      </td>
                      <td
                        class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base"
                      >
                        {{ service.charges }}
                      </td>
                      <td
                        class="relative p-4 w-60 md:w-96 2xl:w-[35rem]"
                      >
                        <div class="font-normal text-gray-900 text-sm md:text-base line-clamp-2">
                          {{ service.description }}
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
    
<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link as InertiaLink, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Multiselect from "vue-multiselect";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import "flatpickr/dist/themes/airbnb.css";

const props = defineProps({
  services: Array,
  departments: Array,
  type: String,
});

const form = useForm({
  type: new URLSearchParams(window.location.search).get("type") || null,
});

// Service Type
const selectedType = ref();
const typeOptions = computed(() => {
  const deductionType = [
    { value: "", label: "All" },
    { value: "IPD", label: "IPD" },
    { value: "OPD", label: "OPD" },
    { value: "Other", label: "Other" },
  ];
  return deductionType;
});

const typeCustomLabel = (option) => option.label;
const onTypeSelect = async (selectedType) => {
  form.type = selectedType.value;
};
if (props?.type) {
  selectedType.value = typeOptions.value.find(
    (option) => option.value === props.type
  );
}
</script>

<script>
export default {
  components: {
    AppLayout,
    Head,
    InertiaLink,
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>