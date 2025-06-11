<template>
<Head title="Update Advance" />
  <AppLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Update Advance</h2>
    </template>
    <div class="py-12">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form @submit.prevent="submitForm" class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
              <div class="px-4 py-6 sm:p-8">
                <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                  <div class="sm:col-span-2">
                    <label for="employee" class="block text-sm font-medium leading-6 text-gray-900">Employee</label>
                    <div class="mt-2">
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
                  <div class="sm:col-span-1">
                    <label for="advance_amount" class="block text-sm font-medium leading-6 text-gray-900">Advance Amount</label>
                    <div class="mt-2">
                      <input id="advance_amount" name="advance_amount" type="text" autocomplete="advance_amount" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6" v-model="form.advance_amount">
                    </div>
                  </div>
                  <div class="sm:col-span-1">
                    <label for="employee" class="block text-sm font-medium leading-6 text-gray-900">Deduction Type</label>
                    <div class="mt-2">
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
                  <div class="sm:col-span-1" v-if="form.deduction_type === 'fixed_amount'">
                    <label for="deduction_amount" class="block text-sm font-medium leading-6 text-gray-900">Deduction Amount</label>
                    <div class="mt-2">
                      <input id="deduction_amount" name="deduction_amount" type="number" autocomplete="deduction_amount" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6" step="0.01" v-model="form.deduction_amount" @input="updateTableData">
                    </div>
                  </div>
                  <div class="sm:col-span-1S" v-if="form.deduction_type === 'by_month'">
                    <label for="deduction_months" class="block text-sm font-medium leading-6 text-gray-900">Deduction Months</label>
                    <div class="mt-2">
                      <input id="deduction_months" name="deduction_months" type="number" autocomplete="deduction_months" class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6" v-model="form.deduction_months" @input="updateTableData">
                    </div>
                  </div>
                  <div class="sm:col-span-1">
                    <label for="start_deduction_from" class="block text-sm font-medium leading-6 text-gray-900">Start Deduction From</label>
                    <div class="mt-2">
                      <flat-pickr
                          v-model="form.start_deduction_from"
                          :config="datePickerConfig"
                          class="form-control"
                          placeholder="Select start deduction date"
                          name="start_deduction_from"
                        />
                    </div>
                  </div>
                </div>
                <div class="mt-8">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Month</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Stop Reason</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(schedule, index) in form.deduction_schedule" :key="index">
                        <td>
                          <input type="text" class="form-control" v-model="schedule.month" readonly />
                        </td>
                        <td>
                          <input type="number" class="form-control" v-model="schedule.amount" />
                        </td>
                        <td>
                          <select class="form-control" v-model="schedule.status" @change="schedule.previousStatus === 'paid' ? confirmStatusChange(schedule) : null">
                            <option value="paid">Paid</option>
                            <option value="pending">Pending</option>
                            <option value="stop">Stop</option>
                          </select>
                        </td>
                        <td>
                          <input type="text" class="form-control" v-model="schedule.stop_reason" />
                        </td>
                        <td>
                          <button @click.prevent="removeSchedule(index)" class="px-1 py-0.5 bg-red-50 text-red-800 rounded-md">Remove</button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <!-- <button @click="addNewSchedule" class="btn btn-primary">Add New Schedule</button> -->
                  <button @click.prevent="updateSchedule" class="px-3 py-1 bg-green-50 text-green-800 rounded-md">Update Schedule</button>
              </div>
              </div>
              <div class="flex items-center border-t border-gray-900/10 px-4 py-4 sm:px-8" :class="{ 'justify-between': form.isDirty, 'justify-end': !form.isDirty }">
                <div class="text-warning italic text-xs" v-if="form.isDirty">There are unsaved form changes.</div>
                <div class="flex gap-x-6">
                <button type="button" class="text-sm font-semibold leading-6 text-gray-900" @click="cancel">Cancel</button>
                <button type="submit" class="rounded-md bg-primary px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary" :disabled="form.processing">Save</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm  } from '@inertiajs/vue3';
import Multiselect from 'vue-multiselect'
import { ref, computed, defineComponent,defineExpose, watch,reactive   } from 'vue';
import flatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import { debounce } from 'lodash-es';
import { Inertia } from '@inertiajs/inertia';
import dayjs from 'dayjs';

const props = defineProps({
  employees: Array,
  advance: Object,
});


const form = useForm({
  employee_id: props.advance.employee_id,
  deduction_type: props.advance.deduction_type,
  advance_amount: props.advance.advance_amount,
  start_deduction_from: props.advance.start_deduction_from,
  deduction_amount: props.advance.deduction_amount,
  deduction_months: props.advance.deduction_months,
  deduction_schedule: props.advance.deduction_schedules.map((schedule) => ({
    id: schedule.id,
    month: new Date(schedule.month_year).toLocaleDateString('default', { month: 'long', year: 'numeric' }),
    amount: schedule.amount,
    status: schedule.status,
    stop_reason: schedule.stop_reason,
  })),
});

// Reactive variables for user inputs
const deductionType = ref('');
const deductionAmount = ref('');
const deductionMonths = ref('');

const addNewSchedule = () => {
  deductionSchedule.value.push({
    month: '',
    amount: '',
    status: 'pending',
    stop_reason: '',
  });
};

// const removeSchedule = (index) => {
//   form.deduction_schedule[index].deleted = true;
// };

const deletedSchedules = ref([]);

const removeSchedule = (index) => {
  const removedSchedule = form.deduction_schedule.splice(index, 1)[0];
  console.log('removed', removedSchedule);
  if (removedSchedule.id) {
    console.log('removed id', removedSchedule);
    deletedSchedules.value.push(removedSchedule);
    console.log('pushed to deleted');
  }
};



const onStartDateChanged = () => {
  form.start_deduction_from = form.start_deduction_from;
};

// Watch for changes in form.deduction_type, form.advance_amount, and form.start_deduction_from
const updateTrigger = ref(0);
const tableData = ref([]);

const generateDeductionSchedule = (deductionType, advanceAmount, startDeductionFrom, deductionAmount, deductionMonths, existingSchedule) => {
  const schedule = [];
  if (!deductionType || !advanceAmount || !startDeductionFrom) {
    return schedule;
  }

  const startDate = new Date(startDeductionFrom);
  const formattedStartDate = new Date(Date.UTC(startDate.getUTCFullYear(), startDate.getUTCMonth(), 1)).toISOString().split('T')[0];
  const totalAdvance = parseFloat(advanceAmount);
  let remainingAmount = totalAdvance;

  if (deductionType === 'fixed_amount') {
    deductionAmount = parseFloat(deductionAmount);
    deductionMonths = Math.ceil(totalAdvance / deductionAmount);
  } else if (deductionType === 'by_month') {
    deductionMonths = parseInt(deductionMonths);
    deductionAmount = totalAdvance / deductionMonths;
  }

  for (let i = 0; i < deductionMonths; i++) {
    const year = startDate.getFullYear();
    const monthNum = (startDate.getMonth() + i + 1).toString().padStart(2, '0');
    const month = `${year}-${monthNum}-01`;

    // If it's the last deduction, set the amount to the remaining balance
    if (i === deductionMonths - 1) {
      deductionAmount = remainingAmount;
    }

    const amount = parseFloat(deductionAmount.toFixed(2));
    const existingEntry = existingSchedule && existingSchedule[i];
    schedule.push({
      month: new Date(year, monthNum - 1, 1).toLocaleDateString('default', { month: 'long', year: 'numeric' }),
      amount: amount,
      status: existingEntry ? existingEntry.status : 'pending',
      stop_reason: existingEntry ? existingEntry.stop_reason : '',
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
const selectedDeductionType = ref({ type: 'fixed_amount', label: 'Fixed Amount' });
const DeductionTypeOptions = computed(() => {
  const deductionType = [
    { value: 'fixed_amount', label: 'Fixed Amount' },
    { value: 'by_month', label: 'By Month' },
  ];
  return deductionType;
});

const DeductionTypeCustomLabel = (option) => option.label;
const onDeductionTypeSelect = async (selectedDeductionType) => {
  form.deduction_type = selectedDeductionType.value;
};

const formatDate = (date) => {
  const monthNames = ["January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
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
    if (schedule.status === 'paid') {
      paidAmount += parseFloat(schedule.amount);
    } else if (schedule.status === 'pending') {
      pendingAmount += parseFloat(schedule.amount);
    }
  });

  const totalAmount = paidAmount + pendingAmount;
  const remainingBalance = form.advance_amount - totalAmount;

  // If the remaining balance is greater than zero, generate a new schedule
  if (remainingBalance > 0) {
    const lastSchedule = form.deduction_schedule[form.deduction_schedule.length - 1];
    let startMonth = new Date(lastSchedule.month);
    startMonth.setMonth(startMonth.getMonth() + 1);

    // Find the next available month
    while (form.deduction_schedule.some(schedule => schedule.month === formatDate(startMonth))) {
      startMonth.setMonth(startMonth.getMonth() + 1);
    }

    const newSchedule = generateDeductionSchedule(
      'fixed_amount',
      remainingBalance,
      startMonth,
      form.deduction_amount,
      null,
      null,
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





// const updateSchedule = () => {
//   const stoppedMonths = [];
//   const newDeductionSchedule = [];

//   // Get stopped months and keep other months as they are
//   form.deduction_schedule.forEach((schedule) => {
//     if (schedule.status === 'stop') {
//       stoppedMonths.push(schedule);
//     }
//     newDeductionSchedule.push(schedule);
//   });

//   // Generate new deduction schedule for stopped months
//   stoppedMonths.forEach((stoppedMonth) => {
//     if (stoppedMonth.status === 'paid') return;
//     let startMonth = new Date(stoppedMonth.month);
//     startMonth.setMonth(startMonth.getMonth() + 1);

//     // Find the next available month
//     while (newDeductionSchedule.some(schedule => schedule.month === formatDate(startMonth))) {
//       startMonth.setMonth(startMonth.getMonth() + 1);
//     }

//     const newSchedule = generateDeductionSchedule(
//       'fixed_amount',
//       stoppedMonth.amount,
//       startMonth,
//       form.deduction_amount,
//       null,
//       null,
//     );

//     // Insert the new schedule entry in the correct position
//     const insertIndex = newDeductionSchedule.findIndex(
//       (schedule) => new Date(schedule.month) > new Date(newSchedule[0].month)
//     );

//     if (insertIndex === -1) {
//       newDeductionSchedule.push(...newSchedule);
//     } else {
//       newDeductionSchedule.splice(insertIndex, 0, ...newSchedule);
//     }
//   });

//   // Replace the old deduction schedule with the new one
//   form.deduction_schedule = newDeductionSchedule;
// };


const submitForm = () => {
  // Update the schedule before submitting the form
  updateSchedule();

  let paidAmount = 0;
  let pendingAmount = 0;

  // Calculate paid and pending amounts
  form.deduction_schedule.forEach((schedule) => {
    if (schedule.status === 'paid') {
      paidAmount += parseFloat(schedule.amount);
    } else if (schedule.status === 'pending') {
      pendingAmount += parseFloat(schedule.amount);
    }
  });

  const totalAmount = paidAmount + pendingAmount;

  // Check if the total amount is equal to the advance amount
  if (totalAmount !== parseFloat(form.advance_amount)) {
    alert('The total amount (Paid + Pending) should be equal to the Advance Amount.');
    return;
  }

  const selectedMonth = new Date(form.start_deduction_from);
  const startDate = new Date(Date.UTC(selectedMonth.getUTCFullYear(), selectedMonth.getUTCMonth(), 1)).toISOString().slice(0, 10);

  // Clone the form data to avoid modifying the reactive form property
  const formData = { ...form, deletedSchedules: deletedSchedules.value };
  formData.start_deduction_from = startDate;

   formData.deleted_deduction_schedule = deletedSchedules.value;

  console.log('Final form data:', formData);
  // Submit the cloned form data
  // Inertia.put(route('employee-advances.update', props.advance.id), formData);
  Inertia.put(route('employee-advances.update', props.advance.id), formData, {
    preserveState: true,
  });
};



// const submitForm = () => {
//   const selectedMonth = new Date(form.start_deduction_from);
//   const startDate = new Date(Date.UTC(selectedMonth.getUTCFullYear(), selectedMonth.getUTCMonth(), 1)).toISOString().slice(0, 10);

//   // Clone the form data to avoid modifying the reactive form property
//   const formData = { ...form };
//   formData.start_deduction_from = startDate;

//   console.log('Final form data:', formData);
//   // Submit the cloned form data
//   // Inertia.put(route('employee-advances.update', props.advance.id), formData);
//   Inertia.put(route('employee-advances.update', props.advance.id), formData, {
//     preserveState: true,
//   });
// };

form.deduction_schedule.forEach((schedule) => {
  schedule.previousStatus = schedule.status;
});


const confirmStatusChange = (schedule) => {
  if (schedule.previousStatus === 'paid') {
    const confirmed = confirm('Are you sure you want to change the status of this paid entry?');

    if (!confirmed) {
      schedule.status = 'paid';
    } else {
      schedule.previousStatus = schedule.status;
    }
  } else {
    schedule.previousStatus = schedule.status;
  }
};


const cancel = () => {
  Inertia.visit(route('employee-advances.index'));
};

const components = {
  Multiselect,
  flatPickr,
};

// defineExpose(components);

</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>