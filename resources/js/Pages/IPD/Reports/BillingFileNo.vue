<template>
  <Head title="Consultant Tax Reports" />
  <AppLayout title="Consultant Tax Reports">
    <div class="w-full mx-auto pt-6">
      <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <div
            class="flex flex-col md:flex-row items-start md:items-center justify-between"
          >
            <h1 class="font-semibold text-3xl text-primary">Billing File No.</h1>
            <div class="mt-2 ml-auto sm:mt-0">
              <form
              @submit.prevent="filter.get(route('ipd.reports-billing-file-no'), filter)"
            >
              <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-3">
                  <div>
                    <label
                      for="file_no"
                      class="block text-base md:text-lg font-medium text-gray-900"
                      >File No.
                    </label>
                    <div class="mt-2">
                      <input
                        id="file_no"
                        name="file_no"
                        type="text"
                        autocomplete="file_no"
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                        step="0.01"
                        v-model="filter.file_no"
                      />
                    </div>
                  </div>
                  
                  <div class="mt-2">
                    <label
                      for="to_date"
                      class="block text-base md:text-lg font-medium text-gray-900"
                      >&nbsp;
                    </label>
                    <button
                  type="submit"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                  >
                    Search
                  </button>
                  </div>
              </div>
            </form>

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
import InputError from "../../Components/InputError.vue";

const props = defineProps({
  file_nos: String,
});

const filter = useForm({
  file_no: null,
});

const printLabTest = (admission_id) => {
  const url = route('admissions.print.discharge', {admission:admission_id, discharge:'report'});
  const printWindow = window.open(url, '_blank');
  printWindow.focus();
  printWindow.print();
};

if(props?.file_nos){
    printLabTest(props?.file_nos);
}


</script>
<script>
import Multiselect from "vue-multiselect";
export default {
  components: {
    Multiselect,
    AppLayout,
    Head,
    InertiaLink,
    InputError
  },
};
</script>
    <style src="vue-multiselect/dist/vue-multiselect.css"></style>
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