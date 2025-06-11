<template>
  <div v-if="projectType == 'hms'" class="a4-size relative mx-auto flex flex-col rounded-lg shadow-md">
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
        <h2 v-if="appointment.is_printed == '2'" class="bg-gray-200 text-gray-900 text-center ml-4 border border-gray-300 bord text-md font-semibold w-24">Duplicate</h2>

        <div class="flex justify-between items-center gap-4 mt-2 px-20">
            <div class="flex items-end gap-2">
                          <!-- <h2 v-if="appointment.is_printed == '2'" class="bg-gray-200 text-gray-900 border border-gray-300 text-center text-md font-semibold">Duplicate</h2> -->

                <div><b>Sr #</b></div> <span class="border-b border-black w-10">{{ appointment.id }}</span>
            </div>
            <div class="flex items-end gap-2">
                <div><strong>Date:</strong></div> <span class="border-b border-black w-full">{{ formattedDate }} {{ formattedTime }}</span>
            </div>
            <div class="flex items-end justify-between gap-2">
                <div><b>Mr #</b></div> <span class="border-b border-black w-12">{{ mr_number }}</span> &nbsp;
                <div><b>Slip #</b></div> <span class="border-b border-black w-10">{{ appointment.slip_no }}</span>
                <div><b>Serial #</b></div> <span class="border-b border-black w-12">{{ appointment.doc_serial }}</span>
            </div>
        </div>
        <div class="flex-auto px_20 pb-6 pt-3">
            <div class="flex items-end justify-between gap-2 pb-3 pt-2 border-t-2 border-gray-300">
                <p class="flex flex-auto"><strong>Name:</strong><span class="border-b border-black w-full">{{  appointment?.patient_name }}</span></p>
                <p class=" flex"><strong>Gender:</strong><span class="border-b border-black w-16">{{ appointment?.patient_gender?.[0] }}</span></p>
                <p class="flex"><strong>Age:</strong><span class="border-b border-black w-12">{{ appointment.patient_age }}</span></p>
            </div>
            <div class="prescription-area flex flex-grow h-[90%] border-t-2 border-gray-300">
                <div class="left"></div>
                <div class="right">
                    <h1 class="text-2xl leading-tight tracking-tighter">R<sub>x</sub></h1>
                </div>
            </div>
            <div class="my-4 flex justify-between border-b-2 border-t border-gray-300 p-2">
                <span class=" text-xs">Recp: {{appointment?.creator?.name}}</span>
                <span class="font-bold" v-if="appointment?.careoff_id != '' && appointment?.careoff_id != null">C/O: {{appointment?.careoff_id != '' && appointment?.careoff_id != null ? appointment?.careoff?.name : ''}}</span>
                <span class="font-bold">Authority: {{appointment?.welfare}}</span>
            </div>
        </div>
        <div class="flex-initial mt-5">
            <div class="flex items-center justify-between px_20">
                <div>
                    <h2 class="doctor-info text-xl font-semibold">{{ appointment?.doctor?.name }}</h2>
                    <!-- <p>Consultant General Medicine</p> -->
                    <h3 class="doctor-info">
                        <template v-for="(education, index) in appointment?.doctor?.education">
                            {{education.degree}} <span v-if="appointment.doctor.education.length - 1 != index">,</span>
                        </template>
                    </h3>
                </div>
                <div class="max-w-xs mx-auto">
                    <p class="text-xs text-center">(Under Supervision of <span class=" font-semibold"> MOFAD-E-AMMA </span>Chiniot Sheikh Association.Reg:fsd)</p>
                </div>
                <div class="space-y-4 mr-8 flex justify-end text-right mt-5">
                   <div class="space-y-4">
                        <div><span class="font-bold">Fees:</span> {{ withCommas(sumAppointmentFees || 0) }}</div>
                        <div v-if="appointment?.zf_fee && appointment?.donor_fee"><span class="font-bold">C/O/ZF:</span> {{withCommas(+appointment?.donor_fee || 0) }} / {{ withCommas(+appointment?.zf_fee || 0) }} </div>
                        <div v-else-if="appointment?.zf_fee"><span class="font-bold">ZF:</span> {{ withCommas(appointment?.zf_fee || 0) }}</div>
                        <div v-else-if="appointment?.donor_fee"><span class="font-bold">C/O:</span> {{ withCommas(appointment?.donor_fee || 0) }}</div>


                        <div v-if="appointment?.zf_fee && appointment?.donor_fee"><span class="font-bold">Net:</span> {{ withCommas(+sumAppointmentFees - +(+appointment?.zf_fee + +appointment?.donor_fee || 0)) }}</div>
                        <div v-else-if="appointment?.zf_fee"><span class="font-bold">Net:</span> {{ withCommas(+sumAppointmentFees - +(+appointment?.zf_fee || 0)) }}</div>
                        <div v-else-if="appointment?.donor_fee"><span class="font-bold">Net:</span> {{ withCommas(+sumAppointmentFees - +(+appointment?.donor_fee || 0)) }}</div>
                   </div>
                </div>
            </div>
            <div class="relative rotate-180 pb-4">
                <span class="bg-[#8B322C] block h-8 w-full"></span>
                <span class="bg-[#FFC470] block h-8 w-11/12"></span>
                <div class="clip h-20 w-32 bg-[#8B322C] absolute top-0">
                    <span class="clip block h-18 w-24 bg-[#FFC470] absolute top-2"></span>
                </div>
            </div>
        </div>
    </div>
    <div v-else class="a4-size relative mx-auto flex flex-col rounded-lg shadow-md">
        <div class="flex-initial">
            <!-- <div class="relative">
                <span class="bg-[#8B322C] block h-16 w-full"></span>
                <span class="bg-[#FFC470] block h-8 w-11/12"></span>
                <div class="clip h-32 w-32 bg-[#8B322C] absolute top-0">
                    <span class="clip block h-24 w-24 bg-[#FFC470] absolute top-4"></span>
                </div>
            </div> -->
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
        <h2 v-if="appointment.is_printed == '2'" class="bg-gray-200 text-gray-900 text-center ml-4 border border-gray-300 bord text-md font-semibold w-24">Duplicate</h2>

        <div class="flex justify-between items-center gap-4 mt-2 px-20">
            <div class="flex items-end gap-2">
                          <!-- <h2 v-if="appointment.is_printed == '2'" class="bg-gray-200 text-gray-900 border border-gray-300 text-center text-md font-semibold">Duplicate</h2> -->

                <div><b>Sr #</b></div> <span class="border-b border-black w-10">{{ appointment.id }}</span>
            </div>
            <div class="flex items-end gap-2">
                <div><strong>Date:</strong></div> <span class="border-b border-black w-full">{{ formattedDate }} {{ formattedTime }}</span>
            </div>
            <div class="flex items-end justify-between gap-2">
                <div><b>Mr #</b></div> <span class="border-b border-black w-12">{{ mr_number }}</span> &nbsp;
                <div><b>Slip #</b></div> <span class="border-b border-black w-10">{{ appointment.slip_no }}</span>
                <div><b>Serial #</b></div> <span class="border-b border-black w-12">{{ appointment.doc_serial }}</span>
            </div>
        </div>
        <div class="flex-auto  pb-4 pt-3">
            <div class="flex items-end justify-between gap-2 pb-3 pt-2 border-t-2 border-gray-300">
                <p class="flex flex-auto"><strong>Name:</strong><span class="border-b border-black w-full">{{  appointment?.patient_name }}</span></p>
                <p class=" flex"><strong>Gender:</strong><span class="border-b border-black w-16">{{ appointment?.patient_gender?.[0] }}</span></p>
                <p class="flex"><strong>Age:</strong><span class="border-b border-black w-12">{{ appointment.patient_age }}</span></p>
            </div>
            <div class="prescription-area flex flex-grow h-[10%] border-t-2 border-gray-300">
                <div class="left"></div>
                <div class="right">
                    <h1 class="text-2xl leading-tight tracking-tighter">R<sub>x</sub></h1>
                </div>
            </div>
            <div class="my-4 flex justify-between border-b-2 border-t border-gray-300 p-2">
                <span class=" text-xs">Recp: {{appointment?.creator?.name}}</span>
                <span class="font-bold" v-if="appointment?.careoff_id != '' && appointment?.careoff_id != null">C/O: {{appointment?.careoff_id != '' && appointment?.careoff_id != null ? appointment?.careoff?.name : ''}}</span>
                <span class="font-bold">Authority: {{appointment?.welfare}}</span>
            </div>
            <div class="mt-3">
                <div class="flex items-center justify-between px_20">
                    <div>
                        <h2 class="doctor-info text-xl font-semibold whitespace-nowrap">{{ appointment?.doctor?.name }}</h2>
                        <!-- <p>Consultant General Medicine</p> -->
                        <h3 class="doctor-info">
                            <template v-for="(education, index) in appointment?.doctor?.education">
                                {{education.degree}} <span v-if="appointment.doctor.education.length - 1 != index">,</span>
                            </template>
                        </h3>
                    </div>
                    <div class="max-w-xs mx-auto">
                        <p class="text-xs text-center">(Under Supervision of <span class=" font-semibold"> MOFAD-E-AMMA </span>Chiniot Sheikh Association.Reg:fsd)</p>
                    </div>
                    <div class="space-y-4 mr-8 flex justify-end text-right ">
                       <div class="space-y-4">
                            <div><span class="font-bold whitespace-nowrap">Fees:</span> {{ withCommas(sumAppointmentFees || 0) }}</div>
                            <div v-if="appointment?.zf_fee && appointment?.donor_fee" class="whitespace-nowrap"><span class="font-bold whitespace-nowrap">C/O/ZF:</span> {{withCommas(+appointment?.donor_fee || 0) }} / {{ withCommas(+appointment?.zf_fee || 0) }} </div>
                            <div v-else-if="appointment?.zf_fee" class="whitespace-nowrap"><span class="font-bold whitespace-nowrap">ZF:</span> {{ withCommas(appointment?.zf_fee || 0) }}</div>
                            <div v-else-if="appointment?.donor_fee" class="whitespace-nowrap"><span class="font-bold whitespace-nowrap">C/O:</span> {{ withCommas(appointment?.donor_fee || 0) }}</div>
                            <div v-if="appointment?.zf_fee && appointment?.donor_fee" class="whitespace-nowrap"><span class="font-bold">Net:</span> {{ withCommas(+sumAppointmentFees - +(+appointment?.zf_fee + +appointment?.donor_fee || 0)) }}</div>
                            <div v-else-if="appointment?.zf_fee" class="whitespace-nowrap"><span class="font-bold">Net:</span> {{ withCommas(+sumAppointmentFees - +(+appointment?.zf_fee || 0)) }}</div>
                            <div v-else-if="appointment?.donor_fee" class="whitespace-nowrap"><span class="font-bold">Net:</span> {{ withCommas(+sumAppointmentFees - +(+appointment?.donor_fee || 0)) }}</div>
                       </div>
                    </div>
                </div>
            </div>
            <!-- <div class="w-full rotate-180 pb-4">
            <span class="bg-[#8B322C] block h-8 w-full"></span>
            <span class="bg-[#FFC470] block h-8 w-11/12"></span>
            <div class="clip h-20 w-32 bg-[#8B322C] absolute top-0">
                <span class="clip block h-18 w-24 bg-[#FFC470] absolute top-2"></span>
            </div>
        </div> -->
        </div>

    </div>
</template>

<script>
export default {
  props: {
    appointment: Object,
    user: Object,
    print_date_time: String,
    mr_number: String,
  },
  data() {
    return {
      projectType: import.meta.env.VITE_PROJECT_TYPE,
    };
  },
  methods:{
    handleKeydown(event) {
      if (event.ctrlKey && event.key === 'p') {
        event.preventDefault();
        window.location.reload();
      }
    },
    withCommas(value) {
        console.log('value', value)
      if(value == null || value == ''){
        return '0';
      }
        return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    },
  },
  mounted() {
    setTimeout(() => {
        window.print();
        setTimeout(() => {
            window.close();
        }, 500);
    }, 500);
    // Automatically trigger the print dialog when the page/component is loaded
    // setTimeout(() => {
    //   window.print();
    //   window.close();
    // }, 500);
//   setTimeout(() => {
//         window.close();
//       }, 700);
  },
//   beforeDestroy() {
//     setTimeout(() => {
//     //   window.print();
//       setTimeout(() => {
//         window.close();
//       }, 500);
//     }, 500);
//   },
  computed: {
    formattedDate() {
    const date = new Date(this.appointment.appointment_date);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();

    return `${day}-${month}-${year}`;
},

    formattedTime() {
      const date = new Date(
        this.appointment.appointment_date +
          " " +
          this.appointment.appointment_time
      );
      return date.toLocaleTimeString("en-GB", {
        hour: "2-digit",
        minute: "2-digit",
      });
    },
    sumAppointmentFees() {
      let sum = 0;
      if (
        this.appointment.appointment_details &&
        this.appointment.appointment_details.length > 0
      ) {
        sum = this.appointment.appointment_details.reduce(
          (acc, detail) => acc + parseFloat(detail.fee),
          0
        );
      }
      return sum;
    },
    patientAge() {
      let dateString = this.appointment.patient_dob;
      if (dateString) {
        // Parse the input date string to create a Date object
        const [day, month, year] = dateString.split("-");
        const birthDate = new Date(year, month - 1, day);

        // Get today's date
        const today = new Date();

        // Calculate the age
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        const dayDiff = today.getDate() - birthDate.getDate();

        // Adjust age if the current date is before the birth date in the current year
        if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
          age--;
        }

        return age;
      }
      return "0";
    },
  },
};
</script>

<style scoped>
body{
pointer-events: none;
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
    </style>
