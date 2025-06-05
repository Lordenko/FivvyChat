<script setup>
import {Link, router} from '@inertiajs/vue3'
import axios from 'axios'

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
    user: {
        type: Object,
        required: true
    },
    current_user: {
        type: Object,
        required: true
    },
    is_online: {
        type: Boolean,
        default: false
    }
})

const createChat = async () => {
    try {
        const res = await axios.post(route('chats.store'), {
            user_ids: props.data.user_ids,
            type: props.data.type
        })
        const chatId = res.data.chat_id
        router.visit(route('chat.show', chatId))
    } catch (err) {
        console.log(err)
    }
}

</script>

<template>
    <button
        v-if="user.id !== current_user.id"
        @click="createChat()"
        class="flex flex-row bg-zinc-800 text-white p-3 w-full text-left hover:bg-zinc-700 hover:cursor-pointer rounded-lg"
    >
        <div class="relative w-[60px] h-[60px]">
            <img :src="`/storage/${user.avatar_path}`" alt="User Avatar" class="w-full h-full rounded-full object-cover" />

            <span
                v-if="is_online"
                class="absolute bottom-0 right-0 w-[14px] h-[14px] bg-green-500 border-[2px] border-zinc-800 rounded-full"
            ></span>
        </div>

        <div class="ml-3 w-0 flex-1">
            <p class="text-[20px] text-white mb-1">{{ user.name }}</p>
            <p class="text-[15px] text-gray-300 truncate w-full block">
                Натисни, щоб створити чат
            </p>
        </div>
    </button>
</template>
