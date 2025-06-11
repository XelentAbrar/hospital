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
        <div class=" mt-2 px-4">
          <div>
      <h2 class="center sub-header">Advance Receipt Slip</h2>
      <h2 v-if="admission.is_printed == '2'" class="bg-gray-200 text-gray-900 text-center border border-gray-300  text-md font-semibold w-24">Duplicate</h2>

      <div class="line"></div>
      <table class="table">
        <tr>
          <td class="bold">Slip No:</td>
          <td>{{ admission.slip_no }}</td>
          <td class="bold">Dated:</td>
          <td>{{ new Date(admission.admission_date).toLocaleDateString('en-GB').replace(/\//g, '-') }} {{ new Date(`${admission.admission_date}T${admission.admission_time}`).toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true }) }}

        </td>
        </tr>
        <tr>
          <td class="bold">File No:</td>
          <td>{{ admission.file_no }}</td>
          <td class="bold">S/F/W/D:</td>
          <td class="uppercase">{{ admission.relation_name }}</td>
        </tr>
        <tr class="bold">
            Mr. {{ mr_number }}
        </tr>
        <tr>
          <td class="bold">Name:</td>
          <td class="uppercase">{{ admission.name }}</td>
          <td class="bold">Department:</td>
          <td class="uppercase">{{ admission?.department?.name }}</td>
        </tr>
        <tr>
          <td class="bold">Description:</td>
          <td> <span v-if="hasDonationModule">{{ (careoffs.find(c => c.id === admission.careoff_id) || { name: '' })?.name}} </span></td>
          <td class="bold">Amount:</td>
          <td>
            {{ withCommas(admission?.advance_amount ?? 0) }}

          </td>
        </tr>
        <tr>
        <td class="bold"></td>
        <td></td>
        <td></td>
        <td><span class="amount bold uppercase underline"
              >({{ numberToWords(admission.advance_amount) }} Rupees Only)</span
            ></td>
            </tr>
        <tr>
          <td class="bold whitespace-nowrap">Service Name:</td>
          <td class="uppercase"> Advances</td>
          <td class="bold"></td>
          <td></td>
        </tr>
        <tr>
          <td class="bold">User Name:</td>
          <td class="uppercase text-xs">{{admission?.creator?.name}}</td>
          <td class="bold"></td>
          <td></td>
        </tr><tr>
          <td class="bold">Authorized:</td>
          <td class="uppercase">{{ admission.welfare }}</td>
          <td class="bold"></td>
          <td></td>
        </tr>
        <tr>
          <td class="bold">Signature:</td>
          <td>_______________________</td>
        </tr>

      </table>
      <div class="line"></div>
      <h1 class="center header">Chiniot General Hospital</h1>
      <h2 class="center sub-header">Advance Receipt Slip</h2>
      <div class="line"></div>
      <table class="table">
        <tr>
          <td class="bold">Slip No:</td>
          <td>{{ admission.slip_no }}</td>
          <td class="bold">Dated:</td>
          <td>{{ new Date(admission.admission_date).toLocaleDateString('en-GB').replace(/\//g, '-') }} {{ new Date(`${admission.admission_date}T${admission.admission_time}`).toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true }) }}</td>
        </tr>
        <tr class="bold">
            Mr. {{ mr_number }}
        </tr>
        <tr>
          <td class="bold">File No:</td>
          <td>{{ admission.file_no }}</td>
          <td class="bold">S/F/W/D:</td>
          <td class="uppercase">{{ admission.relation_name }}</td>
        </tr>
        <tr>
          <td class="bold">Name:</td>
          <td class="uppercase">{{ admission.name }}</td>
          <td class="bold">Department:</td>
          <td class="uppercase">{{ admission?.department?.name }}</td>
        </tr>
        <tr>
          <td class="bold">Description:</td>
          <td><span v-if="hasDonationModule">{{ (careoffs.find(c => c.id === admission.careoff_id) || { name: 'Careoff' })?.name}}</span></td>
          <td class="bold">Amount:</td>
          <td>
            {{ withCommas(admission?.advance_amount ?? 0) }}

          </td>
        </tr>
        <tr><td></td>
        <td></td>
        <td></td>
        <td><span class="amount bold uppercase underline"
              >({{ numberToWords(admission.advance_amount) }} Rupees Only) </span
            ></td>
            </tr>
        <tr>
          <td class="bold">Service Name:</td>
          <td class="uppercase">Advances</td>
          <td class="bold"></td>
          <td></td>
        </tr>
        <tr>
          <td class="bold">User Name:</td>
          <td class="uppercase">{{admission?.creator?.name}}</td>
          <td class="bold"></td>
          <td></td>
        </tr>
         <tr>
          <td class="bold">Authorized:</td>
          <td class="uppercase">{{ admission.welfare }}</td>
          <td class="bold"></td>
          <td></td>
        </tr>

      </table>
    </div>
    </div>
    </div>

</template>

<script setup>
import { ref, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";

const { props } = usePage();
const hasDonationModule = ref(props.hasDonationModule);
const admission = ref(props.admission);
const days = ref(props.days);
const service_amount = ref(props.service_amount);
const amount = ref(props.amount);
const mr_number = ref(props.mr_number);
const careoffs = ref(props.careoffs);
const days_charges = ref(props.days_charges);
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
const numberToWords = (num) => {
    if (num === 0) return 'zero';

    const belowTwenty = [
        'zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten',
        'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
    ];

    const tens = [
        '', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'
    ];

    const scales = [
        '', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion'
    ];

    const chunks = chunkNumber(num);
    const words = [];

    for (let i = 0; i < chunks.length; i++) {
        if (chunks[i] > 0) {
            words.push(convertChunk(chunks[i]) + (scales[i] ? ' ' + scales[i] : ''));
        }
    }

    return words.reverse().join(' ').trim();
}
const chunkNumber = (num) => {
    const chunks = [];
    while (num > 0) {
        chunks.push(num % 1000);
        num = Math.floor(num / 1000);
    }
    return chunks;
}
const convertChunk = (chunk) => {
    const belowTwenty = [
        'zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten',
        'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
    ];
    const tens = [
        '', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'
    ];
    let result = '';
    if (chunk >= 100) {
        result += belowTwenty[Math.floor(chunk / 100)] + ' hundred ';
        chunk %= 100;
    }
    if (chunk >= 20) {
        result += tens[Math.floor(chunk / 10)] + ' ';
        chunk %= 10;
    }
    if (chunk > 0) {
        result += belowTwenty[chunk] + ' ';
    }
    return result.trim();
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
      }, 500); // Allow a short delay to ensure the print dialog is processed
    }, 500); // Delay before triggering print
  },

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
      body {
        font-family: Arial, sans-serif;
        margin: 20px;
      }
      .center {
        text-align: center;
      }
      .bold {
        font-weight: bold;
      }
      .header,
      .sub-header {
        margin: 0;
      }
      .header {
        font-size: 24px;
      }
      .sub-header {
        font-size: 18px;
      }
      .line {
        border-top: 1px solid black;
        margin: 10px 0;
      }
      .table {
        width: 100%;
        margin-top: 10px;
        border-collapse: collapse;
      }
      .table td {
        padding: 5px 0;
      }
      .amount {
        display: block;
        margin-top: 5px;
      }
      .uppercase {
        text-transform: uppercase;
      }
      .underline {
        text-decoration: underline;
      }

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
    margin-bottom: 20px;
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
    text-align: left;
}


</style>
