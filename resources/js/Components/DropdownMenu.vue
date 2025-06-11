<template>
  <Popover class="relative">
    <PopoverButton class="inline-flex items-center px-1 py-[24px] text-lg font-semibold leading-5 text-white hover:text-gray-100 focus:outline-none focus:text-gray-100 transition duration-150 ease-in-out">
      <span>{{ title }}</span>
      <ChevronDownIcon class="h-5 w-5" aria-hidden="true" />
    </PopoverButton>

    <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 translate-y-1" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 translate-y-1">
      <PopoverPanel class="absolute left-1/2 top-[70px] z-50 flex w-screen max-w-min -translate-x-1/2 px-4">
        <div class="w-56 shrink rounded-xl bg-white p-4 text-lg font-semibold leading-6 text-gray-900 shadow-lg ring-1 ring-gray-200">
          <Link v-for="item in menuItems" :key="item.name" :href="item.href" class="block p-2 hover:text-accent">{{ item.name }}</Link>
        </div>
      </PopoverPanel>
    </transition>
  </Popover>
</template>

<script setup>
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import { ChevronDownIcon } from '@heroicons/vue/20/solid'
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  menuItems: Array,
  title: String,
});
</script>
