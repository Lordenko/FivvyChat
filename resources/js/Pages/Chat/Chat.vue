<script setup>
import {ref, onMounted, onUnmounted, nextTick, watch, watchEffect} from 'vue'
import {router, usePage, useRemember} from '@inertiajs/vue3'
import AsideProfile from '../Components/AsideProfile.vue'
import ChatCard from './Components/ChatCard.vue'
import UserCard from './Components/UserCard.vue'
import Message from '../Components/Message.vue'
import SendMessageInput from '../Components/Forms/SendMessageInput.vue'
import LogoutButton from "@/Pages/Components/LogoutButton.vue";

const page = usePage()
const current_user = page.props.auth.user

const props = defineProps({
    avatar_path: {type: String, default: '/images/default_pfp.jpg'},
    chat_id: {type: Number, default: null},
    users: Array,
    chats: Array,
    messages: {type: Array, default: () => []},
    notifications: {type: Array, default: () => []},
    errors: Object,
    appName: String,
    auth: Object,
})

const notificationsList = ref(Array.isArray(props.notifications) ? props.notifications : [])
const messagesList = ref([...props.messages ?? []])
const chatsList = ref(props.chats)
const messagesContainer = ref(null)
const isSettingsOpen = ref(false)
const isChatMenuOpen = ref(false)
const isMobile = ref(false)
const searchButtonActivity = ref(false)
const searchButtonText = ref('')
let searchUserList = ref([])
const refreshKey = ref(0)


const getUserName = id => props.users.find(u => u.id === id)?.name ?? `User #${id}`
const getChatName = chat => chat.type === 'direct'
    ? chat.users.find(user => user.id !== current_user.id)?.name
    : chat.name

const formatTime = dateString => {
    const date = new Date(dateString)
    let hours = date.getHours()
    const minutes = date.getMinutes().toString().padStart(2, '0')
    const ampm = hours >= 12 ? 'pm' : 'am'
    hours = hours % 12 || 12
    return `${hours}:${minutes}${ampm}`
}

const scrollToBottom = behavior => {
    if (messagesContainer.value) {
        nextTick(() => messagesContainer.value.scrollTo({
            top: messagesContainer.value.scrollHeight,
            behavior
        }))
    }
}

const handleResize = () => {
    isMobile.value = window.innerWidth < 768
}

const toggleSettings = () => isSettingsOpen.value = !isSettingsOpen.value
const toggleChatMenu = () => isChatMenuOpen.value = !isChatMenuOpen.value

const findUsersByName = (users, partialName) => {
    const query = partialName.toLowerCase().trim()
    return users.filter(user => user.name.toLowerCase().includes(query))
}

const getAmountNotifications = (chat_id) => {
    return notificationsList.value.filter(
        n => n.chat_id === chat_id && n.pivot?.is_read === 0
    ).length;
};

watch(searchButtonText, (newValue) => {
    searchUserList.value = newValue ? findUsersByName(props.users, newValue) : []
})

watch(isChatMenuOpen, val => {
    document.body.style.overflow = val ? 'hidden' : ''
})

onMounted(() => {
    handleResize()
    window.addEventListener('resize', handleResize)

    if (messagesList) scrollToBottom('auto')

    if (props.chat_id != null) {
        window.Echo.private(`Chat.${props.chat_id}`)
            .listen('MessageSent', e => {
                messagesList.value.push(e.message)

                notificationsList.value = notificationsList.value.some(n => n.id === e.notification.id)
                    ? notificationsList.value.map(n => n.id === e.notification.id ? { ...e.notification } : n)
                    : [...notificationsList.value, e.notification]

                scrollToBottom('smooth')

                const chatIndex = chatsList.value.findIndex(chat => chat.id === props.chat_id)
                if (chatIndex !== -1) {
                    chatsList.value[chatIndex] = {
                        ...chatsList.value[chatIndex],
                        last_message: e.message.message_text
                    }
                }
            })

    }

    window.Echo.private(`NewChat.${current_user.id}`)
        .listen('ChatCreate', e => {
            setTimeout(() => chatsList.value.push(e.chat), 250)
            searchButtonActivity.value = false
            searchButtonText.value = null
        })
})

onUnmounted(() => window.removeEventListener('resize', handleResize))




</script>

<template>
    <Head title="Chat"/>
    <div :class="['relative h-screen bg-zinc-900', isMobile ? 'block' : 'grid md:grid-cols-[300px_1fr]']">

        <button v-if="isMobile" @click="toggleChatMenu"
                class="md:hidden fixed top-2 left-2 z-50 bg-purple-700 text-white px-3 py-1 rounded shadow-lg">
            ☰
        </button>

        <div :class="[
      'bg-zinc-800 border-r border-zinc-700 flex flex-col transition-transform duration-300',
      isChatMenuOpen ? 'fixed inset-0 z-50 w-full h-screen overflow-y-auto' : 'hidden',
      'md:static md:flex md:w-[300px] md:h-screen relative'
    ]">

            <div class="flex items-center justify-between px-4 py-3 border-b border-zinc-700 md:hidden">
                <p class="text-white text-lg font-bold">Chats</p>
                <button class="text-white text-2xl" @click="toggleChatMenu">×</button>
            </div>

            <div class="px-3 py-2 border-b border-zinc-700">
                <input
                    v-model="searchButtonText"
                    @focus="searchButtonActivity = true"
                    class="w-full border-zinc-700 text-purple-300 bg-zinc-900 h-10 py-2 px-3 leading-5 border rounded-2xl focus:outline-none focus:ring-0"
                    placeholder="Enter a username for search him..."
                />
            </div>

            <div class="flex-1 min-h-0 overflow-y-auto scrollbar-hidden">
                <div v-if="searchButtonActivity">
                    <div v-for="user in searchUserList" :key="user.id">
                        <UserCard :data="{ user_ids: [user.id, current_user.id], type:'direct' }"
                                  :user="user"
                                  :current_user="current_user"
                                  v-if="user.id !== current_user.id"
                                  @click="() => { searchButtonActivity = false; searchButtonText = null; }" />
                    </div>
                </div>
                <div v-else>
                    <div v-for="chat in chatsList" :key="chat.id">
                        <ChatCard
                            :name="getChatName(chat)"
                            :href_chat_id="`/chat/${chat.id}`"
                            :isAsideOpen="!isChatMenuOpen"
                            :is_active_chat="chat.id === props.chat_id"
                            :last_message="chat.last_message"
                            :chat_id="chat.id"
                            :notifications="notificationsList"
                        />
                    </div>
                </div>
            </div>

            <div class="relative select-none">
                <div @click="toggleSettings"
                     class="flex-shrink-0 flex items-center justify-between px-4 py-3 text-zinc-400 text-sm border-t border-zinc-700 hover:bg-zinc-700/50 cursor-pointer transition">
                    <div class="flex items-center gap-2">
                        <img :src="props.avatar_path" alt="Avatar" class="w-6 h-6 rounded-full select-none"/>
                        <p class="font-medium text-white truncate max-w-[120px]">{{ current_user.name }}</p>
                    </div>
                    <div class="text-xl transform transition-transform duration-200"
                         :class="{ 'rotate-90': isSettingsOpen }">
                        ⚙️
                    </div>
                </div>
                <Transition name="fade" class="select-none">
                    <div v-if="isSettingsOpen"
                         class="absolute bottom-full mb-2 left-2 right-2 bg-zinc-900 border border-zinc-700 px-4 py-3 text-sm text-white shadow-xl rounded-lg">
                        <p class="mb-2">👤 Профіль</p>
                        <p class="mb-2">🔒 Змінити пароль</p>
                        <p class="mb-2">🌙 Темна тема</p>
                        <p class="hover:text-red-400 cursor-pointer">
                            <LogoutButton/>
                        </p>
                    </div>
                </Transition>
            </div>
        </div>

        <div v-if="!isMobile || !isChatMenuOpen" @click="searchButtonActivity = false" class="h-screen flex flex-col">
            <div v-if="messagesList.length" ref="messagesContainer"
                 class="flex-1 p-3 overflow-y-auto scrollbar-hidden">
                <Message
                    v-for="message in messagesList"
                    :key="message.id"
                    :name="getUserName(message.user_id)"
                    :message_text="message.message_text"
                    :upload_time="formatTime(message.created_at)"
                    :my_message="message.user_id === current_user.id"
                    @load="scrollToBottom('auto')"
                />
            </div>

            <div v-else-if="messagesList" class="flex flex-1 flex-col p-3 full justify-center items-center">
                <p class="text-4xl text-purple-300">Send your first message</p>
            </div>

            <div v-else class="flex flex-1 full items-center justify-center">
                <div class="text-4xl text-purple-300">Start or select any chat</div>
            </div>

            <SendMessageInput
                v-if="messagesList && props.chat_id"
                :user_id="current_user.id"
                :chat_id="props.chat_id"
            />
        </div>

    </div>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from, .fade-leave-to {
    opacity: 0;
}
</style>
