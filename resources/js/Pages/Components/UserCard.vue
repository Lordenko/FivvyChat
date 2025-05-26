<script setup>
defineProps({
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
        required: true
    },
    isAsideOpen: {
        type: Boolean,
        required: true
    },
    href_chat_id: {
        type: String,
        required: true
    }
})

</script>

<template>
    <Link class="flex flex-row bg-black p-3" :href="href_chat_id" :class="{ 'hover:bg-gray-900': isAsideOpen }">
        <img :src="avatar_path" alt="User Avatar" class=" w-15 rounded-full ">
        <div class="ml-3">
            <p class="text-[20px] text-white mb-1 items-center">{{ name }}</p>
            <p class="text-[10px] text-white">{{ last_message }}</p>
        </div>
    </Link>
</template>
