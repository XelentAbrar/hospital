<template>
  <Head title="Create Advance" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">Create Advance</h1>
          <form
              @submit.prevent="submitForm"
          >
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 my-6">
                  <div>
                    <label
                      for="employee"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Employee</label
                    >
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedEmployee"
                        :options="employees"
                        :searchable="true"
                        :close-on-select="true"
                        :show-labels="false"
                        placeholder="Select employee"
                        label="name"
                        track-by="id"
                      />
                    </div>
                  </div>
                  <div>
                    <label
                      for="advance_amount"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Advance Amount</label
                    >
                      <input
                        id="advance_amount"
                        name="advance_amount"
                        type="text"
                        autocomplete="advance_amount"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.advance_amount"
                        @input="updateTableData"
                      />
                  </div>

                  <div>
                    <label
                      for="deduction_type"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Deduction Type</label
                    >
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedDeductionType"
                        :options="DeductionTypeOptions"
                        :custom-label="DeductionTypeCustomLabel"
                        @select="onDeductionTypeSelect"
                        label="label"
                        track-by="value"
                        placeholder="Select Deduction Type"
                      ></multiselect>
                    </div>
                  </div>
                  <div

                    v-if="form.deduction_type === 'fixed_amount'"
                  >
                    <label
                      for="deduction_amount"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Deduction Amount</label
                    >
                      <input
                        id="deduction_amount"
                        name="deduction_amount"
                        type="number"
                        autocomplete="deduction_amount"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="form.deduction_amount"
                        @input="updateTableData"
                      />
                  </div>
                  <div

                    v-if="form.deduction_type === 'by_month'"
                  >
                    <label
                      for="deduction_months"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Deduction Months</label
                    >
                      <input
                        id="deduction_months"
                        name="deduction_months"
                        type="number"
                        autocomplete="deduction_months"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.deduction_months"
                        @input="updateTableData"
                      />
                  </div>
                  <div>
                    <label
                      for="start_deduction_from"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Start Deduction From</label
                    >
                      <flat-pickr
                        v-model="form.start_deduction_from"
                        :config="datePickerConfig"
                        class="form-control mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        placeholder="Select start deduction date"
                        name="start_deduction_from"
                      />
                  </div>
            </div>

            <div class="border shadow-md rounded-lg border-gray-200 p-6 mb-6 bg-green-50">
                  <h3 class="text-2xl font-semibold text-gray-900 pb-3">
                    Update Schedule
                  </h3>
                <table class="table table-fixed w-full border rounded mb-4">
                  <thead>
                    <tr class="divide-x divide-gray-300 border-b">
                      <th class="font-semibold p-2">Month</th>
                      <th class="font-semibold p-2">Amount</th>
                      <th class="font-semibold p-2">Status</th>
                      <th class="font-semibold p-2">Stop Reason</th>
                      <th class="font-semibold p-2">Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr
                      class="divide-x divide-gray-300"
                      v-for="(schedule, index) in form.deduction_schedule"
                      :key="index"
                    >
                      <td class="p-2 border-b">
                        <input
                          type="text"
                          class="form-control w-full rounded border-gray-300"
                          v-model="schedule.month"
                          readonly
                        />
                      </td>
                      <td class="p-2 border-b">
                        <input
                          type="number"
                          class="form-control w-full rounded border-gray-300"
                          v-model="schedule.amount" step="0.01"
                        />
                      </td>
                      <td class="p-2 border-b">
                        <select class="form-control w-full rounded border-gray-300" v-model="schedule.status">
                          <option value="paid">Paid</option>
                          <option value="pending">Pending</option>
                          <option value="stop">Stop</option>
                        </select>
                      </td>
                      <td class="p-2 border-b">
                        <input
                          type="text"
                          class="form-control w-full rounded border-gray-300"
                          v-model="schedule.stop_reason"
                        />
                      </td>
                      <td class="p-2 border-b">
                        <div class="flex w-full justify-center">
                        <button
                          @click.prevent="removeSchedule(index)"
                          class="mx-auto px-3 py-1.5 bg-red-100 text-red-800 rounded"
                        >
                          Remove
                        </button>
                      </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <!-- <button @click="addNewSchedule" class="btn btn-primary">Add New Schedule</button> -->
                <button
                  @click.prevent="updateSchedule"
                  class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-500 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                  Update Schedule
                </button>
              </div>
              <div
                class="flex items-center justify-end gap-4 py-4"
              >
                <InertiaLink
                  class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('employee-advances.index')"
                  >Cancel</InertiaLink
                >
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
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import Multiselect from "vue-multiselect";
import {
  ref,
  computed,
  defineComponent,
  defineExpose,
  watch,
  reactive,
} from "vue";
import flatPickr from "vue-flatpickr-component";
import { debounce } from "lodash-es";
import { Inertia } from "@inertiajs/inertia";
const props = defineProps({
  employees: Array,
});

const form = useForm({
  employee_id: "",
  deduction_type: "fixed_amount",
  advance_amount: "",
  start_deduction_from: "",
  deduction_amount: "",
  deduction_months: "",
  deduction_schedule: "",
});

// Reactive variables for user inputs
const deductionType = ref("");
const deductionAmount = ref("");
const deductionMonths = ref("");

const addNewSchedule = () => {
  deductionSchedule.value.push({
    month: "",
    amount: "",
    status: "pending",
    stop_reason: "",
  });
};

const deletedSchedules = ref([]);

const removeSchedule = (index) => {
  const removedSchedule = form.deduction_schedule.splice(index, 1)[0];
  console.log("removed", removedSchedule);
  if (removedSchedule.id) {
    console.log("removed id", removedSchedule);
    deletedSchedules.value.push(removedSchedule);
    console.log("pushed to deleted");
  }
};

const onStartDateChanged = () => {
  form.start_deduction_from = form.start_deduction_from;
};

// Watch for changes in form.deduction_type, form.advance_amount, and form.start_deduction_from
const updateTrigger = ref(0);
const tableData = ref([]);

const generateDeductionSchedule = (
  deductionType,
  advanceAmount,
  startDeductionFrom,
  deductionAmount,
  deductionMonths,
  existingSchedule
) => {
  const schedule = [];
  if (!deductionType || !advanceAmount || !startDeductionFrom) {
    return schedule;
  }

  const startDate = new Date(startDeductionFrom);
  const formattedStartDate = new Date(
    Date.UTC(startDate.getUTCFullYear(), startDate.getUTCMonth(), 1)
  )
    .toISOString()
    .split("T")[0];
  const totalAdvance = parseFloat(advanceAmount);
  let remainingAmount = totalAdvance;

  if (deductionType === "fixed_amount") {
    deductionAmount = parseFloat(deductionAmount);
    deductionMonths = Math.ceil(totalAdvance / deductionAmount);
  } else if (deductionType === "by_month") {
    deductionMonths = parseInt(deductionMonths);
    deductionAmount = totalAdvance / deductionMonths;
  }

  for (let i = 0; i < deductionMonths; i++) {
    const year = startDate.getFullYear();
    const monthNum = (startDate.getMonth() + i + 1).toString().padStart(2, "0");
    const month = `${year}-${monthNum}-01`;

    // If it's the last deduction, set the amount to the remaining balance
    if (i === deductionMonths - 1) {
      deductionAmount = remainingAmount;
    }

    const amount = parseFloat(deductionAmount.toFixed(2));
    const existingEntry = existingSchedule && existingSchedule[i];
    schedule.push({
      month: new Date(year, monthNum - 1, 1).toLocaleDateString("default", {
        month: "long",
        year: "numeric",
      }),
      amount: amount,
      status: existingEntry ? existingEntry.status : "pending",
      stop_reason: existingEntry ? existingEntry.stop_reason : "",
    });

    remainingAmount -= amount;
  }

  return schedule;
};

watch(
  () => [
    form.deduction_type,
    form.advance_amount,
    form.start_deduction_from,
    form.deduction_amount,
    form.deduction_months,
  ],
  debounce(() => {
    form.deduction_schedule = generateDeductionSchedule(
      form.deduction_type,
      form.advance_amount,
      form.start_deduction_from,
      form.deduction_amount,
      form.deduction_months,
      form.deduction_schedule
    );
  }, 300)
);

const datePickerConfig = {
  altInput: true,
  altFormat: "F Y",
  dateFormat: "Y-m",
  minDate: "today",
  maxDate: new Date(new Date().getFullYear(), new Date().getMonth() + 24, 1),
};

// employee
const selectedEmployee = computed({
  get: () => {
    return props.employees.find((employee) => employee.id === form.employee_id);
  },
  set: (selectedEmployee) => {
    form.employee_id = selectedEmployee ? selectedEmployee.id : null;
  },
});

// Deduction Type
const selectedDeductionType = ref({
  type: "fixed_amount",
  label: "Fixed Amount",
});
const DeductionTypeOptions = computed(() => {
  const deductionType = [
    { value: "fixed_amount", label: "Fixed Amount" },
    { value: "by_month", label: "By Month" },
  ];
  return deductionType;
});

const DeductionTypeCustomLabel = (option) => option.label;
const onDeductionTypeSelect = async (selectedDeductionType) => {
  form.deduction_type = selectedDeductionType.value;
};

const formatDate = (date) => {
  const monthNames = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
  ];

  const month = monthNames[date.getMonth()];
  const year = date.getFullYear();

  return `${month} ${year}`;
};

const updateSchedule = () => {
  let paidAmount = 0;
  let pendingAmount = 0;

  // Calculate paid and pending amounts
  form.deduction_schedule.forEach((schedule) => {
    if (schedule.status === "paid") {
      paidAmount += parseFloat(schedule.amount);
    } else if (schedule.status === "pending") {
      pendingAmount += parseFloat(schedule.amount);
    }
  });

  const totalAmount = paidAmount + pendingAmount;
  const remainingBalance = form.advance_amount - totalAmount;

  // If the remaining balance is greater than zero, generate a new schedule
  if (remainingBalance > 0) {
    const lastSchedule =
      form.deduction_schedule[form.deduction_schedule.length - 1];
    let startMonth = new Date(lastSchedule.month);
    startMonth.setMonth(startMonth.getMonth() + 1);

    // Find the next available month
    while (
      form.deduction_schedule.some(
        (schedule) => schedule.month === formatDate(startMonth)
      )
    ) {
      startMonth.setMonth(startMonth.getMonth() + 1);
    }

    const newSchedule = generateDeductionSchedule(
      form.deduction_type,
      remainingBalance,
      startMonth,
      form.deduction_amount,
      form.deduction_months,
      null
    );

    // Insert the new schedule entry in the correct position
    const insertIndex = form.deduction_schedule.findIndex(
      (schedule) => new Date(schedule.month) > new Date(newSchedule[0].month)
    );

    if (insertIndex === -1) {
      form.deduction_schedule.push(...newSchedule);
    } else {
      form.deduction_schedule.splice(insertIndex, 0, ...newSchedule);
    }
  }
};

const submitting = ref(false);

const submitForm = () => {
  if (submitting.value) return;

  submitting.value = true;
  
  generateDeductionSchedule();

  let paidAmount = 0;
  let pendingAmount = 0;

  form.deduction_schedule.forEach((schedule) => {
    if (schedule.status === "paid") {
      paidAmount += parseFloat(schedule.amount);
    } else if (schedule.status === "pending") {
      pendingAmount += parseFloat(schedule.amount);
    }
  });

  const totalAmount = paidAmount + pendingAmount;

  if (totalAmount !== parseFloat(form.advance_amount)) {
    alert(
      "The total amount (Paid + Pending) should be equal to the Advance Amount."
    );
    return;
  }

  const selectedMonth = new Date(form.start_deduction_from);
  const startDate = new Date(
    Date.UTC(selectedMonth.getUTCFullYear(), selectedMonth.getUTCMonth(), 1)
  )
    .toISOString()
    .slice(0, 10);

  // Clone the form data to avoid modifying the reactive form property
  const formData = { ...form };
  formData.start_deduction_from = startDate;

  console.log("Final form data:", formData);
  // Submit the cloned form data
  formData.post("/employee-advances", {
    onFinish: () => {
      submitting.value = false;
    },
  });
  // Inertia.post(route('employee-advances.store'), formData);
};

const cancel = () => {
  Inertia.visit(route("employee-advances.index"));
};

const components = {
  Multiselect,
  flatPickr,
};

// defineExpose(components);import '';
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style src="flatpickr/dist/flatpickr.css"></style>
