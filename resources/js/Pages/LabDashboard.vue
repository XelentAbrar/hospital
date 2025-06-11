 <template>
    <div class="w-full mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-12 gap-4 mb-6">
            <div class="md:col-span-12 col-span-12">
                <div
                    class="grid grid-cols-12 gap-4 mb-6 bg-white py-4 px-6 rounded-lg shadow-md"
                >
                    <div class="col-span-12">
                        <form
                            @submit.prevent="submitForm()"
                            enctype="multipart/form-data"
                        >
                            <!-- Profile section -->
                            <div class="py-6">
                                <div>
                                    <h2
                                        class="text-3xl font-semibold text-gray-900"
                                    >
                                        Patient Test
                                    </h2>
                                    <!-- <p class="mt-1 text-sm text-gray-900">
                            This information will be displayed publicly so be careful what you share.
                        </p> -->
                                </div>

                                <div class="py-6">
                                    <div
                                        class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-3 lg:grid-cols-4"
                                    >
                                    <div class="">
                                <label
                                    for="mr_number"
                                    class="block text-md font-semibold leading-6 text-gray-900"
                                    >Mr Number.
                                    <!-- <span class="text-red-500">*</span> -->
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
                                    v-model="patient_test.mr_number"
                                    />
                                </div>
                                <InputError
                                    v-if="patient_test.errors?.mr_number"
                                    :message="patient_test.errors?.mr_number"
                                />
                                </div>
                                 <div class="hidden" >
                                <label
                                    for="lab_no"
                                    class="block text-md font-semibold leading-6 text-gray-900"
                                    >Lab No.
                                    <!-- <span class="text-red-500">*</span> -->
                                </label>
                                <div class="mt-2">
                                    <input
                                    id="lab_no"
                                    name="lab_no"
                                    type="text"
                                    autocomplete="lab_no"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                    step="0.01" disabled
                                    @input="generateMRNumber"
                                    @blur="loadPatient($event.target.value)"
                                    v-model="patient_test.lab_no"
                                    />
                                </div>
                                <InputError
                                    v-if="patient_test.errors?.lab_no"
                                    :message="patient_test.errors?.lab_no"
                                />
                                </div>
                                        <div class="">
                                            <label
                                                for="patient_name"
                                                class="block text-md font-semibold leading-6 text-gray-900"
                                                >Name
                                                <span class="text-red-500"
                                                    >*</span
                                                >
                                            </label>
                                            <div class="mt-2">
                                                <input
                                                    id="patient_name"
                                                    name="patient_name"
                                                    type="text"
                                                    autocomplete="name"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                    step="0.01"
                                                    v-model="
                                                        patient_test.patient_name
                                                    "
                                                />
                                            </div>
                                            <InputError
                                                v-if="
                                                    patient_test.errors
                                                        ?.patient_name
                                                "
                                                :message="
                                                    patient_test.errors
                                                        ?.patient_name
                                                "
                                            />
                                        </div>
                                        <div class="">
                                            <label
                                                for="test_nature"
                                                class="block text-md font-semibold leading-6 text-gray-900"
                                                >Test nature
                                                <span class="text-red-500"
                                                    >*</span
                                                >
                                            </label>
                                            <multiselect
                                                v-model="selectedTestNature"
                                                :options="testNatureOptions"
                                                @select="onTestNatureSelect"
                                                label="label"
                                                track-by="value"
                                                placeholder="Select test nature"
                                            ></multiselect>
                                            <InputError
                                                v-if="
                                                    patient_test?.errors
                                                        ?.test_nature
                                                "
                                                :message="
                                                    patient_test?.errors
                                                        ?.test_nature
                                                "
                                            />
                                        </div>

                                        <div class="">
                                            <label
                                                for="test_type"
                                                class="block text-md font-semibold leading-6 text-gray-900"
                                                >Test type
                                                <span class="text-red-500"
                                                    >*</span
                                                >
                                            </label>
                                            <multiselect
                                                v-model="selectedTestType"
                                                :options="testTypeOptions"
                                                @select="onTestTypeSelect"
                                                label="label"
                                                track-by="value"
                                                placeholder="Select test type"
                                            ></multiselect>
                                            <InputError
                                                v-if="
                                                    patient_test?.errors
                                                        ?.test_type
                                                "
                                                :message="
                                                    patient_test?.errors
                                                        ?.test_type
                                                "
                                            />
                                        </div>

                                        <div class="">
                                            <label
                                                for="patient_cnic"
                                                class="block text-md font-semibold leading-6 text-gray-900"
                                                >CNIC

                                            </label>
                                            <div class="mt-2">
                                                <input
                                                    id="patient_cnic"
                                                    name="patient_cnic"
                                                    type="text"
                                                    autocomplete="patient_cnic"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                    step="0.01"
                                                    @blur="loadPatient($event.target.value)"
                                                    v-model="
                                                        patient_test.patient_cnic
                                                    "
                                                   @input="formatCNIC" maxlength="15"

                                                />
                                            </div>
                                            <InputError
                                                v-if="
                                                    patient_test.errors
                                                        ?.patient_cnic
                                                "
                                                :message="
                                                    patient_test.errors
                                                        ?.patient_cnic
                                                "
                                            />
                                        </div>

                                        <div class="">
                                            <label
                                                for="patient_phone"
                                                class="block text-md font-semibold leading-6 text-gray-900"
                                                >Phone  <span class="text-red-500"
                                                    >*</span
                                                ></label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    id="patient_phone"
                                                    name="patient_phone"
                                                    type="text"
                                                    autocomplete="phone"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                    v-model="
                                                        patient_test.patient_phone
                                                    "
                                                    @blur="loadPatient($event.target.value)"
                                                @input="formatPhone" maxlength="12"

                                                />
                                            </div>
                                            <InputError
                                                v-if="
                                                    patient_test.errors
                                                        ?.patient_phone
                                                "
                                                :message="
                                                    patient_test.errors
                                                        ?.patient_phone
                                                "
                                            />
                                        </div>
                                        <div class="">
                                            <label
                                                for="referred_by"
                                                class="block text-md font-semibold leading-6 text-gray-900"
                                                >Referred By   <span class="text-red-500"
                                                    >*</span
                                                ></label>
                                            <div class="mt-2">
                                                <input
                                                    id="referred_by"
                                                    name="referred_by"
                                                    type="text"
                                                    autocomplete="referred_by"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                    v-model="
                                                        patient_test.referred_by
                                                    "
                                                />
                                            </div>
                                            <InputError
                                                v-if="
                                                    patient_test.errors
                                                        ?.referred_by
                                                "
                                                :message="
                                                    patient_test.errors
                                                        ?.referred_by
                                                "
                                            />
                                        </div>

                                        <div class="">
                                            <label
                                                for="patient_address"
                                                class="block text-md font-semibold leading-6 text-gray-900"
                                                >Address</label
                                            >
                                            <div class="mt-2">
                                                <input
                                                    id="patient_address"
                                                    name="patient_address"
                                                    type="text"
                                                    autocomplete="patient_address"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                    v-model="
                                                        patient_test.patient_address
                                                    "
                                                />
                                            </div>
                                            <InputError
                                                v-if="
                                                    patient_test.errors
                                                        ?.patient_address
                                                "
                                                :message="
                                                    patient_test.errors
                                                        ?.patient_address
                                                "
                                            />
                                        </div>
                                        <!-- <div>
                                            <label
                                                for="test_category_id"
                                                class="block text-md font-semibold leading-6 text-gray-900"
                                                >Test
                                                <span class="text-red-500"
                                                    >*</span
                                                >
                                            </label>
                                            <div class="mt-1">
                                                <multiselect
                                                    v-model="selectedTest"
                                                    :options="test_categories"
                                                    @select="onTestSelect"
                                                    label="name"
                                                    track-by="id"
                                                    placeholder="Select a Test"
                                                ></multiselect>
                                            </div>
                                            <InputError
                                                v-if="
                                                    patient_test?.errors
                                                        ?.test_category_id
                                                "
                                                :message="
                                                    patient_test?.errors
                                                        ?.test_category_id
                                                "
                                            />
                                        </div> -->

                                        <div>
                                            <label
                                                for="patient_gender"
                                                class="block text-md font-semibold leading-6 text-gray-900"
                                                >Gender</label
                                            >
                                            <div
                                                class="mt-2 flex rounded-md shadow-sm"
                                            >
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
                                                v-if="
                                                    patient_test.errors
                                                        ?.patient_gender
                                                "
                                                :message="
                                                    patient_test.errors
                                                        ?.patient_gender
                                                "
                                            />
                                        </div>
                                        <div class="">
                                            <label
                                                for="patient_age"
                                                class="block text-md font-semibold leading-6 text-gray-900"
                                                >Age
                                                <span class="text-red-500"
                                                    >*</span
                                                >
                                            </label>
                                            <div class="mt-2">
                                                <input
                                                    id="patient_age"
                                                    name="patient_age"
                                                    type="text"
                                                    autocomplete="patient_age"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                    step="0.01"
                                                    v-model="
                                                        patient_test.patient_age
                                                    "
                                                />
                                            </div>
                                            <InputError
                                                v-if="
                                                    patient_test.errors
                                                        ?.patient_age
                                                "
                                                :message="
                                                    patient_test.errors
                                                        ?.patient_age
                                                "
                                            />
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
                                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                                                    step="0.01"
                                                    v-model="
                                                        patient_test.file_no
                                                    "
                                                />
                                            </div>
                                            <InputError
                                                v-if="
                                                    patient_test.errors?.file_no
                                                "
                                                :message="
                                                    patient_test.errors?.file_no
                                                "
                                            />
                                        </div> -->

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
                                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                                                    v-model="
                                                        patient_test.welfare
                                                    "
                                                />
                                            </div>
                                            <InputError
                                                v-if="
                                                    patient_test.errors?.welfare
                                                "
                                                :message="
                                                    patient_test.errors?.welfare
                                                "
                                            />
                                        </div>

                                    </div>
                                </div>
                                <!-- <div
                                    class="divide-y divide-gray-200 pt-4 pb-4"
                                    v-if="patient_test?.test_nature == 'General'"
                                >

                                    <div
                                        v-for="(
                                            packageDetail, packageIndex
                                        ) in patient_test.patient_test_details"
                                        :key="packageIndex"
                                    >
                                        <div
                                            class="mb-1 px-4 sm:px-6 grid lg:grid-cols-3 md:grid-cols-3 sm:grid-cols-1 gap-2"
                                        >
                                            <div>
                                                <label
                                                    for="card-expiration-date"
                                                    class="block text-sm md:text-base font-medium text-gray-900"
                                                    >Package
                                                </label>
                                                <div class="mt-1">
                                                    <multiselect
                                                        v-model="
                                                            packageDetail.selectedPackage
                                                        "
                                                        :options="
                                                            propss.packages
                                                        "
                                                        @update:modelValue="
                                                            (selectedPackage) =>
                                                                onPackageSelect(
                                                                    selectedPackage,
                                                                    packageIndex
                                                                )
                                                        "
                                                        label="name"
                                                        track-by="id"
                                                        placeholder="Select a Package"
                                                    ></multiselect>
                                                </div>
                                            </div>
                                            <InputError
                                                v-if="
                                                    packageDetail?.errors
                                                        ?.package_id
                                                "
                                                :message="
                                                    packageDetail?.errors
                                                        ?.package_id
                                                "
                                            />
                                            <div>
                                                <label
                                                    for="card-expiration-date"
                                                    class="block text-sm md:text-base font-medium text-gray-900"
                                                    >Remarks
                                                </label>
                                                <div class="mt-1">
                                                    <textarea
                                                        id="remarks"
                                                        name="remarks"
                                                        type="text"
                                                        autocomplete="remarks"
                                                        class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                        step="0.01"
                                                        v-model="
                                                            packageDetail.remarks
                                                        "
                                                    ></textarea>
                                                </div>
                                            </div>
                                            <InputError
                                                v-if="
                                                    packageDetail?.errors
                                                        ?.package_id
                                                "
                                                :message="
                                                    packageDetail?.errors
                                                        ?.package_id
                                                "
                                            />
                                            <div class="mt-8 flex justify-end">
                                            <button
                                            type="button"
                                            @click="
                                                removePackageDetail(
                                                    packageIndex
                                                )
                                            "
                                                            class="inline-flex items-center justify-center ml-2 px-3 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
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

                                        <div class="px-4 sm:px-6">
                                            <h3
                                                class="text-lg font-semibold py-2"
                                            >
                                                General test detail
                                            </h3>
                                            <InputError
                                                v-if="
                                                    packageDetail.errors?.[
                                                        `test_general_details`
                                                    ]
                                                "
                                                :message="
                                                    packageDetail.errors?.[
                                                        `test_general_details`
                                                    ]
                                                "
                                            />

                                            <div
                                                v-for="(
                                                    test_general_detail, index
                                                ) in packageDetail.test_general_details"
                                                :key="index"
                                                class="my-2"
                                            >
                                                <div
                                                    class="flex -space-x-px gap-2"
                                                >
                                                    <div
                                                        class="w-1/2 min-w-0 flex-1"
                                                    >
                                                        <label
                                                            for="card-expiration-date"
                                                            class="sr-only"
                                                        >
                                                            Test
                                                            <span
                                                                class="text-red-500"
                                                                >*</span
                                                            ></label
                                                        >
                                                        <div class="">
                                                            <multiselect
                                                                v-model="
                                                                    test_general_detail.test_category
                                                                "
                                                                :options="
                                                                    propss.test_categories
                                                                "
                                                                @select="
                                                                    onTestSelect(
                                                                        $event,
                                                                        packageIndex,
                                                                        index
                                                                    )
                                                                "
                                                                label="name"
                                                                track-by="id"
                                                                placeholder="Select a Test"
                                                            ></multiselect>
                                                        </div>
                                                        <InputError
                                                            v-if="
                                                                packageDetail
                                                                    .errors?.[
                                                                    `test_general_details.${index}.test_category_id`
                                                                ]
                                                            "
                                                            :message="
                                                                packageDetail
                                                                    .errors?.[
                                                                    `test_general_details.${index}.test_category_id`
                                                                ]
                                                            "
                                                        />
                                                    </div>
                                                    <div
                                                        class="w-1/2 min-w-0 flex-1"
                                                    >
                                                        <label
                                                            for="card-expiration-date"
                                                            class="sr-only"
                                                            >Service</label
                                                        >
                                                        <multiselect
                                                            v-model="
                                                                test_general_detail.lab_test
                                                            "
                                                            :options="
                                                                propss.lab_tests.filter(
                                                                (res) => res.test_category_id === test_general_detail?.test_category?.id
                                                            )
                                                            "
                                                            @update:modelValue="
                                                                calculatePaymentCharges
                                                            "
                                                            label="name"
                                                            track-by="id"
                                                            placeholder="Select Test"
                                                        ></multiselect>
                                                        <InputError
                                                            v-if="
                                                                packageDetail
                                                                    .errors?.[
                                                                    `test_general_details.${index}.lab_test.id`
                                                                ]
                                                            "
                                                            :message="
                                                                packageDetail
                                                                    .errors?.[
                                                                    `test_general_details.${index}.lab_test.id`
                                                                ]
                                                            "
                                                        />
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <label
                                                            for="card-cvc"
                                                            class="sr-only"
                                                            >Result</label
                                                        >
                                                        <input
                                                            v-model="
                                                                test_general_detail.result
                                                            "
                                                            type="text"
                                                            class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                            placeholder="Result"
                                                        />
                                                        <InputError
                                                            v-if="
                                                                packageDetail
                                                                    .errors?.[
                                                                    `test_general_details.${index}.result`
                                                                ]
                                                            "
                                                            :message="
                                                                packageDetail
                                                                    .errors?.[
                                                                    `test_general_details.${index}.result`
                                                                ]
                                                            "
                                                        />
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <label
                                                            for="card-cvc"
                                                            class="sr-only"
                                                            >Result 2</label
                                                        >
                                                        <input
                                                            v-model="
                                                                test_general_detail.result_2
                                                            "
                                                            type="text"
                                                            class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                            placeholder="Result 2"
                                                        />
                                                        <InputError
                                                            v-if="
                                                                packageDetail
                                                                    .errors?.[
                                                                    `test_general_details.${index}.result_2`
                                                                ]
                                                            "
                                                            :message="
                                                                packageDetail
                                                                    .errors?.[
                                                                    `test_general_details.${index}.result_2`
                                                                ]
                                                            "
                                                        />
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <label
                                                            for="card-cvc"
                                                            class="sr-only"
                                                            >Description</label
                                                        >
                                                        <input
                                                            v-model="
                                                                test_general_detail.description
                                                            "
                                                            type="text"
                                                            class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                            placeholder="Description"
                                                        />
                                                        <InputError
                                                            v-if="
                                                                packageDetail
                                                                    .errors?.[
                                                                    `test_general_details.${index}.description`
                                                                ]
                                                            "
                                                            :message="
                                                                packageDetail
                                                                    .errors?.[
                                                                    `test_general_details.${index}.description`
                                                                ]
                                                            "
                                                        />
                                                    </div>
                                                    <div>
                                                        <button
                                                            type="button"
                                                            @click="
                                                                removeTestGeneralDetail(
                                                                    packageIndex,
                                                                    index
                                                                )
                                                            "
                                                            class="inline-flex items-center justify-center ml-2 px-3 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
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
                                            <button
                                                type="button"
                                                @click="
                                                    addTestGeneralDetail(
                                                        packageIndex
                                                    )
                                                "
                                                class="inline-flex items-center mt-1 px-3 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                v-if="
                                                    packageDetail?.selectedPackage ==
                                                    null
                                                "
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
                                        <button
                                            type="button"
                                            @click="
                                                addNewPackageDetail(
                                                    packageIndex
                                                )
                                            "
                                            class="inline-flex items-center mt-1 px-3 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
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
                                            Add New Package
                                        </button>

                                    </div>

                                </div> -->
                                <div
                                class="divide-y divide-gray-200 pt-4 pb-4 border"
                                v-if="patient_test?.test_nature == 'General'"
                            >

                                <div
                                    v-for="(
                                        packageDetail, packageIndex
                                    ) in patient_test.patient_test_details"
                                    :key="packageIndex"
                                >

                                    <div
                                        class="mb-1 px-4 sm:px-6 grid lg:grid-cols-5 md:grid-cols-5 sm:grid-cols-1 gap-2"
                                    >
                                    <div>
                                        <label
                                                for="card-expiration-date"
                                                class="block text-sm md:text-base font-medium text-gray-900"
                                                >Text Category
                                            </label>
                                        <div class="">
                                            <multiselect
                                                v-model="packageDetail.test_category_data"
                                                :options="propss.test_categories"
                                                label="name"
                                                track-by="id"
                                                placeholder="Select a Test"
                                            ></multiselect>
                                        </div>

                                            <InputError
                                                v-if="
                                                    packageDetail
                                                        .errors?.[
                                                        `test_general_details.${index}.test_category_id`
                                                    ]
                                                "
                                                :message="
                                                    packageDetail
                                                        .errors?.[
                                                        `test_general_details.${index}.test_category_id`
                                                    ]
                                                "
                                            />
                                        </div>
                                        <div>
                                            <label
                                                for="card-expiration-date"
                                                class="block text-sm md:text-base font-medium text-gray-900"
                                                >Package <span v-if="packageDetail?.selectedPackage != null"> ({{ packageDetail?.selectedPackage?.price }})</span>
                                            </label>
                                            <div class="mt-1">
                                                <multiselect
                                                    v-model="
                                                        packageDetail.selectedPackage
                                                    "
                                                    :options="propss.packages"
                                                    @update:modelValue="
                                                        (selectedPackage) =>
                                                            onPackageSelect(
                                                                selectedPackage,
                                                                packageIndex
                                                            )
                                                    "
                                                    label="name"
                                                    track-by="id"
                                                    placeholder="Select a Package"
                                                ></multiselect>
                                            </div>
                                        </div>
                                        <InputError
                                            v-if="
                                                packageDetail?.errors
                                                    ?.package_id
                                            "
                                            :message="
                                                packageDetail?.errors
                                                    ?.package_id
                                            "
                                        />

                                        <div>
                                        <div>
                                            <label
                                                for="card-expiration-date"
                                                class="block text-sm md:text-base font-medium text-gray-900"
                                                >Test Package  <span > ({{ totalSubPackagePrice(packageDetail) }})</span>
                                            </label>
                                            <div class="mt-1">
                                                <multiselect
                                                    v-model="packageDetail.selectedSubPackages"
                                                    :options="propss.sub_packages"
                                                    @update:modelValue="
                                                        (selectedSubPackage) =>
                                                            onSubPackageSelect(
                                                                selectedSubPackage,
                                                                packageIndex
                                                            )
                                                    "
                                                    label="name"
                                                    track-by="id"
                                                    placeholder="Select a Test Package"
                                                    multiple
                                                ></multiselect>

                                                    </div>
                                                    <InputError
                                                        v-if="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.sub_package_id`
                                                            ]
                                                        "
                                                        :message="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.sub_package_id`
                                                            ]
                                                        "
                                                    />
                                                </div>
                                              </div>
                                        <div>
                                            <label
                                                for="card-expiration-date"
                                                class="block text-sm md:text-base font-medium text-gray-900"
                                                >Remarks
                                            </label>
                                            <div class="mt-1">
                                                <textarea
                                                    id="remarks"
                                                    name="remarks"
                                                    type="text"
                                                    autocomplete="remarks"
                                                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01"
                                                    v-model="
                                                        packageDetail.remarks
                                                    "
                                                ></textarea>
                                            </div>
                                        </div>
                                        <InputError
                                            v-if="
                                                packageDetail?.errors
                                                    ?.package_id
                                            "
                                            :message="
                                                packageDetail?.errors
                                                    ?.package_id
                                            "
                                        />
                                        <div class="mt-4 py-6 flex justify-end" v-if="propss.roles?.role_id === 1">
                                            <button
                                                type="button"
                                                @click="
                                                    removePackageDetail(
                                                        packageIndex
                                                    )
                                                "
                                                class="inline-flex items-center justify-center ml-2 px-3 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
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

                                    <div class="px-4 sm:px-6" v-if="packageDetail?.selectedPackage == null && packageDetail.selectedSubPackages == null ">
                                        <h3 class="text-lg font-semibold py-2">
                                            General test detail
                                        </h3>
                                        <InputError
                                            v-if="
                                                packageDetail.errors?.[
                                                    `test_general_details`
                                                ]
                                            "
                                            :message="
                                                packageDetail.errors?.[
                                                    `test_general_details`
                                                ]
                                            "
                                        />

                                        <div
                                            v-for="(
                                                test_general_detail, index
                                            ) in packageDetail.test_general_details"
                                            :key="index"
                                            class="my-2"
                                        >

                                        <div v-if="index === 0 || packageDetail.test_general_details[index-1]?.sub_package?.id !== test_general_detail?.sub_package?.id" class="font-semibold text-blue-600 mb-2">
                                            {{ test_general_detail?.sub_package?.name }}
                                        </div>
                                            <div class="flex -space-x-px gap-2">
                                                <!-- <div
                                                    class="w-1/2 min-w-0 flex-1"
                                                >
                                                    <label
                                                        for="card-expiration-date"
                                                        class="sr-only"
                                                    >
                                                        Test
                                                        <span
                                                            class="text-red-500"
                                                            >*</span
                                                        ></label
                                                    > -->
                                                    <!-- <div class="">
                                                        <multiselect
                                                            v-model="
                                                                test_general_detail.test_category
                                                            "
                                                            :options="
                                                                propss.test_categories
                                                            "
                                                            @select="
                                                                onTestSelect(
                                                                    $event,
                                                                    packageIndex,
                                                                    index
                                                                )
                                                            "
                                                            label="name"
                                                            track-by="id"
                                                            placeholder="Select a Test"
                                                        ></multiselect>
                                                    </div>
                                                    <InputError
                                                        v-if="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.test_category_id`
                                                            ]
                                                        "
                                                        :message="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.test_category_id`
                                                            ]
                                                        "
                                                    />
                                                </div> -->
                                                <div
                                                    class="w-1/2 min-w-0 flex-1"
                                                >
                                                    <label
                                                        for="card-expiration-date"
                                                        class="sr-only"
                                                        >Service</label
                                                    >
                                                    <multiselect
                                                        v-model="
                                                            test_general_detail.lab_test
                                                        "
                                                         :options="propss.lab_tests"
                                                        @update:modelValue="
                                                            calculatePaymentCharges
                                                        "
                                                           :custom-label="labTestLabel"
                                                        track-by="id"
                                                        placeholder="Select Test"
                                                    ></multiselect>
                                                    <InputError
                                                        v-if="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.lab_test.id`
                                                            ]
                                                        "
                                                        :message="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.lab_test.id`
                                                            ]
                                                        "
                                                    />
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <label
                                                        for="card-cvc"
                                                        class="sr-only"
                                                        >Result</label
                                                    >
                                                    <input
                                                        v-model="
                                                            test_general_detail.result
                                                        "
                                                        type="text"
                                                        class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        placeholder="Result"
                                                    />
                                                    <InputError
                                                        v-if="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.result`
                                                            ]
                                                        "
                                                        :message="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.result`
                                                            ]
                                                        "
                                                    />
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <label
                                                        for="card-cvc"
                                                        class="sr-only"
                                                        >Result 2</label
                                                    >
                                                    <multiselect
                                                        v-model="test_general_detail.lab_test_detail"
                                                        :modelValue="test_general_detail.result_2"
                                                         :options="propss.lab_test_details.filter((res) => {
                                                            return res.lab_test_id === test_general_detail.lab_test?.id;
                                                        })"
                                                        label="test_report"
                                                        track-by="id"
                                                        placeholder="Select Report"
                                                    ></multiselect>
                                                    <!-- <input
                                                        v-model="
                                                            test_general_detail.result_2
                                                        "
                                                        type="text"
                                                        class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        placeholder="Result 2"
                                                    /> -->
                                                    <InputError
                                                        v-if="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.result_2`
                                                            ]
                                                        "
                                                        :message="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.result_2`
                                                            ]
                                                        "
                                                    />
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <label
                                                        for="card-cvc"
                                                        class="sr-only"
                                                        >Description</label
                                                    >
                                                    <input
                                                        v-model="
                                                            test_general_detail.description
                                                        "
                                                        type="text"
                                                        class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        placeholder="Description"
                                                    />
                                                    <InputError
                                                        v-if="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.description`
                                                            ]
                                                        "
                                                        :message="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.description`
                                                            ]
                                                        "
                                                    />
                                                </div>
                                                <div v-if="propss.roles?.role_id === 1">
                                                    <button
                                                        type="button"
                                                        @click="
                                                            removeTestGeneralDetail(
                                                                packageIndex,
                                                                index
                                                            )
                                                        "
                                                        class="inline-flex items-center justify-center ml-2 px-3 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
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
                                        <div class="font-bold"> Total Lab Test Prcie : {{ totalLabTestPrice(packageDetail) }} </div>

                                        <button
                                            type="button"
                                            @click="
                                                addTestGeneralDetail(
                                                    packageIndex
                                                )
                                            "
                                            class="inline-flex items-center mt-1 px-3 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            v-if="
                                                packageDetail?.selectedPackage ==
                                                null
                                            "
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
                                    <button
                                        type="button"
                                        @click="
                                            addNewPackageDetail(packageIndex)
                                        "
                                        class="inline-flex items-center mt-1 px-3 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
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
                                        Add New Package
                                    </button>
                                </div>
                            </div>

                            <div
                                class="divide-y divide-gray-200 pt-4 pb-4 border"
                                v-else-if="patient_test?.test_nature == 'Other_Receipt'"
                            >

                                <div
                                    v-for="(
                                        packageDetail, packageIndex
                                    ) in patient_test.patient_test_details"
                                    :key="packageIndex"
                                >



                                    <div class="px-4 sm:px-6">
                                        <h3 class="text-lg font-semibold py-2">
                                            General test detail
                                        </h3>
                                        <InputError
                                            v-if="
                                                packageDetail.errors?.[
                                                    `test_general_details`
                                                ]
                                            "
                                            :message="
                                                packageDetail.errors?.[
                                                    `test_general_details`
                                                ]
                                            "
                                        />

                                        <div
                                            v-for="(
                                                test_general_detail, index
                                            ) in packageDetail.test_general_details"
                                            :key="index"
                                            class="my-2"
                                        >

                                        <div v-if="index === 0 || packageDetail.test_general_details[index-1]?.sub_package?.id !== test_general_detail?.sub_package?.id" class="font-semibold text-blue-600 mb-2">
                                            {{ test_general_detail?.sub_package?.name }}
                                        </div>
                                            <div class="flex -space-x-px gap-2">
                                                <!-- <div
                                                    class="w-1/2 min-w-0 flex-1"
                                                >
                                                    <label
                                                        for="card-expiration-date"
                                                        class="sr-only"
                                                    >
                                                        Test
                                                        <span
                                                            class="text-red-500"
                                                            >*</span
                                                        ></label
                                                    > -->
                                                    <!-- <div class="">
                                                        <multiselect
                                                            v-model="
                                                                test_general_detail.test_category
                                                            "
                                                            :options="
                                                                propss.test_categories
                                                            "
                                                            @select="
                                                                onTestSelect(
                                                                    $event,
                                                                    packageIndex,
                                                                    index
                                                                )
                                                            "
                                                            label="name"
                                                            track-by="id"
                                                            placeholder="Select a Test"
                                                        ></multiselect>
                                                    </div>
                                                    <InputError
                                                        v-if="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.test_category_id`
                                                            ]
                                                        "
                                                        :message="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.test_category_id`
                                                            ]
                                                        "
                                                    />
                                                </div> -->
                                                <div
                                                    class="w-1/2 min-w-0 flex-1"
                                                >
                                                    <label
                                                        for="card-expiration-date"
                                                        class="sr-only"
                                                        >Service</label
                                                    >
                                                    <multiselect
                                                        v-model="
                                                            test_general_detail.lab_test
                                                        "
                                                         :options="propss.other_tests"
                                                        @update:modelValue="
                                                            calculatePaymentCharges
                                                        "
                                                           :custom-label="labTestLabel"
                                                        track-by="id"
                                                        placeholder="Select Test"
                                                    ></multiselect>
                                                    <InputError
                                                        v-if="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.lab_test.id`
                                                            ]
                                                        "
                                                        :message="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.lab_test.id`
                                                            ]
                                                        "
                                                    />
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <label
                                                        for="card-cvc"
                                                        class="sr-only"
                                                        >Result</label
                                                    >
                                                    <input
                                                        v-model="
                                                            test_general_detail.result
                                                        "
                                                        type="text"
                                                        class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        placeholder="Result"
                                                    />
                                                    <InputError
                                                        v-if="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.result`
                                                            ]
                                                        "
                                                        :message="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.result`
                                                            ]
                                                        "
                                                    />
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <label
                                                        for="card-cvc"
                                                        class="sr-only"
                                                        >Result 2</label
                                                    >
                                                    <multiselect
                                                        v-model="test_general_detail.lab_test_detail"
                                                        :modelValue="test_general_detail.result_2"
                                                         :options="propss.lab_test_details.filter((res) => {
                                                            return res.lab_test_id === test_general_detail.lab_test?.id;
                                                        })"
                                                        label="test_report"
                                                        track-by="id"
                                                        placeholder="Select Report"
                                                    ></multiselect>
                                                    <!-- <input
                                                        v-model="
                                                            test_general_detail.result_2
                                                        "
                                                        type="text"
                                                        class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        placeholder="Result 2"
                                                    /> -->
                                                    <InputError
                                                        v-if="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.result_2`
                                                            ]
                                                        "
                                                        :message="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.result_2`
                                                            ]
                                                        "
                                                    />
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <label
                                                        for="card-cvc"
                                                        class="sr-only"
                                                        >Description</label
                                                    >
                                                    <input
                                                        v-model="
                                                            test_general_detail.description
                                                        "
                                                        type="text"
                                                        class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        placeholder="Description"
                                                    />
                                                    <InputError
                                                        v-if="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.description`
                                                            ]
                                                        "
                                                        :message="
                                                            packageDetail
                                                                .errors?.[
                                                                `test_general_details.${index}.description`
                                                            ]
                                                        "
                                                    />
                                                </div>
                                                <div  v-if="propss.roles?.role_id === 1">
                                                    <button
                                                        type="button"
                                                        @click="
                                                            removeTestGeneralDetail(
                                                                packageIndex,
                                                                index
                                                            )
                                                        "
                                                        class="inline-flex items-center justify-center ml-2 px-3 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
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
                                        <div class="font-bold"> Total Lab Test Prcie : {{ totalLabTestPrice(packageDetail) }} </div>

                                        <!-- <button
                                            type="button"
                                            @click="
                                                addTestGeneralDetail(
                                                    packageIndex
                                                )
                                            "
                                            class="inline-flex items-center mt-1 px-3 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            v-if="
                                                packageDetail?.selectedPackage ==
                                                null
                                            "
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
                                    <!-- <button
                                        type="button"
                                        @click="
                                            addNewPackageDetail(packageIndex)
                                        "
                                        class="inline-flex items-center mt-1 px-3 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
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
                                        Add New Package
                                    </button> -->
                                </div>
                            </div>

                                <div
                                    class="divide-y divide-gray-200 pt-4 pb-4"
                                    v-else-if="
                                        patient_test?.test_nature == 'Widal'
                                    "
                                >
                                    <div class="px-4 sm:px-6">
                                        <h3 class="text-lg font-semibold py-2">
                                            Widal test detail
                                        </h3>
                                        <InputError
                                            v-if="
                                                patient_test.errors?.[
                                                    `test_widal_details`
                                                ]
                                            "
                                            :message="
                                                patient_test.errors?.[
                                                    `test_widal_details`
                                                ]
                                            "
                                        />
                                        <div
                                            class="my-2"
                                            v-for="(
                                                test_widal_detail, index
                                            ) in patient_test.test_widal_details"
                                            :key="index"
                                        >
                                            <div class="flex -space-x-px">
                                                <div
                                                    class="w-1/2 min-w-0 flex-1"
                                                >
                                                    <label
                                                        for="card-expiration-date"
                                                        class="sr-only"
                                                        >Widal test</label
                                                    >
                                                    <multiselect
                                                        v-model="
                                                            test_widal_detail[
                                                                'widal_test'
                                                            ]
                                                        "
                                                        :options="propss.widal_tests"
                                                        @select="
                                                            calculatePaymentCharges()
                                                        "
                                                        label="name"
                                                        track-by="id"
                                                        placeholder="Select Test"
                                                    ></multiselect>
                                                    <InputError
                                                        v-if="
                                                            patient_test
                                                                .errors?.[
                                                                `test_widal_details.${index}.widal_test.id`
                                                            ]
                                                        "
                                                        :message="
                                                            patient_test
                                                                .errors?.[
                                                                `test_widal_details.${index}.widal_test.id`
                                                            ]
                                                        "
                                                    />
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <label
                                                        for="card-cvc"
                                                        class="sr-only"
                                                        >1-40</label
                                                    >
                                                    <input
                                                        v-model="
                                                            test_widal_detail[
                                                                '1_40'
                                                            ]
                                                        "
                                                        type="text"
                                                        class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        placeholder="1-40"
                                                    />
                                                    <InputError
                                                        v-if="
                                                            patient_test
                                                                .errors?.[
                                                                `test_widal_details.${index}.1_40`
                                                            ]
                                                        "
                                                        :message="
                                                            patient_test
                                                                .errors?.[
                                                                `test_widal_details.${index}.1_40`
                                                            ]
                                                        "
                                                    />
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <label
                                                        for="card-cvc"
                                                        class="sr-only"
                                                        >1-80</label
                                                    >
                                                    <input
                                                        v-model="
                                                            test_widal_detail[
                                                                '1_80'
                                                            ]
                                                        "
                                                        type="text"
                                                        class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        placeholder="1-80"
                                                    />
                                                    <InputError
                                                        v-if="
                                                            patient_test
                                                                .errors?.[
                                                                `test_widal_details.${index}.1_80`
                                                            ]
                                                        "
                                                        :message="
                                                            patient_test
                                                                .errors?.[
                                                                `test_widal_details.${index}.1_80`
                                                            ]
                                                        "
                                                    />
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <label
                                                        for="card-cvc"
                                                        class="sr-only"
                                                        >1-160</label
                                                    >
                                                    <input
                                                        v-model="
                                                            test_widal_detail[
                                                                '1_160'
                                                            ]
                                                        "
                                                        type="text"
                                                        class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        placeholder="1-160"
                                                    />
                                                    <InputError
                                                        v-if="
                                                            patient_test
                                                                .errors?.[
                                                                `test_widal_details.${index}.1_160`
                                                            ]
                                                        "
                                                        :message="
                                                            patient_test
                                                                .errors?.[
                                                                `test_widal_details.${index}.1_160`
                                                            ]
                                                        "
                                                    />
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <label
                                                        for="card-cvc"
                                                        class="sr-only"
                                                        >1-320</label
                                                    >
                                                    <input
                                                        v-model="
                                                            test_widal_detail[
                                                                '1_320'
                                                            ]
                                                        "
                                                        type="text"
                                                        class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        placeholder="1-320"
                                                    />
                                                    <InputError
                                                        v-if="
                                                            patient_test
                                                                .errors?.[
                                                                `test_widal_details.${index}.1_320`
                                                            ]
                                                        "
                                                        :message="
                                                            patient_test
                                                                .errors?.[
                                                                `test_widal_details.${index}.1_320`
                                                            ]
                                                        "
                                                    />
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <label
                                                        for="card-cvc"
                                                        class="sr-only"
                                                        >1-640</label
                                                    >
                                                    <input
                                                        v-model="
                                                            test_widal_detail[
                                                                '1_640'
                                                            ]
                                                        "
                                                        type="text"
                                                        class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        placeholder="1-640"
                                                    />
                                                    <InputError
                                                        v-if="
                                                            patient_test
                                                                .errors?.[
                                                                `test_widal_details.${index}.1_640`
                                                            ]
                                                        "
                                                        :message="
                                                            patient_test
                                                                .errors?.[
                                                                `test_widal_details.${index}.1_640`
                                                            ]
                                                        "
                                                    />
                                                </div>
                                                <div>
                                                    <button
                                                        type="button"
                                                        @click="
                                                            removeTestWidalDetail(
                                                                index
                                                            )
                                                        "
                                                        class="inline-flex items-center justify-center ml-2 px-3 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
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
                                        <button
                                            type="button"
                                            @click="addTestWidalDetail"
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
                                <div
                                    class="divide-y divide-gray-200 pt-4 pb-4"
                                    v-else-if="
                                        patient_test?.test_nature ==
                                        'Cross_Match'
                                    "
                                >
                                    <div class="px-4 sm:px-6">
                                        <h3
                                            class="text-2xl font-semibold py-2 text-primary"
                                        >
                                            Donor detail
                                        </h3>
                                        <div
                                            class="mt-6 grid grid-cols-12 gap-6"
                                        >
                                            <div
                                                class="col-span-12 sm:col-span-4 lg:col-span-3"
                                            >
                                                <label
                                                    for="donor_name"
                                                    class="block text-md font-semibold leading-6 text-gray-900"
                                                    >Donor name</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        id="donor_name"
                                                        name="donor_name"
                                                        type="text"
                                                        autocomplete="donor_name"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        step="0.01"
                                                        v-model="
                                                            patient_test
                                                                .test_cross_match_details
                                                                .donor_name
                                                        "
                                                    />
                                                </div>
                                                <InputError
                                                    v-if="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.donor_name'
                                                        ]
                                                    "
                                                    :message="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.donor_name'
                                                        ]
                                                    "
                                                />
                                            </div>
                                            <div
                                                class="col-span-12 sm:col-span-4 lg:col-span-3"
                                            >
                                                <label
                                                    for="donor_blood_group"
                                                    class="block text-md font-semibold leading-6 text-gray-900"
                                                    >Donor blood group</label
                                                >
                                                <div class="mt-2">
                                                    <multiselect
                                                        v-model="
                                                            selectedDonorBloodGroup
                                                        "
                                                        :options="
                                                            bloodGroupOptions
                                                        "
                                                        @select="
                                                            onDonorBloodGroupSelect
                                                        "
                                                        label="type"
                                                        track-by="type"
                                                        placeholder="Select Blood Group"
                                                    ></multiselect>
                                                </div>
                                                <InputError
                                                    v-if="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.donor_blood_group'
                                                        ]
                                                    "
                                                    :message="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.donor_blood_group'
                                                        ]
                                                    "
                                                />
                                            </div>
                                            <div
                                                class="col-span-12 sm:col-span-4 lg:col-span-3"
                                            >
                                                <label
                                                    for="donor_rh_factor"
                                                    class="block text-md font-semibold leading-6 text-gray-900"
                                                    >Donor RH factor</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        id="donor_rh_factor"
                                                        name="donor_rh_factor"
                                                        type="text"
                                                        autocomplete="donor_rh_factor"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        step="0.01"
                                                        v-model="
                                                            patient_test
                                                                .test_cross_match_details
                                                                .donor_rh_factor
                                                        "
                                                    />
                                                </div>
                                                <InputError
                                                    v-if="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.donor_rh_factor'
                                                        ]
                                                    "
                                                    :message="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.donor_rh_factor'
                                                        ]
                                                    "
                                                />
                                            </div>
                                            <div
                                                class="col-span-12 sm:col-span-4 lg:col-span-3"
                                            >
                                                <label
                                                    for="donor_age"
                                                    class="block text-md font-semibold leading-6 text-gray-900"
                                                    >Donor age</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        id="donor_age"
                                                        name="donor_age"
                                                        type="text"
                                                        autocomplete="donor_age"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        step="0.01"
                                                        v-model="
                                                            patient_test
                                                                .test_cross_match_details
                                                                .donor_age
                                                        "
                                                    />
                                                </div>
                                                <InputError
                                                    v-if="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.donor_age'
                                                        ]
                                                    "
                                                    :message="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.donor_age'
                                                        ]
                                                    "
                                                />
                                            </div>
                                            <div
                                                class="col-span-12 sm:col-span-4 lg:col-span-3"
                                            >
                                                <label
                                                    for="donor_blood_pressure"
                                                    class="block text-md font-semibold leading-6 text-gray-900"
                                                    >Donor blood pressure</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        id="donor_blood_pressure"
                                                        name="donor_blood_pressure"
                                                        type="text"
                                                        autocomplete="donor_blood_pressure"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        step="0.01"
                                                        v-model="
                                                            patient_test
                                                                .test_cross_match_details
                                                                .donor_blood_pressure
                                                        "
                                                    />
                                                </div>
                                                <InputError
                                                    v-if="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.donor_blood_pressure'
                                                        ]
                                                    "
                                                    :message="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.donor_blood_pressure'
                                                        ]
                                                    "
                                                />
                                            </div>
                                            <div
                                                class="col-span-12 sm:col-span-4 lg:col-span-3"
                                            >
                                                <label
                                                    for="donor_anti_hcv"
                                                    class="block text-md font-semibold leading-6 text-gray-900"
                                                    >Donor anti HCV</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        id="donor_anti_hcv"
                                                        name="donor_anti_hcv"
                                                        type="text"
                                                        autocomplete="donor_anti_hcv"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        step="0.01"
                                                        v-model="
                                                            patient_test
                                                                .test_cross_match_details
                                                                .donor_anti_hcv
                                                        "
                                                    />
                                                </div>
                                                <InputError
                                                    v-if="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.donor_anti_hcv'
                                                        ]
                                                    "
                                                    :message="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.donor_anti_hcv'
                                                        ]
                                                    "
                                                />
                                            </div>
                                            <div
                                                class="col-span-12 sm:col-span-4 lg:col-span-3"
                                            >
                                                <label
                                                    for="donor_hbsag"
                                                    class="block text-md font-semibold leading-6 text-gray-900"
                                                    >Donor hbsag</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        id="donor_hbsag"
                                                        name="donor_hbsag"
                                                        type="text"
                                                        autocomplete="donor_hbsag"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        step="0.01"
                                                        v-model="
                                                            patient_test
                                                                .test_cross_match_details
                                                                .donor_hbsag
                                                        "
                                                    />
                                                </div>
                                                <InputError
                                                    v-if="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.donor_hbsag'
                                                        ]
                                                    "
                                                    :message="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.donor_hbsag'
                                                        ]
                                                    "
                                                />
                                            </div>
                                            <div
                                                class="col-span-12 sm:col-span-4 lg:col-span-3"
                                            >
                                                <label
                                                    for="donor_hiv_antibodies"
                                                    class="block text-md font-semibold leading-6 text-gray-900"
                                                    >Donor HIV antibodies</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        id="donor_hiv_antibodies"
                                                        name="donor_hiv_antibodies"
                                                        type="text"
                                                        autocomplete="donor_hiv_antibodies"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        step="0.01"
                                                        v-model="
                                                            patient_test
                                                                .test_cross_match_details
                                                                .donor_hiv_antibodies
                                                        "
                                                    />
                                                </div>
                                                <InputError
                                                    v-if="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.donor_hiv_antibodies'
                                                        ]
                                                    "
                                                    :message="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.donor_hiv_antibodies'
                                                        ]
                                                    "
                                                />
                                            </div>
                                            <div
                                                class="col-span-12 sm:col-span-4 lg:col-span-3"
                                            >
                                                <label
                                                    for="donor_cross_match"
                                                    class="block text-md font-semibold leading-6 text-gray-900"
                                                    >Donor cross match</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        id="donor_cross_match"
                                                        name="donor_cross_match"
                                                        type="text"
                                                        autocomplete="donor_cross_match"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        step="0.01"
                                                        v-model="
                                                            patient_test
                                                                .test_cross_match_details
                                                                .donor_cross_match
                                                        "
                                                    />
                                                </div>
                                                <InputError
                                                    v-if="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.donor_cross_match'
                                                        ]
                                                    "
                                                    :message="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.donor_cross_match'
                                                        ]
                                                    "
                                                />
                                            </div>
                                            <div
                                                class="col-span-12 sm:col-span-4 lg:col-span-3"
                                            >
                                                <label
                                                    for="blood_bag_no"
                                                    class="block text-md font-semibold leading-6 text-gray-900"
                                                    >Blood Bag No</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        id="blood_bag_no"
                                                        name="blood_bag_no"
                                                        type="text"
                                                        autocomplete="blood_bag_no"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        step="0.01"
                                                        v-model="
                                                            patient_test
                                                                .test_cross_match_details
                                                                .blood_bag_no
                                                        "
                                                    />
                                                </div>
                                                <InputError
                                                    v-if="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.blood_bag_no'
                                                        ]
                                                    "
                                                    :message="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.blood_bag_no'
                                                        ]
                                                    "
                                                />
                                            </div>
                                            <div
                                                class="col-span-12 sm:col-span-4 lg:col-span-3"
                                            >
                                                <label
                                                    for="blood_component"
                                                    class="block text-md font-semibold leading-6 text-gray-900"
                                                    >Blood Component</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        id="blood_component"
                                                        name="blood_component"
                                                        type="text"
                                                        autocomplete="blood_component"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        step="0.01"
                                                        v-model="
                                                            patient_test
                                                                .test_cross_match_details
                                                                .blood_component
                                                        "
                                                    />
                                                </div>
                                                <InputError
                                                    v-if="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.blood_component'
                                                        ]
                                                    "
                                                    :message="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.blood_component'
                                                        ]
                                                    "
                                                />
                                            </div>
                                            <div
                                                class="col-span-12 sm:col-span-4 lg:col-span-3"
                                            >
                                                <label
                                                    for="blood_volumn"
                                                    class="block text-md font-semibold leading-6 text-gray-900"
                                                    >Blood Volume</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        id="blood_volumn"
                                                        name="blood_volumn"
                                                        type="text"
                                                        autocomplete="blood_volumn"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        step="0.01"
                                                        v-model="
                                                            patient_test
                                                                .test_cross_match_details
                                                                .blood_volumn
                                                        "
                                                    />
                                                </div>
                                                <InputError
                                                    v-if="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.blood_volumn'
                                                        ]
                                                    "
                                                    :message="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.blood_volumn'
                                                        ]
                                                    "
                                                />
                                            </div>
                                            <div
                                                class="col-span-12 sm:col-span-4 lg:col-span-3"
                                            >
                                                <label
                                                    for="bleeding_date"
                                                    class="block text-md font-semibold leading-6 text-gray-900"
                                                    >Bleeding Date</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        id="bleeding_date"
                                                        name="bleeding_date"
                                                        type="date"
                                                        autocomplete="bleeding_date"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        step="0.01"
                                                        v-model="
                                                            patient_test
                                                                .test_cross_match_details
                                                                .bleeding_date
                                                        "
                                                    />
                                                </div>
                                                <InputError
                                                    v-if="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.bleeding_date'
                                                        ]
                                                    "
                                                    :message="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.bleeding_date'
                                                        ]
                                                    "
                                                />
                                            </div>
                                            <div
                                                class="col-span-12 sm:col-span-4 lg:col-span-3"
                                            >
                                                <label
                                                    for="bag_expiry_date"
                                                    class="block text-md font-semibold leading-6 text-gray-900"
                                                    >Bag Expiry Date</label
                                                >
                                                <div class="mt-2">
                                                    <input
                                                        id="bag_expiry_date"
                                                        name="bag_expiry_date"
                                                        type="date"
                                                        autocomplete="bag_expiry_date"
                                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                        step="0.01"
                                                        v-model="
                                                            patient_test
                                                                .test_cross_match_details
                                                                .bag_expiry_date
                                                        "
                                                    />
                                                </div>
                                                <InputError
                                                    v-if="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.bag_expiry_date'
                                                        ]
                                                    "
                                                    :message="
                                                        patient_test.errors?.[
                                                            'test_cross_match_details.bag_expiry_date'
                                                        ]
                                                    "
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-4"
                                >
                                    <div
                                        class=""
                                        v-if="
                                            patient_test?.test_nature ==
                                            'Cross_Match'
                                        "
                                    >
                                        <label
                                            for="total_amount"
                                            class="block text-md font-semibold leading-6 text-gray-900"
                                            >Total amount</label
                                        >
                                        <div class="mt-2">
                                            <input
                                                id="total_amount"
                                                name="total_amount"
                                                type="text"
                                                autocomplete="total_amount"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                                v-model="
                                                    patient_test.total_amount
                                                "
                                                @input="
                                                    calculatePaymentCharges()
                                                "
                                            />
                                        </div>
                                        <InputError
                                            v-if="
                                                patient_test.errors
                                                    ?.total_amount
                                            "
                                            :message="
                                                patient_test.errors
                                                    ?.total_amount
                                            "
                                        />
                                    </div>

                                    <!-- <div class="">
                    <label
                        for="sub_total"
                        class="block text-md font-semibold leading-6 text-gray-900"
                        >Sub total</label
                    >
                    <div class="mt-2">
                        <input
                        id="sub_total"
                        name="sub_total"
                        type="text"
                        autocomplete="sub_total"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                        :value="subTotal"
                        tabindex="-1"
                        disabled="disabled"
                        />
                    </div>
                </div> -->

                                    <!-- <div class="">
                    <label
                        for="disacount_amount"
                        class="block text-md font-semibold leading-6 text-gray-900"
                        >Discount amount</label
                    >
                    <div class="mt-2">
                        <input
                        id="disacount_amount"
                        name="disacount_amount"
                        type="text"
                        autocomplete="disacount_amount"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                        :value="discountAmount"
                        tabindex="-1"
                        disabled="disabled"
                        />
                    </div>
                </div> -->

                                    <div  v-if="hasDonationModule">
                                        <label
                                            for="careoff_id"
                                            class="block text-md font-semibold leading-6 text-gray-900"
                                            >Care Off
                                        </label>
                                        <div class="mt-1">
                                            <multiselect
                                                v-model="selectedCareOff"
                                                :options="propss.careoffs"
                                                @update:modelValue="
                                                    onCareOffSelect
                                                "
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
                                                class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                                                step="0.01"
                                                @input="
                                                    calculatePaymentCharges()
                                                "
                                                v-model="patient_test.donor_fee"
                                                v-if="
                                                    patient_test.careoff_id !=
                                                        null &&
                                                    patient_test.careoff_id !=
                                                        ''
                                                "
                                            />
                                        </div>
                                        <InputError
                                            v-if="
                                                patient_test?.errors?.careoff_id
                                            "
                                            :message="
                                                patient_test?.errors?.careoff_id
                                            "
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
                                                class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                                                step="0.01"
                                                @input="
                                                    checkAmount(
                                                        $event.target.value,
                                                        zf_fees
                                                    )
                                                "
                                                v-model="patient_test.zf_fee"
                                            />
                                        </div>
                                        <InputError
                                            v-if="patient_test?.errors?.zf_fee"
                                            :message="
                                                patient_test?.errors?.zf_fee
                                            "
                                        />
                                    </div>
                                    <div class="">
                                        <label
                                            for="payable_amount"
                                            class="block text-md font-semibold leading-6 text-gray-900"
                                            >Payable amount</label
                                        >
                                        <div class="mt-1">
                                            <input
                                                id="payable_amount"
                                                name="payable_amount"
                                                type="text"
                                                autocomplete="payable_amount"
                                                class="block w-full rounded border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary bg-primary text-white text-lg"
                                                :value="payableAmount"
                                                tabindex="-1"
                                                disabled="disabled"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div class="divide-y divide-gray-200">
                                    <div
                                        class="flex justify-end gap-x-3 px-4 sm:px-6"
                                    >
                                        <button
                                            type="submit"
                                            class="rounded bg-primary px-5 py-2 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                                            :disabled="patient_test.processing"
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
import { ref, watchEffect, computed, onMounted ,watch} from "vue";
import Multiselect from "vue-multiselect";
import axios from "axios";
import dayjs from "dayjs";
const displayForm = ref("");

const propss = defineProps({
    sub_packages: Array,
    test_categories: Array,
    careoffs: Object,
    zf_fees: Object,
    mr_number:String,
    lab_no:String,
    lab_tests: Object,
    roles: Object ,
    other_tests: Object,
    widal_tests: Object,
    packages: Array,
    lab_test_details: Array,
    hasDonationModule: Boolean,
})
const getCurrentDateInPakistanTimezone = () => {
  const options = { year: 'numeric', month: '2-digit', day: '2-digit', timeZone: 'Asia/Karachi' };
  const currentDate = new Date().toLocaleDateString('en-CA', options);
  return currentDate;
};

const patient_test = useForm({
    id: null,
    source: "dashboard",
    test_date: getCurrentDateInPakistanTimezone(),
    // lab_no: null,
    test_nature: null,
    test_type: null,
    status: null,
    referred_by: null,
    patient_id: null,
    patient_name: null,
    mr_number: propss.mr_number || "",
    lab_no: propss.lab_no || "",
    patient_cnic: null,
    patient_age: null,
    patient_gender: null,
    patient_relation_name: null,
    patient_relation_type: null,
    test_category_id: null,
    patient_address: null,
    patient_state_id: null,
    patient_city_id: null,
    patient_country_id: null,
    patient_phone: null,
    patient_email: null,
    patient_blood_group: null,
    patient_rh_factor: null,
    specimen: null,
    free_test: false,
    package_id: null,
    discount_type: null,
    careoff_id: null,
    zf_fee: null,
    donor_fee: null,
    zf_id: null,
    welfare: null,
    file_no: null,
    discount_value: null,
    total_amount: null,
    patient_test_details:[
        {
            id: null,
            patient_test_id: null,
            remarks: null,
            package_id: null,
            test_general_details: [
                {
                    id: null,
                    patient_test_detail_id: null,
                    lab_test: null,
                    sub_package: null,
                    sub_package_id: null,
                    test_category: null,
                    test_category_data: null,
                    result: null,
                    result_2: null,
                    description: null,
                },
            ],
        },
    ],

    test_widal_details: [
        {
            id: null,
            patient_test_id: null,
            widal_test: null,
            1_40: null,
            1_80: null,
            1_160: null,
            1_320: null,
            1_640: null,
        },
    ],
    test_cross_match_details: {
        id: null,
        patient_test_id: null,
        donor_name: null,
        donor_blood_group: null,
        donor_rh_factor: null,
        donor_age: null,
        donor_blood_pressure: null,
        donor_anti_hcv: null,
        donor_hbsag: null,
        donor_hiv_antibodies: null,
        donor_cross_match: null,
        blood_bag_no: null,
        blood_component: null,
        blood_volumn: null,
        bleeding_date: null,
        bag_expiry_date: null,
    },
});

const formatPhone = (event) => {
  let input = event.target.value.replace(/\D/g, "");
  if (input.length > 4) {
    input = input.replace(/^(\d{4})(\d+)/, "$1-$2");
  }
  patient_test.patient_phone = input;
};
const generateMRNumber = (event) => {
  let input = event.target.value.replace(/\D/g, "");
  let number = input ? parseInt(input) : 1;
  let formattedNumber = number.toString().padStart(5, '0');
  patient_test.mr_number = formattedNumber;
  patient_test.lab_no = formattedNumber;
};
const formatCNIC = (event) => {
  let input = event.target.value.replace(/\D/g, "");
  if (input.length > 5) {
    input = input.replace(/^(\d{5})(\d{7})(\d{1})$/, "$1-$2-$3");
  }
  patient_test.patient_cnic = input;
};
onMounted(() => {
  patient_test.mr_number = propss.mr_number || "";
  patient_test.lab_no = propss.lab_no || "";
  });
const loadPatient = async (patientPhone) => {
  try {
    const response = await axios.get(`/patients-phone/${patientPhone}`);
    let patient = response?.data?.patient || null;
    if (patient) {
      patient_test.patient_name = patient?.name || null;
      patient_test.patient_cnic = patient?.cnic || null;
      selectedGender.value = patient?.gender || null;
      patient_test.patient_relation_name = patient?.relation_name || null;
      patient_test.patient_address = patient?.address || null;
      patient_test.mr_number = patient?.mr_number || null;
      patient_test.patient_phone = patient?.phone || null;
      patient_test.patient_age = patient?.age || null;
    }
  } catch (error) {
    console.error("Error fetching states:", error);
  }
};
const selectedCareOff = ref(null);
const selectedZf = ref(null);
const selectedTest = ref(null);

const labTestLabel = (labTest) => {
      return labTest ? `${labTest.name} - ${labTest.price || '0'}` : '';
    };
    const totalLabTestPrice = (packageDetail) => {
      if (!packageDetail || !packageDetail.test_general_details) {
        return 0;
      }

      return packageDetail.test_general_details.reduce((total, detail) => {
        const price = detail.lab_test?.price || 0;
        return total + price;
      }, 0);
    };
     const totalSubPackagePrice = (packageDetail) => {
      if (!packageDetail || !packageDetail.selectedSubPackages) {
        return 0;
      }

      return packageDetail.selectedSubPackages.reduce((total, detail) => {
        const price = detail?.price || 0;
        return total + price;
      }, 0);
    };

const onTestSelect = async (selectedDoctor, packageIndex, index) => {

        if (!Array.isArray(patient_test.patient_test_details) || patient_test.patient_test_details.length === 0) {
            console.error('patient_test_details is not properly initialized or is empty');
            return;
        }
        if (packageIndex < 0 || packageIndex >= patient_test.patient_test_details.length) {
            console.error('Invalid packageIndex');
            return;
        }
        const packageDetail = patient_test.patient_test_details[packageIndex];
        if (!Array.isArray(packageDetail.test_general_details)) {
            console.error('test_general_details is not an array');
            return;
        }
        if (index < 0 || index >= packageDetail.test_general_details.length) {
            console.error('Invalid index');
            return;
        }
        const testDetail = packageDetail.test_general_details[index];
        if (testDetail) {
            testDetail.test_category.id  = selectedDoctor.id;
        }
    };
    const onCareOffSelect = async (selectedCareOffs) => {
        patient_test.careoff_id = selectedCareOffs.id;
        if (patient_test.careoff_id == null) {
            patient_test.donor_fee = null;
            checkAmount();
        }
    };
    const onServiceSelect = async (selectedDoctor, index) => {
        patient_test.appointment_details[index].fee = selectedDoctor.charges;
    };

    const onZfSelect = async (selectedZf) => {
        patient_test.zf_id = selectedZf.id;
        selectedCareOff.value = null;
        patient_test.careoff_id = null;
        patient_test.zf_fee = null;
    };

const packages = ref(propss.packages || []);


        const removeTestGeneralDetail = (packageIndex, index) => {
            const patientTestDetail = patient_test.patient_test_details[packageIndex];
            if (patientTestDetail && patientTestDetail.test_general_details) {
                patientTestDetail.test_general_details.splice(index, 1);
                calculatePaymentCharges();
            }
        };

const onPackageSelect = async (selectedPackage, packageIndex) => {
    const patientTest = patient_test.patient_test_details[packageIndex];
    patientTest.package_id = selectedPackage.id;
    patientTest.test_general_details = [];
    patientTest.selectedSubPackages = [];
    selectedPackage.sub_packages.map(sub_packages => {
        sub_packages.lab_tests.map(lab_tests => {
            patientTest.test_general_details.push({
                id: null,
                patient_test_detail_id: null,
                sub_package_id: sub_packages.id,
                test_category_id: lab_tests.test_category_id,
                lab_test_id:lab_tests.id,
                lab_test: lab_tests,
                test_category: lab_tests.test_category,
                sub_package: sub_packages,
                result: null,
                result_2: null,
                description: null,
            });
        })
        patientTest.selectedSubPackages.push(sub_packages);
    })

    calculatePaymentCharges();
}
const onSubPackageSelect = async (selectedSubPackage, packageIndex) => {
    const patientTest = patient_test.patient_test_details[packageIndex];
    patientTest.test_general_details = [];
    patientTest.selectedSubPackages = [];
    selectedSubPackage.map(sub_packages => {
        sub_packages.lab_tests.map(lab_tests => {
            patientTest.test_general_details.push({
                id: null,
                patient_test_detail_id: null,
                sub_package_id: sub_packages.id,
                test_category_id: lab_tests.test_category_id,
                lab_test_id:lab_tests.id,
                lab_test: lab_tests,
                test_category: lab_tests.test_category,
                sub_package: sub_packages,
                result: null,
                result_2: null,
                description: null,
            });
        })
        patientTest.selectedSubPackages.push(sub_packages);
    })
    calculatePaymentCharges();

}
    if (
        propss?.patient_test?.patient_test_details &&
        Array.isArray(propss.patient_test.patient_test_details)
    ) {
    let patientTestDetails = propss.patient_test.patient_test_details;

    patientTestDetails.forEach((detail, packageIndex) => {
        if(detail.package){
            detail.selectedPackage = detail?.package;
            detail.selectedSubPackages = detail?.selectedPackage?.sub_packages;

        }
        else{
            detail.test_general_details.forEach((packageDetail, index) => {
                detail.selectedSubPackages = [];
                if(packageDetail?.sub_package){
                    detail.selectedSubPackages.push(packageDetail?.sub_package);
                }
            });
        }
            let testGeneralDetails = detail.test_general_details;

            testGeneralDetails.forEach((packageDetail, index) => {
                const testCategoryId = packageDetail?.test_category_data;
                if (testCategoryId !== undefined) {
                    let testCategory = propss.test_categories.find(
                        (cat) => cat.id === testCategoryId
                    );
                    if (!testCategory) {
                        testCategory = {
                            id: testCategoryId,
                            name:
                                packageDetail.test_category?.name ||
                                "Unknown",
                        };
                        propss.test_categories.push(testCategory);
                    }
                    patientTestDetails[packageIndex].test_general_details[
                        index
                    ].test_category = testCategory;
                }
            });
        // }
    });
    patient_test.patient_test_details = patientTestDetails;
}

const onPackageSelectOther = async (selectedPackage, packageIndex) => {
    const patientTest = patient_test.patient_test_details[packageIndex];
    patientTest.package_id = selectedPackage.id;
    patientTest.test_general_details = [];
    patientTest.selectedSubPackages = [];
    selectedPackage.sub_packages.map(sub_packages => {
        sub_packages.other_tests.map(other_tests => {
            patientTest.test_general_details.push({
                id: null,
                patient_test_detail_id: null,
                sub_package_id: sub_packages.id,
                test_category_id: other_tests.test_category_id,
                lab_test_id:other_tests.id,
                lab_test: other_tests,
                test_category: other_tests.test_category,
                sub_package: sub_packages,
                result: null,
                result_2: null,
                description: null,
            });
        })
        patientTest.selectedSubPackages.push(sub_packages);
    })

    calculatePaymentCharges();
}
const onSubPackageSelect1 = async (selectedSubPackage, packageIndex) => {
    const patientTest = patient_test.patient_test_details[packageIndex];
    patientTest.test_general_details = [];
    patientTest.selectedSubPackages = [];
    selectedSubPackage.map(sub_packages => {
        sub_packages.other_tests.map(other_tests => {
            patientTest.test_general_details.push({
                id: null,
                patient_test_detail_id: null,
                sub_package_id: sub_packages.id,
                test_category_id: other_tests.test_category_id,
                lab_test_id:other_tests.id,
                lab_test: other_tests,
                test_category: other_tests.test_category,
                sub_package: sub_packages,
                result: null,
                result_2: null,
                description: null,
            });
        })
        patientTest.selectedSubPackages.push(sub_packages);
    })
    calculatePaymentCharges();

}
    if (
        propss?.patient_test?.patient_test_details &&
        Array.isArray(propss.patient_test.patient_test_details)
    ) {
    let patientTestDetails = propss.patient_test.patient_test_details;

    patientTestDetails.forEach((detail, packageIndex) => {
        if(detail.package){
            detail.selectedPackage = detail?.package;
            detail.selectedSubPackages = detail?.selectedPackage?.sub_packages;

        }
        else{
            detail.test_general_details.forEach((packageDetail, index) => {
                detail.selectedSubPackages = [];
                if(packageDetail?.sub_package){
                    detail.selectedSubPackages.push(packageDetail?.sub_package);
                }
            });
        }
            let testGeneralDetails = detail.test_general_details;

            testGeneralDetails.forEach((packageDetail, index) => {
                const testCategoryId = packageDetail?.test_category_data;
                if (testCategoryId !== undefined) {
                    let testCategory = propss.test_categories.find(
                        (cat) => cat.id === testCategoryId
                    );
                    if (!testCategory) {
                        testCategory = {
                            id: testCategoryId,
                            name:
                                packageDetail.test_category?.name ||
                                "Unknown",
                        };
                        propss.test_categories.push(testCategory);
                    }
                    patientTestDetails[packageIndex].test_general_details[
                        index
                    ].test_category = testCategory;
                }
            });
        // }
    });
    patient_test.patient_test_details = patientTestDetails;
}


    const removePackageDetail = (packageIndex) => {
        if (
            packageIndex < 0 ||
            packageIndex >= patient_test.patient_test_details.length
        ) {
            console.error("Invalid packageIndex:", packageIndex);
            return;
        }
        patient_test.patient_test_details.splice(packageIndex, 1);
        calculatePaymentCharges();
    };

const addTestGeneralDetail = (packageIndex) => {
    const packageDetail = patient_test.patient_test_details[packageIndex];
    if (!packageDetail.test_general_details) {
        packageDetail.test_general_details = [];
    }
    packageDetail.test_general_details.push({
        id: null,
        patient_test_detail_id: null,
        lab_test_id: null,
        sub_package_id: null,
        test_category_id: null,
        lab_test: null,
        test_category: null,
        sub_package: null,
        result: null,
        result_2: null,
        description: null,
    });
};

const addNewPackageDetail = () => {
    patient_test.patient_test_details.push({
        id: null,
        patient_test_id: null,
        remarks: null,
        package_id: null,
        test_general_details: [
            {
                id: null,
                patient_test_detail_id: null,
                lab_test_id: null,
                sub_package_id: null,
                test_category_id: null,
                lab_test: null,
                test_category: null,
                sub_package: null,
                result: null,
                result_2: null,
                description: null,
            },
        ],
    });
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
    patient_test.patient_gender = selectedGender.value;
};

const testNatureOptions = computed(() => {
    return [
        { value: "General", label: "General" },
        // { value: "Widal", label: "Widal" },
        { value: "Cross_Match", label: "Cross Match" },
        { value: "Other_Receipt", label: "Other Receipt" },
    ];
});

const selectedTestNature = ref(null);
const onTestNatureSelect = async (selectedTestNature) => {
    patient_test.test_nature = selectedTestNature.value;
    patient_test.total_amount = 0;
    calculatePaymentCharges();
};

const testTypeOptions = computed(() => {
    return [
        { value: "IPD", label: "IPD" },
        { value: "OPD", label: "OPD" },
        { value: "Private", label: "Private" },
    ];
});

const selectedTestType = ref(null);
const onTestTypeSelect = async (selectedTestType) => {
    patient_test.test_type = selectedTestType.value;
};

const selectedDonorBloodGroup = ref(null);
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

const onDonorBloodGroupSelect = async (selectedDonorBloodGroup) => {
    patient_test.test_cross_match_details.donor_blood_group =
        selectedDonorBloodGroup.type;
};

//General test detail
// const addTestGeneralDetail = () => {
//     patient_test.test_general_details.push({
//         id: null,
//         patient_test_id: null,
//         lab_test: null,
//         result: null,
//         result_2: null,
//         description: null,
//     });
// };

// const removeTestGeneralDetail = (index) => {
//     patient_test.test_general_details.splice(index, 1);
//     calculatePaymentCharges();
// };

//widal test detail
const addTestWidalDetail = () => {
    patient_test.test_widal_details.push({
        id: null,
        patient_test_id: null,
        widal_test: null,
        1_40: null,
        1_80: null,
        1_160: null,
        1_320: null,
        1_640: null,
    });
};

const removeTestWidalDetail = (index) => {
    patient_test.test_widal_details.splice(index, 1);
    calculatePaymentCharges();
};

const subTotal = ref(0);
const discountAmount = ref(0);
const payableAmount = ref(0);

const calculatePaymentCharges = async () => {
    subTotal.value = 0;
    discountAmount.value = 0;
    payableAmount.value = 0;
    let totalTestAmount = 0;
    if (patient_test?.test_nature == "General" || patient_test?.test_nature == "Other_Receipt") {
        // if (
        //     patient_test?.test_general_details &&
        //     patient_test?.package_id != null
        // ) {
        //     totalTestAmount = selectedPackage.value?.price;
        // } else if (patient_test?.test_general_details) {
        //     patient_test?.test_general_details.map((test_general_detail) => {
        //         if (test_general_detail?.lab_test?.price) {
        //             totalTestAmount =
        //                 parseFloat(totalTestAmount) +
        //                 parseFloat(test_general_detail?.lab_test?.price);
        //         }
        //     });
        // }
        // patient_test?.patient_test_details.map((patient_test_details) => {
        //     if (patient_test_details && patient_test_details?.package_id != null) {
        //         totalTestAmount = patient_test_details?.selectedPackage?.price;
        //     }
        //     else if (patient_test_details?.test_general_details) {
        //         patient_test_details?.test_general_details.map((test_general_detail) => {
        //             if (test_general_detail?.lab_test?.price) {
        //             totalTestAmount =
        //                 parseFloat(totalTestAmount) +
        //                 parseFloat(test_general_detail?.lab_test?.price);
        //             }
        //         });
        //     }

        //     });
        patient_test?.patient_test_details.map((patient_test_details) => {
            if (
                patient_test_details &&
                patient_test_details?.package_id != null
            ) {
                totalTestAmount = parseFloat(totalTestAmount) + +patient_test_details?.selectedPackage?.price;
            }
            else if (
                patient_test_details &&
                patient_test_details?.selectedSubPackages != null && patient_test_details?.selectedSubPackages.length > 0
            ) {
                patient_test_details?.selectedSubPackages.map(res => {
                    totalTestAmount = parseFloat(totalTestAmount) + +res?.price;
                })
            }
            else if (patient_test_details?.test_general_details) {
                patient_test_details?.test_general_details.map(
                    (test_general_detail) => {
                        if (test_general_detail?.lab_test?.price) {
                            totalTestAmount =
                                parseFloat(totalTestAmount) +
                                parseFloat(
                                    test_general_detail?.lab_test?.price
                                );
                        }
                    }
                );
            }
        });
    } else if (patient_test?.test_nature == "Widal") {
        if (patient_test?.test_widal_details) {
            patient_test?.test_widal_details.map((test_widal_detail) => {
                if (test_widal_detail?.widal_test?.charges) {
                    totalTestAmount =
                        parseFloat(totalTestAmount) +
                        parseFloat(test_widal_detail?.widal_test?.charges);
                }
            });
        }
    } else if (patient_test?.test_nature == "Cross_Match") {
        totalTestAmount = parseFloat(patient_test?.total_amount);
    }
    subTotal.value = totalTestAmount;
    patient_test.total_amount = totalTestAmount;

    if (patient_test?.free_test) {
        payableAmount.value = 0;
        discountAmount.value = totalTestAmount;
    }

    if (patient_test?.discount_type == "Fixed") {
        discountAmount.value = patient_test?.discount_value || 0;
    } else if (patient_test?.discount_type == "Percentage") {
        discountAmount.value =
            (parseFloat(patient_test?.discount_value) *
                parseFloat(totalTestAmount)) /
                100 || 0;
    }
    payableAmount.value =
        parseFloat(subTotal.value) -
        parseFloat(discountAmount.value) -
        +patient_test.zf_fee -
        +patient_test.donor_fee;
};

    const submitForm = () => {
        patient_test.post(route("patient-tests.store"), {
            preserveScroll: true,
            patient_test,
            onSuccess: (res) => {
                selectedZf.value = null;
                selectedDonorBloodGroup.value = null;
                selectedTestType.value = null;
                selectedTest.value = null;
                payableAmount.value = 0;
                selectedTestNature.value = null;
                selectedGender.value = null;
                selectedCareOff.value = null;
                patient_test.id = null;
                patient_test.careoff_id = null;
                patient_test.zf_fee = null;
                patient_test.donor_fee = null;
                patient_test.zf_id = null;
                patient_test.welfare = null;
                patient_test.file_no = null;
                patient_test.test_date = new Date().toISOString().slice(0, 10);
                patient_test.lab_no = null;
                patient_test.test_nature = null;
                patient_test.test_type = null;
                patient_test.status = null;
                patient_test.referred_by = null;
                patient_test.patient_id = null;
                patient_test.patient_name = null;
                patient_test.patient_cnic = null;
                patient_test.patient_age = null;
                patient_test.patient_gender = null;
                patient_test.patient_relation_name = null;
                patient_test.patient_relation_type = null;
                patient_test.test_category_id = null;
                patient_test.patient_address = null;
                patient_test.patient_state_id = null;
                patient_test.patient_city_id = null;
                patient_test.patient_country_id = null;
                patient_test.patient_phone = null;
                patient_test.patient_email = null;
                patient_test.patient_blood_group = null;
                patient_test.patient_rh_factor = null;
                patient_test.specimen = null;
                patient_test.free_test = false;
                patient_test.package_id = null;
                patient_test.discount_type = null;
                patient_test.discount_value = null;
                patient_test.total_amount = null;
                // patient_test.test_general_details = [
                //     {
                //         id: null,
                //         patient_test_id: null,
                //         lab_test: null,
                //         result: null,
                //         result_2: null,
                //         description: null,
                //     },
                // ];
                patient_test.patient_test_details = [
                    {
                        id: null,
                        patient_test_id: null,
                        remarks: null,
                        package_id: null,
                        test_general_details: [
                            {
                                id: null,
                                patient_test_detail_id: null,
                                lab_test: null,
                                test_category: null,
                                result: null,
                                result_2: null,
                                description: null,
                            },
                        ],
                    },
                ];

                patient_test.test_widal_details = [
                    {
                        id: null,
                        patient_test_id: null,
                        widal_test: null,
                        1_40: null,
                        1_80: null,
                        1_160: null,
                        1_320: null,
                        1_640: null,
                    },
                ];
                patient_test.test_cross_match_details = {
                    id: null,
                    patient_test_id: null,
                    donor_name: null,
                    donor_blood_group: null,
                    donor_rh_factor: null,
                    donor_age: null,
                    donor_blood_pressure: null,
                    donor_anti_hcv: null,
                    donor_hbsag: null,
                    donor_hiv_antibodies: null,
                    donor_cross_match: null,
                    blood_bag_no: null,
                    blood_component: null,
                    blood_volumn: null,
                    bleeding_date: null,
                    bag_expiry_date: null,
                };
                var url_string = window.location.href;
                var url = new URL(url_string);
                var patient_test_id = url.searchParams.get("patient_test_id");
                printLabTest(patient_test_id);
                // var dashboardUrl = `/dashboard?patient_test_id=${patient_test_id}`;
                // router.visit(dashboardUrl);
                const currentUrl = new URL(window.location.href);
                const tab = currentUrl.searchParams.get('menuTab') || '3';
                router.visit(`/dashboard?menuTab=${tab}`);
            },
        });
    };

const printLabTest = (patient_test_id) => {
    const url = route("patient-tests.print", patient_test_id);
    const printWindow = window.open(url, "_blank");
    // if (printWindow) {
    //     setTimeout(function () {
    //         printWindow.focus();
    //         printWindow.print();

    //     }, 2000);
    // }
};

const checkAmount = (val, zf_fees) => {
    // if (val > zf_fees) {
    //     patient_test.zf_fee = zf_fees;
    // }
    calculatePaymentCharges();
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
