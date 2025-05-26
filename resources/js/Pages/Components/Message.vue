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
    message_text: {
        type: String,
        required: true
    },
    upload_time: {
        type: String,
        required: true
    }

})
</script>

<template>
    <div class="flex flex-row flex-end items-end mb-1">
        <img class="w-10 h-10 rounded-full mr-2" :src="avatar_path" alt="User Avatar">

        <div class="min-w-100 flex flex-col bg-blue-400 p-2 rounded-xl">
            <p class="text-gray-500 text-xs">{{ name }}</p>
            <div class="flex flex-row items-center">
                <p>{{ message_text }}</p>
            </div>
            <div class="w-full flex flex-row-reverse ">
                <p class="text-gray-500 text-xs">{{ upload_time }}</p>
            </div>
        </div>
    </div>
</template>
