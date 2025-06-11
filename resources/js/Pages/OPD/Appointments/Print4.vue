<template>
  <div v-if="projectType == 'hms'" class="a4-size relative mx-auto flex flex-col rounded-lg shadow-md">
        <div class="flex-initial relative">
            <svg class="w-full h-auto" viewBox="0 0 484 131" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M144 120C45.1455 136.571 27.2375 129.012 0.918468 126.582C0.401956 126.535 0 126.102 0 125.583V2C0 1.44772 0.447729 1 1.00001 1H482.5C483.052 1 483.5 1.44772 483.5 2V75.3411C483.5 75.9517 482.952 76.4188 482.348 76.3313C411.839 66.1136 351.194 76.5405 319 84C196 112.5 184.32 113.241 144 120Z" fill="#A6B347"/>
                <path d="M312 76C407.605 52.1571 440.392 55.4362 482.32 62.7924C482.933 62.8999 483.5 62.4299 483.5 61.8081V1.5C483.5 0.947715 483.052 0.5 482.5 0.5H37C36.4477 0.5 36 0.947716 36 1.5V122.054C36 122.585 36.3777 123.022 36.9077 123.047C94.7778 125.852 244.191 92.9108 312 76Z" fill="#78955B"/>
                <path d="M253.5 80.5005C383.5 38.7789 443 36 484 46.0005V0H0.5V122.5L20.5 123.5H42C86.5 123.5 198.5 98.1519 253.5 80.5005Z" fill="url(#paint0_linear_342_3402)"/>
                <defs>
                <linearGradient id="paint0_linear_342_3402" x1="251" y1="29.5" x2="169" y2="169" gradientUnits="userSpaceOnUse">
                <stop stop-color="#f3f4f6"/>
                <stop offset="1" stop-color="#f3f4f6"/>
                </linearGradient>
                </defs>
            </svg>
            <div class="absolute top-0 w-full flex items-start justify-end space-x-8 py-2 px-12">
                <!-- <div class="flex justify-end py-4"> -->
                    <img class="h-auto w-24" src="@/icons/logo.png" alt="" />
                    <h1 class="doctor-info text-4xl tracking-wider mt-3 font-bold text-black mb-2">CHINIOT GENERAL HOSPITAL </h1>
                <!-- </div> -->
            </div>
            <!-- <div class="flex items-center justify-between w-full">
                <div class="flex items-center relative space-x-4 mt-2 mb-4 ml-24 w-full">
                    <div class="w-full">
                        <div class="flex items-center w-full space-x-3">
                            <p class="text-sm font-medium whitespace-nowrap">Your Health is our priority</p>
                            <div class="flex items-center relative w-full">
                                <span class="bg-pink-400 w-2/3 h-0.5"></span>
                                <span>
                                    <svg  class="w-12 h-8 mb-0.5" viewBox="0 0 138 55" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 31H43.382C43.7607 31 44.107 30.786 44.2764 30.4472L52.9466 13.1069C53.3412 12.3176 54.4903 12.3891 54.784 13.2213L68.1264 51.0247C68.4322 51.8912 69.6466 51.9212 69.9948 51.0709L89.4289 3.61548C89.7861 2.74322 91.0413 2.80429 91.3121 3.70711L99.2862 30.2873C99.4131 30.7103 99.8024 31 100.244 31H138" stroke="#2E266D" stroke-width="5"/>
                                        </svg>
                                </span>
                               <span class="absolute right-4 -top-4">
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
            </div> -->
        </div>
        <div class="flex-auto  pt-4 pb-16">
                    <div class="px_20 flex justify-between items-center gap-4 mb-8">
                      <div class="flex items-end gap-2">
                        <h2 v-if="appointment.is_printed == '2'" class="bg-gray-200 text-gray-900 border  border-gray-300 text-center text-md font-semibold w-24">Duplicate</h2>
                          <div><b>Sr #</b></div> <span class="border-b border-black w-10">{{ appointment.id }}</span>
                      </div>
                      <div class="flex items-end justify-between gap-2">
                          <div><b>Mr #</b></div> <span class="border-b border-black w-10">{{ mr_number }}</span>
                          <div><b>Slip #</b></div> <span class="border-b border-black w-10">{{ appointment.slip_no }}</span>
                          <div><b>Serial #</b></div> <span class="border-b border-black w-10">{{ appointment.doc_serial }}</span>
                      </div>
                  </div>

            <div class="prescription-area flex flex-grow h-[89%] ">
                <div class="left px-5 flex flex-col h-full w-[30%]">
                  <div class="space-y-5 mb-32 mr-4 flex-auto">
                    <div>
                        <h2 class="doctor-info text-xl whitespace-nowrap font-semibold">{{ appointment?.doctor?.name }}</h2>
                        <h3 class="doctor-info">
                          <template v-for="(education, index) in appointment?.doctor?.education">
                            {{education.degree}}
                            <span v-if="appointment.doctor.education.length - 1 != index">,</span>
                          </template>
                        </h3>
                        <!-- <p>Consultant General Medicine</p> -->
                    </div>

                        <div>
                          <ul class="flex-1 space-y-4 mt-8 font-semibold">
                              <li>BP</li>
                              <li>Temperature</li>
                              <li>Spo2</li>
                              <li>RBS</li>
                          </ul>
                        </div>
                  </div>
                  <div class="flex-end mb-4">
                    <table class="w-full">
                        <tr>
                          <td><b>Fees :</b></td>
                          <td>{{ withCommas(sumAppointmentFees ?? 0) }}</td>
                        </tr>
                        <tr v-if="appointment?.zf_fee && appointment?.donor_fee">
                          <td><b>Less C/O/ZF :</b></td>
                          <td>{{withCommas(+appointment?.donor_fee ?? 0) }} / {{ withCommas(+appointment?.zf_fee ?? 0 )}} </td>
                        </tr>
                        <tr v-else-if="appointment?.zf_fee">
                          <td><b>Less ZF :</b></td>
                          <td>{{ withCommas(appointment?.zf_fee ?? 0) }}</td>
                        </tr>
                        <tr v-else-if="appointment?.donor_fee">
                          <td><b>Less C/O :</b></td>
                          <td>{{ withCommas(appointment?.donor_fee ?? 0) }}</td>
                        </tr>
                        <tr v-if="appointment?.zf_fee">
                          <td><b>Net :</b></td>
                          <td> {{ withCommas(+sumAppointmentFees - +(+appointment?.zf_fee + +appointment?.donor_fee ?? 0)) }}</td>
                        </tr>
                        <tr v-else-if="appointment?.zf_fee">
                          <td><b>Net :</b></td>
                          <td> {{ withCommas(+sumAppointmentFees - +(+appointment?.zf_fee ?? 0)) }}</td>
                        </tr>
                        <tr v-else-if="appointment?.donor_fee">
                          <td><b>Net :</b></td>
                          <td> {{ withCommas(+sumAppointmentFees - +(+appointment?.donor_fee ?? 0)) }}</td>
                        </tr>
                    </table>
                  </div>
                </div>
                <div class="right px-5">
                  <div>
                    <div class="flex justify-between gap-4 -mt-1">
                      <p class="flex w-full"><span class="font-semibold">Name:</span><span class="border-b border-dashed border-black w-full">{{  appointment.patient_name }}</span></p>
                      <p class="flex"><span class="font-semibold">Age:</span><span class="border-b border-dashed border-black w-full">{{ appointment.patient_age  }}</span></p>
                    </div>
                  </div>
                    <div class="flex justify-between gap-4 mt-1">
                        <p class="flex"><span class="font-semibold">Date: </span><span class="border-b border-dashed border-black w-full whitespace-nowrap"> {{ formattedDate }}</span></p>
                        <p class="flex"><span class="font-semibold">Time: </span><span class="border-b border-dashed border-black w-full whitespace-nowrap"> {{ formattedTime }}</span></p>
                        <p class="flex"><span class="font-semibold">Gender:</span><span class="border-b border-dashed border-black w-full">{{ appointment?.patient_gender }}</span></p>
                    </div>

                    <h1 class="text-5xl text-[#A6B556] yellowtail-regular leading-tight tracking-tighter">R<sub>x</sub></h1>
                </div>
            </div>
            <div class="flex justify-between border-b-2 border-t-2 border-gray-300 p-2">
                <span class=" text-xs">Recp: {{appointment?.creator?.name}}</span>
                <span class="font-semibold text-md" v-if="appointment?.careoff_id != '' && appointment?.careoff_id != null">C/O: {{appointment?.careoff_id != '' && appointment?.careoff_id != null ? appointment?.careoff?.name : ''}}</span>
                <span class="font-semibold text-md">Authority: {{appointment?.welfare}}</span>
            </div>
            <div class="text-xs text-center mt-1">
              <div v-if="projectType === 'hms'">Nawaz Town Sargodha Road, Faisalabad Ph: 042-8848060-8787231</div>
              <div v-else>209 Jinnah Colony, Faisalabad Ph: 041-2618764-2634890</div>
              <div>( Under Supervision of MOFAD-E-AMMA Chiniot Sheikh Association.Reg:fsd )</div>
          </div>
        </div>

    </div>
  <div v-else class="a4-size relative mx-auto flex flex-col rounded-lg shadow-md">
        <div class="flex-initial relative">
            <!-- <svg class="w-full h-auto" viewBox="0 0 484 131" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M144 120C45.1455 136.571 27.2375 129.012 0.918468 126.582C0.401956 126.535 0 126.102 0 125.583V2C0 1.44772 0.447729 1 1.00001 1H482.5C483.052 1 483.5 1.44772 483.5 2V75.3411C483.5 75.9517 482.952 76.4188 482.348 76.3313C411.839 66.1136 351.194 76.5405 319 84C196 112.5 184.32 113.241 144 120Z" fill="#A6B347"/>
                <path d="M312 76C407.605 52.1571 440.392 55.4362 482.32 62.7924C482.933 62.8999 483.5 62.4299 483.5 61.8081V1.5C483.5 0.947715 483.052 0.5 482.5 0.5H37C36.4477 0.5 36 0.947716 36 1.5V122.054C36 122.585 36.3777 123.022 36.9077 123.047C94.7778 125.852 244.191 92.9108 312 76Z" fill="#78955B"/>
                <path d="M253.5 80.5005C383.5 38.7789 443 36 484 46.0005V0H0.5V122.5L20.5 123.5H42C86.5 123.5 198.5 98.1519 253.5 80.5005Z" fill="url(#paint0_linear_342_3402)"/>
                <defs>
                <linearGradient id="paint0_linear_342_3402" x1="251" y1="29.5" x2="169" y2="169" gradientUnits="userSpaceOnUse">
                <stop stop-color="#f3f4f6"/>
                <stop offset="1" stop-color="#f3f4f6"/>
                </linearGradient>
                </defs>
            </svg> -->
            <div class="absolute top-0 w-full flex items-start justify-end space-x-8 py-2 px-12">
                <!-- <div class="flex justify-end py-4"> -->
                    <img class="h-auto w-24" src="@/icons/logo.png" alt="" />
                    <h1 class="doctor-info text-4xl tracking-wider mt-3 font-bold text-black mb-2">CHINIOT GENERAL HOSPITAL </h1>
                <!-- </div> -->
            </div>
            <!-- <div class="flex items-center justify-between w-full">
                <div class="flex items-center relative space-x-4 mt-2 mb-4 ml-24 w-full">
                    <div class="w-full">
                        <div class="flex items-center w-full space-x-3">
                            <p class="text-sm font-medium whitespace-nowrap">Your Health is our priority</p>
                            <div class="flex items-center relative w-full">
                                <span class="bg-pink-400 w-2/3 h-0.5"></span>
                                <span>
                                    <svg  class="w-12 h-8 mb-0.5" viewBox="0 0 138 55" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M0 31H43.382C43.7607 31 44.107 30.786 44.2764 30.4472L52.9466 13.1069C53.3412 12.3176 54.4903 12.3891 54.784 13.2213L68.1264 51.0247C68.4322 51.8912 69.6466 51.9212 69.9948 51.0709L89.4289 3.61548C89.7861 2.74322 91.0413 2.80429 91.3121 3.70711L99.2862 30.2873C99.4131 30.7103 99.8024 31 100.244 31H138" stroke="#2E266D" stroke-width="5"/>
                                        </svg>
                                </span>
                               <span class="absolute right-4 -top-4">
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
            </div> -->
        </div>

        <div class="flex-auto  pt-14 pb-1">
                    <!-- <div class="px_20 flex justify-between items-center gap-4 mb-8"> -->
                      <div class="flex items-center  justify-center gap-4 mt-2 mb-2 ml-12" >
                          <div class="border-b border-black "><b>Sr #</b> {{ appointment.id }}</div>
                          <div class="border-b border-black "><b>Mr #</b> {{ mr_number }}</div>
                          <div class="border-b border-black "><b>Slip #</b> {{ appointment.slip_no }}</div>
                          <div class="border-b border-black "><b>Serial #</b> {{ appointment.doc_serial }}</div>
                      </div>


                  <!-- </div> -->

            <div class="prescription-area flex flex-grow h-[30%] ">
                <div class="left px-5 flex flex-col mt-14 w-[30%]">
                  <div class="space-y-1 mr-4 flex-auto">
                    <div>
                      <h2 v-if="appointment.is_printed == '2'" class="bg-gray-200 text-gray-900 border  border-gray-300 text-center text-md font-semibold w-24">Duplicate</h2>

                        <h2 class="doctor-info text-xl whitespace-nowrap font-semibold">{{ appointment?.doctor?.name }}</h2>
                        <h3 class="doctor-info">
                          <template v-for="(education, index) in appointment?.doctor?.education">
                            {{education.degree}}
                            <span v-if="appointment.doctor.education.length - 1 != index">,</span>
                          </template>
                        </h3>
                        <!-- <p>Consultant General Medicine</p> -->
                    </div>

                        <!-- <div>
                          <ul class="flex-1 space-y-4 mt-8 font-semibold">
                              <li>BP</li>
                              <li>Temperature</li>
                              <li>Spo2</li>
                              <li>RBS</li>
                          </ul>
                        </div> -->
                  </div>
                  <div class="flex-end mb-4">
                    <table class="w-full">
                        <tr>
                          <td><b>Fees :</b></td>
                          <td>{{ withCommas(sumAppointmentFees ?? 0) }}</td>
                        </tr>
                        <tr v-if="appointment?.zf_fee && appointment?.donor_fee">
                          <td><b>Less C/O/ZF :</b></td>
                          <td>{{withCommas(+appointment?.donor_fee ?? 0) }} / {{ withCommas(+appointment?.zf_fee ?? 0 )}} </td>
                        </tr>
                        <tr v-else-if="appointment?.zf_fee">
                          <td><b>Less ZF :</b></td>
                          <td>{{ withCommas(appointment?.zf_fee ?? 0) }}</td>
                        </tr>
                        <tr v-else-if="appointment?.donor_fee">
                          <td><b>Less C/O :</b></td>
                          <td>{{ withCommas(appointment?.donor_fee ?? 0) }}</td>
                        </tr>
                        <tr v-if="appointment?.zf_fee">
                          <td><b>Net :</b></td>
                          <td> {{ withCommas(+sumAppointmentFees - +(+appointment?.zf_fee + +appointment?.donor_fee ?? 0)) }}</td>
                        </tr>
                        <tr v-else-if="appointment?.zf_fee">
                          <td><b>Net :</b></td>
                          <td> {{ withCommas(+sumAppointmentFees - +(+appointment?.zf_fee ?? 0)) }}</td>
                        </tr>
                        <tr v-else-if="appointment?.donor_fee">
                          <td><b>Net :</b></td>
                          <td> {{ withCommas(+sumAppointmentFees - +(+appointment?.donor_fee ?? 0)) }}</td>
                        </tr>
                    </table>
                  </div>
                </div>
                <div class="right px-5">
                  <div>
                    <div class="flex justify-between gap-4 -mt-1">
                      <p class="flex w-full"><span class="font-semibold">Name:</span><span class="border-b border-dashed border-black w-full">{{  appointment.patient_name }}</span></p>
                      <p class="flex"><span class="font-semibold">Age:</span><span class="border-b border-dashed border-black w-full">{{ appointment.patient_age  }}</span></p>
                    </div>
                  </div>
                    <div class="flex justify-between gap-4 mt-1">
                        <p class="flex"><span class="font-semibold">Date: </span><span class="border-b border-dashed border-black w-full whitespace-nowrap"> {{ formattedDate }}</span></p>
                        <p class="flex"><span class="font-semibold">Time: </span><span class="border-b border-dashed border-black w-full whitespace-nowrap"> {{ formattedTime }}</span></p>
                        <p class="flex"><span class="font-semibold">Gender:</span><span class="border-b border-dashed border-black w-full">{{ appointment?.patient_gender }}</span></p>
                    </div>

                    <h1 class="text-5xl text-[#A6B556] yellowtail-regular leading-tight tracking-tighter">R<sub>x</sub></h1>
                </div>
            </div>
            <div class="flex justify-between border-b-2 border-t-2 border-gray-300 p-2">
                <span class=" text-xs">Recp: {{appointment?.creator?.name}}</span>
                <span class="font-semibold text-md" v-if="appointment?.careoff_id != '' && appointment?.careoff_id != null">C/O: {{appointment?.careoff_id != '' && appointment?.careoff_id != null ? appointment?.careoff?.name : ''}}</span>
                <span class="font-semibold text-md">Authority: {{appointment?.welfare}}</span>
            </div>
            <div class="text-xs text-center mt-1">
              <div v-if="projectType === 'hms'">Nawaz Town Sargodha Road, Faisalabad Ph: 042-8848060-8787231</div>
              <div v-else>209 Jinnah Colony, Faisalabad Ph: 041-2618764-2634890</div>
              <div>( Under Supervision of MOFAD-E-AMMA Chiniot Sheikh Association.Reg:fsd )</div>
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
  data() {
    return {
      projectType: import.meta.env.VITE_PROJECT_TYPE,
    };
  },
  mounted() {
    setTimeout(() => {
        window.print();
        setTimeout(() => {
            window.close();
        }, 500);
    }, 500);
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
        @import url('https://fonts.googleapis.com/css2?family=Yellowtail&display=swap');
body {
  pointer-events: none;
}
        @media print {
          @page { size: A4; margin: 0; }
          body { margin: 0; }
      }

      .yellowtail-regular {
  font-family: "Yellowtail", cursive;
  font-weight: 400;
  font-style: normal;
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
            width: 30%;
            border-right: 2px solid #535353;
        }

        .prescription-area .right {
            width: 65%;
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
