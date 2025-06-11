<template>
  <div v-if="projectType == 'hms'" class="a4-size pt-8 relative mx-auto flex flex-col rounded-lg shadow-md">
        <div class="flex-initial">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center relative space-x-4 mt-2 mb-4 ml-24 w-full">
                    <img class="h-auto w-24 -mt-4" src="@/icons/logo.png" alt="" />
                    <div class="w-full">
                        <h1 class="doctor-info text-3xl font-bold mb-2">CHINIOT GENERAL HOSPITAL</h1>
                        <div class="flex items-center w-full space-x-3">
                            <div class="flex items-center relative w-full">
                                <div class="border border-pink-400 w-2/3 h-0.5 -mt-4"></div>
                                <span>
                                    <svg  class="w-12 h-8 mb-0.5 -mt-4" viewBox="0 0 138 55" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 31H43.382C43.7607 31 44.107 30.786 44.2764 30.4472L52.9466 13.1069C53.3412 12.3176 54.4903 12.3891 54.784 13.2213L68.1264 51.0247C68.4322 51.8912 69.6466 51.9212 69.9948 51.0709L89.4289 3.61548C89.7861 2.74322 91.0413 2.80429 91.3121 3.70711L99.2862 30.2873C99.4131 30.7103 99.8024 31 100.244 31H138" stroke="#2E266D" stroke-width="5"/>
                                        </svg>
                                </span>
                               <span class="absolute right-4 -top-10">
                                <svg class="w-28 h-auto" stroke="2" viewBox="0 0 334 312" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M333.439 185.5C333.439 209.524 313.963 229 289.939 229C265.915 229 246.439 209.524 246.439 185.5C246.439 161.476 265.915 142 289.939 142C313.963 142 333.439 161.476 333.439 185.5ZM254.155 185.5C254.155 205.263 270.176 221.284 289.939 221.284C309.702 221.284 325.723 205.263 325.723 185.5C325.723 165.737 309.702 149.716 289.939 149.716C270.176 149.716 254.155 165.737 254.155 185.5Z" fill="#2E266D"/>
                                    <path d="M305.439 184.5C305.439 193.613 298.052 201 288.939 201C279.826 201 272.439 193.613 272.439 184.5C272.439 175.387 279.826 168 288.939 168C298.052 168 305.439 175.387 305.439 184.5ZM280.275 184.5C280.275 189.285 284.154 193.164 288.939 193.164C293.724 193.164 297.603 189.285 297.603 184.5C297.603 179.715 293.724 175.836 288.939 175.836C284.154 175.836 280.275 179.715 280.275 184.5Z" fill="#2E266D"/>
                                    <path d="M160.439 13C160.439 20.1797 154.619 26 147.439 26C140.259 26 134.439 20.1797 134.439 13C134.439 5.8203 140.259 0 147.439 0C154.619 0 160.439 5.8203 160.439 13ZM140.613 13C140.613 16.77 143.669 19.8261 147.439 19.8261C151.209 19.8261 154.265 16.77 154.265 13C154.265 9.23004 151.209 6.17388 147.439 6.17388C143.669 6.17388 140.613 9.23004 140.613 13Z" fill="#2E266D"/>
                                    <path d="M78.439 13C78.439 20.1797 72.6187 26 65.439 26C58.2593 26 52.439 20.1797 52.439 13C52.439 5.8203 58.2593 0 65.439 0C72.6187 0 78.439 5.8203 78.439 13ZM58.6128 13C58.6128 16.77 61.669 19.8261 65.439 19.8261C69.2089 19.8261 72.2651 16.77 72.2651 13C72.2651 9.23004 69.2089 6.17388 65.439 6.17388C61.669 6.17388 58.6128 9.23004 58.6128 13Z" fill="#2E266D"/>
                                    <path d="M291.576 200.5C294.576 233.333 283.075 304 202.439 309C169.075 311.069 106.939 289.5 106.939 202V195M106.939 195C123.106 188.167 164.649 160 202.439 100.5C221.493 70.5 217.475 21.1 159.075 11.5M106.939 195C88.7726 184.167 55.5747 155.3 35.9393 136C14.5747 115 -5.16069 76.3 6.43931 47.5C10.9392 35.5 26.5391 11.5 52.9391 11.5" stroke="#2E266D" stroke-width="5"/>
                                    </svg>
                               </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between px_20">
                <h2 v-if="appointment.is_printed == '2'" class="bg-gray-200 text-gray-900 border border-gray-300 text-center text-md font-semibold w-24">Duplicate</h2>

                <div class="mr-4 text-center w-full -mt-4">
                    <h2 class="doctor-info text-2xl font-semibold text-center">{{ appointment?.doctor?.name }}</h2>
                    <!-- <p>Consultant General Medicine</p> -->
                    <h3 class="doctor-info">
                        <template v-for="(education, index) in appointment?.doctor?.education">
                        {{education.degree}} <span v-if="appointment.doctor.education.length - 1 != index">,</span>
                        </template>
                    </h3>
                </div>
            </div>
        </div>
        <div class="flex justify-between items-center gap-4 mt-6 px-20">
            <div class="flex items-end gap-2">
                <div><b>Sr #</b></div> <span class="border-b border-black w-10">{{ appointment.id }}</span>
            </div>
            <div class="flex items-end gap-2">
                <div><strong>Date:</strong></div> <span class="border-b border-black w-full">{{ formattedDate }} {{ formattedTime }}</span>
            </div>
            <div class="flex items-end justify-between gap-2">
                <div><b>Mr #</b></div> <span class="border-b border-black w-12 mr-1">{{ mr_number }}</span>
                <div><b>Slip #</b></div> <span class="border-b border-black w-10">{{ appointment.slip_no }}</span>
                <div><b>Serial #</b></div> <span class="border-b border-black w-12">{{ appointment.doc_serial }}</span>
            </div>
        </div>

        <div class="flex-auto pt-4 pb-12">
            <div class="px_20 flex items-end justify-between gap-2 pb-3 pt-2 border-t-2 border-gray-300">
               <p class="col-span-3 flex flex-auto"><strong>Name:</strong><span class="border-b border-black w-full">{{  appointment.patient_name }}</span></p>
                <p class="flex"><strong>Gender:</strong><span class="border-b border-black w-16">{{ appointment?.patient_gender  }}</span></p>
                <p class="flex"><strong>Age:</strong><span class="border-b border-black w-12">{{ appointment.patient_age }}</span></p>
            </div>
            <div class="px-6 prescription-area flex flex-grow h-[92%] border-t-2 border-gray-300">
                <div class="left">
                    <div class="flex flex-col h-full">
                        <ul class="flex-1 space-y-4 mt-8 font-semibold">
                            <li>BP</li>
                            <li>Temperature</li>
                            <li>Saturation</li>
                        </ul>
                        <div class="flex-end mb-2">
                        <div>
                            <div class="flex items-center justify-between px-2 py-1">
                                <div><b>FEES :</b></div>
                                <div>{{ withCommas(sumAppointmentFees ?? 0) }}</div>
                            </div>
                            <div class="flex items-center justify-between px-2 py-1" v-if="appointment?.zf_fee && appointment?.donor_fee">
                                <div><b>C/O/ZF :</b></div>
                                <div>{{withCommas(+appointment?.donor_fee ?? 0) }} / {{ withCommas(+appointment?.zf_fee ?? 0)}} </div>
                            </div>
                            <div class="flex items-center justify-between px-2 py-1" v-else-if="appointment?.zf_fee">
                                <div><b>ZF :</b></div>
                                <div>{{ withCommas(appointment?.zf_fee ?? 0) }}</div>
                            </div>
                            <div class="flex items-center justify-between px-2 py-1" v-else-if="appointment?.donor_fee">
                                <div><b>C/O :</b></div>
                                <div>{{ withCommas(appointment?.donor_fee ?? 0) }}</div>
                            </div>
                            <div class="flex items-center justify-between px-2 py-1 gap-2" v-if="appointment?.zf_fee && appointment?.donor_fee">
                                <div><b>NET :</b></div>
                                <div class="px-2 py-1 border rounded border-gray-500">{{ withCommas(+sumAppointmentFees - +(+appointment?.zf_fee + +appointment?.donor_fee ?? 0)) }}</div>
                            </div>
                            <div class="flex items-center justify-between px-2 py-1 gap-2" v-else-if="appointment?.zf_fee">
                                <div><b>NET :</b></div>
                                <div class="px-2 py-1 border rounded border-gray-500">{{ withCommas(+sumAppointmentFees - +(+appointment?.zf_fee ?? 0)) }}</div>
                            </div>
                            <div class="flex items-center justify-between px-2 py-1 gap-2" v-else-if="appointment?.donor_fee">
                                <div><b>NET :</b></div>
                                <div class="px-2 py-1 border rounded border-gray-500">{{ withCommas(+sumAppointmentFees - +(+appointment?.donor_fee ?? 0)) }}</div>
                            </div>
                        </div>
                    </div>

                    </div>
                </div>
                <div class="right">
                    <h1 class="text-2xl leading-tight tracking-tighter">R<sub>x</sub></h1>
                </div>
            </div>
            <div class="px-20 flex justify-between border-b-2 border-t-2 border-gray-300 p-2">
                <span class="text-xs">Recp: {{appointment?.creator?.name}}</span>
                <span class="font-semibold text-md" v-if="appointment?.careoff_id != '' && appointment?.careoff_id != null">C/O: {{appointment?.careoff_id != '' && appointment?.careoff_id != null ? appointment?.careoff?.name : ''}}</span>
                <span class="font-semibold text-md">Authority: {{appointment?.welfare}}</span>
            </div>
        </div>
        <div class="flex-initial">
            <div class="bg-indigo-800 text-white h-auto w-full grid grid-cols-3 gap-6 place-items-center py-4 px-4">
                <p class="text-sm" v-if="projectType === 'hms'"><span class="font-semibold">Address:</span> Nawaz Town Sargodha Road, Faisalabad</p>
                <p class="text-sm" v-else><span class="font-semibold">Address:</span> 209 Jinnah Colony, Faisalabad</p>
                <div class="text-xs text-center">
                    <div>Under Supervision of MOFAD-E-AMMA Chiniot Sheikh Association.Reg:fsd</div>
                </div>
                <div>
                    <p class="text-sm" v-if="projectType === 'hms'"><span class="font-semibold">Contact:</span> 041-8787231-8848060</p>
                    <p class="text-sm" v-else><span class="font-semibold">Contact:</span>041-2618764-2634890</p>
                    <!-- <p class="text-sm"><span class="font-semibold">Call/Appointment:</span> 041-8787231</p>
                    <p class="text-sm"><span class="font-semibold">Emergency:</span> 041-8787231-8848060</p> -->
                </div>
            </div>
        </div>
    </div>


    <div v-else class="a4-size pt-8 relative mx-auto flex flex-col rounded-lg shadow-md">
        <div class="flex-initial">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center relative space-x-4 mt-2 mb-4 ml-24 w-full">
                    <img class="h-auto w-24 -mt-4" src="@/icons/logo.png" alt="" />
                    <div class="w-full">
                        <h1 class="doctor-info text-3xl font-bold mb-2">CHINIOT GENERAL HOSPITAL</h1>
                        <div class="flex items-center w-full space-x-3">
                            <div class="flex items-center relative w-full">
                                <div class="border border-pink-400 w-2/3 h-0.5 -mt-4"></div>
                                <span>
                                    <svg  class="w-12 h-8 mb-0.5 -mt-4" viewBox="0 0 138 55" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 31H43.382C43.7607 31 44.107 30.786 44.2764 30.4472L52.9466 13.1069C53.3412 12.3176 54.4903 12.3891 54.784 13.2213L68.1264 51.0247C68.4322 51.8912 69.6466 51.9212 69.9948 51.0709L89.4289 3.61548C89.7861 2.74322 91.0413 2.80429 91.3121 3.70711L99.2862 30.2873C99.4131 30.7103 99.8024 31 100.244 31H138" stroke="#2E266D" stroke-width="5"/>
                                        </svg>
                                </span>
                               <span class="absolute right-4 -top-10">
                                <svg class="w-28 h-auto" stroke="2" viewBox="0 0 334 312" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M333.439 185.5C333.439 209.524 313.963 229 289.939 229C265.915 229 246.439 209.524 246.439 185.5C246.439 161.476 265.915 142 289.939 142C313.963 142 333.439 161.476 333.439 185.5ZM254.155 185.5C254.155 205.263 270.176 221.284 289.939 221.284C309.702 221.284 325.723 205.263 325.723 185.5C325.723 165.737 309.702 149.716 289.939 149.716C270.176 149.716 254.155 165.737 254.155 185.5Z" fill="#2E266D"/>
                                    <path d="M305.439 184.5C305.439 193.613 298.052 201 288.939 201C279.826 201 272.439 193.613 272.439 184.5C272.439 175.387 279.826 168 288.939 168C298.052 168 305.439 175.387 305.439 184.5ZM280.275 184.5C280.275 189.285 284.154 193.164 288.939 193.164C293.724 193.164 297.603 189.285 297.603 184.5C297.603 179.715 293.724 175.836 288.939 175.836C284.154 175.836 280.275 179.715 280.275 184.5Z" fill="#2E266D"/>
                                    <path d="M160.439 13C160.439 20.1797 154.619 26 147.439 26C140.259 26 134.439 20.1797 134.439 13C134.439 5.8203 140.259 0 147.439 0C154.619 0 160.439 5.8203 160.439 13ZM140.613 13C140.613 16.77 143.669 19.8261 147.439 19.8261C151.209 19.8261 154.265 16.77 154.265 13C154.265 9.23004 151.209 6.17388 147.439 6.17388C143.669 6.17388 140.613 9.23004 140.613 13Z" fill="#2E266D"/>
                                    <path d="M78.439 13C78.439 20.1797 72.6187 26 65.439 26C58.2593 26 52.439 20.1797 52.439 13C52.439 5.8203 58.2593 0 65.439 0C72.6187 0 78.439 5.8203 78.439 13ZM58.6128 13C58.6128 16.77 61.669 19.8261 65.439 19.8261C69.2089 19.8261 72.2651 16.77 72.2651 13C72.2651 9.23004 69.2089 6.17388 65.439 6.17388C61.669 6.17388 58.6128 9.23004 58.6128 13Z" fill="#2E266D"/>
                                    <path d="M291.576 200.5C294.576 233.333 283.075 304 202.439 309C169.075 311.069 106.939 289.5 106.939 202V195M106.939 195C123.106 188.167 164.649 160 202.439 100.5C221.493 70.5 217.475 21.1 159.075 11.5M106.939 195C88.7726 184.167 55.5747 155.3 35.9393 136C14.5747 115 -5.16069 76.3 6.43931 47.5C10.9392 35.5 26.5391 11.5 52.9391 11.5" stroke="#2E266D" stroke-width="5"/>
                                    </svg>
                               </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between px_20">
                <h2 v-if="appointment.is_printed == '2'" class="bg-gray-200 text-gray-900 border border-gray-300 text-center text-md font-semibold w-24">Duplicate</h2>

                <div class="mr-4 text-center w-full -mt-4">
                    <h2 class="doctor-info text-2xl font-semibold text-center">{{ appointment?.doctor?.name }}</h2>
                    <!-- <p>Consultant General Medicine</p> -->
                    <h3 class="doctor-info">
                        <template v-for="(education, index) in appointment?.doctor?.education">
                        {{education.degree}} <span v-if="appointment.doctor.education.length - 1 != index">,</span>
                        </template>
                    </h3>
                </div>
            </div>
        </div>
        <div class="flex justify-between items-center gap-4 mt-6 px-20">
            <div class="flex items-end gap-2">
                <div><b>Sr #</b></div> <span class="border-b border-black w-10">{{ appointment.id }}</span>
            </div>
            <div class="flex items-end gap-2">
                <div><strong>Date:</strong></div> <span class="border-b border-black w-full">{{ formattedDate }} {{ formattedTime }}</span>
            </div>
            <div class="flex items-end justify-between gap-2">
                <div><b>Mr #</b></div> <span class="border-b border-black w-12 mr-1">{{ mr_number }}</span>
                <div><b>Slip #</b></div> <span class="border-b border-black w-10">{{ appointment.slip_no }}</span>
                <div><b>Serial #</b></div> <span class="border-b border-black w-12">{{ appointment.doc_serial }}</span>
            </div>
        </div>

        <div class="flex-auto pt-4 pb-4">
            <div class="px_20 flex items-end justify-between gap-2 pb-3 pt-2 border-t-2 border-gray-300">
               <p class="col-span-3 flex flex-auto"><strong>Name:</strong><span class="border-b border-black w-full">{{  appointment.patient_name }}</span></p>
                <p class="flex"><strong>Gender:</strong><span class="border-b border-black w-16">{{ appointment?.patient_gender  }}</span></p>
                <p class="flex"><strong>Age:</strong><span class="border-b border-black w-12">{{ appointment.patient_age }}</span></p>
            </div>
            <div class="px-6 prescription-area flex flex-grow h-[10%] border-t-2 border-gray-300">
                <div class="left">
                    <div class="flex flex-col ">
                        <!-- <ul class="flex-1  font-semibold">
                            <li>BP</li>
                            <li>Temperature</li>
                            <li>Saturation</li>
                        </ul> -->
                        <div class="flex-end mb-2">
                        <div>
                            <div class="flex items-center justify-between px-2 py-1">
                                <div><b>FEES :</b></div>
                                <div>{{ withCommas(sumAppointmentFees ?? 0) }}</div>
                            </div>
                            <div class="flex items-center justify-between px-2 py-1" v-if="appointment?.zf_fee && appointment?.donor_fee">
                                <div><b>C/O/ZF :</b></div>
                                <div>{{withCommas(+appointment?.donor_fee ?? 0) }} / {{ withCommas(+appointment?.zf_fee ?? 0)}} </div>
                            </div>
                            <div class="flex items-center justify-between px-2 py-1" v-else-if="appointment?.zf_fee">
                                <div><b>ZF :</b></div>
                                <div>{{ withCommas(appointment?.zf_fee ?? 0) }}</div>
                            </div>
                            <div class="flex items-center justify-between px-2 py-1" v-else-if="appointment?.donor_fee">
                                <div><b>C/O :</b></div>
                                <div>{{ withCommas(appointment?.donor_fee ?? 0) }}</div>
                            </div>
                            <div class="flex items-center justify-between px-2 py-1 gap-2" v-if="appointment?.zf_fee && appointment?.donor_fee">
                                <div><b>NET :</b></div>
                                <div class="px-2 py-1 border rounded border-gray-500">{{ withCommas(+sumAppointmentFees - +(+appointment?.zf_fee + +appointment?.donor_fee ?? 0)) }}</div>
                            </div>
                            <div class="flex items-center justify-between px-2 py-1 gap-2" v-else-if="appointment?.zf_fee">
                                <div><b>NET :</b></div>
                                <div class="px-2 py-1 border rounded border-gray-500">{{ withCommas(+sumAppointmentFees - +(+appointment?.zf_fee ?? 0)) }}</div>
                            </div>
                            <div class="flex items-center justify-between px-2 py-1 gap-2" v-else-if="appointment?.donor_fee">
                                <div><b>NET :</b></div>
                                <div class="px-2 py-1 border rounded border-gray-500">{{ withCommas(+sumAppointmentFees - +(+appointment?.donor_fee ?? 0)) }}</div>
                            </div>
                        </div>
                    </div>

                    </div>
                </div>
                <div class="right">
                    <h1 class="text-2xl leading-tight tracking-tighter">R<sub>x</sub></h1>
                </div>
            </div>
            <div class="px-20 flex justify-between border-b-2 border-t-2 border-gray-300 p-2">
                <span class="text-xs">Recp: {{appointment?.creator?.name}}</span>
                <span class="font-semibold text-md" v-if="appointment?.careoff_id != '' && appointment?.careoff_id != null">C/O: {{appointment?.careoff_id != '' && appointment?.careoff_id != null ? appointment?.careoff?.name : ''}}</span>
                <span class="font-semibold text-md">Authority: {{appointment?.welfare}}</span>
            </div>
            <div class="">
                <div class="bg-gray-100 text-gray-900  w-full grid grid-cols-3 gap-6 place-items-center py-4 px-4">
                    <p class="text-sm" v-if="projectType === 'hms'"><span class="font-semibold">Address:</span> Nawaz Town Sargodha Road, Faisalabad</p>
                    <p class="text-sm" v-else><span class="font-semibold">Address:</span> 209 Jinnah Colony, Faisalabad</p>
                    <div class="text-xs text-center">
                        <div>Under Supervision of MOFAD-E-AMMA Chiniot Sheikh Association.Reg:fsd</div>
                    </div>
                    <div>
                        <p class="text-sm" v-if="projectType === 'hms'"><span class="font-semibold">Contact:</span> 041-8787231-8848060</p>
                        <p class="text-sm" v-else><span class="font-semibold">Contact:</span>041-2618764-2634890</p>
                        <!-- <p class="text-sm"><span class="font-semibold">Call/Appointment:</span> 041-8787231</p>
                        <p class="text-sm"><span class="font-semibold">Emergency:</span> 041-8787231-8848060</p> -->
                    </div>
                </div>
            </div>
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
  methods:{
   preventMouseClick(event) {
      event.preventDefault();
      event.stopPropagation();
    },
   reloadPage() {
      window.location.reload();
    },
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
  data() {
    return {
      projectType: import.meta.env.VITE_PROJECT_TYPE,
    };
  },
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
body {
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
        .p_20{
            padding: 20mm;
        }
        .px_20{
            padding-left: 20mm;
            padding-right: 20mm;
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
