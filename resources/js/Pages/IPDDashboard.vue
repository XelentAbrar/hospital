<template>
  <div class="w-full mx-auto sm:px-6 lg:px-8">

      <div
        class="grid grid-cols-12 gap-4 mb-6"
      >
        <div class="md:col-span-12 col-span-12">
            <div class="grid grid-cols-12 gap-4 mb-6 bg-white py-4 px-6 rounded-lg shadow-md">
                <div class="col-span-12">
                    <form

                    enctype="multipart/form-data"
                    >
                    <!-- Profile section -->
                    <div class="py-6">
                        <div>
                        <h2 class="text-2xl font-semibold text-gray-900">
                            Admission
                        </h2>
                        <p class="mt-1 text-sm text-gray-900">
                            This information will be displayed publicly so be careful
                            what you share.
                        </p>
                        </div>

                        <div class="py-6">
                        <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-4">
                           <div class="">
                                <label
                                    for="mr_number"
                                    class="block text-md font-semibold leading-6 text-gray-900"
                                    >Mr Number.
                                </label>
                                <div class="mt-2">
                                    <input
                                    id="mr_number"
                                    name="mr_number"
                                    type="text"
                                    autocomplete="mr_number"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    step="0.01"
                                    @input="generateMRNumber"
                                    @blur="loadPatient($event.target.value)"
                                    :value="admission.mr_number"
                                    />
                                </div>

                                </div>
                                <div class="">
                                <label
                                    for="phone"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Phone</label
                                >
                                <div class="mt-2">
                                    <input
                                    id="phone"
                                    name="phone"
                                    type="text"
                                    autocomplete="phone"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    v-model="admission.phone"
                                    @blur="loadPatient($event.target.value)"
                                    @input="formatPhone" maxlength="12"

                                    />
                                </div>
                                <InputError
                                    v-if="admission.errors?.phone"
                                    :message="admission.errors?.phone"
                                />
                                </div>

                                <div class="">
                                <label
                                    for="name"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Name
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-2">
                                    <input
                                    id="name"
                                    name="name"
                                    type="text"
                                    autocomplete="name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    step="0.01"
                                    v-model="admission.name"
                                    />
                                </div>
                                <InputError
                                    v-if="admission.errors?.name"
                                    :message="admission.errors?.name"
                                />
                                </div>
                            <div class="">
                                <label
                                    for="file_no"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >File NO.
                                    <!-- <span class="text-red-500">*</span> -->
                                </label>
                                <div class="mt-2">
                                    <input
                                    id="file_no"
                                    name="file_no"
                                    type="text"
                                    autocomplete="name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    step="0.01"
                                    disabled
                                    :value="file_no"
                                    />
                                </div>
                                <InputError
                                    v-if="admission.errors?.file_no"
                                    :message="admission.errors?.file_no"
                                />
                                </div>


                                <div class="">
                                <label
                                    for="admission_date"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Admission Date
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-2">
                                    <input
                                    id="admission_date"
                                    name="admission_date"
                                    type="date"
                                    autocomplete="name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    step="0.01"
                                    v-model="admission.admission_date"
                                    />
                                </div>
                                <InputError
                                    v-if="admission.errors?.admission_date"
                                    :message="admission.errors?.admission_date"
                                />
                                </div>
                                <div class="">
                                <label
                                    for="admission_time"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Admission Time
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-2">
                                    <input
                                    id="admission_time"
                                    name="admission_time"
                                    type="time"
                                    autocomplete="name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    step="0.01"
                                    v-model="admission.admission_time"
                                    />
                                </div>
                                <InputError
                                    v-if="admission.errors?.admission_time"
                                    :message="admission.errors?.admission_time"
                                />
                                </div>
                            <div class="">
                                <label
                                    for="cnic"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >CNIC
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="mt-2">
                                    <input
                                    id="cnic"
                                    name="cnic"
                                    type="text"
                                    autocomplete="cnic"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    step="0.01"
                                    @blur="loadPatient($event.target.value)"
                                    v-model="admission.cnic"
                                    @input="formatCNIC" maxlength="15"

                                    />
                                </div>
                                <InputError
                                    v-if="admission.errors?.cnic"
                                    :message="admission.errors?.cnic"
                                />
                            </div>
                            <div>
                    <label
                        for="age"
                        class="block text-base font-medium leading-6 text-gray-900"
                        >Age</label
                    >
                        <input
                        id="age"
                        name="age"
                        type="text"
                        autocomplete="age"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="admission.age"
                        />
                    <InputError
                        v-if="admission.errors?.age"
                        :message="admission.errors?.age"
                    />
                  </div>

                            <div class="">
                                <label
                                    for="address"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Address</label
                                >
                                <div class="mt-2">
                                    <input
                                    id="address"
                                    name="address"
                                    type="text"
                                    autocomplete="address"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    v-model="admission.address"
                                    />
                                </div>
                                <InputError
                                    v-if="admission.errors?.address"
                                    :message="admission.errors?.address"
                                />
                                </div>
                            <div class="">
                                <label
                                    for="relation_name"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >	S/F/W/D</label
                                >
                                <div class="mt-2">
                                    <input
                                    id="relation_name"
                                    name="relation_name"
                                    type="text"
                                    autocomplete="relation_name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    v-model="admission.relation_name"
                                    />
                                </div>
                                <InputError
                                    v-if="admission.errors?.relation_name"
                                    :message="admission.errors?.relation_name"
                                />
                                </div>
                            <div>
                            <label
                                for="gender"
                                class="block text-sm font-medium leading-6 text-gray-900"
                                >Gender</label
                            >
                            <div class="mt-2 flex rounded-md shadow-sm">
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
                                v-if="admission.errors?.gender"
                                :message="admission.errors?.gender"
                            />
                            </div>

                            <div>
                            <label
                                for="consulting_doctor_id"
                                class="block text-sm font-medium leading-6 text-gray-900"
                                >Department
                            </label>
                            <div class="mt-1">
                            <multiselect
                                v-model="selectedDepartment"
                                :options="departments"
                                @update:modelValue="onDepartmentSelect"
                                label="name"
                                track-by="id"
                                placeholder="Select a Department"
                            ></multiselect>
                            </div>
                            <InputError
                                v-if="admission?.errors?.department_id"
                                :message="admission?.errors?.department_id"
                            />
                            </div>


                            <div>
                            <label
                                for="status"
                                class="block text-sm font-medium leading-6 text-gray-900"
                                >Type
                                <span class="text-red-500">*</span>
                              </label>
                            <div class="mt-2 flex rounded-md shadow-sm">
                                <multiselect
                                v-model="selectedRoomType"
                                :options="room_types"
                                @update:modelValue="onRoomTypeSelect"
                                label="name"
                                track-by="id"
                                placeholder="Select a Room Type"
                              ></multiselect>
                            </div>
                            <InputError
                                v-if="admission.errors?.type"
                                :message="admission.errors?.type"
                            />
                            </div>


                            <div>
                            <label
                                for="consulting_doctor_id"
                                class="block text-sm font-medium leading-6 text-gray-900"
                                >Room
                            </label>
                            <div class="mt-1">
                            <multiselect
                                v-model="selectedRoom"
                                :options="rooms.filter(res => {return res.room_type_id == admission?.type})"
                                @update:modelValue="onRoomSelect"
                                label="room_number"
                                track-by="id"
                                placeholder="Select a Room"
                            ></multiselect>
                            </div>
                            <InputError
                                v-if="admission?.errors?.room_bed_id"
                                :message="admission?.errors?.room_bed_id"
                            />
                            </div>

                            <div>
                            <label
                                for="consulting_doctor_id"
                                class="block text-sm font-medium leading-6 text-gray-900"
                                >Bed No.
                            </label>
                            <div class="mt-1">
                            <multiselect
                                v-model="selectedBed"
                                :options="beds.filter(res => {return res.room_id == admission?.room_bed_id})"
                                @update:modelValue="onBedSelect"
                                label="bed_number"
                                track-by="id"
                                placeholder="Select a Bed"
                            ></multiselect>
                            </div>
                            <InputError
                                v-if="admission?.errors?.bed_id"
                                :message="admission?.errors?.bed_id"
                            />
                            </div>


                            <div class="">
                                <label
                                    for="refered_by"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Refered By</label
                                >
                                <div class="mt-2">
                                    <input
                                    id="refered_by"
                                    name="refered_by"
                                    type="text"
                                    autocomplete="refered_by"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    v-model="admission.refered_by"
                                    />
                                </div>
                                <InputError
                                    v-if="admission.errors?.refered_by"
                                    :message="admission.errors?.refered_by"
                                />
                                </div>
                                <div class="">
                                <label
                                    for="discharge_date"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Discharge Date
                                </label>
                                <div class="mt-2">
                                    <input
                                    id="discharge_date"
                                    name="discharge_date"
                                    type="date"
                                    autocomplete="name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    step="0.01"
                                    v-model="admission.discharge_date"
                                    />
                                </div>
                                <InputError
                                    v-if="admission.errors?.discharge_date"
                                    :message="admission.errors?.discharge_date"
                                />
                                </div>
                                <div class="">
                                <label
                                    for="chart_no"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Chart No
                                </label>
                                <div class="mt-2">
                                    <input
                                    id="chart_no"
                                    name="chart_no"
                                    type="text"
                                    autocomplete="name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    step="0.01"
                                    v-model="admission.chart_no"
                                    />
                                </div>
                                <InputError
                                    v-if="admission.errors?.chart_no"
                                    :message="admission.errors?.chart_no"
                                />
                                </div>
                        </div>
                        </div>

                <div
                class="divide-y divide-gray-200 pt-4 pb-4"
              >
                <div class="border shadow-md rounded-lg border-gray-200 p-6 mb-6 bg-yellow-50">
                  <h3 class="text-2xl font-semibold text-gray-900 pb-3">
                    Service Detail
                  </h3>
                  <InputError
                    v-if="admission.errors?.[`details`]"
                    :message="admission.errors?.[`details`]"
                  />
                  <div class="border rounded mb-2">
                    <div class="flex divide-x border-b">
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="card-cvc" class="text-center">Date</label>
                      </div>
                      <div class="min-w-0 w-14 p-1 flex items-center justify-center">
                        <label for="card-cvc" class="text-center">Doctor</label>
                      </div>
                      <div class="w-56 min-w-0 p-1 flex items-center justify-center">
                        <label for="card-cvc" class="text-center" >Service</label>
                      </div>
                      <div class="min-w-0 w-[8.5rem] p-1 flex items-center justify-center">
                        <label for="card-cvc" class="text-center">Amount</label>
                      </div>
                      <div class="min-w-0 w-28 p-1 flex items-center justify-center">
                        <label for="card-cvc" class="text-center">No.</label>
                      </div>
                      <div class="min-w-0 w-28 p-1 flex items-center justify-center">
                        <label for="card-cvc" class="text-center">Days Amount</label>
                      </div>
                      <div class="min-w-0 w-28 p-1 flex items-center justify-center">
                        <label for="card-cvc" class="text-center">Total</label>
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="card-cvc"  class="text-center">Description</label>
                      </div>
                      <div class="flex items-center p-1 w-[49px]"></div>
                    </div>
                  <div
                    v-for="(
                      detail, index
                    ) in admission.details"
                    :key="index"
                  >
                    <div class="flex divide-x border-b">

                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <div class="w-full">
                        <label for="card-cvc" class="sr-only">Date</label>
                        <input
                          v-model="detail.date"
                          type="date"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Date"
                        />
                        <InputError
                          v-if="
                            admission.errors?.[
                              `details.${index}.date`
                            ]
                          "
                          :message="
                            admission.errors?.[
                              `details.${index}.date`
                            ]
                          "
                        />
                        </div>
                      </div>
                      <div class="min-w-0 w-14 p-1 flex items-center justify-center">
                        <input
                            type="checkbox"
                            class="mx-auto"
                             :checked="detail.is_doctor == '1'"
                           @click="(event) => detail.is_doctor = event.target.checked == true ? '1' : '0'"
                        />
                        </div>
                      <div class="min-w-0 w-56 p-1 flex items-center justify-center" v-if="detail.is_doctor == '1'">
                        <div class="w-full">
                        <label
                           for="card-cvc" class="sr-only"
                          >Doctos
                        </label>
                        <div >
                          <multiselect
                            v-model="detail.doctor"
                            :options="doctors"
                            @update:modelValue="onDoctorSelect($event, index)"
                            label="name"
                            track-by="id"
                            placeholder="Select a Doctor"
                          ></multiselect>
                        </div>
                        <InputError
                          v-if="
                            admission.errors?.[
                              `details.${index}.doctor.id`
                            ]
                          "
                          :message="
                            admission.errors?.[
                              `details.${index}.doctor.id`
                            ]
                          "
                        />
                      </div>
                      </div>
                      <div class="min-w-0 w-[8.5rem] p-1 flex items-center justify-center" v-if="detail.is_doctor == '1'">
                        <div class="w-full">
                        <label for="card-cvc" class="sr-only">Doctor Amount</label>
                        <input
                          v-model="detail.amount"
                          type="number"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Amount"
                          @input="calculateAmount()"
                          :disabled="!detail.doctor"
                        />
                        <InputError
                          v-if="
                            admission.errors?.[
                              `details.${index}.amount`
                            ]
                          "
                          :message="
                            admission.errors?.[
                              `details.${index}.amount`
                            ]
                          "
                        />
                      </div>
                      </div>
                      <div class="min-w-0 w-56 p-1 flex items-center justify-center" v-if="detail.is_doctor == '0'">
                        <div class="w-full">
                        <label
                           for="card-cvc" class="sr-only"
                          >Service
                        </label>
                        <div >
                          <multiselect
                            v-model="detail.service"
                            :options="services"
                            label="name"
                            track-by="id"
                            placeholder="Select a Service"
                            @update:modelValue="onServiceSelect($event, index)"
                          ></multiselect>
                        </div>
                        <InputError
                          v-if="
                            admission.errors?.[
                              `details.${index}.service.id`
                            ]
                          "
                          :message="
                            admission.errors?.[
                              `details.${index}.service.id`
                            ]
                          "
                        />
                      </div>
                      </div>
                      <div class="min-w-0 w-[8.5rem] p-1 flex items-center justify-center" v-if="detail.is_doctor == '0'">
                        <div class="w-full">
                        <label for="card-cvc" class="sr-only">Service Amount</label>
                        <input
                          v-model="detail.service_amount"
                          type="number"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Service Amount"
                          @input="calculateAmount()"
                          :disabled="!detail.service"
                        />
                        <InputError
                          v-if="
                            admission.errors?.[
                              `details.${index}.service_amount`
                            ]
                          "
                          :message="
                            admission.errors?.[
                              `details.${index}.service_amount`
                            ]
                          "
                        />
                      </div>
                      </div>
                      <div class="min-w-0 w-28 p-1 flex items-center justify-center">
                        <div class="w-full">
                        <label for="card-cvc" class="sr-only">No. of Days</label>
                        <input
                          v-model="detail.no_of_days"
                          type="number"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="No. of Days"
                          @input="calculateAmount()"
                        />
                        <InputError
                          v-if="
                            admission.errors?.[
                              `details.${index}.no_of_days`
                            ]
                          "
                          :message="
                            admission.errors?.[
                              `details.${index}.no_of_days`
                            ]
                          "
                        />
                      </div>
                      </div>
                      <div class="min-w-0 w-28 p-1 flex items-center justify-center">
                          {{detail.day_charges}}
                      </div>
                      <div class="min-w-0 w-28 p-1 flex items-center justify-center">
                        {{detail.day_charges}}
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <div class="w-full">
                        <label for="card-cvc" class="sr-only"
                          >Description</label
                        >
                        <input
                          v-model="detail.desc"
                          type="text"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="description"
                        />
                        <InputError
                          v-if="
                            admission.errors?.[
                              `details.${index}.desc`
                            ]
                          "
                          :message="
                            admission.errors?.[
                              `details.${index}.desc`
                            ]
                          "
                        />
                      </div>
                      </div>
                      <div class="flex items-center p-1">
                        <button
                          type="button"
                          @click="removeDetail(index)"
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
                    </div>
                  </div>
                  </div>
                  <button
                    type="button"
                    @click="addDetail"
                    class="inline-flex items-center mt-1 px-3 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
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
                  </button>
                </div>
              </div>
            <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-5">
                <div class="col-span-3"></div>
                <div class="col-span-2 space-y-2">
                  <div class="flex items-center space-x-2 w-full">
                    <label
                        for="status"
                        class="text-base md:text-lg font-medium text-gray-900 whitespace-nowrap min-w-44 whitespace-nowrap"
                        >Status
                        <span class="text-red-500">*</span>
                      </label
                    >
                    <div class="w-full flex rounded-md shadow-sm">
                        <multiselect
                        class="w-full"
                        v-model="selectedStatus"
                        :options="statusOptions"
                        @select="onStatusSelect"
                        label="label"
                        track-by="value"
                        placeholder="Select Status"
                        ></multiselect>
                    </div>
                    <InputError
                        v-if="admission.errors?.status"
                        :message="admission.errors?.status"
                    />
                    </div>
                <div class="flex items-center space-x-2 w-full">
                    <label
                        for="payable_amount"
                        class="block text-sm md:text-base font-medium text-gray-900 whitespace-nowrap min-w-44"
                        >Payable amount</label
                    >
                        <input
                        id="payable_amount"
                        name="payable_amount"
                        type="text"
                        autocomplete="payable_amount"
                        class="mt-1 block w-full rounded border-0 py-2 text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11 bg-blue-500"
                        :value="payableAmount"
                        tabindex="-1"
                        disabled="disabled"
                        />
                </div>
                <div class="flex items-center space-x-2 w-full">
                    <label
                        for="advance_amount"
                        class="block text-sm md:text-base font-medium text-gray-900 whitespace-nowrap min-w-44"
                        >Advance amount</label
                    >
                        <input
                        id="advance_amount"
                        name="advance_amount"
                        type="text"
                        autocomplete="advance_amount"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        :value="admission.advance_amount"
                        disabled
                        />
                        <InputError
                            v-if="admission.errors?.advance_amount"
                            :message="admission.errors?.advance_amount"
                        />
                </div>
                <!-- <div class="flex items-center space-x-2 w-full">
                    <label
                        for="advance_amount"
                        class="block text-sm md:text-base font-medium text-gray-900 whitespace-nowrap min-w-44"
                        >Received amount</label
                    >
                        <input
                        id="received_amount"
                        name="received_amount"
                        type="text"
                        autocomplete="received_amount"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="admission.received_amount"
                        @input="calculateAmount()"
                        />
                        <InputError
                            v-if="admission.errors?.received_amount"
                            :message="admission.errors?.received_amount"
                        />
                </div> -->
                <!-- <div class="flex items-center space-x-2 w-full">
                    <label
                        for="discount_amount"
                        class="block text-sm md:text-base font-medium text-gray-900 whitespace-nowrap min-w-44"
                        >S.D</label
                    >
                        <input
                        id="discount_amount"
                        name="discount_amount"
                        type="text"
                        autocomplete="discount_amount"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="admission.discount_amount"
                        @input="calculateAmount()"
                        />
                        <InputError
                            v-if="admission.errors?.discount_amount"
                            :message="admission.errors?.discount_amount"
                        />
                </div> -->


                <div class="flex items-center space-x-2 w-full" v-if="hasDonationModule">
                    <label
                        for="careoff_id"
                        class="block text-sm md:text-base font-medium text-gray-900 whitespace-nowrap min-w-44"
                        >Care Off

                    </label>
                    <div class="w-full">
                    <multiselect
                        class="w-full"
                        v-model="selectedCareOff"
                        :options="careoffs"
                        @update:modelValue="onCareOffSelect(selectedCareOff)"
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
                      v-model="admission.donor_fee"
                      v-if="admission.careoff_id != null && admission.careoff_id != ''"
                      />
                    </div>
                    <InputError
                        v-if="admission?.errors?.careoff_id"
                        :message="admission?.errors?.careoff_id"
                    />
                    </div>

                    <!-- <div class="flex items-center space-x-2 w-full">
                      <label
                          for="donor_id"
                          class="block text-sm md:text-base font-medium text-gray-900 whitespace-nowrap min-w-44"
                          >Zf

                      </label>
                      <input
                          id="name"
                          name="name"
                          type="text"
                          autocomplete="name"
                          class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                          step="0.01"
                          @input="checkAmount($event.target.value, zf_fees)"
                          v-model="admission.zf_fee"
                          />
                      <InputError
                          v-if="admission?.errors?.zf_fee"
                          :message="admission?.errors?.zf_fee"
                      />
                      </div> -->
                      <div class="flex items-center space-x-2 w-full">
                    <label
                        for="total_amount"
                        class="block text-sm md:text-base font-medium text-gray-900 whitespace-nowrap min-w-44"
                        >Reamaning Amount</label
                    >
                        <input
                        id="total_amount"
                        name="total_amount"
                        type="text"
                        autocomplete="total_amount"
                        class="mt-1 block w-full rounded border-0 py- shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11 "
                        :class="admission.total_amount < 0 ? 'bg-red-500 text-white' : 'bg-green-500 text-white'"
                        :value="admission.total_amount"
                        tabindex="-1"
                        disabled="disabled"
                        />
                </div>
                      <div class="flex items-center space-x-2 w-full">
                        <label
                            for="welfare"
                            class="block text-sm md:text-base font-medium text-gray-900 whitespace-nowrap min-w-44"
                            >Authorized</label
                        >
                            <input
                            id="welfare"
                            name="welfare"
                            type="text"
                            autocomplete="welfare"
                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                            v-model="admission.welfare"
                            />
                        <InputError
                            v-if="admission.errors?.welfare"
                            :message="admission.errors?.welfare"
                        />
                        </div>

            </div>
            </div>

                <div class="divide-y divide-gray-200">
                  <div class="flex justify-end gap-x-3 px-4 mt-4 sm:px-6">
                    <button
                      type="submit" @click="submitForm()"  :disabled="admission.processing"
                      class="rounded bg-primary px-5 py-2 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
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
import { Head, useForm , router } from "@inertiajs/vue3";
import { ref, onMounted ,watchEffect, computed ,watch ,nextTick} from "vue";
import Multiselect from "vue-multiselect";
import axios from "axios";
const displayForm = ref('');
const getCurrentDateInPakistanTimezone = () => {
  const options = { year: 'numeric', month: '2-digit', day: '2-digit', timeZone: 'Asia/Karachi' };
  const currentDate = new Date().toLocaleDateString('en-CA', options);
  return currentDate;
};
const props = defineProps({
  hasDonationModule:Boolean,
    file_no:Object,
    services:Object,
    departments:Object,
    doctors:Object,
    rooms:Object,
    beds:Object,
    room_types:Object,
    zf_fees:Object,
    mr_number:String,
    careoffs:Object,
});
// defineProps({
//   file_no:Object,
//     services:Object,
//     departments:Object,
//     doctors:Object,
//     rooms:Object,
//     beds:Object,
//     room_types:Object,
//     zf_fees:Object,
//     mr_number:Object,
//     careoffs:Object,
// });
const admission = useForm({
  id: null,
  source:'dashboard',
  admission_date :  getCurrentDateInPakistanTimezone(),
//   admission_date: new Date().toISOString().slice(0, 10),
  admission_time: new Date().toTimeString().slice(0, 8),
  discharge_date: null,
  chart_no: null,
  name: null,
  age: null,
  file_no: null,
  room_bed_id: null,
  bed_id: null,
  cnic: null,
  address: null,
  mr_number: props.mr_number || "",
  relation_name: null,
  dob: null,
  phone: null,
  gender: null,
  status: 'Pending',
  department_id: null,
  type: null,
  refered_by: null,
  advance_amount: null,
  received_amount: null,
  discount_amount: null,
  donor_fee: null,
  careoff_id: null,
  zf_fee: null,
  total_amount: null,
  patient_id: null,
  welfare: null,
  details: [
    {
      id: null,
      date: null,
      service: null,
      doctor: null,
      amount: null,
      service_amount: null,
      no_of_days: null,
      is_doctor: 0,
      day_charges: null,
      desc: null,
    },
  ],
});
const generateMRNumber = (event) => {
  let input = event.target.value.replace(/\D/g, "");
  let number = input ? parseInt(input) : 1;
  let formattedNumber = number.toString().padStart(5, '0');
  admission.mr_number = formattedNumber;
};

const formatCNIC = (event) => {
  let input = event.target.value.replace(/\D/g, "");
  if (input.length > 5) {
    input = input.replace(/^(\d{5})(\d{7})(\d{1})$/, "$1-$2-$3");
  }
  admission.cnic = input;
};
const formatPhone = (event) => {
  let input = event.target.value.replace(/\D/g, "");
  if (input.length > 4) {
    input = input.replace(/^(\d{4})(\d+)/, "$1-$2");
  }
  admission.phone = input;
};
const selectedDepartment = ref(null);
const onDepartmentSelect = async (selectedDepartment) => {
  admission.department_id = selectedDepartment?.id ?? null;
};

const selectedRoomType = ref(null);
const onRoomTypeSelect = async (selectedRoomType) => {
  admission.type = selectedRoomType?.id ?? null;
};

const selectedRoom = ref(null);
const room_charges = ref(null);
const onRoomSelect = async (selectedRoom) => {
  admission.room_bed_id = selectedRoom?.id ?? null;
  room_charges.value = selectedRoom?.charges ?? null;
  calculateAmount();
};

const selectedBed = ref(null);
const onBedSelect = async (selectedBed) => {
  admission.bed_id = selectedBed?.id ?? null;
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
  admission.gender = selectedGender.value;
};


const selectedStatus = ref({ value: "Pending", label: "Pending" });
const statusOptions = computed(() => {
  return [
    { value: "Pending", label: "Pending" },
    { value: "Complete", label: "Complete" },
  ];
});

const onStatusSelect = async (selectedGender) => {
  admission.status = selectedGender.value;
};

//General test detail
const addDetail = () => {
    const today = new Date().toISOString().slice(0, 10);
  admission.details.push({
    id: null,
    date: today,
    service: null,
    doctor: null,
    is_doctor: 0,
    amount: null,
    service_amount: null,
    no_of_days: null,
    day_charges: null,
    desc: null,
  });
};

const removeDetail = (index) => {
  admission.details.splice(index, 1);
};

const onDoctorSelect = (val, index) => {
  admission.details[index].amount = val?.doctor_charges?.visiting_charges ?? 0;
  calculateAmount();
};

const onServiceSelect = (val, index) => {
    console.log('val',val);
  admission.details[index].service_amount = val?.charges ?? 0;
  admission.details[index].is_nursing = val?.is_nursing ?? '0';
  admission.details[index].is_advance = val?.is_advance ?? '0';
  calculateAmount();
};


const payableAmount = ref();
const calculateAmount = () => {
  let sum = 0;

  let advance_amount = 0;
  admission.details.forEach((detail) => {
    let amount = 0;

        if(detail.amount > 0){
            detail.day_charges = (detail.no_of_days > 0 ? detail.no_of_days : 1) * (detail.amount || 0);
            amount = parseFloat((detail.no_of_days > 0 ? detail.no_of_days : 1) * (detail.amount || 0));
        }
        else if(detail.service_amount > 0){
            detail.day_charges = (detail.no_of_days > 0 ? detail.no_of_days : 1) * (detail.service_amount || 0);
            amount = parseFloat((detail.no_of_days > 0 ? detail.no_of_days : 1) * (detail.service_amount || 0));
        }
        else if (detail.no_of_days !== null) {
            detail.day_charges = detail.no_of_days * room_charges.value;
            amount += parseFloat(detail.day_charges || 0);
        }

        if(detail?.service?.is_advance == '1'){
            advance_amount = +advance_amount + +detail.day_charges;
        }

        sum += amount;
    });

    admission.advance_amount = advance_amount;

    payableAmount.value = +sum - +admission.advance_amount;
    calculateRemaingAmount();
};

// const calculateAmount = () => {
//   let sum = 0;
//   admission.details.forEach((detail) => {
//     let amount = 0;
//     if (detail.is_doctor == '1') {
//      amount = parseFloat(detail.amount || 0);
//     } else {
//       amount = parseFloat(detail.service_amount || 0);

//       if (detail.no_of_days !== null) {
//         if(room_charges?.value !== null && detail.is_nursing == '0' ){
//           const dayCharges = detail.no_of_days * (room_charges?.value || 0);
//           amount += dayCharges;

//         }
//         if (detail.is_nursing == '1') {
//           amount = parseFloat(detail.no_of_days * detail.service_amount || 0);
//         }
//       }
//     }

//     sum += amount + parseFloat(detail.amount || 0);
//   });

//   payableAmount.value = sum;
//   calculateRemaingAmount();
// };
// const calculateAmount = () => {
//   let sum = 0;
//   admission.details.forEach((detail) => {
//     sum += parseFloat(detail.amount || 0);
//     sum += parseFloat(detail.service_amount || 0);
//     if (room_charges.value !== null) {
//       if (detail.no_of_days !== null) {
//         detail.day_charges = detail.no_of_days * room_charges.value;
//         sum += parseFloat(detail.day_charges || 0);
//       } else {
//          detail.day_charges = null;
//       }
//     } else {
//       detail.day_charges = null;
//     }
//   });
//   payableAmount.value = sum;
//   calculateRemaingAmount();
// };
watch(() => admission.details, (newDetails) => {
  nextTick(() => {
    newDetails.forEach((detail, index) => {
      watch(() => detail.is_doctor, (newVal) => {
        if (newVal) {
          detail.service = null;
          detail.service_amount = 0;
          detail.doctor = null;
          detail.amount = 0;
        } else {
          detail.doctor = null;
          detail.amount = 0;
          detail.service = null;
          detail.service_amount = 0;
        }
        calculateAmount();
      });
    });
  });
}, { deep: true });
const total_amount = ref();

const calculateRemaingAmount = () => {
  admission.total_amount = +payableAmount.value - +admission.advance_amount - +admission.donor_fee - +admission.zf_fee - +admission.received_amount - +admission.discount_amount;
};

const selectedCareOff = ref(null);

const onCareOffSelect = async (selectedCareOffs) => {
  admission.careoff_id = selectedCareOffs?.id ?? null;
  // admission.zf_fee = null;
  if(admission.careoff_id == null){
    admission.donor_fee = null;
    calculateAmount();
  }
};

const checkAmount = (val, zf_fees) => {
//   if(val > zf_fees){
//     admission.zf_fee = zf_fees;
//   }
  calculateAmount();
};


const submitForm = () => {
    admission.processing = true;
    admission.post(route('admissions.store'), {
        preserveScroll: true,
        admission,
        onSuccess: (res) => {
            admission.processing = false;
            payableAmount.value = 0;
            selectedBed.value = null;
            selectedCareOff.value = null;
            selectedDepartment.value = null;
            selectedGender.value = null;
            selectedRoom.value = null;
            selectedRoomType.value = null;
            selectedStatus.value = 'Pending';
            selectedStatus.label = 'Pending';
            admission.id = null;
            admission.source ='dashboard';
            admission.admission_date = new Date().toISOString().slice(0, 10);
            admission.admission_time= new Date().toTimeString().slice(0, 8);
            admission.discharge_date = null;
            admission.chart_no = null;
            admission.file_no = null;
            admission.advance_amount = null;
            admission.discount_amount = null;
            admission.received_amount = null;
            admission.total_amount = null;
            admission.patient_id = null;
            admission.name = null;
            admission.mr_number = null;
            admission.room_bed_id = null;
            admission.donor_fee = null;
            admission.cnic = null;
            admission.address = null;
            admission.relation_name = null;
            admission.dob = null;
            admission.phone = null;
            admission.gender = null;
            admission.status = 'Pending';
            admission.department_id = null;
            admission.type = null;
            admission.refered_by = null;
            admission.details = [
              {
                id: null,
                date: null,
                service: null,
                is_doctor:0,
                doctor: null,
                amount: null,
                desc: null,
              },
            ];
            var url_string = window.location.href;
            var url = new URL(url_string);
            var admission_id = url.searchParams.get("admission_id");
            printLabTest(admission_id);
            // router.visit('/dashboard');
            const currentUrl = new URL(window.location.href);
            const tab = currentUrl.searchParams.get('menuTab') || '2';
            router.visit(`/dashboard?menuTab=${tab}`);
        },
        onError: (error) => {
          admission.processing = false;
        }
    })
};

const printLabTest = (admission_id) => {
  const url = route('admissions.print', admission_id);
  const printWindow = window.open(url, '_blank');
  // if (printWindow) {
  //       setTimeout(function () {
  //           printWindow.focus();
  //           printWindow.print();
  //       }, 2000);
  //   }
};


const loadPatient = async (patientPhone) => {
  try {
    const response = await axios.get(`/patients-phone/${patientPhone}`);
    let patient = response?.data?.patient || null;
    if (patient) {
      admission.name = patient?.name || null;
      admission.cnic = patient?.cnic || null;
      admission.gender = patient?.gender || null;
      admission.relation_name = patient?.relation_name || null;
      admission.address = patient?.address || null;
      admission.age = patient?.age || null;
      admission.phone = patient?.phone || null;
      admission.mr_number = patient?.mr_number || null;
    }
  } catch (error) {
    console.error("Error fetching states:", error);
  }
};
onMounted(() => {
    const today = new Date().toISOString().slice(0, 10);
    admission.details.forEach(detail => {
      detail.date = today;
    });
    admission.mr_number = props.mr_number || "";
});

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
    file_no:Object,
    services:Object,
    departments:Object,
    doctors:Object,
    rooms:Object,
    beds:Object,
    room_types:Object,
    zf_fees:Object,
    mr_number:Object,
    careoffs:Object,
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
