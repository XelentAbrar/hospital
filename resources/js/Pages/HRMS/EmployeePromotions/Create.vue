<template>
  <Head title="Create Promotion" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ form?.id ? "Update" : "Create" }} Promotion
          </h1>  
          <form
              @submit.prevent="
              form?.id
                  ? form.put(
                      route('employee-promotions.update', { id: form.id })
                    )
                  : form.post(route('employee-promotions.store'), form)
              "
            >
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 py-6">
                  <div>
                    <label
                      for="employee"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Employee
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedEmployee"
                        :options="employees"
                        @select="onEmployeeSelect"
                        :searchable="true"
                        :close-on-select="true"
                        :show-labels="false"
                        placeholder="Select employee"
                        label="name"
                        track-by="id"
                        :reduce="(employee) => employee.id"
                      />
                    </div>
                    <InputError
                        v-if="form.errors?.employee_id"
                        :message="form.errors?.employee_id"
                      />
                  </div>
                  <div>
                    <label
                      for="from_designation_id"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >From Designation
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedFromDesignation"
                        :options="designations"
                        @select="onFromDesignationSelect"
                        label="name"
                        track-by="id"
                        placeholder="Select From Designation"
                      ></multiselect>
                    </div>
                    <InputError
                        v-if="form.errors?.from_designation_id"
                        :message="form.errors?.from_designation_id"
                      />
                  </div>
                  <div>
                    <label
                      for="to_designation_id"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >To Designation
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedToDesignation"
                        :options="designations"
                        @select="onToDesignationSelect"
                        label="name"
                        track-by="id"
                        placeholder="Select To Designation"
                      ></multiselect>
                    </div>
                    <InputError
                        v-if="form.errors?.to_designation_id"
                        :message="form.errors?.to_designation_id"
                      />
                  </div>
                  <div>
                    <label
                      for="promotion_date"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Promotion Date
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        id="promotion_date"
                        name="promotion_date"
                        type="date"
                        autocomplete="promotion_date"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.promotion_date"
                      />
                    <InputError
                        v-if="form.errors?.promotion_date"
                        :message="form.errors?.promotion_date"
                      />
                  </div>
                  <div class="sm:col-span-2">
                    <label
                      for="reason"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Promotion Reason</label
                    >
                      <input
                        id="reason"
                        name="reason"
                        type="text"
                        autocomplete="reason"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.reason"
                      />
                    <InputError
                        v-if="form.errors?.reason"
                        :message="form.errors?.reason"
                      />
                  </div>
                </div>
              <div
                class="flex items-center justify-center md:justify-end gap-4 py-4"
              >
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('employee-promotions.index')"
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
  employees: Array,
  designations: Array,
  employeePromotion: Object,
});

const form = useForm({
    id: props?.employeePromotion?.id || null,
    employee_id: props?.employeePromotion?.employee_id || null,
    from_designation_id: props?.employeePromotion?.from_designation_id || null,
    to_designation_id: props?.employeePromotion?.to_designation_id || null,
    promotion_date: props?.employeePromotion?.promotion_date || null,
    reason: props?.employeePromotion?.reason || null,
});

// employee
const selectedEmployee = ref(null);
const onEmployeeSelect = async (selectedEmployee) => {
    form.employee_id = selectedEmployee.id;
};
if(props?.employeePromotion?.employee_id){
    selectedEmployee.value = props.employees.find(employee => employee.id === props.employeePromotion.employee_id);
}

// from_designation
const selectedFromDesignation = ref(null);
const onFromDesignationSelect = async (selectedFromDesignation) => {
    form.from_designation_id = selectedFromDesignation.id;
};
if(props?.employeePromotion?.from_designation_id){
    selectedFromDesignation.value = props.designations.find(
  (designation) => designation.id === props.employeePromotion.from_designation_id
);
}

// to_designation
const selectedToDesignation = ref(null);
const onToDesignationSelect = async (selectedToDesignation) => {
    form.to_designation_id = selectedToDesignation.id;
};
if(props?.employeePromotion?.to_designation_id){
    selectedToDesignation.value = props.designations.find(
      (designation) => designation.id === props.employeePromotion.to_designation_id
    );
}

const cancel = () => {
  Inertia.visit(route("employee-promotions.index"));
};

defineComponent({
  components: {
    Multiselect,
  },
});
</script>

<style src="vue-multiselect/dist/vue-multiselect.css"></style>
