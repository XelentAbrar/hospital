<template>

    <Head title="Create Admission" />
    <AppLayout>
        <div class="w-full pt-6">
            <div class=" mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-md rounded-lg">
                    <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                        <h1 class="font-semibold text-3xl text-primary">
                            {{ form?.id ? "Update" : "Create" }} Admission
                        </h1>
                        <div class="grid lg:grid-cols-5 gap-2">
                            <input v-model="FileNo" placeholder="Enter File No"
                                class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11" />
                            <button @click="onSearchClick"
                                class="bg-primary w-32 px-2 mt-1 rounded-md text-white">Search</button>
                        </div>
                        <form  enctype="multipart/form-data">
                            <!-- @submit.prevent="
                                form?.id
                                ? form.put(route('admissions.update', { id: form.id }))
                                : form.post(route('admissions.store'), form)
                            " -->
                        <!-- <form @submit.prevent="
                            isSubmitting = true;
                        form?.id
                            ? form.put(route('admissions.update', { id: form.id }))
                            : form.post(route('admissions.store'), form).then(() => {
                                isSubmitting = false;
                            }).catch(() => {
                                isSubmitting = false;
                            })
                            "> -->
                            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8 py-6 ">
                                <div class="col-span-12 sm:col-span-2" v-if="!form?.id">
                                    <label for="patient_id"
                                        class="block text-base font-medium leading-6 text-gray-900">Patients
                                        <!-- <span class="text-red-500">*</span> -->
                                    </label>
                                    <div class="mt-2">
                                        <multiselect v-model="selectedPatient" :options="patients"
                                            @select="onPatientSelect" label="name" track-by="id"
                                            placeholder="Select patient"></multiselect>
                                    </div>
                                    <InputError v-if="form?.errors?.patient_id" :message="form?.errors?.patient_id" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="name" class="block text-base font-medium leading-6 text-gray-900">Name
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input id="name" name="name" type="text" autocomplete="name"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.name" />
                                    <InputError v-if="form?.errors?.name" :message="form?.errors?.name" />
                                </div>
                                 <div class="col-span-12 sm:col-span-2">
                                    <label for="mr_number" class="block text-base font-medium leading-6 text-gray-900">Mr Number
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input id="mr_number" name="mr_number" type="text" autocomplete="mr_number"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11" @change="loadPatientData($event.target.value)" :value="form.mr_number" />
                                    <InputError v-if="form?.errors?.mr_number" :message="form?.errors?.mr_number" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="file_no"
                                        class="block text-base font-medium leading-6 text-gray-900">File number
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input id="file_no" name="file_no" type="text" autocomplete="file_no"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        :value="form?.id ? form.file_no : file_no" disabled />
                                    <InputError v-if="form?.errors?.file_no" :message="form?.errors?.file_no" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="admission_date"
                                        class="block text-base font-medium leading-6 text-gray-900">Admission
                                        date</label>
                                    <input id="admission_date"  type="text"
                                       ref="admission_date" name="admission_date"
                                        autocomplete="date-of-birth"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.admission_date" />
                                    <InputError v-if="form?.errors?.admission_date"
                                        :message="form?.errors?.admission_date" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="admission_time"
                                        class="block text-base font-medium leading-6 text-gray-900">Admission
                                        Time</label>
                                    <input id="admission_time" name="admission_time" type="time"
                                        autocomplete="admission_time"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.admission_time" />
                                    <InputError v-if="form?.errors?.admission_time"
                                        :message="form?.errors?.admission_time" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="cnic" class="block text-base font-medium leading-6 text-gray-900">CNIC
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input id="cnic" name="cnic" type="text" autocomplete="cnic"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11" @change="loadPatientData($event.target.value)"
                                        step="0.01" v-model="form.cnic" />
                                    <InputError v-if="form.errors?.cnic" :message="form.errors?.cnic" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="phone" class="block text-base font-medium leading-6 text-gray-900">Phone
                                        <span class="text-red-500">*</span></label>
                                    <input id="phone" name="phone" type="text" autocomplete="phone"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11" @change="loadPatientData($event.target.value)"
                                        v-model="form.phone" />
                                    <InputError v-if="form.errors?.phone" :message="form.errors?.phone" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="age" class="block text-base font-medium leading-6 text-gray-900">Age
                                        <span class="text-red-500">*</span></label>
                                    <input id="age" name="age" type="text" autocomplete="age"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.age" />
                                    <InputError v-if="form.errors?.age" :message="form.errors?.age" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="address"
                                        class="block text-base font-medium leading-6 text-gray-900">Address</label>
                                    <input id="address" name="address" type="text" autocomplete="address"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.address" />
                                    <InputError v-if="form.errors?.address" :message="form.errors?.address" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="gender"
                                        class="block text-base font-medium leading-6 text-gray-900">S/F/W/D</label>
                                    <div class="mt-2">
                                        <input id="relation_name" name="relation_name" type="text"
                                            autocomplete="relation_name"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                            v-model="form.relation_name" />
                                    </div>
                                    <InputError v-if="form.errors?.relation_name"
                                        :message="form.errors?.relation_name" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="gender"
                                        class="block text-base font-medium leading-6 text-gray-900">Gender <span
                                            class="text-red-500">*</span></label>
                                    <div class="mt-2 flex rounded-md shadow-sm">
                                        <multiselect v-model="selectedGender" :options="genderOptions"
                                            @select="onGenderSelect" label="label" track-by="value"
                                            placeholder="Select Gender"></multiselect>
                                    </div>
                                    <InputError v-if="form.errors?.gender" :message="form.errors?.gender" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="consulting_doctor_id"
                                        class="block text-base font-medium leading-6 text-gray-900">Department <span
                                            class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1">
                                        <multiselect v-model="selectedDepartment" :options="departments"
                                            @update:modelValue="onDepartmentSelect" label="name" track-by="id"
                                            placeholder="Select a Department"></multiselect>
                                    </div>
                                    <InputError v-if="form?.errors?.department_id"
                                        :message="form?.errors?.department_id" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="status" class="block text-base font-medium leading-6 text-gray-900">Type
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-2 flex rounded-md shadow-sm">
                                        <multiselect v-model="selectedRoomType" :options="room_types"
                                            @update:modelValue="onRoomTypeSelect" label="name" track-by="id"
                                            placeholder="Select a Room Type"></multiselect>
                                    </div>
                                    <InputError v-if="form.errors?.type" :message="form.errors?.type" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="consulting_doctor_id"
                                        class="block text-base font-medium leading-6 text-gray-900">Room <span
                                            class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1">
                                        <multiselect v-model="selectedRoom"
                                            :options="rooms.filter(res => { return res.room_type_id == form?.type })"
                                            @update:modelValue="onRoomSelect" label="room_number" track-by="id"
                                            placeholder="Select a Room"></multiselect>
                                    </div>
                                    <InputError v-if="form?.errors?.room_bed_id" :message="form?.errors?.room_bed_id" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="consulting_doctor_id"
                                        class="block text-base font-medium leading-6 text-gray-900">Bed No. <span
                                            class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1">
                                        <multiselect v-model="selectedBed"
                                            :options="beds.filter(res => { return res.room_id == form?.room_bed_id })"
                                            @update:modelValue="onBedSelect" label="bed_number" track-by="id"
                                            placeholder="Select a Bed"></multiselect>
                                    </div>
                                    <InputError v-if="form?.errors?.bed_id" :message="form?.errors?.bed_id" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="refered_by"
                                        class="block text-base font-medium leading-6 text-gray-900">Refered By</label>
                                    <input id="refered_by" name="refered_by" type="text" autocomplete="refered_by"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.refered_by" />
                                    <InputError v-if="form.errors?.refered_by" :message="form.errors?.refered_by" />
                                </div>
                                <!-- <div class="col-span-12 sm:col-span-2">
                                    <label for="discharge_date"
                                        class="block text-base font-medium leading-6 text-gray-900">Discharge
                                        date</label>
                                    <input id="discharge_date" name="discharge_date" type="date"
                                        autocomplete="discharge_date"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.discharge_date" />
                                    <InputError v-if="form?.errors?.discharge_date"
                                        :message="form?.errors?.discharge_date" />
                                </div> -->
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="chart_no"
                                        class="block text-base font-medium leading-6 text-gray-900">Chart No</label>
                                    <input id="chart_no" name="chart_no" type="text" autocomplete="chart_no"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.chart_no" />
                                    <InputError v-if="form?.errors?.chart_no" :message="form?.errors?.chart_no" />
                                </div>
                                <!-- <div class="col-span-12 sm:col-span-2">
                    <label
                      for="chart_no"
                      class="block text-base font-medium leading-6 text-gray-900"
                      >No of Days</label
                    >
                      <input
                        id="no_of_days"
                        name="no_of_days"
                        type="text"
                        autocomplete="no_of_days"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.no_of_days"
                        @input="calculateAmount();"
                      />
                    <InputError
                      v-if="form?.errors?.no_of_days"
                      :message="form?.errors?.no_of_days"
                    />
                  </div>
                  <div class="col-span-12 sm:col-span-2">
                    <label
                      for="no_of_days"
                      class="block text-base font-medium leading-6 text-gray-900"
                      >Days Charges</label
                    >
                    <input
                    id="no_of_days" name="no_of_days"  type="text" class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                    :value="dayCharges"  readonly
                    />

                  </div> -->

                                <!-- <div class="col-span-12 sm:col-span-3">
                    <label
                      for="reason_for_admission"
                      class="block text-base font-medium leading-6 text-gray-900"
                      >Reason for admission</label
                    >
                    <div class="mt-2">
                      <input
                        id="reason_for_admission"
                        name="reason_for_admission"
                        type="text"
                        autocomplete="reason_for_admission"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                        v-model="form.reason_for_admission"
                      />
                    </div>
                    <InputError
                      v-if="form?.errors?.reason_for_admission"
                      :message="form?.errors?.reason_for_admission"
                    />
                  </div> -->
                            </div>
                            <div class="divide-y divide-gray-200 pt-4 pb-4">
                                <div class="border shadow-md rounded-lg border-gray-200 px-4 py-6 mb-6 bg-blue-50">
                                    <h3 class="text-2xl font-semibold text-gray-900 pb-3">
                                        Service Detail
                                    </h3>
                                    <InputError v-if="form.errors?.[`details`]" :message="form.errors?.[`details`]" />

                                    <div>

  <div class="flow-root">
    <div class="-mx-4 -my-2 sm:-mx-6 lg:-mx-8">
      <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
        <div class="shadow ring-1 ring-black/5 sm:rounded-lg">
          <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Date</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Doctor</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Service</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Amount</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">No.</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Days Amount</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Description</th>
                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                  <span class="sr-only">Edit</span>
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
              <tr v-for="(
                      detail, index
                    ) in form.details" :key="index">
                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6 w-32">
                    <input v-model="detail.date" type="date" class="relative block w-full px-1 rounded-md border-0 bg-white py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6" placeholder="Date" :disabled="detail.is_posted" />
                    <InputError v-if="form.errors?.[`details.${index}.date`]" :message="form.errors?.[`details.${index}.date`]"/>
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 w-8">
                    <input type="checkbox" class="mx-auto" :checked="detail.is_doctor == '1'" @click="(event) => detail.is_doctor = event.target.checked == true ? '1' : '0'"  :disabled="detail.is_posted" />
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500" v-if="detail.is_doctor == '1'">
                    <div>
                        <multiselect v-model="detail.doctor" :options="doctors" @update:modelValue="onDoctorSelect($event, index)" label="name" track-by="id" :disabled="detail.is_posted" placeholder="Select a Doctor"></multiselect>
                    </div>
                    <InputError v-if=" form.errors?.[ `details.${index}.doctor.id`]" :message="form.errors?.[`details.${index}.doctor.id`]" />
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 w-32" v-if="detail.is_doctor == '1'">
                    <input v-model="detail.amount" type="number" class="relative block w-full px-1 rounded-md border-0 bg-white py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6" placeholder="Amount" @input="calculateAmount()" :disabled="!detail.doctor || (detail.is_posted && role && role.role_id != '1')" />
                    <InputError v-if=" form.errors?.[`details.${index}.amount`]" :message="form.errors?.[`details.${index}.amount`]"/>
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500" v-if="detail.is_doctor == '0'">
                    <div v-if="detail?.service?.is_days == '1'">
                        <multiselect v-model="detail.service" :options="services" label="name" track-by="id" placeholder="Select a Service" disabled> </multiselect>
                    </div>
                    <div v-else>
                        <multiselect v-model="detail.service" :options="services.filter(res => {return res.is_days != '1'})" label="name" track-by="id" placeholder="Select a Service" :disabled="detail.is_posted && role && role.role_id != '1'" @update:modelValue="onServiceSelect($event, index)"> </multiselect>
                    </div>
                    <InputError v-if=" form.errors?.[`details.${index}.service.id`]" :message="form.errors?.[`details.${index}.service.id`]"/>
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 w-32" v-if="detail.is_doctor == '0'">
                    <input v-model="detail.service_amount" type="number" class="relative block w-full px-1 rounded-md border-0 bg-white py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6" placeholder="Service Amount" @input="calculateAmount()" :disabled="!detail.service || ((detail.is_posted && role && role.role_id != '1') || (detail.is_posted && detail.service?.is_advance == '1')) || detail?.service?.is_days == '1'" />
                    <InputError v-if="form.errors?.[`details.${index}.service_amount`]" :message="form.errors?.[`details.${index}.service_amount`]"/>
                    {{detail.is_days}}
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 w-24">
                    <input v-model="detail.no_of_days" type="number" class="relative block w-full px-1 rounded-md border-0 bg-white py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6" @input="calculateAmount()" :disabled="detail.is_posted || detail?.service?.is_days == '1'" :placeholder="` ${selectedRoomType ? selectedRoomType.name : ''}`" />
                    <InputError v-if="form.errors?.[`details.${index}.no_of_days`]" :message="form.errors?.[`details.${index}.no_of_days`]"/>
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 w-32">
                    <!-- {{detail.no_of_days * room_charges}} -->
                    <!-- {{
                        detail.is_nursing == '1'
                            ? (detail.no_of_days * detail.service_amount)
                            : (detail.no_of_days ? detail.no_of_days * (room_charges || 0) : 0)
                    }} -->
                    {{detail.day_charges}}
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 w-32">
                    {{detail.day_charges}}
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                    <input v-model="detail.desc" type="text" class="relative block w-full rounded-md border-0 bg-white py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6" placeholder="description" />
                    <InputError v-if="form.errors?.[`details.${index}.desc`]" :message="form.errors?.[`details.${index}.desc`]"/>
                </td>
                <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                    <div class="flex items-center p-1">
                        <button type="button" @click="removeDetail(index)"
                            v-if="!detail.is_posted && detail?.service?.is_days != '1'"
                            class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <button type="button" v-else disabled
                            class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </td>
              </tr>
            </tbody>
          </table>
          <div class="px-4 py-4">
                <button type="button" @click="addDetail"
                class="flex items-center px-3 py-2 text-lg font-semibold text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add More
            </button>
              </div>
        </div>
      </div>
    </div>
  </div>
</div>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-5">
                                <div class="col-span-3"></div>
                                <div class="col-span-2 space-y-2">
                                    <div class="flex items-center space-x-2 w-full">
                                        <label for="status"
                                            class="block text-base font-medium leading-6 text-gray-900 min-w-44 whitespace-nowrap">Status
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <div class="w-full flex rounded-md shadow-sm">
                                            <multiselect class="w-full" v-model="selectedStatus"
                                                :options="statusOptions" @select="onStatusSelect" label="label"
                                                track-by="value" placeholder="Select Status"></multiselect>
                                        </div>
                                        <InputError v-if="form.errors?.status" :message="form.errors?.status" />
                                    </div>
                                    <div class="flex items-center space-x-2 w-full">
                                        <label for="payable_amount"
                                            class="block text-base font-medium leading-6 text-gray-900 whitespace-nowrap min-w-44">Payable
                                            amount</label>
                                        <input id="payable_amount" name="payable_amount" type="text"
                                            autocomplete="payable_amount"
                                            class="mt-1 block w-full rounded border-0 py-2 text-white shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11 bg-blue-500"
                                            :value="payableAmount" tabindex="-1" disabled="disabled" />
                                    </div>
                                    <div class="flex items-center space-x-2 w-full">
                                        <label for="payable_amount"
                                            class="block text-base font-medium leading-6 text-gray-900 whitespace-nowrap min-w-44">Advance Amount</label>
                                        <input id="payable_amount" name="payable_amount" type="text"
                                            autocomplete="payable_amount"
                                            class="mt-1 block w-full rounded border-0 py-2 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            :value="form.advance_amount" disabled="disabled" />
                                    </div>
                                    <div class="flex items-center space-x-2 w-full">
                                        <label for="advance_amount"
                                            class="block text-base font-medium leading-6 text-gray-900 whitespace-nowrap min-w-44">Received
                                            amount</label>
                                        <input id="received_amount" name="received_amount" type="text"
                                            autocomplete="received_amount"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            v-model="form.received_amount" @input="calculateAmount()" />
                                        <InputError v-if="form.errors?.received_amount"
                                            :message="form.errors?.received_amount" />
                                    </div>
                                    <div class="flex items-center space-x-2 w-full" v-if="selectedStatus.label === 'Complete'">
                                        <label for="discount_amount"
                                            class="block text-base font-medium leading-6 text-gray-900 whitespace-nowrap min-w-44">S.D</label>
                                        <input id="discount_amount" name="discount_amount" type="text"
                                            autocomplete="discount_amount"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            v-model="form.discount_amount" @input="calculateAmount()" />
                                        <InputError v-if="form.errors?.discount_amount"
                                            :message="form.errors?.discount_amount" />
                                    </div>

                                    <div class="flex items-center space-x-2 w-full" v-if="hasDonationModule">
                                        <label for="careoff_id"
                                            class="block text-base font-medium leading-6 text-gray-900 whitespace-nowrap min-w-44">Care
                                            Off

                                        </label>
                                        <div class="w-full">
                                            <multiselect class="w-full" v-model="selectedCareOff" :options="careoffs"
                                                @update:modelValue="onCareOffSelect(selectedCareOff)" label="name"
                                                track-by="id" placeholder="Select a CareOff"></multiselect>
                                            <input id="donor_fee" name="donor_fee" type="text" autocomplete="donor_fee"
                                                placeholder="Careoff Fee"
                                                class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                step="0.01" @input="calculateAmount()" v-model="form.donor_fee"
                                                v-if="form.careoff_id != null && form.careoff_id != ''" />
                                        </div>
                                        <InputError v-if="form?.errors?.careoff_id"
                                            :message="form?.errors?.careoff_id" />
                                    </div>

                                    <div class="flex items-center space-x-2 w-full" v-if="selectedStatus.label === 'Complete' && hasDonationModule">
                                        <label for="donor_id"
                                            class="block text-base font-medium leading-6 text-gray-900 whitespace-nowrap min-w-44">Zf

                                        </label>
                                        <input id="name" name="name" type="text" autocomplete="name"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            step="0.01" @input="checkAmount($event.target.value, zf_fees)"
                                            v-model="form.zf_fee" />
                                        <InputError v-if="form?.errors?.zf_fee" :message="form?.errors?.zf_fee" />
                                    </div>
                                    <div class="flex items-center space-x-2 w-full" v-if="selectedStatus.label === 'Complete'">
                                        <label for="remainingAmount"
                                            class="block text-base font-medium leading-6 text-gray-900 whitespace-nowrap min-w-44">Reamaning
                                            Amount</label>
                                        <input id="remainingAmount" name="remainingAmount" type="text"
                                            autocomplete="remainingAmount"
                                            class="mt-1 block w-full rounded border-0 py- shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11 "
                                            :class="remainingAmount < 0 ? 'bg-red-500 text-white' : 'bg-green-500 text-white'"
                                            :value="remainingAmount" tabindex="-1" disabled="disabled" />
                                    </div>
                                    <div class="flex items-center space-x-2 w-full" v-if="selectedStatus.label === 'Complete'">
                                        <label for="refundAmount"
                                            class="block text-sm font-medium leading-6 text-gray-900 whitespace-nowrap min-w-44">Refund
                                            Amount</label>
                                        <input id="refundAmount" name="refundAmount" type="text"
                                            autocomplete="refundAmount"
                                            class="mt-1 block w-full rounded border-0 py- shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11 "
                                            v-model="form.refund_amount" @input="calculateAmount()"
                                            :disabled="remainingAmount >= 0" tabindex="-1" />
                                        <InputError v-if="form?.errors?.refund_amount"
                                            :message="form?.errors?.refund_amount" />
                                    </div>
                                    <div class="flex items-center space-x-2 w-full">
                                        <label for="total_amount"
                                            class="block text-sm font-medium leading-6 text-gray-900 whitespace-nowrap min-w-44">Total</label>
                                        <input id="total_amount" name="total_amount" type="text"
                                            autocomplete="total_amount"
                                            class="mt-1 block w-full rounded border-0 py- shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11 "
                                            :value="form.total_amount" disabled tabindex="-1" />
                                    </div>
                                    <InputError v-if="form?.errors?.total_amount"
                                            :message="form?.errors?.total_amount" />
                                    <div class="flex items-center space-x-2 w-full">
                                        <label for="welfare"
                                            class="block text-base font-medium leading-6 text-gray-900 whitespace-nowrap min-w-44">Authorized</label>
                                        <input id="welfare" name="welfare" type="text" autocomplete="welfare"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                            v-model="form.welfare" />
                                        <InputError v-if="form.errors?.welfare" :message="form.errors?.welfare" />
                                    </div>

                                </div>
                            </div>
                            <div class="flex items-center justify-end gap-x-6 mt-6 py-4">
                                <InertiaLink
                                    class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                                    :href="route('admissions.index')">
                                    Cancel
                                </InertiaLink>
                                <button type="submit" :disabled="form.processing" @click.prevent="handleSubmit"
                                    class="relative rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                                   Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
<script setup>
import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink, router } from "@inertiajs/vue3";
import Multiselect from "vue-multiselect";
import { ref, defineComponent, computed, watch, nextTick, onMounted } from "vue";
import InputError from "../../Components/InputError.vue";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

const admission_date = ref(null);

const props = defineProps({
    hasDonationModule: Boolean,
    admission: Object,
    // patients: Array,
    patients: { type: Object, required: true },
    mr_number: String,
    services: Array,
    doctors: Array,
    careoffs: Array,
    zf_fees: Array,
    departments: Array,
    rooms: Array,
    beds: Array,
    room_types: Array,
    file_no: Array,
    role: Object,
});


const FileNo = ref(props?.admission?.file_no || '');

const searchFile = (fileNo) => {
    if (fileNo) {
        router.visit(route('admissions-file', fileNo));
    }
};

const onSearchClick = () => {
    searchFile(FileNo.value);
};
const getCurrentDateInPakistanTimezone = () => {
  const options = { year: 'numeric', month: '2-digit', day: '2-digit', timeZone: 'Asia/Karachi' };
  const currentDate = new Date().toLocaleDateString('en-GB', options); // 'en-GB' for DD-MM-YYYY format
  return currentDate;
};

const form = useForm({
    id: props?.admission?.id || null,
    patient_id: props?.admission?.patient_id || null,
    name: props?.admission?.name || null,
    // no_of_days: props?.admission?.no_of_days || null,
    day_charges: props.admission?.day_charges || 0,
    file_no: props?.admission?.file_no || null,
    admission_date: props?.admission?.admission_date  || new Date().toLocaleDateString("en-GB").replace(/\//g, "-"),
    admission_time: props?.admission?.admission_time || new Date().toTimeString().slice(0, 8),
    discharge_date: props?.admission?.discharge_date || null,
    chart_no: props?.admission?.chart_no || null,
    reason_for_admission: props?.admission?.reason_for_admission || null,
    room_bed_id: props?.admission?.room_bed_id || null,
    bed_id: props?.admission?.bed_id || null,
    cnic: props?.admission?.cnic || null,
    age: props?.admission?.age || null,
    donor_fee: props?.admission?.donor_fee || null,
    address: props?.admission?.address || null,
    relation_name: props?.admission?.relation_name || null,
    dob: props?.admission?.dob || null,
    mr_number: props?.mr_number || null,
    phone: props?.admission?.phone || null,
    gender: props?.admission?.gender || null,
    status: props?.admission?.status || 'Pending',
    department_id: props?.admission?.department_id || null,
    type: props?.admission?.type || null,
    refered_by: props?.admission?.refered_by || null,
    advance_amount: props?.admission?.advance_amount || null,
    received_amount: props?.admission?.received_amount || null,
    discount_amount: props?.admission?.discount_amount || null,
    refund_amount: props?.admission?.refund_amount || null,
    total_amount: props?.admission?.total_amount || null,
    careoff_id: props?.admission?.careoff_id || null,
    welfare: props?.admission?.welfare || null,
    zf_fee: props?.admission?.zf_fee || null,
    details: props?.admission?.details || [
        {
            id: null,
            date: null,
            service: null,
            doctor: null,
            amount: null,
            service_amount: null,
            no_of_days: null,
            day_charges: null,
            desc: null,
            is_doctor: false,
        },
    ],
});

onMounted(() => {
    const flatpickrOptions = (defaultDate) => ({
        dateFormat: "d-m-Y", // Format: DD-MM-YYYY
        allowInput: true, // Allows manual typing
        clickOpens: true, // Ensures the picker still works
        defaultDate: defaultDate || new Date(), // Use provided default date or current date
    });

    flatpickr(admission_date.value, flatpickrOptions(form.admission_date));
    const today = new Date().toISOString().slice(0, 10);
    form.details.forEach(detail => {
        detail.date = today;
    });
    // alert(mr_number);
    // form.mr_number = mr_number || '';
});
// const is_doctor = ref(false);
const selectedPatient = ref(null);
const patients = ref(props.patients || []);
// if (props?.admission) {
//     const group = props.patients.find((c) => c.id == props.admission.patient_id);
//     if (group) {
//         selectedPatient.value = group;
//     }
// }

// const onPatientSelect = async (selectedPatient) => {
//     form.patient_id = selectedPatient.id;
// };
const selectedGender = ref(null);
const genderOptions = computed(() => {
    return [
        { value: "Male", label: "Male" },
        { value: "Female", label: "Female" },
        { value: "Children", label: "Children" },
    ];
});


const onGenderSelect = async (selectedGender) => {
    form.gender = selectedGender.value;
};

if (props?.admission) {
    selectedGender.value = genderOptions.value.find(
        (option) => option.value === props.admission.gender
    );
    console.log("Initial Selected Gender from Props:", selectedGender.value);
}


const loadPatientData = async (patientPhone) => {
  try {
    const response = await axios.get(`/patients-phone/${patientPhone}`);
    let patient = response?.data?.patient || null;
    if (patient) {
      form.name = patient?.name || null;
        form.mr_number = patient?.mr_number || null;
        // mr_number = patient?.mr_number || null;
        form.cnic = patient?.cnic || null;
        form.age = patient?.age || null;
        form.dob = patient?.dob || null;
        form.gender = patient?.gender || null;
        form.relation_name = patient?.relation_name || null;
        form.address = patient?.address || null;
        form.patient_state_id = patient?.state_id || null;
        form.patient_city_id = patient?.city_id || null;
        form.patient_country_id = patient?.country_id || null;
        form.phone = patient?.phone || null;
        form.patient_email = patient?.email || null;

        if (patient?.gender) {
            selectedGender.value = genderOptions.value.find(
                (option) => option.value === patient.gender
            );
            console.log("Selected Gender after loading patient:", selectedGender.value);
        }
        
    }
  } catch (error) {
    console.error("Error fetching states:", error);
  }
};

const onPatientSelect = async (selectedPatient) => {
    form.patient_id = selectedPatient.id;
  await loadPatient(selectedPatient.id);
};
const loadPatient = async (patientId) => {
    try {
        const response = await axios.get(`/patients/${patientId}`);
        let patient = response?.data?.patient || null;
        if (patient) {
            form.name = patient?.name || null;
            form.mr_number = patient?.mr_number || null;
            // mr_number = patient?.mr_number || null;
            form.cnic = patient?.cnic || null;
            form.age = patient?.age || null;
            form.dob = patient?.dob || null;
            form.gender = patient?.gender || null;
            form.relation_name = patient?.relation_name || null;
            form.address = patient?.address || null;
            form.patient_state_id = patient?.state_id || null;
            form.patient_city_id = patient?.city_id || null;
            form.patient_country_id = patient?.country_id || null;
            form.phone = patient?.phone || null;
            form.patient_email = patient?.email || null;

            if (patient?.gender) {
                selectedGender.value = genderOptions.value.find(
                    (option) => option.value === patient.gender
                );
                console.log("Selected Gender after loading patient:", selectedGender.value);
            }
        }
    } catch (error) {
        console.error("Error fetching states:", error);
    }
};
const selectedRoomBed = ref(null);

const onRoomBedSelect = async (selectedRoomBed) => {
    form.room_bed_id = selectedRoomBed.id;
};

const cancel = () => {
    Inertia.visit(route("admissions.index"));
};

const selectedDepartment = ref(null);
const onDepartmentSelect = async (selectedDepartment) => {
    form.department_id = selectedDepartment?.id ?? null;
};
if (props?.admission) {
    const group = props.departments.find((c) => c.id == props.admission.department_id);
    if (group) {
        selectedDepartment.value = group;
    }
}

const selectedRoomType = ref(null);
const onRoomTypeSelect = async (selectedRoomType) => {
    form.type = selectedRoomType?.id ?? null;
};
if (props?.admission) {
    const group = props.room_types.find((c) => c.id == props.admission.type);
    if (group) {
        selectedRoomType.value = group;
    }
}

const selectedRoom = ref(null);
const room_charges = ref(null);


const onRoomSelect = async (selectedRoom) => {
    form.room_bed_id = selectedRoom?.id ?? null;
    room_charges.value = selectedRoom?.charges ?? null;
    calculateAmount();
};
if (props?.admission) {
    const group = props.rooms.find((c) => c.id == props.admission.room_bed_id);
    if (group) {
        selectedRoom.value = group;
        room_charges.value = group?.charges ?? null;
    }
}
// const dayCharges = computed(() => {
//   const noOfDays = form.no_of_days || 0;
//   const roomCharges = room_charges.value || 0;
//   return noOfDays * roomCharges;
// });
// watch(dayCharges, (newVal) => {
//   form.day_charges = newVal;
// });

const selectedBed = ref(null);
const onBedSelect = async (selectedBed) => {
    form.bed_id = selectedBed?.id ?? null;
    let days_service = props.services.find(res => { return res.is_days == '1'});
    if(days_service && selectedBed?.id){
        let check_service_days =  form.details.find(res => { return res.service?.id == days_service['id']});
        const date1 = new Date(form.admission_date);
        const date2 = new Date(form.discharge_date ?? new Date().toISOString().split('T')[0]);

        // Calculate the difference in milliseconds
        const diffInMilliseconds = Math.abs(date2 - date1);

        // Convert milliseconds to days
        const diffInDays = Math.ceil(diffInMilliseconds / (1000 * 60 * 60 * 24));
        const today = new Date().toISOString().slice(0, 10);

        if(!check_service_days){
            form.details.push({
                id: null,
                date: today,
                service: days_service,
                doctor: null,
                amount: null,
                service_amount: null,
                no_of_days: diffInDays,
                day_charges: diffInDays * room_charges.value,
                desc: null,
                is_doctor: false,
            });
            calculateAmount()
        }
        else{
            form.details.map(res =>{
                if(res.service?.id == days_service['id']){
                    res.no_of_days = diffInDays;
                    res.day_charges = diffInDays * room_charges.value;
                    res.service = days_service;
                }
            })
            calculateAmount()
        }

    }
};
if (props?.admission) {
    const group = props.beds.find((c) => c.id == props.admission.bed_id);
    if (group) {
        selectedBed.value = group;
    }
}


// if (props?.admission) {
//     const group = genderOptions.value.find((c) => c.value == props.admission.gender);
//     if (group) {
//         selectedGender.value = group;
//     }
// }

// const onGenderSelect = async (selectedGender) => {
//     form.gender = selectedGender.value;
// };

const onDoctorSelect = (val, index) => {
    form.details[index].amount = val?.doctor_charges?.visiting_charges ?? 0;
    calculateAmount();
};

const onServiceSelect = (val, index) => {
    form.details[index].service_amount = val?.charges ?? 0;
    form.details[index].is_nursing = val?.is_nursing ?? '0';
    form.details[index].is_advance = val?.is_advance ?? '0';

    calculateAmount();
};

// const generateMRNumber = (event) => {
//   let input = event.target.value.replace(/\D/g, "");
//   let number = input ? parseInt(input) : 1;
//   let formattedNumber = number.toString().padStart(5, '0');
//   form.mr_number = formattedNumber;
// };

const selectedStatus = ref({ value: "Pending", label: "Pending" });
const statusOptions = computed(() => {
    return [
        { value: "Pending", label: "Pending" },
        { value: "Complete", label: "Complete" },
    ];
});

if (props?.admission) {
    const group = statusOptions.value.find((c) => c.value == props.admission.status);
    if (group) {
        selectedStatus.value = group;
    }
}
const handleSubmit = () => {
  const request = form.id
    ? form.put(route('admissions.update', { id: form.id }), form)
    : form.post(route('admissions.store'), form);
};

const onStatusSelect = async (selectedGender) => {
    form.status = selectedGender.value;

    let days_service = props.services.find(res => { return res.is_days == '1'});
    if(days_service){
        let check_service_days =  form.details.find(res => { return res.service?.id == days_service['id']});
        const date1 = new Date(form.admission_date);
        const date2 = new Date(form.discharge_date ?? new Date().toISOString().split('T')[0]);

        // Calculate the difference in milliseconds
        const diffInMilliseconds = Math.abs(date2 - date1);

        // Convert milliseconds to days
        const diffInDays = Math.ceil(diffInMilliseconds / (1000 * 60 * 60 * 24));
        const today = new Date().toISOString().slice(0, 10);

        if(!check_service_days){
            form.details.push({
                id: null,
                date: today,
                service: days_service,
                doctor: null,
                amount: null,
                service_amount: null,
                no_of_days: diffInDays,
                day_charges: diffInDays * room_charges.value,
                desc: null,
                is_doctor: false,
            });
            calculateAmount()
        }
        else{
            form.details.map(res =>{
                if(res.service?.id == days_service['id']){
                    res.no_of_days = diffInDays;
                    res.day_charges = diffInDays * room_charges.value;
                    res.service = days_service;
                }
            })
            calculateAmount()
        }

    }
};

//General test detail
const addDetail = () => {
    const today = new Date().toISOString().slice(0, 10);
    form.details.push({
        id: null,
        date: today,
        service: null,
        doctor: null,
        amount: null,
        service_amount: null,
        no_of_days: null,
        day_charges: null,
        desc: null,
        is_doctor: false,
    });
};
// form.details.forEach((detail, index) => {
//   watch(() => detail.is_doctor, (newVal) => {
//     if (newVal) {
//       detail.service = null;
//       detail.service_amount = 0;
//       detail.doctor = null;
//       detail.amount = 0;
//     } else {
//       detail.doctor = null;
//       detail.amount = 0;
//       detail.service = null;
//       detail.service_amount = 0;
//     }
//   });
// });
watch(() => form.details, (newDetails) => {
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
if(props?.hasDonationModule){
    const selectedCareOff = ref(null);
    const onCareOffSelect = async (selectedCareOffs) => {
        form.careoff_id = selectedCareOffs?.id ?? null;
        // form.zf_fee = null;
        if (admission.careoff_id == null) {
            admission.donor_fee = null;
            calculateAmount();
        }
    };



    const careoffs = ref(props.careoffs || []);
    if (props?.admission) {
        const careoff = props.careoffs.find(
            (c) => c.id === parseInt(props.admission.careoff_id)
        );
        if (careoff) {
            selectedCareOff.value = careoff;
        }
    }

    const zf_fees = ref(props.zf_fees || []);
}
const checkAmount = (val, zf_fees) => {
    // if (val > zf_fees) {
    //     form.zf_fee = zf_fees;
    // }
    calculateAmount();
};

const removeDetail = (index) => {
    form.details.splice(index, 1);
};

const payableAmount = ref();
const remainingAmount = ref();

// const calculateAmount = () => {
//   let sum = 0;
//   form.details.map(res =>{
//     sum += parseFloat(res.amount || 0);
//     sum += parseFloat(res.service_amount || 0);
//   })
//   if (form.no_of_days && room_charges.value) {
//     sum += parseFloat(form.no_of_days * room_charges.value || 0);
//   }
//   payableAmount.value = sum;
//   calculateRemaingAmount();
// };
// const calculateAmount = () => {
//   let sum = 0;
//   form.details.map(res =>{
//     sum += parseFloat(res.amount || 0);
//     sum += parseFloat(res.service_amount || 0);
//     if(room_charges.value){
//       sum += parseFloat(res.no_of_days * room_charges.value || 0);
//     }
//   })
//   payableAmount.value = sum;
//   calculateRemaingAmount();
// };
// const calculateAmount = () => {
//   let sum = 0;
//   form.details.forEach((detail) => {
//     sum += parseFloat(detail.amount || 0);
//     sum += parseFloat(detail.service_amount || 0);
//     if (detail.no_of_days !== null && room_charges.value !== null) {
//       detail.day_charges = detail.no_of_days * room_charges.value;
//       sum += parseFloat(detail.day_charges || 0);
//     } else {
//       detail.day_charges = null;
//     }
//   });
//   payableAmount.value = sum;
//   calculateRemaingAmount();
// };

// const calculateAmount = () => {
//     let sum = 0;

//     let advance_amount = 0;
//     form.details.forEach((detail) => {
//         let amount = 0;

//         if(detail.amount > 0){
//             detail.day_charges = (detail.no_of_days > 0 ? detail.no_of_days : 1) * (detail.amount || 0);
//             amount = parseFloat((detail.no_of_days > 0 ? detail.no_of_days : 1) * (detail.amount || 0));
//         }
//         else if(detail.service_amount > 0){
//             detail.day_charges = (detail.no_of_days > 0 ? detail.no_of_days : 1) * (detail.service_amount || 0);
//             amount = parseFloat((detail.no_of_days > 0 ? detail.no_of_days : 1) * (detail.service_amount || 0));
//         }
//         else if (detail.no_of_days !== null) {
//             detail.day_charges = detail.no_of_days * room_charges.value;
//             amount += parseFloat(detail.day_charges || 0);
//         }
//         // console.log(`Detail: ${JSON.stringify(detail)}, Calculated Amount: ${amount}`);
//         if(detail?.service?.is_advance == '1'){
//             advance_amount = +advance_amount + +detail.day_charges;
//         }

//         sum += amount;
//     });

//     form.advance_amount = advance_amount;

//     payableAmount.value = +sum - +form.advance_amount;
//     calculateRemaingAmount();
// };
const calculateAmount = () => {
    let sum = 0;

    let advance_amount = 0;
    form.details.forEach((detail) => {
        let amount = 0;

        if (detail.amount > 0) {
            if (detail.no_of_days > 0) {
                detail.day_charges = detail.no_of_days * detail.amount;
                amount = parseFloat(detail.no_of_days * detail.amount);
            } else {
                detail.day_charges = detail.amount;
                amount = parseFloat(detail.amount);
            }
        }

       else if (detail.service_amount > 0) {
            if (detail.no_of_days > 0) {
                detail.day_charges = detail.no_of_days * detail.service_amount;
                amount = parseFloat(detail.no_of_days * detail.service_amount);
            } else {
                // If no number of days is provided (0 or null)
                detail.day_charges = detail.service_amount;
                amount = parseFloat(detail.service_amount);
            }
        }

        else if (detail.no_of_days !== null) {
            detail.day_charges = detail.no_of_days * room_charges.value;
            amount += parseFloat(detail.day_charges);
        }
        // console.log(`Detail: ${JSON.stringify(detail)}, Calculated Amount: ${amount}`);
        if(detail?.service?.is_advance == '1'){
            advance_amount = +advance_amount + +detail.day_charges;
        }

        sum += amount;
    });

    form.advance_amount = advance_amount;

    payableAmount.value = +sum - +form.advance_amount;
    calculateRemaingAmount();
};




// const calculateAmount = () => {
//   let sum = 0;
//   form.details.forEach((detail) => {
//     sum += parseFloat(detail.amount || 0);
//     sum += parseFloat(detail.service_amount || 0);
//     if (detail.no_of_days !== null && room_charges.value !== null) {
//       detail.day_charges = detail.no_of_days * room_charges.value;
//       sum += parseFloat(detail.day_charges || 0);
//     } else {
//       detail.day_charges = null;
//     }
//   });
//   payableAmount.value = sum;
//   calculateRemaingAmount();
// };

const calculateRemaingAmount = () => {
    remainingAmount.value = +payableAmount.value - +form.advance_amount - +form.donor_fee - +form.zf_fee - +form.received_amount - +form.discount_amount;
    form.total_amount = +remainingAmount.value + +form.refund_amount;
};

// const calculateAmount = () => {
//   let sum = 0;
//   form.details.map(res =>{
//     sum += parseFloat(res.amount || 0);
//   })
//   payableAmount.value = sum;
//   remainingAmount.value = +payableAmount.value - +form.advance_amount - +form.received_amount;
// };

if (props?.admission) {
    calculateAmount();
}


defineComponent({
    components: {
        Multiselect,
    },
});
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
