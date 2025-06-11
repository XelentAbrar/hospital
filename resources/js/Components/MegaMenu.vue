<template>
  <Popover class="isolate z-50">
    <PopoverButton class="inline-flex items-center px-0.5 py-[24px] text-lg font-semibold leading-5 text-white hover:text-gray-100 focus:outline-none focus:text-gray-100 transition duration-150 ease-in-out">
        {{ title }}
        <ChevronDownIcon class="h-5 w-5" aria-hidden="true" />
    </PopoverButton>

    <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 -translate-y-1" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-1">
      <PopoverPanel class="absolute rounded-xl w-11/12 left-12 top-[70px] -z-10 bg-white shadow-lg ring-1 ring-gray-200">
        <div class="mx-auto grid max-w-7xl grid-cols-1 gap-x-8 gap-y-10 px-6 py-10 lg:grid-cols-1 lg:px-8">
          <div class="grid grid-cols-4 gap-x-6 sm:gap-x-8">
            <div v-for="(group, index) in menuItems" :key="index">
              <h3 class="text-lg font-semibold leading-6 text-accent">{{ group.title }}</h3>
              <div class="mt-6 flow-root">
                <div class="-my-2">
                    <Link v-for="item in group.items" :key="item.name" :href="item.href" class="flex gap-x-4 py-2 text-lg font-semibold leading-6 text-gray-900 hover:text-accent">
                    <component :is="iconComponents[item.icon]" class="h-6 w-6 flex-none text-gray-600" aria-hidden="true" />
                    {{ item.name }}
                     </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </PopoverPanel>
    </transition>
  </Popover>
</template>

<script setup>
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'
import { Link } from '@inertiajs/vue3';
import OneIcon from '@/icons/OneIcon.vue';
import TwoIcon from '@/icons/TwoIcon.vue';
import ThreeIcon from '@/icons/ThreeIcon.vue';
import FourIcon from '@/icons/FourIcon.vue';
import FiveIcon from '@/icons/FiveIcon.vue';
import CPIcon from '@/icons/CPIcon.vue';
import CRIcon from '@/icons/CRIcon.vue';

import {
    GlobeAltIcon,
    GlobeAmericasIcon,
    GlobeAsiaAustraliaIcon,
    Bars2Icon,
    UserGroupIcon,
    BanknotesIcon,
    CreditCardIcon,
    ArrowPathRoundedSquareIcon,
    ListBulletIcon,
    BuildingStorefrontIcon,
    UsersIcon,
    PresentationChartLineIcon,
    BookOpenIcon,
    DocumentTextIcon,
    DocumentPlusIcon,
    FolderPlusIcon
} from '@heroicons/vue/24/outline';

const props = defineProps({
  menuItems: Array,
  title: String,
});

const iconComponents = {
    GlobeAltIcon,
    GlobeAmericasIcon,
    GlobeAsiaAustraliaIcon,
    Bars2Icon,
    UserGroupIcon,
    BanknotesIcon,
    CreditCardIcon,
    ArrowPathRoundedSquareIcon,
    ListBulletIcon,
    BuildingStorefrontIcon,
    UsersIcon,
    PresentationChartLineIcon,
    BookOpenIcon,
    DocumentTextIcon,
    DocumentPlusIcon,
    OneIcon,
    TwoIcon,
    ThreeIcon,
    FourIcon,
    FiveIcon,
    CPIcon,
    CRIcon,
    FolderPlusIcon
};
</script>
