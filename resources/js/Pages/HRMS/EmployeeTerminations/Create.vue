<template>
  <Head title="Create Termination" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ form?.id ? "Update" : "Create" }} Termination
          </h1>
            <form
              @submit.prevent="handleSubmit"
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
                      for="termination_type"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Termination Type
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedTerminationType"
                        :options="terminationTypeOptions"
                        @select="onTerminationTypeSelect"
                        label="label"
                        track-by="value"
                        placeholder="Select Termination Type"
                      ></multiselect>
                    </div>
                    <InputError
                        v-if="form.errors?.termination_type"
                        :message="form.errors?.termination_type"
                      />
                  </div>
                  <div>
                    <label
                      for="approved_by"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Approved By
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedApprovedBy"
                        :options="employees"
                        label="name"
                        track-by="id"
                        @select="onApprovedBySelect"
                        placeholder="Select Approved By"
                      ></multiselect>
                    </div>
                    <InputError
                        v-if="form.errors?.approved_by"
                        :message="form.errors?.approved_by"
                      />
                  </div>
                  <div>
                    <label
                      for="termination_date"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Termination Date
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        id="termination_date"
                        name="termination_date"
                        type="date"
                        autocomplete="termination_date"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.termination_date"
                      />
                    <InputError
                        v-if="form.errors?.termination_date"
                        :message="form.errors?.termination_date"
                      />
                  </div>
                  <div class="col-span-2">
                    <label
                      for="termination_reason"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Termination Reason
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        id="termination_reason"
                        name="termination_reason"
                        type="text"
                        autocomplete="termination_reason"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.termination_reason"
                      />
                    <InputError
                        v-if="form.errors?.termination_reason"
                        :message="form.errors?.termination_reason"
                      />
                  </div>
                </div>
              <div
                class="flex items-center justify-center md:justify-end gap-4 py-4"
              >
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('employee-terminations.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                  :disabled="submitting"
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
  employeeTermination: Object,
});

const form = useForm({
  id: props?.employeeTermination?.id || null,
  employee_id: props?.employeeTermination?.employee_id || null,
    termination_type: props?.employeeTermination?.termination_type || null,
    termination_reason: props?.employeeTermination?.termination_reason || null,
    termination_date: props?.employeeTermination?.termination_date || null,
    approved_by: props?.employeeTermination?.approved_by || null,
});

// employee
const selectedEmployee = ref(null);
const onEmployeeSelect = async (selectedEmployee) => {
    form.employee_id = selectedEmployee.id;
};
if(props?.employeeTermination?.employee_id){
    selectedEmployee.value = props.employees.find(employee => employee.id === props.employeeTermination.employee_id);
}

// Termination Type
const selectedTerminationType = ref();
const terminationTypeOptions = computed(() => {
  const terminationType = [
    { value: "voluntary", label: "Voluntary" },
    { value: "involuntary", label: "inVoluntary" },
    { value: "other", label: "Other" },
  ];
  return terminationType;
});

const onTerminationTypeSelect = async (selectedTerminationType) => {
  form.termination_type = selectedTerminationType.value;
};
if(props?.employeeTermination?.termination_type){
  selectedTerminationType.value = terminationTypeOptions.value.find(option => option.value === props.employeeTermination.termination_type);
}

// Approved by
const selectedApprovedBy = ref(null);

const onApprovedBySelect = (selectedEmployee) => {
  form.approved_by = selectedEmployee.id;
};

if(props?.employeeTermination?.approved_by){
  selectedApprovedBy.value = props.employees.find(employee => employee.id === props.employeeTermination.approved_by);
}

const submitting = ref(false);

const handleSubmit = () => {
  if (submitting.value) return;

  submitting.value = true;

  const method = form?.id ? 'put' : 'post';
  const url = form?.id
    ? route('employee-terminations.update', { id: form.id })
    : route('employee-terminations.store');

  form[method](url, {
    onFinish: () => {
      submitting.value = false;
    },
  });
};


const cancel = () => {
  Inertia.visit(route("employee-terminations.index"));
};

defineComponent({
  components: {
    Multiselect,
  },
});
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>