<template>
  <Head title="Patient Report" />
  <AppLayout title="Patient Report">
    <div class="py-4 px-4 mx-auto max-w-screen-xl lg:py-8">
        <div class="bg-blue-50 border border-gray-300 rounded-lg p-8 md:p-12 mb-8 shadow-md">
            <h1 class="text-gray-900 text-3xl md:text-4xl font-extrabold mb-2">Patient Report</h1>
            <form @submit.prevent="form.get(route('patient-reports.index'))" enctype="multipart/form-data">
                <div class="px-4 py-6 sm:p-8">
                    <label for="date" class="block text-lg font-semibold leading-6 text-gray-900">Select Date</label>
                    <flat-pickr class="block cursor-pointer w-full rounded-md border-0 mt-2 py-2.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6" v-model="form.date" :config="dateConfig"></flat-pickr>
                </div>
                <div class="flex gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                    <button type="submit" class="text-white bg-primary hover:bg-accent focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center me-2" :disabled="form.processing">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-3.5 h-3.5 me-2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
        <div class="inline-block min-w-full py-2 align-middle sm:px-6">
            <div class="ring-1 ring-gray-300">
                <table class="min-w-full divide-y divide-gray-300">
                <thead>
                    <tr class="divide-x divide-gray-300">
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10"
                    >
                      ID
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10"
                    >
                      Name
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10"
                    >
                      CNIC
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10"
                    >
                      DOB
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10"
                    >
                      Gender
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10"
                    >
                      Address
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10"
                    >
                      Phone
                    </th>
                    <th
                      scope="col"
                      class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10"
                    >
                      Created at
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-300 bg-white">
                    <tr class="divide-x divide-gray-300"
                    v-for="(patient, index) in patients"
                    :key="patient.id"
                    :class="index % 2 === 0 ? 'bg-white' : 'bg-blue-50'"
                  >
                    <td
                     class="whitespace-nowrap relative border-b border-gray-300 text-gray-900"
                    >
                      {{ patient.id }}
                    </td>
                    <td
                     class="whitespace-nowrap relative border-b border-gray-300 text-gray-900"
                    >
                      {{ patient.name }}
                    </td>
                    <td
                     class="whitespace-nowrap relative border-b border-gray-300 text-gray-900"
                    >
                      {{ patient.cnic }}
                    </td>
                    <td
                     class="whitespace-nowrap relative border-b border-gray-300 text-gray-900"
                    >
                      {{ patient.dob }}
                    </td>
                    <td
                     class="whitespace-nowrap relative border-b border-gray-300 text-gray-900"
                    >
                      {{ patient.gender }}
                    </td>
                    <td
                     class=" relative border-b border-gray-300 text-gray-900"
                    >
                      {{ patient.address }} {{ patient?.city?.name || "" }}
                      {{ patient?.state?.name || "" }}
                      {{ patient?.country?.name || "" }}
                    </td>
                    <td
                     class="whitespace-nowrap relative border-b border-gray-300 text-gray-900"
                    >
                      {{ patient.phone }}
                    </td>
                    <td
                     class="whitespace-nowrap relative border-b border-gray-300 text-gray-900"
                    >
                    {{ new Date(patient?.created_at).toLocaleDateString('en-GB').replace(/\//g, '-') }}
                </td>
                  </tr>
                </tbody>
              </table>
            </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link as InertiaLink, useForm } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import "flatpickr/dist/themes/airbnb.css";

const form = useForm({
  date: new URLSearchParams(window.location.search).get("date") || null,
});

let dateConfig = {
  altFormat: "F j, Y",
  dateFormat: "Y-m-d",
};
</script>

<script>
export default {
  props: {
    patients: Array,
  },
  components: {
    AppLayout,
    Head,
    InertiaLink,
    flatPickr,
  },
};
</script>
<style>
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
  font-size: 14px;
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
  .print-only {
      display: block;
  }
  .print-hide {
      display: none;
  }
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
  .p_4{
    padding: 4px !important;
  }
  .text_white{
    color: #000 !important;
  }
  @page { size: A4; margin: 0; }
  body { margin: 0; }
.a4-size {
    width: 210mm;
    height: auto;
}
}
</style>