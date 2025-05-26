<script setup>
import {ref, onMounted} from 'vue'
import AsideProfile from '../Components/AsideProfile.vue';
import UserCard from '../Components/UserCard.vue';
import Message from '../Components/Message.vue';
import SendMessageInput from '../Components/Forms/SendMessageInput.vue';
import {router, usePage} from '@inertiajs/vue3'

const hash = ref('')

const page = usePage()
const current_user = page.props.auth.user

const props = defineProps({
    chat_id: Number,
    users: Array,
    chats: Array,
    messages: {
        type: Array,
        default: null
    }
})

const isAsideOpen = ref(false)

function getUserName(id) {
    return props.users.find(u => u.id === id)?.name ?? `User #${id}`
}

function getChatName(chat) {
    if (chat.type === 'direct') {
        return chat.users.find(user => user.id !== current_user.id).name;
    } else if (chat.type === 'group') {
        return chat.name;
    }
}

function formatTime(dateString) {
    const date = new Date(dateString)
    let hours = date.getHours()
    const minutes = date.getMinutes().toString().padStart(2, '0')
    const ampm = hours >= 12 ? 'pm' : 'am'
    hours = hours % 12 || 12
    return `${hours}:${minutes}${ampm}`
}

</script>

<template>

    <Head title="Chat"/>

    <AsideProfile v-model:open="isAsideOpen"/>

    <div class="h-full grid grid-cols-[68px_300px_1fr]">
        <div class="bg-blue-200"></div>


        <div class="bg-green-200">
            <div v-for="chat in chats" :key="chat.id">
                <UserCard :name="getChatName(chat)" :href_chat_id="`/chat/${chat.id}`" last_message="U'r best!!!"
                          :isAsideOpen="!isAsideOpen"/>
            </div>

            <div v-for="user in users" :key="user.id">
                <Link
                    :href="route('chats.store')"
                    method="post"
                    :data="{ user_ids: [user.id, current_user.id], type:'direct' }"
                    as="button"
                    v-if="user.id !== current_user.id"
                >
                    {{ user.name }}
                </Link>
            </div>
        </div>

        <div v-if="messages" class="h-screen flex flex-col">
            <div class="flex-1 overflow-y-auto p-3 overflow-y-auto scrollbar-hidden">
                <Message
                    v-for="message in messages"
                    :key="message.id"
                    :name="getUserName(message.user_id)"
                    :message_text="message.message_text"
                    :upload_time="formatTime(message.created_at)"
                />
            </div>

            <SendMessageInput :user_id="current_user.id" :chat_id="chat_id"/>
        </div>


        <div v-else>

        </div>

    </div>

</template>
