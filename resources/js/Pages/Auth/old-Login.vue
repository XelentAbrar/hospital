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
    <section class="h-full md:h-screen w-full bg-[#019934] bg-opacity-30">
        <div class="grid sm:grid-cols-1 md:grid-cols-2 gap-4">
            <div class="hidden md:block">
                <div class="h-screen flex justify-center items-center">
                    <img class="h-[80vh] m-auto" src="/icons/doctors.webp" alt="">
                </div>
            </div>
            <div class="flex justify-center items-center h-screen p-4">
                <form class="bg-[#fff] bg-opacity-100 rounded-2xl shadow-2xl p-4 md:p-6 w-full max-w-md md:mx-auto"  @submit.prevent="submit">
                    <div>
                         <img src="@/icons/logo.png" class="mx-auto h-40" alt="">
                    </div>
                     <!-- Email input -->
                     <div class="relative mb-6">
                         <input type="text" id="email" class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full text-gray-400 bg-transparent dark:bg-gray-700 border-0 border-b-2 border-gray-400 appearance-none dark:text-gray-400 dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-gray-400 peer" placeholder=" " v-model="form.email" autocomplete="username" />
                         <label for="email" class="absolute text-gray-400 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-2.5 peer-focus:text-gray-400 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4">Email</label>
                        <InputError class="mt-2" :message="form.errors.email" />
                     </div>

                     <!-- Password input -->
                     <div class="relative mb-6">
                         <input type="password" id="password" class="block rounded-t-lg px-2.5 pb-2.5 pt-5 w-full  text-gray-400 bg-transparent dark:bg-gray-700 border-0 border-b-2 border-gray-400 appearance-none dark:text-gray-400 dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-gray-400 peer" placeholder=" " v-model="form.password" autocomplete="current-password" />
                         <label for="password" class="absolute text-gray-400 dark:text-gray-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-2.5 peer-focus:text-gray-400 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4">Password</label>
                         <InputError class="mt-2" :message="form.errors.password" />
                     </div>

                     <div class="flex flex-col sm:flex-col md:flex-row lg:flex-row justify-between items-center mb-6">
                       <div class="form-group form-check">
                         <input type="checkbox" class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-[#019934] checked:border-[#019934] focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer"
                           id=""/>
                         <label class="form-check-label inline-block text-gray-400" for="exampleCheck2">Remember me
                         </label>
                       </div>
                       <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-sm text-[#019934] hover:text-[#019934] rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                            Forgot your password?
                        </Link>
                     </div>

                     <div class="text-center lg:text-right">
                       <button type="submit" class="inline-block px-6 font-semibold py-2 bg-[#019934] text-white text-sm leading-snug uppercase rounded-md hover:bg-[#019934] focus:bg-[#019934] focus:shadow-lg focus:outline-none focus:ring-0 active:bg-[#019934] active:shadow-lg transition duration-150 ease-in-out" :disabled="form.processing">
                         Login
                       </button>
                     </div>
                     <div class="flex items-center my-4 before:flex-1 before:border-t before:border-gray-300 before:mt-0.5 after:flex-1 after:border-t after:border-gray-300 after:mt-0.5">
                     <p class="text-center font-semibold mx-4 text-[#019934] mb-0">Or</p>
                     </div>
                       <div>
                           <p class="text-center mt-2 pt-1 mb-0 text-gray-400">
                               Don't have an account?
                               <a href="#" class="text-red-500 hover:text-red-500 focus:text-red-500 transition duration-200 ease-in-out font-semibold">Register</a>
                           </p>
                       </div>
                       <div class="flex flex-row items-center justify-center lg:justify-center mt-6">
                         <button type="button"
                           data-mdb-ripple="true"
                           data-mdb-ripple-color="light"
                           class="inline-block p-3 bg-[#019934] text-white font-medium text-xs leading-tight uppercase rounded-full shadow-md hover:bg-[#019934] hover:shadow-lg focus:bg-[#019934] focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out mx-1">
                           <!-- Facebook -->
                           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-4 h-4">
                             <path
                               fill="currentColor"
                               d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"
                             />
                           </svg>
                         </button>

                         <button type="button" data-mdb-ripple="true" data-mdb-ripple-color="light" class="inline-block p-3 bg-[#019934] text-white font-medium text-xs leading-tight uppercase rounded-full shadow-md hover:bg-[#019934] hover:shadow-lg focus:bg-[#019934] focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out mx-1">
                           <!-- Twitter -->
                           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-4 h-4">
                             <path
                               fill="currentColor"
                               d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"
                             />
                           </svg>
                         </button>

                         <button type="button" data-mdb-ripple="true" data-mdb-ripple-color="light" class="inline-block p-3 bg-[#019934] text-white font-medium text-xs leading-tight uppercase rounded-full shadow-md hover:bg-[#019934] hover:shadow-lg focus:bg-[#019934] focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out mx-1">
                           <!-- Linkedin -->
                           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-4 h-4">
                             <path
                               fill="currentColor"
                               d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"
                             />
                           </svg>
                         </button>
                       </div>
                   </form>
            </div>
        </div>
    </section>
</template>
