<template>
    <Head title="Employee shift roster" />

    <AppLayout title="Employee shift roster">
        <div class="w-full mx-auto pt-6">
            <div
                class="bg-white overflow-hidden shadow-sm rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6"
            >
                <div>
                    <form
                        @submit.prevent="
                            form.get(route('employee-shift-roster.index'))
                        "
                        enctype="multipart/form-data"
                    >
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-7 py-6">
                            <div class="md:col-span-2">
                                <label
                                    for="department"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Department</label
                                >
                                <div class="mt-1">
                                    <multiselect
                                        v-model="selectedDepartment"
                                        :options="departments"
                                        :searchable="true"
                                        :close-on-select="true"
                                        :show-labels="false"
                                        @select="onDepartmentSelect"
                                        placeholder="Select department"
                                        label="name"
                                        track-by="id"
                                    />
                                    <InputError
                                        v-if="form.errors?.department_id"
                                        :message="form.errors?.department_id"
                                    />
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    for="designation"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Designation</label
                                >
                                <div class="mt-1">
                                    <multiselect
                                        v-model="selectedDesignation"
                                        :options="designations"
                                        :searchable="true"
                                        :close-on-select="true"
                                        :show-labels="false"
                                        @select="onDesignationSelect"
                                        placeholder="Select designation"
                                        label="name"
                                        track-by="id"
                                    />
                                    <InputError
                                        v-if="form.errors?.designation_id"
                                        :message="form.errors?.designation_id"
                                    />
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    for="grade"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Grades</label
                                >
                                <div class="mt-1">
                                    <multiselect
                                        v-model="selectedGrade"
                                        :options="grades"
                                        :searchable="true"
                                        :close-on-select="true"
                                        :show-labels="false"
                                        @select="onGradeSelect"
                                        placeholder="Select grade"
                                        label="name"
                                        track-by="id"
                                    />
                                    <InputError
                                        v-if="form.errors?.grade_id"
                                        :message="form.errors?.grade_id"
                                    />
                                </div>
                            </div>
                            <div class="place-self-center pt-5">
                                <button
                                    type="submit"
                                    class="block rounded bg-primary w-fit px-4 py-2 text-center text-sm md:text-base font-medium text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer"
                                    :disabled="form.processing"
                                >
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div>
                    <form
                        @submit.prevent="storeRoster()"
                        v-if="
                            employees.data.filter((item) => item.selected)
                                .length > 0 ||
                            Object.keys(roster.errors).length > 0
                        "
                    >
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-9 py-6">
                            <div class="sm:col-span-2">
                                <label
                                    for="working days"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Working days</label
                                >
                                <div class="mt-1">
                                    <multiselect
                                        v-model="workingDays"
                                        :options="weekDaysOptions"
                                        @select="onWorkingDaySelect"
                                        @remove="onWorkingDayUnselect"
                                        :searchable="true"
                                        :close-on-select="true"
                                        :show-labels="false"
                                        :multiple="true"
                                        placeholder="Select working days"
                                        label="label"
                                        track-by="value"
                                    />
                                </div>
                                <InputError
                                    v-if="roster.errors?.working_days"
                                    :message="roster.errors?.working_days"
                                />
                            </div>
                            <div class="sm:col-span-2">
                                <label
                                    for="shift"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >Shift</label
                                >
                                <div class="mt-1">
                                    <multiselect
                                        v-model="selectedShift"
                                        :options="shifts"
                                        :searchable="true"
                                        :close-on-select="true"
                                        :show-labels="false"
                                        @select="onShiftSelect"
                                        placeholder="Select shift"
                                        label="name"
                                        track-by="id"
                                    />
                                    <InputError
                                        v-if="roster.errors?.shift_id"
                                        :message="roster.errors?.shift_id"
                                    />
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label
                                    for="from_date"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >From date</label
                                >
                                <input
                                    type="date"
                                    name="from_date"
                                    id="from_date"
                                    autocomplete="from_date-number"
                                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                    v-model="roster.from_date"
                                />
                                <InputError
                                    v-if="roster?.errors?.from_date"
                                    :message="roster?.errors?.from_date"
                                />
                            </div>
                            <div class="sm:col-span-2">
                                <label
                                    for="to_date"
                                    class="block text-sm font-medium leading-6 text-gray-900"
                                    >To date</label
                                >
                                <input
                                    type="date"
                                    name="to_date"
                                    id="to_date"
                                    autocomplete="to_date-number"
                                    class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                                    v-model="roster.to_date"
                                />
                                <InputError
                                    v-if="roster?.errors?.to_date"
                                    :message="roster?.errors?.to_date"
                                />
                            </div>
                            <div class="pt-7">
                                <button
                                    type="submit"
                                    class="block rounded bg-primary w-fit px-4 py-2 text-center text-sm md:text-base font-medium text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer"
                                    :disabled="roster.processing"
                                >
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div
                    class="flex flex-col md:flex-row items-start md:items-center justify-between"
                >
                    <h1 class="font-semibold text-3xl text-primary">
                        Employee shift roster
                    </h1>
                </div>
                <div class="mt-6 flow-root">
                    <InputError
                        v-if="roster?.errors?.employee"
                        :message="roster?.errors?.employee"
                    />
                    <template
                        v-for="(employee, index) in roster.employees"
                        :key="`InputError-${index}`"
                    >
                        <div>
                            <InputError
                                v-if="
                                    roster?.errors?.[
                                        `employee?.${index}?.employee_id`
                                    ]
                                "
                                :message="
                                    roster?.errors?.[
                                        `employee?.${index}?.employee_id`
                                    ]
                                "
                            />
                            <InputError
                                v-if="
                                    roster?.errors?.[
                                        `employee?.${index}?.department_id`
                                    ]
                                "
                                :message="
                                    roster?.errors?.[
                                        `employee?.${index}?.department_id`
                                    ]
                                "
                            />
                            <InputError
                                v-if="
                                    roster?.errors?.[
                                        `employee?.${index}?.roster_type_id`
                                    ]
                                "
                                :message="
                                    roster?.errors?.[
                                        `employee?.${index}?.roster_type_id`
                                    ]
                                "
                            />
                            <InputError
                                v-if="
                                    roster?.errors?.[
                                        `employee?.${index}?.rest_day`
                                    ]
                                "
                                :message="
                                    roster?.errors?.[
                                        `employee?.${index}?.rest_day`
                                    ]
                                "
                            />
                        </div>
                    </template>
                    <div
                        class="ring-1 ring-gray-200 sm:rounded-lg overflow-x-auto overflow-y-hidden"
                    >
                        <table
                            class="min-w-full border-separate border-spacing-0 divide-y divide-gray-200 overflow-auto"
                        >
                            <thead>
                                <tr class="divide-x divide-gray-200">
                                    <th
                                        scope="col"
                                        class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                                    >
                                        <span class="sr-only">checkbox</span>
                                    </th>
                                    <th
                                        scope="col"
                                        class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                                    >
                                        Emp. ID
                                    </th>
                                    <th
                                        scope="col"
                                        class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                                    >
                                        Emp. Name
                                    </th>
                                    <th
                                        scope="col"
                                        class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                                    >
                                        Department
                                    </th>
                                    <th
                                        scope="col"
                                        class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                                    >
                                        Designation
                                    </th>
                                    <th
                                        scope="col"
                                        class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                                    >
                                        Duty Dept.
                                    </th>
                                    <th
                                        scope="col"
                                        class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                                    >
                                        Roster type
                                    </th>
                                    <th
                                        scope="col"
                                        class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                                    >
                                        Holiday status
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    class="divide-x divide-gray-200"
                                    v-for="(employee, index) in employees.data"
                                    :key="employee.id"
                                    :class="
                                        index % 2 === 0
                                            ? 'bg-gray-50'
                                            : 'bg-white'
                                    "
                                >
                                    <td
                                        class="whitespace-nowrap relative text-center p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                                    >
                                        <input
                                            type="checkbox"
                                            @click="
                                                updateEmployeeSelection(
                                                    $event.target.checked,
                                                    employee
                                                )
                                            "
                                        />
                                    </td>
                                    <td
                                        class="whitespace-nowrap relative text-center p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                                    >
                                        {{ employee.id }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap relative text-center p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                                    >
                                        {{ employee.name }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap relative text-center p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                                    >
                                        {{ employee?.department?.name }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap relative text-center p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                                    >
                                        {{ employee?.designation?.name }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap relative text-center p-2 text-gray-900 text-sm md:text-base border-b border-gray-200"
                                    >
                                        <multiselect
                                            v-model="employee.departmentId"
                                            :options="departments"
                                            :searchable="true"
                                            :close-on-select="true"
                                            :show-labels="false"
                                            placeholder="Select department"
                                            label="name"
                                            track-by="id"
                                        />
                                        <div v-if="roster && roster.errors">
                                            <div
                                                v-for="(
                                                    error, key
                                                ) in roster.errors"
                                                :key="key"
                                            >
                                                <InputError
                                                    v-if="
                                                        key.startsWith(
                                                            'employee.'
                                                        ) &&
                                                        key.endsWith(
                                                            '.department_id'
                                                        ) &&
                                                        key.includes(
                                                            `employee.${roster.employee.findIndex(
                                                                (e) =>
                                                                    e.employee_id ===
                                                                    employee.id
                                                            )}.`
                                                        )
                                                    "
                                                    :message="error"
                                                />
                                            </div>
                                        </div>
                                    </td>
                                    <td
                                        class="whitespace-nowrap relative text-center p-2 text-gray-900 text-sm md:text-base border-b border-gray-200"
                                    >
                                        <multiselect
                                            v-model="employee.rosterTypeId"
                                            :options="rosterTypes"
                                            :searchable="true"
                                            :close-on-select="true"
                                            :show-labels="false"
                                            placeholder="Select roster type"
                                            label="name"
                                            track-by="id"
                                        />
                                        <div v-if="roster && roster.errors">
                                            <div
                                                v-for="(
                                                    error, key
                                                ) in roster.errors"
                                                :key="key"
                                            >
                                                <InputError
                                                    v-if="
                                                        key.startsWith(
                                                            'employee.'
                                                        ) &&
                                                        key.endsWith(
                                                            '.roster_type_id'
                                                        ) &&
                                                        key.includes(
                                                            `employee.${roster.employee.findIndex(
                                                                (e) =>
                                                                    e.employee_id ===
                                                                    employee.id
                                                            )}.`
                                                        )
                                                    "
                                                    :message="error"
                                                />
                                            </div>
                                        </div>
                                    </td>
                                    <td
                                        class="whitespace-nowrap relative text-center p-2 text-gray-900 text-sm md:text-base border-b border-gray-200"
                                    >
                                        <multiselect
                                            v-model="employee.holiday"
                                            :options="weekDaysOptions"
                                            :searchable="true"
                                            :close-on-select="true"
                                            :show-labels="false"
                                            placeholder="Select holiday"
                                            label="label"
                                            track-by="value"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div
                        class="pagination-container py-4 flex justify-between px-1"
                    >
                        <div class="pagination-info flex">
                            Page
                            <span
                                class="font-semibold px-1 text-gray-900 report"
                                >{{ employees.current_page }}</span
                            >
                            of
                            <span
                                class="font-semibold px-1 text-gray-900 report"
                                >{{ employees.last_page }}</span
                            >
                            <div>
                                <p class="pagination-info">
                                    Showing
                                    <span
                                        class="font-semibold text-gray-900 report"
                                        >{{ employees.from }}</span
                                    >
                                    to
                                    <span
                                        class="font-semibold text-gray-900 report"
                                        >{{ employees.to }}</span
                                    >
                                    of
                                    <span
                                        class="font-semibold text-gray-900 report"
                                        >{{ employees.total }}</span
                                    >
                                    entries
                                </p>
                            </div>
                        </div>

                        <div class="pagination-boxes">
                            <template v-if="employees.prev_page_url">
                                <InertiaLink
                                    :key="'prev'"
                                    :href="employees.prev_page_url"
                                    class="pagination-box"
                                >
                                    <span>Previous</span>
                                </InertiaLink>
                            </template>

                            <template v-for="page in employees.last_page">
                                <InertiaLink
                                    :key="`page-${page}`"
                                    v-if="page === employees.current_page"
                                    :href="
                                        route('employee-shift-roster.index', {
                                            page: page,
                                        })
                                    "
                                    class="pagination-box current"
                                >
                                    <span>{{ page }}</span>
                                </InertiaLink>
                                <InertiaLink
                                    :key="`page-${page}`"
                                    v-else
                                    :href="
                                        route('employee-shift-roster.index', {
                                            page: page,
                                        })
                                    "
                                    class="pagination-box"
                                >
                                    <span>{{ page }}</span>
                                </InertiaLink>
                            </template>
                            <template v-if="employees.next_page_url">
                                <InertiaLink
                                    :key="'next'"
                                    :href="employees.next_page_url"
                                    class="pagination-box"
                                >
                                    <span>Next</span>
                                </InertiaLink>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link as InertiaLink, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Multiselect from "vue-multiselect";
import InputError from "../../Components/InputError.vue";

const props = defineProps({
    departments: Array,
    designations: Array,
    rosterTypes: Array,
    grades: Array,
    shifts: Array,
    employees: Array,
});

const form = useForm({
    department_id: null,
    designation_id: null,
    grade_id: null,
});

const roster = useForm({
    shift_id: null,
    working_days: [],
    from_date: null,
    to_date: null,
    employee: [],
});

const selectedDepartment = ref(null);

const onDepartmentSelect = async (selectedDepartment) => {
    form.department_id = selectedDepartment.id;
};

const updateEmployeeSelection = async (val, employee) => {
    employee.selected = val;
};

const selectedDesignation = ref(null);

const onDesignationSelect = async (selectedDesignation) => {
    form.designation_id = selectedDesignation.id;
};

const selectedGrade = ref(null);

const onGradeSelect = async (selectedGrade) => {
    form.grade_id = selectedGrade.id;
};

const selectedShift = ref(null);

const onShiftSelect = async (selectedShift) => {
    roster.shift_id = selectedShift.id;
};
const storeRoster = async () => {
    props.employees.data.map((employee) => {
        if (employee?.selected) {
            roster.employee.push({
                employee_id: employee?.id,
                department_id: employee?.departmentId?.id || null,
                roster_type_id: employee?.rosterTypeId?.id || null,
                rest_day: employee?.holiday?.value || null,
            });
        }
    });
    roster.post(route("employee-shift-roster.store"));
};

const workingDays = ref(null);
const weekDaysOptions = computed(() => {
    return [
        { label: "Monday", value: "Monday" },
        { label: "Tuesday", value: "Tuesday" },
        { label: "Wednesday", value: "Wednesday" },
        { label: "Thursday", value: "Thursday" },
        { label: "Friday", value: "Friday" },
        { label: "Saturday", value: "Saturday" },
        { label: "Sunday", value: "Sunday" },
    ];
});

const onWorkingDaySelect = async (selectedWorkingDay) => {
    roster.working_days.push(selectedWorkingDay.value);
};

const onWorkingDayUnselect = async (selectedWorkingDay) => {
    let index = roster.working_days.indexOf(selectedWorkingDay.value);
    if (index !== -1) {
        roster.working_days.splice(index, 1);
    }
};
roster.working_days = weekDaysOptions.value.map((day) => day.value);
workingDays.value = weekDaysOptions.value;
</script>
<script>
export default {
    props: {
        employees: Array,
    },
    components: {
        AppLayout,
        Head,
        InertiaLink,
    },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
<style scoped>
.pagination-container {
    align-items: center;
}

.pagination-boxes {
    margin-top: 0px;
    display: flex;
    gap: 8px;
}

.pagination-box {
    display: inline-block;
    padding: 10px 16px;
    background-color: #f3f4f6;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    text-decoration: none;
    color: #4a4a4a;
    transition: background-color 0.3s, color 0.3s;
}

.pagination-box:hover {
    background-color: #e5e7eb;
    color: #333;
}

.current {
    background-color: #6d4c41;
    color: white;
}

.pagination-info {
    margin-top: 0px;
    color: #6b7280;
    font-size: 16px;
    padding-left: 4px;
}
</style>
