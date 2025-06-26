<template>
  <Head title="Summary By Date" />
  <AppLayout title="Summary By Date">
    <div class="w-full mx-auto pt-6">
      <div class="bg-white shadow-sm rounded-lg">
        <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
            <h1 class="font-semibold text-3xl text-primary">Summary By Date</h1>
            <div class="mt-2 ml-auto sm:mt-0">
              <form @submit.prevent="report.get(route('opd.reports-summary-by-date'), report)">
                <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-3">
                  <div>
                    <label
                      for="from_date"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Search
                    </label>
                    <div class="mt-2">
                      <input
                        id="search"
                        name="search"
                        type="text"
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-400 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                        v-model="report.search"
                      />
                    </div>
                  </div>
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
                         autocomplete="off"
                       data-date-format="d-m-Y H:i"
                        @change="formattedFromDate"
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-400 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
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
                        autocomplete="off"
                       data-date-format="d-m-Y H:i"  class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-400 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"

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
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                  >
                    Search
                  </button>
                  <button
                  class="rounded bg-blue-700 px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-blue-700 hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 ml-2" @click="print()" >
                    Print
                  </button>
                  </div>
              </div>
            </form>

            </div>
          </div>
          <div class="mt-6 flow-root" id="printData">
            <div class="relative a4-size"
            style="font-family: Arial, Helvetica, sans-serif; padding: 8px 20px;display: flex;flex-direction: column;">
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
                        <p v-if="projectType == 'hms'" style="font-size: 12px;">Nawaz Town Sargodha Road, Faisalabad</p>
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
              <div>
                  <!-- <p class="print-hide" -->
                  <p style="font-size: 20px;font-weight: 600;text-align: center;margin:8px 0;" v-if="projectType == 'hms'">Summary By Date (SGD)</p>
                  <p style="font-size: 20px;font-weight: 600;text-align: center;margin:8px 0;" v-else>Summary By Date (JC)</p>
              </div>
            </div>
            <div class="ring-1 ring-gray-400">
                  <table class="min-w-full border-separate border-spacing-0 divide-y divide-gray-400 overflow-auto">
                    <thead>
                      <tr class="divide-x divide-gray-400">
                        <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10"
                      >
                        Sr#
                      </th>

                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10"
                      >
                        Service
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10"
                      >
                        From
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10"
                      >
                        To
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10"
                      >
                        Patient
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10"
                      >
                        Amount
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10"
                      >
                        CareOff
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10"
                      >
                        Z/F
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 max-w-24 sticky top-0 z-10"
                      >
                        S.D
                      </th>
                       <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 max-w-24 sticky top-0 z-10"
                      >
                        Refund
                      </th>
                       <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 max-w-24 sticky top-0 z-10"
                      >
                        Received
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10"
                      >
                        Exp.
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10"
                      >
                        Remarks
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-for="(category, category_name) in orderedReports"
                      :key="category_name"
                    >
                    <tr
                      class="divide-x divide-gray-400"
                    >
                    <td class="whitespace-nowrap border-y border-gray-400 text-left font-bold text-gray-900 bg-blue-200 sticky top-12 2xl:top-[60px] z-20" colspan="13" v-if="category_name && category_name.trim() !== ''">{{category_name}}</td>
                  </tr>

                  <tr  v-if="category_name === 'MO EYE OPD RECEIPT'"
    class="divide-x divide-gray-400 even:bg-gray-50 odd:bg-white"
    v-for="(report, index) in reports['MO EYE OPD RECEIPT']"
    :key="index"
    :class="index % 2 === 0 ? 'bg-gray-50' : 'bg-white'"
  >
    <td class="whitespace-nowrap relative border-b border-gray-400 text-center text-gray-900">
      {{ index + 1 }}
    </td>


    <td class=" relative border-b border-gray-400 text-gray-900">
    {{ report.service_name }} {{ report.doctor_fee ? ' - ' + report.doctor_fee : '' }}
    </td>
    <td class="whitespace-nowrap relative border-b border-gray-400 text-center text-gray-900">
      {{ formatNumber(report.from ?? 0) }}
    </td>
    <td class="whitespace-nowrap relative border-b border-gray-400 text-center text-gray-900">
      {{ formatNumber(report.to ?? 0) }}
    </td>
    <td class="whitespace-nowrap relative border-b border-gray-400 text-gray-900">
      {{ formatNumber(report.patients ?? 0) }}
    </td>

    <td class="whitespace-nowrap relative border-b border-gray-400 text-gray-900">
      {{ formatNumber(report.fee ?? 0) }}
    </td>

    <td class="whitespace-nowrap relative border-b border-gray-400 text-gray-900">
      {{ formatNumber(report.careOff  ?? 0)}}
    </td>
    <td class="whitespace-nowrap relative border-b border-gray-400 text-gray-900">
      {{ formatNumber(report.zf ?? 0) }}
    </td>
    <td class="whitespace-normal w-24 relative border-b border-gray-400 text-gray-900">
      0 <!-- {{ report.discount_amount }} -->
    </td>
    <td class="whitespace-normal w-24 relative border-b border-gray-400 text-gray-900">
      0 <!-- {{ report.discount_amount }} -->
    </td> <td class="whitespace-normal w-24 relative border-b border-gray-400 text-gray-900">
      0 <!-- {{ report.discount_amount }} -->
    </td>
    <td class="whitespace-nowrap relative border-b border-gray-400 text-gray-900">
       {{ formatNumber(report.amount ?? 0)}}
    </td>
    <td class="remarks relative border-b border-gray-400 text-gray-900">
       <!-- {{ report.notes }} -->
    </td>
  </tr>

                    <tr v-else-if="category_name === 'Billing' || category_name === 'PVT CONSULTANT RECEIPT' ||  category_name === 'LAB & OTHER RECEIPTS' || category_name === 'Expenses'"
                  class="divide-x divide-gray-400 even:bg-gray-50 odd:bg-white"
                  v-for="(report, Subindex) in category" :key="Subindex"
                  :class="index % 2 === 0 ? 'bg-gray-50' : 'bg-white'"
                  >
                  <td
                  class="whitespace-nowrap relative border-b border-gray-400 text-center text-gray-900"
                  >
                  {{ Subindex + 1 }}
                </td>

                      <td
                        class=" relative border-b border-gray-400 text-gray-900"
                      >
                        {{ report?.service_name }}
                      </td>
                      <td class="whitespace-nowrap relative border-b border-gray-400 text-center text-gray-900">
                        {{ formatNumber(report.from ?? 0) }}
                        </td>
                        <td class="whitespace-nowrap relative border-b border-gray-400 text-center text-gray-900">
                        {{ formatNumber(report.to ?? 0) }}
                        </td>
                      <td
                        class="whitespace-nowrap relative border-b border-gray-400 text-gray-900"
                      >
                      <!-- {{ formatNumber((report.to && report.from) ? (report.to - report.from) : 0) }} -->
                        {{ formatNumber(report?.patients ?? 0) }}
                    </td>

                      <td
                        class="whitespace-nowrap relative border-b border-gray-400 text-gray-900"
                      >
                        {{ formatNumber(report?.fee ?? 0) }}
                      </td>
                      <td
                        class="whitespace-nowrap relative border-b border-gray-400 text-gray-900"
                      >
                        {{ formatNumber(report?.careOff ?? 0) }}
                      </td>
                      <td
                        class="whitespace-nowrap relative border-b border-gray-400 text-gray-900"
                      >
                        {{ formatNumber(report?.zf ?? 0) }}
                      </td>
                      <td
                        class="whitespace-nowrap relative border-b border-gray-400 text-gray-900"
                      >
                        {{ formatNumber(report?.discount_amount ?? 0) }}
                      </td>
                      <td
                        class="whitespace-nowrap relative border-b border-gray-400 text-gray-900"
                      >
                        {{ formatNumber(report?.refund ?? 0) }}
                      </td>
                      <td
                        class="whitespace-nowrap relative border-b border-gray-400 text-gray-900"
                      >
                        {{ formatNumber(report?.received ?? 0) }}
                      </td>
                      <td
                        class="whitespace-nowrap relative border-b border-gray-400 text-gray-900"
                      >

                      {{ formatNumber(report?.amount ?? 0) }}
                      </td>
                      <td
                        class="relative remarks border-b border-gray-400 text-gray-900"
                      >
                      {{ report?.notes ?? ''}}
                      </td>
                    </tr>

                    <tr v-if="category_name === 'MO EYE OPD RECEIPT' || category_name === 'Billing' || category_name === 'LAB & OTHER RECEIPTS' || category_name === 'Expenses' || category_name === 'PVT CONSULTANT RECEIPT'"
                      class="divide-x divide-gray-400 bg-secondary text-black"
                    >
                      <td
                        class="whitespace-nowrap font-semibold relative border-b border-gray-400 text-center"
                        colspan="2"
                      >
                        Total
                      </td>
                      <td class=" border-b border-gray-400"></td>
                      <td class=" border-b border-gray-400"></td>
                      <td
                        class="whitespace-nowrap font-bold text-xl relative border-b border-gray-400"
                      >
                        {{ formatNumber(stats[category_name]['patients']) }}
                      </td>
                      <td
                        class="whitespace-nowrap font-bold text-xl relative border-b border-gray-400"
                      >
                        {{ formatNumber(stats[category_name]['fee']) }}
                      </td>
                      <td
                        class="whitespace-nowrap font-bold text-xl relative border-b border-gray-400"
                      >
                        {{ formatNumber(stats[category_name]['careOff']) }}
                      </td>
                      <td
                        class="whitespace-nowrap font-bold text-xl relative border-b border-gray-400"
                      >
                        {{ formatNumber(stats[category_name]['zf']) }}
                      </td>
                      <td
                        class="whitespace-nowrap font-bold text-xl relative border-b border-gray-400"
                      >
                        {{ formatNumber(stats[category_name]['discount_amount']) }}
                      </td>
                       <td
                        class="whitespace-nowrap font-bold text-xl relative border-b border-gray-400"
                      >
                        {{ formatNumber(stats[category_name]['refund']) }}
                      </td>
                      <td
                        class="whitespace-nowrap font-bold text-xl relative border-b border-gray-400"
                      >
                        {{ formatNumber(stats[category_name]['received']) }}
                      </td>
                      <td
                        class="whitespace-nowrap font-bold text-xl relative border-b border-gray-400 text-left"
                      >
                      {{ formatNumber(stats[category_name]['total_amount']) }}
                    </td>
                      <td class="whitespace-nowrap font-bold text-xl relative border-b border-gray-400 text-center">
                      </td>
                    </tr>
                    </template>
                  </tbody>
                </table>
              </div>

            <div class="flex justify-between py-5">
              <div class="flex items-center w-fit min-w-[40%] justify-end ring-1 ring-gray-400 sm:rounded-lg overflow-x-auto overflow-y-hidden">
                <table class="w-fit min-w-full border-separate border-spacing-0 divide-y divide-gray-400 overflow-auto">
                    <tbody>
                      <tr class="divide-x divide-gray-400"><td class="whitespace-nowrap border-b border-gray-400 relative p_4 text-center font-bold text-gray-900 text-base md:text-lg 2xl:text-xl bg-blue-200 border-b" colspan="9">Total</td></tr>
                        <tr class="divide-x divide-gray-400">
                            <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-sm md:text-base 2xl:text-lg">Total Receipts</td>
                            <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-sm md:text-base 2xl:text-lg">{{ formatNumber(totalFee) }}</td>
                            <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-sm md:text-base 2xl:text-lg"></td>
                        </tr>
                        <tr class="divide-x divide-gray-400">
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-sm md:text-base 2xl:text-lg">Doctor W.H.T</td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-sm md:text-base 2xl:text-lg">0</td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-sm md:text-base 2xl:text-lg"></td>
                        </tr>
                        <tr class="divide-x divide-gray-400">
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-sm md:text-base 2xl:text-lg">Received Amount</td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-sm md:text-base 2xl:text-lg">{{ formatNumber(totalReceived) }}</td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-sm md:text-base 2xl:text-lg"></td>
                        </tr>
                        <!-- <tr class="divide-x divide-gray-400">
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-sm md:text-base 2xl:text-lg">discounted Amount</td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-sm md:text-base 2xl:text-lg">{{ formatNumber(totalCareOff + totalZF  + totalDisc) }}</td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-sm md:text-base 2xl:text-lg"></td>
                        </tr> -->
                        <tr class="divide-x divide-gray-400">
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-sm md:text-base 2xl:text-lg">Refund Amount</td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-sm md:text-base 2xl:text-lg"></td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left  font-extrabold text-gray-900 text-sm md:text-base 2xl:text-lg">{{ formatNumber(totalRefund) }}</td>
                        </tr>
                        <tr class="divide-x divide-gray-400" v-if="hasDonationModule">
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-sm md:text-base 2xl:text-lg">Care Off</td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-sm md:text-base 2xl:text-lg"></td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-sm md:text-base 2xl:text-lg">{{ formatNumber(totalCareOff) }}</td>
                        </tr>
                        <tr class="divide-x divide-gray-400" v-if="hasDonationModule">
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-sm md:text-base 2xl:text-lg">Zakat Fund</td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-sm md:text-base 2xl:text-lg"></td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-sm md:text-base 2xl:text-lg">{{ formatNumber(totalZF) }}</td>
                        </tr>
                        <tr class="divide-x divide-gray-400">
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-sm md:text-base 2xl:text-lg">S.D</td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-sm md:text-base 2xl:text-lg"></td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-sm md:text-base 2xl:text-lg">{{ formatNumber(totalDisc) }}</td>
                        </tr>
                        <tr class="divide-x divide-gray-400">
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-sm md:text-base 2xl:text-lg">Expense</td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-sm md:text-base 2xl:text-lg"></td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-sm md:text-base 2xl:text-lg">{{ formatNumber(totalExp) }}</td>
                        </tr>
                        <tr class="divide-x divide-gray-400">
                          <td class="border-t border-transparent whitespace-nowrap relative text-left font-bold text-gray-900 text-sm md:text-base 2xl:text-lg"></td>
                          <td class="border-t border-double whitespace-nowrap relative text-left font-extrabold text-gray-900 text-sm md:text-base 2xl:text-lg">{{ formatNumber(totalFee + totalReceived ) }}</td>
                          <td class="border-t border-double whitespace-nowrap relative text-left font-extrabold text-gray-900 text-sm md:text-base 2xl:text-lg">{{ formatNumber(totalCareOff + totalZF + totalExp + totalDisc  + totalRefund) }}</td>
                        </tr>
                        <tr class="divide-x divide-gray-400 bg-secondary text-black">
                          <td class="whitespace-nowrap relative border-t border-gray-400 text-center font-bold text-base md:text-lg 2xl:text-xl">Total Cash in Hand</td>
                          <td colspan="2" class=" whitespace-nowrap relative border-t border-gray-400 text-center font-black text-base md:text-lg 2xl:text-xl">{{ formatNumber(totalFee + totalReceived  -(totalCareOff + totalZF + totalExp + totalDisc  + totalRefund)) }}</td>
                        </tr>
                        <!-- <tr class="divide-x divide-gray-400 bg-secondary text-black">
                          <td class="whitespace-nowrap relative border-t border-gray-400 text-center font-bold text-base md:text-lg 2xl:text-xl">Total WithOut CareOFF</td>
                          <td colspan="2" class=" whitespace-nowrap relative border-t border-gray-400 text-center font-black text-base md:text-lg 2xl:text-xl">{{ formatNumber(totalFee + totalReceived  -(totalZF + totalExp + totalDisc  + totalRefund)) }}</td>
                        </tr> -->
                    </tbody>
                </table>
              </div>
              <div class="mt-10 w-5/12 px-4 space-y-4 flex flex-col items-end justify-end print-only">
                <div class="flex items-center space-x-4">
                  <p class="flex items-center space-x-1 whitespace-nowrap text-xs font-medium w-28">Dated </p>
                  <p class="flex items-center space-x-1 whitespace-nowrap text-xs font-medium">:</p>
                  <p class="flex items-center space-x-1 whitespace-nowrap text-xs font-medium"> __________________________ </p>
                </div>
                <div class="flex items-center space-x-4">
                  <p class="flex items-center space-x-1 whitespace-nowrap text-xs font-medium w-28">Bank Deposit </p>
                  <p class="flex items-center space-x-1 whitespace-nowrap text-xs font-medium">:</p>
                  <p class="flex items-center space-x-1 whitespace-nowrap text-xs font-medium"> __________________________ </p>
                </div>
                <div class="flex items-center space-x-4">
                  <p class="flex items-center space-x-1 whitespace-nowrap text-xs font-medium w-28">Slip # </p>
                  <p class="flex items-center space-x-1 whitespace-nowrap text-xs font-medium">:</p>
                  <p class="flex items-center space-x-1 whitespace-nowrap text-xs font-medium"> __________________________ </p>
                </div>
                <div class="flex items-center space-x-4">
                  <p class="flex items-center space-x-1 whitespace-nowrap text-xs font-medium w-28">Authority </p>
                  <p class="flex items-center space-x-1 whitespace-nowrap text-xs font-medium">:</p>
                  <p class="flex items-center space-x-1 whitespace-nowrap text-xs font-medium"> __________________________ </p>
                </div>
            </div>
            </div>


              </div>
          </div>

        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed ,watch , ref , onMounted} from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
const from_date = ref(null);
const to_date = ref(null);
const props = defineProps({
  hasDonationModule: Boolean,
  stats: Object,
  reports: Object,
  result: Object,
  from_date: String,
  to_date: String,
  search: String,
});
const projectType = import.meta.env.VITE_PROJECT_TYPE;
const report = useForm({
  from_date: props?.from_date || null,
  to_date: props?.to_date || null,
  search: props?.search || null,
});


    onMounted(() => {
      const flatpickrOptions = (defaultDate) => ({
        enableTime: true, // Enable time selection
        dateFormat: "d-m-Y H:i", // Format: DD-MM-YYYY HH:MM
        allowInput: true, // Allow manual typing
        clickOpens: true, // Ensure the picker opens on click
        minTime: "00:00", // Minimum time
        maxTime: "23:59", // Maximum time
        defaultDate: defaultDate || null, // Use provided default date or null
        onChange: (selectedDates, dateStr, instance) => {
          if (instance.element.id === "from_date") {
            report.from_date = dateStr;
          } else if (instance.element.id === "to_date") {
            report.to_date = dateStr;
          }
        },
      });

      flatpickr("#from_date", flatpickrOptions(report.from_date));
      flatpickr("#to_date", flatpickrOptions(report.to_date));
    });
//     onMounted(() => {
//   const flatpickrOptions = (defaultDate) => ({
//     enableTime: false, // Disable time selection
//     dateFormat: "d-m-Y", // Format: DD-MM-YYYY
//     allowInput: true, // Allow manual typing
//     clickOpens: true, // Ensure the picker opens on click
//     defaultDate: defaultDate || null, // Use provided default date or null
//     onChange: (selectedDates, dateStr, instance) => {
//       if (instance.element.id === "from_date") {
//         report.from_date = dateStr;
//       } else if (instance.element.id === "to_date") {
//         report.to_date = dateStr;
//       }
//     },
//   });

//   flatpickr("#from_date", flatpickrOptions(report.from_date));
//   flatpickr("#to_date", flatpickrOptions(report.to_date));
// });
const formatNumber = (number) => {
    return new Intl.NumberFormat().format(number);
};

const totalFee = computed(() => {
  if (!props.stats) return 0;
  return Object.values(props.stats).reduce((total, category) => total + category.fee, 0);
});
const totalCareOff = computed(() => {
  if (!props.stats) return 0;
  return Object.values(props.stats).reduce((total, category) => total + category.careOff, 0);
});
const totalZF = computed(() => {
  if (!props.stats) return 0;
  return Object.values(props.stats).reduce((total, category) => total + category.zf, 0);
});
const totalExp = computed(() => {
  if (!props.stats) return 0;
  return Object.values(props.stats).reduce((total, category) => total + category.total_amount, 0);
});
const totalReceived = computed(() => {
  if (!props.stats) return 0;
  return Object.values(props.stats).reduce((total, category) => total + category.received, 0);
});
const totalRefund = computed(() => {
  if (!props.stats) return 0;
  return Object.values(props.stats).reduce((total, category) => total + category.refund, 0);
});
const totalDisc = computed(() => {
  if (!props.stats) return 0;
  return Object.values(props.stats).reduce((total, category) => total + category.discount_amount, 0);
});
const orderedReports = computed(() => {
  const reordered = {};
  const reports = props.reports || {};
  if (reports['MO']) {
    reordered['MO'] = reports['MO'];
  }
  for (const category in reports) {
    if (category !== 'MO') {
      reordered[category] = reports[category];
    }
  }

  return reordered;
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
         td.remarks {
      border: 1px solid black;
      padding: 4px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      max-width: 150px; /* Set a max-width for cells */
    }
      }
    </style>
