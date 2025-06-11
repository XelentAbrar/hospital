<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, onMounted, watch } from 'vue';
import { debounce } from 'lodash';
const props = defineProps({
    user: Object,
    appointments: Array,
    admissions: Array,
    patientTests: Array,
    donorFunds: Array,
    expenses: Array,
    filters: Object,
    hasDonationModule: Boolean,
});
const searchQuery = ref(props.filters.search || '');
const projectType = import.meta.env.VITE_PROJECT_TYPE;

const performSearch = debounce(() => {
    router.get(route('user-cash-details', {
        user_id: props.user.id,
        from_date: props.filters.from_date,
        to_date: props.filters.to_date,
        search: searchQuery.value
    }), {
        preserveState: true,
        replace: true
    });
}, 500);

watch(searchQuery, () => {
    performSearch();
});
const totals = ref({
  admissionAdvance: 0,
  admissionReceived: 0,
  appointmentFees: 0,
  donorFunds: 0,
  testFees: 0,
  admissionCareOff: 0,
  admissionZF: 0,
  admissionDiscount: 0,
  admissionRefund: 0,
  appointmentCareOff: 0,
  appointmentZF: 0,
  testCareOff: 0,
  testZF: 0,
  expenses: 0
});

const calculateTotals = () => {
  const newTotals = {
    admissionAdvance: 0,
    admissionReceived: 0,
    appointmentFees: 0,
    donorFunds: 0,
    testFees: 0,
    admissionCareOff: 0,
    admissionZF: 0,
    admissionDiscount: 0,
    admissionRefund: 0,
    appointmentCareOff: 0,
    appointmentZF: 0,
    testCareOff: 0,
    testZF: 0,
    expenses: 0
  };

  props.admissions?.forEach(adm => {
    newTotals.admissionAdvance += parseFloat(adm.advance_amount || 0);
    newTotals.admissionReceived += parseFloat(adm.received_amount || 0);
    newTotals.admissionCareOff += parseFloat(adm.donor_fee || 0);
    newTotals.admissionZF += parseFloat(adm.zf_fee || 0);
    newTotals.admissionDiscount += parseFloat(adm.discount_amount || 0);
    newTotals.admissionRefund += parseFloat(adm.refund_amount || 0);
  });

  props.appointments?.forEach(appt => {
    newTotals.appointmentFees += parseFloat(appt?.appointment_details?.[0]?.fee || 0);
    newTotals.appointmentCareOff += parseFloat(appt?.donor_fee || 0);
    newTotals.appointmentZF += parseFloat(appt?.zf_fee || 0);
  });

  props.donorFunds?.forEach(fund => {
    newTotals.donorFunds += parseFloat(fund.charges || 0);
  });

  props.patientTests?.forEach(test => {
    newTotals.testFees += parseFloat(test.total_amount || 0);
    newTotals.testCareOff += parseFloat(test.donor_fee || 0);
    newTotals.testZF += parseFloat(test.zf_fee || 0);
  });

  props.expenses?.forEach(exp => {
    newTotals.expenses += parseFloat(exp.amount || 0);
  });

  totals.value = newTotals;
};

const totalIncome = computed(() => {
  return totals.value.admissionAdvance +
         totals.value.admissionReceived +
         totals.value.appointmentFees +
         totals.value.donorFunds +
         totals.value.testFees;
});

const totalDeductions = computed(() => {
  return totals.value.admissionCareOff +
         totals.value.admissionZF +
         totals.value.admissionDiscount +
         totals.value.admissionRefund +
         totals.value.appointmentCareOff +
         totals.value.appointmentZF +
         totals.value.testCareOff +
         totals.value.testZF +
         totals.value.expenses;
});

const cashInHand = computed(() => {
  return totalIncome.value - totalDeductions.value;
});
const print = () => {
  var header_str = '<html><head><title>' + document.title + '</title></head><body>';
  var footer_str = '</body></html>';
  var new_str = document.getElementById('printData').innerHTML;
  var old_str = document.body.innerHTML;
  document.body.innerHTML = header_str + new_str + footer_str;
  window.print();
  window.onafterprint = () => {
    document.body.innerHTML = old_str;
    window.location.reload();
  };
  return false;
};
onMounted(calculateTotals);
watch(() => [
  props.admissions,
  props.appointments,
  props.donorFunds,
  props.patientTests,
  props.expenses
], calculateTotals, { deep: true });

const formatNumber = (number) => {
    return new Intl.NumberFormat().format(number);
};
</script>

<template>
<Head :title="`User Cash Details - ${user.name}`" />
<AppLayout :title="`User Cash Details - ${user.name}`">
       <div class="pt-6 px-6 flex">
            <div class="relative w-full print-hide">
                <input
                    type="text"
                    v-model="searchQuery"
                    placeholder="Search by patient name, ID, doctor, service, or test..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                />
                <span class="absolute right-3 top-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
            </div>
            <div class="print-hide">
                 <button
                  class="rounded bg-blue-700 px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-blue-700 hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 ml-2" @click="print()" >
                    Print
                  </button>
                </div>
        </div>
<div id="printData">
    <div class="print-only">
              <div class="flex items-start justify-between">
                <div class="flex items-center relative space-x-4 mb-2 ml-2 pb-2 border-b-4 border-[#FFC470] w-7/12">
                    <img class="h-auto w-10" src="/build/assets/logo-20b0503a.png" alt="" />
                    <h1 class="doctor-info text-xl font-semibold">CHINIOT GENERAL HOSPITAL</h1>
                    <span class="bg-[#8B322C] border border-red-800 w-1/2 h-0.5 absolute right-0 -bottom-[3px]"></span>
                </div>
                <div class="space-y-1 ml-4 w-5/12">
                    <div class="flex items-center space-x-2">
                        <div class="border border-[#FFC470] p-[3px] mt-1 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="w-2.5 h-2.5 text-[#FFC470]">
                                <path fill-rule="evenodd"
                                    d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p v-if="projectType == 'hms'" style="font-size: 12px;">Ph: 041-8848060 | 8787231</p>
                        <p v-else style="font-size: 12px;">Ph: 041-2619764 | 2634890</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="border border-[#FFC470] p-[3px] mt-1 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="w-2.5 h-2.5 text-[#FFC470]">
                                <path fill-rule="evenodd"
                                    d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p v-if="projectType === 'hms'" style="font-size: 12px;">Nawaz Town Sargodha Road, Faisalabad</p>
                        <p v-else style="font-size: 12px;">209 Jinnah Colony , Faisalabad</p>
                    </div>
                </div>
              </div>
              <div class="border-dashed border-2 border-gray-400 rounded px-4 mt-2">
              <div
                  style="display: flex;align-items: center;justify-content: space-between;flex: initial;width: 100%;margin: 0 auto;">
                  <div style="width: 50%;">
                      <div style="display: flex;align-items: center;">
                          <h4 style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 12px;">User</h4>
                          <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                          <p style="margin: 0;margin-left: 2px;text-align: left;">All</p>
                      </div>
                  </div>
                  <div style="width: 50%;display: flex;justify-content: start;">
                      <div style="display: flex;align-items: center;">
                          <h4 style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 12px;">Date & Time</h4>
                          <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                          <p style="margin: 0; margin-left: 2px; text-align: left; white-space: nowrap; font-size: 12px;">
                            {{ new Date().getDate() + '-' + (new Date().getMonth() + 1) + '-' + new Date().getFullYear() }}
                            {{ new Date().toTimeString().slice(0, 5) }}
                         </p>
                      </div>
                  </div>
              </div>
              <div style="display: flex;align-items: center;justify-content: space-between;flex: initial;width: 100%;margin: 0 auto;">
                  <div style="width: 50%;">
                      <div style="display: flex;align-items: center;">
                          <h4 style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 12px;">From</h4>
                          <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                          <p style="margin: 0;margin-left: 2px;text-align: left;font-weight: 500;font-size: 12px;">
                            <!-- {{ new Date(from_date).toLocaleDateString('en-GB').replace(/\//g, '-') }} -->
                              {{ report?.from_date }}
                        </p>
                      </div>
                  </div>
                  <div style="width: 50%;display: flex;justify-content: start;">
                      <div style="display: flex;align-items: center;">
                          <h4 style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 12px;">To</h4>
                          <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                          <p style="margin: 0;margin-left: 2px;text-align: left;font-size: 12px;">
                            <!-- {{ new Date(to_date).toLocaleDateString('en-GB').replace(/\//g, '-') }} -->
                              {{ report?.to_date }}
                        </p>
                      </div>
                  </div>
              </div>
              </div>
        </div>
    <div class="p-6 bg-white rounded-lg shadow">
        <h1 class="font-semibold text-2xl pt-2 text-primary text-center">Cash Details for {{ user.name }}</h1>

        <div class="grid grid-cols-1 gap-4 pt-6 print:block">

    <!-- <div class="print:block hidden print:flex print:justify-between print:mb-4">
        <h2 class="text-xl font-semibold">Date Range</h2>
        <div class="flex gap-8">
            <p class="text-sm text-gray-500"><b>From:</b> {{ filters.from_date || 'Start' }}</p>
            <p class="text-sm text-gray-500"><b>To:</b> {{ filters.to_date }}</p>
        </div>
    </div> -->

    <!-- Summary - Single Line for Print -->
    <div class="print:block hidden">
    <h2 class="text-xl font-semibold mb-2">Summary</h2>
    <div class="">
        <table class="w-full border-collapse">
        <tbody>
        <tr class="hover:bg-gray-50 border">
            <td class=" p-2">Total Appointments</td>
            <td class=" p-2 text-right font-bold">{{ formatNumber(appointments.length) }}</td>
            <td class=" p-2">Total Admissions</td>
            <td class=" p-2 text-right font-bold">{{ formatNumber(admissions.length) }}</td>
            <td class=" p-2">Total Tests</td>
            <td class=" p-2 text-right font-bold">{{ formatNumber(patientTests.length) }}</td>
            <td class=" p-2">Total Donations</td>
            <td class=" p-2 text-right font-bold">{{ formatNumber(donorFunds.length) }}</td>
            <td class=" p-2">Total Expenses</td>
            <td class=" p-2 text-right font-bold">{{ formatNumber(expenses.length) }}</td>
        </tr>
            </tbody>
        </table>

            </div>
        </div>

    <!-- Original Layout (Hidden on Print) -->
    <div class="print:hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h2 class="text-xl font-semibold mb-4">Summary</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Total Appointments</p>
                            <p class="font-semibold">{{ appointments.length }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Admissions</p>
                            <p class="font-semibold">{{ admissions.length }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Tests</p>
                            <p class="font-semibold">{{ patientTests.length }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Donations</p>
                            <p class="font-semibold">{{ donorFunds.length }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Expenses</p>
                            <p class="font-semibold">{{ expenses.length }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-4">Date Range</h2>
                <div class="bg-gray-50 p-4 rounded-lg flex">
                    <p class="text-sm text-gray-500"><b>From:</b> {{ filters.from_date || 'Start' }}</p>
                    <p class="text-sm text-gray-500 ml-12"><b>To:</b> {{ filters.to_date }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
        <!-- <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-6">
            <div>
                <h2 class="text-xl font-semibold mb-4">Summary</h2>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Total Appointments</p>
                            <p class="font-semibold">{{ appointments.length }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Admissions</p>
                            <p class="font-semibold">{{ admissions.length }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Tests</p>
                            <p class="font-semibold">{{ patientTests.length }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Donations</p>
                            <p class="font-semibold">{{ donorFunds.length }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Expenses</p>
                            <p class="font-semibold">{{ expenses.length }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-semibold mb-4">Date Range</h2>
                <div class="bg-gray-50 p-4 rounded-lg flex">
                    <p class="text-sm text-gray-500"><b>From:</b> {{ filters.from_date || 'Start' }}</p>
                    <p class="text-sm text-gray-500 ml-12"><b>To:</b> {{ filters.to_date }}</p>
                </div>
            </div>
        </div> -->

        <!-- Appointments Section -->
        <div class="pt-6" v-if="appointments.length > 0">
            <h2 class="text-xl font-semibold mb-4">Appointments</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 print-optimized-table">
                    <thead class="bg-primary">
                        <tr class="border border-gray-200">
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200">Doctor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200">Service</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200" v-if="hasDonationModule">CareOFF/ZF</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="appointment in appointments" :key="'appointment-' + appointment.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ appointment.id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ new Date(appointment.appointment_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ appointment.patient_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ appointment.doctor?.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ appointment.appointment_details?.[0]?.service?.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200"  v-if="hasDonationModule"> <span v-if="appointment?.donor_fee > 0" >{{ appointment?.careoff?.name }} {{ formatNumber(appointment?.donor_fee) }}</span> <span v-if="appointment?.zf_fee > 0" >  {{ formatNumber(appointment?.zf_fee) }}</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 text-right"> {{ formatNumber(appointment?.appointment_details?.[0]?.fee || 0) }} </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Admissions Section -->
        <div class="pt-6" v-if="admissions.length > 0">
            <h2 class="text-xl font-semibold mb-4">Admissions</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 print-optimized-table">
                    <thead class="bg-primary">
                        <tr class="adjust-print">
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200 adjust-print1">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200 adjust-print1">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200 adjust-print1">Discharge</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200 adjust-print1">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200 adjust-print1">Detail</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200 adjust-print1">Advance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200 adjust-print1">CareOFF/ZF</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200 adjust-print1">SD</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200 adjust-print1">Received</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200 adjust-print1">Refund</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200 adjust-print1">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="admission in admissions" :key="'admission-' + admission.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 adjust-print1">{{ admission.id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 adjust-print1"> {{ new Date(admission.admission_date).toLocaleDateString('en-GB').replace(/\//g, '-')  }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 adjust-print1"> {{ (admission?.discharge_date != '' && admission?.discharge_date != null ?
                                                (new Date(admission?.discharge_date).getDate()) + '-' + (new
                                                    Date(admission?.discharge_date).getMonth() + 1)+'-'+(new
                                            Date(admission?.discharge_date).getFullYear()) : '' )}}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 adjust-print1">{{ admission.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 adjust-print1">
                                <table>
                                    <tr class="border border-gray-300">
                                        <th class="border border-gray-300 px-2 py-2 rounded-md">Service</th>
                                        <th class="border border-gray-300 px-2 py-2 rounded-md">Doctor</th>
                                        <th class="border border-gray-300 px-2 py-2 rounded-md">Fee</th>
                                    </tr>
                                    <tr class="border border-gray-300 px-2 py-2 rounded-md" v-for="detail in admission.details">
                                        <td class="border border-gray-300 px-2 py-2 rounded-md">  {{ detail?.service?.name }}</td>
                                        <td class="border border-gray-300 px-2 py-2 rounded-md">{{ detail?.doctor?.name }}</td>
                                        <td class="border border-gray-300 px-2 py-2 rounded-md">{{ detail?.service_amount }} <span v-if="detail.no_of_days" > {{ detail?.day_charges }}</span></td>
                                    </tr>
                                </table>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 adjust-print1 text-right">{{ formatNumber(admission.advance_amount) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 adjust-print1 text-right"><span v-if="admission?.zf_fee > 0" >zf- {{ formatNumber(admission.zf_fee) }}</span><span v-else> {{ admission?.careoff?.name }} {{ formatNumber(admission.donor_fee) }}</span></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 adjust-print1 text-right">{{ formatNumber(admission.discount_amount) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 adjust-print1 text-right">{{ formatNumber(admission.received_amount) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 adjust-print1 text-right">{{ formatNumber(admission.refund_amount) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 adjust-print1 text-right">
                                {{ formatNumber(
                                    (+admission.advance_amount || 0) +
                                    (+admission.received_amount || 0) -
                                    (+admission.refund_amount || 0)
                                ) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Patient Tests Section -->
        <div class="pt-6" v-if="patientTests.length > 0">
            <h2 class="text-xl font-semibold mb-4">Patient Tests</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 print-optimized-table">
                    <thead class="bg-primary">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200 adjust-print1">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200 adjust-print1">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200 adjust-print1">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200 adjust-print1">Test</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200 adjust-print1" v-if="hasDonationModule">CareOFF/ZF</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200 adjust-print1">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="test in patientTests" :key="'test-' + test.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 adjust-print1">{{ test.id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 adjust-print1"> {{ (test?.test_date != '' && test?.test_date != null ?
                                                (new Date(test?.test_date).getDate()) + '-' + (new
                                                    Date(test?.test_date).getMonth() + 1)+'-'+(new
                                            Date(test?.test_date).getFullYear()) : '' )}}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 adjust-print1">{{ test.patient_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 adjust-print1">
                                <template
                                            v-for="(test_general_detail, index) in test.patient_test_details"
                                            :key="index">
                                            <table>

                                                <tr v-if="test_general_detail.package != null">
                                                    <td>{{ test_general_detail.package?.name }}</td>
                                                </tr>
                                                <tr>
                                                    <template v-if="!test_general_detail.package_id">
                                                        <template v-for="(subpkg, subindex) in test_general_detail.test_general_details"
                                                            :key="subindex">
                                                            <template v-if="subpkg.sub_package != null">
                                                                <tr
                                                                    v-if="subindex === 0 || test_general_detail.test_general_details[subindex - 1].sub_package?.name !== subpkg.sub_package?.name">
                                                                    <td>{{ subpkg.sub_package?.name }}</td>
                                                                </tr>
                                                            </template>
                                                        </template>

                                                        <template
                                                            v-if="!test_general_detail.test_general_details.some(detail => detail.sub_package)">
                                                            <template v-for="(test, testIndex) in test_general_detail.test_general_details"
                                                                :key="testIndex">
                                                                <tr
                                                                    v-if="testIndex === 0 || test_general_detail.test_general_details[testIndex - 1].lab_test?.name !== test.lab_test?.name">
                                                                    <td>{{ test.lab_test?.name }}</td>
                                                                </tr>
                                                            </template>
                                                        </template>
                                                    </template>
                                                </tr>
                                            </table>
                            </template>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 text-right adjust-print1" v-if="hasDonationModule"><span v-if="test.donor_fee">
                                {{ test?.careoff?.name }}
                                Donor: {{ formatNumber(test?.donor_fee) }}
                                </span>
                                <span v-if="test.zf_fee" class="ml-2">
                                ZF: {{ formatNumber(test?.zf_fee) }}
                                </span> </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 text-right adjust-print1">{{ formatNumber(test.total_amount) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Donor Funds Section -->
        <div class="pt-6" v-if="donorFunds.length > 0">
            <h2 class="text-xl font-semibold mb-4">Donor Funds</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 print-optimized-table">
                    <thead class="bg-primary">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200">Donor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200">Desc</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="fund in donorFunds" :key="'fund-' + fund.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ fund.id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200"> {{ (fund?.donor_date != '' && fund?.donor_date != null ?
                                                (new Date(fund?.donor_date).getDate()) + '-' + (new
                                                    Date(fund?.donor_date).getMonth() + 1)+'-'+(new
                                            Date(fund?.donor_date).getFullYear()) : '' )}}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ fund.donor?.name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ fund.narration }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 text-right">{{ formatNumber(fund.charges) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Expenses Section -->
        <div class="pt-6" v-if="expenses.length > 0">
            <h2 class="text-xl font-semibold mb-4">Expenses</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 print-optimized-table">
                    <thead class="bg-primary">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider border border-gray-200">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="expense in expenses" :key="'expense-' + expense.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ expense.id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200"> {{ (expense?.date != '' && expense?.date != null ?
                                                (new Date(expense?.date).getDate()) + '-' + (new
                                                    Date(expense?.date).getMonth() + 1)+'-'+(new
                                            Date(expense?.date).getFullYear()) : '' )}}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200">{{ expense.description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 border border-gray-200 text-right">{{ formatNumber(expense.amount) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div class="overall-cash-summary print-hide">
    <h2 class="summary-title">Overall Cash in Hand</h2>

    <!-- Income Section -->
    <div class="section income-section">
      <h3>Income</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="income-item">
          <span>Admissions (Advance):</span>
          <span>Rs. {{ formatNumber(totals.admissionAdvance) }}</span>
        </div>
        <div class="income-item">
          <span>Admissions (Received):</span>
          <span>Rs. {{ formatNumber(totals.admissionReceived) }}</span>
        </div>
        <div class="income-item">
          <span>Appointments:</span>
          <span>Rs. {{ formatNumber(totals.appointmentFees) }}</span>
        </div>
        <div class="income-item">
          <span>Patient Tests:</span>
          <span>Rs. {{ formatNumber(totals.testFees) }}</span>
        </div>
        <div class="income-item">
          <span>Donor Funds:</span>
          <span>Rs. {{ formatNumber(totals.donorFunds) }}</span>
        </div>
        <div class="income-total">
          <span class="font-bold">Total Income:</span>
          <span class="font-bold">Rs. {{ formatNumber(totalIncome) }}</span>
        </div>
      </div>
    </div>

    <!-- Deductions Section -->
    <div class="section deductions-section">
      <h3>Deductions</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="deduction-item">
          <span>Admission CareOFF:</span>
          <span>Rs. {{ formatNumber(totals.admissionCareOff) }}</span>
        </div>
        <div class="deduction-item">
          <span>Admission ZF:</span>
          <span>Rs. {{ formatNumber(totals.admissionZF) }}</span>
        </div>
        <div class="deduction-item">
          <span>Admission Discounts:</span>
          <span>Rs. {{ formatNumber(totals.admissionDiscount) }}</span>
        </div>
        <div class="deduction-item">
          <span>Admission Refunds:</span>
          <span>Rs. {{ formatNumber(totals.admissionRefund) }}</span>
        </div>
        <div class="deduction-item">
          <span>Appointment CareOFF:</span>
          <span>Rs. {{ formatNumber(totals.appointmentCareOff) }}</span>
        </div>
        <div class="deduction-item">
          <span>Appointment ZF:</span>
          <span>Rs. {{ formatNumber(totals.appointmentZF) }}</span>
        </div>
        <div class="deduction-item">
          <span>Test CareOFF:</span>
          <span>Rs. {{ formatNumber(totals.testCareOff) }}</span>
        </div>
        <div class="deduction-item">
          <span>Test ZF:</span>
          <span>Rs. {{ formatNumber(totals.testZF) }}</span>
        </div>
        <div class="deduction-item">
          <span>Expenses:</span>
          <span>Rs. {{ formatNumber(totals.expenses) }}</span>
        </div>
        <div class="deduction-total">
          <span class="font-bold">Total Deductions:</span>
          <span class="font-bold">Rs. {{ formatNumber(totalDeductions) }}</span>
        </div>
      </div>
    </div>

    <!-- Cash in Hand -->
    <div class="cash-in-hand">
      <div class="cash-in-hand-value">
        <span>Total Cash in Hand:</span>
        <span class="text-2xl font-bold">Rs. {{ formatNumber(cashInHand) }}</span>
      </div>
      <div class="cash-formula">
        (Total Income: Rs. {{ formatNumber(totalIncome) }}) -
        (Total Deductions: Rs. {{ formatNumber(totalDeductions) }})
      </div>
    </div>
  </div>
  <div class="overall-cash-summary print-optimized print-only">
  <h2 class="summary-title">Overall Cash in Hand Summary</h2>

  <div class="summary-sections">
    <!-- Income Section -->
  <!-- Income Section -->
<div class="summary-section income-section mb-8">
  <div class="section-header flex justify-between items-center mb-4">
    <h3 class="text-lg font-semibold">Income</h3>
    <div class="section-total font-medium font-bold">Total: Rs. {{ formatNumber(totalIncome) }}</div>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full border-collapse">

      <tbody>
        <tr class="hover:bg-gray-50 border">
          <td class=" p-2">Admissions (Advance)</td>
          <td class=" p-2 text-right font-bold">Rs. {{ formatNumber(totals.admissionAdvance) }}</td>
          <td class=" p-2">Admissions (Received)</td>
          <td class=" p-2 text-right font-bold">Rs. {{ formatNumber(totals.admissionReceived) }}</td>
          <td class=" p-2">Appointments</td>
          <td class=" p-2 text-right font-bold">Rs. {{ formatNumber(totals.appointmentFees) }}</td>
          <td class=" p-2">Patient Tests</td>
          <td class=" p-2 text-right font-bold">Rs. {{ formatNumber(totals.testFees) }}</td>
          <td class=" p-2">Donor Funds</td>
          <td class=" p-2 text-right font-bold">Rs. {{ formatNumber(totals.donorFunds) }}</td>
        </tr>
             </tbody>
    </table>
  </div>
</div>

<!-- Deductions Section -->
<div class="summary-section deductions-section">
  <div class="section-header flex justify-between items-center mb-4">
    <h3 class="text-lg font-semibold">Deductions</h3>
    <div class="section-total font-bold">Total: Rs. {{ formatNumber(totalDeductions) }}</div>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full border-collapse">

      <tbody>
        <tr class="hover:bg-gray-50 border">
            <td class=" p-2">Appointment CareOFF</td>
            <td class=" p-2 text-right font-bold">Rs. {{ formatNumber(totals.appointmentCareOff) }}</td>
          <td class=" p-2">Admission CareOFF</td>
          <td class=" p-2 text-right font-bold">Rs. {{ formatNumber(totals.admissionCareOff) }}</td>
          <td class=" p-2">Test CareOFF</td>
          <td class=" p-2 text-right font-bold">Rs. {{ formatNumber(totals.testCareOff) }}</td>
          <td class=" p-2">Expenses</td>
          <td class=" p-2 text-right font-bold">Rs. {{ formatNumber(totals.expenses) }}</td>
        </tr>
        <tr class="hover:bg-gray-50 border">
          <td class=" p-2">Appointment ZF</td>
          <td class=" p-2 text-right font-bold">Rs. {{ formatNumber(totals.appointmentZF) }}</td>
          <td class=" p-2">Admission ZF</td>
          <td class=" p-2 text-right font-bold">Rs. {{ formatNumber(totals.admissionZF) }}</td>
          <td class=" p-2">Admission Discounts</td>
          <td class=" p-2 text-right font-bold">Rs. {{ formatNumber(totals.admissionDiscount) }}</td>
          <td class=" p-2">Admission Refunds</td>
          <td class=" p-2 text-right font-bold">Rs. {{ formatNumber(totals.admissionRefund) }}</td>
          <td class=" p-2">Test ZF</td>
          <td class=" p-2 text-right font-bold">Rs. {{ formatNumber(totals.testZF) }}</td>
        </tr>

      </tbody>
    </table>
  </div>
</div>
    <!-- Cash in Hand Section -->
    <div class="summary-section cash-in-hand-section mb-8">
  <div class="section-header flex justify-between items-center mb-4">
    <h3 class="text-lg font-semibold">Cash in Hand</h3>
    <div class="section-total font-bold">Total: Rs. {{ formatNumber(cashInHand) }}</div>
  </div>

  <div class="overflow-x-auto">
    <table class="w-full border-collapse ">

      <tbody>
        <tr class="hover:bg-gray-50 border print-optimized-table">
          <td class=" p-2">Total Income</td>
          <td class=" p-2 text-right font-bold">Rs. {{ formatNumber(totalIncome) }}</td>
          <td class=" p-2">Total Deductions</td>
          <td class=" p-2 text-right font-bold">Rs. {{ formatNumber(totalDeductions) }}</td>
          <td class=" p-2">Cash in Hand (Income - Deductions)</td>
          <td class=" p-2 text-right font-bold">Rs. {{ formatNumber(cashInHand) }}</td>
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
<style scoped>
.overall-cash-summary {
  @apply bg-white p-6 rounded-lg shadow;
}

.summary-title {
  @apply text-2xl font-bold text-primary mb-6;
}

.section {
  @apply mb-6 p-4 rounded-lg;
}

.income-section {
  @apply bg-green-50 border border-green-200;
}

.deductions-section {
  @apply bg-red-50 border border-red-200;
}

.income-item, .deduction-item {
  @apply flex justify-between py-2 border-b border-gray-100;
}

.income-total, .deduction-total {
  @apply flex justify-between py-2 font-bold border-t border-gray-300 mt-2;
  grid-column: 1 / -1;
}

.cash-in-hand {
  @apply bg-blue-50 p-4 rounded-lg border border-blue-200 mt-6;
}

.cash-in-hand-value {
  @apply flex justify-between items-center text-lg font-bold text-blue-800;
}

.cash-formula {
  @apply text-sm text-gray-600 mt-2;
}

h3 {
  @apply font-semibold mb-3 text-lg;
}
.print-hide{
    display: block;
}
td {
            text-align: left;
            padding: 10px 8px;
            font-size: 14px;
        }

        th {
            font-size: 14px;
            font-weight: 600;
            color: black;
            padding: 4px 8px;
            text-align: center;
            height: 24px;
        }
        .p_4{
          padding: 16px;
        }
        table {
            width: 100%;
            height: auto;
            border-collapse: collapse;
        }
        table th, table tfoot td{
          padding: 12px 14px;
          font-size:18px;
        }
       table td{
          padding: 10px 12px;
          font-size: 15px;
        }

        .total_row {
            border-left: 1px solid;
            border-right: 1px solid;
        }

        .total_row td {
            border: none;
            font-weight: 600;
        }

        table tfoot tr {
            border: 1px solid;
        }

        table tfoot td {
            border: none;
            font-size: 16px;
            font-weight: 600;
            text-align: center;
        }
        .print-only {
            display: none;
        }
        .text_white{
          color: #fff;
        }
          @media only screen and (min-width: 1536px) {
          table th, table tfoot td{
            padding: 14px 16px;
            font-size:20px;
          }
          table td{
          padding: 10px 12px;
          font-size: 18px;
        }
        }
        /* CSS to show the element only when printing */
        @media print {
  /* General print optimizations */


            table th{
            padding: 4px;
            font-size: 12px !important;
            line-height: 10px !important;
          }
          table tfoot td{
            padding: 4px;
            font-size: 12px !important;
            line-height: 14px !important;
          }
          table td{
            padding: 4px;
            font-size: 8px !important;
            line-height: 10px !important;
          }

          .print-hide {
              display: none;
          }

          .p_4{
            padding: 4px !important;
          }
          .text_white{
            color: #000 !important;
          }
          @page { size: A4; margin: 0; }
          body { margin: 0; }
        .a4-size {
            /* width: 210mm; */
            height: auto;
        }
        .print-optimized {
      font-size: 12pt;
    }

    .summary-section {
      border: none;
      padding: 0;
      margin-bottom: 1.5rem;
    }

    .section-header {
      border-bottom: 2px solid #000;
    }

    .summary-title {
      font-size: 1.8rem;
    }


  .print-only {
            display: block;
        }
  /* Table optimizations */
  .print-optimized-table {
    width: 100% !important;
    margin: 0 !important;
    border-collapse: collapse;
  }

  .print-optimized-table thead {
    display: table-header-group;
    break-inside: avoid;
  }

  .print-optimized-table th,
  .print-optimized-table td {
    padding: 2px 4px !important;
    font-size: 8pt !important;
    line-height: 1.1 !important;
    border: 1px solid #ddd !important;
  }

  .print-optimized-table th {
    background-color: #f1f1f1 !important;
    color: #000 !important;
    font-weight: bold;
  }

  /* Section optimizations */
  .print-section {
    page-break-inside: avoid;
    margin-bottom: 10px;
  }

  .print-section h2 {
    font-size: 12pt !important;
    margin-bottom: 5px !important;
  }

  /* Hide non-essential elements */
  .no-print {
    display: none !important;
  }

  /* Page setup */
  @page {
    size: A4;
    /* margin: 10mm; */
  }
}

</style>
