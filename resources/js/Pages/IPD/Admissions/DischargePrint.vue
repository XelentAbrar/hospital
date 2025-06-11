<template>
    <div  class="a4-size relative mx-auto flex flex-col rounded-lg shadow-md">
        <div class="flex-initial">
            <div class="relative">
                <span class="bg-[#8B322C] block h-16 w-full"></span>
                <span class="bg-[#FFC470] block h-8 w-11/12"></span>
                <div class="clip h-32 w-32 bg-[#8B322C] absolute top-0">
                    <span class="clip block h-24 w-24 bg-[#FFC470] absolute top-4"></span>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div
                    class="flex items-center relative space-x-4 mt-2 mb-4 ml-20 pb-2 border-b-4 border-[#FFC470] w-3/5">
                    <img class="h-auto w-24" src="@/icons/logo.png" alt="" />
                    <h1 class="doctor-info text-3xl font-bold">CHINIOT GENERAL HOSPITAL</h1>
                    <div class="border border-[#8B322C] w-1/2 h-0.5 absolute right-0 -bottom-[3px]"></div>
                </div>
                <div class="space-y-3 ml-4 mt-2 w-2/5">
                    <div class="flex items-center space-x-4">
                        <div class="border border-[#FFC470] p-1 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-6 text-[#FFC470]">
                                <path fill-rule="evenodd"
                                    d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p v-if="projectType === 'hms'">Ph: 041-8787231-8848060</p>
                        <p v-else>Ph: 041-2618764-2634890</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="border border-[#FFC470] p-1 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="size-6 text-[#FFC470]">
                                <path fill-rule="evenodd"
                                    d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <p v-if="projectType === 'hms'">Nawaz Town Sargodha Road, Faisalabad</p>
                        <p v-else>209 Jinnah Colony , Faisalabad</p>
                    </div>
                </div>
            </div>
        </div>
        <div class=" mt-2 px-20">


            <div class="flex justify-between border-y border-black">
                <div class="hospital-header">
                    <h1>Status: <span class="underline pl-4">{{admission.status}}</span></h1>
                </div>
                <div>
                <h1 v-if="admission.is_discharged == '2'" class=" text-gray-900 text-center  items-center text-lg mt-1.5 font-bold w-24">Duplicate</h1>
            </div>
                <div class="bill-header">
                <p>Slip No: {{ admission.slip_no }}</p>
            </div>
        </div>

        <div class="bill-details text-sm mt-2">
            <div class="flex justify-between">
                <p class="w-1/4 whitespace-nowrap mt-2"><strong>File No:</strong> {{ admission.file_no }}</p>
                <p class="w-1/4 whitespace-nowrap mt-2"><strong>Admit Date:</strong> {{ new Date(admission.admission_date).toLocaleDateString('en-GB').replace(/\//g, '-') }} {{ new Date(`${admission.admission_date}T${admission.admission_time}`).toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true }) }} </p>
                <p class="w-1/4 whitespace-nowrap mt-2"><strong>ChartNo:</strong> {{admission.chart_no}}</p>
            </div>
            <div class="flex justify-between">
                <p class="w-1/4 whitespace-nowrap mt-2"><strong>Name:</strong> {{ admission.name }}</p>
                <p class="w-1/4 whitespace-normal mt-2"><strong>Guardian:</strong> {{ admission.relation_name }}</p>
            </div>
            <div class="flex justify-between">
                <p class="w-1/4 whitespace-normal mt-2"><strong>Mr:</strong> {{ mr_number }}</p>
                <p class="w-1/2 whitespace-nowrap mt-2"><strong>Discharge Date:</strong> {{ new Date(admission.discharge_date).toLocaleDateString('en-GB').replace(/\//g, '-') }} {{ new Date(`${admission.discharge_date}T${admission.discharge_time}`).toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true }) }}
                </p>
            </div>
            <div class="flex justify-between">
                <p class="w-1/4 whitespace-nowrap mt-2"><strong>Dept.:</strong> {{ admission?.department?.name }}</p>
                <p class="w-1/4 whitespace-normal mt-2"><strong>Referred by:</strong> {{ admission.refered_by}}</p>
            </div>
            <div class="flex justify-between">
                <p class="w-1/4 whitespace-nowrap mt-2">
                    <strong>Days #</strong>
                    <span>
                        {{ admission.details
                        .filter(day => day?.service?.is_days === '1')
                        .map(day => day.no_of_days)
                        .join(', ') }}
                    </span>
                    </p>

                <p class="w-1/4 whitespace-nowrap mt-2"><strong>Bed #:</strong> {{ admission?.room_type?.name }}</p>
            </div>
        </div>
        <div class="treatment-details" v-if="admission.details.length > 0">
            <table>
                <thead>
                    <tr>
                        <th>Treatment Description</th>
                        <th>No's </th>
                        <th>Rate</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="detail in admission.details">
                    <tr v-if="detail?.service?.is_advance != 1 && detail?.service?.name && (detail.service_amount > 0 || detail.day_charges > 0)" >
                        <td>{{ detail?.service?.name }}</td>
                        <!-- <td>{{(detail?.service?.is_nursing == '1' ? detail?.no_of_days : '1')}}</td> -->
                        <td class="text-right">{{(detail?.no_of_days)}}</td>
                        <td class="text-right">{{ detail?.service_amount > 0 ? withCommas(parseFloat(detail?.service_amount).toFixed(0)) : withCommas(parseFloat(detail?.day_charges).toFixed(0)) }}</td>
                        <!-- <td>{{ withCommas(detail?.service_amount * (detail?.service?.is_nursing == '1' ? detail?.no_of_days : '1')) }}</td> -->
                        <td class="text-right">{{ withCommas(detail?.day_charges) }}</td>
                    </tr>
                    </template>

                    <template v-for="detail in admission.details" >
                    <tr  v-if="detail?.doctor?.name && detail.amount != null">
                        <td>{{ detail?.doctor?.name }}</td>
                        <td class="text-right">{{(detail?.no_of_days)}}</td>
                        <td class="text-right">{{ withCommas(detail?.amount) }}</td>
                        <td class="text-right">{{ withCommas(detail?.day_charges) }}</td>
                    </tr>
                    </template>
                    <tr v-if="days > 0">
                        <td>Bed Charges</td>
                        <td>
                            {{days}}
                        </td>
                        <td class="text-right">
                            {{ withCommas(admission?.room?.charges)}}
                        </td>
                        <td class="text-right">
                            {{ withCommas(admission?.room?.charges * days)}}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="bill-summary flex justify-between items-start gap-4">
            <div>
                <p ><strong class="underline">Description</strong><br>
                    {{ (careoffs.find(c => c.id === admission.careoff_id) || { name: '' })?.name}} </p>
                    <div class="mt-8">
                            <p  class="text-xs  "><strong class="underline">User</strong><br>
                            {{admission?.updator?.name}} </p>
                        </div>
                         <div class="mt-8">
                            <p class="text-sm"><strong class="underline">Authorized</strong><br>
                            {{admission.welfare}} </p>
                        </div>
                </div>
            <div>
                <p class="text-right"><strong>Total:</strong> <input disabled type="text" :value="withCommas( +service_amount)" class="p-0 text-right pr-1"></p>
                <p class="text-right mt-1"><strong>Advance:</strong> <input disabled type="text" :value="withCommas(advance_charges)" class="p-0 text-right pr-1"></p>
                <div class="flex justify-between">

                    <div class="my-1 border-y border-black w-96">
                        <p class="flex items-right mt-2"><strong class="w-1/2 text-right">Total Bill:</strong> <span class="pl-4 w-1/2 ml-6 text-right">{{ withCommas(+service_amount - +advance_charges) }}</span></p>
                        <p class="flex items-right mt-2" v-if="hasDonationModule"><strong class="w-1/2 text-right">Care Off:</strong> <span class="pl-4 w-1/2 ml-6 text-right">{{ withCommas(admission.donor_fee) }}</span></p>
                        <p class="flex items-right mt-2" v-if="hasDonationModule"><strong class="w-1/2 text-right">Zakat:</strong> <span class="pl-4 w-1/2 ml-6 text-right">{{ withCommas(admission.zf_fee) }}</span></p>
                        <p class="flex items-right mt-2"><strong class="w-1/2 text-right">S.D:</strong> <span class="pl-4 w-1/2 ml-6 text-right">{{ withCommas(admission.discount_amount) }}</span></p>
                        <!-- <p class="flex items-right mt-2"><strong class="w-1/2 text-right">Remaining Balance:</strong> <span class="pl-4 w-1/2 ml-6 text-right">{{ withCommas(+days_charges + +service_amount + +amount - +admission.advance_amount - +admission.donor_fee - +admission.zf_fee - +admission.discount_amount) }}</span></p> -->
                        <p class="flex items-right mt-2" v-if="admission.refund_amount > 0"><strong class="w-1/2 text-right">Refund Amt.:</strong> <span class="pl-4 w-1/2 ml-6 text-right">{{ withCommas(+admission.refund_amount) }}</span></p>
                        <p class="flex items-right mt-2" v-if="admission.refund_amount <= 0"><strong class="w-1/2 text-right">Received Amount:</strong> <span class="pl-4 w-1/2 ml-6 text-right">{{ withCommas(admission.received_amount) }}</span></p>
                        <p class="border border-y border-black"></p>
                        <p class="flex items-right mt-2"><strong class="w-1/2 text-right">Net Bill Amount:</strong> <span class="pl-4 w-1/2 ml-6 text-right">{{ withCommas(+service_amount - +admission.donor_fee - +admission.zf_fee - +advance_charges - +admission.received_amount - +admission.discount_amount + +admission.refund_amount) }}</span></p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>


</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const { props } = usePage();
const hasDonationModule = ref(props.hasDonationModule);
const admission = ref(props.admission);
const days = ref(props.days);
const service_amount = ref(props.service_amount);
const amount = ref(props.amount);
const mr_number = ref(props.mr_number);
const days_charges = ref(props.days_charges);
const advance_charges = ref(props.advance_charges);
const careoffs = ref(props.careoffs);
const user = ref(props.user);
const projectType = import.meta.env.VITE_PROJECT_TYPE;
const formattedDate = new Date().toLocaleDateString("en-GB", {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
});

const withCommas = (value) => {
  if(value == null || value == ''){
    return '0';
  }
    return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

</script>

<script>
  export default {
  mounted() {
    // Automatically trigger the print dialog when the page/component is loaded
    setTimeout(() => {
      window.print();
      // Close the tab after the print dialog
      setTimeout(() => {
        window.close();
      }, 500);
    }, 500);
  },
//   beforeDestroy() {
//     setTimeout(() => {
//     //   window.print();

//       setTimeout(() => {
//         window.close();
//       }, 500);
//     }, 500);
//   },
  methods:{
    handleKeydown(event) {
      if (event.ctrlKey && event.key === 'p') {
        event.preventDefault();
        window.location.reload();
      }
    },
  },
}
</script>

<style>
         @media print {
          @page { size: A4; margin: 0; }
          body { margin: 0; }
      }
        .a4-size {
            width: 210mm;
            height: 297mm;
        }

        .p_20 {
            padding: 20mm;
        }

        .px_20 {
            padding-left: 20mm;
            padding-right: 20mm;
        }

        .clip {
            clip-path: polygon(0 0, 66% 0, 100% 50%, 66% 100%, 0 100%, 0% 50%);
        }

        .vector {
            position: absolute;
            top: 20mm;
            right: 20mm;
            width: 50mm;
            height: 50mm;
            background: conic-gradient(from 180deg at 50% 50%, #6EE7B7, #3B82F6, #9333EA, #F59E0B);
            clip-path: polygon(50% 0%, 100% 100%, 0% 100%);
            opacity: 0.1;
        }

        .prescription-area .left {
            width: 25%;
            border-right: 2px solid #d1d5db;
        }

        .prescription-area .right {
            width: 75%;
            padding-left: 16px;
        }

        .doctor-info {
            font-family: 'Helvetica', Arial, sans-serif;
        }

        .header-accent {
            border-bottom: 4px solid #3B82F6;
            padding-bottom: 4px;
        }

.print-container {
    padding: 20px;
    font-family: Arial, sans-serif;
}

.bill-details,
.treatment-details,
.bill-summary {
    margin-bottom: 14px;
}

.hospital-header h1 {
    margin: 0;
    font-size: 24px;
}

.hospital-header p,
.bill-header p {
    margin: 5px 0;
}

.bill-header h2 {
    margin: 0;
    font-size: 20px;
}

.treatment-details table {
    width: 100%;
    border-collapse: collapse;
}

.treatment-details th,
.treatment-details td {
    border: 1px solid black;
    padding: 8px;
    /* text-align: right; */
}

    </style>
