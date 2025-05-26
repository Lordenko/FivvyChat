<script setup>

import {useForm} from "@inertiajs/vue3";
import {router, usePage} from '@inertiajs/vue3'

const props = defineProps({
    user_id: {
        type: Number,
        required: true
    },
    chat_id: {
        type: Number,
        required: true
    }
})

const form = useForm({
    message_text: null,
    user_id: props.user_id,
    chat_id: props.chat_id,
})

const submit = () => {
    form.post(route('messages.store'), {
        preserveScroll: true,
        onSuccess: () => form.reset('message_text')
    });
}

</script>

<template>
    <div class="bg-sky-500 flex flex-row items-center ">
        <form @submit.prevent="submit" class="w-full h-[50px] flex flex-row px-1">
            <input v-model="form.message_text" class="bg-sky-900 w-full h-[50px] p-2 rounded-2xl border" type="text"
                   placeholder="Enter a message...">

            <button
                class="bg-sky-900 w-[50px] h-[50px] rounded-2xl border text-center ml-1 hover:cursor-pointer">➤
            </button>
        </form>
    </div>
</template>
