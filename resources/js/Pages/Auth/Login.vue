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
    password: null,
    remember: null
})

const submit = () => {
    form.post(route('login'), {
        onError: () => form.reset('password'),
    });
}
</script>

<template>

    <Head title="Login" />

    <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Sign In</h2>

    <form @submit.prevent="submit" class='space-y-4'>

        <TextInput name="Name" v-model="form.name" :message="form.errors.name" />

        <TextInput name="Password" type="password" v-model="form.password" :message="form.errors.password" />

        <div class="flex items-center justify-between">
            <CheckBox name="Remember me?" v-model="form.remember" />
            <a href="#" class="text-sm text-indigo-600 hover:text-indigo-500">Forgot password?</a>
        </div>

        <button :disabled="form.processing" :class="{ '!bg-gray-400': form.processing }"
            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition-colors">
            Sign In
        </button>
    </form>

    <div class="mt-6 text-center text-sm text-gray-600">
        Don't have an account?
        <Link :href="route('register')" class="text-indigo-600 hover:text-indigo-500 font-medium">Sign up</Link>
    </div>
</template>
