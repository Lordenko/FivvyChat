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
    }
})

</script>

<template>
    <div class="flex flex-row bg-black p-3" :class="{ 'hover:bg-gray-900': isAsideOpen }">
        <img :src="avatar_path" alt="User Avatar" class=" w-15 rounded-full ">
        <div class="ml-3">
            <p class="text-[20px] text-white mb-1 items-center">{{ name }}</p>
            <p class="text-[10px] text-white">{{ last_message }}</p>
        </div>
    </div>
</template>
