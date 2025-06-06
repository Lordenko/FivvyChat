<script setup>
import {computed} from 'vue'
import {Link, router} from '@inertiajs/vue3'

const props = defineProps({
    chat_id: Number,
    current_chat_id: Number,
    opened_id: Number,
    opened_type: String,
    menu_position: Object,
    avatar_path: String,
    name: String,
    last_message: String,
    href_chat_id: String,
    is_active_chat: Boolean,
    notifications: Array,
    is_online: Boolean,
})

const emit = defineEmits(['open', 'close'])

const getAmountNotifications = (chat_id) => {
    return props.notifications.filter(
        item => item && item.chat_id === chat_id && item.pivot?.is_read !== 1
    ).length;
}

const handleRightClick = (e) => {
    e.preventDefault()
    e.stopPropagation()
    emit('open', props.chat_id, {x: e.clientX, y: e.clientY})

}

const closeChat = () => {
    console.log('test')
    router.visit(route('home'))
}

const deleteChat = () => {
    emit('close', props.chat_id)
    router.delete(`/chats/${props.chat_id}`)
}

</script>

<template>
    <div @click.right="handleRightClick" class="relative">
        <Link
            :href="href_chat_id"
            as="button"
            class="flex flex-row bg-zinc-800 text-white p-3 w-full text-left hover:bg-zinc-700 hover:cursor-pointer"
            :class="{
              '!bg-purple-800': is_active_chat
            }"
        >
            <div class="relative w-[60px] h-[60px]">
                <img :src="`/storage/${avatar_path}`" alt="User Avatar" class="w-full h-full rounded-full object-cover" />

                <span
                    v-if="is_online"
                    class="absolute bottom-0 right-0 w-[14px] h-[14px] bg-green-500 border-[2px] border-zinc-800 rounded-full"
                ></span>
            </div>


            <div class="ml-3 w-0 flex-1 relative">
                <span
                    v-if="getAmountNotifications(props.chat_id) > 0"
                    class="absolute -right-1 -top-1 bg-purple-600 text-white text-[12px] font-bold rounded-full min-w-[18px] size-7 flex items-center justify-center border-2 border-white/20 leading-none"
                >
                  {{ getAmountNotifications(props.chat_id) }}
                </span>

                <p class="text-[20px] text-white mb-1">{{ name }}</p>
                <p class="text-[15px] text-gray-300 truncate w-full block">
                    {{ last_message ?? "Немає повідомлень" }}
                </p>
            </div>
        </Link>

        <div
            v-if="opened_type === 'chat' && opened_id === chat_id"
            class="fixed z-50 bg-zinc-900 border border-zinc-700 rounded-lg shadow-lg w-36 py-1 text-sm text-white context-menu"
            :style="{ top: menu_position.y + 'px', left: menu_position.x + 'px' }"
        >

            <button v-if="current_chat_id === chat_id" @click="closeChat" class="w-full text-left px-4 py-2 hover:bg-zinc-800">Закрити чат</button>
            <button @click="deleteChat" class="w-full text-left px-4 py-2 hover:bg-zinc-800 text-red-400">Видалити чат</button>
        </div>
    </div>
</template>
