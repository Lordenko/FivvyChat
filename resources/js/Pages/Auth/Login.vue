<script setup>
import { useForm } from '@inertiajs/vue3'
import TextInput from '../Components/TextInput.vue';
import CheckBox from './Components/CheckBox.vue';
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

    <h2 class="text-2xl font-bold text-purple-300 mb-6 text-center">Sign In</h2>

    <form @submit.prevent="submit" class='space-y-4'>

        <TextInput name="Name" v-model="form.name" :message="form.errors.name" />

        <TextInput name="Password" type="password" v-model="form.password" :message="form.errors.password" />

        <div class="flex items-center justify-between my-5">
            <CheckBox name="Remember me?" v-model="form.remember" />
            <a href="#" class="text-sm text-purple-400 hover:text-purple-300">Forgot password?</a>
        </div>


        <button
            :disabled="form.processing"
            :class="{ '!bg-zinc-600': form.processing }"
            class="w-full bg-purple-800 hover:bg-purple-900 text-white font-medium py-2.5 rounded-lg transition-colors"
        >
            Sign In
        </button>
    </form>


    <div class="mt-6 text-center text-sm text-zinc-400">
        Don't have an account?
        <Link :href="route('register')" class="text-purple-400 hover:text-purple-300 font-medium">Sign up</Link>
    </div>
</template>
