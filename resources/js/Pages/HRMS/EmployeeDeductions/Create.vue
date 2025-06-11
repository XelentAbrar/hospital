<template>
  <Head title="Create Deduction" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ form?.id ? "Update" : "Create" }} Deduction
          </h1>
          <!-- <form
              @submit.prevent="
              form?.id
                  ? form.put(
                      route('employee-deductions.update', { id: form.id })
                    )
                  : form.post(route('employee-deductions.store'), form)
              "
            > -->
            <form  @submit.prevent="
            form.processing = true;
            form?.id
                ? form.put(route('employee-deductions.update', { id: form.id }))
                : form.post(route('employee-deductions.store'), form).finally(() => {
                    form.processing = false;
                })
            ">
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
                    for="deduction_type"
                    class="block text-sm md:text-base font-medium text-gray-900"
                    >Deduction Type
                    <span class="text-red-500">*</span>
                    </label
                  >
                  <div class="mt-1">
                    <multiselect
                      v-model="selectedDeductionType"
                      :options="DeductionTypeOptions"
                      :custom-label="DeductionTypeCustomLabel"
                      @select="onDeductionTypeSelect"
                      label="type"
                      track-by="value"
                      placeholder="Select Deduction Type"
                    ></multiselect>
                  </div>
                  <InputError
                      v-if="form.errors?.deduction_type"
                      :message="form.errors?.deduction_type"
                    />
                </div>
                <div>
                  <label
                    for="deduction_nature"
                    class="block text-sm md:text-base font-medium text-gray-900"
                    >Deduction Nature
                    <span class="text-red-500">*</span>
                    </label
                  >
                  <div class="mt-1">
                    <multiselect
                      v-model="selectedDeductionNature"
                      :options="DeductionNatureOptions"
                      :custom-label="DeductionNatureCustomLabel"
                      @select="onDeductionNatureSelect"
                      label="label"
                      track-by="value"
                      placeholder="Select Deduction Nature"
                    ></multiselect>
                  </div>
                  <InputError
                      v-if="form.errors?.deduction_nature"
                      :message="form.errors?.deduction_nature"
                    />
                </div>
                <div v-if="form.deduction_nature === 'recurring'">
                  <label
                    for="recurring_type"
                    class="block text-sm md:text-base font-medium text-gray-900"
                    >Recurring Type
                    <span class="text-red-500">*</span>
                    </label
                  >
                  <div class="mt-1">
                    <multiselect
                      v-model="selectedRecurringType"
                      :options="RecurringTypeOptions"
                      :custom-label="RecurringTypeCustomLabel"
                      @select="onRecurringTypeSelect"
                      label="label"
                      track-by="value"
                      placeholder="Select Recurring Type"
                    ></multiselect>
                  </div>
                  <InputError
                      v-if="form.errors?.recurring_type"
                      :message="form.errors?.recurring_type"
                    />
                </div>
                <div>
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
                <div>
                  <label
                    for="deduction_month"
                    class="block text-sm md:text-base font-medium text-gray-900"
                    >Deduction Month
                    <span class="text-red-500">*</span>
                    </label
                  >
                    <input
                      id="deduction_month"
                      name="deduction_month"
                      type="date"
                      autocomplete="deduction_month"
                      class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                      v-model="form.deduction_month"
                    />
                  <InputError
                      v-if="form.errors?.deduction_month"
                      :message="form.errors?.deduction_month"
                    />
                </div>
              </div>
              <div
                class="flex items-center justify-center md:justify-end gap-4 py-4"
              >
                <InertiaLink
                class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('employee-deductions.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                  type="submit" :disabled="form.processing"
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
  employeeDeduction: Object,
});

const form = useForm({
  id: props?.employeeDeduction?.id || null,
  employee_id: props?.employeeDeduction?.employee_id || null,
    deduction_type: props?.employeeDeduction?.deduction_type || null,
    deduction_nature: props?.employeeDeduction?.deduction_nature || null,
    recurring_type: props?.employeeDeduction?.recurring_type || null,
    amount: props?.employeeDeduction?.amount || null,
    deduction_month: props?.employeeDeduction?.deduction_month || null,
});

// employee
const selectedEmployee = ref(null);
const onEmployeeSelect = async (selectedEmployee) => {
  form.employee_id = selectedEmployee.id;
};
if(props?.employeeDeduction?.employee_id){
  selectedEmployee.value = props.employees.find(employee => employee.id === props.employeeDeduction.employee_id);
}

// Deduction Type
const selectedDeductionType = ref();
const DeductionTypeOptions = computed(() => {
  const deductionType = [
    { value: "tax", label: "Tax" },
    { value: "social_security", label: "Social Security" },
    { value: "healthcare", label: "Healthcare" },
    { value: "savings", label: "Savings" },
    { value: "donations", label: "Donations" },
    { value: "other", label: "Other" },
  ];
  return deductionType;
});

const DeductionTypeCustomLabel = (option) => option.label;
const onDeductionTypeSelect = async (selectedDeductionType) => {
  form.deduction_type = selectedDeductionType.value;
};
if(props?.employeeDeduction?.deduction_type){
  selectedDeductionType.value = DeductionTypeOptions.value.find(option => option.value === props.employeeDeduction.deduction_type);
}

// Deduction Nature
const selectedDeductionNature = ref();
const DeductionNatureOptions = computed(() => {
  const deductionNature = [
    { value: "single", label: "Single" },
    { value: "recurring", label: "Recurring" },
  ];
  return deductionNature;
});

const DeductionNatureCustomLabel = (option) => option.label;
const onDeductionNatureSelect = async (selectedDeductionNature) => {
  form.deduction_nature = selectedDeductionNature.value;
};
if(props?.employeeDeduction?.deduction_nature){
  selectedDeductionNature.value = DeductionNatureOptions.value.find(option => option.value === props.employeeDeduction.deduction_nature);
}

// Recurring Type
const selectedRecurringType = ref();
const RecurringTypeOptions = computed(() => {
  const recurringType = [
    { value: "monthly", label: "Monthly" },
    { value: "quarterly", label: "Quarterly" },
    { value: "yearly", label: "Yearly" },
  ];
  return recurringType;
});

const RecurringTypeCustomLabel = (option) => option.label;
const onRecurringTypeSelect = async (selectedRecurringType) => {
  form.recurring_type = selectedRecurringType.value;
};
if(props?.employeeDeduction?.recurring_type){
  selectedRecurringType.value = RecurringTypeOptions.value.find(option => option.value === props.employeeDeduction.recurring_type);
}

const cancel = () => {
  Inertia.visit(route("employee-deductions.index"));
};

defineComponent({
  components: {
    Multiselect,
  },
});
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
