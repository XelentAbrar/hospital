<template>
  <Head title="Create Employee" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ employee?.id ? "Update" : "Create" }} Employee
          </h1>
          <!-- <form
              @submit.prevent="
                employee?.id
                  ? employee.put(route('employees.update', { id: employee.id }))
                  : employee.post(route('employees.store'), employee)
              "
              class="py-6 divide-y divide-gray-200 space-y-10"
              enctype="multipart/form-data"
          > -->
          <form  @submit.prevent="
            employee.processing = true;
            employee?.id
                ? employee.put(route('employees.update', { id: employee.id }))
                : employee.post(route('employees.store'), employee).finally(() => {
                    employee.processing = false;
                })
            ">
              <!-- Profile section -->
              <div class="">
                  <h2 class="text-2xl font-medium text-gray-900">
                    Employee
                  </h2>
                  <p class="mt-1 text-sm text-gray-900">
                    This information will be displayed publicly so be careful
                    what you share.
                  </p>
                <div class="mt-6 grid grid-cols-3 gap-4">
                    <div class="">
                      <label
                        for="serial_no"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Serial No
                        <span class="text-red-500">*</span>
                      </label>
                      <input
                        type="text"
                        name="serial_no"
                        id="serial_no"
                        autocomplete="given-name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="employee.serial_no"
                      />
                      <InputError
                        v-if="employee?.errors?.serial_no"
                        :message="employee?.errors?.serial_no"
                      />
                    </div>
                    <div class="">
                      <label
                        for="machine_id"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Machine Id
                        <span class="text-red-500">*</span>
                      </label>
                      <input
                        type="text"
                        name="machine_id"
                        id="machine_id"
                        autocomplete="given-name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="employee.machine_id"
                      />
                      <InputError
                        v-if="employee?.errors?.machine_id"
                        :message="employee?.errors?.machine_id"
                      />
                    </div>
                    <div class="">
                      <label
                        for="name"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Name
                        <span class="text-red-500">*</span>
                      </label>
                      <input
                        type="text"
                        name="name"
                        id="name"
                        autocomplete="given-name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="employee.name"
                      />
                      <InputError
                        v-if="employee?.errors?.name"
                        :message="employee?.errors?.name"
                      />
                    </div>
                    <div class="">
                      <label
                        for="email"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Email Address
                        <!-- <span class="text-red-500">*</span> -->
                      </label>
                      <input
                        type="text"
                        name="email"
                        id="email"
                        autocomplete="family-name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="employee.email"
                      />
                      <InputError
                        v-if="employee?.errors?.email"
                        :message="employee?.errors?.email"
                      />
                    </div>
                    <div>
                    <label
                      for="dob"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Date of Birth
                      <span class="text-red-500">*</span>
                    </label>
                    <input
                      type="date"
                      name="dob"
                      id="dob"
                      autocomplete="date-of-birth"
                      class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                      v-model="employee.dob"
                    />
                    <InputError
                      v-if="employee?.errors?.dob"
                      :message="employee?.errors?.dob"
                    />
                  </div>
                    <div class="">
                    <label
                      for="mobile"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Mobile</label
                    >
                    <input
                      type="text"
                      name="mobile"
                      id="mobile"
                      autocomplete="mobile-number"
                      class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                      v-model="employee.mobile"
                    />
                    <InputError
                      v-if="employee?.errors?.mobile"
                      :message="employee?.errors?.mobile"
                    />
                    </div>
                    <div class="">
                    <label
                      for="landline"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Landline</label
                    >
                    <input
                      type="text"
                      name="landline"
                      id="landline"
                      autocomplete="landline-number"
                      class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                      v-model="employee.landline"
                    />
                    <InputError
                      v-if="employee?.errors?.landline"
                      :message="employee?.errors?.landline"
                    />
                    </div>
                    <div class="">
                    <label
                      for="gender"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Gender</label
                    >
                    <multiselect
                      class="mt-1"
                      v-model="selectedGender"
                      :options="GenderOptions"
                      :custom-label="GenderCustomLabel"
                      @select="onGenderSelect"
                      label="type"
                      track-by="type"
                      placeholder="Select Gender"
                    ></multiselect>
                    <InputError
                      v-if="employee?.errors?.gender"
                      :message="employee?.errors?.gender"
                    />
                  </div>
                    <div class="col-span-2">
                      <label
                        for="about_employee"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >About</label
                      >
                        <textarea
                          id="about_employee"
                          name="about_employee"
                          rows="3"
                          class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:text-sm sm:leading-6"
                          v-model="employee.about_employee"
                        ></textarea>
                      <p class="mt-2 text-sm text-gray-900">
                        Brief description about employee. Internal use only
                      </p>
                      <InputError
                        v-if="employee?.errors?.about_employee"
                        :message="employee?.errors?.about_employee"
                      />
                    </div>
                  <!-- <div>
                    <label
                      for="image"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Picture
                    </label>
                    <file-pond
                    class="bg-white mt-1"
                      name="image"
                      ref="pond"
                      credits="false"
                      v-bind:allow-multiple="false"
                      accepted-file-types="image/png, image/jpeg, image/jpg"
                      :server="{
                        url: '',
                        timeout: 7000,
                        process: {
                          url: '/upload-employee',
                          method: 'POST',
                          headers: {
                            'X-CSRF-TOKEN': csrfToken,
                          },
                          withCredentials: false,
                          onload: handleFilePondLoad,
                        },
                      }"
                      v-bind:files="myFiles"
                      v-on:init="handleFilePondInit"
                    >
                    </file-pond>
                    <InputError
                      v-if="employee?.errors?.image"
                      :message="employee?.errors?.image"
                    />
                  </div> -->
                    <div class="col-span-2">
                    <label
                      for="street_address"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Street Address</label
                    >
                    <input
                      type="text"
                      name="street_address"
                      id="street_address"
                      class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                      v-model="employee.street_address"
                    />
                    <InputError
                      v-if="employee?.errors?.street_address"
                      :message="employee?.errors?.street_address"
                    />
                  </div>
                  <div class="">
                    <label
                      for="postal-code"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Postal Code</label
                    >
                    <input
                      type="number"
                      name="postal-code"
                      id="postal-code"
                      class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                      v-model="employee.postal_code"
                    />
                    <InputError
                      v-if="employee?.errors?.postal_code"
                      :message="employee?.errors?.postal_code"
                    />
                  </div>
                  <div class="">
                    <label
                      for="country"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Country</label
                    >
                    <div class="mt-2">
                      <multiselect
                        v-model="selectedCountry"
                        :options="countryOptions"
                        :custom-label="countryCustomLabel"
                        @select="onCountrySelect"
                        label="name"
                        track-by="id"
                        placeholder="Select a Country"
                      ></multiselect>
                    </div>
                    <InputError
                      v-if="employee?.errors?.country_id"
                      :message="employee?.errors?.country_id"
                    />
                  </div>

                  <div class="">
                    <label
                      for="state"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >State</label
                    >
                    <div class="mt-2">
                      <multiselect
                        v-model="selectedState"
                        :options="stateOptions"
                        :custom-label="stateCustomLabel"
                        @select="onStateSelect"
                        label="name"
                        track-by="id"
                        placeholder="Select a State"
                      ></multiselect>
                    </div>
                    <InputError
                      v-if="employee?.errors?.state_id"
                      :message="employee?.errors?.state_id"
                    />
                  </div>

                  <div class="">
                    <label
                      for="city"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >City</label
                    >
                    <div class="mt-2">
                      <multiselect
                        v-model="selectedCity"
                        :options="cityOptions"
                        :custom-label="cityCustomLabel"
                        @select="onCitySelect"
                        label="name"
                        track-by="id"
                        placeholder="Select a City"
                      ></multiselect>
                    </div>
                    <InputError
                      v-if="employee?.errors?.city_id"
                      :message="employee?.errors?.city_id"
                    />
                  </div>
                  <div class="">
                    <label
                      for="doj"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Date of Joining
                      <span class="text-red-500">*</span>
                    </label>
                    <input
                      type="date"
                      name="doj"
                      id="doj"
                      autocomplete="date-of-joining"
                      class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                      v-model="employee.doj"
                    />
                    <InputError
                      v-if="employee?.errors?.doj"
                      :message="employee?.errors?.doj"
                    />
                  </div>

                  <div class="">
                    <label
                      for="probation_start_date"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Probation Start Date</label
                    >
                    <input
                      type="date"
                      name="probation_start_date"
                      id="probation_start_date"
                      autocomplete="probation-start-date"
                      class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                      v-model="employee.probation_start_date"
                    />
                    <InputError
                      v-if="employee?.errors?.probation_start_date"
                      :message="employee?.errors?.probation_start_date"
                    />
                  </div>
                  <div class="">
                    <label
                      for="probation_end_date"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Probation End Date</label
                    >
                    <input
                      type="date"
                      name="probation_end_date"
                      id="probation_end_date"
                      autocomplete="probation-end-date"
                      class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                      v-model="employee.probation_end_date"
                    />
                    <InputError
                      v-if="employee?.errors?.probation_end_date"
                      :message="employee?.errors?.probation_end_date"
                    />
                  </div>

                  <div class="">
                    <label
                      for="notice_period_start_date"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Notice Period Start Date</label
                    >
                    <input
                      type="date"
                      name="notice_period_start_date"
                      id="notice_period_start_date"
                      autocomplete="notice-period-start-date"
                      class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                      v-model="employee.notice_period_start_date"
                    />
                    <InputError
                      v-if="employee?.errors?.notice_period_start_date"
                      :message="employee?.errors?.notice_period_start_date"
                    />
                  </div>
                  <div class="">
                    <label
                      for="notice_period_end_date"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Notice Period End Date</label
                    >
                    <input
                      type="date"
                      name="notice_period_end_date"
                      id="notice_period_end_date"
                      autocomplete="notice-period-end-date"
                      class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                      v-model="employee.notice_period_end_date"
                    />
                    <InputError
                      v-if="employee?.errors?.notice_period_end_date"
                      :message="employee?.errors?.notice_period_end_date"
                    />
                  </div>
                  <div class="">
                    <label
                      for="reporting_to"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Reporting To</label
                    >
                    <multiselect
                      v-model="selectedReportingTo"
                      :options="employeeOptions"
                      :custom-label="employeeCustomLabel"
                      @select="onEmployeeSelect"
                      label="name"
                      track-by="id"
                      placeholder="Select a Reporting Employee"
                    ></multiselect>
                    <InputError
                      v-if="employee?.errors?.reporting_to"
                      :message="employee?.errors?.reporting_to"
                    />
                  </div>
                  <div class="">
                    <label
                      for="slack_id"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Slack ID</label
                    >
                    <div class="mt-1 flex rounded">
                      <span
                        class="inline-flex items-center bg-white rounded-l-md border border-r-0 border-gray-300 px-3 text-gray-900 sm:text-sm"
                        >@</span
                      >
                      <input
                        type="text"
                        name="slack_id"
                        id="slack_id"
                        autocomplete="slack_id"
                        class="block w-full rounded-r border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="employee.slack_id"
                      />
                    </div>
                    <InputError
                      v-if="employee?.errors?.slack_id"
                      :message="employee?.errors?.slack_id"
                    />
                  </div>
                  <div class="">
                    <label
                      for="department_id"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Department
                      <span class="text-red-500">*</span>
                    </label>
                    <multiselect
                    class="mt-1"
                      v-model="selectedDepartment"
                      :options="departmentOptions"
                      :custom-label="departmentCustomLabel"
                      @select="onDepartmentSelect"
                      label="name"
                      track-by="id"
                      placeholder="Select a Department"
                    ></multiselect>
                    <InputError
                      v-if="employee?.errors?.department_id"
                      :message="employee?.errors?.department_id"
                    />
                  </div>

                  <div class="">
                    <label
                      for="grade_id"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Grades
                      <span class="text-red-500">*</span>
                    </label>
                    <multiselect
                    class="mt-1"
                      v-model="selectedGrade"
                      :options="grades"
                      @select="onGradeSelect"
                      label="name"
                      track-by="id"
                      placeholder="Select a grade"
                    ></multiselect>
                    <InputError
                      v-if="employee?.errors?.grade_id"
                      :message="employee?.errors?.grade_id"
                    />
                  </div>

                  <div class="">
                    <label
                      for="employee_type"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Employee Type
                      <span class="text-red-500">*</span>
                    </label>
                    <multiselect
                      class="mt-1"
                      v-model="selectedEmployeeType"
                      :options="EmployeeTypeOptions"
                      :custom-label="EmployeeTypeCustomLabel"
                      @select="onEmployeeTypeSelect"
                      label="type"
                      track-by="type"
                      placeholder="Select Employee Type"
                    ></multiselect>
                    <InputError
                      v-if="employee?.errors?.employee_type"
                      :message="employee?.errors?.employee_type"
                    />
                  </div>
                  <div class="">
                    <label
                      for="martial_status"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Martial Status
                      <span class="text-red-500">*</span>
                    </label>
                    <multiselect
                      class="mt-1"
                      v-model="selectedMartialStatus"
                      :options="MartialStatusOptions"
                      :custom-label="MartialStatusCustomLabel"
                      @select="onMartialStatusSelect"
                      label="type"
                      track-by="type"
                      placeholder="Select Martial Status"
                    ></multiselect>
                    <InputError
                      v-if="employee?.errors?.marital_status"
                      :message="employee?.errors?.marital_status"
                    />
                  </div>
                  <div class="">
                    <label
                      for="blood_group"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Blood Group</label
                    >
                    <multiselect
                      class="mt-1"
                      v-model="selectedBloodGroup"
                      :options="BloodGroupOptions"
                      :custom-label="BloodGroupCustomLabel"
                      @select="onBloodGroupSelect"
                      label="type"
                      track-by="type"
                      placeholder="Select Blood Group"
                    ></multiselect>
                    <InputError
                      v-if="employee?.errors?.blood_group"
                      :message="employee?.errors?.blood_group"
                    />
                  </div>
                  <div class="">
                    <label
                      for="religion"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Religion</label
                    >
                      <input
                        type="text"
                        name="religion"
                        id="religion"
                        autocomplete="religion"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="employee.religion"
                      />
                    <InputError
                      v-if="employee?.errors?.religion"
                      :message="employee?.errors?.religion"
                    />
                  </div>
                  <div class="">
                    <label
                      for="emergency_contact"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Emergency Contact</label
                    >
                      <input
                        type="text"
                        name="emergency_contact"
                        id="emergency_contact"
                        autocomplete="emergency_contact"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="employee.emergency_contact"
                      />
                    <InputError
                      v-if="employee?.errors?.emergency_contact"
                      :message="employee?.errors?.emergency_contact"
                    />
                  </div>
                  <div class="col-span-2">
                    <label
                      for="skills"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Skills</label
                    >
                      <input
                        type="text"
                        name="skills"
                        id="skills"
                        autocomplete="skills"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="employee.skills"
                      />
                    <InputError
                      v-if="employee?.errors?.skills"
                      :message="employee?.errors?.skills"
                    />
                  </div>
                  <div class="">
                    <label
                      for="account_name"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Account Name</label
                    >
                      <input
                        type="text"
                        name="account_name"
                        id="account_name"
                        autocomplete="account_name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="employee.account_name"
                      />
                    <InputError
                      v-if="employee?.errors?.account_name"
                      :message="employee?.errors?.account_name"
                    />
                  </div>
                  <div class="">
                    <label
                      for="account_number"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Account #</label
                    >
                      <input
                        type="text"
                        name="account_number"
                        id="account_number"
                        autocomplete="account_number"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="employee.account_number"
                      />
                    <InputError
                      v-if="employee?.errors?.account_number"
                      :message="employee?.errors?.account_number"
                    />
                  </div>
                  <div class="">
                    <label
                      for="bank_name"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Bank Name</label
                    >
                      <input
                        type="text"
                        name="bank_name"
                        id="bank_name"
                        autocomplete="bank_name"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="employee.bank_name"
                      />
                    <InputError
                      v-if="employee?.errors?.bank_name"
                      :message="employee?.errors?.bank_name"
                    />
                  </div>
                  <div class="">
                    <label
                      for="bank_location"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Bank Location</label
                    >
                      <input
                        type="text"
                        name="bank_location"
                        id="bank_location"
                        autocomplete="bank_location"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="employee.bank_location"
                      />
                    <InputError
                      v-if="employee?.errors?.bank_location"
                      :message="employee?.errors?.bank_location"
                    />
                  </div>
                  <div class="">
                    <label
                      for="tax_payer_id"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Tax Payer ID</label
                    >
                      <input
                        type="text"
                        name="tax_payer_id"
                        id="tax_payer_id"
                        autocomplete="tax_payer_id"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="employee.tax_payer_id"
                      />
                    <InputError
                      v-if="employee?.errors?.tax_payer_id"
                      :message="employee?.errors?.tax_payer_id"
                    />
                  </div>
                  <div class="">
                    <label
                      for="basic_salary"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Basic Salary</label
                    >
                      <input
                        type="text"
                        name="basic_salary"
                        id="basic_salary"
                        autocomplete="basic_salary"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="employee.basic_salary"
                      />
                    <InputError
                      v-if="employee?.errors?.basic_salary"
                      :message="employee?.errors?.basic_salary"
                    />
                  </div>
                  <div class="">
                    <label
                      for="salary_currency"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Salary Currency</label
                    >
                      <multiselect
                      class="mt-1"
                        v-model="selectedSalaryCurrency"
                        :options="SalaryCurrencyOptions"
                        :custom-label="SalaryCurrencyCustomLabel"
                        @select="onSalaryCurrencySelect"
                        label="type"
                        track-by="type"
                        placeholder="Select Salary Currency"
                      ></multiselect>
                    <InputError
                      v-if="employee?.errors?.salary_currency"
                      :message="employee?.errors?.salary_currency"
                    />
                  </div>
                  <!-- <div class="col-span-12 sm:col-span-3">
                    <label for="employee_role" class="block text-sm md:text-base font-medium text-gray-900">Employee Role</label>
                    <div class="mt-2 flex rounded-md shadow-sm">
                    <input type="text" name="employee_role" id="employee_role" autocomplete="employee_role" class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11">
                    <InputError
                        v-if="employee?.errors?.salary_currency"
                        :message="employee?.errors?.salary_currency"
                    />
                    </div>
                  </div> -->
                </div>
              </div>
              <div class="block">
              <div class="border shadow-md rounded-lg border-gray-200 p-6 my-10 bg-green-50">
                <h3 class="text-2xl font-semibold text-gray-900 pb-3">Designation</h3>

                  <div class="mt-6 grid grid-cols-3 gap-4">
                    <div class="">
                        <label
                          for="designation_id"
                          class="block text-sm md:text-base font-medium text-gray-900"
                          >Designation
                          <span class="text-red-500">*</span>
                        </label>
                        <multiselect
                        class="mt-1"
                          v-model="selectedDesignation"
                          :options="designations"
                          :custom-label="designationCustomLabel"
                          @select="onDesignationSelect"
                          label="name"
                          track-by="id"
                          placeholder="Select a Designation"
                        ></multiselect>
                        <InputError
                          v-if="employee?.errors?.designation_id"
                          :message="employee?.errors?.designation_id"
                        />
                      </div>

                      <template v-if="employee?.is_doctor">
                    <div class="">
                      <label
                        for="visiting_charges"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Doctor Type</label
                      >
                      <div class="mt-1">
                        <multiselect
                          v-model="selectedDoctorType"
                          :options="doctorTypeOptions"
                          @select="onDoctorTypeSelect"
                          label="label"
                          track-by="value"
                          placeholder="Select Type"
                          ></multiselect>
                      </div>
                      <InputError
                        v-if="employee?.errors?.doctor_type"
                        :message="employee?.errors?.doctor_type"
                      />
                    </div>
                    <div class="">
                      <label
                        for="visiting_charges"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Visiting Charges</label
                      >
                        <input
                          type="number"
                          name="visiting_charges"
                          id="visiting_charges"
                          autocomplete="visiting_charges"
                          class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                          step="0.01"
                          v-model="employee.visiting_charges"
                        />
                      <InputError
                        v-if="employee?.errors?.visiting_charges"
                        :message="employee?.errors?.visiting_charges"
                      />
                    </div>
                    <div class="">
                      <label
                        for="consulting_charges"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Consulting Charges</label
                      >
                        <input
                          type="number"
                          name="consulting_charges"
                          id="consulting_charges"
                          autocomplete="consulting_charges"
                          class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                          step="0.01"
                          v-model="employee.consulting_charges"
                        />
                      <InputError
                        v-if="employee?.errors?.consulting_charges"
                        :message="employee?.errors?.consulting_charges"
                      />
                    </div>
                    <div class="">
                      <label
                        for="shares"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Shares</label
                      >
                        <input
                          type="number"
                          name="shares"
                          id="shares"
                          autocomplete="shares"
                          class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                          step="0.01"
                          v-model="employee.shares"
                        />
                      <InputError
                        v-if="employee?.errors?.shares"
                        :message="employee?.errors?.shares"
                      />
                    </div>
                    <div class="">
                      <label
                        for="shares"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Shares Type</label
                      >
                      <select
                        type="number"
                        name="shares_type"
                        id="shares_type"
                        autocomplete="shares_type"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="employee.shares_type"
                      >
                        <option value="Percentage">Percentage</option>
                        <option value="Fixed">Fixed</option>
                      </select>
                      <InputError
                        v-if="employee?.errors?.shares_type"
                        :message="employee?.errors?.shares_type"
                      />
                    </div>
                    <div class="">
                      <label
                        for="doctor_template"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Template</label
                      >
                      <select
                        name="doctor_template"
                        id="doctor_template"
                        autocomplete="doctor_template"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        step="0.01"
                        v-model="employee.doctor_template"
                      >
                        <option value="Print2">General</option>
                        <option value="Print3">Specialist</option>
                        <option value="Print1">Template 3</option>
                        <option value="Print4">Template 4</option>
                        <option value="Print5">MO</option>
                      </select>
                      <InputError
                        v-if="employee?.errors?.doctor_template"
                        :message="employee?.errors?.doctor_template"
                      />
                    </div>
                    <div class="" v-if="hasAccountsModule">
                      <label
                        for="coa_id"
                        class="block text-sm md:text-base font-medium text-gray-900"
                        >Account Head</label
                      >
                      <multiselect
                        v-model="selectedCoa"
                        :options="coa"
                        @update:modelValue="onCoaSelect"
                        label="acc_desc"
                        track-by="id"
                        placeholder="Select a Account Head"
                      ></multiselect>
                      <InputError
                        v-if="employee?.errors?.coa_id"
                        :message="employee?.errors?.coa_id"
                      />
                    </div>
                  </template>
                    </div>
              </div>
            </div>

              <!-- Education section -->
              <div class="block">
              <div class="border shadow-md rounded-lg border-gray-200 p-6 my-10 bg-green-50">
                <h3 class="text-2xl font-semibold text-gray-900 pb-3">Education</h3>
                  <div class="border rounded mb-2">
                    <div class="flex divide-x border-b">
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="card-cvc" class="text-center font-medium text-sm">Institute</label>
                      </div>
                      <div class="w-1/2 min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="card-cvc" class="text-center font-medium text-sm" >Degree</label>
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="card-cvc" class="text-center font-medium text-sm">Passing Year</label>
                      </div>
                      <div class="w-1/2 min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="card-cvc" class="text-center font-medium text-sm" >Result</label>
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="card-cvc" class="text-center font-medium text-sm">GP/CGPA</label>
                      </div>
                      <div class="flex items-center p-1 w-[49px]"></div>
                    </div>
                  <div
                    v-for="(education, index) in employee.education"
                    :key="index"
                  >
                    <div class="flex divide-x border-b">
                      <div class="w-1/2 min-w-0 flex-1 p-1 flex items-center justify-center">
                        <input
                          v-model="education.institute"
                          type="text"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Institute"
                        />
                        <InputError
                          v-if="
                            employee.errors?.[`education.${index}.institute`]
                          "
                          :message="
                            employee.errors?.[`education.${index}.institute`]
                          "
                        />
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <input
                          v-model="education.degree"
                          type="text"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Degree"
                        />
                        <InputError
                          v-if="employee.errors?.[`education.${index}.degree`]"
                          :message="
                            employee.errors?.[`education.${index}.degree`]
                          "
                        />
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <input
                          v-model="education.passing_year"
                          type="number"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Passing Year"
                        />
                        <InputError
                          v-if="
                            employee.errors?.[`education.${index}.passing_year`]
                          "
                          :message="
                            employee.errors?.[`education.${index}.passing_year`]
                          "
                        />
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <input
                          v-model="education.result"
                          type="number"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Result"
                        />
                        <InputError
                          v-if="employee.errors?.[`education.${index}.result`]"
                          :message="
                            employee.errors?.[`education.${index}.result`]
                          "
                        />
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <input
                          v-model="education.cgpa"
                          type="number"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="CGPA"
                        />
                        <InputError
                          v-if="employee.errors?.[`education.${index}.cgpa`]"
                          :message="
                            employee.errors?.[`education.${index}.cgpa`]
                          "
                        />
                      </div>
                      <div class="flex items-center p-1">
                        <button
                          type="button"
                          @click="removeEducation(index)"
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
                    @click="addEducation"
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
              <!-- Experience section -->
              <div>
              <div class="border shadow-md rounded-lg border-gray-200 p-6 my-10 bg-green-50">
                  <h3 class="text-2xl font-semibold text-gray-900 pb-3">Experience</h3>
                  <div class="border rounded mb-2">
                    <div class="flex divide-x border-b">
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="card-cvc" class="text-center font-medium text-sm">Experience</label>
                      </div>
                      <div class="w-1/2 min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="card-cvc" class="text-center font-medium text-sm">designation</label>
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="card-cvc" class="text-center font-medium text-sm">Job Period</label>
                      </div>
                      <div class="w-1/2 min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="card-cvc" class="text-center font-medium text-sm">Responsibility</label>
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <label for="card-cvc" class="text-center font-medium text-sm">Skills</label>
                      </div>
                      <div class="flex items-center p-1 w-[49px]"></div>
                    </div>
                  <div
                    v-for="(experience, index) in employee.experience"
                    :key="index"
                  >
                    <div class="flex divide-x border-b">
                      <div class="w-1/2 min-w-0 flex-1 p-1 flex items-center justify-center">
                        <input
                          v-model="experience.company"
                          type="text"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="company"
                        />
                        <InputError
                          v-if="
                            employee.errors?.[`experience.${index}.company`]
                          "
                          :message="
                            employee.errors?.[`experience.${index}.company`]
                          "
                        />
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <input
                          v-model="experience.designation"
                          type="text"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Designation"
                        />
                        <InputError
                          v-if="
                            employee.errors?.[`experience.${index}.designation`]
                          "
                          :message="
                            employee.errors?.[`experience.${index}.designation`]
                          "
                        />
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <input
                          v-model="experience.job_period"
                          type="text"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Job Period"
                        />
                        <InputError
                          v-if="
                            employee.errors?.[`experience.${index}.job_period`]
                          "
                          :message="
                            employee.errors?.[`experience.${index}.job_period`]
                          "
                        />
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <input
                          v-model="experience.responsibility"
                          type="text"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Responsibility"
                        />
                        <InputError
                          v-if="
                            employee.errors?.[
                              `experience.${index}.responsibility`
                            ]
                          "
                          :message="
                            employee.errors?.[
                              `experience.${index}.responsibility`
                            ]
                          "
                        />
                      </div>
                      <div class="min-w-0 flex-1 p-1 flex items-center justify-center">
                        <input
                          v-model="experience.skills"
                          type="text"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Skills"
                        />
                        <InputError
                          v-if="employee.errors?.[`experience.${index}.skills`]"
                          :message="
                            employee.errors?.[`experience.${index}.skills`]
                          "
                        />
                      </div>
                      <div class="flex items-center p-1">
                        <button
                          type="button"
                          @click="removeExperience(index)"
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
                    @click="addExperience"
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
              <!-- Privacy section -->
              <div>
              <div class="divide-y divide-gray-200 pt-6">
                  <ul role="list" class="mt-2 divide-y divide-gray-200">
                    <li class="flex items-center justify-between py-4">
                      <div class="flex flex-col">
                        <p
                          class="block text-sm md:text-base font-medium text-gray-900"
                          id="privacy-option-3-label"
                        >
                          Allow login
                        </p>
                        <p
                          class="text-sm text-gray-900"
                          id="privacy-option-3-description"
                        >
                          Allow employee to login to dashboard based on it's
                          role.
                        </p>
                      </div>
                      <button
                        type="button"
                        :class="[
                          employee.login_allowed
                            ? 'bg-teal-500'
                            : 'bg-gray-200',
                          'relative ml-4 inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2  focus:ring-sky-500 focus:ring-offset-2',
                        ]"
                        role="switch"
                        :aria-checked="employee.login_allowed"
                        @click="
                          employee.login_allowed = !employee.login_allowed
                        "
                      >
                        <span
                          aria-hidden="true"
                          :class="[
                            employee.login_allowed
                              ? 'translate-x-5'
                              : 'translate-x-0',
                            'inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                          ]"
                        ></span>
                      </button>
                    </li>
                    <li
                      class="items-center justify-between py-4"
                      v-show="employee.login_allowed"
                    >
                      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                          <label
                            for="password"
                            class="block text-sm md:text-base font-medium text-gray-900"
                            >Password
                            <span class="text-red-500">*</span>
                          </label>
                          <input
                            type="password"
                            name="password"
                            id="password"
                            autocomplete=""
                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                            v-model="employee.password"
                          />
                          <InputError
                            v-if="employee.errors?.password"
                            :message="employee.errors?.password"
                          />
                        </div>
                        <div>
                          <label
                            for="confirm-password"
                            class="block text-sm md:text-base font-medium text-gray-900"
                            >Confirm Password
                            <span class="text-red-500">*</span>
                          </label>
                          <input
                            type="password"
                            name="confirm-password"
                            id="confirm-password"
                            autocomplete=""
                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                            v-model="employee.password_confirmation"
                          />
                          <InputError
                            v-if="employee.errors?.password_confirmation"
                            :message="employee.errors?.password_confirmation"
                          />
                        </div>
                        <div>
                          <label
                            for="roles"
                            class="block text-sm md:text-base font-medium text-gray-900"
                            >Roles
                            <span class="text-red-500">*</span>
                          </label>
                          <div class="mt-1">
                            <multiselect
                              v-model="selectedRole"
                              :options="roleOptions"
                              :custom-label="roleCustomLabel"
                              @select="onRoleSelect"
                              @remove="onRoleUnselect"
                              label="name"
                              track-by="id"
                              placeholder="Select a role"
                              :multiple="true"
                            ></multiselect>
                          </div>
                          <InputError
                            v-if="employee?.errors?.roles"
                            :message="employee?.errors?.roles"
                          />
                        </div>
                      </div>
                    </li>
                  </ul>
                </div>
                <div class="mt-4 flex justify-end gap-x-3 px-4 py-4 sm:px-6">
                  <InertiaLink
                    :href="route('employees.index')"
                    class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  >
                    Cancel
                  </InertiaLink>
                  <button
                    type="submit"
                    class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                    :disabled="employee.processing"
                  >
                    Save
                  </button>
                </div>
              </div>
            </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>


<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import { ref, watchEffect, computed, onMounted } from "vue";
import vueFilePond from "vue-filepond";
import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";
import FilePondPluginFilePoster from "filepond-plugin-file-poster";
import "filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css";
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import Multiselect from "vue-multiselect";
import axios from "axios";

const FilePond = vueFilePond(
  FilePondPluginFileValidateType,
  FilePondPluginImagePreview,
  FilePondPluginFilePoster
);

const props = defineProps({
  hasAccountsModule:Boolean,
  employee: { type: Object, default: null },
  roles: { type: Object, required: true },
  employees: { type: Object, required: true },
  coa: { type: Array, required: true, default: () => [] },
  countries: { type: Array, required: true, default: () => [] },
  departments: { type: Array, required: true, default: () => [] },
  designations: { type: Array, required: true, default: () => [] },
  grades: { type: Array, required: true, default: () => [] },
  states: { type: Array, required: false, default: () => [] },
  cities: { type: Array, required: false, default: () => [] },
  selectedRoles: { type: Array, required: false, default: () => [] },
});

const employee = useForm({
  id: props?.employee?.id || null,
  name: props?.employee?.name || null,
  machine_id: props?.employee?.machine_id || null,
  serial_no: props?.employee?.serial_no || 1,
  coa_id: props?.employee?.coa_id || null,
  email: props?.employee?.email,
  password: null,
  password_confirmation: null,
  mobile: props?.employee?.mobile || null,
  landline: props?.employee?.landline || null,
  street_address: props?.employee?.street_address || null,
  postal_code: props?.employee?.postal_code || null,
  doj: props?.employee?.doj || null,
  dob: props?.employee?.dob || null,
  probation_start_date: props?.employee?.probation_start_date || null,
  probation_end_date: props?.employee?.probation_end_date,
  notice_period_start_date: props?.employee?.notice_period_start_date || null,
  notice_period_end_date: props?.employee?.notice_period_end_date || null,
  gender: props?.employee?.gender || null,
  reporting_to: props?.employee?.reporting_to || null,
  image: props?.employee?.image || null,
  login_allowed: props?.employee?.user ? true : false,
  slack_id: props?.employee?.slack_id || null,
  about_employee: props?.employee?.about_employee || null,
  religion: props?.employee?.religion || null,
  emergency_contact: props?.employee?.emergency_contact || null,
  skills: props?.employee?.skills || null,
  shares_type: props?.employee?.shares_type || null,
  doctor_template: props?.employee?.doctor_template || null,
  shares: props?.employee?.shares || null,
  employee_type: props?.employee?.employee_type || null,
  marital_status: props?.employee?.marital_status || null,
  blood_group: props?.employee?.blood_group || null,
  account_name: props?.employee?.account_name || null,
  account_number: props?.employee?.account_number || null,
  bank_name: props?.employee?.bank_name || null,
  bank_location: props?.employee?.bank_location || null,
  tax_payer_id: props?.employee?.tax_payer_id || null,
  basic_salary: props?.employee?.basic_salary || null,
  salary_currency: props?.employee?.salary_currency || null,
  roles: props?.employee?.roles || [],
  country_id: props?.employee?.country_id || null,
  state_id: props?.employee?.state_id || null,
  city_id: props?.employee?.city_id || null,
  department_id: props?.employee?.department_id || null,
  designation_id: props?.employee?.designation_id || null,
  doctor_type: props?.employee?.doctor_type || null,
  grade_id: props?.employee?.grade_id || null,
  is_doctor: props?.employee?.designation?.is_doctor || false,
  visiting_charges: props?.employee?.doctor_charges?.visiting_charges || null,
  consulting_charges:
    props?.employee?.doctor_charges?.consulting_charges || null,
  education: props?.employee?.education || [
    {
      institute: "",
      degree: "",
      passing_year: "",
      result: "",
      cgpa: "",
    },
  ],
  experience: props?.employee?.experience || [
    {
      company: "",
      designation: "",
      job_period: "",
      responsibility: "",
      skills: "",
    },
  ],
  _method: props?.employee?.id ? "put" : "post",
});

const myFiles = ref([]);
const csrfToken = document
  .querySelector('meta[name="csrf-token"]')
  .getAttribute("content");
let init = ref(false);

const handleFilePondInit = () => {
  if (props?.employee?.image) {
    myFiles.value = [
      {
        source: "/uploads/employees/" + employee.image,
        options: {
          type: "local",
          metadata: {
            poster: "/uploads/employees/" + employee.image,
          },
        },
      },
    ];
  } else {
    myFiles.value = [];
  }
};

const handleFilePondLoad = (response) => {
  const parsedResponse = JSON.parse(response);
  const filename = parsedResponse.image;

  if (!init.value) {
    employee.image = ref(filename);
    init.value = true;
  } else {
    employee.image.value = filename;
  }
  console.log(employee.image.value);
};


const selectedDoctorType = ref();
const doctorTypeOptions = computed(() => {
  return [
    { value: "General", label: "General" },
    { value: "Specialist", label: "Specialist" },
  ];
});

const onDoctorTypeSelect = async (selected) => {
  employee.doctor_type = selected.value;
};

if (props?.employee) {
  const country = doctorTypeOptions.value.find(
    (c) => c.value === props.employee.doctor_type
  );
  if (country) {
    selectedDoctorType.value = country;
  }
}


const roles = ref(props.roles || []);
const countries = ref(props.countries || []);
const states = ref(props.states || []);
const cities = ref(props.cities || []);

const departments = ref(props.departments || []);
const designations = ref(props.designations || []);
const grades = ref(props.grades || []);

const selectedRole = ref(props?.selectedRoles || []);
const selectedCountry = ref(null);
if (props?.employee) {
  const country = props.countries.find(
    (c) => c.id === props.employee.country_id
  );
  if (country) {
    selectedCountry.value = country;
  }
}
const selectedState = ref(null);
if (props?.employee) {
  const state = props.states.find((s) => s.id === props.employee.state_id);
  if (state) {
    selectedState.value = state;
  }
}
const selectedCity = ref(null);
if (props?.employee) {
  const city = props.cities.find((c) => c.id === props.employee.city_id);
  if (city) {
    selectedCity.value = city;
  }
}

const selectedDepartment = ref(null);
if (props?.employee) {
  const department = props.departments.find(
    (c) => c.id === props.employee.department_id
  );
  if (department) {
    selectedDepartment.value = department;
  }
}
const selectedDesignation = ref(null);
if (props?.employee) {
  const designation = props.designations.find(
    (c) => c.id === props.employee.designation_id
  );
  if (designation) {
    selectedDesignation.value = designation;
  }
}
const selectedGrade = ref(null);
if (props?.employee) {
  const grade = props.grades.find((c) => c.id === props.employee.grade_id);
  if (grade) {
    selectedGrade.value = grade;
  }
}
const roleOptions = computed(() => {
  return roles.value && roles.value.length ? roles.value : [];
});

const countryOptions = computed(() => {
  return countries.value && countries.value.length ? countries.value : [];
});

const departmentOptions = computed(() => {
  return departments.value && departments.value.length ? departments.value : [];
});

const stateOptions = computed(() => {
  return states.value && states.value.length ? states.value : [];
});

const cityOptions = computed(() => {
  return cities.value && cities.value.length ? cities.value : [];
});
const EmployeeTypeOptions = computed(() => {
  const employeeTypes = [
    { type: "Full Time" },
    { type: "Part Time" },
    { type: "On Contract" },
    { type: "Internship" },
    { type: "Trainee" },
  ];
  return employeeTypes;
});
//employee type
const selectedEmployeeType = ref(null);
if (props?.employee?.employee_type) {
  selectedEmployeeType.value = EmployeeTypeOptions.value.find(
    (option) => option.type === props.employee.employee_type
  );
}

const EmployeeTypeCustomLabel = (option) => option.type;

const onEmployeeTypeSelect = async (selectedEmployeeType) => {
  employee.employee_type = selectedEmployeeType.type;
};

// martial status
const MartialStatusOptions = computed(() => {
  const martialStatus = [
    { type: "Unmarried" },
    { type: "Married" },
    { type: "Divorced" },
    { type: "Widowed" },
  ];
  return martialStatus;
});

const selectedMartialStatus = ref(null);
if (props?.employee?.marital_status) {
  selectedMartialStatus.value = MartialStatusOptions.value.find(
    (option) => option.type === props.employee.marital_status
  );
}

const MartialStatusCustomLabel = (option) => option.type;

const onMartialStatusSelect = async (selectedMartialStatus) => {
  employee.marital_status = selectedMartialStatus.type;
};
// Blood Group
const BloodGroupOptions = computed(() => {
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
if (props?.employee?.blood_group) {
  selectedBloodGroup.value = BloodGroupOptions.value.find(
    (option) => option.type === props.employee.blood_group
  );
}
const BloodGroupCustomLabel = (option) => option.type;
const onBloodGroupSelect = async (selectedBloodGroup) => {
  employee.blood_group = selectedBloodGroup.type;
};

// Gender
const GenderOptions = computed(() => {
  const gender = [{ type: "Male" }, { type: "Female" }, { type: "Others" }];
  return gender;
});

const selectedGender = ref(null);
if (props?.employee?.gender) {
  selectedGender.value = GenderOptions.value.find(
    (option) => option.type === props.employee.gender
  );
}

const GenderCustomLabel = (option) => option.type;
const onGenderSelect = async (selectedGender) => {
  employee.gender = selectedGender.type;
};
// Salary Currency
const SalaryCurrencyOptions = computed(() => {
  const salaryCurrency = [{ type: "PKR" }, { type: "USD" }];
  return salaryCurrency;
});

const selectedSalaryCurrency = ref(null);
if (props?.employee?.salary_currency) {
  selectedSalaryCurrency.value = SalaryCurrencyOptions.value.find(
    (option) => option.type === props.employee.salary_currency
  );
}
const SalaryCurrencyCustomLabel = (option) => option.type;
const onSalaryCurrencySelect = async (selectedSalaryCurrency) => {
  employee.salary_currency = selectedSalaryCurrency.type;
};
// reporting to
const employees = ref(props.employees);

const employeeOptions = computed(() => {
  return employees.value && employees.value.length ? employees.value : [];
});

const employeeCustomLabel = (employee) => {
  return `${employee.name}`;
};

const selectedReportingTo = ref(null);
if (props?.employee?.reporting_to) {
  selectedReportingTo.value = employees.value.find(
    (e) => e.id === props.employee.reporting_to
  );
}

const onEmployeeSelect = (selectedEmployee) => {
  employee.reporting_to = selectedEmployee.id;
};

const onRoleUnselect = (role) => {
  let index = employee.roles.indexOf(role.id);
  if (index !== -1) {
    employee.roles.splice(index, 1);
  }
};

const onRoleSelect = (role) => {
  employee.roles.push(role.id);
};

const onCountrySelect = async (selectedCountry) => {
  employee.country_id = selectedCountry.id;
  selectedState.value = null;
  await loadStates(selectedCountry.id);
};

const onDepartmentSelect = async (selectedDepartment) => {
  employee.department_id = selectedDepartment.id;
};

const onDesignationSelect = async (selectedDesignation) => {
  employee.designation_id = selectedDesignation.id;
  employee.is_doctor = selectedDesignation.is_doctor;
};

const onGradeSelect = async (selectedGrade) => {
  employee.grade_id = selectedGrade.id;
};

const onStateSelect = async (selectedState) => {
  employee.state_id = selectedState.id;
  selectedCity.value = null;
  await loadCities(selectedState.id);
};

const onCitySelect = (selectedCity) => {
  employee.city_id = selectedCity.id;
};



const selectedCoa = ref(null);
if (props?.employee && props?.hasAccountsModule) {
  const coas = props.coa.find(
    (c) => c.id === props.employee.coa_id
  );
  if (coas) {
    selectedCoa.value = coas;
  }
}

const onCoaSelect = async (selectedCoa) => {
  employee.coa_id = selectedCoa?.id ?? null;
};


const loadStates = async (countryId) => {
  try {
    const response = await axios.get(`/states/${countryId}`);
    states.value = response.data.states;
  } catch (error) {
    console.error("Error fetching states:", error);
  }
};

const loadCities = async (stateId) => {
  try {
    const response = await axios.get(`/cities-by-state/${stateId}`);
    cities.value = response.data.cities;
  } catch (error) {
    console.error("Error fetching cities:", error);
  }
};

const roleCustomLabel = (role) => {
  return `${role.name}`;
};

const countryCustomLabel = (country) => {
  return `${country.name}`;
};
const departmentCustomLabel = (department) => {
  return `${department.name}`;
};
const designationCustomLabel = (designation) => {
  return `${designation.name}`;
};

const stateCustomLabel = (state) => {
  return `${state.name}`;
};

const cityCustomLabel = (city) => {
  return `${city.name}`;
};
//education
const addEducation = () => {
  employee.education.push({
    institute: "",
    degree: "",
    passing_year: "",
    result: "",
    cgpa: "",
  });
};

const removeEducation = (index) => {
  employee.education.splice(index, 1);
};

//experience
const addExperience = () => {
  employee.experience.push({
    company: "",
    designation: "",
    job_period: "",
    responsibility: "",
    skills: "",
  });
};

const removeExperience = (index) => {
  employee.experience.splice(index, 1);
};
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
<style>
.filepond--panel-root {
  background-color: transparent;
  border: 2px solid #2c3340;
}
.filepond--item {
  width: calc(99% - 0.5em);
  height: auto;
}
</style>
