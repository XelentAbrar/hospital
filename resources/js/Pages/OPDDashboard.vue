<template>
  <div class="w-full mx-auto sm:px-6 lg:px-8">

      <div
        class="grid grid-cols-12 gap-4 mb-6 h-full"
      >
        <div class="md:col-span-3 col-span-12 h-full">
          <div class="bg-white shadow-md rounded-xl p-4 h-full">
            <div class="flex items-center justify-between">
              <h2 class="font-bold text-xl">Patient History</h2>
            </div>
            <div
              class="my-4 bg-primary border border-primary text-white rounded-xl p-4"
            >
              <div class="flex items-center space-x-5">
                <div
                  class="relative w-24 h-24 flex items-center justify-center"
                >
                  <svg class="w-full h-full" viewBox="0 0 100 100">
                    <!-- Background circle -->
                    <circle
                      class="text-[#47A49C] stroke-current"
                      stroke-width="8"
                      cx="50"
                      cy="50"
                      r="40"
                      fill="transparent"
                    ></circle>
                    <!-- Progress circle -->
                    <circle
                      class="text-white progress-ring__circle stroke-current"
                      stroke-width="9"
                      stroke-linecap="round"
                      cx="50"
                      cy="50"
                      r="40"
                      fill="transparent"
                      stroke-dasharray="251.2"
                      stroke-dashoffset="calc(251.2 - (251.2 * 55) / 100)"
                    ></circle>

                    <!-- Center text -->
                  </svg>
                  <div class="absolute">
                    <svg
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 24 24"
                      fill="currentColor"
                      class="w-8 h-8"
                    >
                      <path
                        fill-rule="evenodd"
                        d="M18.685 19.097A9.723 9.723 0 0 0 21.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 0 0 3.065 7.097A9.716 9.716 0 0 0 12 21.75a9.716 9.716 0 0 0 6.685-2.653Zm-12.54-1.285A7.486 7.486 0 0 1 12 15a7.486 7.486 0 0 1 5.855 2.812A8.224 8.224 0 0 1 12 20.25a8.224 8.224 0 0 1-5.855-2.438ZM15.75 9a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"
                        clip-rule="evenodd"
                      />
                    </svg>
                  </div>
                </div>
                <div>
                  <h3 class="font-bold text-xl text-white">{{doctor_appointments.reduce((sum, item) => sum + item.counts, 0)}} Patient today</h3>
                </div>
              </div>
            </div>
            <div class="divide-y mt-4 flex flex-col h-auto pr-2">
              <!-- <div class="flex items-start justify-between py-4 w-full"> -->
                <div class="flex-1 ">
                    <div class="flex items-start space-x-2 py-4 w-full bg-primary border rounded p-2">
                        <!-- <div class="flex items-start space-x-2"> -->
                        <div class="w-full ">
                        <div class="flex items-start justify-between">
                            <h6 class="text-white font-semibold text-lg">
                            Name
                            </h6>
                            <p
                            class="text-white text-sm font-medium whitespace-nowrap"
                            >
                            No.
                            </p>
                        </div>
                        </div>
                        <!-- </div> -->
                    </div>
                    <div class="">
                        <div class="flex items-start space-x-2 py-4 w-full p-2" v-for="doctor_appointment in doctor_appointments">
                            <!-- <div class="flex items-start space-x-2"> -->
                            <div class="w-full">
                            <div class="flex items-start justify-between">
                                <h6 class="text-primary font-semibold text-lg">

                                {{doctor_appointment?.doctor?.name}}
                                </h6>
                                <p
                                class="text-gray-900 text-sm font-medium whitespace-nowrap"
                                >
                                {{doctor_appointment.counts}}
                                </p>
                            </div>
                            </div>

                        </div>
                    </div>

                </div>

                <!-- <div class="flex-end">
                    <div class="flex items-start space-x-2 py-4 w-full bg-secondary border rounded p-2 mt-4">
                        <div class="w-full">
                            <h6 class="text-white font-semibold text-lg">Follow-ups</h6>
                        </div>
                    </div>

                    <div class="flex items-start space-x-2 py-4 w-full p-2" v-for="doctor_appointment in follow_ups" :key="'followup-' + doctor_appointment.consulting_doctor_id">
                        <div class="w-full">
                            <div class="flex items-start justify-between">
                                <h6 class="text-primary font-semibold text-lg">

                                    {{ doctor_appointment?.doctor_name }}
                                </h6>
                                <p class="text-gray-900 text-sm font-medium whitespace-nowrap">
                                    {{ doctor_appointment?.follow_up_count }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
          </div>
        </div>


        <div class="md:col-span-9 col-span-12">
            <div class="grid grid-cols-12 gap-4 mb-6 bg-white py-4 px-6 rounded-lg shadow-md">
                <div class="col-span-12">
                    <form
                    @submit.prevent="submitForm()"
                    enctype="multipart/form-data"
                    >
                    <div class="py-6">
                        <div>
                        <h2 class="text-3xl font-semibold text-gray-900">
                            General
                        </h2>
                        <!-- <p class="mt-1 text-sm text-gray-900">
                            This information will be displayed publicly so be careful what you share.
                        </p> -->
                        </div>

                        <div class="py-6">
                        <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-5">
                            <div class="">
                                <label
                                    for="mr_number"
                                    class="block text-md font-semibold leading-6 text-gray-900"
                                    >Mr Number.
                                </label>
                                <div class="mt-1">
                                    <input
                                    id="mr_number"
                                    name="mr_number"
                                    type="text"

                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                    step="0.01"
                                    @input="generateMRNumber"
                                    @blur="loadPatient($event.target.value)"
                                    :value="appointment.mr_number"
                                    />
                                </div>
                                <InputError
                                    v-if="appointment.errors?.mr_number"
                                    :message="appointment.errors?.mr_number"
                                />
                                </div>
                                <div>
                                <label
                                  for="phone"
                                  class="block text-md font-semibold leading-6 text-gray-900"
                                  >Phone</label
                                >
                                <div class="mt-1">
                                  <input
                                    id="phone"
                                    name="phone"
                                    type="text"
                                    autocomplete="phone"
                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                    v-model="appointment.patient_phone"
                                    @input="formatPhone" maxlength="12"
                                    @blur="loadPatient($event.target.value)"
                                  />
                                </div>
                                <InputError
                                  v-if="appointment.errors?.patient_phone"
                                  :message="appointment.errors?.patient_phone"
                                />
                              </div>

                                <div>
                            <label
                                for="name"
                                class="block text-md font-semibold leading-6 text-gray-900"
                                >Name
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <input
                                id="name"
                                name="name"
                                type="text"
                                autocomplete="name"
                                class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                step="0.01"
                                v-model="appointment.patient_name"
                                />
                            </div>
                            <InputError
                                v-if="appointment.errors?.patient_name"
                                :message="appointment.errors?.patient_name"
                            />
                            </div>


                              <div>
                              <label
                                for="cnic"
                                class="block text-md font-semibold leading-6 text-gray-900"
                                >CNIC
                              </label>
                              <div class="mt-1">
                                <input
                                  id="cnic"
                                  name="cnic"
                                  type="text"
                                  autocomplete="cnic"
                                  class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                  step="0.01"
                                  @blur="loadPatient($event.target.value)"
                                  v-model="appointment.patient_cnic"
                                  @input="formatCNIC" maxlength="15"
                                />
                              </div>
                              <InputError
                                v-if="appointment.errors?.patient_cnic"
                                :message="appointment.errors?.patient_cnic"
                              />
                            </div>
                            <div>
                              <label
                                for="age"
                                class="block text-md font-semibold leading-6 text-gray-900"
                                >Age
                              </label>
                              <div class="mt-1">
                                <input
                                  id="age"
                                  name="age"
                                  type="text"
                                  autocomplete="age"
                                  class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                  step="0.01"
                                  v-model="appointment.patient_age"
                                />
                              </div>
                              <InputError
                                v-if="appointment.errors?.patient_age"
                                :message="appointment.errors?.patient_age"
                              />
                            </div>

                            <div>
                              <label
                                for="gender"
                                class="block text-md font-semibold leading-6 text-gray-900"
                                >Gender</label
                              >
                              <div class="mt-1">
                                <multiselect
                                  v-model="selectedGender"
                                  :options="genderOptions"
                                  @select="onGenderSelect"
                                  label="label"
                                  track-by="value"
                                  placeholder="Select Gender"
                                ></multiselect>
                              </div>
                              <InputError
                                v-if="appointment.errors?.patient_gender"
                                :message="appointment.errors?.patient_gender"
                              />
                            </div>

                            <div>
                              <label
                                for="relation_name"
                                class="block text-md font-semibold leading-6 text-gray-900"
                                >Relation name</label
                              >
                              <div class="mt-1">
                                <input
                                  id="relation_name"
                                  name="relation_name"
                                  type="text"
                                  autocomplete="relation_name"
                                  class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                  step="0.01"
                                  v-model="appointment.patient_relation_name"
                                />
                              </div>
                              <InputError
                                v-if="appointment.errors?.patient_relation_name"
                                :message="appointment.errors?.patient_relation_name"
                              />
                            </div>

                    <div>
                            <label
                                for="consulting_doctor_id"
                                class="block text-md font-semibold leading-6 text-gray-900"
                                >Doctors
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                            <multiselect
                                v-model="selectedDoctor"
                                :options="doctors.filter(res => {return res.doctor_type == 'General'})"
                                @update:modelValue="onDoctorSelect"
                                label="name"
                                track-by="id"
                                placeholder="Select a Doctor"
                            ></multiselect>
                            </div>
                            <InputError
                                v-if="appointment?.errors?.consulting_doctor_id"
                                :message="appointment?.errors?.consulting_doctor_id"
                            />
                            </div>

                    <div>
                    <div class="flex items-start space-x-4 mb-2">
                      <div class="w-1/2 min-w-0 flex-1">
                        <label
                          for="card-expiration-date"
                          class="block text-md font-semibold leading-6 text-gray-900"
                          >Service
                          <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                          <multiselect
                            v-model="appointment.appointment_details[0].service"
                            @select="onServiceSelect($event,0)"
                            :options="services"
                            label="name"
                            track-by="id"
                            placeholder="Select a Service"
                          ></multiselect>
                        </div>
                        <InputError
                          v-if="
                            appointment.errors?.[
                              `appointment_details.0.service.id`
                            ]
                          "
                          :message="
                            appointment.errors?.[
                              `appointment_details.0.service.id`
                            ]
                          "
                        />
                      </div>

                    </div>
                  </div>
                  <div>
                    <div class="flex items-start space-x-4 mb-2">

                      <div class="min-w-0 flex-1">
                        <label
                          for="card-cvc"
                          class="block text-md font-semibold leading-6 text-gray-900"
                          >Fee
                          <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                          <input
                            :value="appointment.appointment_details[0].fee"
                            type="number"
                            class="rblock w-full rounded border-0 py-2 text-gray-900 bg-secondary text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                            placeholder="Fee"
                            step="0.01"
                            disabled
                            @input="calculateAmount()"
                          />
                        </div>
                        <InputError
                          v-if="
                            appointment.errors?.[
                              `appointment_details.0.fee`
                            ]
                          "
                          :message="
                            appointment.errors?.[
                              `appointment_details.0.fee`
                            ]
                          "
                        />
                      </div>
                    </div>
                  </div>

                      <div v-if="hasDonationModule">
                            <label
                                for="careoff_id"
                                class="block text-md font-semibold leading-6 text-gray-900"
                                >Care Off

                            </label>
                            <div class="mt-1">
                            <multiselect
                                v-model="selectedCareOff"
                                :options="careoffs"
                                @update:modelValue="onCareOffSelect"
                                label="name"
                                track-by="id"
                                placeholder="Select a CareOff"
                            ></multiselect>

                            <input
                                    id="donor_fee"
                                    name="donor_fee"
                                    type="text"
                                    autocomplete="donor_fee"
                                    placeholder="Careoff Fee"
                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                    step="0.01"
                                    @input="calculateAmount()"
                                    v-model="appointment.donor_fee"
                                    v-if="appointment.careoff_id != null && appointment.careoff_id != ''"
                                    />

                            </div>
                            <InputError
                                v-if="appointment?.errors?.careoff_id"
                                :message="appointment?.errors?.careoff_id"
                            />
                            </div>


                            <div v-if="hasDonationModule">
                            <label
                                for="donor_id"
                                class="block text-md font-semibold leading-6 text-gray-900"
                                >Zf

                            </label>
                            <div class="mt-1">
                            <input
                                id="name"
                                name="name"
                                type="text"
                                autocomplete="name"
                                class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                step="0.01"
                                @input="checkAmount($event.target.value, 'General', zf_fees)"
                                v-model="appointment.zf_fee"
                                />
                            </div>
                            <InputError
                                v-if="appointment?.errors?.zf_fee"
                                :message="appointment?.errors?.zf_fee"
                            />
                            </div>
                            <div class="col-span-2">
                            <label
                                for="address"
                                class="block text-md font-semibold leading-6 text-gray-900"
                                >Address</label
                            >
                            <div class="mt-1">
                                <input
                                id="address"
                                name="address"
                                type="text"
                                autocomplete="address"
                                class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                v-model="appointment.patient_address"
                                />
                            </div>
                            <InputError
                                v-if="appointment.errors?.patient_address"
                                :message="appointment.errors?.patient_address"
                            />
                            </div>

                            <div>
                            <label
                                for="welfare"
                                class="block text-md font-semibold leading-6 text-gray-900"
                                >Authorized</label
                            >
                            <div class="mt-1">
                                <input
                                id="welfare"
                                name="welfare"
                                type="text"
                                autocomplete="welfare"
                                class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                v-model="appointment.welfare"
                                />
                            </div>
                            <InputError
                                v-if="appointment.errors?.welfare"
                                :message="appointment.errors?.welfare"
                            />
                            </div>
                            <div>
                                <label
                                    for="payable"
                                    class="block text-md font-semibold leading-6 text-gray-900"
                                    >Payable Amount</label
                                >
                                <div class="mt-1">
                                    <input
                                    id="payable"
                                    name="payable"
                                    type="text"
                                    autocomplete="payable"
                                    class="block w-full rounded border-0 py-2 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11 bg-blue-900 text-white"
                                    :value="payableAmount"
                                    disabled
                                    />
                                </div>
                                </div>

                        </div>
                        </div>

                <div class="divide-y divide-gray-200">
                  <div class="flex justify-end gap-x-3 px-4 sm:px-6">
                    <button
                      type="submit"
                      class="rounded bg-primary px-5 py-2 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                      :disabled="appointment.processing"
                    >
                      Add & Print
                    </button>
                  </div>
                </div>
              </div>
            </form>
                </div>
            </div>

            <div class="grid grid-cols-12 gap-4 mb-6 py-4 px-6 rounded-lg shadow-md bg-blue-100">
                <div class="col-span-12">
                    <form
                    @submit.prevent="submitFormSpecialist()"
                    enctype="multipart/form-data"
                    >
                    <!-- Profile section -->
                    <div class="py-6">
                        <div>
                        <h2 class="text-2xl font-semibold text-gray-900">
                            Specialist
                        </h2>
                        <!-- <p class="mt-1 text-sm text-gray-900">
                            This information will be displayed publicly so be careful what you share.
                        </p> -->
                        </div>

                        <div class="py-6">
                        <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-5">
                            <div class="">
                                <label
                                    for="mr_number"
                                    class="block text-md font-semibold leading-6 text-gray-900"
                                    >Mr Number.
                                    <!-- <span class="text-red-500">*</span> -->
                                </label>
                                <div class="mt-1">
                                    <input
                                    id="mr_number"
                                    name="mr_number"
                                    type="text"

                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                    step="0.01"
                                    @input="generateMRNumber"
                                    @blur="loadSpecialPatient($event.target.value)"
                                    :value="special_appointment.mr_number"
                                    />
                                </div>
                                <InputError
                                    v-if="special_appointment.errors?.mr_number"
                                    :message="special_appointment.errors?.mr_number"
                                />
                                </div>
                                <div>
                                <label
                                  for="phone"
                                  class="block text-md font-semibold leading-6 text-gray-900"
                                  >Phone</label
                                >
                                <div class="mt-1">
                                  <input
                                    id="phone"
                                    name="phone"
                                    type="text"
                                    autocomplete="phone"
                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                    v-model="special_appointment.patient_phone"
                                    @blur="loadSpecialPatient($event.target.value)"
                                    @input="formatPhones" maxlength="12"

                                  />
                                </div>
                                <InputError
                                  v-if="special_appointment.errors?.patient_phone"
                                  :message="special_appointment.errors?.patient_phone"
                                />
                              </div>

                                <div>
                            <label
                                for="name"
                                class="block text-md font-semibold leading-6 text-gray-900"
                                >Name
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                                <input
                                id="name"
                                name="name"
                                type="text"
                                autocomplete="name"
                                class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                step="0.01"
                                v-model="special_appointment.patient_name"
                                />
                            </div>
                            <InputError
                                v-if="special_appointment.errors?.patient_name"
                                :message="special_appointment.errors?.patient_name"
                            />
                            </div>


                              <div>
                              <label
                                for="cnic"
                                class="block text-md font-semibold leading-6 text-gray-900"
                                >CNIC
                              </label>
                              <div class="mt-1">
                                <input
                                  id="cnic"
                                  name="cnic"
                                  type="text"
                                  autocomplete="cnic"
                                  class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                  step="0.01"
                                  @blur="loadSpecialPatient($event.target.value)"
                                  v-model="special_appointment.patient_cnic"
                                  @input="formatCNICs" maxlength="15"

                                />
                              </div>
                              <InputError
                                v-if="special_appointment.errors?.patient_cnic"
                                :message="special_appointment.errors?.patient_cnic"
                              />
                            </div>

                            <div>
                              <label
                                for="age"
                                class="block text-md font-semibold leading-6 text-gray-900"
                                >Age
                              </label>
                              <div class="mt-1">
                                <input
                                  id="age"
                                  name="age"
                                  type="text"
                                  autocomplete="age"
                                  class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                  step="0.01"
                                  v-model="special_appointment.patient_age"
                                />
                              </div>
                              <InputError
                                v-if="special_appointment.errors?.patient_age"
                                :message="special_appointment.errors?.patient_age"
                              />
                            </div>

                            <div>
                              <label
                                for="gender"
                                class="block text-md font-semibold leading-6 text-gray-900"
                                >Gender</label
                              >
                              <div class="mt-1">
                                <multiselect
                                  v-model="selectedSpecialGender"
                                  :options="genderSpecialOptions"
                                  @select="onGenderSpecialSelect"
                                  label="label"
                                  track-by="value"
                                  placeholder="Select Gender"
                                ></multiselect>
                              </div>
                              <InputError
                                v-if="special_appointment.errors?.patient_gender"
                                :message="special_appointment.errors?.patient_gender"
                              />
                            </div>
                            <div>
                            <label
                                for="welfare"
                                class="block text-md font-semibold leading-6 text-gray-900"
                                >Authorized</label
                            >
                            <div class="mt-1">
                                <input
                                id="welfare"
                                name="welfare"
                                type="text"
                                autocomplete="welfare"
                                class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                v-model="special_appointment.welfare"
                                />
                            </div>
                            <InputError
                                v-if="special_appointment.errors?.welfare"
                                :message="special_appointment.errors?.welfare"
                            />
                            </div>
                            <div>
                      <label
                        for="relation_name"
                        class="block text-md font-semibold leading-6 text-gray-900"
                        >Relation name</label
                      >
                      <div class="mt-1">
                        <input
                          id="relation_name"
                          name="relation_name"
                          type="text"
                          autocomplete="relation_name"
                          class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                          step="0.01"
                          v-model="special_appointment.patient_relation_name"
                        />
                      </div>
                      <InputError
                        v-if="special_appointment.errors?.patient_relation_name"
                        :message="special_appointment.errors?.patient_relation_name"
                      />
                    </div>
                    <div class="col-span-2">
                            <label
                                for="address"
                                class="block text-md font-semibold leading-6 text-gray-900"
                                >Address</label
                            >
                            <div class="mt-1">
                                <input
                                id="address"
                                name="address"
                                type="text"
                                autocomplete="address"
                                class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                v-model="special_appointment.patient_address"
                                />
                            </div>
                            <InputError
                                v-if="special_appointment.errors?.patient_address"
                                :message="special_appointment.errors?.patient_address"
                            />
                            </div>

                            <div>
                            <label
                                for="consulting_doctor_id"
                                class="block text-md font-semibold leading-6 text-gray-900"
                                >Doctors
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1">
                            <multiselect
                                v-model="selectedDoctorSpecialList"
                                :options="doctors.filter(res => {return res.doctor_type == 'Specialist'})"
                                @update:modelValue="onDoctorSelectSpecialList"
                                label="name"
                                track-by="id"
                                placeholder="Select a Doctor"
                            ></multiselect>
                            </div>
                            <InputError
                                v-if="special_appointment?.errors?.consulting_doctor_id"
                                :message="special_appointment?.errors?.consulting_doctor_id"
                            />
                            </div>
                    <div
                  >
                    <div class="flex items-start space-x-4 mb-2">
                      <div class="w-1/2 min-w-0 flex-1">
                        <label
                          for="card-expiration-date"
                          class="block text-md font-semibold leading-6 text-gray-900"
                          >Service
                          <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                          <multiselect
                            v-model="special_appointment.appointment_details[0].service"
                            @select="onServiceSelectSpecialList($event,0)"
                            :options="services"
                            label="name"
                            track-by="id"
                            placeholder="Select a Service"
                          ></multiselect>
                        </div>
                        <InputError
                          v-if="
                            special_appointment.errors?.[
                              `appointment_details.0.service.id`
                            ]
                          "
                          :message="
                            special_appointment.errors?.[
                              `appointment_details.0.service.id`
                            ]
                          "
                        />
                      </div>

                    </div>
                  </div>
                  <div
                  >
                    <div class="flex items-start space-x-4 mb-2">

                      <div class="min-w-0 flex-1">
                        <label
                          for="card-cvc"
                          class="block text-md font-semibold leading-6 text-gray-900"
                          >Fee
                          <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                          <input
                            :value="special_appointment.appointment_details[0].fee"
                            type="number"
                            class="rblock w-full bg-secondary text-white rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                            placeholder="Fee"
                            @input="calculateAmountSpecial()"
                            step="0.01"
                            disabled
                          />
                        </div>
                        <InputError
                          v-if="
                            special_appointment.errors?.[
                              `appointment_details.0.fee`
                            ]
                          "
                          :message="
                            special_appointment.errors?.[
                              `appointment_details.0.fee`
                            ]
                          "
                        />
                      </div>
                    </div>
                  </div>


                  <!-- <div>
                      <label
                        for="file_no"
                        class="block text-md font-semibold leading-6 text-gray-900"
                        >File NO.</label
                      >
                      <div class="mt-1">
                        <input
                          id="file_no"
                          name="file_no"
                          type="text"
                          autocomplete="file_no"
                          class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                          step="0.01"
                          v-model="special_appointment.file_no"
                        />
                      </div>
                      <InputError
                        v-if="special_appointment.errors?.file_no"
                        :message="special_appointment.errors?.file_no"
                      />
                    </div> -->

                  <div>
                            <label
                                for="careoff_id"
                                class="block text-md font-semibold leading-6 text-gray-900"
                                >Care Off

                            </label>
                            <div class="mt-1">
                            <multiselect
                                v-model="selectedCareOffSpecialList"
                                :options="careoffs"
                                @update:modelValue="onCareOffSelectSpecialList"
                                label="name"
                                track-by="id"
                                placeholder="Select a CareOff"
                            ></multiselect>
                            <input
                                    id="donor_fee"
                                    name="donor_fee"
                                    type="text"
                                    autocomplete="donor_fee"
                                    placeholder="Careoff Fee"
                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                    step="0.01"
                                    @input="calculateAmountSpecial()"
                                    v-model="special_appointment.donor_fee"
                                    v-if="special_appointment.careoff_id != null && special_appointment.careoff_id != ''"
                                    />
                            </div>
                            <InputError
                                v-if="special_appointment?.errors?.careoff_id"
                                :message="special_appointment?.errors?.careoff_id"
                            />
                            </div>


                            <div>
                            <label
                                for="donor_id"
                                class="block text-md font-semibold leading-6 text-gray-900"
                                >Zf

                            </label>
                            <div class="mt-1">
                            <input
                                id="name"
                                name="name"
                                type="text"
                                autocomplete="name"
                                class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                step="0.01"
                                @input="checkAmount($event.target.value, 'Specialist', zf_fees)"
                                v-model="special_appointment.zf_fee"
                                />
                            </div>
                            <InputError
                                v-if="special_appointment?.errors?.zf_id"
                                :message="special_appointment?.errors?.zf_id"
                            />
                            </div>


                            <div>
                                <label
                                    for="payable"
                                    class="block text-md font-semibold leading-6 text-gray-900"
                                    >Payable Amount</label
                                >
                                <div class="mt-1">
                                    <input
                                    id="payable"
                                    name="payable"
                                    type="text"
                                    autocomplete="payable"
                                    class="block w-full rounded border-0 py-2 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11 bg-blue-900 text-white"
                                    :value="payableAmountSpecial"
                                    disabled
                                    />
                                </div>
                              </div>

                        </div>
                        </div>

                <div class="divide-y divide-gray-200">
                  <div class="flex justify-end gap-x-3 px-4 sm:px-6">
                    <button
                      type="submit"
                      class="rounded bg-primary px-5 py-2 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                      :disabled="special_appointment.processing"
                    >
                      Add & Print
                    </button>
                  </div>
                </div>
              </div>
            </form>
                </div>
            </div>

        </div>

      </div>
    </div>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, router } from "@inertiajs/vue3";
import { ref, watchEffect, computed, onMounted ,reactive  } from "vue";
import Multiselect from "vue-multiselect";
import axios from "axios";
const displayForm = ref('');
const getCurrentDateInPakistanTimezone = () => {
  const options = { year: 'numeric', month: '2-digit', day: '2-digit', timeZone: 'Asia/Karachi' };
  const currentDate = new Date().toLocaleDateString('en-CA', options);
  return currentDate;
};
const props = defineProps({
  mr_number: String,
  doctor_appointments: Array,
  follow_ups: Array,
  doctors: Object,
  careoffs: Object,
  zf_fees: String,
  patients: Object,
  services: Array,
  hasDonationModule: Boolean,
});
const appointment = useForm({
  id: null,
  type: 'General',
  consulting_doctor_id: null,
 appointment_date :  getCurrentDateInPakistanTimezone(),
  appointment_time: new Date().toTimeString().slice(0, 8),
  patient_name: null,
  patient_phone: null,
  mr_number: props.mr_number || "",
  patient_cnic: null,
  patient_relation_name: null,
  patient_address: null,
  patient_gender: null,
  patient_age: null,
  careoff_id: null,
  donor_fee: null,
  zf_id: null,
  welfare: null,
  zf_fee:null,
  source:'dashboard',
  appointment_details: [
    {
      id: null,
      appointment_id: null,
      service: null,
      fee: null,
    },
  ],
});
onMounted(() => {
  appointment.mr_number = props.mr_number || "";
  special_appointment.mr_number = props.mr_number || "";
});
const formatPhone = (event) => {
  let input = event.target.value.replace(/\D/g, "");
  if (input.length > 4) {
    input = input.replace(/^(\d{4})(\d+)/, "$1-$2");
  }
  appointment.patient_phone = input;
};
const formatCNIC = (event) => {
  let input = event.target.value.replace(/\D/g, "");
  if (input.length > 5) {
    input = input.replace(/^(\d{5})(\d{7})(\d{1})$/, "$1-$2-$3");
  }
  appointment.patient_cnic = input;
};

const special_appointment = useForm({
  id: null,
  type: 'Specialist',
  consulting_doctor_id: null,
  appointment_date: getCurrentDateInPakistanTimezone(),
  appointment_time: new Date().toTimeString().slice(0, 8),
  patient_name: null,
  patient_phone: null,
  mr_number: props.mr_number || "",
  patient_cnic:null,
  patient_relation_name: null,
  patient_address: null,
  patient_gender: null,
  patient_age: null,
  file_no: null,
  careoff_id: null,
  donor_fee: null,
  zf_id: null,
  welfare: null,
  zf_fee:null,
  source:'dashboard',
  appointment_details: [
    {
      id: null,
      appointment_id: null,
      service: null,
      fee: null,
    },
  ],
});
const formatPhones = (event) => {
  let input = event.target.value.replace(/\D/g, "");
  if (input.length > 4) {
    input = input.replace(/^(\d{4})(\d+)/, "$1-$2");
  }
  special_appointment.patient_phone = input;
};

const generateMRNumber = (event) => {
  let input = event.target.value.replace(/\D/g, "");
  let number = input ? parseInt(input) : 1;
  let formattedNumber = number.toString().padStart(5, '0');
  appointment.mr_number = formattedNumber;
  special_appointment.mr_number = formattedNumber;

};


const formatCNICs = (event) => {
  let input = event.target.value.replace(/\D/g, "");
  if (input.length > 5) {
    input = input.replace(/^(\d{5})(\d{7})(\d{1})$/, "$1-$2-$3");
  }
  special_appointment.patient_cnic = input;
};

const selectedGender = ref();
const genderOptions = computed(() => {
  return [
    { value: "Male", label: "Male" },
    { value: "Female", label: "Female" },
    { value: "Children", label: "Children" },
  ];
});

const onGenderSelect = async (selectedGender) => {
  appointment.patient_gender = selectedGender.value;
};


const selectedCareOff = ref(null);
const selectedZf = ref(null);
const selectedDoctor = ref(null);
const selectedDoctorSpecialList = ref(null);
const selectedCareOffSpecialList = ref(null);
const selectedZfSpecialList = ref(null);

const onDoctorSelect = async (selectedDoctor) => {
  appointment.appointment_details[0].fee = selectedDoctor?.doctor_charges?.consulting_charges ?? 0;
  appointment.consulting_doctor_id = selectedDoctor?.id ?? null;
  calculateAmount();
};

const onCareOffSelect = async (selectedCareOffs) => {
  appointment.careoff_id = selectedCareOffs?.id ?? null;
  if(appointment.careoff_id == null){
    appointment.donor_fee = null;
    calculateAmount();
  }
};

const onServiceSelect = async (selectedDoctor, index) => {
  // appointment.appointment_details[index].fee = selectedDoctor.charges;
};

const onZfSelect = async (selectedZf) => {
  appointment.zf_id = selectedZf.id;
  selectedCareOff.value = null;
  appointment.careoff_id = null;
  appointment.zf_fee = null;
};

const onServiceSelectSpecialList = async (selectedDoctor, index) => {
  // special_appointment.appointment_details[index].fee = selectedDoctor.charges;
};


const selectedSpecialGender = ref();
const genderSpecialOptions = computed(() => {
  return [
    { value: "Male", label: "Male" },
    { value: "Female", label: "Female" },
    { value: "Children", label: "Children" },
  ];
});

const onGenderSpecialSelect = async (selectedGender) => {
  special_appointment.patient_gender = selectedGender.value;
};

const onDoctorSelectSpecialList = async (selectedDoctor) => {
  special_appointment.appointment_details[0].fee = selectedDoctor?.doctor_charges?.consulting_charges ?? 0;
  special_appointment.consulting_doctor_id = selectedDoctor?.id ?? null;
  calculateAmountSpecial();
};

const onCareOffSelectSpecialList = async (selectedCareOffs) => {
  special_appointment.careoff_id = selectedCareOffs?.id ?? null;
  if(special_appointment.careoff_id == null){
    special_appointment.donor_fee = null;
    calculateAmountSpecial();
  }
};

const onZfSelectSpecialList = async (selectedZf) => {
  special_appointment.zf_id = selectedZf.id;
  selectedCareOffSpecialList.value = null;
  special_appointment.careoff_id = null;
  special_appointment.zf_fee = null;
};

const payableAmount = ref();

const calculateAmount = () => {
  let sum = 0;
  appointment.appointment_details.map(res =>{
    sum += parseFloat(res.fee || 0);
  })
  payableAmount.value = sum;
  payableAmount.value = +payableAmount.value - +(appointment.donor_fee || 0) - +(appointment.zf_fee || 0);
  // calculateRemaingAmount();
};

const payableAmountSpecial = ref();

const calculateAmountSpecial = () => {
  let sum = 0;
  special_appointment.appointment_details.map(res =>{
    sum += parseFloat(res.fee || 0);
  })
  payableAmountSpecial.value = sum;
  payableAmountSpecial.value = +payableAmountSpecial.value - +(special_appointment.donor_fee || 0) - +(special_appointment.zf_fee || 0);
  // calculateRemaingAmount();
};

// const remainingAmount = ref();

// const calculateRemaingAmount = () => {
//   remainingAmount.value = +payableAmount.value - +appointment.donor_fee - +appointment.zf_fee;
// };

// console.log(activeTab);
const submitForm = () => {
    appointment.processing = true;
    appointment.post(route('appointments.store'), {
        preserveScroll: true,
        appointment,
        onSuccess: (res) => {
            appointment.processing = false;
            selectedDoctor.value = null;
            appointment.patient_name = null;
            appointment.patient_phone = null;
            appointment.patient_cnic = null;
            appointment.patient_address = null;
            appointment.patient_gender = null;
            selectedGender.value = null;
            appointment.patient_age = null;
            appointment.careoff_id = null;
            appointment.donor_fee = null;
            appointment.zf_id = null;
            appointment.welfare = null;
            appointment.consulting_doctor_id = null;
            appointment.patient_relation_name = null;
            appointment.zf_fee = null;
            selectedZf.value = null;
            selectedCareOff.value = null;
            appointment.appointment_details = [
                {
                    id: null,
                    appointment_id: null,
                    service: null,
                    fee: null,
                },
            ];
            // appointment.mr_number = res.mr_number || '';
            var url_string = window.location.href;
            var url = new URL(url_string);
            var appointment_id = url.searchParams.get("appointment_id");
            printAppointment(appointment_id);
            // router.visit('/dashboard');
            const redirectUrl = new URL(window.location.href);
            const tab = redirectUrl.searchParams.get('menuTab') || '1';
            router.visit(`/dashboard?menuTab=${tab}`);
        },
        onError: (error) => {
          appointment.processing = false;
        }
    })
};


const submitFormSpecialist = () => {
    special_appointment.processing = true;
    special_appointment.post(route('appointments.store'), {
        preserveScroll: true,
        appointment,
        onSuccess: (res) => {
            special_appointment.processing = false;
            selectedDoctorSpecialList.value = null;
            special_appointment.patient_name = null;
            special_appointment.patient_phone = null;
            special_appointment.patient_cnic = null;
            special_appointment.patient_address = null;
            special_appointment.patient_gender = null;
            special_appointment.patient_age = null;
            special_appointment.consulting_doctor_id = null;
            special_appointment.patient_relation_name = null;
            special_appointment.careoff_id = null;
            special_appointment.donor_fee = null;
            special_appointment.zf_id = null;
            special_appointment.welfare = null;
            special_appointment.zf_fee = null;
            special_appointment.file_no = null;
            selectedZfSpecialList.value = null;
            selectedCareOffSpecialList.value = null;
            special_appointment.appointment_details = [
                {
                    id: null,
                    appointment_id: null,
                    service: null,
                    fee: null,
                },
            ]
            var url_string = window.location.href;
            var url = new URL(url_string);
            var appointment_id = url.searchParams.get("appointment_id");
            printAppointment(appointment_id);
            // router.visit('/dashboard');
            const currentUrl = new URL(window.location.href);
            const tab = currentUrl.searchParams.get('menuTab') || '1';
            router.visit(`/dashboard?menuTab=${tab}`);

        },
        onError: (error) => {
          special_appointment.processing = false;
        }
    })
};

const printAppointment = (appointment_id) => {

    const url = route('appointments.print', appointment_id);
    if (!url) {
        return;
    }
   const printWindow = window.open(url, '_blank');


};

const checkAmount = (val, type, zf_fees) => {
//   if(val > zf_fees && type == 'General'){
    // appointment.zf_fee = zf_fees;
    // calculateAmount();
//   }
//   if(val > zf_fees && type == 'Specialist'){
    // special_appointment.zf_fee = zf_fees;
    // calculateAmountSpecial();
//   }

  if(type == 'General'){
    calculateAmount();
  }
  if(type == 'Specialist'){
    calculateAmountSpecial();
  }
};

const loadPatient = async (patientPhone) => {
  try {
    const response = await axios.get(`/patients-phone/${patientPhone}`);
    let patient = response?.data?.patient || null;
    if (patient) {
      appointment.patient_name = patient?.name || null;
      appointment.patient_cnic = patient?.cnic || null;
      appointment.patient_age = patient?.age || null;
      appointment.patient_relation_name = patient?.relation_name || null;
      appointment.patient_gender = patient?.gender || null;
      appointment.patient_address = patient?.address || null;
      appointment.mr_number = patient?.mr_number || null;
        }
  } catch (error) {
    console.error("Error fetching states:", error);
  }
};

const loadSpecialPatient = async (patientPhone) => {
  try {
    const response = await axios.get(`/patients-phone/${patientPhone}`);
    let patient = response?.data?.patient || null;
    if (patient) {
      special_appointment.patient_name = patient?.name || null;
      special_appointment.patient_cnic = patient?.cnic || null;
      special_appointment.patient_age = patient?.age || null;
      special_appointment.patient_relation_name = patient?.relation_name || null;
      special_appointment.patient_address = patient?.address || null;
      special_appointment.mr_number = patient?.mr_number || null;
    }
  } catch (error) {
    console.error("Error fetching states:", error);
  }
};


</script>
<script>
import { defineComponent } from "vue";
import InputError from "../Components/InputError.vue";


export default defineComponent({
  components: {
    Multiselect,
    InputError,
  },
  props: {
    mr_number:String,
    doctor_appointments: Array,
    follow_ups: Array,
    doctors:Object,
    careoffs:Object,
    zf_fees:String,
    patients:Object,
    services:Array,
  },
});
</script>


<style scoped>
.progress-ring__circle {
  transition: stroke-dashoffset 0.35s;
  transform: rotate(-90deg);
  transform-origin: 50% 50%;
}
</style>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
