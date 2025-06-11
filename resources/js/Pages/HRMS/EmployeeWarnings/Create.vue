<template>
  <Head title="Create Warning" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ form?.id ? "Update" : "Create" }} Warning
          </h1>
            <form
              @submit.prevent="
              form?.id
                  ? form.put(
                      route('employee-warnings.update', { id: form.id })
                    )
                  : form.post(route('employee-warnings.store'), form)
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
                        :searchable="true"
                        :close-on-select="true"
                        :show-labels="false"
                        @select="onEmployeeSelect"
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
                      for="warning_type"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Warning Type
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedWarningType"
                        :options="WarningTypeOptions"
                        :custom-label="WarningTypeCustomLabel"
                        @select="onWarningTypeSelect"
                        label="type"
                        track-by="value"
                        placeholder="Select Warning Type"
                      ></multiselect>
                    </div>
                    <InputError
                        v-if="form.errors?.warning_type"
                        :message="form.errors?.warning_type"
                      />
                  </div>
                  <div>
                    <label
                      for="warning_reason"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Warning Reason  <span class="text-red-500">*</span></label
                    >
                      <input
                        id="warning_reason"
                        name="warning_reason"
                        type="text"
                        autocomplete="warning_reason"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.warning_reason"
                      />
                    <InputError
                        v-if="form.errors?.warning_reason"
                        :message="form.errors?.warning_reason"
                      />
                  </div>
                  <div>
                    <label
                      for="additional_notes"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Additional Notes</label
                    >
                      <input
                        id="additional_notes"
                        name="additional_notes"
                        type="text"
                        autocomplete="additional_notes"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.additional_notes"
                      />
                    <InputError
                        v-if="form.errors?.additional_notes"
                        :message="form.errors?.additional_notes"
                      />
                  </div>
                  <div>
                    <label
                      for="warning_date"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Warning Date
                      <span class="text-red-500">*</span>
                      </label
                    >
                      <input
                        id="warning_date"
                        name="warning_date"
                        type="date"
                        autocomplete="warning_date"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.warning_date"
                      />
                    <InputError
                        v-if="form.errors?.warning_date"
                        :message="form.errors?.warning_date"
                      />
                  </div>
                  <!-- <div>
                    <label
                      for="amount"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Issued By
                      <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedIssuedBy"
                        :options="employeeOptions"
                        :custom-label="employeeCustomLabel"
                        @select="onIssuedBySelect"
                        placeholder="Select a Reporting Employee"
                      ></multiselect>
                    </div>
                    <InputError
                        v-if="form.errors?.issued_by"
                        :message="form.errors?.issued_by"
                      />
                  </div> -->
                </div>
              <div
                class="flex items-center justify-center md:justify-end gap-4 py-4"
              >
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('employee-warnings.index')"
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
  users: Array,
  employeeWarning: Object,
});

const form = useForm({
  id: props?.employeeWarning?.id || null,
  employee_id: props?.employeeWarning?.employee_id || null,
    warning_type: props?.employeeWarning?.warning_type || null,
    warning_reason: props?.employeeWarning?.warning_reason || null,
    additional_notes: props?.employeeWarning?.additional_notes || null,
    warning_date: props?.employeeWarning?.warning_date || null,
    issued_by: props?.employeeWarning?.issued_by || null,
});

// employee
const selectedEmployee = ref(null);
const onEmployeeSelect = async (selectedEmployee) => {
  form.employee_id = selectedEmployee.id;
};
if(props?.employeeWarning?.employee_id){
  selectedEmployee.value = props.employees.find(employee => employee.id === props.employeeWarning.employee_id);
}

// Warning Type
const selectedWarningType = ref();
const WarningTypeOptions = computed(() => {
  const warningType = [
    { value: "verbal", label: "Verbal" },
    { value: "written", label: "Written" },
    { value: "suspension", label: "Suspension" },
    { value: "other", label: "Other" },
  ];
  return warningType;
});

const WarningTypeCustomLabel = (option) => option.label;
const onWarningTypeSelect = async (selectedWarningType) => {
  form.warning_type = selectedWarningType.value;
};

if(props?.employeeWarning?.warning_type){
  selectedWarningType.value = WarningTypeOptions.value.find(option => option.value === props.employeeWarning.warning_type);
}

// Issued by
const users = ref(props.users);

const employeeOptions = computed(() => {
  return users.value && users.value.length ? users.value : [];
});

const employeeCustomLabel = (form) => {
  return `${form.name}`;
};

const selectedIssuedBy = ref(null);

const onIssuedBySelect = (selectedIssuedBy) => {
  form.issued_by = selectedIssuedBy.id;
};

if(props?.employeeWarning?.issued_by){
  selectedIssuedBy.value = props.employees.find(employee => employee.id === props.employeeWarning.issued_by);
}


const cancel = () => {
  Inertia.visit(route("employee-warnings.index"));
};

defineComponent({
  components: {
    Multiselect,
  },
});
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
