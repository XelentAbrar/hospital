<template>
  <Head title="Z/F Report By Date" />
  <AppLayout title="Z/F Report By Date">
    <div class="w-full mx-auto pt-6">
      <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <div
            class="flex flex-col md:flex-row items-start md:items-center justify-between"
          >
            <h1 class="font-semibold text-3xl text-primary">Z/F Report By Date</h1>
            <div class="mt-2 ml-auto sm:mt-0">
              <form
              @submit.prevent="report.get(props.hasDonationModule ? route('donation.reports-zf-detail') : null, report)"
            >
              <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-3">
                  <div>
                    <label
                      for="from_date"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >From Date
                    </label>
                    <div class="mt-2">
                      <input
                        id="from_date"
                        name="from_date"
                        type="text" ref="from_date"
                        autocomplete="from_date"
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"

                        v-model="report.from_date"
                      />
                    </div>
                  </div>

                  <div>
                    <label
                      for="to_date"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >To Date
                    </label>
                    <div class="mt-2">
                      <input
                        id="to_date"
                        name="to_date"
                        type="text" ref="to_date"
                        autocomplete="to_date"
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"

                        v-model="report.to_date"
                      />
                    </div>
                  </div>
                  <div class="mt-2">
                    <label
                      for="to_date"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >&nbsp;
                    </label>
                    <button
                  type="submit"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary" >
                    Search
                  </button>
                    <button
                  class="rounded bg-blue-700 px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-blue-700 hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 ml-2" @click="print()" v-if="reports.length > 0" >
                    Print
                  </button>
                  </div>
              </div>
            </form>

            </div>
          </div>
          <div class="mt-6 flow-root" id="printData">

            <div
            style="font-family: Arial, Helvetica, sans-serif; padding: 20px;display: flex;flex-direction: column;">
            <div class="print-only">
              <div style="display: flex;flex-direction: column; align-items: center;justify-content: center;">
                  <img style="width:100px;object-fit: contain; margin-bottom: 16px;" alt="">
                  <p style="font-size: 24px;font-weight: 600;text-align: center;margin: 0;margin-bottom: 2px;">Chiniot
                      General Hospital</p>
                  <p v-if="projectType == 'hms'" style="font-size: 18px;font-weight: 500;text-align: center;margin: 0;margin-bottom: 2px;">Nawaz Town
                      Sarghoda Road, Faisalabad</p>
                   <p v-else style="font-size: 18px;font-weight: 500;text-align: center;margin: 0;margin-bottom: 2px;">209 Jinnah Colony , Faisalabad</p>

                  <p v-if="projectType == 'hms'" style="font-size: 18px;font-weight: 500;text-align: center;margin: 0;margin-bottom: 2px;">Ph:
                      041-8848060 | 8787231</p>
                      <p v-else style="font-size: 18px;font-weight: 500;text-align: center;margin: 0;margin-bottom: 2px;">Ph:
                      041-2618764 | 2634890</p>
              </div>
              <div
                  style="display: flex;align-items: center;justify-content: space-between;flex: initial;margin-top: 20px !important;width: 100%;margin: 10px auto;">
                  <div style="width: 50%;">
                      <div style="display: flex;align-items: center;">
                          <h4 style="margin: 0;font-family: sans-serif; width: 100px;">User</h4>
                          <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                          <p style="margin: 0;margin-left: 2px;text-align: left;">All</p>
                      </div>
                  </div>
                  <div style="width: 50%;display: flex;justify-content: start;">
                      <div style="display: flex;align-items: center;">
                          <h4 style="margin: 0;font-family: sans-serif; width: 100px;">Date & Time</h4>
                          <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                          <p style="margin: 0;margin-left: 2px;text-align: left;white-space: nowrap;">{{ new Date().getDate() + '-' + (new Date().getMonth() + 1) + '-' + new Date().getFullYear() }} {{new Date().toTimeString().slice(0, 5)}}</p>
                      </div>
                  </div>
              </div>
              <div style="display: flex;align-items: center;justify-content: space-between;flex: initial;width: 100%;margin: 10px auto">
                  <div style="width: 50%;">
                      <div style="display: flex;align-items: center;">
                          <h4 style="margin: 0;font-family: sans-serif; width: 100px;">From</h4>
                          <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                          <!-- <p style="margin: 0;margin-left: 2px;text-align: left;font-weight: 500;">{{ new Date(from_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}</p> -->
                          <p style="margin: 0;margin-left: 2px;text-align: left;font-weight: 500;">{{ report.from_date }}</p>
                      </div>
                  </div>
                  <div style="width: 50%;display: flex;justify-content: start;">
                      <div style="display: flex;align-items: center;">
                          <h4 style="margin: 0;font-family: sans-serif; width: 100px;">To</h4>
                          <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                          <p style="margin: 0;margin-left: 2px;text-align: left;"> {{ report.to_date }}
                          <!-- <p style="margin: 0;margin-left: 2px;text-align: left;"> {{ new Date(to_date).toLocaleDateString('en-GB').replace(/\//g, '-') }} -->
                        </p>
                      </div>
                  </div>
              </div>
              <div>

                  <p
                      style="font-size: 24px;font-weight: 600;text-align: center;margin: 0;margin-bottom: 20px;margin-top: 20px;">Z/F Report By Date</p>
              </div>
            </div>
            <div class="ring-1 ring-gray-200 sm:rounded-lg overflow-x-auto overflow-y-hidden">
                  <table class="min-w-full border-separate border-spacing-0 divide-y divide-gray-200 overflow-auto">
                    <thead>
                      <tr class="divide-x divide-gray-200">
                        <th
                        scope="col"
                        class="bg-primary whitespace-nowrap py-3.5 px-4 text-left text-base md:text-lg font-medium text-white"
                      >
                        Dept.
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap py-3.5 px-4 text-left text-base md:text-lg font-medium text-white"
                      >
                        Dated.
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap py-3.5 px-4 text-left text-base md:text-lg font-medium text-white"
                      >
                        Doctor
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap py-3.5 px-4 text-left text-base md:text-lg font-medium text-white"
                      >
                        Service
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap py-3.5 px-4 text-left text-base md:text-lg font-medium text-white"
                      >
                        Total Amount
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap py-3.5 px-4 text-left text-base md:text-lg font-medium text-white"
                      >
                        Z/F
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-for="(report, index) in reports">
                    <tr
                      class="divide-x divide-gray-200"
                      v-for="(appointment_details, subIndex) in report.appointment_details"
                      :key="subIndex"
                      :class="index % 2 === 0 ? 'bg-gray-50' : 'bg-white'"
                    >
                      <td
                        class="whitespace-nowrap relative p-4 text-center text-gray-900 text-sm md:text-base"
                      >
                        {{ report.type }}
                      </td>
                      <td
                        class="whitespace-nowrap relative p-4 text-center text-gray-900 text-sm md:text-base"
                      >
                        <!-- {{ report.appointment_date }} -->
                        {{ new Date(report.appointment_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}

                      </td>
                      <td
                        class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base"
                      >
                        {{ report?.doctor?.name }}
                      </td>
                      <td
                        class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base"
                      >
                        {{ appointment_details?.service?.name }}
                      </td>
                      <td
                        class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base"
                      >
                        {{ formatNumber(appointment_details?.fee) }}
                      </td>
                      <td
                        class="whitespace-nowrap relative p-4 text-gray-900 text-sm md:text-base"
                      >
                        <!-- {{ report?.donor_fee }} ({{ report?.careoff_id != '' && report?.careoff_id != null ? report?.careoff?.name : report?.zf?.name }}) -->
                        {{ formatNumber(report?.zf_fee)}}
                      </td>
                    </tr>
                    </template>
                  </tbody>

                  <tfoot>
                    <tr
                      class="divide-x divide-gray-200 bg-secondary text-white"
                    >
                      <td
                        class="whitespace-nowrap relative p-4 text-center text-sm md:text-base"
                        colspan="4"
                      >
                        Total
                      </td>
                      <td
                        class="whitespace-nowrap relative p-4 text-sm md:text-base"
                      >
                        {{ formatNumber(sum(reports, 'fee', 'appointment_details')) }}
                      </td>
                      <td
                        class="whitespace-nowrap relative p-4 text-sm md:text-base"
                      >
                        {{ formatNumber(sum(reports, 'zf_fee', null, 'zf_id')) }}
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>


            </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

    <script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import { ref, watchEffect, computed, onMounted } from "vue";
import { Inertia } from "@inertiajs/inertia";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
const projectType = import.meta.env.VITE_PROJECT_TYPE;
const from_date = ref(null);
const to_date = ref(null);
const props = defineProps({
  hasDonationModule:Boolean,
  reports: Object,
  from_date: String,
  to_date: String,
});

const report = useForm({
  from_date: props?.from_date || null,
  to_date: props?.to_date || null,
});
onMounted(() => {

const flatpickrOptions = (defaultDate) => ({
    dateFormat: "d-m-Y",
    allowInput: true, // Allows manual typing
    clickOpens: true, // Ensures the picker still works
    defaultDate: defaultDate || new Date(), // Use provided default date or current date
});

flatpickr(to_date.value, flatpickrOptions(report.to_date));
flatpickr(from_date.value, flatpickrOptions(report.from_date));

});
const formatNumber = (number) => {
    return new Intl.NumberFormat().format(number);
};
const print = () => {
  var header_str = '<html><head><title>' + document.title  + '</title></head><body>';
  var footer_str = '</body></html>';
  var new_str = document.getElementById('printData').innerHTML;
  var old_str = document.body.innerHTML;
  document.body.innerHTML = header_str + new_str + footer_str;
  window.print();

  printWindow.close();
  window.location.reload();
}

const sum = (reports, val, type = null, check = null) => {
  let total = 0;
  reports.map(res => {
    if(type && type == 'appointment_details'){
      res.appointment_details.map(res1 => {
        total = +total + +res1[val];
      });
    }
    else{
      total = +total + +res[val]
    }
  });
  return total;
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
<style>
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

        table {
            width: 100%;
            height: auto;
            border-collapse: collapse;
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
            font-size: 14px;
            font-weight: 600;
            text-align: left;
        }
        .print-only {
            display: none;
        }

        /* CSS to show the element only when printing */
        @media print {
            .print-only {
                display: block;
            }
        }
    </style>
