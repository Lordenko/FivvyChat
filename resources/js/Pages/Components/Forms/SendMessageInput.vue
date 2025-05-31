<script setup>

import {useForm, router, usePage} from "@inertiajs/vue3";

const props = defineProps({
    user_id: {
        type: Number,
        required: true
    },
    chat_id: {
        type: Number,
        required: true
    },

})

const form = useForm({
    message_text: null,
    user_id: props.user_id,
    chat_id: props.chat_id,
})

const submit = () => {
    form.post(route('messages.store'), {
        preserveScroll: true,
        onBefore: visit => {
            visit.data.skipProgress = true
        },
        onSuccess: () => {
            form.reset('message_text')
        }
    })
}


</script>

<template>
    <div class="flex flex-row items-center bg-zinc-800">
        <form @submit.prevent="submit" class="border-t border-zinc-700 w-full h-[60px] flex flex-row items-center p-2">
            <input v-model="form.message_text" class="bg-zinc-900 border-zinc-700 text-purple-300 w-full h-[50px] p-2 rounded-2xl border focus:outline-none focus:ring-0" type="text"
                   placeholder="Enter a message...">

            <button
                :disabled="form.processing || !form.message_text?.trim()"
                class="hover:bg-purple-800 bg-purple-700 text-purple-300 border-zinc-700 w-[50px] h-[50px] rounded-2xl border text-center ml-1 hover:cursor-pointer">➤
            </button>
        </form>
    </div>



</template>
