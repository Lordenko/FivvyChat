<script setup>
import {computed} from 'vue'
import {Link} from '@inertiajs/vue3'

const props = defineProps({
    chat_id: Number,
    opened_id: Number,
    opened_type: String,
    menu_position: Object,
    avatar_path: String,
    name: String,
    last_message: String,
    href_chat_id: String,
    is_active_chat: Boolean,
    notifications: Array,
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
            <img :src="`/storage/${avatar_path}`" alt="User Avatar" class="w-15 rounded-full"/>
            <div class="ml-3 w-0 flex-1 relative">
                <span
                    v-if="getAmountNotifications(props.chat_id) > 0"
                    class="absolute -right-1 -top-1 bg-purple-600 text-white text-[12px] font-bold rounded-full min-w-[18px] size-7 flex items-center justify-center border-2 border-white/20 leading-none"
                >
                  {{ getAmountNotifications(props.chat_id) }}
                </span>

                <p class="text-[20px] text-white mb-1">{{ name }}</p>
                <p class="text-[15px] text-gray-300 truncate w-full block">
                    {{ last_message ?? "No messages" }}
                </p>
            </div>
        </Link>

        <div
            v-if="opened_type === 'chat' && opened_id === chat_id"
            class="fixed z-50 bg-zinc-900 border border-zinc-700 rounded-lg shadow-lg w-36 py-1 text-sm text-white context-menu"
            :style="{ top: menu_position.y + 'px', left: menu_position.x + 'px' }"
        >

            <button class="w-full text-left px-4 py-2 hover:bg-zinc-800">Rename</button>
            <button class="w-full text-left px-4 py-2 hover:bg-zinc-800 text-red-400">Delete</button>
        </div>
    </div>
</template>
