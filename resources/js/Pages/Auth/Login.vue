<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>
<template>
    <Head title="Log in" />
    <div class="flex items-center justify-center min-h-screen bg-gray-800 bg-cover bg-no-repeat bg-center" style="background-image: url(images/bg-hospital.webp);">
        <div class="absolute bg-gradient-to-b from-green-500 to-blue-800 opacity-75 inset-0 z-0"></div>
        <div class="grid gap-8 relative z-10">
            <div class="relative sm:max-w-7xl w-full">
                <!-- <div class="card bg-red-600 shadow-lg  w-full h-full rounded-3xl absolute  transform -rotate-6"></div>
                <div class="card bg-green-600 shadow-lg  w-full h-full rounded-3xl absolute  transform rotate-6"></div> -->
                <div class="relative border-[20px] border-transparent rounded-[20px] bg-white shadow-lg md:p-6 m-2 p-2">
                    <img class="mx-auto h-24 w-auto" src="@/icons/logo.png" alt="Chiniot">
                    <h2 class="text-4xl text-center font-extrabold text-black">Chiniot General Hospital</h2>
                    <form @submit.prevent="submit" class="space-y-4 mt-6">
                        <div>
                            <label for="email" class="mb-2 text-lg font-semibold">Email</label>
                            <input v-model="form.email" id="email" class="border p-3 shadow-md placeholder:text-base border-gray-300 rounded-lg w-full" type="email" autofocus placeholder="Enter Email Address" required/>
                        </div>
                        <div>
                            <label for="password" class="mb-2 text-lg font-semibold">Password</label>
                            <input id="password" class="border p-3 shadow-md placeholder:text-base border-gray-300 rounded-lg w-full" type="password" placeholder="Password" v-model="form.password" required/>
                        </div>
                        <button class="bg-gradient-to-r from-green-500 to-gray-500 shadow-lg mt-6 p-2 text-white text-lg rounded-lg w-full hover:scale-105 hover:from-gray-500 hover:to-green-500 transition duration-300 ease-in-out" type="submit">
                            LOG IN
                        </button>
                    </form>
                    <footer class="text-center py-2">
                        <p class="text-blue-600 text-sm">Under Supervision Mofad-e-Amma Chiniot Sheikh Association </p>
                        <span class="text-gray-600">Built with </span>
                        <span class="text-red-500">&hearts;</span>
                        <span class="text-gray-600"> by </span>
                        <p class="text-md my-6 group relative w-max inline-block">
                            <a href="https://xelent.pk" target="_blank" class="px-1 relative z-10 group-hover:text-white">Xelent Solutions</a>
                            <span class="absolute left-0 bottom-0 w-full h-0.5 transition-all bg-blue-600 z-0 group-hover:h-full "></span>
                            </p>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</template>
