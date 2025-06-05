<script setup>
import {ref, onMounted, onUnmounted, nextTick, watch, watchEffect} from 'vue'
import {router, usePage, useRemember} from '@inertiajs/vue3'
import ChatCard from './Components/Cards/ChatCard.vue'
import UserCard from './Components/Cards/UserCard.vue'
import Message from './Components/Message.vue'
import SendMessageInput from './Components/Forms/SendMessageInput.vue'
import LogoutButton from "./Components/Buttons/LogoutButton.vue";
import ProfileButton from "./Components/Buttons/ProfileButton.vue";
import Profile from './Profile.vue'
import { useOnlineStore } from '@/Stores/useOnlineStore'

const onlineStore = useOnlineStore()

const current_user = ref(usePage().props.auth.user)

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

const isOpenProfile = ref(false)

const notificationsList = ref([...props.notifications ?? []])
const messagesList = ref([...props.messages ?? []])
const chatsList = ref(props.chats)
const messagesContainer = ref(null)
const isSettingsOpen = ref(false)
const isChatMenuOpen = ref(false)
const isMobile = ref(false)
const searchButtonActivity = ref(false)
const searchButtonText = ref('')
const usersOnline = ref([])
let searchUserList = ref([])
const onlineMap = ref({})
const leaveTimeouts = {}

const getUserName = id => props.users.find(u => u.id === id)?.name ?? `User #${id}`

const getUserAvatarPath = id => {
    const user = props.users.find(u => u.id === id)
    return user?.avatar_path ? `/storage/${user.avatar_path}` : '/images/default_pfp.jpg'
}

const getChatName = chat => chat.type === 'direct'
    ? chat.users.find(user => user.id !== current_user.value.id)?.name
    : chat.name

const getAvatarGroup = chat => chat.type === 'direct'
    ? chat.users.find(user => user.id !== current_user.value.id)?.avatar_path
    : chat.avatar_path

const getDirectChatUserId = chat => chat.type === 'direct'
    ? chat.users.find(user => user.id !== current_user.value.id)?.id
    : -1

const getDirectChatIsOnline = chat => {
    const directUserId = getDirectChatUserId(chat)
    return onlineStore.isOnline(directUserId)
}

console.log(usersOnline.value)

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

watch(searchButtonText, (newValue) => {
    searchUserList.value = newValue ? findUsersByName(props.users, newValue) : []
})

watch(isChatMenuOpen, val => {
    document.body.style.overflow = val ? 'hidden' : ''
})

onMounted(() => {
    console.log(`chatid - ${props.chat_id}`)

    handleResize()
    window.addEventListener('resize', handleResize)

    if (props.chat_id != null) {
        subscribeToChatChannels(props.chat_id)
    }

    if (messagesList) scrollToBottom('auto')

    window.Echo.private(`NewChat.${current_user.value.id}`)
        .listen('ChatCreate', e => {
            chatsList.value = [...chatsList.value, e.chat]

            searchButtonActivity.value = false
            searchButtonText.value = null
        })

    window.Echo.private(`ChatDeleteChannel.${current_user.value.id}`)
        .listen('ChatDelete', e => {
            const index_chat =  chatsList.value.findIndex(item => item.id === e.chat.id)
            chatsList.value.splice(index_chat, 1)

            if (e.chat.id !== props.chat_id) {
                router.visit(route('home'))
            }
        })

    window.Echo.private(`Notification.${current_user.value.id}`)
        .listen('NotificationSent', e => {
            if (props.chat_id !== e.notification.chat_id) {
                notificationsList.value.push(e.notification)
            }

            const chatIndex = chatsList.value.findIndex(chat => chat.id === e.message.chat_id)
            if (chatIndex !== -1) {
                chatsList.value[chatIndex] = {
                    ...chatsList.value[chatIndex],
                    last_message: e.message.message_text
                }
            }
        })

    window.Echo.private(`ProfileChangeChannel.${current_user.value.id}`)
        .listen('ProfileChange', e => {
            current_user.value = usePage().props.auth?.user

        })

    window.Echo.join('Presence.users')
        .here(users => {
            users.forEach(user => {
                onlineStore.setOnline(user.id)
            })
        })
        .joining(user => {
            onlineStore.setOnline(user.id)
        })
        .leaving(user => {
            onlineStore.setOfflineWithDelay(user.id)
        })

})

const isUserOnline = (userId) => !!onlineMap.value[userId]


const subscribeToChatChannels = (chatId) => {
    if (!chatId) return

    // Підписка на повідомлення
    window.Echo.private(`Chat.${chatId}`)
        .listen('MessageSent', e => {
            messagesList.value = [...messagesList.value, e.message]

            if (e.message.user_id !== current_user.value.id) {
                if (chatId === e.message.chat_id) {
                    axios.post('/notifications/read', { chat_id: e.message.chat_id })
                } else {
                    notificationsList.value.push(e.notification)
                }
            }

            scrollToBottom('smooth')

            const chatIndex = chatsList.value.findIndex(chat => chat.id === chatId)
            if (chatIndex !== -1) {
                chatsList.value[chatIndex] = {
                    ...chatsList.value[chatIndex],
                    last_message: e.message.message_text
                }
            }
        })

    // Підписка на видалення повідомлень
    window.Echo.private(`MessageDeleteChannel.${chatId}`)
        .listen('MessageDelete', e => {
            const index = messagesList.value.findIndex(msg => msg.id === e.message_id)
            if (index !== -1) messagesList.value.splice(index, 1)
        })

    // Підписка на редагування повідомлень
    window.Echo.private(`MessageEditChannel.${chatId}`)
        .listen('MessageEdit', e => {
            const index = messagesList.value.findIndex(msg => msg.id === e.message.id)
            if (index !== -1) messagesList.value[index].message_text = e.message.message_text
        })

    // Очищення сповіщень
    notificationsList.value = notificationsList.value.filter(n => n.chat_id !== chatId)
}


watch(() => props.chat_id, (newChatId, oldChatId) => {
    if (oldChatId) {
        window.Echo.leave(`private-Chat.${oldChatId}`)
        window.Echo.leave(`private-MessageDeleteChannel.${oldChatId}`)
        window.Echo.leave(`private-MessageEditChannel.${oldChatId}`)
    }

    if (newChatId) {
        subscribeToChatChannels(newChatId)
    }
})

onUnmounted(() => {
    window.removeEventListener('resize', handleResize)
    console.log(`onUnmounted chatid - ${props.chat_id}`)
})

const settingsRef = ref(null)
const settingsButtonRef = ref(null)

const handleClickOutside = (event) => {
    const clickedOutsideSettings = settingsRef.value && !settingsRef.value.contains(event.target)
    const clickedOutsideButton = settingsButtonRef.value && !settingsButtonRef.value.contains(event.target)

    if (isSettingsOpen.value && clickedOutsideSettings && clickedOutsideButton) {
        isSettingsOpen.value = false
    }
}

const handleGlobalClick = (e) => {
    const menu = document.querySelector('.context-menu')
    if (menu && !menu.contains(e.target)) {
        closeContextMenu()
    }
}

onMounted(() => {
    document.addEventListener('click', handleGlobalClick)
    document.addEventListener('click', handleClickOutside)

})

onUnmounted(() => {
    document.removeEventListener('click', handleGlobalClick)
    document.removeEventListener('click', handleClickOutside)
})


const contextMenu = ref({
    type: null, // 'chat' | 'message' | null
    id: null,
    position: {x: 0, y: 0}
})

// Функція для відкриття меню
const openContextMenu = (type, id, position) => {
    contextMenu.value = {
        type,
        id,
        position
    }
}

// Функція для закриття меню
const closeContextMenu = () => {
    contextMenu.value = {
        type: null,
        id: null,
        position: {x: 0, y: 0}
    }
}

const isMessageEdit = ref(false)
const messageBeingEdited = ref({id: null, text: ''})

const startEditMessage = (id, text) => {
    isMessageEdit.value = true
    messageBeingEdited.value = {id, text}
    closeContextMenu()
}

const submitEdit = () => {
    if (!messageBeingEdited.value.text.trim()) return

    router.put(`/messages/${messageBeingEdited.value.id}`, {
        message_text: messageBeingEdited.value.text
    }, {
        preserveScroll: true,
        onSuccess: () => {
            isMessageEdit.value = false
            messageBeingEdited.value = { id: null, text: '' }
        }
    })
}

watch(() => props.chat_id, (newChatId) => {
    if (newChatId && Array.isArray(props.messages)) {
        messagesList.value = [...props.messages]
        nextTick(() => scrollToBottom('auto'))
    } else {
        messagesList.value = []
    }
})

</script>

<template>
    <Head title="Chat"/>

    <Profile v-if="isOpenProfile" v-model:user="current_user" v-model:isOpen="isOpenProfile"/>

    <div :class="['relative h-screen bg-zinc-900', isMobile ? 'block' : 'grid md:grid-cols-[300px_1fr]']">

        <button v-if="isMobile" @click="toggleChatMenu"
                class="md:hidden fixed top-2 left-2 z-20 bg-purple-700 text-white px-3 py-1 rounded shadow-lg">
            ☰
        </button>

        <div :class="[
      'bg-zinc-800 border-r border-zinc-700 flex flex-col transition-transform duration-300',
      isChatMenuOpen ? 'fixed inset-0 z-38 w-full h-screen overflow-y-auto' : 'hidden',
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
                    class="w-full border-zinc-700 text-purple-300 bg-zinc-900 h-10 py-2 px-3 leading-5 border rounded-2xl focus:outline-none text-center focus:ring-0"
                    placeholder="Введіть ім'я користувача"
                />
            </div>

            <div class="flex-1 min-h-0 overflow-y-auto scrollbar-hidden">
                <div v-if="searchButtonActivity">
                    <div v-for="user in searchUserList" :key="user.id">
                        <UserCard :data="{ user_ids: [user.id, current_user.id], type:'direct' }"
                                  :user="user"
                                  :current_user="current_user"
                                  v-if="user.id !== current_user.id"
                                  @click="() => { searchButtonActivity = false; searchButtonText = null; }"/>
                    </div>
                </div>
                <div v-else>
                    <ChatCard
                        v-for="chat in chatsList" :key="chat.id"
                        :name="getChatName(chat)"
                        :href_chat_id="`/chat/${chat.id}`"
                        :avatar_path="getAvatarGroup(chat)"
                        :is_active_chat="chat.id === props.chat_id"
                        :last_message="chat.last_message"
                        :chat_id="chat.id"
                        :current_chat_id="chat_id"
                        :notifications="notificationsList"
                        :opened_id="contextMenu.id"
                        :opened_type="contextMenu.type"
                        :menu_position="contextMenu.position"
                        :is_online="getDirectChatIsOnline(chat)"
                        @open="(id, pos) => openContextMenu('chat', id, pos)"
                        @close="closeContextMenu"
                    />
                </div>
            </div>

            <div class="relative select-none">
                <!-- Кнопка ⚙️ -->
                <div
                    @click="toggleSettings"
                    ref="settingsButtonRef"
                    class="flex-shrink-0 flex items-center justify-between px-4 py-4 h-[60px] text-zinc-400 text-sm border-t border-zinc-700 hover:bg-zinc-700/50 cursor-pointer transition"
                >
                    <div class="flex items-center gap-2">
                        <img :src="`/storage/${current_user.avatar_path}`" alt="Avatar"
                             class="w-6 h-6 rounded-full select-none"/>
                        <p class="font-medium text-white truncate max-w-[120px]">{{ current_user.name }}</p>
                    </div>
                    <div class="text-xl transform transition-transform duration-200"
                         :class="{ 'rotate-90': isSettingsOpen }">
                        ⚙️
                    </div>
                </div>

                <!-- Випадаюче меню -->
                <Transition name="fade" class="select-none">
                    <div
                        v-if="isSettingsOpen"
                        ref="settingsRef"
                        class="absolute bottom-full mb-2 left-2 right-2 bg-zinc-900 border border-zinc-700 px-4 py-3 text-sm text-white shadow-xl rounded-lg"
                    >
                        <p class="mb-2">
                            <ProfileButton @click="() => { isOpenProfile = true; isSettingsOpen = false }"/>
                        </p>
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
                    :message_id="message.id"
                    :avatar_path="getUserAvatarPath(message.user_id)"
                    @load="scrollToBottom('auto')"
                    :name="getUserName(message.user_id)"
                    :message_text="message.message_text"
                    :message_owner_id="message.user_id"
                    :current_user_id="current_user.id"
                    :upload_time="formatTime(message.created_at)"
                    :my_message="message.user_id === current_user.id"
                    :opened_id="contextMenu.id"
                    :opened_type="contextMenu.type"
                    :menu_position="contextMenu.position"
                    @edit="startEditMessage"
                    @open="(id, pos) => openContextMenu('message', id, pos)"
                    @close="closeContextMenu"
                />
            </div>

            <div v-else-if="messagesList && props.chat_id"
                 class="flex flex-1 flex-col p-3 full justify-center items-center">
                <p class="text-4xl text-purple-300 select-none">Відправте ваше перше повідомлення</p>
            </div>

            <div v-else class="flex flex-1 full items-center justify-center">
                <div class="text-4xl text-purple-300 select-none">Оберіть або почніть новий чат</div>
            </div>

            <SendMessageInput
                v-if="messagesList && props.chat_id && !isMessageEdit"
                :user_id="current_user.id"
                :chat_id="props.chat_id"
            />

            <div v-else-if="messagesList && props.chat_id && isMessageEdit" class="p-3">
                <form @submit.prevent="submitEdit">
        <textarea
            v-model="messageBeingEdited.text"
            class="w-full h-24 p-2 border rounded bg-zinc-800 text-white resize-none focus:outline-none outline-none focus:ring-0 ring-0 border-transparent focus:border-transparent"
        ></textarea>
                    <div class="flex justify-end gap-2 mt-2">
                        <button
                            type="button"
                            @click="isMessageEdit = false"
                            class="px-3 py-1 text-white bg-zinc-700 rounded hover:bg-zinc-600"
                        >Відмінити
                        </button>
                        <button
                            type="submit"
                            class="px-3 py-1 text-white bg-purple-700 rounded hover:bg-purple-600"
                        >Зберегти
                        </button>
                    </div>
                </form>
            </div>
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
