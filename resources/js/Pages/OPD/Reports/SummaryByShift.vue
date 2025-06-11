<template>
  <Head title="Summary By Shift" />
  <AppLayout title="Summary By Shift">
    <div class="w-full mx-auto pt-6">
      <div class="bg-white shadow-sm rounded-lg">
        <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <div
            class="flex flex-col md:flex-row items-start md:items-center justify-between"
          >
            <h1 class="font-semibold text-3xl text-primary">Summary By Shift</h1>
            <div class="mt-2 ml-auto sm:mt-0">
              <form
              @submit.prevent="report.get(route('opd.reports-summary-by-shift'), report)"
            >
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
                        name="from_date" autocomplete="off"
                        type="text" ref="from_date"  data-date-format="d-m-Y H:i"
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
                        name="to_date" autocomplete="off"
                        type="text" ref="to_date" data-date-format="d-m-Y H:i"
                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-400 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"

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
                  <!-- <button
                  class="rounded bg-blue-800 px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-blue-700 hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 ml-2" @click="print()" v-if="reports['1'] && reports['1'].length > 0" >
                    Print
                  </button> -->
                  <button
                    class="rounded bg-blue-800 px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-blue-700 hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 ml-2"
                    @click="print()"
                    v-if="hasReports"
                    >
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
                        <p style="font-size: 12px;"  v-if="projectType === 'hms'">Ph: 041-8848060 | 8787231</p>
                        <p  style="font-size: 12px;"  v-else>Ph: 041-2619764 | 2634890</p>
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
                          <p style="margin: 0;margin-left: 2px;text-align: left;font-size: 12px;">All</p>
                      </div>
                  </div>
                  <div style="width: 50%;display: flex;justify-content: start;">
                      <div style="display: flex;align-items: center;">
                          <h4 style="margin: 0;font-family: sans-serif; width: 100px;font-weight:600;font-size: 12px;">Date & Time</h4>
                          <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                          <p style="margin: 0;margin-left: 2px;text-align: left;white-space: nowrap;font-size: 12px;">{{ new Date().getDate() + '-' + (new Date().getMonth() + 1) + '-' + new Date().getFullYear() }} {{new Date().toTimeString().slice(0, 5)}}</p>
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
                  <p
                      style="font-size: 20px;font-weight: 600;text-align: center;margin: 0;margin-bottom: 8px;margin-top: 8px;">Summary By Shift</p>
              </div>
            </div>
            <div class="ring-1 ring-gray-400">
                  <table class="min-w-full border-separate border-spacing-0 divide-y divide-gray-400 overflow-auto">
                    <thead>
                      <tr class="divide-x divide-gray-400">
                        <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-bold text_white sticky top-0 z-10"
                      >
                        Sr#
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-bold text_white sticky top-0 z-10"
                      >
                        Service
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-bold text_white sticky top-0 z-10"
                      >
                        From
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-bold text_white sticky top-0 z-10"
                      >
                        To
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-bold text_white sticky top-0 z-10"
                      >
                        Patient
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-bold text_white sticky top-0 z-10"
                      >
                        Amount
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-bold text_white sticky top-0 z-10"
                      >
                        CareOff
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-bold text_white sticky top-0 z-10"
                      >
                        Z/F
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-bold text_white sticky top-0 z-10"
                      >
                        Received
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-bold text_white sticky top-0 z-10"
                      >
                        Refund
                      </th> <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-bold text_white sticky top-0 z-10"
                      >
                        S.D
                      </th>
                      <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-bold text_white sticky top-0 z-10"
                      >
                        Exp.
                      </th>
                       <th
                        scope="col"
                        class="bg-primary whitespace-nowrap text-center text-base md:text-lg font-bold text_white sticky top-0 z-10"
                      >
                       Remarks
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- <template v-for="(shift, shift_index) in shifts" :key="shift_index">
                    <tr class="divide-x divide-gray-400">
                      <td
                        class="whitespace-nowrap relative border-y border-gray-400 text-center text-gray-900 text-sm md:text-base bg-blue-200" colspan="6"
                      >
                        <span class="font-bold">{{ shift.name }}</span>
                      </td>
                    </tr>
                    <tr
                      class="divide-x divide-gray-400"
                      v-for="(report, index) in reports[shift.name]"
                      :key="index"
                      :class="index % 2 === 0 ? 'bg-gray-50' : 'bg-white'"
                    >
                      <td
                        class="whitespace-nowrap relative border-b border-gray-400 text-center text-gray-900 text-sm md:text-base"
                      >
                        {{ index+1 }}
                      </td>
                      <td
                        class="whitespace-nowrap relative border-b border-gray-400 text-gray-900 text-sm md:text-base"
                      >
                        {{ report?.service_name }}
                      </td>
                      <td
                        class="whitespace-nowrap relative border-b border-gray-400 text-gray-900 text-sm md:text-base"
                      >
                        {{ report?.patients }}
                      </td>
                      <td
                        class="whitespace-nowrap relative border-b border-gray-400 text-gray-900 text-sm md:text-base"
                      >
                        {{ report?.fee }}
                      </td>
                      <td
                        class="whitespace-nowrap relative border-b border-gray-400 text-gray-900 text-sm md:text-base"
                      >
                        {{ report?.careOff }}
                      </td>
                      <td
                        class="whitespace-nowrap relative border-b border-gray-400 text-gray-900 text-sm md:text-base"
                      >
                        {{ report?.appointment.zf }}
                      </td>
                    </tr>
                      <tr
                        class="bg-secondary text-white"
                      >
                        <td
                          class="whitespace-nowrap relative border-b border-gray-400 text-center  text-sm md:text-base"
                          colspan="2"
                        >
                          Total
                        </td>
                        <td
                          class="whitespace-nowrap relative border-b border-gray-400  text-sm md:text-base"
                        >
                          {{ sum(reports[shift.id], 'patients') }}
                        </td>
                        <td
                          class="whitespace-nowrap relative border-b border-gray-400  text-sm md:text-base"
                        >
                          {{ sum(reports[shift.id], 'fee') }}
                        </td>
                        <td
                          class="whitespace-nowrap relative border-b border-gray-400  text-sm md:text-base"
                        >
                          {{ sum(reports[shift.id], 'careOff') }}
                        </td>
                        <td
                          class="whitespace-nowrap relative border-b border-gray-400  text-sm md:text-base"
                        >
                          {{ sum(reports[shift.id], 'zf') }}
                        </td>
                      </tr>
                    </template> -->
                    <template v-for="shift in shifts" :key="shift.id">
                        <tr class="">
                            <td colspan="13" class="whitespace-nowrap border-y border-gray-400 text-center py-1.5 font-bold text-gray-900 text-sm md:text-base bg-blue-200 sticky top-12 z-10">
                            <span class="">{{ shift.name }}</span>
                            </td>
                        </tr>
                        <template v-for="(reportType, reportTypeName) in reports[shift.name]" :key="reportTypeName">
                            <tr class="whitespace-nowrap text-center text-gray-900 text-sm md:text-base">
                            <td colspan="13" class="p-0">
                                <p class="font-semibold p_2 border-t border-gray-400">{{ reportTypeName }}</p>
                                </td>
                            </tr>
                                <!-- <table class="">

                                <tbody> -->
                                    <tr class="divide-x divide-gray-400" v-for="(item, index) in reportType" :key="index" :class="index % 2 === 0 ? 'bg-gray-50' : 'bg-white'">
                                    <td class="whitespace-nowrap relative border-t border-gray-400 text-center ">{{ index + 1 }}</td>
                                    <td class=" relative border-t border-gray-400">{{ item.service_name }}{{ item.doctor_fee ? ' - ' + item.doctor_fee : '' }}</td>
                                    <td class="whitespace-nowrap relative border-t border-gray-400">{{ formatNumber(item.from ?? 0) }}</td>
                                    <td class="whitespace-nowrap relative border-t border-gray-400">{{ formatNumber(item.to ?? 0) }}</td>
                                    <td class="whitespace-nowrap relative border-t border-gray-400">   {{ formatNumber(item.patients) }}</td>
                                    <td class="whitespace-nowrap relative border-t border-gray-400">{{ formatNumber(item.fee  ?? 0) }}</td>
                                    <td class="whitespace-nowrap relative border-t border-gray-400">{{ formatNumber(item.careOff || 0) }}</td>
                                    <td class="whitespace-nowrap relative border-t border-gray-400">{{ formatNumber(item.zf || 0) }}</td>
                                    <td class="whitespace-nowrap relative border-t border-gray-400">{{ formatNumber(item.received || 0 )}}</td>
                                    <td class="whitespace-nowrap relative border-t border-gray-400">{{ formatNumber(item.refund || 0 )}}</td>
                                    <td class="whitespace-nowrap relative border-t border-gray-400">{{ formatNumber(item.discount_amount || 0 )}}</td>
                                    <td class="whitespace-nowrap relative border-t border-gray-400">{{ formatNumber(item.amount || 0 )}}</td>
                                    <td class="remarks relative border-t border-gray-400">{{ formatNumber(item.note || 0) }}</td>
                                    </tr>
                                    <!-- Totals Row -->

                                <!-- </tbody> -->
                                <!-- <tfoot> -->
                                    <tr class=" bg-secondary text-black py-1 border-t">
                                    <td colspan="2" class="border-b border-r p-1 footer_border text-xl text-center">Total</td>
                                    <td class="border-b border-r text-center footer_border text-xl"></td>
                                    <td class="border-b border-r text-center footer_border text-xl"></td>
                                    <!-- <td class="border-b border-r text-center footer_border text-base 2xl:text-lg">{{ formatNumber(sumform(reportType)) }}</td> -->
                                    <td class="border-b border-r text-center footer_border text-xl font-bold ">{{ formatNumber(sum(reportType, 'patients')) }}</td>
                                    <td class="border-b border-r text-center footer_border text-xl font-bold ">{{ formatNumber(sum(reportType, 'fee')) }}</td>
                                    <td class="border-b border-r text-center footer_border text-xl font-bold">{{ formatNumber(sum(reportType, 'careOff')) }}</td>
                                    <td class="border-b border-r text-center footer_border text-xl font-bold">{{ formatNumber(sum(reportType, 'zf')) }}</td>
                                    <td class="border-b border-r text-center footer_border text-xl font-bold">{{ formatNumber(sum(reportType, 'received')) }}</td>
                                    <td class="border-b border-r text-center footer_border text-xl font-bold">{{ formatNumber(sum(reportType, 'refund')) }}</td>
                                    <td class="border-b border-r text-center footer_border text-xl font-bold">{{ formatNumber(sum(reportType, 'discount_amount')) }}</td>
                                    <td class="border-b border-r text-center footer_border text-xl font-bold">{{ formatNumber(sum(reportType, 'amount')) }}</td>
                                    <td class="border-b footer_border text-xl"></td>
                                    </tr>
                                <!-- </tfoot> -->
                                <!-- </table> -->

                            <!-- </tr> -->
                    </template>
        </template>
                  </tbody>

                </table>
              </div>
              <div class="flex justify-between py-5">
              <div class="flex items-center w-fit min-w-[40%] justify-end ring-1 ring-gray-400 sm:rounded-lg overflow-x-auto overflow-y-hidden">
                <table class="w-fit min-w-full border-separate border-spacing-0 divide-y divide-gray-400 overflow-auto">
                    <tbody>
                      <tr class="divide-x divide-gray-400"><td class="whitespace-nowrap relative p_4 border-b border-gray-400 text-center font-bold text-gray-900 text-lg 2xl:text-xl bg-blue-200" colspan="9">Total</td></tr>
                        <tr class="divide-x divide-gray-400">
                            <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-base 2xl:text-lg">Total Receipts</td>
                            <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-base 2xl:text-lg ">{{ formatNumber(totalFee) }}</td>
                            <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-base 2xl:text-lg"></td>
                        </tr>
                        <tr class="divide-x divide-gray-400">
                            <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-base 2xl:text-lg">Total Received</td>
                            <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-base 2xl:text-lg">{{ formatNumber(totalReceived) }}</td>
                            <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-base 2xl:text-lg"></td>
                        </tr>
                        <!-- <tr class="divide-x divide-gray-400">
                            <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-xl md:text-base">Discounted Amount</td>
                            <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-xl md:text-base">{{ formatNumber(totalDisc + totalZF + totalCareOff) }}</td>
                            <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-xl md:text-base"></td>
                        </tr> -->
                         <tr class="divide-x divide-gray-400">
                            <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-base 2xl:text-lg">Total Refund</td>
                            <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-base 2xl:text-lg"></td>
                            <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-base 2xl:text-lg">{{ formatNumber(totalRefund) }}</td>
                        </tr>
                        <tr class="divide-x divide-gray-400">
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-base 2xl:text-lg">Doctor W.H.T</td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-base 2xl:text-lg">0</td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-base 2xl:text-lg"></td>
                        </tr>
                        <tr class="divide-x divide-gray-400">
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-base 2xl:text-lg">Care Off</td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-base 2xl:text-lg"></td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-base 2xl:text-lg">{{ formatNumber(totalCareOff) }}</td>
                        </tr>
                        <tr class="divide-x divide-gray-400">
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-base 2xl:text-lg">Zakat Fund</td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-base 2xl:text-lg"></td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-base 2xl:text-lg">{{ formatNumber(totalZF) }}</td>
                        </tr>
                        <tr class="divide-x divide-gray-400">
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-base 2xl:text-lg">S.D</td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-base 2xl:text-lg"></td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-base 2xl:text-lg">{{ formatNumber(totalDisc) }}</td>
                        </tr>
                        <tr class="divide-x divide-gray-400">
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-bold text-gray-900 text-base 2xl:text-lg">Expense</td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-base 2xl:text-lg"></td>
                          <td class="whitespace-nowrap relative border-b border-gray-400 text-left font-extrabold text-gray-900 text-base 2xl:text-lg">{{ formatNumber(totalAmount) }}</td>
                        </tr>
                        <tr class="divide-x divide-gray-400">
                          <td class="border-t border-transparent whitespace-nowrap relative text-left font-bold text-gray-900 text-base 2xl:text-lg"></td>
                          <td class="border-t border-double whitespace-nowrap relative text-left font-extrabold text-gray-900 text-base 2xl:text-lg">{{ formatNumber(totalFee + totalReceived) }}</td>
                          <td class="border-t border-double whitespace-nowrap relative text-left font-extrabold text-gray-900 text-base 2xl:text-lg">{{ formatNumber(totalCareOff + totalZF + totalExp + totalDisc + totalRefund) }}</td>
                        </tr>
                        <tr class="divide-x divide-gray-400 bg-secondary text-black">
                          <td class="border-t border-gray-400 whitespace-nowrap relative text-center font-bold text-lg 2xl:text-xl">Total Cash In Hand</td>
                          <td colspan="2" class="border-t border-gray-400 whitespace-nowrap relative text-center font-black text-lg 2xl:text-xl">{{ formatNumber(totalFee + totalReceived  -(totalCareOff + totalZF + totalExp + totalDisc + totalRefund)) }}</td>
                        </tr>

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
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import { computed  , watch , ref , onMounted} from "vue";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
const from_date = ref(null);
const to_date = ref(null);
const projectType = import.meta.env.VITE_PROJECT_TYPE;
const props = defineProps({
  shifts: Array,
  reports: Object,
  from_date: String,
  to_date: String,
  search: String,
});

const report = useForm({
  from_date: props?.from_date || null,
  to_date: props?.to_date || null,
  search: props?.search || null,
});
    onMounted(() => {
        const flatpickrOptions = (defaultDate) => ({
            enableTime: true,
            dateFormat: "d-m-Y H:i",
            allowInput: true,
            clickOpens: true,
            defaultDate: defaultDate || new Date(),
        });

        flatpickr(to_date.value, flatpickrOptions(report.to_date));
        flatpickr(from_date.value, flatpickrOptions(report.from_date));

    });
// watch(
//   () => report.from_date,
//   (newVal) => {
//     console.log("Formatted From Date:", newVal);
//     let formattedFromDate = moment(newVal).format('YYYY-MM-DD HH:mm:ss');
//     console.log("Converted From Date:", formattedFromDate);
//     report.from_date = formattedFromDate;
//   }
// );

// watch(
//   () => report.to_date,
//   (newVal) => {
//     console.log("Formatted To Date:", newVal);
//     let formattedToDate = moment(newVal).format('YYYY-MM-DD HH:mm:ss');
//     console.log("Converted To Date:", formattedToDate);
//     report.to_date = formattedToDate;
//   }
// );

// const sum = (reports, val) => {
//   let total = 0;
//   if(reports){
//     reports.map(res => {
//         total = +total + +res.appointment[val]
//     });
//   }
//   return total;
// }
const sum = (reports, key) => {
  return reports ? reports.reduce((total, item) => total + (Number(item[key]) || 0), 0) : 0;
};
const sumform = (reports) => {
  return reports ? reports.reduce((total, item) => {
    const difference = (Number(item.to) || 0) - (Number(item.from) || 0);
    return total + difference;
  }, 0) : 0;
};

const formatNumber = (number) => {
    return new Intl.NumberFormat().format(number);
};
const hasReports = computed(() => {
  return props.reports && Object.values(props.reports).some(shiftReports => {
    return Object.values(shiftReports).some(reportType => reportType.length > 0);
  });
});

const totalFee = computed(() => {
  return Object.values(props.reports).flatMap(shiftReports =>
    Object.values(shiftReports)    .flatMap(reportType => reportType)
  ).reduce((total, item) => total + (Number(item.fee) || 0), 0);
});

const totalCareOff = computed(() => {
  return Object.values(props.reports).flatMap(shiftReports =>
    Object.values(shiftReports).flatMap(reportType => reportType)
  ).reduce((total, item) => total + (Number(item.careOff) || 0), 0);
});
const totalReceived = computed(() => {
  return Object.values(props.reports).flatMap(shiftReports =>
    Object.values(shiftReports).flatMap(reportType => reportType)
  ).reduce((total, item) => total + (Number(item.received) || 0), 0);
});
const totalRefund = computed(() => {
  return Object.values(props.reports).flatMap(shiftReports =>
    Object.values(shiftReports).flatMap(reportType => reportType)
  ).reduce((total, item) => total + (Number(item.refund) || 0), 0);
});
const totalDisc = computed(() => {
  return Object.values(props.reports).flatMap(shiftReports =>
    Object.values(shiftReports).flatMap(reportType => reportType)
  ).reduce((total, item) => total + (Number(item.discount_amount) || 0), 0);
});

const totalZF = computed(() => {
  return Object.values(props.reports).flatMap(shiftReports =>
    Object.values(shiftReports).flatMap(reportType => reportType)
  ).reduce((total, item) => total + (Number(item.zf) || 0), 0);
});

const totalAmount = computed(() => {
  return Object.values(props.reports).flatMap(shiftReports =>
    Object.values(shiftReports).flatMap(reportType => reportType)
  ).reduce((total, item) => total + (Number(item.amount) || 0), 0);
});
const totalExp = computed(() => {
  return Object.values(props.reports).flatMap(shiftReports =>
    Object.values(shiftReports).flatMap(reportType => reportType)
  ).reduce((total, item) => total + (Number(item.amount) || 0), 0);
});

// const totalDisc = computed(() => 0);
// const totalExp = computed(() => 0);

const finalTotal = computed(() => {
  return totalFee.value - (totalCareOff.value + totalZF.value + totalExp.value + totalDisc.value);
});
const print = () => {
  var header_str = '<html><head><title>' + document.title  + '</title></head><body>';
  var footer_str = '</body></html>';
  var new_str = document.getElementById('printData').innerHTML;
  var old_str = document.body.innerHTML;
  document.body.innerHTML = header_str + new_str + footer_str;
  window.print();
  const myTimeout = setTimeout(function(){
      window.location.reload()
    }, 8000);
  return false;
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
            /* padding: 10px 8px; */
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
        table th, table tfoot td{
          padding: 12px 14px;
          font-size:16px;
        }
       table td{
          padding: 10px 12px;
          font-size: 14px;
        }
        .total_row {
            border-left: 1px solid;
            border-right: 1px solid;
        }

        .total_row td {
            border: none;
            font-weight: 600;
        }

        /* table tfoot tr {
            border: 1px solid;
        } */

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
        .p_2{
          padding: 8px;
        }
        .footer_border{
          border-top: 1px solid #e5e7eb;
          border-right: 1px solid #e5e7eb;
          font-weight: 600;
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
            table th, table tfoot td{
            padding: 4px;
            font-size: 12px !important;
            line-height: 10px !important;
          }
          table td{
            padding: 4px;
            font-size: 10px !important;
            line-height: 10px !important;
          }
          .text_white{
            color: #000 !important;
          }
        .p_2{
          padding: 6px 8px;
        }
        @page { size: A4; margin: 0; }
          body { margin: 0; }
        .a4-size {
            width: 210mm;
            height: auto;
        }
        td.remarks {
      border: 1px solid black;
      padding: 8px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      max-width: 200px; /* Set a max-width for cells */
    }
        /* .footer_border{
          border-top: 1px solid #1f2937;
          border-right: 1px solid #1f2937;
        } */
        }
    </style>
