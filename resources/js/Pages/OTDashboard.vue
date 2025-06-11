<template>
    <div class="w-full mx-auto sm:px-6 lg:px-8">

        <div class="grid grid-cols-12 gap-4 mb-6 h-full">
            <div class="md:col-span-12 col-span-12">
                <div class="grid grid-cols-12 gap-4 mb-6 bg-white py-4 px-6 rounded-lg shadow-md">
                    <div class="col-span-12">
                        <form @submit.prevent="submitForm()" enctype="multipart/form-data">
                            <div class="py-6">
                                <div>
                                    <h2 class="text-3xl font-semibold text-gray-900">
                                        Operation Theatre
                                    </h2>
                                    <!-- <p class="mt-1 text-sm text-gray-900">
                              This information will be displayed publicly so be careful what you share.
                          </p> -->
                                </div>

                                <div class="py-6">
                                    <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-6">
                                        <div class="">
                                            <label for="file_no"
                                                class="block text-md font-semibold leading-6 text-gray-900">File No.
                                            </label>
                                            <div class="mt-1">
                                                <input id="file_no" name="file_no" type="text"
                                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01"
                                                    @input="fetchAdmissionData"  v-model="operation.file_no" />
                                            </div>
                                            <InputError v-if="operation.errors?.file_no"
                                                :message="operation.errors?.file_no" />
                                        </div>
                                         <div class="">
                                            <label for="mr_number"
                                                class="block text-md font-semibold leading-6 text-gray-900">Mr Number.
                                            </label>
                                            <div class="mt-1">
                                                <input id="mr_number" name="mr_number" type="text"
                                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01" @input="generateMRNumber"
                                                    @blur="loadPatient($event.target.value)"
                                                    :value="operation.mr_number" />
                                            </div>
                                            <InputError v-if="operation.errors?.mr_number"
                                                :message="operation.errors?.mr_number" />
                                        </div>
                                        <div>
                                            <label for="name"
                                                class="block text-md font-semibold leading-6 text-gray-900">Name
                                                <span class="text-red-500">*</span>
                                            </label>
                                            <div class="mt-1">
                                                <input id="name" name="name" type="text" autocomplete="name"
                                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01" v-model="operation.patient_name" />
                                            </div>
                                            <InputError v-if="operation.errors?.patient_name"
                                                :message="operation.errors?.patient_name" />
                                        </div>

                                        <div>
                                            <label for="phone"
                                                class="block text-md font-semibold leading-6 text-gray-900">Phone</label>
                                            <div class="mt-1">
                                                <input id="phone" name="phone" type="text" autocomplete="phone"
                                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    v-model="operation.patient_phone" @input="formatPhone"
                                                    maxlength="12" @blur="loadPatient($event.target.value)" />
                                            </div>
                                            <InputError v-if="operation.errors?.patient_phone"
                                                :message="operation.errors?.patient_phone" />
                                        </div>

                                        <div>
                                            <label for="cnic"
                                                class="block text-md font-semibold leading-6 text-gray-900">CNIC
                                            </label>
                                            <div class="mt-1">
                                                <input id="cnic" name="cnic" type="text" autocomplete="cnic"
                                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01" @blur="loadPatient($event.target.value)"
                                                    v-model="operation.patient_cnic" @input="formatCNIC"
                                                    maxlength="15" />
                                            </div>
                                            <InputError v-if="operation.errors?.patient_cnic"
                                                :message="operation.errors?.patient_cnic" />
                                        </div>
                                        <div>
                                            <label for="age"
                                                class="block text-md font-semibold leading-6 text-gray-900">Age
                                            </label>
                                            <div class="mt-1">
                                                <input id="age" name="age" type="text" autocomplete="age"
                                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01" v-model="operation.patient_age" />
                                            </div>
                                            <InputError v-if="operation.errors?.patient_age"
                                                :message="operation.errors?.patient_age" />
                                        </div>

                                        <div>
                                            <label for="gender"
                                                class="block text-md font-semibold leading-6 text-gray-900">Gender</label>
                                            <div class="mt-1">
                                                <multiselect v-model="selectedGender" :options="genderOptions"
                                                    @select="onGenderSelect" label="label" track-by="value"
                                                    placeholder="Select Gender"></multiselect>
                                            </div>
                                            <InputError v-if="operation.errors?.patient_gender"
                                                :message="operation.errors?.patient_gender" />
                                        </div>

                                        <div>
                                            <label for="relation_name"
                                                class="block text-md font-semibold leading-6 text-gray-900">Relation
                                                name</label>
                                            <div class="mt-1">
                                                <input id="relation_name" name="relation_name" type="text"
                                                    autocomplete="relation_name"
                                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01" v-model="operation.patient_relation_name" />
                                            </div>
                                            <InputError v-if="operation.errors?.patient_relation_name"
                                                :message="operation.errors?.patient_relation_name" />
                                        </div>
                                        <div>
                                            <label for="address"
                                                class="block text-md font-semibold leading-6 text-gray-900">Address</label>
                                            <div class="mt-1">
                                                <input id="address" name="address" type="text" autocomplete="address"
                                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    v-model="operation.patient_address" />
                                            </div>
                                            <InputError v-if="operation.errors?.patient_address"
                                                :message="operation.errors?.patient_address" />
                                        </div>
                                        <div v-if="hasDonationModule">
                                            <label for="careoff_id"
                                                class="block text-md font-semibold leading-6 text-gray-900">Care Off

                                            </label>
                                            <div class="mt-1">
                                                <multiselect v-model="selectedCareOff" :options="careoffs"
                                                    @update:modelValue="onCareOffSelect" label="name" track-by="id"
                                                    placeholder="Select a CareOff"></multiselect>

                                                <input id="donor_fee" name="donor_fee" type="text"
                                                    autocomplete="donor_fee" placeholder="Careoff Fee"
                                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01" @input="calculateAmount()" v-model="operation.donor_fee"
                                                    v-if="operation.careoff_id != null && operation.careoff_id != ''" />

                                            </div>
                                            <InputError v-if="operation?.errors?.careoff_id"
                                                :message="operation?.errors?.careoff_id" />
                                        </div>


                                        <div v-if="hasDonationModule">
                                            <label for="donor_id"
                                                class="block text-md font-semibold leading-6 text-gray-900">Zf

                                            </label>
                                            <div class="mt-1">
                                                <input id="name" name="name" type="text" autocomplete="name"
                                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    step="0.01"
                                                    @input="checkAmount($event.target.value, 'General', zf_fees)"
                                                    v-model="operation.zf_fee" />
                                            </div>
                                            <InputError v-if="operation?.errors?.zf_fee"
                                                :message="operation?.errors?.zf_fee" />
                                        </div>


                                        <div>
                                            <label for="welfare"
                                                class="block text-md font-semibold leading-6 text-gray-900">Authorized</label>
                                            <div class="mt-1">
                                                <input id="welfare" name="welfare" type="text" autocomplete="welfare"
                                                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                                    v-model="operation.welfare" />
                                            </div>
                                            <InputError v-if="operation.errors?.welfare"
                                                :message="operation.errors?.welfare" />
                                        </div>

                                    </div>
                                </div>

                                <div class="divide-y divide-gray-200">
                                    <div class="flex justify-end gap-x-3 px-4 sm:px-6">
                                        <button type="submit"
                                            class="rounded bg-primary px-5 py-2 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                                            :disabled="operation.processing">
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
import { ref, watchEffect, watch,computed, onMounted, reactive } from "vue";
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
    operation_theater: Array,
    follow_ups: Array,
    doctors: Object,
    careoffs: Object,
    zf_fees: String,
    patients: Object,
    hasDonationModule: Boolean,
});
const operation = useForm({
    id: null,
    consulting_doctor_id: null,
    operation_date: getCurrentDateInPakistanTimezone(),
    patient_name: null,
    patient_phone: null,
    mr_number: props.mr_number || "",
    patient_cnic: null,
    patient_relation_name: null,
    file_no: null,
    patient_address: null,
    patient_gender: null,
    patient_age: null,
    careoff_id: null,
    donor_fee: null,
    zf_id: null,
    welfare: null,
    zf_fee: null,
    source: 'dashboard',

});
const matchedPatient = ref(null);
const admission = ref(null);

const fetchAdmissionData = async () => {
  try {
    const fileNoValue = operation.file_no;
      console.log('fileNoValue',fileNoValue);
    const response = await axios.get('/admissions_data', {
      params: {
        file_no: fileNoValue,
      },
    });

    admission.value = response.data.admission;
    matchedPatient.value = response.data.matched_patient;

    operation.patient_name = admission.value.name;
    operation.patient_cnic = admission.value.cnic;
    operation.patient_phone = admission.value.phone;
    operation.patient_address = admission.value.address;
    operation.patient_relation_name = admission.value.relation_name;
    operation.patient_age = admission.value.age;
    operation.received_amount = admission.value.total_amount;

  } catch (error) {
    console.error('Error fetching data:', error);
  }
};
onMounted(() => {
    operation.mr_number = props.mr_number || "";
});
const formatPhone = (event) => {
    let input = event.target.value.replace(/\D/g, "");
    if (input.length > 4) {
        input = input.replace(/^(\d{4})(\d+)/, "$1-$2");
    }
    operation.patient_phone = input;
};
const formatCNIC = (event) => {
    let input = event.target.value.replace(/\D/g, "");
    if (input.length > 5) {
        input = input.replace(/^(\d{5})(\d{7})(\d{1})$/, "$1-$2-$3");
    }
    operation.patient_cnic = input;
};

const generateMRNumber = (event) => {
    let input = event.target.value.replace(/\D/g, "");
    let number = input ? parseInt(input) : 1;
    let formattedNumber = number.toString().padStart(5, '0');
    operation.mr_number = formattedNumber;

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
    operation.patient_gender = selectedGender.value;
};


const selectedCareOff = ref(null);
const selectedZf = ref(null);


const onCareOffSelect = async (selectedCareOffs) => {
    operation.careoff_id = selectedCareOffs?.id ?? null;
    if (operation.careoff_id == null) {
        operation.donor_fee = null;
        calculateAmount();
    }
};

//   const onServiceSelect = async (selectedDoctor, index) => {
//     // operation.appointment_details[index].fee = selectedDoctor.charges;
//   };

const onZfSelect = async (selectedZf) => {
    operation.zf_id = selectedZf.id;
    selectedCareOff.value = null;
    operation.careoff_id = null;
    operation.zf_fee = null;
};

watch(() => operation.file_no, (newVal) => {
  console.log('File No Changed:', newVal);
});


const payableAmount = ref();

const calculateAmount = () => {
    let sum = 0;
    operation.appointment_details.map(res => {
        sum += parseFloat(res.fee || 0);
    })
    payableAmount.value = sum;
    payableAmount.value = +payableAmount.value - +(operation.donor_fee || 0) - +(operation.zf_fee || 0);
};

const submitForm = () => {
    operation.processing = true;
    operation.post(route('operations.store'), {
        preserveScroll: true,
        operation,
        onSuccess: (res) => {
            operation.processing = false;
            operation.patient_name = null;
            operation.patient_phone = null;
            operation.patient_cnic = null;
            operation.patient_address = null;
            operation.patient_gender = null;
            selectedGender.value = null;
            operation.patient_age = null;
            operation.careoff_id = null;
            operation.file_no = null;
            operation.donor_fee = null;
            operation.zf_id = null;
            operation.welfare = null;
            operation.consulting_doctor_id = null;
            operation.patient_relation_name = null;
            operation.zf_fee = null;
            selectedZf.value = null;
            selectedCareOff.value = null;

            // operation.mr_number = res.mr_number || '';
            var url_string = window.location.href;
            var url = new URL(url_string);
            var appointment_id = url.searchParams.get("operation_id");
            // printAppointment(operati_id);
            // router.visit('/dashboard');
            const currentUrl = new URL(window.location.href);
            const tab = currentUrl.searchParams.get('menuTab') || '4';
            router.visit(`/dashboard?menuTab=${tab}`);
        },
        onError: (error) => {
            operation.processing = false;
        }
    })
};



const printAppointment = (appointment_id) => {

    const url = route('operations.print', appointment_id);
    if (!url) {
        return;
    }
    // const url = route('operations.print', appointment_id);
    const printWindow = window.open(url, '_blank');


};

const checkAmount = (val, type, zf_fees) => {

};

const loadPatient = async (patientPhone) => {
    try {
        const response = await axios.get(`/patients-phone/${patientPhone}`);
        let patient = response?.data?.patient || null;
        if (patient) {
            operation.patient_name = patient?.name || null;
            operation.patient_cnic = patient?.cnic || null;
            operation.patient_age = patient?.age || null;
            operation.patient_relation_name = patient?.relation_name || null;
            operation.patient_gender = patient?.gender || null;
            operation.patient_address = patient?.address || null;
            operation.mr_number = patient?.mr_number || null;
        }
    } catch (error) {
        console.error("Error fetching states:", error);
    }
};

// const loadSpecialPatient = async (patientPhone) => {
//     try {
//         const response = await axios.get(`/patients-phone/${patientPhone}`);
//         let patient = response?.data?.patient || null;
//         if (patient) {
//             special_appointment.patient_name = patient?.name || null;
//             special_appointment.patient_cnic = patient?.cnic || null;
//             special_appointment.patient_age = patient?.age || null;
//             special_appointment.patient_relation_name = patient?.relation_name || null;
//             special_appointment.patient_address = patient?.address || null;
//             special_appointment.mr_number = patient?.mr_number || null;
//         }
//     } catch (error) {
//         console.error("Error fetching states:", error);
//     }
// };


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
        mr_number: String,
        operation_theater: Array,
        follow_ups: Array,
        doctors: Object,
        careoffs: Object,
        zf_fees: String,
        patients: Object,
        services: Array,
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
