<template>

    <Head title="Create Admission" />
    <AppLayout>
        <div class="w-full pt-6">
            <div class=" mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-md rounded-lg">
                    <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                        <h1 class="font-semibold text-3xl text-primary">
                            {{ form?.id ? "Update" : "Create" }} Operation
                        </h1>

                        <form enctype="multipart/form-data" @submit.prevent="handleSubmit">
                            <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-8 py-6 ">
                                <!-- <div class="col-span-12 sm:col-span-2">
                                    <label for="patient_id"
                                        class="block text-base font-medium leading-6 text-gray-900">Patients
                                    </label>
                                    <div class="mt-2">
                                        <multiselect v-model="selectedPatient" :options="patients"
                                            @select="onPatientSelect" label="name" track-by="id"
                                            placeholder="Select patient"></multiselect>
                                    </div>
                                    <InputError v-if="form?.errors?.patient_id" :message="form?.errors?.patient_id" />
                                </div> -->
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="patient_name"
                                        class="block text-base font-medium leading-6 text-gray-900">Name
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input id="patient_name" name="patient_name" type="text" autocomplete="patient_name"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.patient_name" :disabled="props?.operation" />
                                    <InputError v-if="form?.errors?.patient_name"
                                        :message="form?.errors?.patient_name" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="mr_number"
                                        class="block text-base font-medium leading-6 text-gray-900">Mr Number
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input id="mr_number" name="mr_number" type="text" autocomplete="mr_number"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        @input="generateMRNumber" :value="props.mr_number" @focusout="loadPatient" />
                                    <InputError v-if="form?.errors?.mr_number" :message="form?.errors?.mr_number" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="file_no"
                                        class="block text-base font-medium leading-6 text-gray-900">File number
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input id="file_no" name="file_no" type="text" autocomplete="file_no"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.file_no" @input="fetchAdmissionData"  :disabled="props?.operation" />
                                    <InputError v-if="form?.errors?.file_no" :message="form?.errors?.file_no" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="ot_number"
                                        class="block text-base font-medium leading-6 text-gray-900">OT Number
                                        <span class="text-red-500">*</span></label>
                                    <input id="ot_number" name="ot_number" type="text" autocomplete="ot_number"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.ot_number" />
                                    <InputError v-if="form.errors?.ot_number" :message="form.errors?.ot_number" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="procedure_type"
                                        class="block text-base font-medium leading-6 text-gray-900">Procedure Type
                                        <span class="text-red-500">*</span></label>
                                    <input id="procedure_type" name="procedure_type" type="text" autocomplete="procedure_type"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.procedure_type" />
                                    <InputError v-if="form.errors?.procedure_type" :message="form.errors?.procedure_type" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="lap_chole"
                                        class="block text-base font-medium leading-6 text-gray-900">Lap Chole
                                        <span class="text-red-500">*</span></label>
                                    <input id="lap_chole" name="lap_chole" type="text" autocomplete="lap_chole"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.lap_chole" />
                                    <InputError v-if="form.errors?.lap_chole" :message="form.errors?.lap_chole" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="admission_date"
                                        class="block text-base font-medium leading-6 text-gray-900">Operation
                                        date</label>
                                    <input id="operation_date" name="operation_date" type="date"
                                        autocomplete="operation_date"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.operation_date" />
                                    <InputError v-if="form?.errors?.operation_date"
                                        :message="form?.errors?.operation_date" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="start_time"
                                        class="block text-base font-medium leading-6 text-gray-900">Operation Start
                                        Time</label>
                                    <input id="start_time" name="start_time" type="time" autocomplete="start_time"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.start_time" />
                                    <InputError v-if="form?.errors?.start_time" :message="form?.errors?.start_time" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="end_time"
                                        class="block text-base font-medium leading-6 text-gray-900">Operation End
                                        Time</label>
                                    <input id="end_time" name="end_time" type="time" autocomplete="end_time"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.end_time" />
                                    <InputError v-if="form?.errors?.end_time" :message="form?.errors?.end_time" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="patient_cnic"
                                        class="block text-base font-medium leading-6 text-gray-900">CNIC
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input id="patient_cnic" name="patient_cnic" type="text" autocomplete="patient_cnic"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        step="0.01" v-model="form.patient_cnic" :disabled="props?.operation" />
                                    <InputError v-if="form.errors?.patient_cnic" :message="form.errors?.patient_cnic" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="patient_phone"
                                        class="block text-base font-medium leading-6 text-gray-900">Phone
                                        <span class="text-red-500">*</span></label>
                                    <input id="patient_phone" name="patient_phone" type="text"
                                        autocomplete="patient_phone"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.patient_phone" :disabled="props?.operation" />
                                    <InputError v-if="form.errors?.patient_phone"
                                        :message="form.errors?.patient_phone" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="patient_age"
                                        class="block text-base font-medium leading-6 text-gray-900">Age
                                        <span class="text-red-500">*</span></label>
                                    <input id="patient_age" name="patient_age" type="text" autocomplete="patient_age"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.patient_age" :disabled="props?.operation" />
                                    <InputError v-if="form.errors?.patient_age" :message="form.errors?.patient_age" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="patient_address"
                                        class="block text-base font-medium leading-6 text-gray-900">Address</label>
                                    <input id="patient_address" name="patient_address" type="text"
                                        autocomplete="patient_address"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.patient_address" :disabled="props?.operation" />
                                    <InputError v-if="form.errors?.patient_address"
                                        :message="form.errors?.patient_address"  />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="gender"
                                        class="block text-base font-medium leading-6 text-gray-900">S/F/W/D</label>
                                    <div class="mt-2">
                                        <input id="patient_relation_name" name="patient_relation_name" type="text"
                                            autocomplete="patient_relation_name"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"                                            v-model="form.patient_relation_name" :disabled="props?.operation" />
                                    </div>
                                    <InputError v-if="form.errors?.patient_relation_name"
                                        :message="form.errors?.patient_relation_name" />
                                </div>

                                <div class="col-span-12 sm:col-span-2">
                                    <label for="surgeon_name"
                                        class="block text-base font-medium leading-6 text-gray-900">Surgeon Name</label>
                                    <div class="mt-2">
                                        <input id="surgeon_name" name="surgeon_name" type="text"
                                            autocomplete="surgeon_name"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"                                            v-model="form.surgeon_name" />
                                    </div>
                                    <InputError v-if="form.errors?.surgeon_name" :message="form.errors?.surgeon_name" />
                                </div>

                                <div class="col-span-12 sm:col-span-2">
                                    <label for="anasthetic_doc_name"
                                        class="block text-base font-medium leading-6 text-gray-900">Anesthetic Doc
                                        Name</label>
                                    <div class="mt-2">
                                        <input id="anasthetic_doc_name" name="anasthetic_doc_name" type="text"
                                            autocomplete="anasthetic_doc_name"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"                                            v-model="form.anasthetic_doc_name" />
                                    </div>
                                    <InputError v-if="form.errors?.anasthetic_doc_name"
                                        :message="form.errors?.anasthetic_doc_name" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="assistant_male"
                                        class="block text-base font-medium leading-6 text-gray-900">Male
                                        Assistant</label>
                                    <div class="mt-2">
                                        <input id="assistant_male" name="assistant_male" type="text"
                                            autocomplete="assistant_male"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"                                            v-model="form.assistant_male" />
                                    </div>
                                    <InputError v-if="form.errors?.assistant_male"
                                        :message="form.errors?.assistant_male" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="assistant_female"
                                        class="block text-base font-medium leading-6 text-gray-900">Female
                                        Assistant</label>
                                    <div class="mt-2">
                                        <input id="assistant_female" name="assistant_female" type="text"
                                            autocomplete="assistant_female"
                                            class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"                                            v-model="form.assistant_female" />
                                    </div>
                                    <InputError v-if="form.errors?.assistant_female"
                                        :message="form.errors?.assistant_female" />
                                </div>


                                <div class="col-span-12 sm:col-span-2">
                                    <label for="anasthesia_type"
                                        class="block text-base font-medium leading-6 text-gray-900">Anethesia Type <span
                                            class="text-red-500">*</span></label>
                                    <div class="mt-2 flex rounded-md shadow-sm">
                                        <multiselect v-model="selectedAnesthesiaType" :options="AnesthesiaTypeOptions"
                                            @select="onAnesthesiaTypeSelect" label="label" track-by="value"
                                            placeholder="Select Anesthesia Type"></multiselect>
                                    </div>
                                    <InputError v-if="form.errors?.anasthesia_type"
                                        :message="form.errors?.anasthesia_type" />
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
                                    <InputError v-if="form.errors?.patient_gender"
                                        :message="form.errors?.patient_gender" />
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
                                    <label for="referred_by"
                                        class="block text-base font-medium leading-6 text-gray-900">Refered By</label>
                                    <input id="referred_by" name="referred_by" type="text" autocomplete="referred_by"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.referred_by" />
                                    <InputError v-if="form.errors?.referred_by" :message="form.errors?.referred_by" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="advance_amount"
                                        class="block text-base font-medium leading-6 text-gray-900">Advance amount</label>
                                    <input id="advance_amount" name="advance_amount" type="text" autocomplete="advance_amount"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.advance_amount" />
                                    <InputError v-if="form.errors?.advance_amount" :message="form.errors?.advance_amount" />
                                </div>
                                <div class="col-span-12 sm:col-span-2">
                                    <label for="received_amount"
                                        class="block text-base font-medium leading-6 text-gray-900">Total Amount</label>
                                    <input id="received_amount" name="received_amount" type="text" autocomplete="received_amount"
                                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                        v-model="form.received_amount" />
                                    <InputError v-if="form.errors?.received_amount" :message="form.errors?.received_amount" />
                                </div>
                            </div>





                            <div class="flex items-center justify-end gap-x-6 mt-6 py-4">
                                <InertiaLink
                                    class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                                    :href="route('operations.index')">
                                    Cancel
                                </InertiaLink>
                                <button type="submit"
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

const props = defineProps({
    operation: Object,
    // patients: Array,
    patients: { type: Object, required: true },
    mr_number: String,
    services: Array,
    doctors: Array,
    careoffs: Array,
    zf_fees: Array,
    departments: Array,
    // file_no: Array,
    role: Object,
});

onMounted(() => {
    const today = new Date().toISOString().slice(0, 10);
    form.details.forEach(detail => {
        detail.date = today;
    });
    form.mr_number = mr_number || '';
});

const form = useForm({
    id: props?.operation?.id || null,
    patient_id: props?.operation?.patient_id || null,
    patient_name: props?.operation?.patient_name || null,
    surgeon_name: props?.operation?.surgeon_name || null,
    assistant_male: props?.operation?.assistant_male || null,
    assistant_female: props?.operation?.assistant_female || null,
    anasthetic_doc_name: props?.operation?.anasthetic_doc_name || null,
    ot_number: props?.operation?.ot_number || null,
    file_no: props?.operation?.file_no || null,
    is_hepatitus: props?.operation?.is_hepatitus || null,
    operation_date: props?.operation?.operation_date || new Date().toISOString().slice(0, 10),
    start_time: props?.operation?.start_time || new Date().toTimeString().slice(0, 5),
    end_time: props?.operation?.end_time || new Date().toTimeString().slice(0, 5),
    patient_cnic: props?.operation?.patient_cnic || null,
    patient_age: props?.operation?.patient_age || null,
    donor_fee: props?.operation?.donor_fee || null,
    patient_address: props?.operation?.patient_address || null,
    anasthesia_type: props?.operation?.anasthesia_type || null,
    procedure_type: props?.operation?.procedure_type || null,
    lap_chole: props?.operation?.lap_chole || null,
    patient_relation_name: props?.operation?.patient_relation_name || null,
    mr_number: props?.mr_number || null,
    patient_phone: props?.operation?.patient_phone || null,
    patient_gender: props?.operation?.patient_gender || null,
    status: props?.operation?.status || 'Pending',
    department_id: props?.operation?.department_id || null,
    referred_by: props?.operation?.referred_by || null,
    advance_amount: props?.operation?.advance_amount || null,
    received_amount: props?.operation?.received_amount || null,
    careoff_id: props?.operation?.careoff_id || null,
    welfare: props?.operation?.welfare || null,
    zf_fee: props?.operation?.zf_fee || null,
    details: props?.operation?.details || [
        {
            id: null,
            date: null,
            service: null,
            doctor: null,
            doctor_amount: null,
            service_amount: null,
            no_of_days: null,
            description: null,
            // is_doctor: false,
        },
    ],
});
// const is_doctor = ref(false);
const selectedPatient = ref(null);
const patients = ref(props.patients || []);

const selectedAnesthesiaType = ref(null);
const AnesthesiaTypeOptions = computed(() => {
    return [
        { value: "General Anesthesia", label: "General Anesthesia" },
        { value: "Spinal", label: "Spinal" },
        { value: "Local", label: "Local" },
    ];
});
const onAnesthesiaTypeSelect = async (selectedAnesthesiaType) => {
    form.anasthesia_type  = selectedAnesthesiaType.value;
};

if (props?.operation) {
    selectedAnesthesiaType.value = AnesthesiaTypeOptions.value.find(
        (option) => option.value === props.operation.anasthesia_type
    );
    // console.log("Initial Selected Gender from Props:", selectedGender.value);
}


const selectedGender = ref(null);
const genderOptions = computed(() => {
    return [
        { value: "Male", label: "Male" },
        { value: "Female", label: "Female" },
        { value: "Children", label: "Children" },
    ];
});


const onGenderSelect = async (selectedGender) => {
    form.patient_gender = selectedGender.value;
};

if (props?.operation) {
    selectedGender.value = genderOptions.value.find(
        (option) => option.value === props.operation.patient_gender
    );
    console.log("Initial Selected Gender from Props:", selectedGender.value);
}


const onPatientSelect = async (selectedPatient) => {
    form.patient_id = selectedPatient.id;
    await loadPatient(selectedPatient.id);
};
const patient_phone = ref('');

const matchedPatient = ref(null);
const admission = ref(null);

const fetchAdmissionData = async () => {
  try {
    const fileNoValue = form.file_no;

    const response = await axios.get('/admissions_data', {
      params: {
        file_no: fileNoValue,
        patient_phone: patient_phone.value,
      },
    });

    admission.value = response.data.admission;
    matchedPatient.value = response.data.matched_patient;
    form.advance_amount = admission.value.advance_amount;
    form.patient_name = admission.value.name;
    form.patient_cnic = admission.value.cnic;
    form.patient_phone = admission.value.phone;
    form.patient_address = admission.value.address;
    form.patient_relation_name = admission.value.relation_name;
    form.patient_age = admission.value.age;
    form.received_amount = admission.value.total_amount;

  } catch (error) {
    console.error('Error fetching data:', error);
  }
};



const loadPatient = async (patientId) => {
    try {
        const response = await axios.get(`/patients/${patientId}`);
        let patient = response?.data?.patient || null;
        if (patient) {
            form.patient_name = patient?.name || null;
            form.mr_number = patient?.mr_number || null;
            form.patient_cnic = patient?.cnic || null;
            form.patient_age = patient?.age || null;
            form.patient_dob = patient?.dob || null;
            form.patient_gender = patient?.gender || null;
            form.patient_relation = patient?.relation_name || null;
            form.patient_address = patient?.address || null;
            form.patient_state_id = patient?.state_id || null;
            form.patient_country_id = patient?.country_id || null;
            form.patient_phone = patient?.phone || null;

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

const cancel = () => {
    Inertia.visit(route("operations.index"));
};

const selectedDepartment = ref(null);
const onDepartmentSelect = async (selectedDepartment) => {
    form.department_id = selectedDepartment?.id ?? null;
};
if (props?.operation) {
    const group = props.departments.find((c) => c.id == props.operation.department_id);
    if (group) {
        selectedDepartment.value = group;
    }
}


const generateMRNumber = (event) => {
    let input = event.target.value.replace(/\D/g, "");
    let number = input ? parseInt(input) : 1;
    let formattedNumber = number.toString().padStart(5, '0');
    form.mr_number = formattedNumber;
};
const handleSubmit = () => {
    const request = form.id
        ? form.put(route('operations.update', { id: form.id }), form)
        : form.post(route('operations.store'), form);
};



defineComponent({
    components: {
        Multiselect,
    },
});
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
