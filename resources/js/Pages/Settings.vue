<template>
  <Head title="Settings" />

  <AppLayout title="Settings">
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-md rounded-xl">
          <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <h1 class="font-semibold text-3xl text-primary">Settings</h1>
            <!-- </div>
            </div>
            <div> -->
            <!-- <div class="-mx-4 -my-2 sm:-mx-6 lg:-mx-8">
      <div class="inline-block min-w-full py-2 align-middle">
        <div class="w-full max-w-5xl px-10">
    <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" @submit.prevent="saveSettings">
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="app_id">
          App ID
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="form.app_id" id="app_id" type="text" placeholder="App ID">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="app_id">
          Client ID
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="form.client_id" id="app_id" type="text" placeholder="Client ID">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="app_id">
          Client Secret
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="form.client_secret" id="app_id" type="text" placeholder="Client Secret">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="app_id">
          Signing Secret
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="form.signing_secret" id="app_id" type="text" placeholder="Signing Secret">
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="app_id">
          Webhook url
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" v-model="form.webhook_url" id="app_id" type="text" placeholder="Webhook url">
      </div>


      <div class="flex items-center justify-between">
        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
          Save
        </button>
        <p v-if="$page.props.flash && $page.props.flash.message" class="text-green-500">
            {{ $page.props.flash.message }}
        </p>
      </div>
    </form>
  </div>
      </div>
    </div> -->
            <div class="mx-auto max-w-7xl flex gap-4">
              <aside
                class="flex overflow-x-auto border-b border-gray-900/5 py-6 lg:block lg:w-64 lg:flex-none lg:border-0"
              >
                <nav class="flex-none px-4 sm:px-6 lg:px-0">
                  <ul
                    role="list"
                    class="flex gap-x-3 gap-y-2 whitespace-nowrap lg:flex-col"
                  >
                    <li v-for="item in settingNavigation" :key="item.name">
                      <a
                        :href="item.href"
                        :class="[
                          item.current
                            ? 'bg-primary text-white'
                            : 'text-primary hover:text-white hover:bg-primary',
                          'group flex gap-x-2 rounded border border-primary px-4 py-2 text-base md:text-lg',
                        ]"
                        @click.prevent="item.onClick"
                      >
                        <component
                          :is="item.icon"
                          :class="[
                            item.current
                              ? 'text-white'
                              : 'text-primary group-hover:text-white',
                            'h-6 w-6 shrink-0',
                          ]"
                          aria-hidden="true"
                        />
                        {{ item.name }}
                      </a>
                    </li>
                  </ul>
                </nav>
              </aside>
              <main class="px-4 py-6 sm:px-6 lg:flex-auto">
                <form
                  class="bg-white shadow-md border border-gray-100 rounded p-4 mb-4"
                  @submit.prevent="saveSettings"
                >
                  <div
                    v-show="activeSettingsCategory === 'General'"
                    id="general-settings"
                    class="mx-auto max-w-2xl space-y-16 sm:space-y-20 lg:mx-0 lg:max-w-none"
                  >
                    <div>
                      <h3 class="text-2xl font-semibold text-gray-900">
                        General Settings
                      </h3>

                      <dl
                        class="mt-4 space-y-6 border-t border-gray-200 text-sm leading-6"
                      >
                        <div class="grid grid-cols-2 gap-4 mt-6">
                          <div>
                            <label
                              for="date-format"
                              class="text-base md:text-lg font-medium text-gray-900"
                              >Date Format</label
                            >
                            <multiselect
                              class="mt-2"
                              v-model="selectedDateFormat"
                              :options="DateFormatOptions"
                              :custom-label="DateFormatCustomLabel"
                              @select="onDateFormatSelect"
                              label="type"
                              track-by="type"
                              placeholder="Select Date Format"
                            ></multiselect>
                          </div>
                          <div>
                            <label
                              for="time-format"
                              class="text-base md:text-lg font-medium text-gray-900"
                              >Time Format</label
                            >
                            <multiselect
                              class="mt-2"
                              v-model="selectedTimeFormat"
                              :options="TimeFormatOptions"
                              :custom-label="TimeFormatCustomLabel"
                              @select="onTimeFormatSelect"
                              label="type"
                              track-by="type"
                              placeholder="Select Time Format"
                            ></multiselect>
                          </div>
                          <div>
                            <label
                              for="currency-symbol"
                              class="text-base md:text-lg font-medium text-gray-900"
                              >Currency Symbol</label
                            >
                            <input
                              class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="form.currency_symbol"
                              id="currency-symbol"
                              type="text"
                              placeholder="Currency Symbol"
                            />
                          </div>
                          <div>
                            <label
                              for="currency-code"
                              class="text-base md:text-lg font-medium text-gray-900"
                              >Currency Code</label
                            >
                            <input
                              class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="form.currency_code"
                              id="currency-code"
                              type="text"
                              placeholder="Currency Code"
                            />
                          </div>
                          <div>
                            <label
                              for="currency-code"
                              class="text-base md:text-lg font-medium text-gray-900"
                              >Currency Format</label
                            >
                            <multiselect
                              class="mt-2"
                              v-model="selectedCurrencyFormat"
                              :options="CurrencyFormatOptions"
                              :custom-label="CurrencyFormatCustomLabel"
                              @select="onCurrencyFormatSelect"
                              label="type"
                              track-by="type"
                              placeholder="Select Currency Format"
                            ></multiselect>
                          </div>
                          <div>
                            <label
                              for="currency-code"
                              class="text-base md:text-lg font-medium text-gray-900"
                            >
                              Max File size for upload</label
                            >
                            <div class="relative mt-2 flex items-center">
                              <input
                                type="text"
                                name="max_file_size_upload"
                                id="max_file_size_upload"
                                class="block w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                                v-model="form.max_file_size_upload"
                                placeholder="10"
                              />
                              <div
                                class="absolute inset-y-0 right-0 flex py-1.5 pr-1.5"
                              >
                                <kbd
                                  class="inline-flex items-center rounded border border-gray-200 px-1 font-sans text-xs text-gray-400"
                                  >MB</kbd
                                >
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="py-3">
                          <h2
                            class="text-base font-semibold leading-7 text-gray-900"
                          >
                            Google MAP Settings
                          </h2>
                          <p class="mt-1 text-sm leading-6 text-gray-900">
                            Leave blank to remove Google map key
                          </p>

                          <div class="mt-2">
                            <label for="google_map_key" class="sr-only"
                              >Google map key</label
                            >
                            <input
                              class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="form.google_map_key"
                              id="google_map_key"
                              type="text"
                              placeholder="e.g. AIzaSyDSl2bG7XXXXXXXXXXXXXXXXXX"
                            />
                            <p
                              class="mt-2 text-sm text-gray-900"
                              id="email-description"
                            >
                              Visit
                              <a
                                class="text-blue-500 hover:underline"
                                href="https://console.cloud.google.com/project/_/google/maps-apis/overview"
                                >Google Cloud Console</a
                              >
                              to get the keys
                            </p>
                          </div>
                        </div>
                      </dl>
                    </div>
                  </div>

                  <div
                    v-show="activeSettingsCategory === 'Company'"
                    id="company-settings"
                    class="mx-auto max-w-2xl space-y-16 sm:space-y-20 lg:mx-0 lg:max-w-none"
                  >
                    <div>
                      <h3 class="text-2xl font-semibold text-gray-900">
                        Company Settings
                      </h3>
                      <p class="mt-1 text-sm leading-6 text-gray-900">
                        This information will be displayed publicly so be
                        careful what you share.
                      </p>
                      <div
                        class="mt-4 space-y-6 border-t border-gray-200 text-sm leading-6"
                      >
                        <div class="grid grid-cols-2 gap-4 mt-6">
                          <div>
                            <label
                              class="block text-sm md:text-base font-medium text-gray-900"
                            >
                              Company Name
                            </label>
                            <input
                              class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="form.company_name"
                              id="company_name"
                              type="text"
                              placeholder="Company Name"
                            />
                          </div>
                          <div>
                            <label
                              class="block text-sm md:text-base font-medium text-gray-900"
                            >
                              Company Email
                            </label>
                            <input
                              class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="form.company_email"
                              id="company_email"
                              type="text"
                              placeholder="Company Email"
                            />
                          </div>
                          <div>
                            <label
                              class="block text-sm md:text-base font-medium text-gray-900"
                            >
                              Company Phone
                            </label>
                            <input
                              class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="form.company_phone"
                              id="company_phone"
                              type="text"
                              placeholder="Company Phone"
                            />
                          </div>
                          <div>
                            <label
                              class="block text-sm md:text-base font-medium text-gray-900"
                            >
                              Company Website
                            </label>
                            <input
                              class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="form.company_website"
                              id="company_website"
                              type="text"
                              placeholder="Company Website"
                            />
                          </div>
                          <div>
                            <label
                              class="block text-sm md:text-base font-medium text-gray-900"
                            >
                              Company Address
                            </label>
                            <input
                              class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="form.company_address"
                              id="company_address"
                              type="text"
                              placeholder="Company Address"
                            />
                          </div>
                          <div>
                            <label
                              class="block text-sm md:text-base font-medium text-gray-900"
                            >
                              Company City
                            </label>
                            <input
                              class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="form.company_city"
                              id="company_city"
                              type="text"
                              placeholder="Company City"
                            />
                          </div>
                          <div>
                            <label
                              class="block text-sm md:text-base font-medium text-gray-900"
                            >
                              Company State
                            </label>
                            <input
                              class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="form.company_state"
                              id="company_state"
                              type="text"
                              placeholder="Company State"
                            />
                          </div>
                          <div>
                            <label
                              class="block text-sm md:text-base font-medium text-gray-900"
                            >
                              Company Country
                            </label>
                            <input
                              class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="form.company_country"
                              id="company_country"
                              type="text"
                              placeholder="Company Country"
                            />
                          </div>
                          <div>
                            <label
                              class="block text-sm md:text-base font-medium text-gray-900"
                            >
                              Tax No.
                            </label>
                            <input
                              class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="form.company_tax_no"
                              id="company_tax_no"
                              type="text"
                              placeholder="Tax No."
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div
                    v-show="activeSettingsCategory === 'Slack'"
                    id="slack-settings"
                    class="mx-auto max-w-2xl space-y-16 sm:space-y-20 lg:mx-0 lg:max-w-none"
                  >
                    <div>
                      <h3 class="text-2xl font-semibold text-gray-900">
                        Slack
                      </h3>
                      <p class="mt-1 text-sm leading-6 text-gray-900">
                        This information will be displayed publicly so be
                        careful what you share.
                      </p>

                      <div
                        class="mt-4 space-y-6 border-t border-gray-200 text-sm leading-6"
                      >
                        <div class="pt-6 sm:w-1/2">
                          <label
                            class="block text-sm md:text-base font-medium text-gray-900"
                          >
                            Slack Signature Secret
                          </label>
                          <input
                            class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                            v-model="form.signing_secret"
                            id="app_id"
                            type="text"
                            placeholder="Signing Secret"
                          />
                        </div>
                      </div>
                    </div>
                  </div>

                  <div
                    v-show="activeSettingsCategory === 'Mail'"
                    id="mail-settings"
                    class="mx-auto max-w-2xl space-y-16 sm:space-y-20 lg:mx-0 lg:max-w-none"
                  >
                    <div>
                      <h3 class="text-2xl font-semibold text-gray-900">Mail</h3>
                      <p class="mt-1 text-sm leading-6 text-gray-900">
                        This information will be displayed publicly so be
                        careful what you share.
                      </p>

                      <div
                        class="mt-4 space-y-6 border-t border-gray-200 text-sm leading-6"
                      >
                        <div class="pt-6 grid grid-cols-2 gap-4">
                          <div>
                            <label
                              class="block text-sm md:text-base font-medium text-gray-900"
                            >
                              Mail From Name
                            </label>
                            <input
                              class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="form.mail_from_name"
                              id="mail_from_name"
                              type="text"
                              placeholder="Mail From Name"
                            />
                          </div>
                          <div>
                            <label
                              class="block text-sm md:text-base font-medium text-gray-900"
                            >
                              Mail From Address
                            </label>
                            <input
                              class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="form.mail_from_address"
                              id="mail_from_address"
                              type="text"
                              placeholder="Mail From Address"
                            />
                          </div>
                          <div>
                            <label
                              class="block text-sm md:text-base font-medium text-gray-900"
                            >
                              Mail Encryption
                            </label>
                            <input
                              class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="form.mail_encryption"
                              id="mail_encryption"
                              type="text"
                              placeholder="Mail Encryption"
                            />
                          </div>
                          <div>
                            <label
                              class="block text-sm md:text-base font-medium text-gray-900"
                            >
                              Mail Mailer
                            </label>
                            <input
                              class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="form.mail_mailer"
                              id="mail_mailer"
                              type="text"
                              placeholder="Mail Mailer"
                            />
                          </div>
                          <div>
                            <label
                              class="block text-sm md:text-base font-medium text-gray-900"
                            >
                              Mail Host
                            </label>
                            <input
                              class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="form.mail_host"
                              id="mail_host"
                              type="text"
                              placeholder="Mail Host"
                            />
                          </div>
                          <div>
                            <label
                              class="block text-sm md:text-base font-medium text-gray-900"
                            >
                              Mail Port
                            </label>
                            <input
                              class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="form.mail_port"
                              id="mail_port"
                              type="text"
                              placeholder="Mail Port"
                            />
                          </div>
                          <div>
                            <label
                              class="block text-sm md:text-base font-medium text-gray-900"
                            >
                              Mail Username
                            </label>
                            <input
                              class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="form.mail_username"
                              id="mail_username"
                              type="text"
                              placeholder="Mail Username"
                            />
                          </div>
                          <div>
                            <label
                              class="block text-sm md:text-base font-medium text-gray-900"
                            >
                              Mail Password
                            </label>
                            <input
                              class="block mt-2 w-full rounded border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-200 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                              v-model="form.mail_password"
                              id="mail_password"
                              type="text"
                              placeholder="Mail Password"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="flex items-center justify-end mt-6 py-4">
                    <div>
                      <button
                        class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                        type="submit"
                      >
                        Save
                      </button>
                      <p
                        v-if="$page.props.flash && $page.props.flash.message"
                        class="text-green-500"
                      >
                        {{ $page.props.flash.message }}
                      </p>
                    </div>
                  </div>
                </form>
              </main>
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
import { Inertia } from "@inertiajs/inertia";
import { ref, computed } from "vue";
import Multiselect from "vue-multiselect";
import moment from "moment-timezone";
import { Bars3Icon } from "@heroicons/vue/20/solid";
import {
  BuildingOfficeIcon,
  EnvelopeIcon,
  FingerPrintIcon,
  Cog6ToothIcon,
  ServerStackIcon,
} from "@heroicons/vue/24/outline";

let activeSettingsCategory = ref("General");

const settingNavigation = computed(() => [
  {
    name: "General Settings",
    href: "#",
    icon: Cog6ToothIcon,
    current: activeSettingsCategory.value === "General",
    onClick: () => (activeSettingsCategory.value = "General"),
  },
  {
    name: "Company Settings",
    href: "#",
    icon: BuildingOfficeIcon,
    current: activeSettingsCategory.value === "Company",
    onClick: () => (activeSettingsCategory.value = "Company"),
  },
  {
    name: "Slack Settings",
    href: "#",
    icon: ServerStackIcon,
    current: activeSettingsCategory.value === "Slack",
    onClick: () => (activeSettingsCategory.value = "Slack"),
  },
  {
    name: "Mail Settings",
    href: "#",
    icon: EnvelopeIcon,
    current: activeSettingsCategory.value === "Mail",
    onClick: () => (activeSettingsCategory.value = "Mail"),
  },
]);

const props = defineProps({
  settings: Object,
});

let form = useForm({
  date_format: props.settings.date_format?.value || "",
  default_timezone: props.settings.default_timezone?.value || "",
  currency_symbol: props.settings.currency_symbol?.value || "",
  currency_code: props.settings.currency_code?.value || "",
  currency_format: props.settings.currency_format?.value || "",
  max_file_size_upload: props.settings.max_file_size_upload?.value || "",
  google_map_key: props.settings.google_map_key?.value || "",
  company_name: props.settings.company_name?.value || "",
  company_email: props.settings.company_email?.value || "",
  company_phone: props.settings.company_phone?.value || "",
  company_website: props.settings.company_website?.value || "",
  company_address: props.settings.company_address?.value || "",
  company_city: props.settings.company_city?.value || "",
  company_state: props.settings.company_state?.value || "",
  company_country: props.settings.company_country?.value || "",
  company_tax_no: props.settings.company_tax_no?.value || "",
  signing_secret: props.settings.signing_secret?.value || "",
  mail_from_name: props.settings.mail_from_name?.value || "",
  mail_from_address: props.settings.mail_from_address?.value || "",
  mail_encryption: props.settings.mail_encryption?.value || "",
  mail_mailer: props.settings.mail_mailer?.value || "",
  mail_host: props.settings.mail_host?.value || "",
  mail_port: props.settings.mail_port?.value || "",
  mail_username: props.settings.mail_username?.value || "",
  mail_password: props.settings.mail_password?.value || "",
  // webhook_url: props.settings.webhook_url?.value || ''
});

// Date Format
const selectedDateFormat = ref(null);

const DateFormatOptions = computed(() => {
  const today = moment(); // gets current date
  const dateFormat = [
    { type: "d-m-Y", example: today.format("DD-MM-YYYY") },
    { type: "m-d-Y", example: today.format("MM-DD-YYYY") },
    { type: "Y-m-d", example: today.format("YYYY-MM-DD") },
    { type: "d.m.Y", example: today.format("DD.MM.YYYY") },
    { type: "m.d.Y", example: today.format("MM.DD.YYYY") },
    { type: "Y.m.d", example: today.format("YYYY.MM.DD") },
    { type: "d/m/Y", example: today.format("DD/MM/YYYY") },
    { type: "m/d/Y", example: today.format("MM/DD/YYYY") },
    { type: "Y/m/d", example: today.format("YYYY/MM/DD") },
    { type: "d/M/Y", example: today.format("DD/MMM/YYYY") },
    { type: "d-M-Y", example: today.format("DD-MMM-YYYY") },
    { type: "d M Y", example: today.format("DD MMM YYYY") },
    { type: "d F, Y", example: today.format("DD MMMM, YYYY") },
    { type: "D/M/Y", example: today.format("ddd/MM/YYYY") },
    { type: "D-M-Y", example: today.format("ddd-MMM-YYYY") },
    { type: "D M Y", example: today.format("ddd MMM YYYY") },
    { type: "d D M Y", example: today.format("DD ddd MMM YYYY") },
    { type: "D d M Y", example: today.format("ddd DD MMM YYYY") },
    { type: "Do M Y", example: today.format("Do MMM YYYY") },
  ];
  return dateFormat.map((format) => ({
    type: `${format.type} (${format.example})`,
    formatString: format.type.replace(/[dmy]/g, (match) => match.toUpperCase()),
  }));
});

const DateFormatCustomLabel = (option) => option.type;

const onDateFormatSelect = async (selectedDateFormat) => {
  form.date_format = selectedDateFormat.formatString;
};

/// Time Format
const selectedTimeFormat = ref(null);

const TimeFormatOptions = computed(() => {
  const timeZones = moment.tz.names();
  const timeFormat = timeZones.map((tz) => ({ type: tz }));
  return timeFormat;
});

const TimeFormatCustomLabel = (option) => option.type;

const onTimeFormatSelect = async (selectedTimeFormat) => {
  form.default_timezone = selectedTimeFormat.type;
};

// Currency Format
const selectedCurrencyFormat = ref(null);
const CurrencyFormatOptions = computed(() => {
  const currencyFormat = [{ type: "Prefix" }, { type: "Suffix" }];
  return currencyFormat;
});
const CurrencyFormatCustomLabel = (option) => option.type;
const onCurrencyFormatSelect = async (selectedCurrencyFormat) => {
  form.currency_format = selectedCurrencyFormat.type;
};

const saveSettings = () => {
  form.post("/settings");
};
</script>
<script>
export default {
  components: {
    AppLayout,
    Head,
    InertiaLink,
  },
};
</script>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
