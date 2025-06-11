<template>
    <div v-if="projectType == 'hms'" class="a4-size relative mx-auto flex flex-col rounded-lg shadow-md">
        <div class="relative flex-initial">
            <div class="absolute top-0 left-0">
                <svg
                    class="w-64 h-32"
                    viewBox="0 0 117 43"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M14.5 0V32C35.1291 20.0986 90.579 5.90201 116.365 0.141406L14.5 0Z"
                        fill="#F5B553"
                    />
                    <path
                        d="M0 0V42.5C16 23.7 77.3333 6.33333 106 0H0Z"
                        fill="#231F20"
                    />
                </svg>
            </div>
            <div class="flex items-center justify-end pt-8 pr-4">
                <div>
                    <h2
                        v-if="appointment.is_printed == '2'"
                        class="bg-gray-200 text-gray-900 text-center border border-gray-300 text-md font-semibold w-24"
                    >
                        Duplicate
                    </h2>
                    <h2 class="doctor-info text-xl font-semibold">
                        {{ appointment?.doctor?.name }}
                    </h2>
                    <h3 class="doctor-info">
                        <template
                            v-for="(education, index) in appointment?.doctor
                                ?.education"
                        >
                            {{ education.degree
                            }}<span
                                v-if="
                                    appointment.doctor.education.length - 1 !=
                                    index
                                "
                                >,</span
                            >
                        </template>
                    </h3>
                </div>
            </div>
            <div>
                <svg
                    class="w-full h-auto"
                    viewBox="0 0 344 53"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M115 1.50003C62.5 4 17 22.9999 0.5 32.9999C37.7 13.3998 88.5 9 115 7.00003C141.098 5.03039 185.667 12.9999 205 18C207.4 18.4 244.333 28.8334 262.5 34C276.5 40 322.333 48.1667 343.5 51.5V39C315.1 36.6 269.333 25.3334 250 20C226.8 13.6 201.333 8.66661 191.5 6.9999C160.7 0.599902 127.667 0.500034 115 1.50003Z"
                        fill="#D1D3D4"
                        stroke="#D1D3D4"
                    />
                </svg>
            </div>
            <div
                class="header-accent -mt-12 pl-12 flex items-center space-x-8 text-center"
            >
                <img class="h-auto w-24" src="@/icons/logo.png" alt="" />
                <div>
                    <h1 class="doctor-info text-3xl font-bold">
                        CHINIOT GENERAL HOSPITAL
                    </h1>
                    <div class="text-center text-sm">
                        <p v-if="projectType === 'hms'">
                            Nawaz Town Sargodha Road, Faisalabad
                        </p>
                        <p v-else>209 Jinnah Colony, Faisalabad</p>
                        <p v-if="projectType === 'hms'">
                            Ph: 041-8787231-8848060
                        </p>
                        <p v-else>Ph: 041-2618764-2634890</p>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-2.5">
                    <div>
                        <p
                            class="border-gray-500 border h-8 w-16 rounded-md text-center"
                        >
                            {{ appointment.slip_no }}
                        </p>
                    </div>
                    <!-- <div><p class="border-gray-500 border h-8 w-16 rounded-md text-center">{{ mr_number}}</p></div> -->
                    <div>
                        <p
                            class="border-gray-500 border h-8 w-16 rounded-md text-center ml-2"
                        >
                            {{ appointment.doc_serial }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-auto mt-3">
            <div
                class="grid grid-cols-4 mx-4 gap-4 px-4 mb-4 border border-dashed border-gray-300 py-4"
            >
                <div class="space-y-4">
                    <div>
                        <span class="font-bold whitespace-nowrap">Sr#</span>
                        {{ appointment.id }}
                    </div>
                    <div>
                        <span class="font-bold whitespace-nowrap">Name:</span>
                        {{ appointment.patient_name }}
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <span class="font-bold whitespace-nowrap">Date:</span>
                        {{ formattedDate }}
                    </div>
                    <div>
                        <span class="font-bold whitespace-nowrap"
                            >Age /Sex :</span
                        >
                        {{ appointment.patient_age }} /
                        {{ appointment?.patient_gender }}
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <span class="font-bold whitespace-nowrap">Fees:</span>
                        {{ withCommas(sumAppointmentFees ?? 0) }}
                    </div>
                    <div
                        v-if="appointment?.zf_fee && appointment?.donor_fee"
                        class="whitespace-nowrap"
                    >
                        <span class="font-bold whitespace-nowrap"
                            >Less C/O/ZF:</span
                        >
                        {{ withCommas(+appointment?.donor_fee ?? 0) }} /
                        {{ withCommas(+appointment?.zf_fee ?? 0) }}
                    </div>

                    <div v-else-if="appointment?.zf_fee">
                        <span class="font-bold whitespace-nowrap"
                            >Less ZF:</span
                        >
                        {{ withCommas(appointment?.zf_fee ?? 0) }}
                    </div>

                    <div v-else-if="appointment?.donor_fee">
                        <span class="font-bold whitespace-nowrap"
                            >Less C/O:</span
                        >
                        {{ withCommas(appointment?.donor_fee ?? 0) }}
                    </div>
                    <div>
                        <span class="font-bold whitespace-nowrap">Mr:</span
                        >{{ mr_number }}
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <span class="font-bold whitespace-nowrap">Time:</span>
                        {{ formattedTime }}
                    </div>

                    <div v-if="appointment?.zf_fee && appointment?.donor_fee">
                        <span class="font-bold whitespace-nowrap">Net:</span>
                        {{
                            +sumAppointmentFees -
                            +(
                                withCommas(
                                    +appointment?.zf_fee +
                                        +appointment?.donor_fee
                                ) ?? 0
                            )
                        }}
                    </div>
                    <div v-else-if="appointment?.zf_fee">
                        <span class="font-bold whitespace-nowrap">Net:</span>
                        {{
                            withCommas(
                                +sumAppointmentFees -
                                    +(+appointment?.zf_fee ?? 0)
                            )
                        }}
                    </div>
                    <div v-else-if="appointment?.donor_fee">
                        <span class="font-bold whitespace-nowrap">Net:</span>
                        {{
                            withCommas(
                                +sumAppointmentFees -
                                    +(+appointment?.donor_fee ?? 0)
                            )
                        }}
                    </div>
                </div>
            </div>
            <div
                style="height: 75%"
                class="prescription-area flex flex-grow border border-t-2 border-r-0 border-l-0 border-gray-300"
            >
                <div class="left px-5">
                    <div>
                        <ul class="flex-1 space-y-4 mt-8 font-semibold">
                            <li>BP</li>
                            <li>Temprature</li>
                            <li>Spo2</li>
                            <li>RBS</li>
                        </ul>
                    </div>
                </div>
                <div class="right px-5">
                    <h1 class="text-2xl leading-tight tracking-tighter">
                        R<sub>x</sub>
                    </h1>
                </div>
            </div>
            <div
                class="mb-1 px-20 flex justify-between border border-b-2 border-t-2 border-r-0 border-l-0 border-gray-300 p-2"
            >
                <span class="text-xs"
                    >Recp: {{ appointment?.creator?.name }}</span
                >
                <span
                    class="font-bold"
                    v-if="
                        appointment?.careoff_id != '' &&
                        appointment?.careoff_id != null
                    "
                    >C/O:
                    {{
                        appointment?.careoff_id != "" &&
                        appointment?.careoff_id != null
                            ? appointment?.careoff?.name
                            : ""
                    }}</span
                >
                <span class="font-bold"
                    >Authority: {{ appointment?.welfare }}</span
                >
            </div>
            <div class="text-xs text-center">
                <!-- <div>Nawaz Town Sargodha Road, Faisalabad Ph: 042-8848060-8787231</div> -->
                <div>
                    ( Under Supervision of MOFAD-E-AMMA Chiniot Sheikh
                    Association.Reg:fsd )
                </div>
            </div>
        </div>
    </div>

    <div v-else class="a4-size relative mx-auto flex flex-col rounded-lg shadow-md">
        <div class="relative flex-initial">
            <!-- <div class="absolute top-0 left-0">
                <svg
                    class="w-64 h-32"
                    viewBox="0 0 117 43"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M14.5 0V32C35.1291 20.0986 90.579 5.90201 116.365 0.141406L14.5 0Z"
                        fill="#F5B553"
                    />
                    <path
                        d="M0 0V42.5C16 23.7 77.3333 6.33333 106 0H0Z"
                        fill="#231F20"
                    />
                </svg>
            </div> -->
            <div class="flex items-center justify-end pt-8 pb-2 pr-4">
                <div>
                    <h2
                        v-if="appointment.is_printed == '2'"
                        class="bg-gray-200 text-gray-900 text-center border border-gray-300 text-md font-semibold w-24"
                    >
                        Duplicate
                    </h2>

                </div>
            </div>
            <!-- <div>
                <svg
                    class="w-full h-auto"
                    viewBox="0 0 344 53"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M115 1.50003C62.5 4 17 22.9999 0.5 32.9999C37.7 13.3998 88.5 9 115 7.00003C141.098 5.03039 185.667 12.9999 205 18C207.4 18.4 244.333 28.8334 262.5 34C276.5 40 322.333 48.1667 343.5 51.5V39C315.1 36.6 269.333 25.3334 250 20C226.8 13.6 201.333 8.66661 191.5 6.9999C160.7 0.599902 127.667 0.500034 115 1.50003Z"
                        fill="#D1D3D4"
                        stroke="#D1D3D4"
                    />
                </svg>
            </div> -->
            <div
                class="header-accent -mt-12 pl-12 flex items-center space-x-8 text-center"
            >
                <img class="h-auto w-24" src="@/icons/logo.png" alt="" />
                <div>
                    <h1 class="doctor-info text-3xl font-bold">
                        CHINIOT GENERAL HOSPITAL
                    </h1>
                    <div class="text-center text-sm">
                        <p v-if="projectType === 'hms'">
                            Nawaz Town Sargodha Road, Faisalabad
                        </p>
                        <p v-else>209 Jinnah Colony, Faisalabad</p>
                        <p v-if="projectType === 'hms'">
                            Ph: 041-8787231-8848060
                        </p>
                        <p v-else>Ph: 041-2618764-2634890</p>
                        <div>
                            <h2 class="doctor-info text-xl font-semibold">
                        {{ appointment?.doctor?.name }}
                    </h2>
                    <h3 class="doctor-info">
                        <template
                            v-for="(education, index) in appointment?.doctor
                                ?.education"
                        >
                            {{ education.degree
                            }}<span
                                v-if="
                                    appointment.doctor.education.length - 1 !=
                                    index
                                "
                                >,</span
                            >
                        </template>
                    </h3>

                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-2.5">
                    <div>
                        <p
                            class="border-gray-500 border h-8 w-16 rounded-md text-center"
                        >
                            {{ appointment.slip_no }}
                        </p>
                    </div>
                    <!-- <div><p class="border-gray-500 border h-8 w-16 rounded-md text-center">{{ mr_number}}</p></div> -->
                    <div>
                        <p
                            class="border-gray-500 border h-8 w-16 rounded-md text-center ml-2"
                        >
                            {{ appointment.doc_serial }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex-auto mt-3">
            <div
                class="grid grid-cols-4 mx-4 gap-4 px-4 mb-4 border border-dashed border-gray-300 py-4"
            >
                <div class="space-y-4">
                    <div>
                        <span class="font-bold whitespace-nowrap">Sr#</span>
                        {{ appointment.id }}
                    </div>
                    <div>
                        <span class="font-bold whitespace-nowrap">Name:</span>
                        {{ appointment.patient_name }}
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <span class="font-bold whitespace-nowrap">Date:</span>
                        {{ formattedDate }}
                    </div>
                    <div>
                        <span class="font-bold whitespace-nowrap"
                            >Age /Sex :</span
                        >
                        {{ appointment.patient_age }} /
                        {{ appointment?.patient_gender }}
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <span class="font-bold whitespace-nowrap">Fees:</span>
                        {{ withCommas(sumAppointmentFees ?? 0) }}
                    </div>
                    <div
                        v-if="appointment?.zf_fee && appointment?.donor_fee"
                        class="whitespace-nowrap"
                    >
                        <span class="font-bold whitespace-nowrap"
                            >Less C/O/ZF:</span
                        >
                        {{ withCommas(+appointment?.donor_fee ?? 0) }} /
                        {{ withCommas(+appointment?.zf_fee ?? 0) }}
                    </div>

                    <div v-else-if="appointment?.zf_fee">
                        <span class="font-bold whitespace-nowrap"
                            >Less ZF:</span
                        >
                        {{ withCommas(appointment?.zf_fee ?? 0) }}
                    </div>

                    <div v-else-if="appointment?.donor_fee">
                        <span class="font-bold whitespace-nowrap"
                            >Less C/O:</span
                        >
                        {{ withCommas(appointment?.donor_fee ?? 0) }}
                    </div>
                    <div>
                        <span class="font-bold whitespace-nowrap">Mr:</span
                        >{{ mr_number }}
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <span class="font-bold whitespace-nowrap">Time:</span>
                        {{ formattedTime }}
                    </div>

                    <div v-if="appointment?.zf_fee && appointment?.donor_fee">
                        <span class="font-bold whitespace-nowrap">Net:</span>
                        {{
                            +sumAppointmentFees -
                            +(
                                withCommas(
                                    +appointment?.zf_fee +
                                        +appointment?.donor_fee
                                ) ?? 0
                            )
                        }}
                    </div>
                    <div v-else-if="appointment?.zf_fee">
                        <span class="font-bold whitespace-nowrap">Net:</span>
                        {{
                            withCommas(
                                +sumAppointmentFees -
                                    +(+appointment?.zf_fee ?? 0)
                            )
                        }}
                    </div>
                    <div v-else-if="appointment?.donor_fee">
                        <span class="font-bold whitespace-nowrap">Net:</span>
                        {{
                            withCommas(
                                +sumAppointmentFees -
                                    +(+appointment?.donor_fee ?? 0)
                            )
                        }}
                    </div>
                </div>
            </div>
            <div
                style="height: 15%"
                class="prescription-area flex flex-grow border border-t-2 border-r-0 border-l-0 border-gray-300"
            >
                <div class="left px-5">
                    <div>
                        <!-- <ul class="flex-1 space-y-4 mt-8 font-semibold">
                            <li>BP</li>
                            <li>Temprature</li>
                            <li>Spo2</li>
                            <li>RBS</li>
                        </ul> -->
                    </div>
                </div>
                <div class="right px-5">
                    <h1 class="text-2xl leading-tight tracking-tighter">
                        R<sub>x</sub>
                    </h1>
                </div>
            </div>
            <div
                class="mb-1 px-20 flex justify-between border border-b-2 border-t-2 border-r-0 border-l-0 border-gray-300 p-2"
            >
                <span class="text-xs"
                    >Recp: {{ appointment?.creator?.name }}</span
                >
                <span
                    class="font-bold"
                    v-if="
                        appointment?.careoff_id != '' &&
                        appointment?.careoff_id != null
                    "
                    >C/O:
                    {{
                        appointment?.careoff_id != "" &&
                        appointment?.careoff_id != null
                            ? appointment?.careoff?.name
                            : ""
                    }}</span
                >
                <span class="font-bold"
                    >Authority: {{ appointment?.welfare }}</span
                >
            </div>
            <div class="text-xs text-center">
                <!-- <div>Nawaz Town Sargodha Road, Faisalabad Ph: 042-8848060-8787231</div> -->
                <div>
                    ( Under Supervision of MOFAD-E-AMMA Chiniot Sheikh
                    Association.Reg:fsd )
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
    methods: {
        reloadPage() {
            window.location.reload();
        },
        preventMouseClick(event) {
            event.preventDefault();
            event.stopPropagation();
        },
        handleKeydown(event) {
            if (event.ctrlKey && event.key === "p") {
                event.preventDefault();
                window.location.reload();
            }
        },
        withCommas(value) {
            console.log("value", value);
            if (value == null || value == "") {
                return "0";
            }
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
    },
    computed: {
        formattedDate() {
            const date = new Date(this.appointment.appointment_date);
            const day = String(date.getDate()).padStart(2, "0");
            const month = String(date.getMonth() + 1).padStart(2, "0");
            const year = date.getFullYear();

            return `${day}-${month}-${year}`;
        },
        todaysPatientCount() {
            const today = new Date().toISOString().split("T")[0];
            if (!this.appointment || !this.appointment.appointment_date)
                return 0;

            return this.appointment.appointment_details.filter((detail) => {
                const appointmentDate = new Date(detail.appointment_date)
                    .toISOString()
                    .split("T")[0];
                return appointmentDate === today;
            }).length;
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
    @page {
        size: A4;
        margin: 0;
    }
    body {
        margin: 0;
    }
}
.a4-size {
    width: 210mm;
    height: 297mm;
}
.p_20 {
    padding: 20mm;
}
.px_20 {
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
    font-family: "Helvetica", Arial, sans-serif;
}
.header-accent {
    border-bottom: 4px solid #f5b553;
    padding-bottom: 4px;
}
</style>
