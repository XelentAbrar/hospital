<template>
  <!-- <button @click="open = true"
    v-bind="$attrs"class="bg-gray-100 p-2 rounded-full text-[#3f66ae] hvr-rectangle-out">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
    </button> -->
  <TransitionRoot
    :show="open"
    as="template"
    @after-leave="rawQuery = ''"
    appear
  >
    <Dialog class="relative z-10" @close="open = false">
      <TransitionChild
        as="template"
        enter="ease-out duration-300"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="ease-in duration-200"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-gray-500/25 transition-opacity" />
      </TransitionChild>

      <div
        class="fixed inset-0 z-10 w-screen overflow-y-auto p-4 sm:p-6 md:p-20"
      >
        <TransitionChild
          as="template"
          enter="ease-out duration-300"
          enter-from="opacity-0 scale-95"
          enter-to="opacity-100 scale-100"
          leave="ease-in duration-200"
          leave-from="opacity-100 scale-100"
          leave-to="opacity-0 scale-95"
        >
          <DialogPanel
            class="mx-auto max-w-2xl transform divide-y divide-gray-100 overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-black/5 transition-all"
          >
            <Combobox @update:modelValue="onSelect">
              <div class="grid grid-cols-1">
                <ComboboxInput
                  class="col-start-1 row-start-1 h-12 w-full pl-11 pr-4 text-base text-gray-900 outline-none placeholder:text-gray-400 sm:text-sm border-none"
                  placeholder="Search..."
                  @input="search($event.target.value)"
                  @blur="query = ''"
                />
                <MagnifyingGlassIcon
                  class="pointer-events-none col-start-1 row-start-1 ml-4 size-5 self-center text-gray-400"
                  aria-hidden="true"
                />
              </div>

              <ComboboxOptions
                v-if="expenses.length > 0 || vouchers.length > 0 || appointments.length > 0 || admissions.length > 0 || donors.length > 0 || labs.length > 0"
                static
                as="ul"
                class="max-h-80 transform-gpu scroll-py-10 scroll-pb-2 space-y-4 overflow-y-auto p-4 pb-2"
              >
                <li v-if="expenses.length > 0 || vouchers.length > 0 || appointments.length > 0 || admissions.length > 0 || donors.length > 0 || labs.length > 0">
                  <h2 class="text-xs font-semibold text-gray-900">
                    <span v-if="rawQuery.startsWith('#')"> Accounts Search Tickets </span>
                    <span v-else-if="rawQuery.startsWith('$')">
                      IPD Search Tickets
                    </span>
                    <span v-else-if="rawQuery.startsWith('%')">
                      OPD Search Tickets
                    </span>
                    <span v-else-if="rawQuery.startsWith('*')">
                      LAB Search Tickets
                    </span>
                    <span v-else-if="rawQuery.startsWith('^')">
                      Donor Search Tickets
                    </span>
                    <span v-else-if="rawQuery.startsWith('&')">
                      Expense Search Tickets
                    </span>
                    <span v-else> Search Tickets </span>
                  </h2>
                  <ul class="-mx-4 mt-2 text-sm text-gray-700">
                    <ComboboxOption
                      v-for="(item, index) in donors"
                      :key="index"
                      :value="item"
                      as="template"
                      v-slot="{ active }"
                    >
                      <li
                        :class="[
                          'flex cursor-default select-none items-center px-4 py-2',
                          active && 'bg-gray-100 text-white outline-none',
                        ]"
                      >
                        <div
                          class="relative flex justify-between gap-x-6 px-4 py-5 sm:px-6 lg:px-8 w-full" :set="item.slug = 'donor'"
                        >
                          <div class="flex min-w-0 gap-x-4">
                            <div class="min-w-0 flex-auto">
                              <p class="text-sm/6 font-semibold text-gray-900">
                                <a href="#">
                                  <span
                                    class="absolute inset-x-0 -top-px bottom-0"
                                  />
                                  {{ item?.donor?.name }}
                                </a>
                              </p>
                              <p class="mt-1 flex text-xs/5 text-gray-500">
                                <span class="relative truncate hover:underline">
                                  Amount: {{ item?.charges }}<br>
                                  {{ item?.donor?.description ? item?.donor?.description.substring(0, 30) : null }}
                                </span>
                              </p>
                            </div>
                          </div>
                          <div class="flex shrink-0 items-center gap-x-4">
                            <div
                              class="hidden sm:flex sm:flex-col sm:items-end"
                            >
                              <p class="text-sm/6 text-gray-900">
                                {{ item.donor_date }}
                              </p>
                              <div class="mt-1 flex items-center gap-x-1.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ml-1 bg-green-100 text-green-800">Donor</span>
                              </div>
                            </div>
                            <ChevronRightIcon
                              class="size-5 flex-none text-gray-400"
                              aria-hidden="true"
                            />
                          </div>
                        </div>
                      </li>
                    </ComboboxOption>
                  </ul>

                  <ul class="-mx-4 mt-2 text-sm text-gray-700">
                    <ComboboxOption
                      v-for="(item, index) in expenses"
                      :key="index"
                      :value="item"
                      as="template"
                      v-slot="{ active }"
                    >
                      <li
                        :class="[
                          'flex cursor-default select-none items-center px-4 py-2',
                          active && 'bg-gray-100 text-white outline-none',
                        ]"
                      >
                        <div
                          class="relative flex justify-between gap-x-6 px-4 py-5 sm:px-6 lg:px-8 w-full" :set="item.slug = 'expense'"
                        >
                          <div class="flex min-w-0 gap-x-4">
                            <div class="min-w-0 flex-auto">
                              <p class="text-sm/6 font-semibold text-gray-900">
                                <a href="#">
                                  <span
                                    class="absolute inset-x-0 -top-px bottom-0"
                                  />
                                  {{ item?.description ? item?.description.substring(0, 30) : null }}
                                </a>
                              </p>
                              <p class="mt-1 flex text-xs/5 text-gray-500">
                                <span class="relative truncate hover:underline">
                                  Amount: {{ item?.amount }}<br>
                                  {{ item?.notes ? item?.notes.substring(0, 50) : null }}
                                </span>
                              </p>
                            </div>
                          </div>
                          <div class="flex shrink-0 items-center gap-x-4">
                            <div
                              class="hidden sm:flex sm:flex-col sm:items-end"
                            >
                              <p class="text-sm/6 text-gray-900">
                                {{ item.date }}
                              </p>
                              <div class="mt-1 flex items-center gap-x-1.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ml-1 bg-green-100 text-green-800">Expense</span>
                              </div>
                            </div>
                            <ChevronRightIcon
                              class="size-5 flex-none text-gray-400"
                              aria-hidden="true"
                            />
                          </div>
                        </div>
                      </li>
                    </ComboboxOption>
                  </ul>


                  <ul class="-mx-4 mt-2 text-sm text-gray-700">
                    <ComboboxOption
                      v-for="(item, index) in labs"
                      :key="index"
                      :value="item"
                      as="template"
                      v-slot="{ active }"
                    >
                      <li
                        :class="[
                          'flex cursor-default select-none items-center px-4 py-2',
                          active && 'bg-gray-100 text-white outline-none',
                        ]"
                      >
                        <div
                          class="relative flex justify-between gap-x-6 px-4 py-5 sm:px-6 lg:px-8 w-full" :set="item.slug = 'labs'"
                        >
                          <div class="flex min-w-0 gap-x-4">
                            <div class="min-w-0 flex-auto">
                              <p class="text-sm/6 font-semibold text-gray-900">
                                <a href="#">
                                  <span
                                    class="absolute inset-x-0 -top-px bottom-0"
                                  />
                                  {{ item?.patient_name }} {{item?.patient_cnic != null ? '('+item?.patient_cnic+')' : ''}} {{item?.patient_phone != null ? '('+item?.patient_phone+')' : ''}}
                                </a>
                              </p>
                              <p class="mt-1 flex text-xs/5 text-gray-500">
                                <span class="relative truncate hover:underline">
                                  Test Nature: {{ item?.test_nature }}<br>
                                  Lab #: {{ item?.lab_no }}<br>
                                  File #: {{ item?.file_no }}<br>
                                </span>
                              </p>
                            </div>
                          </div>
                          <div class="flex shrink-0 items-center gap-x-4">
                            <div
                              class="hidden sm:flex sm:flex-col sm:items-end"
                            >
                              <p class="text-sm/6 text-gray-900">
                                {{ item.test_date }}
                              </p>
                              <div class="mt-1 flex items-center gap-x-1.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ml-1 bg-green-100 text-green-800">Lab Test</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ml-1 bg-green-100 text-green-800">Amount : {{item?.total_amount}}</span>
                              </div>
                            </div>
                            <ChevronRightIcon
                              class="size-5 flex-none text-gray-400"
                              aria-hidden="true"
                            />
                          </div>
                        </div>
                      </li>
                    </ComboboxOption>
                  </ul>

                  <ul class="-mx-4 mt-2 text-sm text-gray-700">
                    <ComboboxOption
                      v-for="(item, index) in appointments"
                      :key="index"
                      :value="item"
                      as="template"
                      v-slot="{ active }"
                    >
                      <li
                        :class="[
                          'flex cursor-default select-none items-center px-4 py-2',
                          active && 'bg-gray-100 text-white outline-none',
                        ]"
                      >
                        <div
                          class="relative flex justify-between gap-x-6 px-4 py-5 sm:px-6 lg:px-8 w-full" :set="item.slug = 'appointment'"
                        >
                          <div class="flex min-w-0 gap-x-4">
                            <div class="min-w-0 flex-auto">
                              <p class="text-sm/6 font-semibold text-gray-900">
                                <a href="#">
                                  <span
                                    class="absolute inset-x-0 -top-px bottom-0"
                                  />
                                  {{ item?.patient_name }} (MR# {{item?.patient?.mr_number}})
                                </a>
                              </p>
                              <p class="mt-1 flex text-xs/5 text-gray-500">
                                <span class="relative truncate hover:underline">
                                  Phone: {{ item?.patient_phone }}<br>
                                  {{ item?.doctor?.name }}
                                </span>
                              </p>
                            </div>
                          </div>
                          <div class="flex shrink-0 items-center gap-x-4">
                            <div
                              class="hidden sm:flex sm:flex-col sm:items-end"
                            >
                              <p class="text-sm/6 text-gray-900">
                                {{ item.appointment_date }} {{ item.appointment_time }}
                              </p>
                              <div class="mt-1 flex items-center gap-x-1.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ml-1 bg-green-100 text-green-800">OPD</span>
                              </div>
                            </div>
                            <ChevronRightIcon
                              class="size-5 flex-none text-gray-400"
                              aria-hidden="true"
                            />
                          </div>
                        </div>
                      </li>
                    </ComboboxOption>
                  </ul>
                  <ul class="-mx-4 mt-2 text-sm text-gray-700">
                    <ComboboxOption
                      v-for="(item, index) in admissions"
                      :key="index"
                      :value="item"
                      as="template"
                      v-slot="{ active }"
                    >
                      <li
                        :class="[
                          'flex cursor-default select-none items-center px-4 py-2',
                          active && 'bg-gray-100 text-white outline-none',
                        ]"
                      >
                        <div
                          class="relative flex justify-between gap-x-6 px-4 py-5 sm:px-6 lg:px-8 w-full" :set="item.slug = 'admission'"
                        >
                          <div class="flex min-w-0 gap-x-4">
                            <div class="min-w-0 flex-auto">
                              <p class="text-sm/6 font-semibold text-gray-900">
                                <a href="#">
                                  <span
                                    class="absolute inset-x-0 -top-px bottom-0"
                                  />
                                  {{ item?.name }} (MR# {{item?.patient?.mr_number}})
                                </a>
                              </p>
                              <p class="mt-1 flex text-xs/5 text-gray-500">
                                <span class="relative truncate hover:underline">
                                  Phone: {{ item?.phone }}<br>
                                  File# {{ item?.file_no }}
                                </span>
                              </p>
                            </div>
                          </div>
                          <div class="flex shrink-0 items-center gap-x-4">
                            <div
                              class="hidden sm:flex sm:flex-col sm:items-end"
                            >
                              <p class="text-sm/6 text-gray-900">
                                {{ item.admission_date }} {{ item.admission_time }}
                              </p>
                              <div class="mt-1 flex items-center gap-x-1.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ml-1 bg-green-100 text-green-800">IPD ({{item.status == 'Pending' ? 'Admit' : 'Discharge'}})</span>
                              </div>
                            </div>
                            <ChevronRightIcon
                              class="size-5 flex-none text-gray-400"
                              aria-hidden="true"
                            />
                          </div>
                        </div>
                      </li>
                    </ComboboxOption>
                  </ul>

                  <ul class="-mx-4 mt-2 text-sm text-gray-700">
                    <ComboboxOption
                      v-for="(item, index) in vouchers"
                      :key="index"
                      :value="item"
                      as="template"
                      v-slot="{ active }"
                    >
                      <li
                        :class="[
                          'flex cursor-default select-none items-center px-4 py-2',
                          active && 'bg-gray-100 text-white outline-none',
                        ]"
                      >
                        <div
                          class="relative flex justify-between gap-x-6 px-4 py-5 sm:px-6 lg:px-8 w-full" :set="item.slug = 'voucher'"
                        >
                          <div class="flex min-w-0 gap-x-4">
                            <div class="min-w-0 flex-auto">
                              <p class="text-sm/6 font-semibold text-gray-900">
                                <a href="#">
                                  <span
                                    class="absolute inset-x-0 -top-px bottom-0"
                                  />
                                  {{ item?.voucher_type?.voucher_name }}
                                </a>
                              </p>
                              <p class="mt-1 flex text-xs/5 text-gray-500">
                                <span class="relative truncate hover:underline">
                                  Voucher # {{ item?.voucher_no }}
                                </span>
                              </p>
                              <p class="mt-1 flex text-xs/5 text-gray-500">
                                <span class="" v-if="item?.search == 'acc_desc'">
                                  {{ item?.voucher_details[0]?.chart_of_account?.acc_desc }} -> {{ item?.voucher_details[0]?.chart_of_account?.account_code?.acc_desc }}
                                </span>
                                <span class="" v-else>
                                  <template v-for="voucher_details in item?.voucher_details">
                                    {{voucher_details?.chart_of_account?.acc_desc}},
                                  </template>
                                </span>
                              </p>
                            </div>
                          </div>
                          <div class="flex shrink-0 items-center gap-x-4">
                            <div
                              class="hidden sm:flex sm:flex-col sm:items-end"
                            >
                              <p class="text-sm/6 text-gray-900">
                                {{ item.voucher_date.split("T")[0] }}
                              </p>
                              <div class="mt-1 flex items-center gap-x-1.5" v-if="item?.search == 'acc_desc'">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ml-1 bg-green-100 text-green-800">{{item?.voucher_details[0]?.cr > 0 ? 'CR '+item?.voucher_details[0]?.cr : 'DR '+item?.voucher_details[0]?.dr}}</span>
                              </div>
                              <div class="mt-1 flex items-center gap-x-1.5" v-else>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ml-1 bg-green-100 text-green-800">CR {{calSum('cr', item?.voucher_details)}}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ml-1 bg-green-100 text-green-800">DR {{calSum('dr', item?.voucher_details)}}</span>
                              </div>
                            </div>
                            <ChevronRightIcon
                              class="size-5 flex-none text-gray-400"
                              aria-hidden="true"
                            />
                          </div>
                        </div>
                      </li>
                    </ComboboxOption>
                  </ul>
                </li>
              </ComboboxOptions>

              <div
                v-if="rawQuery === '?'"
                class="px-6 py-14 text-center text-sm sm:px-14"
              >
                <LifebuoyIcon
                  class="mx-auto size-6 text-gray-400"
                  aria-hidden="true"
                />
                <p class="mt-4 font-semibold text-gray-900">
                  Help with searching
                </p>
                <p class="mt-2 text-gray-500">
                  Use this tool to quickly search for master bids, inventory and
                  leads across entire platform. You can search by stock number,
                  vin, claim number, Year, make, model status, owner name, offer
                  amount or gross total amount.
                </p>
              </div>

              <div
                v-if="query !== '' && rawQuery !== '?' && (expenses.length == 0 && vouchers.length == 0 && appointments.length == 0 && admissions.length == 0 && donors.length == 0)"
                class="px-6 py-14 text-center text-sm sm:px-14"
              >
                <ExclamationTriangleIcon
                  class="mx-auto size-6 text-gray-400"
                  aria-hidden="true"
                />
                <p class="mt-4 font-semibold text-gray-900">No results found</p>
                <p class="mt-2 text-gray-500">
                  We couldn't find anything with that term. Please try again.
                </p>
              </div>

              <div
                class="flex flex-wrap items-center bg-gray-50 px-4 py-2.5 text-xs text-gray-700"
              >
                Type
                <kbd
                  :class="[
                    'mx-1 flex size-5 items-center justify-center rounded border bg-white font-semibold sm:mx-2',
                    rawQuery.startsWith('#')
                      ? 'border-indigo-600 text-indigo-600'
                      : 'border-gray-400 text-gray-900',
                  ]"
                >
                  #
                </kbd>
                <span class="sm:hidden">for Accounts,</span>
                <span class="hidden sm:inline">for Accounts,</span>
                <kbd
                  :class="[
                    'mx-1 flex size-5 items-center justify-center rounded border bg-white font-semibold sm:mx-2',
                    rawQuery.startsWith('$')
                      ? 'border-indigo-600 text-indigo-600'
                      : 'border-gray-400 text-gray-900',
                  ]"
                >
                  $
                </kbd>
                for IPD,
                <kbd
                  :class="[
                    'mx-1 flex size-5 items-center justify-center rounded border bg-white font-semibold sm:mx-2',
                    rawQuery.startsWith('%')
                      ? 'border-indigo-600 text-indigo-600'
                      : 'border-gray-400 text-gray-900',
                  ]"
                >
                  %
                </kbd>
                for OPD,
                <kbd
                  :class="[
                    'mx-1 flex size-5 items-center justify-center rounded border bg-white font-semibold sm:mx-2',
                    rawQuery.startsWith('*')
                      ? 'border-indigo-600 text-indigo-600'
                      : 'border-gray-400 text-gray-900',
                  ]"
                >
                  *
                </kbd>
                for LAB.
                <kbd
                  :class="[
                    'mx-1 flex size-5 items-center justify-center rounded border bg-white font-semibold sm:mx-2',
                    rawQuery.startsWith('^')
                      ? 'border-indigo-600 text-indigo-600'
                      : 'border-gray-400 text-gray-900',
                  ]"
                >
                  ^
                </kbd>
                for Donor,
                <kbd
                  :class="[
                    'mx-1 flex size-5 items-center justify-center rounded border bg-white font-semibold sm:mx-2',
                    rawQuery.startsWith('&')
                      ? 'border-indigo-600 text-indigo-600'
                      : 'border-gray-400 text-gray-900',
                  ]"
                >
                  &
                </kbd>
                for Expense.
              </div>
            </Combobox>
          </DialogPanel>
        </TransitionChild>
      </div>
    </Dialog>
  </TransitionRoot>
</template>


<script setup>
import { computed, onMounted, onUnmounted, ref } from "vue";
import { MagnifyingGlassIcon } from "@heroicons/vue/20/solid";
import {
  ExclamationTriangleIcon,
  FolderIcon,
  LifebuoyIcon,
} from "@heroicons/vue/24/outline";
import {
  Combobox,
  ComboboxInput,
  ComboboxOptions,
  ComboboxOption,
  Dialog,
  DialogPanel,
  TransitionChild,
  TransitionRoot,
} from "@headlessui/vue";
import { debounce } from "lodash";
import axios from "axios";
import { router } from "@inertiajs/vue3";

const open = ref(false);
const rawQuery = ref("");
const query = computed(() => rawQuery.value.toLowerCase().replace(/^[#$%^&*]/, ""));

function calSum(type, itemDetail) {
  let total = 0;
  itemDetail.map(res => {
    total = +total + +res[type];
  });
  return total;
}
function onSelect(item) {
  if (item) {
    if(item.slug == 'donor'){
      router.visit(
        route("donor-funds.edit", {
          id: item.id,
        })
      );
    }
    else if(item.slug == 'expense'){
      router.visit(
        route("expenses.edit", {
          id: item.id,
        })
      );
    }
    else if(item.slug == 'labs'){
      router.visit(
        route("patient-tests.edit", {
          id: item.id,
        })
      );
    }
    else if(item.slug == 'appointment'){
      router.visit(
        route("appointments.edit", {
          id: item.id,
        })
      );
    }
    else if(item.slug == 'admission'){
      router.visit(
        route("admissions.edit", {
          id: item.id,
        })
      );
    }
    else if(item.slug == 'voucher'){
      router.visit(
        route("vouchers.edit", {
          id: item.id,
        })
      );
    }
    open.value = false;
  }
}

const vouchers = ref([]);
const expenses = ref([]);
const labs = ref([]);
const appointments = ref([]);
const admissions = ref([]);
const donors = ref([]);

const search = debounce(async (term) => {
  rawQuery.value = term;
  if (query.value === "" || query.value.length < 3) return;

  let searchFrom = "";
  if (term.startsWith("#")) {
    searchFrom = "Accounts";
  } else if (term.startsWith("$")) {
    searchFrom = "IPD";
  } else if (term.startsWith("%")) {
    searchFrom = "OPD";
  } else if (term.startsWith("^")) {
    searchFrom = "Donor";
  } else if (term.startsWith("&")) {
    searchFrom = "Expense";
  } else if (term.startsWith("*")) {
    searchFrom = "labs";
  }

  let { data } = await axios.post(route("quick-search"), {
    searchParms: query.value,
    searchFrom: searchFrom,
  });
  vouchers.value = data.vouchers;
  expenses.value = data.expenses;
  labs.value = data.labs;
  appointments.value = data.appointments;
  admissions.value = data.admissions;
  donors.value = data.donors;
}, 250);

const onKeyDown = (event) => {
  if (open.value) return;
  if (event.key === "F2" || event.keyCode === 113) {
    event.preventDefault();
    open.value = true;
  }
};

onMounted(() => {
  window.addEventListener("keydown", onKeyDown);
});

onUnmounted(() => {
  window.removeEventListener("keydown", onKeyDown);
});

</script>
<style scoped>
/* Slide Animation */
.slide-enter-active, .slide-leave-active {
  transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
}
.slide-enter-from, .slide-leave-to {
  transform: translateY(-20px);
  opacity: 0;
}

/* Rectangle Out */
.hvr-rectangle-out {
  display: inline-block;
  vertical-align: middle;
  -webkit-transform: translateZ(0);
  transform: translateZ(0);
  box-shadow: 0 0 1px rgba(0, 0, 0, 0);
  -webkit-backface-visibility: hidden;
  backface-visibility: hidden;
  -moz-osx-font-smoothing: grayscale;
  position: relative;
  -webkit-transition-property: color;
  transition-property: color;
  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  border-radius: 100%;
}
.hvr-rectangle-out:before {
  content: "";
  position: absolute;
  z-index: -1;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: #3f66ae;
  -webkit-transform: scale(0);
  transform: scale(0);
  -webkit-transition-property: transform;
  transition-property: transform;
  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -webkit-transition-timing-function: ease-out;
  transition-timing-function: ease-out;
  border-radius: 100%;
}
.hvr-rectangle-out:hover, .hvr-rectangle-out:focus, .hvr-rectangle-out:active {
  color: white;
}
.hvr-rectangle-out:hover:before, .hvr-rectangle-out:focus:before, .hvr-rectangle-out:active:before {
  -webkit-transform: scale(1);
  transform: scale(1);
}

</style>
