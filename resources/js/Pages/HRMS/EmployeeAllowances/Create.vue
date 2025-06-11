<template>
  <Head title="Create Allowance" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ form?.id ? "Update" : "Create" }} Allowance
          </h1>
          <!-- <form
            @submit.prevent="
            form?.id
              ? form.put(
                route('employee-allowances.update', { id: form.id })
              )
            : form.post(route('employee-allowances.store'), form)"
          > -->
          <form  @submit.prevent="
            form.processing = true;
            form?.id
                ? form.put(route('employee-allowances.update', { id: form.id }))
                : form.post(route('employee-allowances.store'), form).finally(() => {
                    form.processing = false;
                })
            ">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 my-6">
              <div class="">
                <label
                  for="employee"
                  class="block text-sm md:text-base font-medium text-gray-900"
                  >Employee
                  <span class="text-red-500">*</span>
                </label>
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedEmployee"
                        :options="employees"
                        :searchable="true"
                        :close-on-select="true"
                        :show-labels="false"
                        @select="onEmployeeSelect"
                        placeholder="Select employee"
                        label="name"
                        track-by="id"
                      />
                    </div>
                    <InputError
                        v-if="form.errors?.employee_id"
                        :message="form.errors?.employee_id"
                      />
                  </div>
                  <div class="">
                    <label
                      for="allowance"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Allowance
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedAllowance"
                        :options="allowances"
                        :searchable="true"
                        :close-on-select="true"
                        @select="onAllowanceSelect"
                        placeholder="Select allowance"
                        label="name"
                        track-by="id"
                      />
                    </div>
                    <InputError
                        v-if="form.errors?.allowance_id"
                        :message="form.errors?.allowance_id"
                      />
                  </div>
                  <div class="">
                    <label
                      for="amount"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Amount
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        id="amount"
                        name="amount"
                        type="text"
                        autocomplete="amount"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.amount"
                      />
                    <InputError
                        v-if="form.errors?.amount"
                        :message="form.errors?.amount"
                      />
                  </div>
                  <div class="">
                    <label
                      for="effective_date"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Effective Date
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        id="effective_date"
                        name="effective_date"
                        type="date"
                        autocomplete="effective_date"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.effective_date"
                      />
                    <InputError
                        v-if="form.errors?.effective_date"
                        :message="form.errors?.effective_date"
                      />
                  </div>
                </div>
              <div
                class="flex items-center justify-center md:justify-end gap-4 py-4"
              >
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('employee-allowances.index')"
                >
                  Cancel
                </InertiaLink>
                <button :disabled="form.processing"
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
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import Multiselect from "vue-multiselect";
import { defineComponent, ref } from "vue";
import InputError from "../../Components/InputError.vue";
const props = defineProps({
  employeeAllowance: Object,
  employees: Array,
  allowances: Array,
});

const form = useForm({
  id: props?.employeeAllowance?.id || null,
  employee_id: props?.employeeAllowance?.employee_id || null,
  allowance_id: props?.employeeAllowance?.allowance_id || null,
  amount: props?.employeeAllowance?.amount || null,
  effective_date: props?.employeeAllowance?.effective_date || null,
});

const selectedEmployee = ref(null);
if (props?.employeeAllowance) {
  const employee = props.employees.find(
    (c) => c.id === props.employeeAllowance.employee_id
  );
  if (employee) {
    selectedEmployee.value = employee;
  }
}
const onEmployeeSelect = async (selectedEmployee) => {
  form.employee_id = selectedEmployee.id;
};

const selectedAllowance = ref(null);
if (props?.employeeAllowance) {
  const allowance = props.allowances.find(
    (c) => c.id === props.employeeAllowance.allowance_id
  );
  if (allowance) {
    selectedAllowance.value = allowance;
  }
}
const onAllowanceSelect = async (selectedAllowance) => {
  form.allowance_id = selectedAllowance.id;
};

const cancel = () => {
  Inertia.visit(route("employee-allowances.index"));
};

defineComponent({
  components: {
    Multiselect,
  },
});
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
