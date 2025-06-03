<script setup>
import {computed} from "vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    avatar_path: String,
    name: String,
    message_text: String,
    upload_time: String,
    my_message: Boolean,
    message_id: Number,
    opened_id: Number,
    opened_type: String,
    message_owner_id: Number,
    current_user_id: Number,
    menu_position: Object, // ← передається з батьківського Chat.vue
})

const emit = defineEmits(['open', 'close'])

const handleRightClick = (e) => {
    if (props.message_owner_id === props.current_user_id) {
        e.preventDefault()
        e.stopPropagation()
        emit('open', props.message_id, { x: e.clientX, y: e.clientY })
    }
}

const deleteMessage = (id) => {
    emit('close', props.message_id)

    router.delete(`/messages/${id}`, {
        preserveScroll: true,
    })
}



</script>

<template>
    <div @click.right="handleRightClick" class="relative max-w-[450px] w-full mb-4 flex flex-row items-end">
        <div
            v-if="opened_type === 'message' && opened_id === message_id"
            class="fixed z-50 bg-zinc-900 border border-zinc-700 rounded-lg shadow-lg w-54 py-1 text-sm text-white context-menu"
            :style="{ top: menu_position.y + 'px', left: menu_position.x + 'px' }"
        >
            <button @click="$emit('edit', message_id, message_text)" class="w-full text-left px-4 py-2 hover:bg-zinc-800">Редагувати повідомлення</button>
            <button @click="deleteMessage(message_id)" class="w-full text-left px-4 py-2 hover:bg-zinc-800 text-red-400 hover:text-red-300">Видалити повідомлення</button>
        </div>

        <img class="select-none w-10 h-10 rounded-full mr-2 shrink-0 border object-cover border-purple-900" :src="avatar_path" alt="Avatar" />

        <div class="flex flex-col p-3 rounded-xl w-full text-white border"
             :class="my_message ? 'bg-purple-800 border-purple-800' : 'bg-zinc-800 border-zinc-800'">
            <p class="select-none text-xs mb-1"
               :class="my_message ? 'text-purple-300' : 'text-purple-400'">{{ name }}</p>

            <p class="text-sm break-words whitespace-pre-wrap">{{ message_text }}</p>

            <div class="select-none w-full flex flex-row-reverse mt-1">
                <p class="text-xs" :class="my_message ? 'text-purple-400' : 'text-purple-500'">{{ upload_time }}</p>
            </div>
        </div>
    </div>
</template>
