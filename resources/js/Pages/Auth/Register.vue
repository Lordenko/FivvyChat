<script setup>

import { useForm } from '@inertiajs/vue3'
import TextInput from '../Components/TextInput.vue';
import CheckBox from '../Components/CheckBox.vue';
import AuthLayout from '../../Layouts/AuthLayout.vue';

defineOptions({
    layout: AuthLayout
})

const form = useForm({
    name: null,
    email: null,
    password: null,
    password_confirmation: null,
    agree: null
})

const submit = () => {
    form.post(route('register'), {
        onError: () => form.reset('password', 'password_confirmation', 'agree'),
    });
}
</script>

<template>

    <Head title="Register" />

    <h2 class="text-2xl font-bold text-purple-300 mb-6 text-center">Sign Up</h2>

    <form @submit.prevent="submit" class='space-y-4'>
        <TextInput name="Name" v-model="form.name" :message="form.errors.name" />

        <TextInput name="Email" v-model="form.email" :message="form.errors.email" />

        <TextInput name="Password" type="password" v-model="form.password" :message="form.errors.password" />

        <TextInput name="Repeat password" type="password" v-model="form.password_confirmation"
            :message="form.errors.password_confirmation" />

        <div class="flex items-center justify-between my-5">
            <CheckBox name="Agree to the community rules?" v-model="form.agree" />
            <a href="#" class="text-sm text-purple-400 hover:text-purple-300">Community rules</a>
        </div>
        <p v-if="form.errors.agree" class="text-sm text-red-500 mt-1">{{ form.errors.agree }}</p>

        <button
            :disabled="form.processing"
            :class="{ '!bg-zinc-600': form.processing }"
            class="w-full bg-purple-800 hover:bg-purple-900 text-white font-medium py-2.5 rounded-lg transition-colors"
        >
            Sign Up
        </button>
    </form>


    <div class="mt-6 text-center text-sm text-zinc-400">
        Have account?
        <Link :href="route('login')" class="text-purple-400 hover:text-purple-300 font-medium">Sign in</Link>
    </div>
</template>
