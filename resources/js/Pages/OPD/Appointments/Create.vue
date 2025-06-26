<template>
  <Head title="Create Appointment" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg">
          <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <h1 class="font-semibold text-3xl text-primary">
              {{ appointment?.id ? "Update" : "Create" }} Appointment
            </h1>
            <form
              @submit.prevent="
                appointment?.id
                  ? appointment.put(
                      route('appointments.update', { id: appointment.id })
                    )
                  : appointment.post(route('appointments.store'), appointment)
              "
              enctype="multipart/form-data"
            >
              <!-- Profile section -->
              <div class="py-6">
                <div>
                  <h2 class="text-2xl font-semibold text-gray-900">
                    Appointment
                  </h2>
                  <p class="mt-1 text-sm text-gray-900">
                    This information will be displayed publicly so be careful
                    what you share.
                  </p>
                </div>

                <div class="py-6">
                  <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-4">
                    <div>
                      <label
                        for="name"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >MR Number
                        <span class="text-red-500">*</span>
                      </label>
                      <div class="mt-1">
                        <input
                          id="mr_number"
                          name="mr_number"
                          type="text"
                          autocomplete="mr_number"
                          @keyup.enter="focusNext($event)"
                          @change="loadPatientData($event.target.value)"
                          ref="input0"
                          class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                          step="0.01"
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
                        for="name"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Name
                        <span class="text-red-500">*</span>
                      </label>
                      <div class="mt-1">
                        <input
                          id="name"
                          name="name"
                          type="text"
                          autocomplete="name"
                          @keyup.enter="focusNext($event)"
                          ref="input0"
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
                        for="phone"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Phone</label
                      >
                      <div class="mt-1">
                        <input
                          id="phone"
                          name="phone"
                          type="text"
                          autocomplete="phone"
                          @keyup.enter="focusNext($event)"
                          @change="loadPatientData($event.target.value)"
                          ref="input1"
                          class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                          v-model="appointment.patient_phone"
                        />
                      </div>
                      <InputError
                        v-if="appointment.errors?.patient_phone"
                        :message="appointment.errors?.patient_phone"
                      />
                    </div>
                    <div>
                      <label
                        for="cnic"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >CNIC
                        <span class="text-red-500">*</span>
                      </label>
                      <div class="mt-1">
                        <input
                          id="cnic"
                          name="cnic"
                          type="text"
                          autocomplete="cnic"
                          @keyup.enter="focusNext($event)"
                          @change="loadPatientData($event.target.value)"
                          ref="input2"
                          class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                          step="0.01"
                          v-model="appointment.patient_cnic"
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
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Age
                        <span class="text-red-500">*</span>
                      </label>
                      <div class="mt-1">
                        <input
                          id="age"
                          name="age"
                          type="text"
                          autocomplete="age"
                          @keyup.enter="focusNext($event)"
                          ref="input3"
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
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Gender</label
                      >
                      <div class="mt-1">
                        <multiselect
                          v-model="selectedGender"
                          :options="genderOptions"
                          @select="onGenderSelect"
                          label="label"
                          track-by="value"
                          @keyup.enter="focusNext($event)"
                          ref="input4"
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
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Relation name</label
                      >
                      <div class="mt-1">
                        <input
                          id="relation_name"
                          name="relation_name"
                          type="text"
                          autocomplete="relation_name"
                          @keyup.enter="focusNext($event)"
                          ref="input5"
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
                        for="address"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Address</label
                      >
                      <div class="mt-1">
                        <input
                          id="address"
                          name="address"
                          type="text"
                          autocomplete="address"
                          @keyup.enter="focusNext($event)"
                          ref="input6"
                          class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                          v-model="appointment.patient_address"
                        />
                      </div>
                      <InputError
                        v-if="appointment.errors?.patient_address"
                        :message="appointment.errors?.patient_address"
                      />
                    </div>
                    <!-- <div>
                      <label
                        for="patient_id"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Patient</label
                      >
                      <div class="mt-1">
                      <multiselect
                        v-model="selectedPatient"
                        :options="patients"
                        @select="onPatientSelect"
                        label="name"
                        track-by="id"
                        placeholder="Select a Patient"
                      ></multiselect>
                    </div>
                      <InputError
                        v-if="appointment?.errors?.patient_id"
                        :message="appointment?.errors?.patient_id"
                      />
                    </div> -->
                    <div>
                      <label
                        for="type"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Appointment type
                        <span class="text-red-500">*</span>
                      </label>
                      <div class="mt-1">
                      <multiselect
                        v-model="selectedType"
                        :options="typeOptions"
                        @select="onTypeSelect"
                        @keyup.enter="focusNext($event)"
                        ref="input7"
                        label="value"
                        track-by="label"
                        placeholder="Select type"
                      ></multiselect>
                      </div>
                      <InputError
                        v-if="appointment?.errors?.type"
                        :message="appointment?.errors?.type"
                      />
                    </div>
                    <div>
                      <label
                        for="appointment_date"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Appointment date</label
                      >
                      <input
                        type="text"
                        ref="appointment_date"
                        name="appointment_date"
                        id="appointment_date"
                        autocomplete="date-of-birth"
                        @keyup.enter="focusNext($event)"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="appointment.appointment_date"
                      />
                      <InputError
                        v-if="appointment?.errors?.appointment_date"
                        :message="appointment?.errors?.appointment_date"
                      />
                    </div>
                    <div>
                      <label
                        for="appointment_time"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Appointment time</label
                      >
                      <input
                        type="time"
                        name="appointment_time"
                        id="appointment_time"
                        autocomplete="date-of-birth"
                        @keyup.enter="focusNext($event)"
                        ref="input9"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="appointment.appointment_time"
                      />
                      <InputError
                        v-if="appointment?.errors?.appointment_time"
                        :message="appointment?.errors?.appointment_time"
                      />
                    </div>

                  </div>
                </div>

                <!-- <div class="border-t border-gray-200 py-6">
                  <h2 class="text-2xl font-semibold text-gray-900 pb-3">
                    Patient detail
                  </h2>
                  <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-3">

                    <div>
                      <label
                        for="dob"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >DOB</label
                      >
                      <div class="mt-1">
                        <input
                          id="dob"
                          name="dob"
                          type="date"
                          autocomplete="dob"
                          class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                          step="0.01"
                          v-model="appointment.patient_dob"
                        />
                      </div>
                      <InputError
                        v-if="appointment.errors?.patient_dob"
                        :message="appointment.errors?.patient_dob"
                      />
                    </div>

                    <div>
                      <label
                        for="relation_type"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Relation type</label
                      >
                      <div class="mt-1">
                        <multiselect
                          v-model="selectedRelationType"
                          :options="relationTypeOptions"
                          @select="onRelationTypeSelect"
                          label="label"
                          track-by="value"
                          placeholder="Select relation type"
                        ></multiselect>
                      </div>
                      <InputError
                        v-if="appointment.errors?.patient_relation_type"
                        :message="appointment.errors?.patient_relation_type"
                      />
                    </div>
                    <div>
                      <label
                        for="country"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Country</label
                      >
                      <div class="mt-1">
                        <multiselect
                          v-model="selectedCountry"
                          :options="countryOptions"
                          @select="onCountrySelect"
                          label="name"
                          track-by="id"
                          placeholder="Select a Country"
                        ></multiselect>
                      </div>
                      <InputError
                        v-if="appointment?.errors?.country_id"
                        :message="appointment?.errors?.country_id"
                      />
                    </div>
                    <div>
                      <label
                        for="state"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >State</label
                      >
                      <div class="mt-1">
                        <multiselect
                          v-model="selectedState"
                          :options="stateOptions"
                          @select="onStateSelect"
                          label="name"
                          track-by="id"
                          placeholder="Select a State"
                        ></multiselect>
                      </div>
                      <InputError
                        v-if="appointment?.errors?.state_id"
                        :message="appointment?.errors?.state_id"
                      />
                    </div>
                    <div>
                      <label
                        for="city"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >City</label
                      >
                      <div class="mt-1">
                        <multiselect
                          v-model="selectedCity"
                          :options="cityOptions"
                          @select="onCitySelect"
                          label="name"
                          track-by="id"
                          placeholder="Select a City"
                        ></multiselect>
                      </div>
                      <InputError
                        v-if="appointment?.errors?.city_id"
                        :message="appointment?.errors?.city_id"
                      />
                    </div>
                    <div>
                      <label
                        for="email"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Email</label
                      >
                      <div class="mt-1">
                        <input
                          id="email"
                          name="email"
                          type="email"
                          autocomplete="email"
                          class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                          v-model="appointment.patient_email"
                        />
                      </div>
                      <InputError
                        v-if="appointment.errors?.patient_email"
                        :message="appointment.errors?.patient_email"
                      />
                    </div>
                    <div>
                      <label
                        for="blood_group"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Blood Group</label
                      >
                      <div class="mt-1">
                        <multiselect
                          v-model="selectedBloodGroup"
                          :options="bloodGroupOptions"
                          @select="onBloodGroupSelect"
                          label="type"
                          track-by="type"
                          placeholder="Select Blood Group"
                        ></multiselect>
                      </div>
                      <InputError
                        v-if="appointment?.errors?.blood_group"
                        :message="appointment?.errors?.blood_group"
                      />
                    </div>
                    <div>
                      <label
                        for="rh_factor"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >RH Factor</label
                      >
                      <div class="mt-1">
                        <input
                          id="rh_factor"
                          name="rh_factor"
                          type="text"
                          autocomplete="rh_factor"
                          class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                          v-model="appointment.patient_rh_factor"
                        />
                      </div>
                      <InputError
                        v-if="appointment.errors?.patient_rh_factor"
                        :message="appointment.errors?.patient_rh_factor"
                      />
                    </div>
                  </div>
                </div> -->

                <div class="border shadow-md rounded-lg border-gray-200 p-6 bg-green-50 my-6">
                  <h2 class="text-2xl font-semibold text-gray-900 pb-3">
                    Services
                  </h2>
                  <div class="border rounded mb-2">
                    <div class="flex divide-x border-b">
                      <div class="w-[32%] min-w-0 flex-1 p-2">
                        <label
                          for="card-expiration-date"
                          class="block text-sm md:text-base font-medium text-gray-900"
                          >Doctor
                          <!-- <span class="text-red-500">*</span> -->
                        </label>
                      </div>
                      <div class="w-[32%] min-w-0 flex-1 p-2">
                        <label
                          for="card-expiration-date"
                          class="block text-sm md:text-base font-medium text-gray-900"
                          >Service
                          <!-- <span class="text-red-500">*</span> -->
                        </label>
                      </div>
                      <div class="w-[32%] min-w-0 flex-1 p-2">
                        <label
                          for="card-cvc"
                          class="block text-sm md:text-base font-medium text-gray-900"
                          >Fee
                          <!-- <span class="text-red-500">*</span> -->
                        </label>
                      </div>
                      <!-- <div class="w-[31%] min-w-0 flex-1 p-2">
                        <label
                          for="card-cvc"
                          class="block text-sm md:text-base font-medium text-gray-900"
                          >Fee status
                          <span class="text-red-500">*</span>
                        </label>
                      </div> -->
                      <!-- <div class="w-[5%]"></div> -->
                    </div>
                  <div
                    v-for="(
                      appointment_detail, index
                    ) in appointment.appointment_details"
                    :key="index"
                  >
                    <div class="flex items-start divide-x border-b">
                      <div class="w-[32%] min-w-0 flex-1 p-2">
                        <label
                          for="card-expiration-date"
                          class="block text-sm md:text-base font-medium text-gray-900 sr-only"
                          >Doctor
                          <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                        <multiselect
                          v-model="selectedDoctor"
                          :options="doctorOptions.filter(res => {return res.doctor_type == appointment.type})"
                          @update:modelValue="onDoctorSelect"
                          @keyup.enter="focusNext($event)"
                          ref="input10"
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
                      <div class="w-[32%] min-w-0 flex-1 p-2">
                        <label
                          for="card-expiration-date"
                          class="block text-sm md:text-base font-medium text-gray-900 sr-only"
                          >Service
                          <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                          <multiselect
                            v-model="appointment_detail.service"
                            :options="services"
                            @keyup.enter="focusNext($event)"
                            ref="input11"
                            label="name"
                            track-by="id"
                            placeholder="Select a Service"
                          ></multiselect>
                        </div>
                        <InputError
                          v-if="
                            appointment.errors?.[
                              `appointment_details.${index}.service.id`
                            ]
                          "
                          :message="
                            appointment.errors?.[
                              `appointment_details.${index}.service.id`
                            ]
                          "
                        />
                      </div>
                      <div class="w-[32%] min-w-0 flex-1 p-2">
                        <label
                          for="card-cvc"
                          class="block text-sm md:text-base font-medium text-gray-900 sr-only"
                          >Fee
                          <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                          <input
                            v-model="appointment_detail.fee"
                            type="number"
                            @keyup.enter="focusNext($event)"
                            ref="input12"
                            class="block w-full bg-secondary text-white rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                            placeholder="Fee"
                            step="0.01"
                            disabled
                            @input="calculateAmount()"
                          />
                        </div>
                        <InputError
                          v-if="
                            appointment.errors?.[
                              `appointment_details.${index}.fee`
                            ]
                          "
                          :message="
                            appointment.errors?.[
                              `appointment_details.${index}.fee`
                            ]
                          "
                        />
                      </div>
                      <!-- <div class="w-[32%] min-w-0 flex-1 p-2">
                        <label
                          for="card-cvc"
                          class="block text-sm md:text-base font-medium text-gray-900 sr-only"
                          >Fee status
                          <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                          <multiselect
                            v-model="appointment_detail.fee_status"
                            :options="feeStatusOptions"
                            label="label"
                            track-by="value"
                            placeholder="Select fee status"
                          ></multiselect>
                        </div>
                        <InputError
                          v-if="
                            appointment.errors?.[
                              `appointment_details.${index}.fee_status.value`
                            ]
                          "
                          :message="
                            appointment.errors?.[
                              `appointment_details.${index}.fee_status.value`
                            ]
                          "
                        />
                      </div> -->
                      <!-- <div class="w-[5%]">
                        <div class="p-2 flex items-center justify-center h-16">
                        <button
                          type="button"
                          @click="removeAppointmentDetail(index)"
                          class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                        >
                          <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"
                            />
                          </svg>
                        </button>
                      </div>
                      </div> -->
                    </div>
                  </div>
                </div>
                  <!-- <button
                    type="button"
                    @click="addAppointmentDetail"
                    class="inline-flex items-center mt-1 px-3 py-2 text-sm font-medium text-white bg-blue-500 rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    <svg
                      class="w-4 h-4 mr-2"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                      />
                    </svg>
                    Add More
                  </button> -->
                </div>
                <div class="py-6">
                  <div class="grid grid-cols-1 gap-4 md:grid-cols-5">
                    <div class="col-span-3"></div>
                    <div class="col-span-2 space-y-2">
                      <div class="flex items-center space-x-2 w-full" v-if="hasDonationModule">
                        <label
                          for="careoff_id"
                          class="block text-base font-medium leading-6 text-gray-900 min-w-44 whitespace-nowrap"
                        >Care Off
                        </label>
                        <div class="mt-1 w-full">
                          <multiselect
                            v-model="selectedCareOff"
                            :options="careoffs"
                            @update:modelValue="onCareOffSelect(selectedCareOff)"
                            @keyup.enter="focusNext($event)"
                            ref="input13"
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
                            @keyup.enter="focusNext($event)"
                            ref="input14"
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
                    <div class="flex items-center space-x-2 w-full" v-if="hasDonationModule">
                      <label
                          for="donor_id"
                          class="block text-base font-medium leading-6 text-gray-900 min-w-44 whitespace-nowrap"
                          >Zf
                      </label>
                      <div class="mt-1 w-full">
                        <input
                          id="name"
                          name="name"
                          type="text"
                          autocomplete="name"
                          class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                          step="0.01"
                          @keyup.enter="focusNext($event)"
                          ref="input15"
                          @input="checkAmount($event.target.value, zf_fees)"
                          v-model="appointment.zf_fee"
                        />
                      </div>
                      <InputError
                          v-if="appointment?.errors?.zf_fee"
                          :message="appointment?.errors?.zf_fee"
                      />
                      </div>
                      <div class="flex items-center space-x-2 w-full">
                        <label
                          for="welfare"
                          class="block text-base font-medium leading-6 text-gray-900 min-w-44 whitespace-nowrap"
                          >Authorized</label
                        >
                        <div class="mt-1 w-full">
                          <input
                          id="welfare"
                          name="welfare"
                          type="text"
                          autocomplete="welfare"
                          class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                          v-model="appointment.welfare"
                          @keyup.enter="focusNext($event)"
                          ref="input16"
                          />
                        </div>
                        <InputError
                          v-if="appointment.errors?.welfare"
                          :message="appointment.errors?.welfare"
                        />
                      </div>
                      <div class="flex items-center space-x-2 w-full">
                        <label
                          for="payable"
                          class="block text-base font-medium leading-6 text-gray-900 min-w-44 whitespace-nowrap"
                          >Payable Amount</label
                        >
                        <div class="mt-1 w-full">
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
              </div>
                  <div class="flex justify-end gap-x-3 py-4">
                    <button
                      type="button"
                      class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                    >
                      Cancel
                    </button>
                    <button
                      type="submit"
                      class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                      :disabled="appointment.processing"
                    >
                      Save
                    </button>
                  </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>


  <script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, watchEffect, computed, onMounted } from "vue";
import Multiselect from "vue-multiselect";
import axios from "axios";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

const appointment_date = ref(null);


const props = defineProps({
  appointment: { type: Object, default: null },
  doctors: { type: Object, required: true },
  patients: { type: Object, required: true },
  careoffs: { type: Object, required: true },
  zf_fees: { type: String, required: true },
  mr_number: { type: String, required: true },
  patients: { type: Object, required: true },
  services: { type: Array, required: true, default: () => [] },
  countries: { type: Array, required: true, default: () => [] },
  states: { type: Array, required: false, default: () => [] },
  cities: { type: Array, required: false, default: () => [] },
  hasDonationModule: { type: Boolean, default: null },
});
const getCurrentDateInPakistanTimezone = () => {
  const options = { year: 'numeric', month: '2-digit', day: '2-digit', timeZone: 'Asia/Karachi' };
  const currentDate = new Date().toLocaleDateString('en-CA', options); // 'en-CA' formats the date as 'YYYY-MM-DD'
  return currentDate;
};

const appointment = useForm({
  id: props?.appointment?.id || null,
  patient_id: null,
  type: props?.appointment?.type || null,
  doc_serial: props?.appointment?.doc_serial || 0,
  consulting_doctor_id: props?.appointment?.consulting_doctor_id || null,
   appointment_date : props?.appointment?.appointment_date || getCurrentDateInPakistanTimezone(),
  //   appointment_date:
//     props?.appointment?.appointment_date ||
//     new Date().toISOString().slice(0, 10),
  appointment_time: props?.appointment?.appointment_time
    ? props?.appointment?.appointment_time.substring(0, 8)
    : `${new Date().getHours().toString().padStart(2, '0')}:${new Date().getMinutes().toString().padStart(2, '0')}:00`,
  patient_name: props?.appointment?.patient_name || null,
  mr_number: props?.mr_number || null,
  patient_cnic: props?.appointment?.patient_cnic || null,
  patient_age: props?.appointment?.patient_age || null,
  patient_dob: props?.appointment?.patient_dob || null,
  donor_fee: props?.appointment?.donor_fee || null,
  patient_gender: props?.appointment?.patient_gender || null,
  patient_relation_name: props?.appointment?.patient_relation_name || null,
  patient_relation_type: props?.appointment?.patient_relation_type || null,
  patient_address: props?.appointment?.patient_address || null,
  patient_state_id: props?.appointment?.patient_state_id || null,
  zf_fee: props?.appointment?.zf_fee || null,
  careoff_id: props?.appointment?.careoff_id || null,
  zf_id: props?.appointment?.zf_id || null,
  welfare: props?.appointment?.welfare || null,
  patient_country_id: props?.appointment?.patient_country_id || null,
  patient_phone: props?.appointment?.patient_phone || null,
  patient_email: props?.appointment?.patient_email || null,
  patient_blood_group: props?.appointment?.patient_blood_group || null,
  patient_rh_factor: props?.appointment?.patient_rh_factor || null,
  appointment_details: props?.appointment?.appointment_details || [
    {
      id: null,
      appointment_id: null,
      service: null,
      fee: null,
      fee_status: null,
    },
  ],
});


const doctors = ref(props.doctors || []);
const selectedDoctor = ref(null);
if (props?.appointment) {
  const doctor = props.doctors.find(
    (c) => c.id === props.appointment.consulting_doctor_id
  );
  if (doctor) {
    selectedDoctor.value = doctor;
  }
}

onMounted(() => {
  const formatDate = (date) => {
        if (!date) return new Date();
        const d = new Date(date);
        return `${d.getDate().toString().padStart(2, '0')}-${(d.getMonth() + 1)
            .toString().padStart(2, '0')}-${d.getFullYear()}`;
    };

  const flatpickrOptions = (defaultDate) => ({
        dateFormat: "d-m-Y", // Format: DD-MM-YYYY
        allowInput: true, // Allows manual typing
        clickOpens: true, // Ensures the picker still works
        defaultDate: formatDate(defaultDate) || new Date(), // Use provided default date or current date
    });

    flatpickr(appointment_date.value, flatpickrOptions(appointment.appointment_date));


  // appointment.mr_number = mr_number || "";
  });
const doctorOptions = computed(() => {
  return doctors.value && doctors.value.length ? doctors.value : [];
});

const onDoctorSelect = async (selectedDoctor) => {
  appointment.appointment_details[0].fee = selectedDoctor?.doctor_charges?.consulting_charges ?? 0;
  appointment.consulting_doctor_id = selectedDoctor.id;
  calculateAmount();
};

const patients = ref(props.patients || []);
const selectedPatient = ref(null);
// if (props?.appointment) {
//   const patient = props.patients.find(
//     (c) => c.id === props.appointment.patient_id
//   );
//   if (patient) {
//     selectedPatient.value = patient;
//   }
// }

const loadPatientData = async (patientPhone) => {
  try {
    const response = await axios.get(`/patients-phone/${patientPhone}`);
    let patient = response?.data?.patient || null;
    if (patient) {
        appointment.patient_name = patient?.name || null;
        appointment.patient_cnic = patient?.cnic || null;
        appointment.mr_number = patient?.mr_number || null;
        appointment.patient_age = patient?.age || null;
        appointment.patient_dob = patient?.dob || null;
        appointment.patient_gender = patient?.gender || null;
        appointment.patient_relation_name = patient?.relation_name || null;
        appointment.patient_relation_type = patient?.relation_type || null;
        appointment.patient_address = patient?.address || null;
        appointment.patient_state_id = patient?.state_id || null;
        appointment.zf_fee = patient?.zf_fee || null;
        appointment.careoff_id = patient?.careoff_id || null;
        appointment.zf_id = patient?.zf_id || null;
        appointment.welfare = patient?.welfare || null;
        appointment.patient_city_id = patient?.city_id || null;
        appointment.patient_country_id = patient?.country_id || null;
        appointment.patient_phone = patient?.phone || null;
        appointment.patient_email = patient?.email || null;
        appointment.patient_blood_group = patient?.blood_group || null;
        appointment.patient_rh_factor = patient?.rh_factor || null;
        selectedCountry.value = patient?.country || null;
        selectedState.value = patient?.country || null;

        if (patient?.country) {
          selectedCountry.value = patient?.country;
          await loadStates(patient?.country_id);
        }
        if (patient?.state) {
          selectedState.value = patient?.state;
          await loadCities(patient?.state_id);
        }
        if (patient?.city) {
          selectedCity.value = patient?.city;
        }

        if (patient?.blood_group) {
          selectedBloodGroup.value = bloodGroupOptions.value.find(
            (option) => option.type === patient.blood_group
          );
        }

        if (patient?.relation_type) {
          selectedRelationType.value = relationTypeOptions.value.find(
            (option) => option.value === patient?.relation_type
          );
        }

        if (patient?.gender) {
          selectedGender.value = genderOptions.value.find(
            (option) => option.value === patient?.gender
          );
        }
        
    }
  } catch (error) {
    console.error("Error fetching states:", error);
  }
};

const onPatientSelect = async (selectedPatient) => {
  appointment.patient_id = selectedPatient.id;
  await loadPatient(selectedPatient.id);
};

// Gender
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
if (props?.appointment?.patient_gender) {
  selectedGender.value = genderOptions.value.find(
    (option) => option.value === props.appointment.patient_gender
  );
}

// relation type
const selectedRelationType = ref();
const relationTypeOptions = computed(() => {
  return [
    { value: "Guardian", label: "Guardian" },
    { value: "Spouse", label: "Spouse" },
    { value: "Parent", label: "Parent" },
    { value: "Sibling", label: "Sibling" },
    { value: "Child", label: "Child" },
    { value: "Friend", label: "Friend" },
    { value: "Relative", label: "Relative" },
  ];
});


const selectedCareOff = ref(null);
const selectedZf = ref(null);

if(props?.hasDonationModule){
  const onCareOffSelect = async (selectedCareOffs) => {
    appointment.careoff_id = selectedCareOffs?.id ?? null;
    if(appointment.careoff_id == null){
      appointment.donor_fee = null;
      calculateAmount();
    }
  };



  const onZfSelect = async (selectedZf) => {
    appointment.zf_id = selectedZf.id;
    selectedCareOff.value = null;
    appointment.careoff_id = null;
    appointment.zf_fee = null;
  };



  const careoffs = ref(props.careoffs || []);
  if (props?.appointment) {
    const careoff = props.careoffs.find(
      (c) => c.id === parseInt(props.appointment.careoff_id)
    );
    if (careoff) {
      selectedCareOff.value = careoff;
    }
  }

  const zf_fees = ref(props.zf_fees || []);
}


const onRelationTypeSelect = async (selectedRelationType) => {
  appointment.patient_relation_type = selectedRelationType.value;
};
if (props?.appointment?.patient_relation_type) {
  selectedRelationType.value = relationTypeOptions.value.find(
    (option) => option.value === props.appointment.patient_relation_type
  );
}


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
if (props?.appointment) {
  calculateAmount();
}

const countries = ref(props.countries || []);

const selectedCountry = ref(null);
if (props?.appointment) {
  const country = props.countries.find(
    (c) => c.id == props.appointment.patient_country_id
  );
  if (country) {
    selectedCountry.value = country;
  }
}

const countryOptions = computed(() => {
  return countries.value && countries.value.length ? countries.value : [];
});

const onCountrySelect = async (selectedCountry) => {
  appointment.patient_country_id = selectedCountry.id;
  selectedState.value = null;
  appointment.patient_state_id = null;
  states.value = [];
  selectedCity.value = null;
  appointment.patient_city_id = null;
  cities.value = [];
  await loadStates(selectedCountry.id);
};

const loadStates = async (countryId) => {
  try {
    const response = await axios.get(`/states/${countryId}`);
    states.value = response.data.states;
  } catch (error) {
    console.error("Error fetching states:", error);
  }
};

const states = ref(props.states || []);
const selectedState = ref(null);
if (props?.appointment) {
  const state = props.states.find(
    (s) => s.id == props.appointment.patient_state_id
  );
  if (state) {
    selectedState.value = state;
  }
}

const stateOptions = computed(() => {
  return states.value && states.value.length ? states.value : [];
});

const onStateSelect = async (selectedState) => {
  appointment.patient_state_id = selectedState.id;
  selectedCity.value = null;
  appointment.patient_city_id = null;
  cities.value = [];
  await loadCities(selectedState.id);
};

const loadCities = async (stateId) => {
  try {
    const response = await axios.get(`/cities-by-state/${stateId}`);
    cities.value = response.data.cities;
  } catch (error) {
    console.error("Error fetching cities:", error);
  }
};

const cities = ref(props.cities || []);
const selectedCity = ref(null);
if (props?.appointment) {
  const city = props.cities.find(
    (c) => c.id == props.appointment.patient_city_id
  );
  if (city) {
    selectedCity.value = city;
  }
}

const cityOptions = computed(() => {
  return cities.value && cities.value.length ? cities.value : [];
});

const onCitySelect = (selectedCity) => {
  appointment.patient_city_id = selectedCity.id;
};

const bloodGroupOptions = computed(() => {
  const bloodGroup = [
    { type: "A+" },
    { type: "A-" },
    { type: "B+" },
    { type: "B-" },
    { type: "AB+" },
    { type: "AB-" },
    { type: "O+" },
    { type: "O-" },
  ];
  return bloodGroup;
});

const selectedBloodGroup = ref(null);
if (props?.appointment?.patient_blood_group) {
  selectedBloodGroup.value = bloodGroupOptions.value.find(
    (option) => option.type === props.appointment.patient_blood_group
  );
}
const onBloodGroupSelect = async (selectedBloodGroup) => {
  appointment.patient_blood_group = selectedBloodGroup.type;
};

const typeOptions = computed(() => {
  return [
    { value: "General", label: "General" },
    { value: "Specialist", label: "Specialist" },
  ];
});

const selectedType = ref(null);
if (props?.appointment?.type) {
  selectedType.value = typeOptions.value.find(
    (option) => option.value == props.appointment.type
  );
}
const onTypeSelect = async (selectedType) => {
  appointment.type = selectedType.value;
};

//appointment_detail
const addAppointmentDetail = () => {
  appointment.appointment_details.push({
    id: null,
    appointment_id: null,
    service: null,
    fee: null,
    fee_status: null,
  });
};

const removeAppointmentDetail = (index) => {
  appointment.appointment_details.splice(index, 1);
};

const feeStatusOptions = computed(() => {
  return [
    { value: "Pending", label: "Pending" },
    { value: "Complete", label: "Complete" },
  ];
});

// if (props?.appointment?.appointment_details) {
//   props?.appointment?.appointment_details.map((appointment_details) => {
//     if (appointment_details?.fee_status) {
//       appointment_details.fee_status = feeStatusOptions.value.find(
//         (option) => option.value == appointment_details?.fee_status
//       );
//     }
//   });
// }

const loadPatient = async (patientId) => {
  try {
    const response = await axios.get(`/patients/${patientId}`);
    let patient = response?.data?.patient || null;
    if (patient) {
      appointment.patient_name = patient?.name || null;
      mr_number = patient?.mr_number || null;
      appointment.patient_cnic = patient?.cnic || null;
      appointment.patient_age = patient?.age || null;
      appointment.patient_dob = patient?.dob || null;
      appointment.patient_gender = patient?.gender || null;
      appointment.patient_relation_name = patient?.relation_name || null;
      appointment.patient_relation_type = patient?.relation_type || null;
      appointment.patient_address = patient?.address || null;
      appointment.patient_state_id = patient?.state_id || null;
      appointment.zf_fee = patient?.zf_fee || null;
      appointment.careoff_id = patient?.careoff_id || null;
      appointment.zf_id = patient?.zf_id || null;
      appointment.welfare = patient?.welfare || null;
      appointment.patient_city_id = patient?.city_id || null;
      appointment.patient_country_id = patient?.country_id || null;
      appointment.patient_phone = patient?.phone || null;
      appointment.patient_email = patient?.email || null;
      appointment.patient_blood_group = patient?.blood_group || null;
      appointment.patient_rh_factor = patient?.rh_factor || null;
      selectedCountry.value = patient?.country || null;
      selectedState.value = patient?.country || null;

      if (patient?.country) {
        selectedCountry.value = patient?.country;
        await loadStates(patient?.country_id);
      }
      if (patient?.state) {
        selectedState.value = patient?.state;
        await loadCities(patient?.state_id);
      }
      if (patient?.city) {
        selectedCity.value = patient?.city;
      }

      if (patient?.blood_group) {
        selectedBloodGroup.value = bloodGroupOptions.value.find(
          (option) => option.type === patient.blood_group
        );
      }

      if (patient?.relation_type) {
        selectedRelationType.value = relationTypeOptions.value.find(
          (option) => option.value === patient?.relation_type
        );
      }

      if (patient?.gender) {
        selectedGender.value = genderOptions.value.find(
          (option) => option.value === patient?.gender
        );
      }
    }
  } catch (error) {
    console.error("Error fetching states:", error);
  }
};


const checkAmount = (val, zf_fees) => {
  // if(val > zf_fees && appointment.type == 'General'){
  //   appointment.zf_fee = zf_fees;
  // }
  // if(val > zf_fees && appointment.type == 'Specialist'){
  //   appointment.zf_fee = zf_fees;
  // }
  calculateAmount();

};

const focusNext = (event) => {
  const currentInput = event.target;
  const inputs = Array.from(document.querySelectorAll('input'));
  const currentIndex = inputs.indexOf(currentInput);
  const nextIndex = (currentIndex + 1) % inputs.length;
  inputs[nextIndex].focus();
}

</script>

  <script>
import { defineComponent } from "vue";
import InputError from "../../Components/InputError.vue";
export default defineComponent({
  components: {
    Multiselect,
    InputError,
  },
});
</script>

  <style src="vue-multiselect/dist/vue-multiselect.css"></style>
