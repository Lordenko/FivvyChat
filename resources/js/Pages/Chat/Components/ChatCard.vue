<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    avatar_path: {
        type: String,
        default: '/images/default_pfp.jpg'
    },
    name: {
        type: String,
        required: true
    },
    last_message: {
        type: String,
        required: false
    },
    isAsideOpen: {
        type: Boolean,
        required: true
    },
    href_chat_id: {
        type: String,
        required: true
    },
    is_active_chat: {
        type: Boolean,
        default: false
    },
    chat_id: {
        type: Number,
        required: true
    },
    notifications: {
        type: Array,
        required: true
    }
})

// реактивне обчислення кількості непрочитаних
const unreadCount = computed(() =>
    props.notifications.filter(n => n.chat_id === props.chat_id && n.pivot?.is_read === 0).length
)
</script>

<template>
    <Link
        :href="href_chat_id"
        as="button"
        class="flex flex-row bg-zinc-800 text-white p-3 w-full text-left"
        :class="{
      'hover:bg-zinc-700 hover:cursor-pointer': isAsideOpen,
      '!bg-purple-800': is_active_chat
    }"
    >
        <img :src="avatar_path" alt="User Avatar" class="w-15 rounded-full" />
        <div class="ml-3 w-0 flex-1 relative">
      <span
          v-if="unreadCount > 0"
          class="absolute -right-1 -top-1 bg-purple-600 text-white text-[12px] font-bold rounded-full min-w-[18px] size-7 flex items-center justify-center border-2 border-white/20 leading-none"
      >
        {{ unreadCount }}
      </span>

            <p class="text-[20px] text-white mb-1">{{ name }}</p>
            <p v-if="last_message" class="text-[15px] text-gray-300 truncate w-full block">
                {{ last_message }}
            </p>
        </div>
    </Link>
</template>
