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
    },
    my_message: {
        type: Boolean,
        required: true
    }
})
</script>

<template>
    <div class="flex flex-row items-end mb-4 max-w-[450px] w-full">
        <img class="select-none w-10 h-10 rounded-full mr-2 shrink-0 border border-purple-900" :src="avatar_path"
             alt="User Avatar"
        />

        <div class="flex flex-col  p-3 rounded-xl w-full max-w-full overflow-hidden text-white border"
             :class="my_message ? 'bg-purple-800 border-purple-800' : 'bg-zinc-800 border-zinc-800'"
        >
            <p class="select-none text-xs mb-1"
               :class="my_message ? 'text-purple-300 ' : 'text-purple-400 '"
            >{{ name }}</p>
            <p class="text-sm break-words whitespace-pre-wrap w-full overflow-hidden">
                {{ message_text }}
            </p>
            <div class="select-none w-full flex flex-row-reverse mt-1">
                <p class="text-xs"
                   :class="my_message ? 'text-purple-400' : 'text-purple-500'"
                >{{ upload_time }}</p>
            </div>
        </div>
    </div>
</template>
