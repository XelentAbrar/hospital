<template>
    <Head title="Activity Log" />
    <AppLayout title="Activity Log">
        <div class="w-full mx-auto pt-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                    <div
                        class="flex flex-col md:flex-row items-start md:items-center justify-between"
                    >
                        <h1 class="font-semibold text-3xl text-primary">
                            Activity Log
                        </h1>
                        <div class="mt-2 ml-auto sm:mt-0">
                            <input
                                id="name"
                                name="name"
                                type="text"
                                autocomplete="name"
                                class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                                step="0.01"
                                placeholder="Search"
                                :value="filters?.search"
                                @keyup.enter="searchItem($event.target.value)"
                            />
                        </div>
                    </div>
                    <div class="mt-6 flow-root">
                        <div
                            class="ring-1 ring-gray-200 sm:rounded-lg overflow-auto"
                        >
                            <table
                                class="min-w-full border-separate border-spacing-0 divide-y divide-gray-200 overflow-auto"
                            >
                                <thead>
                                    <tr class="divide-x divide-gray-200">
                                        <th
                                            scope="col"
                                            class="bg-primary backdrop-blur whitespace-nowrap backdrop-filter py-3.5 pl-4 pr-3 text-center text-base md:text-lg font-medium text-white sm:pl-6"
                                        >
                                            Created By
                                        </th>
                                        <th
                                            scope="col"
                                            class="bg-primary backdrop-blur backdrop-filter py-3.5 pl-4 pr-3 text-center text-base md:text-lg font-medium text-white sm:pl-6"
                                        >
                                            Voucher Type
                                        </th>
                                        <th
                                            scope="col"
                                            class="bg-primary backdrop-blur backdrop-filter py-3.5 pl-4 pr-3 text-center text-base md:text-lg font-medium text-white sm:pl-6"
                                        >
                                            V#
                                        </th>
                                        <th
                                            scope="col"
                                            class="bg-primary backdrop-blur backdrop-filter py-3.5 pl-4 pr-3 text-center text-base md:text-lg font-medium text-white sm:pl-6"
                                        >
                                            Narration
                                        </th>
                                        <th
                                            scope="col"
                                            class="bg-primary backdrop-blur backdrop-filter py-3.5 pl-4 pr-3 text-center text-base md:text-lg font-medium text-white sm:pl-6"
                                        >
                                            Date
                                        </th>
                                        <th
                                            scope="col"
                                            class="bg-primary backdrop-blur backdrop-filter py-3.5 pl-4 pr-3 text-center text-base md:text-lg font-medium text-white sm:pl-6"
                                        >
                                            Account Descriptions
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr
                                        class="divide-x divide-gray-200"
                                        v-for="(
                                            voucher, index
                                        ) in voucher_log.data"
                                        :key="voucher.voucher_id"
                                        :class="
                                            index % 2 === 0
                                                ? 'bg-gray-50'
                                                : 'bg-white'
                                        "
                                    >
                                        <td
                                            class="whitespace-nowrap relative text-center py-4 pl-4 pr-3 sm:pl-6 text-gray-900 text-sm md:text-base"
                                        >
                                            {{ voucher.created_by.name }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap relative text-center py-4 pl-4 pr-3 sm:pl-6 text-gray-900 text-sm md:text-base"
                                        >
                                            {{
                                                voucher.voucher_type
                                                    .voucher_name
                                            }}
                                        </td>
                                        <td
                                            class="whitespace-normal text-center relative py-4 pl-4 pr-3 sm:pl-6 text-gray-900 text-sm md:text-base"
                                        >
                                            {{ voucher.voucher_no }}
                                        </td>
                                        <td
                                            class="whitespace-normal text-center relative py-4 pl-4 pr-3 sm:pl-6 text-gray-900 text-sm md:text-base"
                                        >
                                            {{ voucher.narration }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap text-center relative py-4 pl-4 pr-3 sm:pl-6 text-gray-900 text-sm md:text-base"
                                        >
                                            {{
                                                new Date(voucher?.voucher_date)
                                                    .toLocaleDateString("en-GB")
                                                    .replace(/\//g, "-")
                                            }}
                                        </td>
                                        <td
                                            class="whitespace-normal text-center relative py-4 pl-4 pr-3 sm:pl-6 text-gray-900 text-sm md:text-base"
                                        >
                                            {{ voucher.account_descriptions }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div
                            class="pagination-container py-4 flex justify-between px-4"
                        >
                            <div class="pagination-info flex">
                                Page
                                <span
                                    class="font-semibold px-2 text-gray-900 report"
                                    >{{ voucher_log.current_page }}</span
                                >
                                of
                                <span
                                    class="font-semibold px-2 text-gray-900 report"
                                    >{{ voucher_log.last_page }}</span
                                >
                                <div>
                                    <p class="pagination-info">
                                        Showing
                                        <span
                                            class="font-semibold px-1 text-gray-900 report"
                                            >{{ voucher_log.from }}</span
                                        >
                                        to
                                        <span
                                            class="font-semibold px-1 text-gray-900 report"
                                            >{{ voucher_log.to }}</span
                                        >
                                        of
                                        <span
                                            class="font-semibold px-1 text-gray-900 report"
                                            >{{ voucher_log.total }}</span
                                        >
                                        entries
                                    </p>
                                </div>
                            </div>
                            <div class="pagination-links">
                                <template v-if="voucher_log.links">
                                    <template
                                        v-for="(
                                            link, index
                                        ) in voucher_log.links"
                                        :key="index"
                                    >
                                        <InertiaLink
                                            v-if="link.url"
                                            :href="link.url"
                                            v-html="link.label"
                                            :class="[
                                                'pagination-box',
                                                {
                                                    'pagination-box-current':
                                                        link.active,
                                                },
                                            ]"
                                        >
                                        </InertiaLink>
                                    </template>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, Link as InertiaLink, router } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import Swal from "sweetalert2";
const searchItem = async (val) => {
    try {
        router.visit(
            route("voucher-activity-log", {
                search: val,
            }),
            {
                preserveState: true,
                preserveScroll: true,
            }
        );
    } catch (error) {
        console.error("Error fetching cities:", error);
    }
};
</script>
<script>
export default {
    props: {
        voucher_log: Array,
    },
    components: {
        AppLayout,
        Head,
        InertiaLink,
    },
};
</script>
<style scoped>
.pagination-box {
    padding: 10px 16px;
    border: 1px solid #ccc;
    border-radius: 0.25rem;
    margin: 0 0.25rem;
    text-decoration: none;
}

.pagination-box-current {
    background-color: #6D4C41;
    color: #fff;
}

.pagination-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1rem;
}

.pagination-info {
    display: flex;
    align-items: center;
}

.pagination-links {
    display: flex;
}
</style>
